<?php

namespace Tests\Feature;

use App\Models\BumdesUnitTransaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BumdesAgentTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_unit_and_record_transaction(): void
    {
        $admin = User::factory()->create(['village_name' => 'Desa Maju']);

        $unitId = $this->actingAs($admin)
            ->postJson('/bumdes/units', [
                'name' => 'Wardes Maju',
                'category' => 'wardes',
                'village_name' => 'Desa Maju',
                'pades_percentage' => 25,
            ])
            ->assertCreated()
            ->assertJsonPath('success', true)
            ->assertJsonPath('summary.kontribusi_pades', '25,0%')
            ->json('unit_id');

        $this->actingAs($admin)
            ->postJson('/bumdes/transactions', [
                'unit_id' => $unitId,
                'type' => 'income',
                'amount' => 5000000,
                'transaction_date' => now()->toDateString(),
                'description' => 'Penjualan wardes',
            ])
            ->assertCreated()
            ->assertJsonPath('summary.nominal', 'Rp 5.000.000');
    }

    public function test_consolidated_report_calculates_pades_from_net_profit(): void
    {
        $admin = User::factory()->create(['village_name' => 'Desa Maju']);
        $unitId = $this->createUnit($admin);

        $this->createTransaction($admin, $unitId, 'income', 5000000);
        $this->createTransaction($admin, $unitId, 'expense', 3000000);

        $this->actingAs($admin)
            ->postJson('/bumdes/reports/consolidation', [
                'village_name' => 'Desa Maju',
                'period' => now()->format('Y-m'),
            ])
            ->assertOk()
            ->assertJsonPath('total_pemasukan', 'Rp 5.000.000')
            ->assertJsonPath('total_pengeluaran', 'Rp 3.000.000')
            ->assertJsonPath('laba_bersih', 'Rp 2.000.000')
            ->assertJsonPath('kontribusi_pades', 'Rp 500.000');
    }

    public function test_transaction_is_never_deleted_and_uses_reversal(): void
    {
        $admin = User::factory()->create(['village_name' => 'Desa Maju']);
        $unitId = $this->createUnit($admin);
        $transactionId = $this->createTransaction($admin, $unitId, 'income', 1000000);

        $this->actingAs($admin)
            ->postJson('/bumdes/reversal', [
                'transaction_id' => $transactionId,
                'reason' => 'Salah input nominal.',
            ])
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('message', 'Transaksi dibalik melalui reversal. Data asli tidak dihapus.');

        $this->assertDatabaseHas('bumdes_unit_transactions', ['id' => $transactionId, 'status' => 'reversed']);
        $this->assertDatabaseHas('bumdes_unit_transactions', ['reversal_of_id' => $transactionId, 'type' => 'expense']);
        $this->assertEquals(2, BumdesUnitTransaction::count());
    }

    public function test_annual_report_uses_rat_format(): void
    {
        $admin = User::factory()->create(['village_name' => 'Desa Maju']);
        $unitId = $this->createUnit($admin);
        $this->createTransaction($admin, $unitId, 'income', 4000000);

        $this->actingAs($admin)
            ->postJson('/bumdes/reports/annual', [
                'village_name' => 'Desa Maju',
                'year' => now()->year,
            ])
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('format', 'Format ringkas mengacu Permendesa No. 3/2021 untuk laporan tahunan dan RAT.')
            ->assertJsonPath('bab_laporan.laporan_keuangan_konsolidasi.total_pemasukan', 'Rp 4.000.000');
    }

    public function test_unit_financial_report_includes_balance_categories_pades_and_month_comparison(): void
    {
        $admin = User::factory()->create(['village_name' => 'Desa Maju']);
        $unitId = $this->createUnit($admin);

        $this->createTransaction($admin, $unitId, 'income', 2000000, now()->subMonth(), 'penjualan');
        $this->createTransaction($admin, $unitId, 'expense', 500000, now()->subMonth(), 'operasional');
        $this->createTransaction($admin, $unitId, 'income', 5000000, now(), 'penjualan');
        $this->createTransaction($admin, $unitId, 'income', 1000000, now(), 'sewa');
        $this->createTransaction($admin, $unitId, 'expense', 3000000, now(), 'belanja_stok');

        $this->actingAs($admin)
            ->postJson('/bumdes/reports/unit-finance', [
                'bumdes_name' => 'BUMDes Maju Bersama',
                'unit_name' => 'Wardes Maju',
                'month' => now()->month,
                'year' => now()->year,
                'pades_percentage' => 25,
            ])
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('bumdes', 'BUMDes Maju Bersama')
            ->assertJsonPath('saldo_awal', 'Rp 1.500.000')
            ->assertJsonPath('total_pemasukan_per_kategori.penjualan', 'Rp 5.000.000')
            ->assertJsonPath('total_pemasukan_per_kategori.sewa', 'Rp 1.000.000')
            ->assertJsonPath('total_pengeluaran_per_kategori.belanja_stok', 'Rp 3.000.000')
            ->assertJsonPath('laba_rugi_bersih', 'Rp 3.000.000')
            ->assertJsonPath('kontribusi_pades.nominal', 'Rp 750.000')
            ->assertJsonPath('saldo_akhir', 'Rp 3.750.000')
            ->assertJsonPath('perbandingan_vs_bulan_lalu.bulan_lalu', 'Rp 1.500.000')
            ->assertJsonPath('perbandingan_vs_bulan_lalu.status', 'naik ↑ 100,0%');
    }

    private function createUnit(User $admin): int
    {
        return $this->actingAs($admin)
            ->postJson('/bumdes/units', [
                'name' => 'Wardes Maju',
                'category' => 'wardes',
                'village_name' => 'Desa Maju',
                'pades_percentage' => 25,
            ])
            ->assertCreated()
            ->json('unit_id');
    }

    private function createTransaction(User $admin, int $unitId, string $type, int $amount, mixed $date = null, string $category = 'umum'): int
    {
        $date ??= now();

        return $this->actingAs($admin)
            ->postJson('/bumdes/transactions', [
                'unit_id' => $unitId,
                'type' => $type,
                'category' => $category,
                'amount' => $amount,
                'transaction_date' => $date->toDateString(),
                'description' => 'Transaksi demo',
            ])
            ->assertCreated()
            ->json('transaction_id');
    }
}
