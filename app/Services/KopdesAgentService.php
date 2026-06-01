<?php

namespace App\Services;

use App\Models\KopdesSetting;
use App\Models\KoperasiInstallment;
use App\Models\KoperasiLoan;
use App\Models\KoperasiMember;
use App\Models\KoperasiSaving;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class KopdesAgentService
{
    public const SAVING_TYPES = ['pokok', 'wajib', 'sukarela'];
    public const MEMBER_STATUSES = ['active', 'inactive'];

    public function createMember(array $data, ?User $admin): array
    {
        $user = null;
        if (! empty($data['phone'])) {
            $user = User::where('phone', $data['phone'])->first();
        }

        $member = KoperasiMember::create([
            'user_id' => $user?->id,
            'created_by' => $admin?->id,
            'member_number' => $data['member_number'] ?? $this->generateMemberNumber(),
            'name' => $data['name'],
            'phone' => $data['phone'] ?? null,
            'nik' => $data['nik'] ?? null,
            'village_name' => $data['village_name'] ?? $admin?->village_name,
            'address' => $data['address'] ?? null,
            'status' => $data['status'] ?? 'active',
            'joined_at' => $data['joined_at'] ?? now()->toDateString(),
        ]);

        return [
            'success' => true,
            'message' => 'Anggota koperasi berhasil didaftarkan.',
            'member_id' => $member->id,
            'summary' => [
                'nomor_anggota' => $member->member_number,
                'nama' => $member->name,
                'status' => $this->statusLabel($member->status),
                'desa' => $member->village_name,
            ],
        ];
    }

    public function recordSaving(array $data, ?User $admin): array
    {
        $member = KoperasiMember::findOrFail($data['member_id']);

        if ($data['type'] === 'pokok' && $member->savings()->where('type', 'pokok')->exists()) {
            return [
                'success' => false,
                'message' => 'Simpanan pokok hanya dibayar satu kali saat anggota mendaftar.',
            ];
        }

        $saving = KoperasiSaving::create([
            'member_id' => $member->id,
            'created_by' => $admin?->id,
            'type' => $data['type'],
            'amount' => $data['amount'],
            'paid_at' => $data['paid_at'] ?? now()->toDateString(),
            'period' => $data['period'] ?? null,
            'notes' => $data['notes'] ?? null,
        ]);

        return [
            'success' => true,
            'message' => 'Simpanan anggota berhasil dicatat.',
            'saving_id' => $saving->id,
            'summary' => [
                'anggota' => $member->name,
                'jenis_simpanan' => $this->savingTypeLabel($saving->type),
                'nominal' => $this->formatRupiah((float) $saving->amount),
                'total_simpanan' => $this->formatRupiah($this->totalSavings($member)),
            ],
        ];
    }

    public function evaluateLoan(int $memberId, float $amount): array
    {
        $member = KoperasiMember::with(['savings', 'loans.installments'])->findOrFail($memberId);
        $totalSavings = $this->totalSavings($member);
        $plafond = $totalSavings * 3;
        $activeArrears = $this->hasActiveArrears($member);

        $allowed = $member->status === 'active'
            && ! $activeArrears
            && $amount <= $plafond
            && $amount > 0;

        $reasons = [];
        if ($member->status !== 'active') {
            $reasons[] = 'Status keanggotaan tidak aktif.';
        }
        if ($activeArrears) {
            $reasons[] = 'Masih ada tunggakan aktif.';
        }
        if ($amount > $plafond) {
            $reasons[] = 'Jumlah pinjaman melebihi plafond maksimal.';
        }
        if ($amount <= 0) {
            $reasons[] = 'Jumlah pinjaman harus lebih dari Rp 0.';
        }

        return [
            'allowed' => $allowed,
            'message' => $allowed
                ? 'Pengajuan pinjaman memenuhi syarat.'
                : implode(' ', $reasons),
            'summary' => [
                'status_keanggotaan' => $this->statusLabel($member->status),
                'total_simpanan' => $this->formatRupiah($totalSavings),
                'plafond_maksimal' => $this->formatRupiah($plafond),
                'ada_tunggakan' => $activeArrears ? 'ya' : 'tidak',
            ],
        ];
    }

    public function createLoan(array $data, ?User $admin): array
    {
        $member = KoperasiMember::findOrFail($data['member_id']);
        $evaluation = $this->evaluateLoan($member->id, (float) $data['principal_amount']);

        if (! $evaluation['allowed']) {
            return [
                'success' => false,
                'message' => 'Pinjaman belum dapat diproses. '.$evaluation['message'],
                'validation' => $evaluation['summary'],
            ];
        }

        $interestRate = isset($data['interest_rate'])
            ? (float) $data['interest_rate']
            : $this->setting($member->village_name)->monthly_interest_rate;
        $tenor = (int) $data['tenor_months'];
        $principal = (float) $data['principal_amount'];
        $totalInterest = $principal * ($interestRate / 100) * $tenor;
        $totalPayable = $principal + $totalInterest;
        $monthlyInstallment = round($totalPayable / $tenor, 2);
        $dueStartDate = isset($data['due_start_date']) ? Carbon::parse($data['due_start_date']) : now()->addMonthNoOverflow();

        $loan = DB::transaction(function () use ($data, $admin, $member, $principal, $interestRate, $tenor, $monthlyInstallment, $totalPayable, $dueStartDate) {
            $loan = KoperasiLoan::create([
                'member_id' => $member->id,
                'created_by' => $admin?->id,
                'principal_amount' => $principal,
                'interest_rate' => $interestRate,
                'tenor_months' => $tenor,
                'monthly_installment' => $monthlyInstallment,
                'total_payable' => $totalPayable,
                'status' => 'active',
                'approved_at' => now()->toDateString(),
                'due_start_date' => $dueStartDate->toDateString(),
                'notes' => $data['notes'] ?? null,
            ]);

            for ($i = 1; $i <= $tenor; $i++) {
                KoperasiInstallment::create([
                    'loan_id' => $loan->id,
                    'installment_number' => $i,
                    'due_date' => $dueStartDate->copy()->addMonthsNoOverflow($i - 1)->toDateString(),
                    'amount_due' => $monthlyInstallment,
                    'status' => 'unpaid',
                ]);
            }

            return $loan;
        });

        return [
            'success' => true,
            'message' => 'Pinjaman berhasil diproses dan jadwal angsuran telah dibuat.',
            'loan_id' => $loan->id,
            'summary' => [
                'anggota' => $member->name,
                'jumlah_pinjaman' => $this->formatRupiah($principal),
                'bunga_per_bulan' => $interestRate.'%',
                'tenor' => $tenor.' bulan',
                'angsuran_bulanan' => $this->formatRupiah($monthlyInstallment),
                'total_dibayar' => $this->formatRupiah($totalPayable),
            ],
        ];
    }

    public function payInstallment(array $data): array
    {
        $installment = KoperasiInstallment::with('loan')->findOrFail($data['installment_id']);
        $amount = (float) $data['amount'];
        $newPaidAmount = min((float) $installment->amount_due, (float) $installment->amount_paid + $amount);
        $status = $newPaidAmount >= (float) $installment->amount_due ? 'paid' : 'partial';

        $installment->forceFill([
            'amount_paid' => $newPaidAmount,
            'paid_at' => $status === 'paid' ? now()->toDateString() : $installment->paid_at,
            'status' => $status,
        ])->save();

        $loan = $installment->loan;
        if ($loan->installments()->where('status', '!=', 'paid')->doesntExist()) {
            $loan->forceFill(['status' => 'paid'])->save();
        }

        return [
            'success' => true,
            'message' => 'Pembayaran angsuran berhasil dicatat.',
            'summary' => [
                'angsuran_ke' => $installment->installment_number,
                'dibayar' => $this->formatRupiah($amount),
                'total_terbayar' => $this->formatRupiah($newPaidAmount),
                'status' => $status === 'paid' ? 'lunas' : 'sebagian',
            ],
        ];
    }

    public function financialReport(?string $villageName = null): array
    {
        $memberQuery = KoperasiMember::query()->when($villageName, fn ($query) => $query->where('village_name', $villageName));
        $savingQuery = KoperasiSaving::query()->whereHas('member', fn ($query) => $query->when($villageName, fn ($inner) => $inner->where('village_name', $villageName)));
        $loanQuery = KoperasiLoan::query()->whereHas('member', fn ($query) => $query->when($villageName, fn ($inner) => $inner->where('village_name', $villageName)));
        $arrearsQuery = KoperasiInstallment::query()
            ->where('due_date', '<', now()->toDateString())
            ->where('status', '!=', 'paid')
            ->whereHas('loan.member', fn ($query) => $query->when($villageName, fn ($inner) => $inner->where('village_name', $villageName)));

        $totalSavings = (float) $savingQuery->sum('amount');
        $totalLoans = (float) $loanQuery->sum('principal_amount');
        $totalArrears = (float) $arrearsQuery->sum(DB::raw('amount_due - amount_paid'));

        return [
            'success' => true,
            'message' => 'Laporan keuangan koperasi berhasil dibuat.',
            'summary' => [
                'desa' => $villageName ?? 'Semua desa',
                'jumlah_anggota' => $memberQuery->count(),
                'total_simpanan' => $this->formatRupiah($totalSavings),
                'total_pinjaman_aktif' => $this->formatRupiah($totalLoans),
                'total_tunggakan' => $this->formatRupiah($totalArrears),
            ],
        ];
    }

    public function formatRupiah(float $value): string
    {
        return 'Rp '.number_format($value, 0, ',', '.');
    }

    private function totalSavings(KoperasiMember $member): float
    {
        return (float) $member->savings()->sum('amount');
    }

    private function hasActiveArrears(KoperasiMember $member): bool
    {
        return KoperasiInstallment::query()
            ->whereHas('loan', fn ($query) => $query->where('member_id', $member->id))
            ->where('due_date', '<', now()->toDateString())
            ->where('status', '!=', 'paid')
            ->exists();
    }

    private function setting(?string $villageName): KopdesSetting
    {
        return KopdesSetting::firstOrCreate(
            ['village_name' => $villageName],
            ['monthly_interest_rate' => 1.5]
        );
    }

    private function generateMemberNumber(): string
    {
        do {
            $number = 'KDMP-'.now()->format('Ymd').'-'.Str::upper(Str::random(4));
        } while (KoperasiMember::where('member_number', $number)->exists());

        return $number;
    }

    private function savingTypeLabel(string $type): string
    {
        return match ($type) {
            'pokok' => 'Simpanan Pokok',
            'wajib' => 'Simpanan Wajib',
            default => 'Simpanan Sukarela',
        };
    }

    private function statusLabel(string $status): string
    {
        return $status === 'active' ? 'aktif' : 'nonaktif';
    }
}
