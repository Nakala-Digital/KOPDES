<?php

namespace Tests\Feature;

use App\Models\KoperasiInstallment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class KopdesAgentTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_member_and_record_savings(): void
    {
        $admin = User::factory()->create();

        $memberId = $this->actingAs($admin)
            ->postJson('/kopdes/members', [
                'name' => 'Bapak Jaya',
                'phone' => '081234500001',
                'village_name' => 'Desa Maju',
                'address' => 'Dusun Tengah',
            ])
            ->assertCreated()
            ->assertJsonPath('success', true)
            ->json('member_id');

        $this->actingAs($admin)
            ->postJson('/kopdes/savings', [
                'member_id' => $memberId,
                'type' => 'pokok',
                'amount' => 1000000,
            ])
            ->assertCreated()
            ->assertJsonPath('summary.nominal', 'Rp 1.000.000')
            ->assertJsonPath('summary.total_simpanan', 'Rp 1.000.000');

        $this->assertDatabaseHas('koperasi_members', ['name' => 'Bapak Jaya']);
        $this->assertDatabaseHas('koperasi_savings', ['member_id' => $memberId, 'type' => 'pokok']);
    }

    public function test_principal_saving_can_only_be_paid_once(): void
    {
        $admin = User::factory()->create();
        $memberId = $this->createMember($admin);

        $payload = [
            'member_id' => $memberId,
            'type' => 'pokok',
            'amount' => 500000,
        ];

        $this->actingAs($admin)->postJson('/kopdes/savings', $payload)->assertCreated();

        $this->actingAs($admin)
            ->postJson('/kopdes/savings', $payload)
            ->assertStatus(422)
            ->assertJsonPath('success', false)
            ->assertJsonPath('message', 'Simpanan pokok hanya dibayar satu kali saat anggota mendaftar.');
    }

    public function test_loan_is_limited_to_three_times_total_savings(): void
    {
        $admin = User::factory()->create();
        $memberId = $this->createMember($admin);

        $this->actingAs($admin)->postJson('/kopdes/savings', [
            'member_id' => $memberId,
            'type' => 'pokok',
            'amount' => 1000000,
        ])->assertCreated();

        $this->actingAs($admin)
            ->postJson('/kopdes/loans/evaluate', [
                'member_id' => $memberId,
                'principal_amount' => 3500000,
            ])
            ->assertOk()
            ->assertJsonPath('allowed', false)
            ->assertJsonPath('summary.plafond_maksimal', 'Rp 3.000.000');
    }

    public function test_valid_loan_creates_installment_schedule(): void
    {
        $admin = User::factory()->create();
        $memberId = $this->createMember($admin);

        $this->actingAs($admin)->postJson('/kopdes/savings', [
            'member_id' => $memberId,
            'type' => 'pokok',
            'amount' => 2000000,
        ])->assertCreated();

        $loanId = $this->actingAs($admin)
            ->postJson('/kopdes/loans', [
                'member_id' => $memberId,
                'principal_amount' => 3000000,
                'tenor_months' => 6,
            ])
            ->assertCreated()
            ->assertJsonPath('success', true)
            ->assertJsonPath('summary.bunga_per_bulan', '1.5%')
            ->json('loan_id');

        $this->assertDatabaseCount('koperasi_installments', 6);
        $this->assertDatabaseHas('koperasi_loans', ['id' => $loanId, 'status' => 'active']);
    }

    public function test_member_with_active_arrears_cannot_create_new_loan(): void
    {
        $admin = User::factory()->create();
        $memberId = $this->createMember($admin);

        $this->actingAs($admin)->postJson('/kopdes/savings', [
            'member_id' => $memberId,
            'type' => 'pokok',
            'amount' => 5000000,
        ])->assertCreated();

        $this->actingAs($admin)->postJson('/kopdes/loans', [
            'member_id' => $memberId,
            'principal_amount' => 3000000,
            'tenor_months' => 3,
            'due_start_date' => now()->subMonths(2)->toDateString(),
        ])->assertCreated();

        KoperasiInstallment::query()->first()->update(['due_date' => now()->subMonth()->toDateString()]);

        $this->actingAs($admin)
            ->postJson('/kopdes/loans', [
                'member_id' => $memberId,
                'principal_amount' => 1000000,
                'tenor_months' => 3,
            ])
            ->assertStatus(422)
            ->assertJsonPath('success', false)
            ->assertJsonPath('validation.ada_tunggakan', 'ya');
    }

    public function test_financial_report_uses_rupiah_format(): void
    {
        $admin = User::factory()->create(['village_name' => 'Desa Maju']);
        $memberId = $this->createMember($admin);

        $this->actingAs($admin)->postJson('/kopdes/savings', [
            'member_id' => $memberId,
            'type' => 'sukarela',
            'amount' => 750000,
        ])->assertCreated();

        $this->actingAs($admin)
            ->postJson('/kopdes/reports/finance', ['village_name' => 'Desa Maju'])
            ->assertOk()
            ->assertJsonPath('summary.total_simpanan', 'Rp 750.000');
    }

    private function createMember(User $admin): int
    {
        return $this->actingAs($admin)
            ->postJson('/kopdes/members', [
                'name' => 'Anggota Test',
                'phone' => fake()->unique()->numerify('0812########'),
                'village_name' => 'Desa Maju',
                'status' => 'active',
            ])
            ->assertCreated()
            ->json('member_id');
    }
}
