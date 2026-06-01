<?php

namespace App\Services;

use App\Models\MbgDistribution;
use App\Models\MbgFinancialTransaction;
use App\Models\MbgIncident;
use App\Models\MbgNotification;
use App\Models\MbgOrder;
use App\Models\MbgSupplierConfirmation;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class MbgSupplyChainAgentService
{
    public const SUPPLIER_TYPES = ['desa_sendiri', 'desa_tetangga', 'supplier_luar'];

    public function createOrder(array $data): array
    {
        $order = MbgOrder::create([
            'order_number' => $data['order_number'] ?? $this->generateOrderNumber(),
            'village_name' => $data['village_name'],
            'beneficiary_name' => $data['beneficiary_name'],
            'target_portions' => $data['target_portions'],
            'distribution_date' => $data['distribution_date'] ?? null,
            'status' => 'approved',
        ]);

        return [
            'success' => true,
            'message' => 'Order MBG berhasil dibuat dan siap dikirim ke supplier.',
            'order_id' => $order->id,
            'order_number' => $order->order_number,
        ];
    }

    public function notifySuppliers(int $orderId, array $suppliers): array
    {
        $order = MbgOrder::findOrFail($orderId);
        $items = [];

        foreach ($suppliers as $supplier) {
            $supplierType = $supplier['supplier_type'] ?? 'desa_sendiri';
            $estimatedUnitPrice = (float) ($supplier['estimated_unit_price'] ?? 0);
            $estimatedTotal = (float) $supplier['quantity'] * $estimatedUnitPrice;
            $risk = $this->riskNote($supplierType, $supplier['delivery_date']);

            $confirmation = MbgSupplierConfirmation::create([
                'mbg_order_id' => $order->id,
                'supplier_name' => $supplier['supplier_name'],
                'supplier_type' => $supplierType,
                'product_name' => $supplier['product_name'],
                'quantity' => $supplier['quantity'],
                'unit' => $supplier['unit'],
                'delivery_date' => $supplier['delivery_date'],
                'estimated_unit_price' => $estimatedUnitPrice,
                'estimated_total_cost' => $estimatedTotal,
                'status' => 'menunggu_konfirmasi',
                'notified_at' => now(),
                'reminder_due_at' => now()->addHours(12),
                'confirmation_due_at' => now()->addHours(24),
                'admin_approval_required' => $supplierType === 'supplier_luar',
                'risk_note' => $risk,
            ]);

            $message = 'Order MBG '.$order->order_number.': mohon konfirmasi kesanggupan menyediakan '.$supplier['product_name'].' '.$this->formatNumber((float) $supplier['quantity']).' '.$supplier['unit'].' untuk tanggal kirim '.$supplier['delivery_date'].'. Batas konfirmasi 24 jam.';
            $this->notify($order, $confirmation, 'supplier', $supplier['supplier_name'], 'Konfirmasi order MBG', $message, 'in_app');
            $this->notify($order, $confirmation, 'supplier', $supplier['supplier_name'], 'SMS Konfirmasi order MBG', $message, 'sms');
            $this->notify($order, $confirmation, 'system', 'Reminder otomatis', 'Reminder 12 jam', 'Jika supplier belum konfirmasi dalam 12 jam, sistem mengirim pengingat.', 'reminder');

            if ($supplierType === 'supplier_luar') {
                $this->notify($order, $confirmation, 'admin_desa', 'Admin Desa', 'Persetujuan supplier luar', 'Supplier luar perlu persetujuan admin sebelum dipakai.', 'in_app');
            }

            $items[] = $this->confirmationSummary($confirmation);
        }

        return [
            'success' => true,
            'message' => 'Konfirmasi supplier sudah dikirim melalui in-app dan SMS.',
            'checklist' => [
                'order_disetujui' => true,
                'supplier_dinotifikasi' => true,
                'sms_dikirim' => true,
                'reminder_12_jam_dibuat' => true,
                'batas_konfirmasi_24_jam_dibuat' => true,
                'pencatatan_database' => true,
            ],
            'estimasi' => [
                'total_biaya' => $this->formatRupiah(collect($items)->sum('estimated_total_cost_raw')),
                'waktu_konfirmasi' => 'maksimal 24 jam',
            ],
            'risiko_keterlambatan' => collect($items)->contains(fn ($item) => $item['risk_flag']) ? 'ada' : 'rendah',
            'suppliers' => $items,
        ];
    }

    public function confirmSupplier(int $confirmationId, bool $canFulfill): array
    {
        $confirmation = MbgSupplierConfirmation::with('order')->findOrFail($confirmationId);

        if (! $canFulfill) {
            return $this->markSupplierFailed($confirmationId, 'Supplier menyatakan tidak sanggup memenuhi komitmen.');
        }

        $confirmation->forceFill([
            'status' => 'dikonfirmasi',
            'confirmed_at' => now(),
        ])->save();

        MbgFinancialTransaction::create([
            'mbg_order_id' => $confirmation->mbg_order_id,
            'supplier_confirmation_id' => $confirmation->id,
            'supplier_name' => $confirmation->supplier_name,
            'supplier_type' => $confirmation->supplier_type,
            'amount' => $confirmation->estimated_total_cost,
            'description' => 'Komitmen supply MBG: '.$confirmation->product_name,
            'transaction_date' => now()->toDateString(),
        ]);

        return [
            'success' => true,
            'message' => 'Supplier sudah konfirmasi dan komitmen supply dicatat.',
            'checklist' => [
                'supplier_konfirmasi' => true,
                'komitmen_dicatat' => true,
                'transaksi_keuangan_dicatat' => true,
            ],
            'summary' => $this->confirmationSummary($confirmation->fresh()),
        ];
    }

    public function markSupplierFailed(int $confirmationId, string $reason): array
    {
        $confirmation = MbgSupplierConfirmation::with('order')->findOrFail($confirmationId);
        $confirmation->forceFill([
            'status' => 'gagal',
            'failed_at' => now(),
            'risk_note' => $reason,
        ])->save();

        MbgIncident::create([
            'mbg_order_id' => $confirmation->mbg_order_id,
            'supplier_confirmation_id' => $confirmation->id,
            'type' => 'supplier_gagal',
            'description' => $reason,
        ]);

        $this->notify($confirmation->order, $confirmation, 'admin_desa', 'Admin Desa', 'Supplier gagal memenuhi MBG', 'Supplier '.$confirmation->supplier_name.' gagal memenuhi '.$confirmation->product_name.'. Mohon cari pengganti segera.', 'in_app');

        return [
            'success' => true,
            'message' => 'Supplier ditandai gagal. Admin desa sudah diberi peringatan untuk mencari pengganti.',
            'checklist' => [
                'supplier_diflag_gagal' => true,
                'admin_desa_diberi_alert' => true,
                'butuh_supplier_pengganti' => true,
            ],
            'alternatif_supplier' => $this->alternativeSupplierAdvice($confirmation),
            'summary' => $this->confirmationSummary($confirmation->fresh()),
        ];
    }

    public function processSupplierDeadlines(): array
    {
        $reminders = MbgSupplierConfirmation::with('order')
            ->where('status', 'menunggu_konfirmasi')
            ->where('reminder_due_at', '<=', now())
            ->get();
        $expired = MbgSupplierConfirmation::with('order')
            ->where('status', 'menunggu_konfirmasi')
            ->where('confirmation_due_at', '<=', now())
            ->get();

        foreach ($reminders as $confirmation) {
            $this->notify($confirmation->order, $confirmation, 'supplier', $confirmation->supplier_name, 'Pengingat konfirmasi MBG', 'Mohon segera konfirmasi kesanggupan supply MBG. Batas maksimal 24 jam.', 'reminder_sent');
        }

        foreach ($expired as $confirmation) {
            $confirmation->forceFill(['status' => 'terlambat_konfirmasi'])->save();
            MbgIncident::create([
                'mbg_order_id' => $confirmation->mbg_order_id,
                'supplier_confirmation_id' => $confirmation->id,
                'type' => 'konfirmasi_terlambat',
                'description' => 'Supplier belum konfirmasi lebih dari 24 jam.',
            ]);
            $this->notify($confirmation->order, $confirmation, 'admin_desa', 'Admin Desa', 'Supplier belum konfirmasi', 'Supplier '.$confirmation->supplier_name.' belum konfirmasi lebih dari 24 jam. Mohon cari alternatif.', 'in_app');
        }

        return [
            'success' => true,
            'message' => 'Pengecekan reminder dan batas 24 jam selesai.',
            'reminder_terkirim' => $reminders->count(),
            'flag_admin' => $expired->count(),
        ];
    }

    public function recordDistribution(array $data): array
    {
        $distribution = MbgDistribution::create([
            'mbg_order_id' => $data['mbg_order_id'],
            'school_name' => $data['school_name'],
            'target_portions' => $data['target_portions'],
            'realized_portions' => $data['realized_portions'],
            'distributed_at' => $data['distributed_at'],
            'status' => $data['status'] ?? 'selesai',
            'notes' => $data['notes'] ?? null,
        ]);

        $order = MbgOrder::find($data['mbg_order_id']);
        $order?->forceFill([
            'realized_portions' => $order->distributions()->sum('realized_portions'),
        ])->save();

        return [
            'success' => true,
            'message' => 'Realisasi distribusi MBG berhasil dicatat.',
            'distribution_id' => $distribution->id,
        ];
    }

    public function monthlyReport(string $villageName, int $month, int $year): array
    {
        $start = Carbon::create($year, $month, 1)->startOfMonth();
        $end = $start->copy()->endOfMonth();
        $orders = MbgOrder::with(['supplierConfirmations', 'distributions'])
            ->where('village_name', $villageName)
            ->whereBetween('created_at', [$start, $end])
            ->get();
        $confirmations = MbgSupplierConfirmation::whereHas('order', fn ($query) => $query
            ->where('village_name', $villageName)
            ->whereBetween('created_at', [$start, $end])
        )->get();
        $transactions = MbgFinancialTransaction::whereHas('order', fn ($query) => $query
            ->where('village_name', $villageName)
            ->whereBetween('created_at', [$start, $end])
        )->get();
        $incidents = MbgIncident::whereHas('order', fn ($query) => $query
            ->where('village_name', $villageName)
            ->whereBetween('created_at', [$start, $end])
        )->get();

        $target = (int) $orders->sum('target_portions');
        $realized = (int) $orders->sum('realized_portions');
        $localValue = (float) $transactions->whereIn('supplier_type', ['desa_sendiri', 'desa_tetangga'])->sum('amount');
        $ownVillageValue = (float) $transactions->where('supplier_type', 'desa_sendiri')->sum('amount');
        $totalValue = (float) $transactions->sum('amount');
        $localPercentage = $totalValue > 0 ? ($ownVillageValue / $totalValue) * 100 : 0;

        return [
            'success' => true,
            'title' => 'Laporan Realisasi MBG '.$villageName.' - '.$start->translatedFormat('F Y'),
            'format' => 'Siap dilaporkan ke Dinas/Kecamatan',
            'checklist' => [
                'data_order_mbg' => $orders->count() > 0,
                'data_supplier' => $confirmations->count() > 0,
                'data_distribusi' => $orders->sum('realized_portions') > 0,
                'data_keuangan' => $transactions->count() > 0,
                'insiden_dicatat' => true,
            ],
            'estimasi' => [
                'nilai_transaksi_total' => $this->formatRupiah($totalValue),
                'risiko_keterlambatan' => $incidents->whereIn('type', ['konfirmasi_terlambat', 'supplier_gagal'])->count() > 0 ? 'ada' : 'rendah',
            ],
            'laporan' => [
                'total_distribusi' => [
                    'target_porsi' => $target,
                    'realisasi_porsi' => $realized,
                    'persentase_realisasi' => $target > 0 ? number_format(($realized / $target) * 100, 1, ',', '.').'%' : '0%',
                ],
                'nilai_transaksi_supplier_lokal' => $this->formatRupiah($localValue),
                'persentase_bahan_produk_lokal_desa' => number_format($localPercentage, 1, ',', '.').'%',
                'sekolah_penerima_terlayani' => $orders->flatMap->distributions->pluck('school_name')->unique()->values()->all(),
                'supplier_lokal_terlibat' => $transactions
                    ->whereIn('supplier_type', ['desa_sendiri', 'desa_tetangga'])
                    ->groupBy('supplier_name')
                    ->map(fn ($rows, $name) => ['supplier' => $name, 'nilai_kontribusi' => $this->formatRupiah((float) $rows->sum('amount'))])
                    ->values()
                    ->all(),
                'insiden' => $incidents->map(fn (MbgIncident $incident) => [
                    'jenis' => $incident->type,
                    'keterangan' => $incident->description,
                    'status' => $incident->status,
                ])->values()->all(),
                'rekomendasi_bulan_depan' => $this->recommendations($incidents, $localPercentage),
            ],
        ];
    }

    public function formatRupiah(float $value): string
    {
        return 'Rp '.number_format($value, 0, ',', '.');
    }

    private function notify(MbgOrder $order, ?MbgSupplierConfirmation $confirmation, string $recipientType, string $recipientName, string $title, string $message, string $channel): void
    {
        MbgNotification::create([
            'mbg_order_id' => $order->id,
            'supplier_confirmation_id' => $confirmation?->id,
            'recipient_type' => $recipientType,
            'recipient_name' => $recipientName,
            'title' => $title,
            'message' => $message,
            'channel' => $channel,
            'send_at' => now(),
        ]);
    }

    private function confirmationSummary(MbgSupplierConfirmation $confirmation): array
    {
        return [
            'supplier_id' => $confirmation->id,
            'nama_supplier' => $confirmation->supplier_name,
            'prioritas' => $this->supplierPriorityLabel($confirmation->supplier_type),
            'produk' => $confirmation->product_name,
            'jumlah' => $this->formatNumber((float) $confirmation->quantity).' '.$confirmation->unit,
            'tanggal_kirim' => $confirmation->delivery_date->toDateString(),
            'status' => $confirmation->status,
            'estimasi_biaya' => $this->formatRupiah((float) $confirmation->estimated_total_cost),
            'estimated_total_cost_raw' => (float) $confirmation->estimated_total_cost,
            'estimasi_waktu' => 'konfirmasi maksimal 24 jam, kirim tanggal '.$confirmation->delivery_date->format('d/m/Y'),
            'risk_flag' => (bool) $confirmation->risk_note,
            'risiko' => $confirmation->risk_note ?: 'Tidak ada risiko besar saat ini.',
        ];
    }

    private function riskNote(string $supplierType, string $deliveryDate): ?string
    {
        if ($supplierType === 'supplier_luar') {
            return 'Supplier luar perlu persetujuan admin dan berisiko lebih lambat.';
        }

        if (Carbon::parse($deliveryDate)->isBefore(now()->addDay())) {
            return 'Tanggal kirim sangat dekat. Perlu dipantau.';
        }

        return null;
    }

    private function supplierPriorityLabel(string $supplierType): string
    {
        return match ($supplierType) {
            'desa_sendiri' => 'Prioritas 1 - Petani/UMKM desa sendiri',
            'desa_tetangga' => 'Prioritas 2 - UMKM desa tetangga',
            default => 'Prioritas 3 - Supplier luar, perlu persetujuan admin',
        };
    }

    private function alternativeSupplierAdvice(MbgSupplierConfirmation $confirmation): array
    {
        return [
            'cari_desa_sendiri' => 'Cek Petani/UMKM desa sendiri untuk '.$confirmation->product_name.'.',
            'jika_tidak_cukup' => 'Hubungi UMKM desa tetangga.',
            'last_resort' => 'Supplier luar hanya dipakai jika admin menyetujui.',
        ];
    }

    private function recommendations($incidents, float $localPercentage): array
    {
        $items = ['Kirim konfirmasi supplier lebih awal untuk mengurangi risiko terlambat.'];

        if ($localPercentage < 70) {
            $items[] = 'Perkuat pendataan produk lokal desa agar bahan dari desa sendiri meningkat.';
        }

        if ($incidents->count() > 0) {
            $items[] = 'Buat daftar supplier cadangan untuk bahan yang sering bermasalah.';
        }

        return $items;
    }

    private function generateOrderNumber(): string
    {
        do {
            $number = 'MBG-'.now()->format('Ymd').'-'.Str::upper(Str::random(5));
        } while (MbgOrder::where('order_number', $number)->exists());

        return $number;
    }

    private function formatNumber(float $value): string
    {
        return rtrim(rtrim(number_format($value, 2, ',', '.'), '0'), ',');
    }
}
