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
    <x-app-sidebar />
    <x-app-topbar title="Rantai Pasok MBG" />

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
            <x-app-footer />
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
