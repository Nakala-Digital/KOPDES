<?php

namespace App\Services;

use App\Models\BumdesUnit;
use App\Models\BumdesUnitTransaction;
use App\Models\KoperasiInstallment;
use App\Models\KoperasiLoan;
use App\Models\KoperasiMember;
use App\Models\KoperasiSaving;
use App\Models\MarketplaceOrder;
use App\Models\MbgFinancialTransaction;
use App\Models\MbgIncident;
use App\Models\MbgOrder;
use App\Models\MbgSupplierConfirmation;
use App\Models\Product;
use App\Models\UmkmFinancialTransaction;
use App\Models\UmkmProfile;
use App\Models\User;
use Illuminate\Support\Carbon;

class DashboardAgentService
{
    public function load(User $user, ?string $villageName = null, ?Carbon $date = null): array
    {
        $date ??= now();
        $villageName ??= $user->village_name ?? 'Semua Desa';
        $role = $user->role?->slug ?? 'admin_desa';
        $modules = $this->modulesForRole($role);
        $today = [$date->copy()->startOfDay(), $date->copy()->endOfDay()];
        $yesterday = [$date->copy()->subDay()->startOfDay(), $date->copy()->subDay()->endOfDay()];

        $transactionsToday = $this->transactionsValue($today, $modules, $villageName);
        $transactionsYesterday = $this->transactionsValue($yesterday, $modules, $villageName);
        $weeklyTransactions = $this->transactionsValue([$date->copy()->startOfWeek(), $date->copy()->endOfDay()], $modules, $villageName);
        $activeUmkm = in_array('umkm', $modules, true)
            ? UmkmProfile::whereHas('products.orderItems.order', fn ($query) => $query->whereBetween('created_at', $today))->count()
            : 0;
        $mbgToday = in_array('mbg', $modules, true)
            ? MbgOrder::whereBetween('created_at', $today)->count()
            : 0;
        $koperasiCash = in_array('kopdes', $modules, true) ? $this->koperasiCash() : 0;
        $alerts = $this->alerts($modules, $date);
        $registeredProducts = in_array('umkm', $modules, true) ? Product::count() : 0;
        $criticalStock = in_array('umkm', $modules, true) ? Product::whereColumn('stock', '<=', 'minimum_stock')->count() : 0;
        $activeKoperasiMembers = in_array('kopdes', $modules, true) ? KoperasiMember::where('status', 'active')->count() : 0;
        $activeBumdesUnits = in_array('bumdes', $modules, true) ? BumdesUnit::where('status', 'active')->count() : 0;

        return [
            'user' => ['name' => $user->name, 'role' => $role, 'village_name' => $villageName],
            'date' => $date->format('d/m/Y'),
            'modules' => $modules,
            'summary' => [
                'transactions_today_count' => $transactionsToday['count'],
                'transactions_today_value' => $this->formatRupiah($transactionsToday['value']),
                'transactions_vs_yesterday' => $this->trend($transactionsToday['value'], $transactionsYesterday['value']),
                'active_umkm_transactions' => $activeUmkm,
                'mbg_orders_today' => $mbgToday,
                'koperasi_cash' => $this->formatRupiah($koperasiCash),
            ],
            'alerts' => $alerts,
            'stats' => [
                'registered_products' => $registeredProducts,
                'critical_stock' => $criticalStock,
                'active_koperasi_members' => $activeKoperasiMembers,
                'active_bumdes_units' => $activeBumdesUnits,
                'weekly_transaction_value' => $this->formatRupiah($weeklyTransactions['value']),
            ],
            'charts' => [
                'transactions' => [
                    ['label' => 'Kemarin', 'value' => $transactionsYesterday['value'], 'formatted' => $this->formatRupiah($transactionsYesterday['value'])],
                    ['label' => 'Hari ini', 'value' => $transactionsToday['value'], 'formatted' => $this->formatRupiah($transactionsToday['value'])],
                ],
                'module_activity' => [
                    ['label' => 'UMKM', 'value' => $activeUmkm],
                    ['label' => 'MBG', 'value' => $mbgToday],
                    ['label' => 'Kopdes', 'value' => $activeKoperasiMembers],
                    ['label' => 'BUMDes', 'value' => $activeBumdesUnits],
                ],
                'stock' => [
                    ['label' => 'Aman', 'value' => max(0, $registeredProducts - $criticalStock)],
                    ['label' => 'Kritis', 'value' => $criticalStock],
                ],
                'alerts' => [
                    ['label' => 'Urgent', 'value' => collect($alerts)->where('level', 'urgent')->count()],
                    ['label' => 'Perhatian', 'value' => collect($alerts)->where('level', 'warning')->count()],
                    ['label' => 'Info', 'value' => collect($alerts)->where('level', 'info')->count()],
                ],
            ],
        ];
    }

    public function villageReport(string $villageName, int $month, int $year): array
    {
        $start = Carbon::create($year, $month, 1)->startOfMonth();
        $end = $start->copy()->endOfMonth();
        $transactions = $this->transactionsValue([$start, $end], ['umkm', 'mbg'], $villageName);
        $activeUmkm = UmkmProfile::whereHas('products.orderItems.order', fn ($query) => $query->whereBetween('created_at', [$start, $end]))->count();
        $avgUmkmRevenue = $activeUmkm > 0 ? $transactions['value'] / $activeUmkm : 0;
        $targetPortions = MbgOrder::where('village_name', $villageName)->whereBetween('created_at', [$start, $end])->sum('target_portions');
        $realizedPortions = MbgOrder::where('village_name', $villageName)->whereBetween('created_at', [$start, $end])->sum('realized_portions');
        $mbgPercent = $targetPortions > 0 ? ($realizedPortions / $targetPortions) * 100 : 0;
        $savings = (float) KoperasiSaving::whereHas('member', fn ($query) => $query->where('village_name', $villageName))->whereBetween('created_at', [$start, $end])->sum('amount');
        $loans = (float) KoperasiLoan::whereHas('member', fn ($query) => $query->where('village_name', $villageName))->whereBetween('created_at', [$start, $end])->sum('principal_amount');
        $criticalStock = Product::whereColumn('stock', '<=', 'minimum_stock')->count();
        $lateMbg = MbgSupplierConfirmation::where('status', 'menunggu_konfirmasi')->where('notified_at', '<', now()->subHours(6))->count();
        $arrears = KoperasiInstallment::where('due_date', '<', now()->subDays(60)->toDateString())->where('status', '!=', 'paid')->count();

        return [
            'success' => true,
            'format' => 'Maksimal 1 halaman A4, siap dibawa ke rapat desa.',
            'header' => [
                'logo' => 'Logo Desa',
                'desa' => $villageName,
                'periode' => $start->translatedFormat('F Y'),
            ],
            'ringkasan_eksekutif' => $this->executiveSummary($villageName, $transactions['value'], $activeUmkm, $mbgPercent),
            'indikator_utama' => [
                'total_nilai_transaksi_ekonomi' => $this->formatRupiah($transactions['value']),
                'jumlah_umkm_aktif' => $activeUmkm,
                'pendapatan_rata_rata_umkm' => $this->formatRupiah($avgUmkmRevenue),
                'realisasi_mbg' => number_format($mbgPercent, 1, ',', '.').'%',
                'simpanan_koperasi' => $this->formatRupiah($savings),
                'pinjaman_koperasi' => $this->formatRupiah($loans),
            ],
            'pencapaian_bulan_ini' => [
                'Transaksi ekonomi desa tercatat sebesar '.$this->formatRupiah($transactions['value']).'.',
                'UMKM aktif bertransaksi sebanyak '.$activeUmkm.' pelaku usaha.',
                'Realisasi MBG mencapai '.number_format($mbgPercent, 1, ',', '.').'% dari target.',
            ],
            'perlu_perhatian' => array_slice(array_values(array_filter([
                $criticalStock > 0 ? $criticalStock.' produk memiliki stok kritis.' : null,
                $lateMbg > 0 ? $lateMbg.' supplier MBG belum konfirmasi.' : null,
                $arrears > 0 ? $arrears.' angsuran koperasi menunggak lebih dari 60 hari.' : null,
            ])), 0, 3),
        ];
    }

    private function modulesForRole(string $role): array
    {
        return match ($role) {
            'pengurus_kopdes' => ['kopdes'],
            'pengurus_bumdes' => ['bumdes'],
            'umkm_petani' => ['umkm'],
            'operator_mbg' => ['mbg'],
            'warga' => ['umkm'],
            'pemda_viewer' => ['umkm', 'mbg', 'kopdes', 'bumdes'],
            default => ['umkm', 'mbg', 'kopdes', 'bumdes'],
        };
    }

    private function transactionsValue(array $range, array $modules, string $villageName): array
    {
        $count = 0;
        $value = 0.0;

        if (in_array('umkm', $modules, true)) {
            $query = UmkmFinancialTransaction::whereBetween('created_at', $range);
            $count += (clone $query)->count();
            $value += (float) $query->sum('amount');
        }

        if (in_array('mbg', $modules, true)) {
            $query = MbgFinancialTransaction::whereBetween('created_at', $range);
            if ($villageName !== 'Semua Desa') {
                $query->whereHas('order', fn ($inner) => $inner->where('village_name', $villageName));
            }
            $count += (clone $query)->count();
            $value += (float) $query->sum('amount');
        }

        if (in_array('bumdes', $modules, true)) {
            $query = BumdesUnitTransaction::whereBetween('created_at', $range);
            if ($villageName !== 'Semua Desa') {
                $query->whereHas('unit', fn ($inner) => $inner->where('village_name', $villageName));
            }
            $count += (clone $query)->count();
            $value += (float) $query->where('type', 'income')->sum('amount');
        }

        return ['count' => $count, 'value' => $value];
    }

    private function alerts(array $modules, Carbon $date): array
    {
        $alerts = [];

        if (in_array('mbg', $modules, true)) {
            foreach (MbgSupplierConfirmation::where('status', 'menunggu_konfirmasi')->where('notified_at', '<', $date->copy()->subHours(6))->limit(5)->get() as $item) {
                $alerts[] = ['level' => 'urgent', 'label' => 'URGENT', 'message' => 'Order MBG supplier '.$item->supplier_name.' pending lebih dari 6 jam.'];
            }
            foreach (MbgSupplierConfirmation::where('status', 'menunggu_konfirmasi')->limit(5)->get() as $item) {
                $alerts[] = ['level' => 'warning', 'label' => 'PERHATIAN', 'message' => 'Supplier '.$item->supplier_name.' belum konfirmasi.'];
            }
        }

        if (in_array('umkm', $modules, true)) {
            foreach (Product::whereColumn('stock', '<=', 'minimum_stock')->limit(5)->get() as $product) {
                $alerts[] = ['level' => 'urgent', 'label' => 'URGENT', 'message' => 'Stok '.$product->name.' di bawah minimum.'];
            }
            foreach (Product::where('created_at', '>=', $date->copy()->startOfDay())->limit(5)->get() as $product) {
                $alerts[] = ['level' => 'info', 'label' => 'INFO', 'message' => 'Produk baru ditambahkan: '.$product->name.'.'];
            }
        }

        if (in_array('kopdes', $modules, true)) {
            foreach (KoperasiInstallment::where('due_date', '<', $date->copy()->subDays(60)->toDateString())->where('status', '!=', 'paid')->limit(5)->get() as $installment) {
                $alerts[] = ['level' => 'urgent', 'label' => 'URGENT', 'message' => 'Ada tunggakan koperasi lebih dari 60 hari.'];
            }
            foreach (KoperasiMember::where('created_at', '>=', $date->copy()->startOfDay())->limit(5)->get() as $member) {
                $alerts[] = ['level' => 'info', 'label' => 'INFO', 'message' => 'Anggota koperasi baru: '.$member->name.'.'];
            }
        }

        if (in_array('bumdes', $modules, true)) {
            foreach (BumdesUnitTransaction::where('created_at', '>=', $date->copy()->startOfDay())->limit(5)->get() as $transaction) {
                $alerts[] = ['level' => 'info', 'label' => 'INFO', 'message' => 'Transaksi BUMDes baru: '.$this->formatRupiah((float) $transaction->amount).'.'];
            }
        }

        $order = ['urgent' => 1, 'warning' => 2, 'info' => 3];
        usort($alerts, fn ($a, $b) => $order[$a['level']] <=> $order[$b['level']]);

        return $alerts;
    }

    private function koperasiCash(): float
    {
        return (float) KoperasiSaving::sum('amount') - (float) KoperasiLoan::where('status', 'active')->sum('principal_amount');
    }

    private function trend(float $today, float $yesterday): string
    {
        if ($yesterday <= 0 && $today > 0) {
            return 'naik ↑ 100%';
        }
        if ($yesterday <= 0) {
            return 'tetap 0%';
        }

        $change = (($today - $yesterday) / $yesterday) * 100;

        return ($change >= 0 ? 'naik ↑ ' : 'turun ↓ ').number_format(abs($change), 1, ',', '.').'%';
    }

    private function executiveSummary(string $villageName, float $value, int $activeUmkm, float $mbgPercent): string
    {
        return 'Kondisi ekonomi '.$villageName.' pada periode ini tercatat dengan nilai transaksi '.$this->formatRupiah($value).'. '.
            'Sebanyak '.$activeUmkm.' UMKM aktif bertransaksi dan menjadi penggerak utama ekonomi lokal. '.
            'Program MBG terealisasi '.number_format($mbgPercent, 1, ',', '.').'% dari target. '.
            'Beberapa hal tetap perlu dipantau, terutama stok produk, konfirmasi supplier, dan kedisiplinan angsuran koperasi.';
    }

    private function formatRupiah(float $value): string
    {
        return 'Rp '.number_format($value, 0, ',', '.');
    }
}
