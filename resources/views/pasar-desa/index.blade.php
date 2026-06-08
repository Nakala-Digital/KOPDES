<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pasar Desa | DesaHub</title>
    <meta name="description" content="Dashboard Pasar Desa DesaHub - Kelola tenant pasar, transaksi harian, dan performa penjualan desa.">
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>
</head>
<body class="pasar-page">
    @php
        $user = auth()->user();
        $villageName = $user->village_name ?? 'Desa Sukamaju';
        $roleLabel = 'Kepala Desa';
        $tabs = ['Ringkasan', 'Daftar Tenant', 'Produk Terlaris', 'Transaksi', 'Kategori', 'Ulasan'];
    @endphp

    <div class="sidebar-overlay" data-sidebar-overlay></div>

    <aside class="sidebar" data-sidebar>
        {{-- Brand --}}
        <div class="sidebar-brand" style="display: block; padding: 24px 20px 16px;">
            <img src="{{ asset('assets/desahub/logo-desahub-transparent.png') }}" alt="DesaHub Logo" style="width: 140px; height: auto; object-fit: contain; display: block;">
        </div>

        {{-- Navigation --}}
        <nav class="sidebar-nav">
            <a href="{{ route('dashboard') }}" class="sidebar-link {{ request()->routeIs('dashboard*') ? 'active' : '' }}">
                <x-sidebar-icon name="dashboard" />
                <span>Dashboard</span>
            </a>

            <p class="sidebar-category">Data & Informasi</p>
            <a href="{{ route('pendataan.index') }}" class="sidebar-link {{ request()->routeIs('pendataan.*') ? 'active' : '' }}">
                <x-sidebar-icon name="pendataan-desa" />
                <span>Pendataan Desa</span>
            </a>
            <a href="{{ route('penduduk.index') }}" class="sidebar-link {{ request()->routeIs('penduduk.*') ? 'active' : '' }}">
                <x-sidebar-icon name="penduduk" />
                <span>Penduduk</span>
            </a>
            <a href="{{ route('potensi.index') }}" class="sidebar-link {{ request()->routeIs('potensi.*') ? 'active' : '' }}">
                <x-sidebar-icon name="potensi-desa" />
                <span>Potensi Desa</span>
            </a>

            <p class="sidebar-category">Kelembagaan</p>
            <a href="{{ route('kopdes.index') }}" class="sidebar-link {{ request()->routeIs('kopdes.*') ? 'active' : '' }}">
                <x-sidebar-icon name="kopdes" />
                <span>Kopdes/KDMP</span>
            </a>
            <a href="{{ route('bumdes.index') }}" class="sidebar-link {{ request()->routeIs('bumdes.*') ? 'active' : '' }}">
                <x-sidebar-icon name="bumdes" />
                <span>BUMDes</span>
            </a>

            <p class="sidebar-category">Ekonomi</p>
            <a href="{{ route('umkm.index') }}" class="sidebar-link {{ request()->routeIs('umkm.*') ? 'active' : '' }}">
                <x-sidebar-icon name="umkm" />
                <span>UMKM & Produk</span>
            </a>
            <a href="{{ route('pasar-desa.index') }}" class="sidebar-link {{ request()->routeIs('pasar-desa.*') ? 'active' : '' }}">
                <x-sidebar-icon name="pasar-desa" />
                <span>Pasar Desa</span>
            </a>

            <p class="sidebar-category">Supply Chain & MBG</p>
            <a href="{{ route('rantai-pasok-mbg.index') }}" class="sidebar-link {{ request()->routeIs('rantai-pasok-mbg.*') ? 'active' : '' }}">
                <x-sidebar-icon name="rantai-pasok" />
                <span>Rantai Pasok MBG</span>
            </a>
            <a href="{{ route('gudang-logistik.index') }}" class="sidebar-link {{ request()->routeIs('gudang-logistik.*') ? 'active' : '' }}">
                <x-sidebar-icon name="gudang" />
                <span>Gudang & Logistik</span>
            </a>
            <a href="{{ route('penerima-manfaat.index') }}" class="sidebar-link {{ request()->routeIs('penerima-manfaat.*') ? 'active' : '' }}">
                <x-sidebar-icon name="penerima-manfaat" />
                <span>Penerima Manfaat</span>
            </a>

            <p class="sidebar-category">Keuangan</p>
            <a href="{{ route('keuangan.index') }}" class="sidebar-link {{ request()->routeIs('keuangan.*') ? 'active' : '' }}">
                <x-sidebar-icon name="keuangan" />
                <span>Keuangan & Transaksi</span>
            </a>

            <p class="sidebar-category">Laporan</p>
            <a href="{{ route('analitik.index') }}" class="sidebar-link {{ request()->routeIs('analitik.*') ? 'active' : '' }}">
                <x-sidebar-icon name="dashboard-analitik" />
                <span>Dashboard & Analitik</span>
            </a>
            <a href="{{ route('laporan.index') }}" class="sidebar-link {{ request()->routeIs('laporan.*') ? 'active' : '' }}">
                <x-sidebar-icon name="laporan" />
                <span>Laporan</span>
            </a>
            
            <div style="height: 1px; background: rgba(255,255,255,0.1); margin: 16px 20px;"></div>

            <a href="{{ route('pengaturan.index') }}" class="sidebar-link {{ request()->routeIs('pengaturan.*') ? 'active' : '' }}">
                <x-sidebar-icon name="pengaturan" />
                <span>Pengaturan</span>
            </a>
        </nav>

        {{-- Collapse Button --}}
        <button class="sidebar-collapse-btn" data-sidebar-toggle>
            <x-sidebar-icon name="collapse" />
            <span data-sidebar-brand-text>Persempit Menu</span>
        </button>
    </aside>

    <header class="topbar" data-topbar>
        <div class="topbar-left">
            <button class="topbar-hamburger" aria-label="Toggle Menu" data-sidebar-toggle>
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" style="width: 20px; height: 20px;">
                    <line x1="4" x2="15" y1="7" y2="7"/><line x1="4" x2="12" y1="12" y2="12"/><line x1="4" x2="18" y1="17" y2="17"/>
                </svg>
            </button>
            <span class="topbar-title">Pasar Desa</span>
        </div>

        <div class="topbar-right">
            <button class="topbar-icon-btn" aria-label="Help">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"/><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/><path d="M12 17h.01"/>
                </svg>
            </button>
            <button class="topbar-icon-btn" aria-label="Notifications">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9"/><path d="M10.3 21a1.94 1.94 0 0 0 3.4 0"/>
                </svg>
                <span class="notification-badge">3</span>
            </button>

            <div class="topbar-user" id="pasar-user-dropdown-trigger">
                <img src="https://i.pravatar.cc/150?img=12" alt="User Avatar" class="topbar-user-avatar" />
                <div class="topbar-user-info">
                    <div class="topbar-user-name">{{ $roleLabel }}</div>
                    <div class="topbar-user-role">{{ $villageName }}</div>
                </div>
                <svg class="topbar-user-chevron" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>

                <div class="user-dropdown" id="pasar-user-dropdown">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit">
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m7 17 10-10"/><path d="M17 17V7H7"/></svg>
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </header>

    <main class="main-content fin-main-content" data-main-content>
        <div class="main-inner fin-main-inner">
                    <section class="pasar-page-heading">
                        <div>
                            <h1>Pasar Desa</h1>
                            <p>Kelola dan pantau tenant pasar, transaksi harian, dan performa penjualan desa secara terstruktur.</p>
                        </div>
                        <div class="pasar-page-actions">
                            <button class="pasar-btn pasar-btn-outline">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" x2="12" y1="15" y2="3"/>
                                </svg>
                                Export Data
                            </button>
                            <button class="pasar-btn pasar-btn-primary">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round">
                                    <line x1="12" x2="12" y1="5" y2="19"/><line x1="5" x2="19" y1="12" y2="12"/>
                                </svg>
                                Tambah Tenant
                            </button>
                        </div>
                    </section>

                    <nav class="pasar-tabs" aria-label="Navigasi dashboard Pasar Desa">
                        @foreach ($tabs as $index => $tab)
                            <a href="#" class="pasar-tab {{ $index === 0 ? 'active' : '' }}">{{ $tab }}</a>
                        @endforeach
                    </nav>

                    <section class="pasar-kpi-grid">
                        <article class="pasar-kpi-card">
                            <div class="pasar-kpi-icon blue"><x-sidebar-icon name="umkm" /></div>
                            <div><p>Total Tenant</p><strong>48</strong><span>+ 10% dari bulan lalu</span></div>
                        </article>
                        <article class="pasar-kpi-card">
                            <div class="pasar-kpi-icon green"><x-sidebar-icon name="pasar-desa" /></div>
                            <div><p>Transaksi Hari Ini</p><strong>Rp 12,8 jt</strong><span>+ 8% dari kemarin</span></div>
                        </article>
                        <article class="pasar-kpi-card">
                            <div class="pasar-kpi-icon red">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="M3 20h18"/><path d="M5 17v-4"/><path d="M10 17V9"/><path d="M15 17v-6"/><path d="M20 17V5"/><path d="m5 11 5-5 4 4 6-7"/></svg>
                            </div>
                            <div><p>Omzet Bulan Ini</p><strong>Rp 186,4 jt</strong><span>+ 15% dari bulan lalu</span></div>
                        </article>
                        <article class="pasar-kpi-card">
                            <div class="pasar-kpi-icon orange">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-8 0v2"/><circle cx="12" cy="7" r="4"/><path d="M20 8v6"/><path d="M23 11h-6"/><path d="M4 8v6"/><path d="M7 11H1"/></svg>
                            </div>
                            <div><p>Pembeli Aktif</p><strong>1.245</strong><span>+ 12% dari bulan lalu</span></div>
                        </article>
                        <article class="pasar-kpi-card">
                            <div class="pasar-kpi-icon purple">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round"><path d="M21 8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16Z"/><path d="m3.3 7 8.7 5 8.7-5"/><path d="M12 22V12"/></svg>
                            </div>
                            <div><p>Produk Terjual</p><strong>3.820</strong><span>+ 9% dari bulan lalu</span></div>
                        </article>
                    </section>

                    <section class="pasar-chart-grid">
                        <div class="pasar-card pasar-chart-card">
                            <div class="pasar-card-header">
                                <h2>Grafik Performa Penjualan</h2>
                                <button class="pasar-period-btn">6 Bulan Terakhir
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m6 9 6 6 6-6"/></svg>
                                </button>
                            </div>
                            <p class="pasar-chart-label">Omzet (Rp jt)</p>
                            <div class="pasar-line-chart"><canvas id="pasarSalesChart"></canvas></div>
                        </div>

                        <div class="pasar-card pasar-chart-card">
                            <div class="pasar-card-header"><h2>Kategori Penjualan Terbesar</h2></div>
                            <div class="pasar-donut-layout">
                                <div class="pasar-donut-wrap"><canvas id="pasarCategoryChart"></canvas></div>
                                <div class="pasar-donut-legend">
                                    <div><span><i style="background:#1267ff"></i>Makanan & Minuman</span><strong>38%</strong></div>
                                    <div><span><i style="background:#27b957"></i>Sayur & Hasil Tani</span><strong>24%</strong></div>
                                    <div><span><i style="background:#ff8c1a"></i>Kerajinan</span><strong>16%</strong></div>
                                    <div><span><i style="background:#7651e8"></i>Ikan & Peternakan</span><strong>14%</strong></div>
                                    <div><span><i style="background:#42a8c6"></i>Lainnya</span><strong>8%</strong></div>
                                </div>
                            </div>
                        </div>
                    </section>

                    <section class="pasar-table-grid">
                        <div class="pasar-card pasar-table-card">
                            <h2>Tenant Unggulan</h2>
                            <table class="pasar-table pasar-tenant-table">
                                <thead><tr><th>No</th><th>Nama Tenant</th><th>Kategori</th><th>Produk Andalan</th><th>Omzet Bulan Ini</th><th>Status</th></tr></thead>
                                <tbody>
                                    @foreach ([
                                        ['Warung Sembako Maju', 'Perdagangan', 'Sembako', 'Rp 18,4 jt', 'purple'],
                                        ['Tani Makmur', 'Hasil Tani', 'Sayuran Segar', 'Rp 15,7 jt', 'green'],
                                        ['Dapur Mbok Sari', 'Makanan', 'Nasi Bungkus', 'Rp 13,1 jt', 'orange'],
                                        ['Denimji Craft', 'Kerajinan', 'Tas Anyaman', 'Rp 9,6 jt', 'blue'],
                                        ['Ikan Segar Sukamaju', 'Perikanan', 'Ikan Nila', 'Rp 8,9 jt', 'cyan'],
                                    ] as $index => $tenant)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td><span class="pasar-tenant-name"><span class="pasar-tenant-icon {{ $tenant[4] }}"><x-sidebar-icon name="umkm" /></span>{{ $tenant[0] }}</span></td>
                                            <td>{{ $tenant[1] }}</td>
                                            <td>{{ $tenant[2] }}</td>
                                            <td>{{ $tenant[3] }}</td>
                                            <td><span class="pasar-status done">Aktif</span></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <a href="#" class="pasar-table-link">Lihat Semua Tenant <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></svg></a>
                        </div>

                        <div class="pasar-card pasar-table-card">
                            <h2>Transaksi Terbaru / Aktivitas Pasar</h2>
                            <table class="pasar-table pasar-transaction-table">
                                <thead><tr><th>Tenant</th><th>Produk</th><th>Nominal</th><th>Status</th></tr></thead>
                                <tbody>
                                    @foreach ([
                                        ['Warung Sembako Maju', 'Beras Premium 5kg', 'Rp 250.000', 'Selesai', 'done', 'purple'],
                                        ['Tani Makmur', 'Sayuran Paket Segar', 'Rp 85.000', 'Selesai', 'done', 'green'],
                                        ['Dapur Mbok Sari', 'Nasi Bungkus Komplit', 'Rp 25.000', 'Diproses', 'process', 'orange'],
                                        ['Denimji Craft', 'Tas Anyaman Pandan', 'Rp 120.000', 'Menunggu', 'waiting', 'blue'],
                                        ['Ikan Segar Sukamaju', 'Ikan Nila Segar 1kg', 'Rp 45.000', 'Selesai', 'done', 'cyan'],
                                    ] as $trx)
                                        <tr>
                                            <td><span class="pasar-tenant-name"><span class="pasar-tenant-icon {{ $trx[5] }}"><x-sidebar-icon name="umkm" /></span>{{ $trx[0] }}</span></td>
                                            <td>{{ $trx[1] }}</td>
                                            <td>{{ $trx[2] }}</td>
                                            <td><span class="pasar-status {{ $trx[4] }}">{{ $trx[3] }}</span></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <a href="#" class="pasar-table-link">Lihat Semua Transaksi <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></svg></a>
                        </div>
                    </section>

            <footer class="pasar-footer">
                <span>DesaHub - Ekosistem Ekonomi Desa</span>
                <span>&copy; 2025 DesaHub. All rights reserved.</span>
                <span>Nakala Digital&nbsp;&nbsp; x &nbsp;&nbsp;Romulus Digital<br>Strategic Partner - Singapore/Vietnam</span>
            </footer>
        </div>
    </main>

    <script>
    document.addEventListener('DOMContentLoaded', () => {
        const trigger = document.getElementById('pasar-user-dropdown-trigger');
        const dropdown = document.getElementById('pasar-user-dropdown');
        trigger?.addEventListener('click', (event) => {
            event.stopPropagation();
            dropdown?.classList.toggle('show');
        });
        document.addEventListener('click', () => dropdown?.classList.remove('show'));

        const salesCtx = document.getElementById('pasarSalesChart')?.getContext('2d');
        if (salesCtx) {
            new Chart(salesCtx, {
                type: 'line',
                data: {
                    labels: ['Des 2024', 'Jan 2025', 'Feb 2025', 'Mar 2025', 'Apr 2025', 'Mei 2025'],
                    datasets: [{
                        data: [92.4, 104.6, 121.3, 139.8, 163.7, 186.4],
                        borderColor: '#1267ff',
                        borderWidth: 3,
                        pointRadius: 5,
                        pointBackgroundColor: '#1267ff',
                        pointBorderColor: '#ffffff',
                        pointBorderWidth: 2,
                        tension: 0.34,
                        fill: false,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    layout: { padding: { top: 22, right: 18, left: 2, bottom: 0 } },
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: '#071d4f',
                            callbacks: { label: (ctx) => `${ctx.parsed.y.toString().replace('.', ',')} jt` }
                        }
                    },
                    scales: {
                        x: {
                            grid: { display: false },
                            ticks: { color: '#062a70', font: { family: 'Inter', size: 11, weight: 600 } },
                            border: { color: '#9fb8dc' }
                        },
                        y: {
                            min: 0,
                            max: 200,
                            ticks: {
                                stepSize: 50,
                                color: '#062a70',
                                font: { family: 'Inter', size: 11, weight: 600 },
                                callback: (value) => value === 0 ? '0' : value
                            },
                            grid: { color: '#dce7f7' },
                            border: { display: false }
                        }
                    },
                    animation: false
                },
                plugins: [{
                    id: 'pasarValueLabels',
                    afterDatasetsDraw(chart) {
                        const { ctx } = chart;
                        const meta = chart.getDatasetMeta(0);
                        ctx.save();
                        ctx.fillStyle = '#062a70';
                        ctx.font = '700 11px Inter, sans-serif';
                        ctx.textAlign = 'center';
                        chart.data.datasets[0].data.forEach((value, index) => {
                            const point = meta.data[index];
                            const nudge = index === 0 ? 14 : (index === chart.data.datasets[0].data.length - 1 ? -8 : 0);
                            ctx.fillText(String(value).replace('.', ','), point.x + nudge, point.y - 12);
                        });
                        ctx.restore();
                    }
                }]
            });
        }

        const categoryCtx = document.getElementById('pasarCategoryChart')?.getContext('2d');
        if (categoryCtx) {
            new Chart(categoryCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Makanan & Minuman', 'Sayur & Hasil Tani', 'Kerajinan', 'Ikan & Peternakan', 'Lainnya'],
                    datasets: [{
                        data: [38, 24, 16, 14, 8],
                        backgroundColor: ['#1267ff', '#27b957', '#ff8c1a', '#7651e8', '#42a8c6'],
                        borderColor: '#ffffff',
                        borderWidth: 3,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '58%',
                    plugins: { legend: { display: false } },
                    animation: false
                }
            });
        }
    });
    </script>
</body>
</html>
