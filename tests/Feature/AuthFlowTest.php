<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_root_redirects_to_login(): void
    {
        $this->get('/')->assertRedirect('/login');
    }

    public function test_login_page_is_available_for_guest(): void
    {
        $this->get('/login')->assertOk();
    }

    public function test_register_page_is_available_for_guest(): void
    {
        $this->get('/register')
            ->assertOk()
            ->assertSee('Daftar akun');
    }

    public function test_guest_can_register(): void
    {
        $this->post('/register', [
            'name' => 'Warga Desa',
            'email' => 'warga@example.com',
            'phone' => '081234567890',
            'nik' => '3201010101010001',
            'password' => 'password',
            'password_confirmation' => 'password',
        ])->assertRedirect(route('dashboard'));

        $this->assertAuthenticated();
        $this->assertDatabaseHas('users', [
            'name' => 'Warga Desa',
            'email' => 'warga@example.com',
        ]);
    }

    public function test_authenticated_user_can_see_dashboard(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get('/dashboard')
            ->assertOk()
            ->assertSee($user->name);
    }

    public function test_auth_agent_returns_json_for_valid_pin_login(): void
    {
        $role = Role::create([
            'name' => 'Warga',
            'slug' => 'warga',
            'dashboard_type' => 'warga',
            'dashboard_label' => 'Warga',
        ]);

        $user = User::factory()->create([
            'phone' => '081111111111',
            'nik' => '3201010101010001',
            'role_id' => $role->id,
            'pin_hash' => Hash::make('123456'),
            'status' => 'active',
        ]);

        $this->postJson('/login', [
            'identifier' => $user->phone,
            'pin' => '123456',
        ])
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('role', 'warga')
            ->assertJsonPath('redirect_to', '/dashboard/warga')
            ->assertJsonStructure(['success', 'role', 'redirect_to', 'token', 'message']);

        $this->assertDatabaseHas('audit_logs', [
            'user_id' => $user->id,
            'event' => 'login_success',
        ]);
    }

    public function test_auth_agent_locks_account_after_three_wrong_pins(): void
    {
        $user = User::factory()->create([
            'phone' => '082222222222',
            'pin_hash' => Hash::make('123456'),
            'status' => 'active',
        ]);

        foreach (range(1, 3) as $attempt) {
            $this->postJson('/login', [
                'identifier' => $user->phone,
                'pin' => '000000',
            ])->assertStatus(422);
        }

        $user->refresh();

        $this->assertSame(3, $user->failed_login_attempts);
        $this->assertNotNull($user->locked_until);
        $this->assertDatabaseHas('audit_logs', [
            'user_id' => $user->id,
            'event' => 'login_wrong_pin',
        ]);
    }
}
