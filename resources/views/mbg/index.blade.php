<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>MBG | {{ config('app.name', 'KopDes') }}</title>
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
</head>
<body class="min-h-screen bg-red-50 text-red-950">
@php
    $feature = $feature ?? 'orders';
    $features = [
        'orders' => ['label' => 'Order', 'route' => route('mbg.orders.page')],
        'suppliers' => ['label' => 'Supplier', 'route' => route('mbg.suppliers.page')],
        'distributions' => ['label' => 'Distribusi', 'route' => route('mbg.distributions.page')],
        'reports' => ['label' => 'Laporan', 'route' => route('mbg.reports.page')],
    ];
@endphp
<div class="min-h-screen lg:flex">
    <div data-sidebar-overlay class="fixed inset-0 z-30 hidden bg-red-950/40 lg:hidden"></div>

    <header data-topbar class="fixed inset-x-0 left-20 top-0 z-20 flex h-16 items-center justify-between border-b border-red-100 bg-white px-4 shadow-sm transition-all duration-200 lg:left-72 sm:px-6">
        <div class="flex items-center gap-3">
            <button type="button" data-sidebar-toggle class="inline-flex h-10 w-10 items-center justify-center rounded-lg border border-red-100 bg-white text-red-700 transition hover:bg-red-50" aria-label="Buka tutup sidebar">
                <span class="flex flex-col gap-1.5">
                    <span class="block h-0.5 w-5 bg-red-700"></span>
                    <span class="block h-0.5 w-5 bg-red-700"></span>
                    <span class="block h-0.5 w-5 bg-red-700"></span>
                </span>
            </button>
            <div>
                <p class="text-sm font-bold text-red-950">{{ config('app.name', 'KopDes') }}</p>
                <p class="text-xs font-semibold text-red-700">Ekosistem Ekonomi Desa</p>
            </div>
        </div>
        <div class="flex items-center gap-3">
            <span class="hidden text-sm font-semibold text-red-700 sm:inline">{{ auth()->user()->name }}</span>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="rounded-lg bg-red-700 px-4 py-2 text-sm font-semibold text-white transition hover:bg-red-800">Logout</button>
            </form>
        </div>
    </header>
    <aside data-sidebar class="fixed inset-y-0 left-0 z-40 flex w-20 flex-col overflow-hidden bg-red-700 text-white transition-all duration-200 lg:w-72">
        <div class="flex items-center gap-3 px-6 py-5 lg:px-7 lg:py-7">
            <a href="{{ route('dashboard') }}" class="flex h-11 w-11 items-center justify-center rounded-lg bg-white text-lg font-bold text-red-700 shadow-sm">K</a>
            <div data-sidebar-brand-text>
                <p class="text-lg font-bold leading-tight">KopDes</p>
                <p class="text-xs font-medium text-red-100">Supply Chain MBG</p>
            </div>
        </div>
        <nav class="flex gap-2 overflow-x-auto px-4 pb-5 lg:mt-4 lg:flex-1 lg:flex-col lg:overflow-visible lg:px-5 lg:pb-0">
            <a href="{{ route('dashboard') }}" class="flex items-center gap-3 whitespace-nowrap rounded-lg px-4 py-3 text-sm font-semibold text-red-50 transition hover:bg-white/10 lg:w-full"><x-sidebar-icon name="dashboard" /><span>Dashboard</span></a>
            <a href="{{ route('mbg.index') }}" class="flex items-center gap-3 whitespace-nowrap rounded-lg bg-white px-4 py-3 text-sm font-semibold text-red-700 shadow-sm lg:w-full"><x-sidebar-icon name="mbg" /><span>MBG</span></a>
            <a href="{{ route('umkm.index') }}" class="flex items-center gap-3 whitespace-nowrap rounded-lg px-4 py-3 text-sm font-semibold text-red-50 transition hover:bg-white/10 lg:w-full"><x-sidebar-icon name="umkm" /><span>UMKM</span></a>
            <a href="{{ route('kopdes.index') }}" class="flex items-center gap-3 whitespace-nowrap rounded-lg px-4 py-3 text-sm font-semibold text-red-50 transition hover:bg-white/10 lg:w-full"><x-sidebar-icon name="kopdes" /><span>Kopdes</span></a>
            <a href="{{ route('bumdes.index') }}" class="flex items-center gap-3 whitespace-nowrap rounded-lg px-4 py-3 text-sm font-semibold text-red-50 transition hover:bg-white/10 lg:w-full"><x-sidebar-icon name="bumdes" /><span>BUMDes</span></a>
            <a href="{{ route('pendataan.index') }}" class="flex items-center gap-3 whitespace-nowrap rounded-lg px-4 py-3 text-sm font-semibold text-red-50 transition hover:bg-white/10 lg:w-full"><x-sidebar-icon name="pendataan" /><span>Pendataan</span></a>
        </nav>
    </aside>

    <main data-main-content class="ml-20 min-w-0 px-5 pb-6 pt-24 transition-all duration-200 sm:px-8 lg:ml-72 lg:px-10 lg:pb-8">
        <div class="mx-auto max-w-4xl">
            <header class="rounded-lg border border-red-100 bg-white p-6 shadow-lg shadow-red-900/5">
                <p class="text-sm font-semibold text-red-700">MBG Supply Chain Agent</p>
                <h1 class="mt-2 text-3xl font-bold text-red-950">{{ $features[$feature]['label'] }}</h1>
                <p class="mt-2 text-sm leading-6 text-red-800">Setiap fitur MBG dipisah agar operator tidak perlu melihat terlalu banyak form sekaligus.</p>
            </header>

            <nav class="mt-5 flex gap-2 overflow-x-auto rounded-lg border border-red-100 bg-white p-2">
                @foreach ($features as $key => $item)
                    <a href="{{ $item['route'] }}" class="whitespace-nowrap rounded-lg px-4 py-3 text-sm font-semibold {{ $feature === $key ? 'bg-red-700 text-white' : 'bg-red-50 text-red-700 hover:bg-red-100' }}">{{ $item['label'] }}</a>
                @endforeach
            </nav>

            @if ($feature === 'orders')
                <form method="POST" action="{{ route('mbg.orders.store') }}" class="mt-5 rounded-lg border border-red-100 bg-white p-6 shadow-sm">
                    @csrf
                    <div class="grid gap-4">
                        <input name="village_name" class="rounded-lg border border-red-100 px-4 py-3" placeholder="Nama desa">
                        <input name="beneficiary_name" class="rounded-lg border border-red-100 px-4 py-3" placeholder="Sekolah / penerima manfaat">
                        <input name="target_portions" type="number" min="1" class="rounded-lg border border-red-100 px-4 py-3" placeholder="Target porsi">
                        <input name="distribution_date" type="date" class="rounded-lg border border-red-100 px-4 py-3">
                    </div>
                    <button class="mt-5 w-full rounded-lg bg-red-700 px-4 py-3 text-sm font-semibold text-white hover:bg-red-800">Buat Order</button>
                </form>
            @elseif ($feature === 'suppliers')
                <form id="supplier-form" method="POST" action="{{ route('mbg.suppliers.notify', ['order' => 0]) }}" data-template="{{ route('mbg.suppliers.notify', ['order' => '__ORDER__']) }}" class="mt-5 rounded-lg border border-red-100 bg-white p-6 shadow-sm">
                    @csrf
                    <div class="grid gap-4">
                        <input id="mbg-order-id" type="number" min="1" class="rounded-lg border border-red-100 px-4 py-3" placeholder="ID order MBG">
                        <input name="suppliers[0][supplier_name]" class="rounded-lg border border-red-100 px-4 py-3" placeholder="Nama supplier">
                        <select name="suppliers[0][supplier_type]" class="rounded-lg border border-red-100 px-4 py-3">
                            <option value="desa_sendiri">Petani / UMKM desa sendiri</option>
                            <option value="desa_tetangga">UMKM desa tetangga</option>
                            <option value="supplier_luar">Supplier luar</option>
                        </select>
                        <input name="suppliers[0][product_name]" class="rounded-lg border border-red-100 px-4 py-3" placeholder="Produk">
                        <div class="grid gap-4 sm:grid-cols-3">
                            <input name="suppliers[0][quantity]" type="number" min="1" class="rounded-lg border border-red-100 px-4 py-3" placeholder="Jumlah">
                            <input name="suppliers[0][unit]" class="rounded-lg border border-red-100 px-4 py-3" placeholder="Satuan">
                            <input name="suppliers[0][delivery_date]" type="date" class="rounded-lg border border-red-100 px-4 py-3">
                        </div>
                        <input name="suppliers[0][estimated_unit_price]" type="number" min="0" class="rounded-lg border border-red-100 px-4 py-3" placeholder="Estimasi harga satuan">
                    </div>
                    <button class="mt-5 w-full rounded-lg bg-red-700 px-4 py-3 text-sm font-semibold text-white hover:bg-red-800">Kirim Konfirmasi Supplier</button>
                </form>
            @elseif ($feature === 'distributions')
                <form method="POST" action="{{ route('mbg.distributions.store') }}" class="mt-5 rounded-lg border border-red-100 bg-white p-6 shadow-sm">
                    @csrf
                    <div class="grid gap-4">
                        <input name="mbg_order_id" type="number" min="1" class="rounded-lg border border-red-100 px-4 py-3" placeholder="ID order MBG">
                        <input name="school_name" class="rounded-lg border border-red-100 px-4 py-3" placeholder="Sekolah / penerima">
                        <input name="target_portions" type="number" min="0" class="rounded-lg border border-red-100 px-4 py-3" placeholder="Target porsi">
                        <input name="realized_portions" type="number" min="0" class="rounded-lg border border-red-100 px-4 py-3" placeholder="Realisasi porsi">
                        <input name="distributed_at" type="date" class="rounded-lg border border-red-100 px-4 py-3">
                    </div>
                    <button class="mt-5 w-full rounded-lg bg-red-700 px-4 py-3 text-sm font-semibold text-white hover:bg-red-800">Catat Distribusi</button>
                </form>
            @else
                <form method="POST" action="{{ route('mbg.reports.monthly') }}" class="mt-5 rounded-lg border border-red-100 bg-white p-6 shadow-sm">
                    @csrf
                    <div class="grid gap-4 sm:grid-cols-3">
                        <input name="village_name" class="rounded-lg border border-red-100 px-4 py-3" placeholder="Nama desa">
                        <input name="month" type="number" min="1" max="12" class="rounded-lg border border-red-100 px-4 py-3" placeholder="Bulan">
                        <input name="year" type="number" min="2020" class="rounded-lg border border-red-100 px-4 py-3" placeholder="Tahun">
                    </div>
                    <button class="mt-5 w-full rounded-lg bg-red-700 px-4 py-3 text-sm font-semibold text-white hover:bg-red-800">Buat Laporan</button>
                </form>
            @endif
        </div>
    </main>
</div>
<script>
    const supplierForm = document.getElementById('supplier-form');
    const orderInput = document.getElementById('mbg-order-id');

    supplierForm?.addEventListener('submit', () => {
        supplierForm.action = supplierForm.dataset.template.replace('__ORDER__', orderInput.value || '0');
    });
</script>
</body>
</html>

