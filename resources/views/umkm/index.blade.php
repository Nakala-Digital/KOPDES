<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>UMKM & Produk | DesaHub</title>
    <meta name="description" content="Dashboard UMKM & Produk DesaHub - Kelola UMKM desa, katalog produk lokal, dan performa penjualan.">
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>
</head>
<body class="umkm-dashboard-page">
    @php
        $user = auth()->user();
        $villageName = $user->village_name ?? 'Desa Sukamaju';
        $roleLabel = 'Kepala Desa';
        $tabs = ['Ringkasan', 'Daftar UMKM', 'Katalog Produk', 'Pesanan', 'Kategori', 'Ulasan'];
    @endphp

    <div class="sidebar-overlay" data-sidebar-overlay></div>

    <aside class="sidebar umkm-sidebar" data-sidebar>
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

    <header class="topbar umkm-topbar" data-topbar>
        <div class="topbar-left">
            <button class="topbar-hamburger" aria-label="Toggle Menu" data-sidebar-toggle>
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="4" x2="15" y1="7" y2="7"/><line x1="4" x2="12" y1="12" y2="12"/><line x1="4" x2="18" y1="17" y2="17"/>
                </svg>
            </button>
            <span class="topbar-title">UMKM & Produk</span>
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
                <span class="notification-badge">2</span>
            </button>

            <div class="topbar-user" id="user-dropdown-trigger">
                <img src="https://i.pravatar.cc/150?img=12" alt="User Avatar" class="topbar-user-avatar" />
                <div class="topbar-user-info">
                    <div class="topbar-user-name">{{ $roleLabel }}</div>
                    <div class="topbar-user-role">{{ $villageName }}</div>
                </div>
                <svg class="topbar-user-chevron" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>

                <div class="user-dropdown" id="user-dropdown">
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
            <section class="umkm-page-heading">
                <div>
                    <h1>UMKM & Produk</h1>
                    <p>Kelola dan pantau pelaku UMKM, produk lokal, dan performa penjualan desa secara terstruktur.</p>
                </div>
                <div class="umkm-page-actions">
                    <button class="umkm-btn umkm-btn-outline">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" x2="12" y1="15" y2="3"/>
                        </svg>
                        Export Data
                    </button>
                    <button class="umkm-btn umkm-btn-primary">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="12" x2="12" y1="5" y2="19"/><line x1="5" x2="19" y1="12" y2="12"/>
                        </svg>
                        Tambah UMKM
                    </button>
                </div>
            </section>

            <nav class="umkm-tabs" aria-label="Navigasi dashboard UMKM">
                @foreach ($tabs as $index => $tab)
                    <a href="#" class="umkm-tab {{ $index === 0 ? 'active' : '' }}">{{ $tab }}</a>
                @endforeach
            </nav>

            <section class="umkm-section-frame umkm-kpi-frame">
                <article class="umkm-kpi-card">
                    <div class="umkm-kpi-icon blue">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round">
                            <path d="m2 7 4-5h12l4 5"/><path d="M4 11v9h16v-9"/><path d="M9 20v-5h6v5"/><path d="M2 7h20"/><path d="M5 7v4a3 3 0 0 0 6 0V7"/><path d="M11 7v4a3 3 0 0 0 6 0V7"/><path d="M17 7v4a3 3 0 0 0 5 2"/>
                        </svg>
                    </div>
                    <div>
                        <p class="umkm-kpi-label">Total UMKM</p>
                        <p class="umkm-kpi-value">128</p>
                        <p class="umkm-kpi-change">&#9650; 12,3% dari bulan lalu</p>
                    </div>
                </article>
                <article class="umkm-kpi-card">
                    <div class="umkm-kpi-icon green">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M4 10h16"/><path d="M5 10l1.6-5h10.8L19 10"/><path d="M6 10v10h12V10"/><path d="M9 15h6"/>
                        </svg>
                    </div>
                    <div>
                        <p class="umkm-kpi-label">Produk Aktif</p>
                        <p class="umkm-kpi-value">546</p>
                        <p class="umkm-kpi-change">&#9650; 15,7% dari bulan lalu</p>
                    </div>
                </article>
                <article class="umkm-kpi-card">
                    <div class="umkm-kpi-icon red">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M3 20h18"/><path d="M5 17v-4"/><path d="M10 17V9"/><path d="M15 17v-6"/><path d="M20 17V5"/><path d="m5 11 5-5 4 4 6-7"/>
                        </svg>
                    </div>
                    <div>
                        <p class="umkm-kpi-label">Omzet Bulan Ini</p>
                        <p class="umkm-kpi-value">Rp 74,8 jt</p>
                        <p class="umkm-kpi-change">&#9650; 18,9% dari bulan lalu</p>
                    </div>
                </article>
                <article class="umkm-kpi-card">
                    <div class="umkm-kpi-icon orange">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M6 8h12l1.5 12h-15L6 8Z"/><path d="M9 8a3 3 0 0 1 6 0"/><path d="M9 13h6"/><path d="M12 10v6"/>
                        </svg>
                    </div>
                    <div>
                        <p class="umkm-kpi-label">Produk Terjual</p>
                        <p class="umkm-kpi-value">1.245</p>
                        <p class="umkm-kpi-change">&#9650; 20,4% dari bulan lalu</p>
                    </div>
                </article>
                <article class="umkm-kpi-card">
                    <div class="umkm-kpi-icon purple">
                        <svg viewBox="0 0 24 24" fill="currentColor">
                            <rect x="4" y="4" width="7" height="7" rx="2"/><rect x="13" y="4" width="7" height="7" rx="2"/><rect x="4" y="13" width="7" height="7" rx="2"/><rect x="13" y="13" width="7" height="7" rx="2"/>
                        </svg>
                    </div>
                    <div>
                        <p class="umkm-kpi-label">Kategori Aktif</p>
                        <p class="umkm-kpi-value">12</p>
                        <p class="umkm-kpi-change">&#9650; 9,1% dari bulan lalu</p>
                    </div>
                </article>
            </section>

            <section class="umkm-chart-grid">
                <div class="umkm-section-frame umkm-chart-card">
                    <div class="umkm-card-header">
                        <h2>Grafik Performa Penjualan</h2>
                        <button class="umkm-period-btn">6 Bulan Terakhir
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m6 9 6 6 6-6"/></svg>
                        </button>
                    </div>
                    <p class="umkm-chart-label">Omzet (Rp)</p>
                    <div class="umkm-line-chart">
                        <canvas id="umkmSalesChart"></canvas>
                    </div>
                </div>

                <div class="umkm-section-frame umkm-chart-card umkm-donut-card">
                    <div class="umkm-card-header">
                        <h2>Kategori Produk Terlaris</h2>
                    </div>
                    <div class="umkm-donut-layout">
                        <div class="umkm-donut-wrap">
                            <canvas id="umkmCategoryChart"></canvas>
                        </div>
                        <div class="umkm-donut-legend">
                            <div><span><i style="background:#1267ff"></i>Makanan & Minuman</span><strong>42%</strong></div>
                            <div><span><i style="background:#27b957"></i>Kerajinan</span><strong>22%</strong></div>
                            <div><span><i style="background:#ff8c1a"></i>Pertanian Olahan</span><strong>18%</strong></div>
                            <div><span><i style="background:#7651e8"></i>Fashion</span><strong>12%</strong></div>
                            <div><span><i style="background:#f1cc19"></i>Lainnya</span><strong>6%</strong></div>
                        </div>
                    </div>
                </div>
            </section>

            <section class="umkm-table-grid">
                <div class="umkm-section-frame umkm-table-card">
                    <h2>Daftar UMKM Unggulan</h2>
                    <div class="umkm-table-wrap">
                        <table class="umkm-table umkm-featured-table">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>Nama UMKM</th>
                                    <th>Kategori</th>
                                    <th>Produk Utama</th>
                                    <th>Omzet Bulan Ini</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ([
                                    ['Keripik Pisang Makmur', 'Makanan & Minuman', 'Keripik Pisang', 'Rp 12,6 jt', 'purple'],
                                    ['Denimji Craft', 'Kerajinan', 'Tas Denim', 'Rp 8,7 jt', 'orange'],
                                    ['Ternak Sapi Berkah', 'Pertanian Olahan', 'Daging Sapi', 'Rp 10,3 jt', 'green'],
                                    ['Kopi Sukamaju', 'Makanan & Minuman', 'Kopi Bubuk', 'Rp 9,4 jt', 'green'],
                                    ['Sambal Ibu Sari', 'Makanan & Minuman', 'Sambal Botol', 'Rp 7,2 jt', 'orange'],
                                ] as $index => $item)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td><span class="umkm-business-icon {{ $item[4] }}"><x-sidebar-icon name="umkm" /></span>{{ $item[0] }}</td>
                                        <td>{{ $item[1] }}</td>
                                        <td>{{ $item[2] }}</td>
                                        <td>{{ $item[3] }}</td>
                                        <td><span class="umkm-status active">Aktif</span></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <a href="{{ route('umkm.products.page') }}" class="umkm-table-link">Lihat Semua UMKM
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></svg>
                    </a>
                </div>

                <div class="umkm-section-frame umkm-table-card">
                    <h2>Produk Terbaru / Aktivitas Produk</h2>
                    <div class="umkm-table-wrap">
                        <table class="umkm-table umkm-product-table">
                            <thead>
                                <tr>
                                    <th>Produk</th>
                                    <th>Pemilik UMKM</th>
                                    <th>Stok</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ([
                                    ['Kopi Arabika Sukamaju 250g', 'Kopi Sukamaju', '120 pcs', 'Baru', 'new', 'coffee'],
                                    ['Tas Denim Tote Bag', 'Denimji Craft', '45 pcs', 'Baru', 'new', 'bag'],
                                    ['Keripik Pisang Original', 'Keripik Pisang Makmur', '80 pcs', 'Tersedia', 'available', 'chips'],
                                    ['Sambal Terasi Pedas', 'Sambal Ibu Sari', '60 pcs', 'Tersedia', 'available', 'sambal'],
                                    ['Dendeng Sapi Original', 'Ternak Sapi Berkah', '30 pcs', 'Hampir Habis', 'low', 'meat'],
                                ] as $product)
                                    <tr>
                                        <td>
                                            <span class="umkm-product-name-cell">
                                                <span class="umkm-product-thumb {{ $product[5] }}"></span>
                                                <span>{{ $product[0] }}</span>
                                            </span>
                                        </td>
                                        <td>{{ $product[1] }}</td>
                                        <td>{{ $product[2] }}</td>
                                        <td><span class="umkm-status {{ $product[4] }}">{{ $product[3] }}</span></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <a href="{{ route('umkm.products.page') }}" class="umkm-table-link">Lihat Semua Produk
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></svg>
                    </a>
                </div>
            </section>

            <footer class="umkm-footer">
                <span>DesaHub - Ekosistem Ekonomi Desa</span>
                <span>&copy; 2025 DesaHub. All rights reserved.</span>
                <span>Nakala Digital&nbsp;&nbsp; x &nbsp;&nbsp;Romulus Digital<br>Strategic Partner - Singapore/Vietnam</span>
            </footer>
        </div>
    </main>

    <script>
    document.addEventListener('DOMContentLoaded', () => {
        const trigger = document.getElementById('user-dropdown-trigger');
        const dropdown = document.getElementById('user-dropdown');
        trigger?.addEventListener('click', (event) => {
            event.stopPropagation();
            dropdown?.classList.toggle('show');
        });
        document.addEventListener('click', () => dropdown?.classList.remove('show'));

        const salesCtx = document.getElementById('umkmSalesChart')?.getContext('2d');
        if (salesCtx) {
            new Chart(salesCtx, {
                type: 'line',
                data: {
                    labels: ['Des 2024', 'Jan 2025', 'Feb 2025', 'Mar 2025', 'Apr 2025', 'Mei 2025'],
                    datasets: [{
                        data: [32.4, 41.7, 55.2, 63.8, 68.1, 74.8],
                        borderColor: '#1267ff',
                        backgroundColor: 'rgba(18, 103, 255, 0.04)',
                        borderWidth: 3,
                        pointRadius: 5,
                        pointHoverRadius: 6,
                        pointBackgroundColor: '#1267ff',
                        pointBorderColor: '#ffffff',
                        pointBorderWidth: 2,
                        tension: 0.35,
                        fill: false,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    layout: {
                        padding: { top: 22, right: 18, bottom: 0, left: 2 }
                    },
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
                            min: 20,
                            max: 100,
                            ticks: {
                                stepSize: 20,
                                color: '#062a70',
                                font: { family: 'Inter', size: 11, weight: 600 },
                                callback: (value) => `${value} jt`
                            },
                            grid: { color: '#dce7f7' },
                            border: { display: false }
                        }
                    },
                    animation: false
                },
                plugins: [{
                    id: 'valueLabels',
                    afterDatasetsDraw(chart) {
                        const { ctx } = chart;
                        const meta = chart.getDatasetMeta(0);
                        ctx.save();
                        ctx.fillStyle = '#062a70';
                        ctx.font = '700 11px Inter, sans-serif';
                        ctx.textAlign = 'center';
                        chart.data.datasets[0].data.forEach((value, index) => {
                            const point = meta.data[index];
                            const nudge = index === 0 ? 18 : (index === chart.data.datasets[0].data.length - 1 ? -10 : 0);
                            ctx.fillText(`${String(value).replace('.', ',')} jt`, point.x + nudge, point.y - 12);
                        });
                        ctx.restore();
                    }
                }]
            });
        }

        const categoryCtx = document.getElementById('umkmCategoryChart')?.getContext('2d');
        if (categoryCtx) {
            new Chart(categoryCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Makanan & Minuman', 'Kerajinan', 'Pertanian Olahan', 'Fashion', 'Lainnya'],
                    datasets: [{
                        data: [42, 22, 18, 12, 6],
                        backgroundColor: ['#1267ff', '#27b957', '#ff8c1a', '#7651e8', '#f1cc19'],
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
