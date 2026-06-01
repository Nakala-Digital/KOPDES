<?php

namespace App\Services;

use App\Models\BumdesUnit;
use App\Models\BumdesUnitTransaction;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class BumdesAgentService
{
    public const UNIT_CATEGORIES = ['wardes', 'wisata_desa', 'air_bersih', 'simpan_pinjam', 'pertanian', 'lainnya'];
    public const TRANSACTION_TYPES = ['income', 'expense'];

    public function createUnit(array $data, User $user): array
    {
        $unit = BumdesUnit::create([
            'created_by' => $user->id,
            'village_name' => $data['village_name'] ?? $user->village_name,
            'name' => $data['name'],
            'category' => $data['category'],
            'pades_percentage' => $data['pades_percentage'] ?? 20,
            'status' => $data['status'] ?? 'active',
            'notes' => $data['notes'] ?? null,
        ]);

        return [
            'success' => true,
            'unit_id' => $unit->id,
            'message' => 'Unit usaha BUMDes berhasil dibuat.',
            'summary' => [
                'nama_unit' => $unit->name,
                'kategori' => $unit->category,
                'kontribusi_pades' => number_format((float) $unit->pades_percentage, 1, ',', '.').'%',
            ],
        ];
    }

    public function recordTransaction(array $data, User $user): array
    {
        $unit = BumdesUnit::findOrFail($data['unit_id']);
        $transaction = BumdesUnitTransaction::create([
            'unit_id' => $unit->id,
            'created_by' => $user->id,
            'transaction_number' => $data['transaction_number'] ?? 'BUM-'.now()->format('Ymd').'-'.Str::upper(Str::random(6)),
            'type' => $data['type'],
            'category' => $data['category'] ?? 'umum',
            'amount' => $data['amount'],
            'transaction_date' => $data['transaction_date'] ?? now()->toDateString(),
            'description' => $data['description'],
            'status' => 'posted',
        ]);

        return [
            'success' => true,
            'transaction_id' => $transaction->id,
            'message' => 'Transaksi unit usaha berhasil dicatat.',
            'summary' => [
                'unit' => $unit->name,
                'jenis' => $transaction->type === 'income' ? 'Pemasukan' : 'Pengeluaran',
                'nominal' => $this->formatRupiah((float) $transaction->amount),
                'tanggal' => $transaction->transaction_date->format('d/m/Y'),
            ],
        ];
    }

    public function reverseTransaction(int $transactionId, string $reason, User $user): array
    {
        $original = BumdesUnitTransaction::findOrFail($transactionId);

        if ($original->status === 'reversed') {
            return ['success' => false, 'message' => 'Transaksi ini sudah pernah direversal.'];
        }

        $reversalType = $original->type === 'income' ? 'expense' : 'income';
        $reversal = BumdesUnitTransaction::create([
            'unit_id' => $original->unit_id,
            'created_by' => $user->id,
            'reversal_of_id' => $original->id,
            'transaction_number' => 'REV-'.now()->format('Ymd').'-'.Str::upper(Str::random(6)),
            'type' => $reversalType,
            'category' => $original->category,
            'amount' => $original->amount,
            'transaction_date' => now()->toDateString(),
            'description' => 'Reversal: '.$reason,
            'status' => 'posted',
        ]);
        $original->forceFill(['status' => 'reversed'])->save();

        return [
            'success' => true,
            'reversal_id' => $reversal->id,
            'message' => 'Transaksi dibalik melalui reversal. Data asli tidak dihapus.',
            'nominal' => $this->formatRupiah((float) $reversal->amount),
        ];
    }

    public function consolidatedReport(?string $villageName = null, ?string $period = null): array
    {
        [$start, $end] = $this->periodRange($period);

        return $this->reportForRange($villageName, $start, $end);
    }

    public function annualReport(string $villageName, int $year): array
    {
        $start = Carbon::create($year, 1, 1)->startOfYear();
        $end = $start->copy()->endOfYear();
        $report = $this->reportForRange($villageName, $start, $end);

        return [
            'success' => true,
            'format' => 'Format ringkas mengacu Permendesa No. 3/2021 untuk laporan tahunan dan RAT.',
            'tahun' => $year,
            'desa' => $villageName,
            'bab_laporan' => [
                'pendahuluan' => 'Gambaran umum BUMDes dan unit usaha berjalan.',
                'laporan_pengelolaan_unit' => $report['unit_usaha'],
                'laporan_keuangan_konsolidasi' => [
                    'total_pemasukan' => $report['total_pemasukan'],
                    'total_pengeluaran' => $report['total_pengeluaran'],
                    'laba_bersih' => $report['laba_bersih'],
                    'kontribusi_pades' => $report['kontribusi_pades'],
                ],
                'rencana_tindak_lanjut' => 'Penguatan pencatatan transaksi, peningkatan laba unit, dan evaluasi kontribusi PADes.',
            ],
        ];
    }

    public function unitFinancialReport(string $bumdesName, string $unitName, int $month, int $year, ?float $padesPercentage = null): array
    {
        $start = Carbon::create($year, $month, 1)->startOfMonth();
        $end = $start->copy()->endOfMonth();
        $previousStart = $start->copy()->subMonth()->startOfMonth();
        $previousEnd = $previousStart->copy()->endOfMonth();

        $unit = BumdesUnit::where('name', $unitName)->firstOrFail();
        $padesRate = $padesPercentage ?? (float) $unit->pades_percentage;
        $saldoAwal = $this->balanceUntil($unit, $start->copy()->subDay());
        $currentTransactions = $unit->transactions()->whereBetween('transaction_date', [$start, $end])->get();
        $previousTransactions = $unit->transactions()->whereBetween('transaction_date', [$previousStart, $previousEnd])->get();
        $income = (float) $currentTransactions->where('type', 'income')->sum('amount');
        $expense = (float) $currentTransactions->where('type', 'expense')->sum('amount');
        $previousIncome = (float) $previousTransactions->where('type', 'income')->sum('amount');
        $previousExpense = (float) $previousTransactions->where('type', 'expense')->sum('amount');
        $netProfit = $income - $expense;
        $previousProfit = $previousIncome - $previousExpense;
        $pades = max(0, $netProfit * ($padesRate / 100));
        $saldoAkhir = $saldoAwal + $netProfit - $pades;

        return [
            'success' => true,
            'bumdes' => $bumdesName,
            'unit_usaha' => $unit->name,
            'periode' => $start->translatedFormat('F Y'),
            'saldo_awal' => $this->formatRupiah($saldoAwal),
            'total_pemasukan_per_kategori' => $this->totalsByCategory($currentTransactions, 'income'),
            'total_pengeluaran_per_kategori' => $this->totalsByCategory($currentTransactions, 'expense'),
            'laba_rugi_bersih' => $this->formatRupiah($netProfit),
            'kontribusi_pades' => [
                'persentase' => number_format($padesRate, 1, ',', '.').'%',
                'nominal' => $this->formatRupiah($pades),
            ],
            'saldo_akhir' => $this->formatRupiah($saldoAkhir),
            'perbandingan_vs_bulan_lalu' => [
                'bulan_lalu' => $this->formatRupiah($previousProfit),
                'bulan_ini' => $this->formatRupiah($netProfit),
                'status' => $this->trend($netProfit, $previousProfit),
            ],
        ];
    }

    private function periodRange(?string $period): array
    {
        $start = $period ? Carbon::parse($period.'-01')->startOfMonth() : now()->startOfMonth();

        return [$start, $start->copy()->endOfMonth()];
    }

    private function reportForRange(?string $villageName, Carbon $start, Carbon $end): array
    {
        $units = BumdesUnit::query()
            ->when($villageName, fn ($query) => $query->where('village_name', $villageName))
            ->with(['transactions' => fn ($query) => $query->whereBetween('transaction_date', [$start, $end])])
            ->get();

        $unitReports = $units->map(function (BumdesUnit $unit) {
            $income = (float) $unit->transactions->where('type', 'income')->sum('amount');
            $expense = (float) $unit->transactions->where('type', 'expense')->sum('amount');
            $netProfit = $income - $expense;
            $pades = max(0, $netProfit * ((float) $unit->pades_percentage / 100));

            return [
                'unit_id' => $unit->id,
                'unit' => $unit->name,
                'pemasukan' => $this->formatRupiah($income),
                'pengeluaran' => $this->formatRupiah($expense),
                'laba_bersih' => $this->formatRupiah($netProfit),
                'kontribusi_pades' => $this->formatRupiah($pades),
            ];
        });

        $totalIncome = $units->flatMap->transactions->where('type', 'income')->sum('amount');
        $totalExpense = $units->flatMap->transactions->where('type', 'expense')->sum('amount');
        $totalPades = $units->sum(function (BumdesUnit $unit) {
            $income = (float) $unit->transactions->where('type', 'income')->sum('amount');
            $expense = (float) $unit->transactions->where('type', 'expense')->sum('amount');

            return max(0, ($income - $expense) * ((float) $unit->pades_percentage / 100));
        });

        return [
            'success' => true,
            'message' => 'Laporan konsolidasi BUMDes berhasil dibuat.',
            'periode' => $start->format('d/m/Y').' - '.$end->format('d/m/Y'),
            'total_pemasukan' => $this->formatRupiah((float) $totalIncome),
            'total_pengeluaran' => $this->formatRupiah((float) $totalExpense),
            'laba_bersih' => $this->formatRupiah((float) $totalIncome - (float) $totalExpense),
            'kontribusi_pades' => $this->formatRupiah((float) $totalPades),
            'unit_usaha' => $unitReports->values(),
        ];
    }

    private function balanceUntil(BumdesUnit $unit, Carbon $date): float
    {
        $transactions = $unit->transactions()->whereDate('transaction_date', '<=', $date->toDateString())->get();

        return (float) $transactions->where('type', 'income')->sum('amount') - (float) $transactions->where('type', 'expense')->sum('amount');
    }

    private function totalsByCategory($transactions, string $type): array
    {
        return $transactions
            ->where('type', $type)
            ->groupBy('category')
            ->map(fn ($items) => $this->formatRupiah((float) $items->sum('amount')))
            ->all();
    }

    private function trend(float $current, float $previous): string
    {
        if ($previous == 0.0 && $current > 0.0) {
            return 'naik ↑ 100%';
        }
        if ($previous == 0.0) {
            return 'tetap 0%';
        }

        $change = (($current - $previous) / abs($previous)) * 100;

        return ($change >= 0 ? 'naik ↑ ' : 'turun ↓ ').number_format(abs($change), 1, ',', '.').'%';
    }

    private function formatRupiah(float $value): string
    {
        return 'Rp '.number_format($value, 0, ',', '.');
    }
}
