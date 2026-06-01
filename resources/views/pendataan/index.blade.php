<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pendataan | {{ config('app.name', 'KopDes') }}</title>
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
</head>
<body class="min-h-screen bg-red-50 text-red-950">
@php
    $feature = $feature ?? 'aset';
    $features = [
        'aset' => ['label' => 'Aset Desa', 'route' => route('pendataan.aset.page')],
        'umkm' => ['label' => 'UMKM', 'route' => route('pendataan.umkm.page')],
        'export' => ['label' => 'Export', 'route' => route('pendataan.export.page')],
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
                <p class="text-xs font-medium text-red-100">Pendataan Desa</p>
            </div>
        </div>
        <nav class="flex gap-2 overflow-x-auto px-4 pb-5 lg:mt-4 lg:flex-1 lg:flex-col lg:overflow-visible lg:px-5 lg:pb-0">
            <a href="{{ route('dashboard') }}" class="flex items-center gap-3 whitespace-nowrap rounded-lg px-4 py-3 text-sm font-semibold text-red-50 transition hover:bg-white/10 lg:w-full"><x-sidebar-icon name="dashboard" /><span>Dashboard</span></a>
            <a href="{{ route('pendataan.index') }}" class="flex items-center gap-3 whitespace-nowrap rounded-lg bg-white px-4 py-3 text-sm font-semibold text-red-700 shadow-sm lg:w-full"><x-sidebar-icon name="pendataan" /><span>Pendataan</span></a>
            <a href="{{ route('kopdes.index') }}" class="flex items-center gap-3 whitespace-nowrap rounded-lg px-4 py-3 text-sm font-semibold text-red-50 transition hover:bg-white/10 lg:w-full"><x-sidebar-icon name="kopdes" /><span>Kopdes</span></a>
            <a href="{{ route('bumdes.index') }}" class="flex items-center gap-3 whitespace-nowrap rounded-lg px-4 py-3 text-sm font-semibold text-red-50 transition hover:bg-white/10 lg:w-full"><x-sidebar-icon name="bumdes" /><span>BUMDes</span></a>
            <a href="{{ route('umkm.index') }}" class="flex items-center gap-3 whitespace-nowrap rounded-lg px-4 py-3 text-sm font-semibold text-red-50 transition hover:bg-white/10 lg:w-full"><x-sidebar-icon name="umkm" /><span>UMKM</span></a>
            <a href="{{ route('mbg.index') }}" class="flex items-center gap-3 whitespace-nowrap rounded-lg px-4 py-3 text-sm font-semibold text-red-50 transition hover:bg-white/10 lg:w-full"><x-sidebar-icon name="mbg" /><span>MBG</span></a>
        </nav>
    </aside>

    <main data-main-content class="ml-20 min-w-0 px-5 pb-6 pt-24 transition-all duration-200 sm:px-8 lg:ml-72 lg:px-10 lg:pb-8">
        <div class="mx-auto max-w-4xl">
            <header class="rounded-lg border border-red-100 bg-white p-6 shadow-lg shadow-red-900/5">
                <p class="text-sm font-semibold text-red-700">Pendataan Agent</p>
                <h1 class="mt-2 text-3xl font-bold text-red-950">{{ $features[$feature]['label'] }}</h1>
                <p class="mt-2 text-sm leading-6 text-red-800">Satu halaman untuk satu pekerjaan, supaya input data lebih ringan dan jelas.</p>
            </header>

            <nav class="mt-5 flex gap-2 overflow-x-auto rounded-lg border border-red-100 bg-white p-2">
                @foreach ($features as $key => $item)
                    <a href="{{ $item['route'] }}" class="whitespace-nowrap rounded-lg px-4 py-3 text-sm font-semibold {{ $feature === $key ? 'bg-red-700 text-white' : 'bg-red-50 text-red-700 hover:bg-red-100' }}">{{ $item['label'] }}</a>
                @endforeach
            </nav>

            @if ($feature === 'aset')
                <form method="POST" action="{{ route('pendataan.aset.store') }}" class="mt-5 rounded-lg border border-red-100 bg-white p-6 shadow-sm">
                    @csrf
                    <div class="grid gap-4">
                        <input name="name" class="rounded-lg border border-red-100 px-4 py-3" placeholder="Nama aset">
                        <select name="category" class="rounded-lg border border-red-100 px-4 py-3">
                            <option value="">Pilih kategori</option>
                            <option value="tanah">Tanah</option>
                            <option value="bangunan">Bangunan</option>
                            <option value="alat">Alat pertanian</option>
                            <option value="infrastruktur">Infrastruktur</option>
                        </select>
                        <input name="location_description" class="rounded-lg border border-red-100 px-4 py-3" placeholder="Lokasi">
                        <select name="condition" class="rounded-lg border border-red-100 px-4 py-3">
                            <option value="">Pilih kondisi</option>
                            <option value="baik">Baik</option>
                            <option value="sedang">Sedang</option>
                            <option value="rusak">Rusak</option>
                        </select>
                        <input name="estimated_value" type="number" min="0" class="rounded-lg border border-red-100 px-4 py-3" placeholder="Estimasi nilai">
                        <textarea name="notes" class="rounded-lg border border-red-100 px-4 py-3" placeholder="Keterangan"></textarea>
                    </div>
                    <button class="mt-5 w-full rounded-lg bg-red-700 px-4 py-3 text-sm font-semibold text-white hover:bg-red-800">Simpan Aset</button>
                </form>
            @elseif ($feature === 'umkm')
                <form method="POST" action="{{ route('pendataan.umkm.store') }}" class="mt-5 rounded-lg border border-red-100 bg-white p-6 shadow-sm">
                    @csrf
                    <div class="grid gap-4">
                        <input name="business_name" class="rounded-lg border border-red-100 px-4 py-3" placeholder="Nama usaha">
                        <input name="owner_name" class="rounded-lg border border-red-100 px-4 py-3" placeholder="Nama pemilik">
                        <input name="phone" class="rounded-lg border border-red-100 px-4 py-3" placeholder="No. HP">
                        <input name="business_type" class="rounded-lg border border-red-100 px-4 py-3" placeholder="Jenis usaha">
                        <input name="main_products" class="rounded-lg border border-red-100 px-4 py-3" placeholder="Produk utama, pisahkan koma">
                        <div class="grid gap-4 sm:grid-cols-3">
                            <input name="production_capacity" type="number" min="1" class="rounded-lg border border-red-100 px-4 py-3" placeholder="Kapasitas">
                            <input name="production_unit" class="rounded-lg border border-red-100 px-4 py-3" placeholder="Satuan">
                            <input name="production_period" class="rounded-lg border border-red-100 px-4 py-3" placeholder="Periode">
                        </div>
                        <select name="needs_capital" class="rounded-lg border border-red-100 px-4 py-3">
                            <option value="">Butuh modal?</option>
                            <option value="ya">Ya</option>
                            <option value="tidak">Tidak</option>
                        </select>
                        <textarea name="address" class="rounded-lg border border-red-100 px-4 py-3" placeholder="Alamat"></textarea>
                    </div>
                    <button class="mt-5 w-full rounded-lg bg-red-700 px-4 py-3 text-sm font-semibold text-white hover:bg-red-800">Simpan UMKM</button>
                </form>
            @else
                <form method="POST" action="{{ route('pendataan.export') }}" class="mt-5 rounded-lg border border-red-100 bg-white p-6 shadow-sm">
                    @csrf
                    <div class="grid gap-4 sm:grid-cols-2">
                        <input name="village_name" class="rounded-lg border border-red-100 px-4 py-3" placeholder="Nama desa">
                        <input name="period" class="rounded-lg border border-red-100 px-4 py-3" placeholder="Periode data">
                        <select name="category" class="rounded-lg border border-red-100 px-4 py-3">
                            <option value="semua">Semua</option>
                            <option value="aset">Aset</option>
                            <option value="sdm">SDM</option>
                            <option value="umkm">UMKM</option>
                            <option value="komoditas">Komoditas</option>
                        </select>
                        <select name="format" class="rounded-lg border border-red-100 px-4 py-3">
                            <option value="excel">Excel</option>
                            <option value="pdf">PDF</option>
                        </select>
                    </div>
                    <button class="mt-5 w-full rounded-lg bg-red-700 px-4 py-3 text-sm font-semibold text-white hover:bg-red-800">Buat File Export</button>
                </form>
            @endif
        </div>
    </main>
</div>
</body>
</html>

