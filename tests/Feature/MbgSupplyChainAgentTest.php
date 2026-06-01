<?php

namespace Tests\Feature;

use App\Models\MbgOrder;
use App\Models\MbgSupplierConfirmation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MbgSupplyChainAgentTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_mbg_order_and_notify_suppliers(): void
    {
        $admin = User::factory()->create();
        $orderId = $this->createOrder($admin);

        $this->actingAs($admin)
            ->postJson('/mbg/orders/'.$orderId.'/suppliers/notify', [
                'suppliers' => [
                    [
                        'supplier_name' => 'Petani Makmur',
                        'supplier_type' => 'desa_sendiri',
                        'product_name' => 'Beras',
                        'quantity' => 100,
                        'unit' => 'kg',
                        'delivery_date' => now()->addDays(3)->toDateString(),
                        'estimated_unit_price' => 12000,
                    ],
                ],
            ])
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('checklist.supplier_dinotifikasi', true)
            ->assertJsonPath('estimasi.total_biaya', 'Rp 1.200.000');

        $this->assertDatabaseHas('mbg_supplier_confirmations', [
            'supplier_name' => 'Petani Makmur',
            'status' => 'menunggu_konfirmasi',
        ]);
        $this->assertDatabaseHas('mbg_notifications', ['channel' => 'sms']);
    }

    public function test_external_supplier_requires_admin_approval_and_risk_flag(): void
    {
        $admin = User::factory()->create();
        $orderId = $this->createOrder($admin);

        $this->actingAs($admin)
            ->postJson('/mbg/orders/'.$orderId.'/suppliers/notify', [
                'suppliers' => [
                    [
                        'supplier_name' => 'Supplier Luar Kota',
                        'supplier_type' => 'supplier_luar',
                        'product_name' => 'Telur',
                        'quantity' => 50,
                        'unit' => 'kg',
                        'delivery_date' => now()->addDays(2)->toDateString(),
                        'estimated_unit_price' => 28000,
                    ],
                ],
            ])
            ->assertOk()
            ->assertJsonPath('risiko_keterlambatan', 'ada')
            ->assertJsonPath('suppliers.0.risk_flag', true);

        $this->assertDatabaseHas('mbg_supplier_confirmations', [
            'supplier_name' => 'Supplier Luar Kota',
            'admin_approval_required' => true,
        ]);
    }

    public function test_supplier_confirmation_records_financial_transaction(): void
    {
        $admin = User::factory()->create();
        $confirmationId = $this->createSupplierConfirmation($admin);

        $this->actingAs($admin)
            ->postJson('/mbg/suppliers/'.$confirmationId.'/confirm', ['can_fulfill' => true])
            ->assertOk()
            ->assertJsonPath('checklist.transaksi_keuangan_dicatat', true)
            ->assertJsonPath('summary.status', 'dikonfirmasi');

        $this->assertDatabaseHas('mbg_financial_transactions', [
            'supplier_confirmation_id' => $confirmationId,
            'supplier_name' => 'Petani Makmur',
        ]);
    }

    public function test_supplier_failure_flags_admin_and_creates_incident(): void
    {
        $admin = User::factory()->create();
        $confirmationId = $this->createSupplierConfirmation($admin);

        $this->actingAs($admin)
            ->postJson('/mbg/suppliers/'.$confirmationId.'/fail', ['reason' => 'Stok beras gagal panen.'])
            ->assertOk()
            ->assertJsonPath('checklist.butuh_supplier_pengganti', true);

        $this->assertDatabaseHas('mbg_incidents', ['type' => 'supplier_gagal']);
        $this->assertDatabaseHas('mbg_notifications', ['recipient_type' => 'admin_desa']);
    }

    public function test_deadline_processing_sends_reminder_and_flags_late_supplier(): void
    {
        $admin = User::factory()->create();
        $confirmationId = $this->createSupplierConfirmation($admin);
        MbgSupplierConfirmation::whereKey($confirmationId)->update([
            'reminder_due_at' => now()->subHour(),
            'confirmation_due_at' => now()->subMinute(),
        ]);

        $this->actingAs($admin)
            ->postJson('/mbg/suppliers/process-deadlines')
            ->assertOk()
            ->assertJsonPath('flag_admin', 1);

        $this->assertDatabaseHas('mbg_incidents', ['type' => 'konfirmasi_terlambat']);
    }

    public function test_distribution_and_monthly_report_are_ready_for_dinas(): void
    {
        $admin = User::factory()->create();
        $orderId = $this->createOrder($admin);
        $confirmationId = $this->createSupplierConfirmation($admin, $orderId);

        $this->actingAs($admin)
            ->postJson('/mbg/suppliers/'.$confirmationId.'/confirm', ['can_fulfill' => true])
            ->assertOk();

        $this->actingAs($admin)
            ->postJson('/mbg/distributions', [
                'mbg_order_id' => $orderId,
                'school_name' => 'SD Negeri 1',
                'target_portions' => 100,
                'realized_portions' => 95,
                'distributed_at' => now()->toDateString(),
            ])
            ->assertCreated();

        $this->actingAs($admin)
            ->postJson('/mbg/reports/monthly', [
                'village_name' => 'Desa Maju',
                'month' => now()->month,
                'year' => now()->year,
            ])
            ->assertOk()
            ->assertJsonPath('format', 'Siap dilaporkan ke Dinas/Kecamatan')
            ->assertJsonPath('laporan.total_distribusi.target_porsi', 100)
            ->assertJsonPath('laporan.total_distribusi.realisasi_porsi', 95)
            ->assertJsonPath('laporan.nilai_transaksi_supplier_lokal', 'Rp 1.200.000');
    }

    private function createOrder(User $admin): int
    {
        return $this->actingAs($admin)
            ->postJson('/mbg/orders', [
                'village_name' => 'Desa Maju',
                'beneficiary_name' => 'SD Negeri 1',
                'target_portions' => 100,
                'distribution_date' => now()->addDays(3)->toDateString(),
            ])
            ->assertCreated()
            ->json('order_id');
    }

    private function createSupplierConfirmation(User $admin, ?int $orderId = null): int
    {
        $orderId ??= $this->createOrder($admin);

        $this->actingAs($admin)
            ->postJson('/mbg/orders/'.$orderId.'/suppliers/notify', [
                'suppliers' => [
                    [
                        'supplier_name' => 'Petani Makmur',
                        'supplier_type' => 'desa_sendiri',
                        'product_name' => 'Beras',
                        'quantity' => 100,
                        'unit' => 'kg',
                        'delivery_date' => now()->addDays(3)->toDateString(),
                        'estimated_unit_price' => 12000,
                    ],
                ],
            ])
            ->assertOk();

        return MbgSupplierConfirmation::where('mbg_order_id', $orderId)->firstOrFail()->id;
    }
}
