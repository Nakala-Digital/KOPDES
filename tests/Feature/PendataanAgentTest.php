<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PendataanAgentTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_store_village_asset(): void
    {
        $admin = User::factory()->create();

        $this->actingAs($admin)
            ->postJson('/pendataan/aset', [
                'name' => 'Lahan Pertanian Desa',
                'category' => 'tanah',
                'location_description' => 'Dusun Utara',
                'condition' => 'baik',
                'estimated_value' => 150000000,
                'notes' => 'Dekat jalan utama',
            ])
            ->assertCreated()
            ->assertJsonPath('success', true)
            ->assertJsonPath('summary.estimasi_nilai', 'Rp150.000.000');

        $this->assertDatabaseHas('aset_desa', [
            'name' => 'Lahan Pertanian Desa',
            'category' => 'tanah',
        ]);
    }

    public function test_asset_validation_reports_missing_fields(): void
    {
        $admin = User::factory()->create();

        $this->actingAs($admin)
            ->postJson('/pendataan/aset', [
                'name' => '',
                'category' => 'kendaraan',
            ])
            ->assertStatus(422)
            ->assertJsonPath('success', false)
            ->assertJsonStructure(['message', 'missing_fields', 'errors']);
    }

    public function test_admin_can_store_umkm_and_offer_account_creation(): void
    {
        $admin = User::factory()->create();

        $this->actingAs($admin)
            ->postJson('/pendataan/umkm', [
                'business_name' => 'Keripik Makmur',
                'owner_name' => 'Ibu Sari',
                'phone' => '089999999999',
                'business_type' => 'Makanan',
                'main_products' => 'Keripik singkong, Keripik pisang',
                'production_capacity' => 120,
                'production_unit' => 'bungkus',
                'production_period' => 'bulan',
                'needs_capital' => 'ya',
                'address' => 'Jalan Desa No. 1',
            ])
            ->assertCreated()
            ->assertJsonPath('success', true)
            ->assertJsonPath('owner_has_account', false)
            ->assertJsonPath('recommended_role', 'umkm_petani');

        $this->assertDatabaseHas('umkm_profiles', [
            'business_name' => 'Keripik Makmur',
            'phone' => '089999999999',
        ]);
    }

    public function test_admin_can_export_potential_data(): void
    {
        $admin = User::factory()->create(['village_name' => 'Desa Maju']);

        $this->actingAs($admin)
            ->postJson('/pendataan/aset', [
                'village_name' => 'Desa Maju',
                'name' => 'Balai Desa',
                'category' => 'bangunan',
                'location_description' => 'Pusat desa',
                'condition' => 'baik',
                'estimated_value' => 500000000,
            ])
            ->assertCreated();

        $response = $this->actingAs($admin)
            ->postJson('/pendataan/export', [
                'village_name' => 'Desa Maju',
                'category' => 'aset',
                'format' => 'excel',
                'period' => 'Mei 2026',
            ])
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonStructure(['download_url']);

        $this->assertStringContainsString('/exports/', $response->json('download_url'));
    }
}
