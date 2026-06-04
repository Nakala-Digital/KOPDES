<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Gudang & Logistik | DesaHub</title>
    <meta name="description" content="Dashboard Gudang & Logistik DesaHub - Pantau stok, pergerakan barang, pengiriman, dan status logistik desa.">
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>
</head>
<body class="mbg-chain-page warehouse-page">
@php
    $user = auth()->user();
    $villageName = $user->village_name ?? 'Desa Sukamaju';
    $tabs = ['Ringkasan', 'Stok Barang', 'Barang Masuk', 'Barang Keluar', 'Pengiriman', 'Laporan'];
@endphp

<div class="sidebar-overlay" data-sidebar-overlay></div>

<aside class="sidebar" data-sidebar>
    <div class="sidebar-brand">
        <div class="sidebar-brand-icon">
            <svg viewBox="0 0 40 40" fill="none">
                <rect width="40" height="40" rx="10" fill="#2d5cf6"/>
                <path d="M12 10h4l6 10-6 10h-4l6-10-6-10z" fill="#fff"/>
                <path d="M18 10h4l6 10-6 10h-4l6-10-6-10z" fill="rgba(255,255,255,0.5)"/>
            </svg>
        </div>
        <div class="sidebar-brand-text" data-sidebar-brand-text>
            <h1>DesaHub</h1>
            <p>Ekosistem Ekonomi Desa</p>
        </div>
    </div>

    <nav class="sidebar-nav">
        <a href="{{ route('dashboard') }}" class="sidebar-link"><x-sidebar-icon name="dashboard" /><span>Dashboard</span></a>
        <p class="sidebar-category">Data & Informasi</p>
        <a href="{{ route('pendataan.index') }}" class="sidebar-link"><x-sidebar-icon name="pendataan-desa" /><span>Pendataan Desa</span></a>
        <a href="{{ route('pendataan.index') }}" class="sidebar-link"><x-sidebar-icon name="penduduk" /><span>Penduduk</span></a>
        <a href="{{ route('pendataan.index') }}" class="sidebar-link"><x-sidebar-icon name="potensi-desa" /><span>Potensi Desa</span></a>
        <p class="sidebar-category">Kelembagaan</p>
        <a href="{{ route('kopdes.index') }}" class="sidebar-link"><x-sidebar-icon name="kopdes" /><span>Kopdes/KDMP</span></a>
        <a href="{{ route('bumdes.index') }}" class="sidebar-link"><x-sidebar-icon name="bumdes" /><span>BUMDes</span></a>
        <p class="sidebar-category">Ekonomi</p>
        <a href="{{ route('umkm.index') }}" class="sidebar-link"><x-sidebar-icon name="umkm" /><span>UMKM & Produk</span></a>
        <a href="{{ route('pasar-desa.index') }}" class="sidebar-link"><x-sidebar-icon name="pasar-desa" /><span>Pasar Desa</span></a>
        <p class="sidebar-category">Supply Chain & MBG</p>
        <a href="{{ route('rantai-pasok-mbg.index') }}" class="sidebar-link"><x-sidebar-icon name="rantai-pasok" /><span>Rantai Pasok MBG</span></a>
        <a href="{{ route('gudang-logistik.index') }}" class="sidebar-link active"><x-sidebar-icon name="gudang" /><span>Gudang & Logistik</span></a>
        <a href="{{ route('penerima-manfaat.index') }}" class="sidebar-link"><x-sidebar-icon name="penerima-manfaat" /><span>Penerima Manfaat</span></a>
        <p class="sidebar-category">Keuangan</p>
        <a href="{{ route('keuangan.index') }}" class="sidebar-link"><x-sidebar-icon name="keuangan" /><span>Keuangan & Transaksi</span></a>
        <p class="sidebar-category">Laporan</p>
        <a href="{{ route('analitik.index') }}" class="sidebar-link"><x-sidebar-icon name="dashboard-analitik" /><span>Dashboard & Analitik</span></a>
        <a href="{{ route('laporan.index') }}" class="sidebar-link"><x-sidebar-icon name="laporan" /><span>Laporan</span></a>
        <span class="mbg-chain-sidebar-divider"></span>
        <a href="{{ route('pengaturan.index') }}" class="sidebar-link"><x-sidebar-icon name="pengaturan" /><span>Pengaturan</span></a>
    </nav>

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
        <span class="topbar-title">Gudang & Logistik</span>
    </div>
    <div class="topbar-right">
        <button class="topbar-icon-btn" aria-label="Help"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/><path d="M12 17h.01"/></svg></button>
        <button class="topbar-icon-btn" aria-label="Notifications"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9"/><path d="M10.3 21a1.94 1.94 0 0 0 3.4 0"/></svg><span class="notification-badge">2</span></button>
        <div class="topbar-user" id="warehouse-user-trigger">
            <img src="https://i.pravatar.cc/150?img=12" alt="User Avatar" class="topbar-user-avatar" />
            <div class="topbar-user-info"><div class="topbar-user-name">Kepala Desa</div><div class="topbar-user-role">{{ $villageName }}</div></div>
            <svg class="topbar-user-chevron" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m6 9 6 6 6-6"/></svg>
            <div class="user-dropdown" id="warehouse-user-dropdown">
                <form method="POST" action="{{ route('logout') }}">@csrf<button type="submit">Logout</button></form>
            </div>
        </div>
    </div>
</header>

<main class="main-content mbg-chain-main" data-main-content>
    <div class="main-inner mbg-chain-inner">
        <section class="mbg-chain-heading">
            <div>
                <h1>Gudang & Logistik</h1>
                <p>Pantau stok, pergerakan barang, pengiriman, dan status logistik secara real-time.</p>
            </div>
            <div class="mbg-chain-actions">
                <button class="mbg-chain-btn mbg-chain-btn-outline">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" x2="12" y1="15" y2="3"/></svg>
                    Export Data
                </button>
                <button class="mbg-chain-btn mbg-chain-btn-primary">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4"><line x1="12" x2="12" y1="5" y2="19"/><line x1="5" x2="19" y1="12" y2="12"/></svg>
                    Tambah Barang
                </button>
            </div>
        </section>

        <nav class="mbg-chain-tabs">
            @foreach ($tabs as $index => $tab)
                <a href="#" class="mbg-chain-tab {{ $index === 0 ? 'active' : '' }}">{{ $tab }}</a>
            @endforeach
        </nav>

        <section class="mbg-chain-kpi-grid">
            <article class="mbg-chain-kpi"><div class="mbg-chain-kpi-icon purple"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M21 8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16Z"/><path d="m3.3 7 8.7 5 8.7-5"/><path d="M12 22V12"/></svg></div><div><p>Total SKU</p><strong>486</strong><span>+ 8% dari bulan lalu</span></div></article>
            <article class="mbg-chain-kpi"><div class="mbg-chain-kpi-icon green"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M21 8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16Z"/><path d="m3.3 7 8.7 5 8.7-5"/><path d="M12 22V12"/></svg></div><div><p>Stok Tersedia</p><strong>12.840 <small>unit</small></strong><span>+ 6,5% dari bulan lalu</span></div></article>
            <article class="mbg-chain-kpi"><div class="mbg-chain-kpi-icon blue"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.3"><path d="M12 3v12"/><path d="m7 10 5 5 5-5"/><path d="M5 21h14"/></svg></div><div><p>Barang Masuk Hari Ini</p><strong>58 <small>item</small></strong><span>+ 12% dari kemarin</span></div></article>
            <article class="mbg-chain-kpi"><div class="mbg-chain-kpi-icon orange"><x-sidebar-icon name="gudang" /></div><div><p>Pengiriman Hari Ini</p><strong>14</strong><span>+ 7% dari kemarin</span></div></article>
            <article class="mbg-chain-kpi"><div class="mbg-chain-kpi-icon cyan"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M3 21h18"/><path d="M5 21V7l8-4 8 4v14"/><path d="M9 21v-8h8v8"/><path d="M9 9h.01"/><path d="M13 9h.01"/><path d="M17 9h.01"/></svg></div><div><p>Gudang Aktif</p><strong>3</strong><span>+ 0% dari bulan lalu</span></div></article>
        </section>

        <section class="mbg-chain-chart-grid">
            <div class="mbg-chain-card mbg-chain-chart-card">
                <div class="mbg-chain-card-header"><h2>Tren Pergerakan Stok</h2></div>
                <div class="mbg-chain-legend"><span><i style="background:#16a34a"></i>Barang Masuk</span><span><i style="background:#1267ff"></i>Barang Keluar</span></div>
                <div class="mbg-chain-line-chart"><canvas id="warehouseStockChart"></canvas></div>
            </div>
            <div class="mbg-chain-card mbg-chain-chart-card">
                <div class="mbg-chain-card-header"><h2>Status Ketersediaan Stok</h2></div>
                <div class="mbg-chain-donut-layout">
                    <div class="mbg-chain-donut-wrap"><canvas id="warehouseStatusChart"></canvas></div>
                    <div class="mbg-chain-donut-legend">
                        <div><span><i style="background:#16a34a"></i>Aman</span><strong>320 SKU (65,8%)</strong></div>
                        <div><span><i style="background:#f5b80f"></i>Menipis</span><strong>110 SKU (22,6%)</strong></div>
                        <div><span><i style="background:#ef4444"></i>Kritis</span><strong>38 SKU (7,8%)</strong></div>
                        <div><span><i style="background:#94a3b8"></i>Kosong</span><strong>18 SKU (3,7%)</strong></div>
                        <div class="total"><span>Total</span><strong>486 SKU</strong></div>
                    </div>
                </div>
            </div>
        </section>

        <section class="mbg-chain-table-grid">
            <div class="mbg-chain-card mbg-chain-table-card">
                <h2>Stok Prioritas / Inventaris Utama</h2>
                <table class="mbg-chain-table warehouse-stock-table">
                    <thead><tr><th>No</th><th>Nama Barang</th><th>Kategori</th><th>Stok Saat Ini</th><th>Satuan</th><th>Status</th></tr></thead>
                    <tbody>
                    @foreach ([['Beras Premium','Bahan Pokok','3.250','kg','Aman','active'],['Telur Ayam','Protein Hewani','1.280','butir','Menipis','warning'],['Minyak Goreng','Bahan Pokok','640','liter','Menipis','warning'],['Gula Pasir','Bahan Pokok','210','kg','Kritis','late'],['Paket Sayur MBG','Sayuran','120','paket','Kritis','late']] as $i => $row)
                        <tr><td>{{ $i + 1 }}</td><td>{{ $row[0] }}</td><td>{{ $row[1] }}</td><td>{{ $row[2] }}</td><td>{{ $row[3] }}</td><td><span class="mbg-chain-status {{ $row[5] }}">{{ $row[4] }}</span></td></tr>
                    @endforeach
                    </tbody>
                </table>
                <a href="#" class="mbg-chain-table-link">Lihat Semua Stok <span>&gt;</span></a>
            </div>
            <div class="mbg-chain-card mbg-chain-table-card">
                <h2>Aktivitas Gudang / Pengiriman Terbaru</h2>
                <table class="mbg-chain-table warehouse-activity-table">
                    <thead><tr><th>No</th><th>Tanggal</th><th>Aktivitas</th><th>Tujuan</th><th>Status</th></tr></thead>
                    <tbody>
                    @foreach ([['22 Mei 2025 10.30','Barang Masuk','Supplier Beras Jaya','Selesai','active'],['22 Mei 2025 09.15','Picking','Dapur MBG Desa','Diproses','process'],['21 Mei 2025 08.45','Pengiriman','SDN Sukamaju 01','Dalam Pengiriman','prepare'],['21 Mei 2025 16.20','Restock','Gudang Utama','Selesai','active'],['21 Mei 2025 14.10','Retur','Supplier Telur Makmur','Tertunda','late']] as $i => $row)
                        <tr><td>{{ $i + 1 }}</td><td>{{ $row[0] }}</td><td>{{ $row[1] }}</td><td>{{ $row[2] }}</td><td><span class="mbg-chain-status {{ $row[4] }}">{{ $row[3] }}</span></td></tr>
                    @endforeach
                    </tbody>
                </table>
                <a href="#" class="mbg-chain-table-link">Lihat Semua Aktivitas <span>&gt;</span></a>
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
    const trigger = document.getElementById('warehouse-user-trigger');
    const dropdown = document.getElementById('warehouse-user-dropdown');
    trigger?.addEventListener('click', (event) => { event.stopPropagation(); dropdown?.classList.toggle('show'); });
    document.addEventListener('click', () => dropdown?.classList.remove('show'));

    const valueLabelPlugin = {
        id: 'warehouseValueLabels',
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
                    const x = point.x + (isFirst ? 8 : (isLast ? -4 : 0));
                    const y = point.y + (datasetIndex === 0 ? -12 : 12);
                    ctx.fillText(value.toLocaleString('id-ID'), x, y);
                });
            });
            ctx.restore();
        }
    };

    const stockCtx = document.getElementById('warehouseStockChart')?.getContext('2d');
    if (stockCtx) {
        new Chart(stockCtx, {
            type: 'line',
            data: {
                labels: ['Des 2024','Jan 2025','Feb 2025','Mar 2025','Apr 2025','Mei 2025'],
                datasets: [
                    { data: [3200,3800,4300,4800,5600,6200], borderColor: '#16a34a', pointBackgroundColor: '#16a34a', pointBorderColor: '#fff', pointBorderWidth: 2, pointRadius: 4, pointHoverRadius: 4, borderWidth: 3, tension: .32 },
                    { data: [2100,2600,2900,3400,3900,4500], borderColor: '#1267ff', pointBackgroundColor: '#1267ff', pointBorderColor: '#fff', pointBorderWidth: 2, pointRadius: 4, pointHoverRadius: 4, borderWidth: 3, tension: .32 }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                animation: false,
                layout: { padding: { top: 34, right: 22, left: 10, bottom: 12 } },
                plugins: { legend: { display: false }, tooltip: { backgroundColor: '#071d4f' } },
                scales: {
                    x: {
                        offset: true,
                        grid: { display: false },
                        ticks: { color: '#062a70', padding: 8, font: { family: 'Inter', size: 10, weight: 700 } },
                        border: { color: '#9fb8dc' }
                    },
                    y: {
                        min: 0,
                        max: 8000,
                        ticks: {
                            stepSize: 2000,
                            color: '#062a70',
                            padding: 4,
                            font: { family: 'Inter', size: 10, weight: 700 },
                            callback: (v) => v.toLocaleString('id-ID')
                        },
                        grid: { color: '#dce7f7', drawTicks: false },
                        border: { display: false }
                    }
                }
            },
            plugins: [valueLabelPlugin]
        });
    }

    const statusCtx = document.getElementById('warehouseStatusChart')?.getContext('2d');
    if (statusCtx) {
        new Chart(statusCtx, {
            type: 'doughnut',
            data: { datasets: [{ data: [65.8,22.6,7.8,3.7], backgroundColor: ['#16a34a','#f5b80f','#ef4444','#94a3b8'], borderColor: '#fff', borderWidth: 3 }] },
            options: { responsive: true, maintainAspectRatio: false, cutout: '58%', animation: false, plugins: { legend: { display: false } } }
        });
    }
});
</script>
</body>
</html>
