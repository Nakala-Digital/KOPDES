<?php

namespace App\Services;

use App\Models\MarketplaceOrder;
use App\Models\MarketplaceOrderItem;
use App\Models\Product;
use App\Models\UmkmFinancialTransaction;
use App\Models\UmkmNotification;
use App\Models\UmkmProfile;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class UmkmAgentService
{
    public function addProduct(array $data): array
    {
        if (! ($data['confirmed'] ?? false)) {
            return $this->confirmationNeeded('Sebelum produk disimpan, mohon cek kembali nama produk, harga, stok, dan foto.');
        }

        $umkm = UmkmProfile::findOrFail($data['umkm_profile_id']);

        $product = Product::create([
            'umkm_profile_id' => $umkm->id,
            'name' => $data['name'],
            'description' => $data['description'],
            'price' => $data['price'],
            'unit' => $data['unit'],
            'stock' => $data['stock'],
            'minimum_stock' => $data['minimum_stock'],
            'category' => $data['category'],
            'photo_url' => $data['photo_url'] ?? null,
            'status' => 'aktif',
            'is_marketplace_visible' => true,
        ]);

        $this->notify($umkm, null, 'seller', $umkm->business_name, 'Produk baru sudah tampil', 'Produk '.$product->name.' sudah masuk katalog marketplace desa.');
        $this->notifyLowStockIfNeeded($product);

        return [
            'success' => true,
            'message' => 'Produk berhasil disimpan dan sudah tampil di katalog desa.',
            'product_id' => $product->id,
            'preview_url' => url('/marketplace/products/'.$product->id),
            'summary' => [
                'nama_produk' => $product->name,
                'harga' => $this->formatRupiah((float) $product->price).' per '.$product->unit,
                'stok' => $this->formatNumber((float) $product->stock).' '.$product->unit,
                'status' => $product->status,
            ],
            'langkah_berikutnya' => [
                'Cek foto dan nama produk di katalog.',
                'Pastikan stok selalu diperbarui.',
                'Jika ada pesanan, segera konfirmasi paling lambat 24 jam.',
            ],
        ];
    }

    public function createIncomingOrder(array $data): array
    {
        $product = Product::with('umkm')->findOrFail($data['product_id']);
        $quantity = (float) $data['quantity'];

        if ((float) $product->stock < $quantity) {
            $this->notify($product->umkm, null, 'buyer', $data['buyer_name'], 'Stok belum cukup', 'Maaf, stok '.$product->name.' belum cukup. Silakan pilih jumlah lebih sedikit atau produk lain.');

            return [
                'success' => false,
                'message' => 'Stok produk belum cukup. Pesanan ditolak agar penjual tidak kewalahan.',
                'available_stock' => $this->formatNumber((float) $product->stock).' '.$product->unit,
                'alternatives' => $this->alternativeProducts($product),
            ];
        }

        $order = DB::transaction(function () use ($data, $product, $quantity) {
            $total = $quantity * (float) $product->price;
            $order = MarketplaceOrder::create([
                'umkm_profile_id' => $product->umkm_profile_id,
                'order_number' => $data['order_number'] ?? $this->generateOrderNumber(),
                'buyer_name' => $data['buyer_name'],
                'buyer_phone' => $data['buyer_phone'] ?? null,
                'shipping_address' => $data['shipping_address'],
                'notes' => $data['notes'] ?? null,
                'total_amount' => $total,
                'status' => 'menunggu_konfirmasi',
            ]);

            MarketplaceOrderItem::create([
                'order_id' => $order->id,
                'product_id' => $product->id,
                'quantity' => $quantity,
                'unit' => $product->unit,
                'price' => $product->price,
                'subtotal' => $total,
            ]);

            return $order;
        });

        $this->notify($product->umkm, $order, 'seller', $product->umkm->business_name, 'Ada pesanan baru', 'Ada pesanan '.$product->name.' sebanyak '.$this->formatNumber($quantity).' '.$product->unit.'. Mohon konfirmasi sebelum 24 jam.');
        $this->notify($product->umkm, $order, 'buyer', $order->buyer_name, 'Pesanan diterima', 'Pesanan Anda sudah diterima dan menunggu konfirmasi penjual.');

        return [
            'success' => true,
            'message' => 'Pesanan masuk. Penjual sudah diberi notifikasi untuk segera konfirmasi.',
            'order_id' => $order->id,
            'summary' => $this->orderSummary($order->fresh(['items.product', 'umkm'])),
        ];
    }

    public function confirmOrder(int $orderId, bool $confirmed): array
    {
        if (! $confirmed) {
            return $this->confirmationNeeded('Mohon pastikan stok dan alamat pembeli sudah benar sebelum pesanan diproses.');
        }

        $order = MarketplaceOrder::with(['items.product', 'umkm'])->findOrFail($orderId);

        if ($order->status !== 'menunggu_konfirmasi') {
            return [
                'success' => false,
                'message' => 'Pesanan ini tidak bisa dikonfirmasi lagi karena statusnya sudah berubah.',
            ];
        }

        foreach ($order->items as $item) {
            if ((float) $item->product->stock < (float) $item->quantity) {
                $this->notify($order->umkm, $order, 'buyer', $order->buyer_name, 'Stok belum cukup', 'Maaf, stok produk belum cukup. Penjual akan menghubungi Anda.');

                return [
                    'success' => false,
                    'message' => 'Stok tidak cukup. Pesanan belum bisa diproses.',
                    'product' => $item->product->name,
                    'available_stock' => $this->formatNumber((float) $item->product->stock).' '.$item->unit,
                ];
            }
        }

        DB::transaction(function () use ($order) {
            foreach ($order->items as $item) {
                $product = $item->product;
                $product->forceFill(['stock' => (float) $product->stock - (float) $item->quantity])->save();
                $this->notifyLowStockIfNeeded($product->fresh());
            }

            $order->forceFill([
                'status' => 'diproses',
                'confirmed_at' => now(),
            ])->save();
        });

        $this->notify($order->umkm, $order, 'buyer', $order->buyer_name, 'Pesanan sedang diproses', 'Pesanan Anda sudah dikonfirmasi penjual dan sedang disiapkan.');

        return [
            'success' => true,
            'message' => 'Pesanan berhasil dikonfirmasi. Stok produk sudah otomatis berkurang.',
            'summary' => $this->orderSummary($order->fresh(['items.product', 'umkm'])),
        ];
    }

    public function markAsShipped(int $orderId): array
    {
        $order = MarketplaceOrder::with(['items.product', 'umkm'])->findOrFail($orderId);

        if ($order->status !== 'diproses') {
            return [
                'success' => false,
                'message' => 'Pesanan hanya bisa ditandai dikirim jika statusnya sedang diproses.',
            ];
        }

        DB::transaction(function () use ($order) {
            $order->forceFill([
                'status' => 'dikirim',
                'shipped_at' => now(),
            ])->save();

            UmkmFinancialTransaction::create([
                'umkm_profile_id' => $order->umkm_profile_id,
                'order_id' => $order->id,
                'type' => 'penjualan',
                'amount' => $order->total_amount,
                'description' => 'Penjualan dari pesanan '.$order->order_number,
                'transaction_date' => now()->toDateString(),
            ]);
        });

        $this->notify($order->umkm, $order, 'buyer', $order->buyer_name, 'Pesanan dikirim', 'Pesanan Anda sudah dikirim. Mohon ditunggu ya.');

        return [
            'success' => true,
            'message' => 'Pesanan ditandai dikirim dan transaksi penjualan sudah dicatat.',
            'summary' => $this->orderSummary($order->fresh(['items.product', 'umkm'])),
        ];
    }

    public function alertOverdueOrders(): array
    {
        $orders = MarketplaceOrder::with('umkm')
            ->where('status', 'menunggu_konfirmasi')
            ->where('created_at', '<', now()->subHours(24))
            ->get();

        foreach ($orders as $order) {
            $this->notify($order->umkm, $order, 'admin_desa', 'Admin Desa', 'Pesanan belum dikonfirmasi', 'Pesanan '.$order->order_number.' belum dikonfirmasi lebih dari 24 jam. Mohon bantu hubungi UMKM.');
        }

        return [
            'success' => true,
            'message' => 'Pengecekan pesanan terlambat selesai.',
            'total_alert' => $orders->count(),
        ];
    }

    public function salesReport(int $umkmProfileId, ?string $period): array
    {
        $umkm = UmkmProfile::findOrFail($umkmProfileId);
        [$start, $end] = $this->periodRange($period);
        $previousStart = $start->copy()->subDays($start->diffInDays($end) + 1);
        $previousEnd = $start->copy()->subDay();

        $orders = MarketplaceOrder::with(['items.product'])
            ->where('umkm_profile_id', $umkm->id)
            ->whereBetween('created_at', [$start, $end])
            ->get();
        $previousTurnover = (float) MarketplaceOrder::where('umkm_profile_id', $umkm->id)
            ->whereIn('status', ['dikirim', 'selesai'])
            ->whereBetween('created_at', [$previousStart, $previousEnd])
            ->sum('total_amount');
        $turnover = (float) $orders->whereIn('status', ['dikirim', 'selesai'])->sum('total_amount');
        $change = $previousTurnover > 0 ? (($turnover - $previousTurnover) / $previousTurnover) * 100 : ($turnover > 0 ? 100 : 0);
        $soldItems = $orders->whereIn('status', ['dikirim', 'selesai'])->flatMap->items;
        $topProducts = $soldItems
            ->groupBy('product_id')
            ->map(fn ($items) => [
                'produk' => $items->first()->product?->name,
                'jumlah_terjual' => $this->formatNumber((float) $items->sum('quantity')).' '.$items->first()->unit,
            ])
            ->values()
            ->take(3)
            ->all();
        $lowStock = Product::where('umkm_profile_id', $umkm->id)
            ->orderBy('stock')
            ->limit(5)
            ->get()
            ->map(fn (Product $product) => [
                'produk' => $product->name,
                'stok' => $this->formatNumber((float) $product->stock).' '.$product->unit,
                'batas_minimum' => $this->formatNumber((float) $product->minimum_stock).' '.$product->unit,
            ])
            ->all();
        $frequentBuyer = $orders->groupBy('buyer_name')
            ->map(fn ($buyerOrders, $name) => ['nama' => $name, 'jumlah_pesanan' => $buyerOrders->count()])
            ->sortByDesc('jumlah_pesanan')
            ->values()
            ->first();

        return [
            'success' => true,
            'message' => '📊 Laporan penjualan berhasil dibuat. Berikut ringkasannya dengan bahasa sederhana.',
            'period' => $period ?? 'bulan ini',
            'summary' => [
                'pesanan_masuk' => $orders->count(),
                'pesanan_selesai' => $orders->whereIn('status', ['dikirim', 'selesai'])->count(),
                'pesanan_dibatalkan' => $orders->where('status', 'dibatalkan')->count(),
                'omzet_periode_ini' => $this->formatRupiah($turnover),
                'omzet_periode_sebelumnya' => $this->formatRupiah($previousTurnover),
                'perubahan_omzet' => ($change >= 0 ? 'naik ' : 'turun ').number_format(abs($change), 1, ',', '.').'%',
                'produk_terlaris' => $topProducts,
                'stok_paling_menipis' => $lowStock,
                'rata_rata_nilai_pesanan' => $this->formatRupiah($orders->count() ? ((float) $orders->sum('total_amount') / $orders->count()) : 0),
                'pembeli_paling_sering' => $frequentBuyer ?: ['nama' => '-', 'jumlah_pesanan' => 0],
            ],
            'saran_sederhana' => [
                'Cek produk yang stoknya menipis.',
                'Hubungi pembeli yang sering membeli untuk promo ringan.',
                'Konfirmasi pesanan baru secepat mungkin.',
            ],
        ];
    }

    public function formatRupiah(float $value): string
    {
        return 'Rp '.number_format($value, 0, ',', '.');
    }

    private function notify(?UmkmProfile $umkm, ?MarketplaceOrder $order, string $recipientType, ?string $recipientName, string $title, string $message): void
    {
        UmkmNotification::create([
            'umkm_profile_id' => $umkm?->id,
            'order_id' => $order?->id,
            'recipient_type' => $recipientType,
            'recipient_name' => $recipientName,
            'title' => $title,
            'message' => $message,
        ]);
    }

    private function notifyLowStockIfNeeded(Product $product): void
    {
        if ((float) $product->stock <= (float) $product->minimum_stock) {
            $this->notify($product->umkm, null, 'seller', $product->umkm?->business_name, 'Stok mulai menipis', 'Stok '.$product->name.' tinggal '.$this->formatNumber((float) $product->stock).' '.$product->unit.'. Mohon tambah stok jika masih tersedia.');
        }
    }

    private function alternativeProducts(Product $product): array
    {
        return Product::where('umkm_profile_id', $product->umkm_profile_id)
            ->where('id', '!=', $product->id)
            ->where('status', 'aktif')
            ->where('stock', '>', 0)
            ->limit(3)
            ->get()
            ->map(fn (Product $item) => [
                'nama_produk' => $item->name,
                'stok' => $this->formatNumber((float) $item->stock).' '.$item->unit,
                'harga' => $this->formatRupiah((float) $item->price),
            ])
            ->all();
    }

    private function orderSummary(MarketplaceOrder $order): array
    {
        $item = $order->items->first();

        return [
            'nomor_pesanan' => $order->order_number,
            'pembeli' => $order->buyer_name,
            'produk' => $item?->product?->name,
            'jumlah' => $item ? $this->formatNumber((float) $item->quantity).' '.$item->unit : '-',
            'total' => $this->formatRupiah((float) $order->total_amount),
            'status' => $order->status,
        ];
    }

    private function confirmationNeeded(string $message): array
    {
        return [
            'success' => false,
            'confirmation_required' => true,
            'message' => $message,
            'langkah' => [
                'Baca ulang datanya.',
                'Jika sudah benar, kirim lagi dengan confirmed = true.',
                'Jika ada yang salah, ubah dulu datanya.',
            ],
        ];
    }

    private function generateOrderNumber(): string
    {
        do {
            $number = 'ORD-'.now()->format('Ymd').'-'.Str::upper(Str::random(5));
        } while (MarketplaceOrder::where('order_number', $number)->exists());

        return $number;
    }

    private function periodRange(?string $period): array
    {
        if ($period && preg_match('/^\d{4}-\d{2}$/', $period)) {
            $start = Carbon::createFromFormat('Y-m', $period)->startOfMonth();
            return [$start, $start->copy()->endOfMonth()];
        }

        return [now()->startOfMonth(), now()->endOfMonth()];
    }

    private function formatNumber(float $value): string
    {
        return rtrim(rtrim(number_format($value, 2, ',', '.'), '0'), ',');
    }
}
