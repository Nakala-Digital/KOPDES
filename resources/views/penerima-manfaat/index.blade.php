<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Penerima Manfaat | DesaHub</title>
    <meta name="description" content="Dashboard Penerima Manfaat DesaHub - Pantau data penerima, distribusi bantuan, dan cakupan program MBG desa.">
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>
</head>
<body class="mbg-chain-page beneficiary-page">
@php
    $user = auth()->user();
    $villageName = $user->village_name ?? 'Desa Sukamaju';
    $tabs = ['Ringkasan', 'Daftar Penerima', 'Verifikasi', 'Penyaluran', 'Riwayat Bantuan', 'Laporan'];
@endphp
    <x-app-sidebar />
    <x-app-topbar title="Penerima Manfaat" />

<main class="main-content fin-main-content" data-main-content>
        <div class="main-inner fin-main-inner">
        <section class="mbg-chain-heading">
            <div>
                <h1>Penerima Manfaat</h1>
                <p>Kelola dan pantau data penerima manfaat program desa secara cepat, akurat, dan terverifikasi.</p>
            </div>
            <div class="mbg-chain-actions">
                <button class="mbg-chain-btn mbg-chain-btn-outline">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" x2="12" y1="15" y2="3"/></svg>
                    Export Data
                </button>
                <button class="mbg-chain-btn mbg-chain-btn-primary">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4"><line x1="12" x2="12" y1="5" y2="19"/><line x1="5" x2="19" y1="12" y2="12"/></svg>
                    Tambah Penerima
                </button>
            </div>
        </section>

        <nav class="mbg-chain-tabs">
            @foreach ($tabs as $index => $tab)
                <a href="#" class="mbg-chain-tab {{ $index === 0 ? 'active' : '' }}">{{ $tab }}</a>
            @endforeach
        </nav>

        <section class="mbg-chain-kpi-grid">
            <article class="mbg-chain-kpi"><div class="mbg-chain-kpi-icon purple"><x-sidebar-icon name="penduduk" /></div><div><p>Total Penerima Manfaat</p><strong>642</strong><span>+ 8,7% dari bulan lalu</span></div></article>
            <article class="mbg-chain-kpi"><div class="mbg-chain-kpi-icon green"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.3"><path d="m3 11 9-8 9 8"/><path d="M5 10v10h14V10"/><path d="M9 20v-6h6v6"/></svg></div><div><p>Keluarga Aktif</p><strong>418</strong><span>+ 6,2% dari bulan lalu</span></div></article>
            <article class="mbg-chain-kpi"><div class="mbg-chain-kpi-icon blue"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.3"><path d="m22 10-10-5-10 5 10 5 10-5Z"/><path d="M6 12v5c3 2 9 2 12 0v-5"/><path d="M22 10v6"/></svg></div><div><p>Siswa Penerima MBG</p><strong>356</strong><span>+ 7,1% dari bulan lalu</span></div></article>
            <article class="mbg-chain-kpi"><div class="mbg-chain-kpi-icon orange"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.3"><circle cx="12" cy="7" r="4"/><path d="M6 21v-2a6 6 0 0 1 12 0v2"/><path d="M19 8v4"/><path d="M21 10h-4"/></svg></div><div><p>Lansia / Disabilitas</p><strong>124</strong><span>+ 5,4% dari bulan lalu</span></div></article>
            <article class="mbg-chain-kpi"><div class="mbg-chain-kpi-icon cyan"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.3"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10Z"/><path d="m9 12 2 2 4-5"/></svg></div><div><p>Tingkat Verifikasi</p><strong>96,4%</strong><span>+ 3,8% dari bulan lalu</span></div></article>
        </section>

        <section class="mbg-chain-chart-grid">
            <div class="mbg-chain-card mbg-chain-chart-card">
                <div class="mbg-chain-card-header"><h2>Tren Verifikasi & Penyaluran</h2></div>
                <div class="mbg-chain-legend"><span><i style="background:#1267ff"></i>Terverifikasi</span><span><i style="background:#16a34a"></i>Tersalurkan</span></div>
                <div class="mbg-chain-line-chart"><canvas id="beneficiaryTrendChart"></canvas></div>
            </div>
            <div class="mbg-chain-card mbg-chain-chart-card">
                <div class="mbg-chain-card-header"><h2>Status Penerima Manfaat</h2></div>
                <div class="mbg-chain-donut-layout">
                    <div class="mbg-chain-donut-wrap"><canvas id="beneficiaryCategoryChart"></canvas></div>
                    <div class="mbg-chain-donut-legend">
                        <div><span><i style="background:#16a34a"></i>Terverifikasi</span><strong>468 (72,9%)</strong></div>
                        <div><span><i style="background:#f5b80f"></i>Menunggu Verifikasi</span><strong>88 (13,7%)</strong></div>
                        <div><span><i style="background:#1267ff"></i>Aktif Menerima</span><strong>58 (9,0%)</strong></div>
                        <div><span><i style="background:#94a3b8"></i>Tidak Aktif</span><strong>28 (4,4%)</strong></div>
                        <div class="total"><span>Total</span><strong>642 penerima</strong></div>
                    </div>
                </div>
            </div>
        </section>

        <section class="mbg-chain-table-grid">
            <div class="mbg-chain-card mbg-chain-table-card">
                <h2>Kategori Penerima Prioritas</h2>
                <table class="mbg-chain-table beneficiary-priority-table">
                    <thead><tr><th>No</th><th>Nama Kategori</th><th>Jumlah</th><th>Keterangan</th><th>Status</th></tr></thead>
                    <tbody>
                    @foreach ([['Balita & Anak Sekolah','210','Penerima MBG','Aktif','active'],['Keluarga Rentan','165','Prioritas Bantuan','Aktif','active'],['Lansia','84','Bantuan Sosial','Aktif','active'],['Disabilitas','40','Monitoring Khusus','Aktif','active'],['Ibu Hamil / Menyusui','53','Gizi & Kesehatan','Aktif','active']] as $i => $row)
                        <tr><td>{{ $i + 1 }}</td><td>{{ $row[0] }}</td><td>{{ $row[1] }}</td><td>{{ $row[2] }}</td><td><span class="mbg-chain-status {{ $row[4] }}">{{ $row[3] }}</span></td></tr>
                    @endforeach
                    </tbody>
                </table>
                <a href="#" class="mbg-chain-table-link">Lihat Semua Kategori <span>&gt;</span></a>
            </div>
            <div class="mbg-chain-card mbg-chain-table-card">
                <h2>Aktivitas Verifikasi / Penyaluran Terbaru</h2>
                <table class="mbg-chain-table beneficiary-activity-table">
                    <thead><tr><th>No</th><th>Tanggal</th><th>Aktivitas</th><th>Program</th><th>Status</th></tr></thead>
                    <tbody>
                    @foreach ([['22 Mei 2025','Verifikasi Data Baru','Bantuan Desa','Selesai','active'],['22 Mei 2025','Penyaluran Paket Gizi','MBG','Diproses','process'],['21 Mei 2025','Update Status Penerima','Bantuan Desa','Selesai','active'],['21 Mei 2025','Penyaluran ke Sekolah','MBG','Dalam Penyaluran','prepare'],['20 Mei 2025','Validasi KK / NIK','Bantuan Sosial','Tertunda','late']] as $i => $row)
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
    const trigger = document.getElementById('beneficiary-user-trigger');
    const dropdown = document.getElementById('beneficiary-user-dropdown');
    trigger?.addEventListener('click', (event) => { event.stopPropagation(); dropdown?.classList.toggle('show'); });
    document.addEventListener('click', () => dropdown?.classList.remove('show'));

    const beneficiaryValueLabels = {
        id: 'beneficiaryValueLabels',
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
                    const y = point.y + (datasetIndex === 0 ? -11 : 11);
                    ctx.fillText(value.toLocaleString('id-ID'), x, y);
                });
            });
            ctx.restore();
        }
    };

    const trendCtx = document.getElementById('beneficiaryTrendChart')?.getContext('2d');
    if (trendCtx) {
        new Chart(trendCtx, {
            type: 'line',
            data: {
                labels: ['Des 2024','Jan 2025','Feb 2025','Mar 2025','Apr 2025','Mei 2025'],
                datasets: [
                    { data: [380,412,452,498,560,642], borderColor: '#1267ff', pointBackgroundColor: '#1267ff', pointBorderColor: '#fff', pointBorderWidth: 2, pointRadius: 4, pointHoverRadius: 4, borderWidth: 3, tension: .32 },
                    { data: [260,298,334,382,452,518], borderColor: '#16a34a', pointBackgroundColor: '#16a34a', pointBorderColor: '#fff', pointBorderWidth: 2, pointRadius: 4, pointHoverRadius: 4, borderWidth: 3, tension: .32 }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                animation: false,
                layout: { padding: { top: 30, right: 22, left: 10, bottom: 10 } },
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
                        max: 800,
                        ticks: { stepSize: 200, color: '#062a70', padding: 4, font: { family: 'Inter', size: 10, weight: 700 }, callback: (v) => v.toLocaleString('id-ID') },
                        grid: { color: '#dce7f7', drawTicks: false },
                        border: { display: false }
                    }
                }
            },
            plugins: [beneficiaryValueLabels]
        });
    }

    const categoryCtx = document.getElementById('beneficiaryCategoryChart')?.getContext('2d');
    if (categoryCtx) {
        new Chart(categoryCtx, {
            type: 'doughnut',
            data: { datasets: [{ data: [72.9,13.7,9.0,4.4], backgroundColor: ['#16a34a','#f5b80f','#1267ff','#94a3b8'], borderColor: '#fff', borderWidth: 3 }] },
            options: { responsive: true, maintainAspectRatio: false, cutout: '58%', animation: false, plugins: { legend: { display: false } } }
        });
    }
});
</script>
</body>
</html>
