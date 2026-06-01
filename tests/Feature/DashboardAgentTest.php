<?php

namespace Tests\Feature;

use App\Models\KoperasiLoan;
use App\Models\KoperasiMember;
use App\Models\KoperasiSaving;
use App\Models\MarketplaceOrder;
use App\Models\MarketplaceOrderItem;
use App\Models\MbgOrder;
use App\Models\MbgSupplierConfirmation;
use App\Models\Product;
use App\Models\Role;
use App\Models\UmkmFinancialTransaction;
use App\Models\UmkmProfile;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardAgentTest extends TestCase
{
    use RefreshDatabase;

    public function test_dashboard_data_summarizes_today_and_prioritizes_alerts(): void
    {
        $admin = User::factory()->create(['village_name' => 'Desa Maju']);
        $umkm = $this->createUmkm();
        $product = $this->createProduct($umkm, stock: 2, minimumStock: 5);
        $this->createMarketplaceOrder($umkm, $product);
        $this->createUmkmTransaction($umkm, 100000, now());
        $this->createUmkmTransaction($umkm, 50000, now()->subDay());
        $order = $this->createMbgOrder();
        $this->createPendingSupplier($order, now()->subHours(7));

        $this->actingAs($admin)
            ->getJson('/dashboard-data?village_name=Desa Maju')
            ->assertOk()
            ->assertJsonPath('summary.transactions_today_count', 1)
            ->assertJsonPath('summary.transactions_today_value', 'Rp 100.000')
            ->assertJsonPath('summary.transactions_vs_yesterday', 'naik ↑ 100,0%')
            ->assertJsonPath('summary.active_umkm_transactions', 1)
            ->assertJsonPath('summary.mbg_orders_today', 1)
            ->assertJsonPath('alerts.0.label', 'URGENT')
            ->assertJsonPath('stats.critical_stock', 1)
            ->assertJsonPath('charts.transactions.0.label', 'Kemarin')
            ->assertJsonPath('charts.transactions.1.value', 100000)
            ->assertJsonPath('charts.module_activity.0.label', 'UMKM')
            ->assertJsonPath('charts.stock.1.label', 'Kritis')
            ->assertJsonPath('charts.alerts.0.label', 'Urgent');
    }

    public function test_kopdes_role_only_sees_koperasi_metrics(): void
    {
        $role = Role::create([
            'name' => 'Pengurus Kopdes',
            'slug' => 'pengurus_kopdes',
            'dashboard_type' => 'kopdes',
            'dashboard_label' => 'Kopdes',
        ]);
        $user = User::factory()->create(['role_id' => $role->id, 'village_name' => 'Desa Maju']);
        $umkm = $this->createUmkm();
        $this->createProduct($umkm, stock: 1, minimumStock: 5);
        $this->createKoperasiMember();

        $this->actingAs($user)
            ->getJson('/dashboard-data?village_name=Desa Maju')
            ->assertOk()
            ->assertJsonPath('modules', ['kopdes'])
            ->assertJsonPath('stats.critical_stock', 0)
            ->assertJsonPath('stats.active_koperasi_members', 1)
            ->assertJsonPath('summary.mbg_orders_today', 0);
    }

    public function test_village_report_is_ready_for_meeting(): void
    {
        $admin = User::factory()->create(['village_name' => 'Desa Maju']);
        $umkm = $this->createUmkm();
        $this->createUmkmTransaction($umkm, 250000, now());
        $order = $this->createMbgOrder(target: 100, realized: 90);
        $member = $this->createKoperasiMember();

        KoperasiSaving::create([
            'member_id' => $member->id,
            'type' => 'pokok',
            'amount' => 500000,
            'paid_at' => now()->toDateString(),
        ]);
        KoperasiLoan::create([
            'member_id' => $member->id,
            'principal_amount' => 300000,
            'interest_rate' => 1.5,
            'tenor_months' => 3,
            'monthly_installment' => 103000,
            'total_payable' => 309000,
            'status' => 'active',
        ]);
        $order->forceFill(['created_at' => now(), 'updated_at' => now()])->save();

        $this->actingAs($admin)
            ->postJson('/dashboard/reports/village', [
                'village_name' => 'Desa Maju',
                'month' => now()->month,
                'year' => now()->year,
            ])
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('format', 'Maksimal 1 halaman A4, siap dibawa ke rapat desa.')
            ->assertJsonPath('indikator_utama.total_nilai_transaksi_ekonomi', 'Rp 250.000')
            ->assertJsonPath('indikator_utama.realisasi_mbg', '90,0%')
            ->assertJsonPath('indikator_utama.simpanan_koperasi', 'Rp 500.000')
            ->assertJsonPath('indikator_utama.pinjaman_koperasi', 'Rp 300.000');
    }

    private function createUmkm(): UmkmProfile
    {
        return UmkmProfile::create([
            'village_name' => 'Desa Maju',
            'business_name' => 'Dapur Desa',
            'owner_name' => 'Ibu Sari',
            'phone' => fake()->unique()->numerify('0812########'),
            'business_type' => 'Makanan',
            'main_products' => ['Keripik'],
            'production_capacity' => 100,
            'production_unit' => 'bungkus',
            'production_period' => 'bulan',
            'needs_capital' => false,
            'address' => 'Dusun Tengah',
        ]);
    }

    private function createProduct(UmkmProfile $umkm, int $stock, int $minimumStock): Product
    {
        return Product::create([
            'umkm_profile_id' => $umkm->id,
            'name' => 'Keripik Pisang',
            'description' => 'Renyah',
            'price' => 15000,
            'unit' => 'bungkus',
            'stock' => $stock,
            'minimum_stock' => $minimumStock,
            'category' => 'Makanan',
            'status' => 'aktif',
            'is_marketplace_visible' => true,
        ]);
    }

    private function createMarketplaceOrder(UmkmProfile $umkm, Product $product): void
    {
        $order = MarketplaceOrder::create([
            'umkm_profile_id' => $umkm->id,
            'order_number' => 'ORD-'.fake()->unique()->numberBetween(1000, 9999),
            'buyer_name' => 'Bu Ani',
            'shipping_address' => 'Dusun Barat',
            'total_amount' => 30000,
            'status' => 'diproses',
        ]);

        MarketplaceOrderItem::create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'quantity' => 2,
            'unit' => 'bungkus',
            'price' => 15000,
            'subtotal' => 30000,
        ]);
    }

    private function createUmkmTransaction(UmkmProfile $umkm, int $amount, mixed $date): void
    {
        $transaction = UmkmFinancialTransaction::create([
            'umkm_profile_id' => $umkm->id,
            'type' => 'penjualan',
            'amount' => $amount,
            'description' => 'Penjualan marketplace',
            'transaction_date' => $date->toDateString(),
        ]);

        $this->setCreatedAt($transaction, $date);
    }

    private function createMbgOrder(int $target = 100, int $realized = 0): MbgOrder
    {
        return MbgOrder::create([
            'order_number' => 'MBG-'.fake()->unique()->numberBetween(1000, 9999),
            'village_name' => 'Desa Maju',
            'beneficiary_name' => 'SD Negeri 1',
            'target_portions' => $target,
            'realized_portions' => $realized,
            'distribution_date' => now()->toDateString(),
            'status' => 'approved',
        ]);
    }

    private function createPendingSupplier(MbgOrder $order, mixed $notifiedAt): void
    {
        MbgSupplierConfirmation::create([
            'mbg_order_id' => $order->id,
            'supplier_name' => 'Petani Makmur',
            'supplier_type' => 'desa_sendiri',
            'product_name' => 'Beras',
            'quantity' => 100,
            'unit' => 'kg',
            'delivery_date' => now()->addDay()->toDateString(),
            'estimated_unit_price' => 12000,
            'estimated_total_cost' => 1200000,
            'status' => 'menunggu_konfirmasi',
            'notified_at' => $notifiedAt,
        ]);
    }

    private function createKoperasiMember(): KoperasiMember
    {
        return KoperasiMember::create([
            'member_number' => 'KOP-'.fake()->unique()->numberBetween(1000, 9999),
            'name' => 'Bapak Jaya',
            'phone' => fake()->unique()->numerify('0812########'),
            'village_name' => 'Desa Maju',
            'status' => 'active',
            'joined_at' => now()->toDateString(),
        ]);
    }

    private function setCreatedAt(Model $model, mixed $date): void
    {
        $model->forceFill([
            'created_at' => $date,
            'updated_at' => $date,
        ])->save();
    }
}
