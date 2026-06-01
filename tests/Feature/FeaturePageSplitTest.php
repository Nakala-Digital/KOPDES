<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FeaturePageSplitTest extends TestCase
{
    use RefreshDatabase;

    public function test_each_feature_page_can_be_opened_separately(): void
    {
        $user = User::factory()->create();

        $paths = [
            '/dashboard',
            '/role-flow',
            '/pendataan/aset',
            '/pendataan/umkm',
            '/pendataan/export',
            '/kopdes/members',
            '/kopdes/savings',
            '/kopdes/loans',
            '/kopdes/reports',
            '/bumdes/units',
            '/bumdes/transactions',
            '/bumdes/reversal',
            '/bumdes/consolidation',
            '/bumdes/annual',
            '/umkm/products',
            '/umkm/orders',
            '/umkm/reports',
            '/mbg/orders',
            '/mbg/suppliers',
            '/mbg/distributions',
            '/mbg/reports',
        ];

        foreach ($paths as $path) {
            $this->actingAs($user)
                ->get($path)
                ->assertOk()
                ->assertSee('data-sidebar-toggle', false)
                ->assertSee('data-sidebar', false)
                ->assertSee('data-topbar', false);
        }
    }
}
