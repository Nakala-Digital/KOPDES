<?php

namespace Tests\Feature;

use App\Models\MarketplaceOrder;
use App\Models\Product;
use App\Models\UmkmProfile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UmkmAgentTest extends TestCase
{
    use RefreshDatabase;

    public function test_product_requires_confirmation_before_save(): void
    {
        $admin = User::factory()->create();
        $umkm = $this->createUmkm($admin);

        $this->actingAs($admin)
            ->postJson('/umkm/products', [
                'umkm_profile_id' => $umkm->id,
                'name' => 'Keripik Pisang',
                'description' => 'Renyah dan manis',
                'price' => 15000,
                'unit' => 'bungkus',
                'stock' => 20,
                'minimum_stock' => 5,
                'category' => 'Makanan',
            ])
            ->assertStatus(202)
            ->assertJsonPath('confirmation_required', true);

        $this->assertDatabaseMissing('products', ['name' => 'Keripik Pisang']);
    }

    public function test_umkm_can_add_product_after_confirmation(): void
    {
        $admin = User::factory()->create();
        $umkm = $this->createUmkm($admin);

        $this->actingAs($admin)
            ->postJson('/umkm/products', [
                'umkm_profile_id' => $umkm->id,
                'name' => 'Keripik Pisang',
                'description' => 'Renyah dan manis',
                'price' => 15000,
                'unit' => 'bungkus',
                'stock' => 20,
                'minimum_stock' => 5,
                'category' => 'Makanan',
                'confirmed' => true,
            ])
            ->assertCreated()
            ->assertJsonPath('success', true)
            ->assertJsonPath('summary.harga', 'Rp 15.000 per bungkus')
            ->assertJsonStructure(['product_id', 'preview_url']);

        $this->assertDatabaseHas('products', ['name' => 'Keripik Pisang', 'status' => 'aktif']);
        $this->assertDatabaseHas('umkm_notifications', ['title' => 'Produk baru sudah tampil']);
    }

    public function test_order_is_rejected_when_stock_is_not_enough(): void
    {
        $admin = User::factory()->create();
        $product = $this->createProduct($admin, stock: 2);

        $this->actingAs($admin)
            ->postJson('/umkm/orders', [
                'product_id' => $product->id,
                'buyer_name' => 'Bu Ani',
                'quantity' => 5,
                'shipping_address' => 'Dusun Barat',
            ])
            ->assertStatus(422)
            ->assertJsonPath('success', false)
            ->assertJsonPath('available_stock', '2 bungkus');
    }

    public function test_confirm_order_reduces_stock_and_sends_notifications(): void
    {
        $admin = User::factory()->create();
        $product = $this->createProduct($admin, stock: 10, minimumStock: 3);
        $orderId = $this->createOrder($admin, $product, 4);

        $this->actingAs($admin)
            ->postJson('/umkm/orders/'.$orderId.'/confirm', ['confirmed' => true])
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('summary.status', 'diproses');

        $product->refresh();
        $this->assertEquals(6, (float) $product->stock);
        $this->assertDatabaseHas('marketplace_orders', ['id' => $orderId, 'status' => 'diproses']);
        $this->assertDatabaseHas('umkm_notifications', ['title' => 'Pesanan sedang diproses']);
    }

    public function test_low_stock_alert_is_created_after_confirmation(): void
    {
        $admin = User::factory()->create();
        $product = $this->createProduct($admin, stock: 5, minimumStock: 2);
        $orderId = $this->createOrder($admin, $product, 3);

        $this->actingAs($admin)
            ->postJson('/umkm/orders/'.$orderId.'/confirm', ['confirmed' => true])
            ->assertOk();

        $this->assertDatabaseHas('umkm_notifications', ['title' => 'Stok mulai menipis']);
    }

    public function test_shipping_order_records_financial_transaction(): void
    {
        $admin = User::factory()->create();
        $product = $this->createProduct($admin, stock: 10);
        $orderId = $this->createOrder($admin, $product, 2);

        $this->actingAs($admin)->postJson('/umkm/orders/'.$orderId.'/confirm', ['confirmed' => true])->assertOk();

        $this->actingAs($admin)
            ->postJson('/umkm/orders/'.$orderId.'/ship')
            ->assertOk()
            ->assertJsonPath('summary.status', 'dikirim');

        $this->assertDatabaseHas('umkm_financial_transactions', ['order_id' => $orderId, 'type' => 'penjualan']);
    }

    public function test_overdue_order_alerts_admin(): void
    {
        $admin = User::factory()->create();
        $product = $this->createProduct($admin, stock: 10);
        $orderId = $this->createOrder($admin, $product, 1);
        MarketplaceOrder::whereKey($orderId)->update(['created_at' => now()->subHours(25)]);

        $this->actingAs($admin)
            ->postJson('/umkm/orders/overdue-alerts')
            ->assertOk()
            ->assertJsonPath('total_alert', 1);

        $this->assertDatabaseHas('umkm_notifications', ['recipient_type' => 'admin_desa']);
    }

    public function test_sales_report_uses_simple_summary(): void
    {
        $admin = User::factory()->create();
        $product = $this->createProduct($admin, stock: 10);
        $orderId = $this->createOrder($admin, $product, 2);
        $this->actingAs($admin)->postJson('/umkm/orders/'.$orderId.'/confirm', ['confirmed' => true])->assertOk();
        $this->actingAs($admin)->postJson('/umkm/orders/'.$orderId.'/ship')->assertOk();

        $this->actingAs($admin)
            ->postJson('/umkm/reports/sales', [
                'umkm_profile_id' => $product->umkm_profile_id,
                'period' => now()->format('Y-m'),
            ])
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('summary.pesanan_selesai', 1)
            ->assertJsonPath('summary.omzet_periode_ini', 'Rp 30.000');
    }

    private function createUmkm(User $admin): UmkmProfile
    {
        return UmkmProfile::create([
            'created_by' => $admin->id,
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

    private function createProduct(User $admin, int $stock = 10, int $minimumStock = 3): Product
    {
        $umkm = $this->createUmkm($admin);

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

    private function createOrder(User $admin, Product $product, int $quantity): int
    {
        return $this->actingAs($admin)
            ->postJson('/umkm/orders', [
                'product_id' => $product->id,
                'buyer_name' => 'Bu Ani',
                'quantity' => $quantity,
                'shipping_address' => 'Dusun Barat',
            ])
            ->assertCreated()
            ->json('order_id');
    }
}
