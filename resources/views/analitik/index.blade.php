<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Keuangan & Transaksi | DesaHub</title>
    <meta name="description" content="Dashboard Keuangan & Transaksi DesaHub - Kelola arus kas, transaksi pemasukan dan pengeluaran desa.">
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>
</head>
<body>
    @php
        $user = auth()->user();
        $userName = $user->name ?? 'Kepala Desa';
        $villageName = $user->village_name ?? 'Desa Sukamaju';
        $roleLabel = 'Kepala Desa';
        $userInitial = strtoupper(substr($userName, 0, 1));
    @endphp

    {{-- Sidebar Overlay (mobile) --}}
    <x-app-sidebar />

{{-- ===== TOP BAR ===== --}}
    <x-app-topbar :title="'Dashboard & Analitik'" />

{{-- ===== MAIN CONTENT ===== --}}
    <main class="main-content fin-main-content" data-main-content>
        <div class="main-inner fin-main-inner">


            {{-- ── KPI Stat Cards ── --}}
            <section class="fin-kpi-cards" style="margin-top: 24px;">
                {{-- Total Penduduk --}}
                <article class="stat-card">
                    <div class="stat-card-icon" style="background: #e0e7ff; color: #4f46e5;">
                        <svg viewBox="0 0 24 24" fill="currentColor" width="24" height="24">
                            <path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5C6.34 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="stat-card-label" style="font-weight: 700; color: var(--sidebar-bg);">Total Penduduk</p>
                        <p class="stat-card-value" style="color: var(--sidebar-bg);">3.250</p>
                        <p class="stat-card-change" style="display: flex; align-items: center; gap: 3px;">
                            <span style="color: #16a34a; display: flex; align-items: center; font-weight: 600; gap: 2px;">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="width: 14px; height: 14px;"><line x1="12" y1="19" x2="12" y2="5"/><polyline points="5 12 12 5 19 12"/></svg>
                                8,2%
                            </span>
                            <span style="color: #64748b;">dari bulan lalu</span>
                        </p>
                    </div>
                </article>

                {{-- Total UMKM --}}
                <article class="stat-card">
                    <div class="stat-card-icon" style="background: #dcfce7; color: #16a34a;">
                        <svg viewBox="0 0 24 24" fill="currentColor" width="24" height="24">
                            <path d="M4 6h16v2H4zm2 2.5v5c0 1.11-.89 2-2 2H3v2h18v-2h-1c-1.11 0-2-.89-2-2v-5l-1-2H5l-1 2zm2 5h8v-3h-8v3z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="stat-card-label" style="font-weight: 700; color: var(--sidebar-bg);">Total UMKM</p>
                        <p class="stat-card-value" style="color: var(--sidebar-bg);">128</p>
                        <p class="stat-card-change" style="display: flex; align-items: center; gap: 3px;">
                            <span style="color: #16a34a; display: flex; align-items: center; font-weight: 600; gap: 2px;">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="width: 14px; height: 14px;"><line x1="12" y1="19" x2="12" y2="5"/><polyline points="5 12 12 5 19 12"/></svg>
                                6,4%
                            </span>
                            <span style="color: #64748b;">dari bulan lalu</span>
                        </p>
                    </div>
                </article>

                {{-- Total Transaksi --}}
                <article class="stat-card">
                    <div class="stat-card-icon" style="background: #f3e8ff; color: #9333ea;">
                        <svg viewBox="0 0 24 24" fill="currentColor" width="24" height="24">
                            <path d="M3.5 18.5l6-6 4 4L22 6.92 20.59 5.5l-7.09 7.09-4-4L2 17.09z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="stat-card-label" style="font-weight: 700; color: var(--sidebar-bg);">Total Transaksi</p>
                        <p class="stat-card-value" style="color: var(--sidebar-bg);">1.284</p>
                        <p class="stat-card-change" style="display: flex; align-items: center; gap: 3px;">
                            <span style="color: #16a34a; display: flex; align-items: center; font-weight: 600; gap: 2px;">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="width: 14px; height: 14px;"><line x1="12" y1="19" x2="12" y2="5"/><polyline points="5 12 12 5 19 12"/></svg>
                                12,3%
                            </span>
                            <span style="color: #64748b;">dari bulan lalu</span>
                        </p>
                    </div>
                </article>

                {{-- Penyaluran MBG --}}
                <article class="stat-card">
                    <div class="stat-card-icon" style="background: #ffedd5; color: #ea580c;">
                        <svg viewBox="0 0 24 24" fill="currentColor" width="24" height="24">
                            <path d="M11 9H9V2H7v7H5V2H3v7c0 2.12 1.66 3.84 3.75 3.97V22h2.5v-9.03C11.34 12.84 13 11.12 13 9V2h-2v7zm5-3v8h2.5v8H21V2c-2.76 0-5 2.24-5 4z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="stat-card-label" style="font-weight: 700; color: var(--sidebar-bg);">Penyaluran MBG</p>
                        <p class="stat-card-value" style="color: var(--sidebar-bg);">2.850 <span style="font-size: 14px; font-weight: 500;">porsi</span></p>
                        <p class="stat-card-change" style="display: flex; align-items: center; gap: 3px;">
                            <span style="color: #16a34a; display: flex; align-items: center; font-weight: 600; gap: 2px;">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="width: 14px; height: 14px;"><line x1="12" y1="19" x2="12" y2="5"/><polyline points="5 12 12 5 19 12"/></svg>
                                9,7%
                            </span>
                            <span style="color: #64748b;">dari bulan lalu</span>
                        </p>
                    </div>
                </article>

                {{-- Saldo Kas Desa --}}
                <article class="stat-card">
                    <div class="stat-card-icon" style="background: #dcfce7; color: #16a34a;">
                        <svg viewBox="0 0 24 24" fill="currentColor" width="24" height="24">
                            <path d="M21 7.28V5c0-1.1-.9-2-2-2H5c-1.11 0-2 .9-2 2v14c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2v-2.28A2 2 0 0 0 22 15V9a2 2 0 0 0-1-1.72zM20 15h-4V9h4v6z"/>
                            <circle cx="18" cy="12" r="1.5" fill="#dcfce7"/>
                        </svg>
                    </div>
                    <div>
                        <p class="stat-card-label" style="font-weight: 700; color: var(--sidebar-bg);">Saldo Kas Desa</p>
                        <p class="stat-card-value" style="color: var(--sidebar-bg);">Rp 62,2 jt</p>
                        <p class="stat-card-change" style="display: flex; align-items: center; gap: 3px;">
                            <span style="color: #16a34a; display: flex; align-items: center; font-weight: 600; gap: 2px;">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="width: 14px; height: 14px;"><line x1="12" y1="19" x2="12" y2="5"/><polyline points="5 12 12 5 19 12"/></svg>
                                10,6%
                            </span>
                            <span style="color: #64748b;">dari bulan lalu</span>
                        </p>
                    </div>
                </article>
            </section>

            {{-- ── Charts Row (Tren Kinerja & Sebaran Aktivitas) ── --}}
            <section class="ana-chart-row">
                {{-- Tren Kinerja Desa --}}
                <div class="chart-card">
                    <div class="chart-header" style="justify-content: flex-start; gap: 32px;">
                        <h2 class="chart-title">Tren Kinerja Desa</h2>
                        <div class="fin-chart-legend" style="gap: 16px;">
                            <span class="fin-legend-item"><span class="fin-legend-dot" style="background: #2563eb;"></span> Transaksi (jt)</span>
                            <span class="fin-legend-item"><span class="fin-legend-dot" style="background: #16a34a;"></span> Penyaluran MBG (porsi)</span>
                            <span class="fin-legend-item"><span class="fin-legend-dot" style="background: #9333ea;"></span> Pengguna Aktif</span>
                        </div>
                    </div>
                    <div class="line-chart-container" style="height: 240px; margin-top: 10px;">
                        <canvas id="trenKinerjaChart"></canvas>
                    </div>
                </div>

                {{-- Sebaran Aktivitas per Modul --}}
                <div class="chart-card">
                    <div class="chart-header">
                        <h2 class="chart-title">Sebaran Aktivitas per Modul</h2>
                    </div>
                    <div class="fin-expense-chart-container" style="gap: 16px; margin-top: 10px;">
                        <div class="fin-donut-wrapper" style="width: 140px; height: 140px;">
                            <canvas id="sebaranModulChart"></canvas>
                        </div>
                        <div class="fin-expense-legend" style="flex: 1; padding-left: 12px;">
                            <div class="fin-expense-legend-item" style="margin-bottom: 8px;">
                                <span class="fin-expense-legend-label" style="font-size: 11px;"><span class="donut-legend-dot" style="background: #3b82f6;"></span> Penduduk</span>
                                <span class="fin-expense-legend-values" style="font-size: 11px;">25% <span class="fin-expense-pct">(721)</span></span>
                            </div>
                            <div class="fin-expense-legend-item" style="margin-bottom: 8px;">
                                <span class="fin-expense-legend-label" style="font-size: 11px;"><span class="donut-legend-dot" style="background: #22c55e;"></span> UMKM & Produk</span>
                                <span class="fin-expense-legend-values" style="font-size: 11px;">22% <span class="fin-expense-pct">(632)</span></span>
                            </div>
                            <div class="fin-expense-legend-item" style="margin-bottom: 8px;">
                                <span class="fin-expense-legend-label" style="font-size: 11px;"><span class="donut-legend-dot" style="background: #f59e0b;"></span> Pasar Desa</span>
                                <span class="fin-expense-legend-values" style="font-size: 11px;">18% <span class="fin-expense-pct">(518)</span></span>
                            </div>
                            <div class="fin-expense-legend-item" style="margin-bottom: 8px;">
                                <span class="fin-expense-legend-label" style="font-size: 11px;"><span class="donut-legend-dot" style="background: #ec4899;"></span> MBG</span>
                                <span class="fin-expense-legend-values" style="font-size: 11px;">20% <span class="fin-expense-pct">(578)</span></span>
                            </div>
                            <div class="fin-expense-legend-item" style="margin-bottom: 8px;">
                                <span class="fin-expense-legend-label" style="font-size: 11px;"><span class="donut-legend-dot" style="background: #6366f1;"></span> Keuangan & Transaksi</span>
                                <span class="fin-expense-legend-values" style="font-size: 11px;">15% <span class="fin-expense-pct">(433)</span></span>
                            </div>
                            
                            <div style="border-top: 1px solid #e5e7eb; margin-top: 12px; padding-top: 12px; display: flex; justify-content: space-between; font-size: 12px; font-weight: 600; color: var(--sidebar-bg);">
                                <span>Total Aktivitas</span>
                                <span>2.882</span>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            {{-- ── Bottom 3 Cards Row ── --}}
            <section class="ana-bottom-row">
                {{-- Ringkasan Performa Program --}}
                <div class="chart-card">
                    <div class="chart-header">
                        <h2 class="chart-title">Ringkasan Performa Program</h2>
                    </div>
                    <div style="margin-top: 16px;">
                        <div class="ana-progress-item">
                            <div class="ana-progress-header">
                                <span style="display: flex; align-items: center; gap: 8px;">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#16a34a" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                                    Penyaluran Tepat Waktu
                                </span>
                                <span>95%</span>
                            </div>
                            <div class="ana-progress-bar-bg"><div class="ana-progress-bar-fill" style="width: 95%; background: #16a34a;"></div></div>
                        </div>
                        <div class="ana-progress-item">
                            <div class="ana-progress-header">
                                <span style="display: flex; align-items: center; gap: 8px;">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#16a34a" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="7" width="20" height="14" rx="2" ry="2"/><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/></svg>
                                    Ketersediaan Pasokan
                                </span>
                                <span>92%</span>
                            </div>
                            <div class="ana-progress-bar-bg"><div class="ana-progress-bar-fill" style="width: 92%; background: #16a34a;"></div></div>
                        </div>
                        <div class="ana-progress-item">
                            <div class="ana-progress-header">
                                <span style="display: flex; align-items: center; gap: 8px;">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#ea580c" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><circle cx="12" cy="12" r="6"/><circle cx="12" cy="12" r="2"/></svg>
                                    Distribusi Tepat Sasaran
                                </span>
                                <span>90%</span>
                            </div>
                            <div class="ana-progress-bar-bg"><div class="ana-progress-bar-fill" style="width: 90%; background: #f59e0b;"></div></div>
                        </div>
                        <div class="ana-progress-item" style="margin-bottom: 0;">
                            <div class="ana-progress-header">
                                <span style="display: flex; align-items: center; gap: 8px;">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#7e22ce" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/><polyline points="3.27 6.96 12 12.01 20.73 6.96"/><line x1="12" y1="22.08" x2="12" y2="12"/></svg>
                                    Pemanfaatan Anggaran
                                </span>
                                <span>88%</span>
                            </div>
                            <div class="ana-progress-bar-bg"><div class="ana-progress-bar-fill" style="width: 88%; background: #8b5cf6;"></div></div>
                        </div>
                    </div>
                </div>

                {{-- Pengeluaran vs Anggaran --}}
                <div class="chart-card">
                    <div class="chart-header">
                        <h2 class="chart-title">Pengeluaran vs Anggaran</h2>
                    </div>
                    <div style="margin-top: 16px;">
                        <div style="display: flex; justify-content: space-between; font-size: 13px; font-weight: 500; margin-bottom: 8px;">
                            <span style="display: flex; align-items: center; gap: 8px; color: var(--text-secondary);"><span class="donut-legend-dot" style="background: #2563eb;"></span> Anggaran</span>
                            <span style="color: var(--sidebar-bg);">Rp 60.000.000</span>
                        </div>
                        <div style="display: flex; justify-content: space-between; font-size: 13px; font-weight: 500; margin-bottom: 24px;">
                            <span style="display: flex; align-items: center; gap: 8px; color: var(--text-secondary);"><span class="donut-legend-dot" style="background: #16a34a;"></span> Pengeluaran</span>
                            <span style="color: var(--sidebar-bg);">Rp 54.230.000</span>
                        </div>
                        
                        <div class="ana-progress-bar-bg" style="height: 12px; margin-bottom: 24px;">
                            <div class="ana-progress-bar-fill" style="width: 90%; background: #2563eb;"></div>
                        </div>
                        
                        <div style="display: flex; justify-content: space-between; align-items: flex-end;">
                            <div>
                                <div style="font-size: 32px; font-weight: 700; color: #16a34a; line-height: 1;">90%</div>
                                <div style="font-size: 12px; color: var(--text-secondary); margin-top: 4px;">Dari Total Anggaran</div>
                            </div>
                            <div style="font-size: 14px; font-weight: 600; color: var(--sidebar-bg);">
                                Rp 60.000.000
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Insight Strategis --}}
                <div class="chart-card" style="display: flex; flex-direction: column;">
                    <div class="chart-header">
                        <h2 class="chart-title">Insight Strategis</h2>
                    </div>
                    <div class="ana-insight-grid" style="margin-top: 12px;">
                        <div class="ana-insight-card">
                            <div class="ana-insight-title">Cakupan Program</div>
                            <div>
                                <div class="ana-insight-val">85%</div>
                                <div style="display: flex; align-items: center; gap: 4px; font-size: 11px; color: #16a34a; font-weight: 600;">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="width: 12px; height: 12px;"><line x1="12" y1="19" x2="12" y2="5"/><polyline points="5 12 12 5 19 12"/></svg>
                                    11% <span style="color: #64748b; font-weight: 400;">dari bulan lalu</span>
                                </div>
                            </div>
                        </div>
                        <div class="ana-insight-card">
                            <div class="ana-insight-title">Ketepatan Penyaluran</div>
                            <div>
                                <div class="ana-insight-val">95%</div>
                                <div style="display: flex; align-items: center; gap: 4px; font-size: 11px; color: #16a34a; font-weight: 600;">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="width: 12px; height: 12px;"><line x1="12" y1="19" x2="12" y2="5"/><polyline points="5 12 12 5 19 12"/></svg>
                                    6% <span style="color: #64748b; font-weight: 400;">dari bulan lalu</span>
                                </div>
                            </div>
                        </div>
                        <div class="ana-insight-card">
                            <div class="ana-insight-title">Kepuasan Penerima</div>
                            <div>
                                <div class="ana-insight-val">4,6 <span style="font-size: 14px; font-weight: 500; color: var(--text-secondary);">/ 5</span></div>
                                <div style="display: flex; align-items: center; gap: 4px; font-size: 11px; color: #16a34a; font-weight: 600;">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="width: 12px; height: 12px;"><line x1="12" y1="19" x2="12" y2="5"/><polyline points="5 12 12 5 19 12"/></svg>
                                    0,3 <span style="color: #64748b; font-weight: 400;">dari bulan lalu</span>
                                </div>
                            </div>
                        </div>
                        <div class="ana-insight-card">
                            <div class="ana-insight-title">Potensi Terserap</div>
                            <div>
                                <div class="ana-insight-val">78%</div>
                                <div style="display: flex; align-items: center; gap: 4px; font-size: 11px; color: #16a34a; font-weight: 600;">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="width: 12px; height: 12px;"><line x1="12" y1="19" x2="12" y2="5"/><polyline points="5 12 12 5 19 12"/></svg>
                                    6% <span style="color: #64748b; font-weight: 400;">dari bulan lalu</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            {{-- ── Bottom Tables Row ── --}}
            <section class="fin-tables-row">
                <div class="fin-table-card" style="grid-column: 1 / -1;">
                    <div class="bottom-card-header">
                        <h3 class="bottom-card-title">Peringatan & Notifikasi</h3>
                        <a href="#" class="fin-table-link" style="margin-top: 0;">Lihat Semua Notifikasi <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width: 14px; height: 14px;"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg></a>
                    </div>
                    <div class="fin-table-wrapper">
                        <table class="fin-table">
                            <thead>
                                <tr>
                                    <th style="width: 50px;">No</th>
                                    <th style="width: 140px;">Waktu</th>
                                    <th style="width: 120px;">Kategori</th>
                                    <th>Pesan</th>
                                    <th style="width: 180px;">Modul</th>
                                    <th style="width: 140px;">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>1</td>
                                    <td class="fin-td-date">21 Mei 2025 09:30</td>
                                    <td><span class="fin-badge fin-badge-warning" style="background: #ffedd5; color: #ea580c;">Stok</span></td>
                                    <td class="fin-td-name" style="font-weight: 500;">Stok beras di Gudang Desa Sukamaju menipis.</td>
                                    <td style="color: var(--text-secondary);">Gudang & Logistik</td>
                                    <td><span class="fin-badge fin-badge-warning" style="background: #ffedd5; color: #ea580c;">Perlu Tindakan</span></td>
                                </tr>
                                <tr>
                                    <td>2</td>
                                    <td class="fin-td-date">21 Mei 2025 08:15</td>
                                    <td><span class="fin-badge fin-badge-warning" style="background: #fef9c3; color: #ca8a04;">Penerima</span></td>
                                    <td class="fin-td-name" style="font-weight: 500;">Verifikasi data 24 penerima manfaat belum selesai.</td>
                                    <td style="color: var(--text-secondary);">Penerima Manfaat</td>
                                    <td><span class="fin-badge fin-badge-warning" style="background: #ffedd5; color: #ea580c;">Perlu Tindakan</span></td>
                                </tr>
                                <tr>
                                    <td>3</td>
                                    <td class="fin-td-date">20 Mei 2025 16:45</td>
                                    <td><span class="fin-badge fin-badge-danger" style="background: #fee2e2; color: #dc2626;">Anggaran</span></td>
                                    <td class="fin-td-name" style="font-weight: 500;">Pengeluaran mendekati batas 90% dari total anggaran.</td>
                                    <td style="color: var(--text-secondary);">Keuangan & Transaksi</td>
                                    <td><span class="fin-badge fin-badge-warning" style="background: #fef9c3; color: #ca8a04;">Waspada</span></td>
                                </tr>
                                <tr>
                                    <td>4</td>
                                    <td class="fin-td-date">20 Mei 2025 14:20</td>
                                    <td><span class="fin-badge fin-badge-info" style="background: #dbeafe; color: #2563eb;">Transaksi</span></td>
                                    <td class="fin-td-name" style="font-weight: 500;">Volume transaksi meningkat 18% dibanding minggu lalu.</td>
                                    <td style="color: var(--text-secondary);">Keuangan & Transaksi</td>
                                    <td><span class="fin-badge fin-badge-info" style="background: #dbeafe; color: #2563eb;">Informasi</span></td>
                                </tr>
                                <tr>
                                    <td>5</td>
                                    <td class="fin-td-date">20 Mei 2025 10:05</td>
                                    <td><span class="fin-badge" style="background: #f3e8ff; color: #9333ea;">Pasar</span></td>
                                    <td class="fin-td-name" style="font-weight: 500;">Permintaan komoditas sayur naik signifikan di Pasar Desa.</td>
                                    <td style="color: var(--text-secondary);">Pasar Desa</td>
                                    <td><span class="fin-badge fin-badge-info" style="background: #dbeafe; color: #2563eb;">Informasi</span></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>
            <x-app-footer />

        </div>
    </main>

    {{-- ===== CHART & INTERACTION SCRIPTS ===== --}}
    <script>
    document.addEventListener('DOMContentLoaded', () => {
        // ── Inline datalabels plugin ──
        const lineDatalabels = {
            id: 'lineDatalabels',
            afterDatasetsDraw(chart) {
                const { ctx } = chart;
                chart.data.datasets.forEach((dataset, i) => {
                    const meta = chart.getDatasetMeta(i);
                    meta.data.forEach((point, index) => {
                        const value = dataset.data[index];
                        
                        let displayValue = value.toString();
                        if (value >= 1000) {
                            displayValue = value.toLocaleString('id-ID');
                        }

                        ctx.save();
                        ctx.font = '600 10px Inter, sans-serif';
                        ctx.fillStyle = dataset.borderColor;
                        ctx.textAlign = 'center';
                        ctx.textBaseline = 'bottom';
                        ctx.fillText(displayValue, point.x, point.y - 8);
                        ctx.restore();
                    });
                });
            }
        };

        // ── Tren Kinerja Desa (Line Chart) ──
        const trenCtx = document.getElementById('trenKinerjaChart')?.getContext('2d');
        if (trenCtx) {
            new Chart(trenCtx, {
                type: 'line',
                data: {
                    labels: ['Des 2024', 'Jan 2025', 'Feb 2025', 'Mar 2025', 'Apr 2025', 'Mei 2025'],
                    datasets: [
                        {
                            label: 'Penyaluran MBG (porsi)',
                            data: [1800, 2050, 2200, 2450, 2650, 2850],
                            borderColor: '#16a34a',
                            borderWidth: 2,
                            tension: 0,
                            fill: false,
                            pointRadius: 4,
                            pointBackgroundColor: '#16a34a',
                            pointBorderColor: '#ffffff',
                            pointBorderWidth: 1.5,
                        },
                        {
                            label: 'Transaksi (jt)',
                            data: [920, 980, 1050, 1120, 1210, 1284],
                            borderColor: '#2563eb',
                            borderWidth: 2,
                            tension: 0,
                            fill: false,
                            pointRadius: 4,
                            pointBackgroundColor: '#2563eb',
                            pointBorderColor: '#ffffff',
                            pointBorderWidth: 1.5,
                        },
                        {
                            label: 'Pengguna Aktif',
                            data: [420, 465, 510, 560, 605, 660],
                            borderColor: '#9333ea',
                            borderWidth: 2,
                            tension: 0,
                            fill: false,
                            pointRadius: 4,
                            pointBackgroundColor: '#9333ea',
                            pointBorderColor: '#ffffff',
                            pointBorderWidth: 1.5,
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    layout: {
                        padding: { top: 20 }
                    },
                    interaction: {
                        intersect: false,
                        mode: 'index',
                    },
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: '#1a1d2e',
                            titleFont: { family: 'Inter', size: 12 },
                            bodyFont: { family: 'Inter', size: 12 },
                            padding: 10,
                            cornerRadius: 8,
                        }
                    },
                    scales: {
                        x: {
                            grid: { display: false },
                            ticks: { font: { family: 'Inter', size: 11 }, color: '#64748b' },
                            border: { display: false }
                        },
                        y: {
                            grid: { color: '#f1f5f9' },
                            ticks: {
                                font: { family: 'Inter', size: 11 },
                                color: '#64748b',
                                stepSize: 500,
                            },
                            border: { display: false },
                            min: 0,
                            max: 3500,
                        }
                    }
                },
                plugins: [lineDatalabels]
            });
        }

        // ── Sebaran Aktivitas per Modul (Doughnut Chart) ──
        const modulCtx = document.getElementById('sebaranModulChart')?.getContext('2d');
        if (modulCtx) {
            new Chart(modulCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Penduduk', 'UMKM & Produk', 'Pasar Desa', 'MBG', 'Keuangan & Transaksi'],
                    datasets: [{
                        data: [721, 632, 518, 578, 433],
                        backgroundColor: ['#3b82f6', '#22c55e', '#f59e0b', '#ec4899', '#6366f1'],
                        borderWidth: 2,
                        borderColor: '#ffffff',
                        hoverOffset: 4,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    cutout: '55%',
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: '#1a1d2e',
                            titleFont: { family: 'Inter', size: 12 },
                            bodyFont: { family: 'Inter', size: 12 },
                            padding: 10,
                            cornerRadius: 8,
                        }
                    }
                }
            });
        }

        // ── User dropdown toggle ──
        const trigger = document.getElementById('user-dropdown-trigger');
        const dropdown = document.getElementById('user-dropdown');
        trigger?.addEventListener('click', (e) => {
            e.stopPropagation();
            dropdown?.classList.toggle('show');
        });
        document.addEventListener('click', () => dropdown?.classList.remove('show'));
    });
    </script>
</body>
</html>
