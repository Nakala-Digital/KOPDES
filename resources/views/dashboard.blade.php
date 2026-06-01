<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard | {{ config('app.name', 'KopDes') }}</title>
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
</head>
<body class="min-h-screen bg-red-50 text-red-950">
    @php
        $dashboard = $dashboard ?? [
            'user' => [
                'name' => auth()->user()->name,
                'role' => auth()->user()->role?->slug ?? 'admin_desa',
                'village_name' => auth()->user()->village_name ?? 'Semua Desa',
            ],
            'date' => now()->format('d/m/Y'),
            'modules' => [],
            'summary' => [
                'transactions_today_count' => 0,
                'transactions_today_value' => 'Rp 0',
                'transactions_vs_yesterday' => 'tetap 0%',
                'active_umkm_transactions' => 0,
                'mbg_orders_today' => 0,
                'koperasi_cash' => 'Rp 0',
            ],
            'alerts' => [],
            'stats' => [
                'registered_products' => 0,
                'critical_stock' => 0,
                'active_koperasi_members' => 0,
                'active_bumdes_units' => 0,
                'weekly_transaction_value' => 'Rp 0',
            ],
            'charts' => [
                'transactions' => [
                    ['label' => 'Kemarin', 'value' => 0, 'formatted' => 'Rp 0'],
                    ['label' => 'Hari ini', 'value' => 0, 'formatted' => 'Rp 0'],
                ],
                'module_activity' => [
                    ['label' => 'UMKM', 'value' => 0],
                    ['label' => 'MBG', 'value' => 0],
                    ['label' => 'Kopdes', 'value' => 0],
                    ['label' => 'BUMDes', 'value' => 0],
                ],
                'stock' => [
                    ['label' => 'Aman', 'value' => 0],
                    ['label' => 'Kritis', 'value' => 0],
                ],
                'alerts' => [
                    ['label' => 'Urgent', 'value' => 0],
                    ['label' => 'Perhatian', 'value' => 0],
                    ['label' => 'Info', 'value' => 0],
                ],
            ],
        ];
        $summary = $dashboard['summary'];
        $stats = $dashboard['stats'];
        $charts = $dashboard['charts'];
        $roleLabel = str($dashboard['user']['role'])->replace('_', ' ')->title();
        $transactionMax = max(1, collect($charts['transactions'])->max('value'));
        $activityMax = max(1, collect($charts['module_activity'])->max('value'));
        $stockTotal = max(1, collect($charts['stock'])->sum('value'));
        $alertMax = max(1, collect($charts['alerts'])->max('value'));
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
            <div class="flex items-center justify-between gap-4 px-6 py-5 lg:block lg:px-7 lg:py-7">
                <div class="flex items-center gap-3">
                    <a href="{{ route('dashboard') }}" class="flex h-11 w-11 items-center justify-center rounded-lg bg-white text-lg font-bold text-red-700 shadow-sm">
                        K
                    </a>
                    <div data-sidebar-brand-text>
                        <p class="text-lg font-bold leading-tight">KopDes</p>
                        <p class="text-xs font-medium text-red-100">Merah Putih</p>
                    </div>
                </div>
            </div>

            <nav class="flex gap-2 overflow-x-auto px-4 pb-5 lg:mt-4 lg:flex-1 lg:flex-col lg:overflow-visible lg:px-5 lg:pb-0">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-3 whitespace-nowrap rounded-lg bg-white px-4 py-3 text-sm font-semibold text-red-700 shadow-sm lg:w-full">
                    <x-sidebar-icon name="dashboard" />
                    <span>Dashboard</span>
                </a>
                <a href="{{ url('/role-flow') }}" class="flex items-center gap-3 whitespace-nowrap rounded-lg px-4 py-3 text-sm font-semibold text-red-50 transition hover:bg-white/10 lg:w-full">
                    <x-sidebar-icon name="role" />
                    <span>Role Flow</span>
                </a>
                <a href="{{ url('/flow') }}" class="flex items-center gap-3 whitespace-nowrap rounded-lg px-4 py-3 text-sm font-semibold text-red-50 transition hover:bg-white/10 lg:w-full">
                    <x-sidebar-icon name="flow" />
                    <span>Alur Sistem</span>
                </a>
                <a href="{{ route('pendataan.index') }}" class="flex items-center gap-3 whitespace-nowrap rounded-lg px-4 py-3 text-sm font-semibold text-red-50 transition hover:bg-white/10 lg:w-full">
                    <x-sidebar-icon name="pendataan" />
                    <span>Pendataan</span>
                </a>
                <a href="{{ route('kopdes.index') }}" class="flex items-center gap-3 whitespace-nowrap rounded-lg px-4 py-3 text-sm font-semibold text-red-50 transition hover:bg-white/10 lg:w-full">
                    <x-sidebar-icon name="kopdes" />
                    <span>Kopdes</span>
                </a>
                <a href="{{ route('bumdes.index') }}" class="flex items-center gap-3 whitespace-nowrap rounded-lg px-4 py-3 text-sm font-semibold text-red-50 transition hover:bg-white/10 lg:w-full">
                    <x-sidebar-icon name="bumdes" />
                    <span>BUMDes</span>
                </a>
                <a href="{{ route('umkm.index') }}" class="flex items-center gap-3 whitespace-nowrap rounded-lg px-4 py-3 text-sm font-semibold text-red-50 transition hover:bg-white/10 lg:w-full">
                    <x-sidebar-icon name="umkm" />
                    <span>UMKM</span>
                </a>
                <a href="{{ route('mbg.index') }}" class="flex items-center gap-3 whitespace-nowrap rounded-lg px-4 py-3 text-sm font-semibold text-red-50 transition hover:bg-white/10 lg:w-full">
                    <x-sidebar-icon name="mbg" />
                    <span>MBG</span>
                </a>
            </nav>
        </aside>

        <main data-main-content class="ml-20 min-w-0 px-5 pb-6 pt-24 transition-all duration-200 sm:px-8 lg:ml-72 lg:px-10 lg:pb-8">
            <div class="mx-auto max-w-7xl">
                <header class="rounded-lg border border-red-100 bg-white p-6 shadow-lg shadow-red-900/5 sm:p-7">
                    <p class="text-sm font-semibold text-red-700">Dashboard Agent</p>
                    <div class="mt-2 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
                        <div>
                            <h1 class="text-3xl font-bold leading-tight text-red-950">Ringkasan Ekonomi Desa</h1>
                            <p class="mt-2 text-sm leading-6 text-red-800">
                                {{ $dashboard['user']['village_name'] }} Â· {{ $roleLabel }} Â· {{ $dashboard['date'] }}
                            </p>
                        </div>
                        <div class="rounded-lg bg-red-50 px-4 py-3 text-sm font-semibold text-red-700">
                            {{ count($dashboard['alerts']) }} alert aktif
                        </div>
                    </div>
                </header>

                <section class="mt-6 grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
                    <article class="rounded-lg border border-red-100 bg-white p-5 shadow-sm">
                        <p class="text-sm font-semibold text-red-600">Transaksi Hari Ini</p>
                        <p class="mt-3 text-2xl font-bold text-red-950">{{ $summary['transactions_today_value'] }}</p>
                        <p class="mt-2 text-sm text-red-800">{{ $summary['transactions_today_count'] }} transaksi Â· {{ $summary['transactions_vs_yesterday'] }}</p>
                    </article>
                    <article class="rounded-lg border border-red-100 bg-white p-5 shadow-sm">
                        <p class="text-sm font-semibold text-red-600">UMKM Aktif</p>
                        <p class="mt-3 text-2xl font-bold text-red-950">{{ $summary['active_umkm_transactions'] }}</p>
                        <p class="mt-2 text-sm text-red-800">Bertransaksi hari ini</p>
                    </article>
                    <article class="rounded-lg border border-red-100 bg-white p-5 shadow-sm">
                        <p class="text-sm font-semibold text-red-600">Order MBG</p>
                        <p class="mt-3 text-2xl font-bold text-red-950">{{ $summary['mbg_orders_today'] }}</p>
                        <p class="mt-2 text-sm text-red-800">Order tercatat hari ini</p>
                    </article>
                    <article class="rounded-lg border border-red-100 bg-white p-5 shadow-sm">
                        <p class="text-sm font-semibold text-red-600">Kas Koperasi</p>
                        <p class="mt-3 text-2xl font-bold text-red-950">{{ $summary['koperasi_cash'] }}</p>
                        <p class="mt-2 text-sm text-red-800">Simpanan dikurangi pinjaman aktif</p>
                    </article>
                </section>

                <section class="mt-6 grid gap-6 xl:grid-cols-[1.2fr_1fr]">
                    <div class="rounded-lg border border-red-100 bg-white p-6 shadow-sm">
                        <div class="flex items-center justify-between gap-4">
                            <div>
                                <h2 class="text-xl font-bold text-red-950">Grafik Transaksi</h2>
                                <p class="mt-1 text-sm text-red-800">Perbandingan hari ini dengan kemarin.</p>
                            </div>
                            <span class="rounded-lg bg-red-50 px-3 py-2 text-xs font-bold text-red-700">Harian</span>
                        </div>
                        <div class="mt-6 space-y-5">
                            @foreach ($charts['transactions'] as $item)
                                @php $width = max(4, ((float) $item['value'] / $transactionMax) * 100); @endphp
                                <div>
                                    <div class="mb-2 flex items-center justify-between gap-4 text-sm">
                                        <span class="font-semibold text-red-700">{{ $item['label'] }}</span>
                                        <span class="font-bold text-red-950">{{ $item['formatted'] }}</span>
                                    </div>
                                    <div class="h-4 overflow-hidden rounded-lg border border-red-100 bg-red-50">
                                        <div class="h-full rounded-lg bg-red-700" style="width: {{ $width }}%"></div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="rounded-lg border border-red-100 bg-white p-6 shadow-sm">
                        <h2 class="text-xl font-bold text-red-950">Aktivitas Modul</h2>
                        <div class="mt-5 grid gap-3">
                            @foreach ($charts['module_activity'] as $item)
                                @php $height = max(12, ((float) $item['value'] / $activityMax) * 100); @endphp
                                <div class="grid grid-cols-[80px_1fr_40px] items-center gap-3">
                                    <span class="text-sm font-semibold text-red-700">{{ $item['label'] }}</span>
                                    <div class="flex h-8 items-end rounded-lg border border-red-100 bg-red-50 px-1">
                                        <div class="h-full rounded-md bg-red-700" style="width: {{ $height }}%"></div>
                                    </div>
                                    <span class="text-right text-sm font-bold text-red-950">{{ $item['value'] }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </section>

                <section class="mt-6 grid gap-6 xl:grid-cols-2">
                    <div class="rounded-lg border border-red-100 bg-white p-6 shadow-sm">
                        <h2 class="text-xl font-bold text-red-950">Grafik Stok Produk</h2>
                        <div class="mt-5 h-5 overflow-hidden rounded-lg border border-red-100 bg-red-50">
                            @foreach ($charts['stock'] as $index => $item)
                                @php $width = ((float) $item['value'] / $stockTotal) * 100; @endphp
                                <div class="float-left h-full {{ $index === 0 ? 'bg-white' : 'bg-red-700' }}" style="width: {{ $width }}%"></div>
                            @endforeach
                        </div>
                        <div class="mt-4 grid gap-3 sm:grid-cols-2">
                            @foreach ($charts['stock'] as $index => $item)
                                <div class="rounded-lg border border-red-100 bg-red-50 p-4">
                                    <p class="text-sm font-semibold text-red-700">{{ $item['label'] }}</p>
                                    <p class="mt-1 text-2xl font-bold text-red-950">{{ $item['value'] }}</p>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="rounded-lg border border-red-100 bg-white p-6 shadow-sm">
                        <h2 class="text-xl font-bold text-red-950">Grafik Alert</h2>
                        <div class="mt-5 space-y-4">
                            @foreach ($charts['alerts'] as $item)
                                @php $width = max(4, ((float) $item['value'] / $alertMax) * 100); @endphp
                                <div>
                                    <div class="mb-2 flex items-center justify-between text-sm">
                                        <span class="font-semibold text-red-700">{{ $item['label'] }}</span>
                                        <span class="font-bold text-red-950">{{ $item['value'] }}</span>
                                    </div>
                                    <div class="h-3 overflow-hidden rounded-lg bg-red-50">
                                        <div class="h-full rounded-lg bg-red-700" style="width: {{ $width }}%"></div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </section>

                <section class="mt-6 grid gap-6 xl:grid-cols-[1.4fr_1fr]">
                    <div class="rounded-lg border border-red-100 bg-white p-6 shadow-sm">
                        <div class="flex items-center justify-between gap-4">
                            <div>
                                <h2 class="text-xl font-bold text-red-950">Panel Alert</h2>
                                <p class="mt-1 text-sm text-red-800">Urutan: urgent, perhatian, lalu info.</p>
                            </div>
                            <span class="rounded-lg bg-red-700 px-3 py-2 text-xs font-bold text-white">Prioritas</span>
                        </div>

                        <div class="mt-5 space-y-3">
                            @forelse ($dashboard['alerts'] as $alert)
                                @php
                                    $alertClass = match ($alert['level']) {
                                        'urgent' => 'border-red-600 bg-red-50',
                                        'warning' => 'border-red-300 bg-white',
                                        default => 'border-red-100 bg-white',
                                    };
                                @endphp
                                <div class="rounded-lg border-l-4 {{ $alertClass }} p-4">
                                    <p class="text-xs font-bold uppercase text-red-700">{{ $alert['label'] }}</p>
                                    <p class="mt-1 text-sm font-medium leading-6 text-red-950">{{ $alert['message'] }}</p>
                                </div>
                            @empty
                                <div class="rounded-lg border border-red-100 bg-red-50 p-5 text-sm font-semibold text-red-700">
                                    Tidak ada alert penting saat ini.
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <div class="rounded-lg border border-red-100 bg-white p-6 shadow-sm">
                        <h2 class="text-xl font-bold text-red-950">Statistik Singkat</h2>
                        <dl class="mt-5 space-y-4">
                            <div class="flex items-center justify-between gap-4 border-b border-red-100 pb-3">
                                <dt class="text-sm font-semibold text-red-700">Produk terdaftar</dt>
                                <dd class="text-lg font-bold text-red-950">{{ $stats['registered_products'] }}</dd>
                            </div>
                            <div class="flex items-center justify-between gap-4 border-b border-red-100 pb-3">
                                <dt class="text-sm font-semibold text-red-700">Stok kritis</dt>
                                <dd class="text-lg font-bold text-red-950">{{ $stats['critical_stock'] }}</dd>
                            </div>
                            <div class="flex items-center justify-between gap-4 border-b border-red-100 pb-3">
                                <dt class="text-sm font-semibold text-red-700">Anggota koperasi aktif</dt>
                                <dd class="text-lg font-bold text-red-950">{{ $stats['active_koperasi_members'] }}</dd>
                            </div>
                            <div class="flex items-center justify-between gap-4 border-b border-red-100 pb-3">
                                <dt class="text-sm font-semibold text-red-700">Unit BUMDes aktif</dt>
                                <dd class="text-lg font-bold text-red-950">{{ $stats['active_bumdes_units'] ?? 0 }}</dd>
                            </div>
                            <div class="flex items-center justify-between gap-4">
                                <dt class="text-sm font-semibold text-red-700">Transaksi minggu ini</dt>
                                <dd class="text-lg font-bold text-red-950">{{ $stats['weekly_transaction_value'] }}</dd>
                            </div>
                        </dl>
                    </div>
                </section>

                <section class="mt-6 rounded-lg border border-red-100 bg-white p-6 shadow-sm">
                    <div class="flex flex-col gap-5 xl:flex-row xl:items-end xl:justify-between">
                        <div>
                            <h2 class="text-xl font-bold text-red-950">Laporan Kepala Desa</h2>
                            <p class="mt-1 text-sm leading-6 text-red-800">Generate laporan ringkas maksimal 1 halaman A4 untuk bahan rapat desa.</p>
                        </div>
                        <form id="village-report-form" method="POST" action="{{ route('dashboard.reports.village') }}" class="grid gap-3 sm:grid-cols-4 xl:min-w-[640px]">
                            @csrf
                            <input name="village_name" value="{{ $dashboard['user']['village_name'] }}" class="rounded-lg border border-red-200 bg-white px-4 py-3 text-sm font-semibold text-red-950 outline-none focus:border-red-600" aria-label="Nama desa">
                            <input name="month" type="number" min="1" max="12" value="{{ now()->month }}" class="rounded-lg border border-red-200 bg-white px-4 py-3 text-sm font-semibold text-red-950 outline-none focus:border-red-600" aria-label="Bulan">
                            <input name="year" type="number" min="2020" value="{{ now()->year }}" class="rounded-lg border border-red-200 bg-white px-4 py-3 text-sm font-semibold text-red-950 outline-none focus:border-red-600" aria-label="Tahun">
                            <button type="submit" class="rounded-lg bg-red-700 px-4 py-3 text-sm font-bold text-white transition hover:bg-red-800">
                                Buat Laporan
                            </button>
                        </form>
                    </div>
                    <div id="village-report-result" class="mt-5 hidden rounded-lg border border-red-100 bg-red-50 p-5 text-sm leading-6 text-red-950"></div>
                </section>
            </div>
        </main>
    </div>
    <script>
        const reportForm = document.getElementById('village-report-form');
        const reportResult = document.getElementById('village-report-result');

        reportForm?.addEventListener('submit', async (event) => {
            event.preventDefault();
            reportResult.classList.remove('hidden');
            reportResult.textContent = 'Laporan sedang dibuat...';

            const response = await fetch(reportForm.action, {
                method: 'POST',
                headers: { 'Accept': 'application/json' },
                body: new FormData(reportForm),
            });
            const data = await response.json();

            if (!response.ok || !data.success) {
                reportResult.textContent = data.message ?? 'Laporan belum bisa dibuat. Periksa data yang diisi.';
                return;
            }

            const indicators = data.indikator_utama;
            reportResult.innerHTML = `
                <p class="font-bold text-red-700">${data.header.desa} Â· ${data.header.periode}</p>
                <p class="mt-2">${data.ringkasan_eksekutif}</p>
                <div class="mt-4 grid gap-2 sm:grid-cols-2">
                    <p><strong>Total transaksi:</strong> ${indicators.total_nilai_transaksi_ekonomi}</p>
                    <p><strong>UMKM aktif:</strong> ${indicators.jumlah_umkm_aktif}</p>
                    <p><strong>Realisasi MBG:</strong> ${indicators.realisasi_mbg}</p>
                    <p><strong>Simpanan koperasi:</strong> ${indicators.simpanan_koperasi}</p>
                </div>
            `;
        });
    </script>
</body>
</html>

