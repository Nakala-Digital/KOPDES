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
    <x-app-sidebar />
    <x-app-topbar :title="'Gudang & Logistik'" />

<main class="main-content fin-main-content" data-main-content>
        <div class="main-inner fin-main-inner">
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
            <x-app-footer />
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
