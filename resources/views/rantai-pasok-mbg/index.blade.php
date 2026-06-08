<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Rantai Pasok MBG | DesaHub</title>
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>
</head>
<body class="mbg-chain-page supply-chain-page">
@php
    $user = auth()->user();
    $villageName = $user->village_name ?? 'Desa Sukamaju';
    $tabs = ['Ringkasan', 'Permintaan', 'Supplier', 'Distribusi', 'Sekolah Penerima', 'Laporan'];
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
        <span class="topbar-title">Rantai Pasok MBG</span>
    </div>
    <div class="topbar-right">
        <button class="topbar-icon-btn" aria-label="Help"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/><path d="M12 17h.01"/></svg></button>
        <button class="topbar-icon-btn" aria-label="Notifications"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9"/><path d="M10.3 21a1.94 1.94 0 0 0 3.4 0"/></svg><span class="notification-badge">3</span></button>
        <div class="topbar-user" id="mbg-chain-user-trigger">
            <img src="https://i.pravatar.cc/150?img=12" alt="User Avatar" class="topbar-user-avatar" />
            <div class="topbar-user-info"><div class="topbar-user-name">Kepala Desa</div><div class="topbar-user-role">{{ $villageName }}</div></div>
            <svg class="topbar-user-chevron" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m6 9 6 6 6-6"/></svg>
            <div class="user-dropdown" id="mbg-chain-user-dropdown">
                <form method="POST" action="{{ route('logout') }}">@csrf<button type="submit">Logout</button></form>
            </div>
        </div>
    </div>
</header>

<main class="main-content fin-main-content" data-main-content>
        <div class="main-inner fin-main-inner">
        <section class="mbg-chain-heading">
            <div>
                <h1>Rantai Pasok MBG</h1>
                <p>Kelola kebutuhan, pasokan, supplier, distribusi, dan pemenuhan program MBG desa secara terstruktur.</p>
            </div>
            <div class="mbg-chain-actions">
                <button class="mbg-chain-btn mbg-chain-btn-outline">Export Data</button>
                <button class="mbg-chain-btn mbg-chain-btn-primary">Buat Permintaan</button>
            </div>
        </section>

        <nav class="mbg-chain-tabs">
            @foreach ($tabs as $index => $tab)
                <a href="#" class="mbg-chain-tab {{ $index === 0 ? 'active' : '' }}">{{ $tab }}</a>
            @endforeach
        </nav>

        <section class="mbg-chain-kpi-grid">
            <article class="mbg-chain-kpi"><div class="mbg-chain-kpi-icon orange">🍽</div><div><p>Total Demand MBG</p><strong>2.850 <small>porsi</small></strong><span>+ 12% dari bulan lalu</span></div></article>
            <article class="mbg-chain-kpi"><div class="mbg-chain-kpi-icon purple">👥</div><div><p>Total Supplier Aktif</p><strong>24</strong><span>+ 9% dari bulan lalu</span></div></article>
            <article class="mbg-chain-kpi"><div class="mbg-chain-kpi-icon green">◔</div><div><p>Pemenuhan Pasokan</p><strong>85,3%</strong><span>+ 6,2% dari bulan lalu</span></div></article>
            <article class="mbg-chain-kpi"><div class="mbg-chain-kpi-icon blue">🚚</div><div><p>Pengiriman Hari Ini</p><strong>12</strong><span>+ 3 dari kemarin</span></div></article>
            <article class="mbg-chain-kpi"><div class="mbg-chain-kpi-icon cyan">🏫</div><div><p>Sekolah Penerima</p><strong>8</strong><span>+ 1 dari bulan lalu</span></div></article>
        </section>

        <section class="mbg-chain-chart-grid">
            <div class="mbg-chain-card mbg-chain-chart-card">
                <div class="mbg-chain-card-header"><h2>Tren Kebutuhan & Pasokan</h2></div>
                <div class="mbg-chain-legend"><span><i style="background:#1267ff"></i>Kebutuhan (Porsi)</span><span><i style="background:#22c55e"></i>Pasokan (Porsi)</span></div>
                <div class="mbg-chain-line-chart"><canvas id="mbgSupplyTrendChart"></canvas></div>
            </div>
            <div class="mbg-chain-card mbg-chain-chart-card">
                <div class="mbg-chain-card-header"><h2>Status Pemenuhan MBG</h2></div>
                <div class="mbg-chain-donut-layout">
                    <div class="mbg-chain-donut-wrap"><canvas id="mbgStatusChart"></canvas></div>
                    <div class="mbg-chain-donut-legend">
                        <div><span><i style="background:#16a34a"></i>Tepat Waktu</span><strong>1.680 porsi (59,0%)</strong></div>
                        <div><span><i style="background:#f97316"></i>Parsial</span><strong>720 porsi (25,3%)</strong></div>
                        <div><span><i style="background:#ef4444"></i>Tertunda</span><strong>300 porsi (10,5%)</strong></div>
                        <div><span><i style="background:#94a3b8"></i>Belum Dipenuhi</span><strong>150 porsi (5,2%)</strong></div>
                        <div class="total"><span>Total</span><strong>2.850 porsi</strong></div>
                    </div>
                </div>
            </div>
        </section>

        <section class="mbg-chain-table-grid">
            <div class="mbg-chain-card mbg-chain-table-card">
                <h2>Supplier Utama</h2>
                <table class="mbg-chain-table mbg-supplier-table">
                    <thead><tr><th>No</th><th>Nama Supplier</th><th>Kategori</th><th>Kapasitas</th><th>Pemenuhan</th><th>Status</th></tr></thead>
                    <tbody>
                    @foreach ([['Beras Makmur','Pangan Pokok','1.000 porsi/hari','92%','Aktif','active'],['Tani Makmur','Sayuran','800 porsi/hari','88%','Aktif','active'],['Telur Sejahtera','Protein Hewani','600 porsi/hari','76%','Stabil','stable'],['Sayur Hijau','Sayuran','700 porsi/hari','65%','Evaluasi','warning'],['Dapur Gizi','Olahan & Siap Saji','500 porsi/hari','90%','Aktif','active']] as $i => $row)
                        <tr><td>{{ $i + 1 }}</td><td>{{ $row[0] }}</td><td>{{ $row[1] }}</td><td>{{ $row[2] }}</td><td>{{ $row[3] }}</td><td><span class="mbg-chain-status {{ $row[5] }}">{{ $row[4] }}</span></td></tr>
                    @endforeach
                    </tbody>
                </table>
                <a href="#" class="mbg-chain-table-link">Lihat Semua Supplier <span>›</span></a>
            </div>
            <div class="mbg-chain-card mbg-chain-table-card">
                <h2>Distribusi / Pengiriman Terbaru</h2>
                <table class="mbg-chain-table mbg-distribution-table">
                    <thead><tr><th>No</th><th>Tujuan</th><th>Produk</th><th>Nominal/Porsi</th><th>Jadwal</th><th>Status</th></tr></thead>
                    <tbody>
                    @foreach ([['SDN Sukamaju 01','Nasi + Lauk + Sayur','350 porsi','22 Mei 2025','Terkirim','sent'],['SMP Desa Maju','Nasi + Lauk + Sayur','280 porsi','22 Mei 2025','Diproses','process'],['PAUD Melati','Nasi + Lauk + Sayur','120 porsi','22 Mei 2025','Dalam Persiapan','prepare'],['SDN Sukamaju 02','Nasi + Lauk + Sayur','300 porsi','23 Mei 2025','Diproses','process'],['SMK Harapan Desa','Nasi + Lauk + Sayur','250 porsi','23 Mei 2025','Tertunda','late']] as $i => $row)
                        <tr><td>{{ $i + 1 }}</td><td>{{ $row[0] }}</td><td>{{ $row[1] }}</td><td>{{ $row[2] }}</td><td>{{ $row[3] }}</td><td><span class="mbg-chain-status {{ $row[5] }}">{{ $row[4] }}</span></td></tr>
                    @endforeach
                    </tbody>
                </table>
                <a href="#" class="mbg-chain-table-link">Lihat Semua Pengiriman <span>›</span></a>
            </div>
        </section>

        <footer class="mbg-chain-footer">
            <span>DesaHub - Ekosistem Ekonomi Desa</span>
            <span>&copy; 2025 DesaHub. All rights reserved.</span>
            <span>Nakala Digital&nbsp;&nbsp; x &nbsp;&nbsp;Romulus Digital<br>Strategic Partner - Singapore/Vietnam</span>
        </footer>
    </div>
</main>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const trigger = document.getElementById('mbg-chain-user-trigger');
    const dropdown = document.getElementById('mbg-chain-user-dropdown');
    trigger?.addEventListener('click', (event) => { event.stopPropagation(); dropdown?.classList.toggle('show'); });
    document.addEventListener('click', () => dropdown?.classList.remove('show'));

    const supplyValueLabels = {
        id: 'supplyValueLabels',
        afterDatasetsDraw(chart) {
            const { ctx } = chart;
            ctx.save();
            ctx.font = '800 10px Inter, sans-serif';
            chart.data.datasets.forEach((dataset, datasetIndex) => {
                const meta = chart.getDatasetMeta(datasetIndex);
                ctx.fillStyle = dataset.borderColor;
                ctx.textBaseline = datasetIndex === 0 ? 'bottom' : 'top';
                dataset.data.forEach((value, index) => {
                    const point = meta.data[index];
                    const isFirst = index === 0;
                    const isLast = index === dataset.data.length - 1;
                    ctx.textAlign = isFirst ? 'left' : (isLast ? 'right' : 'center');
                    const x = point.x + (isFirst ? 8 : (isLast ? -5 : 0));
                    const y = point.y + (datasetIndex === 0 ? -18 : 18);
                    ctx.fillText(value.toLocaleString('id-ID'), x, y);
                });
            });
            ctx.restore();
        }
    };

    const trendCtx = document.getElementById('mbgSupplyTrendChart')?.getContext('2d');
    if (trendCtx) {
        new Chart(trendCtx, {
            type: 'line',
            data: {
                labels: ['Des 2024','Jan 2025','Feb 2025','Mar 2025','Apr 2025','Mei 2025'],
                datasets: [
                    { data: [2200,2350,2480,2620,2740,2850], borderColor: '#1267ff', pointBackgroundColor: '#1267ff', pointBorderColor: '#fff', pointBorderWidth: 2, pointRadius: 4, pointHoverRadius: 4, borderWidth: 3, tension: .32 },
                    { data: [1950,2150,2220,2380,2500,2430], borderColor: '#22c55e', pointBackgroundColor: '#22c55e', pointBorderColor: '#fff', pointBorderWidth: 2, pointRadius: 4, pointHoverRadius: 4, borderWidth: 3, tension: .32 }
                ]
            },
            options: {
                responsive: true, maintainAspectRatio: false, animation: false,
                layout: { padding: { top: 30, right: 22, left: 10, bottom: 10 } },
                plugins: { legend: { display: false } },
                scales: {
                    x: {
                        offset: true,
                        grid: { display: false },
                        ticks: { color: '#062a70', padding: 8, font: { family: 'Inter', size: 10, weight: 700 } },
                        border: { color: '#9fb8dc' }
                    },
                    y: {
                        min: 0,
                        max: 4000,
                        ticks: { stepSize: 1000, color: '#062a70', padding: 4, font: { family: 'Inter', size: 10, weight: 700 }, callback: (v) => v.toLocaleString('id-ID') },
                        grid: { color: '#dce7f7', drawTicks: false },
                        border: { display: false }
                    }
                }
            },
            plugins: [supplyValueLabels]
        });
    }
    const statusCtx = document.getElementById('mbgStatusChart')?.getContext('2d');
    if (statusCtx) {
        new Chart(statusCtx, {
            type: 'doughnut',
            data: { datasets: [{ data: [59,25.3,10.5,5.2], backgroundColor: ['#16a34a','#f97316','#ef4444','#94a3b8'], borderColor: '#fff', borderWidth: 3 }] },
            options: { responsive: true, maintainAspectRatio: false, cutout: '58%', animation: false, plugins: { legend: { display: false } } }
        });
    }
});
</script>
</body>
</html>
