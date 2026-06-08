<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Kopdes/KDMP | DesaHub</title>
    <meta name="description" content="Dashboard Utama DesaHub - Ekosistem Ekonomi Desa. Kelola data dan pantau perkembangan ekonomi desa secara real-time.">
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.2.0"></script>
    <link href="https://cdn.jsdelivr.net/npm/@flaticon/flaticon-uicons@3.3.1/css/all/all.min.css" rel="stylesheet">
</head>
<body>
    @php
        $user = auth()->user();
        $userName = $user->name ?? 'Kepala Desa';
        $villageName = $user->village_name ?? 'Desa Sukamaju';
        $roleLabel = 'Kepala Desa';
        $userInitial = strtoupper(substr($userName, 0, 1));
        $hour = (int) now()->format('H');
        $greeting = match(true) {
            $hour < 11 => 'Selamat pagi',
            $hour < 15 => 'Selamat siang',
            $hour < 18 => 'Selamat sore',
            default => 'Selamat malam',
        };
    @endphp

    {{-- Sidebar Overlay (mobile) --}}
    <x-app-sidebar />

{{-- ===== TOP BAR ===== --}}
    <x-app-topbar title="Kopdes/KDMP" />

{{-- ===== MAIN CONTENT ===== --}}
<main class="main-content fin-main-content" data-main-content>
        <div class="main-inner fin-main-inner">
            {{-- Kopdes Header --}}
            <div class="greeting-header">
                <div>
                    <div style="display:flex; align-items:center; gap:12px;">
                        <h1>Koperasi Desa Makmur Sejahtera</h1>
                        <span style="background: #ecfdf5; color: #16a34a; font-size:11px; font-weight:600; padding:2px 8px; border-radius:12px;">Aktif</span>
                    </div>
                    <p>Kopdes/KDMP &bull; Desa Sukamaju</p>
                </div>
                <button class="export-btn" style="background:#2d5cf6; color:white; border-radius:8px; padding:8px 14px; border:none; font-weight:600; display:flex; align-items:center; gap:8px;">
                    <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                    Lihat Profil Koperasi
                </button>
            </div>

            {{-- Nav Tabs --}}
            <div class="pendataan-tabs" style="margin-bottom: 12px; margin-top: 12px;">
                <a href="#" class="pendataan-tab-item active">Ringkasan</a>
                <a href="#" class="pendataan-tab-item">Anggota</a>
                <a href="#" class="pendataan-tab-item">Simpanan</a>
                <a href="#" class="pendataan-tab-item">Pinjaman</a>
                <a href="#" class="pendataan-tab-item">Unit Usaha</a>
                <a href="#" class="pendataan-tab-item">Transaksi</a>
                <a href="#" class="pendataan-tab-item">Laporan Keuangan</a>
                <a href="#" class="pendataan-tab-item">Dokumen</a>
            </div>
            <section class="stat-cards" style="display: grid; grid-template-columns: repeat(5, 1fr); gap: 16px; margin-bottom: 24px;">
                <article class="stat-card" style="display:flex; gap:16px; align-items:center; background:white; border:1px solid #e2e8f0; border-radius:14px; padding:12px;">
                    <div style="width:48px; height:48px; min-width:48px; border-radius:12px; display:flex; align-items:center; justify-content:center; background: #eff6ff; color: #3b82f6;">
                        <svg viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                    </div>
                    <div style="flex:1;">
                        <p style="font-size:12px; font-weight:600; color:#475569; margin-bottom:4px;">Total Anggota</p>
                        <p style="font-size:20px; font-weight:700; color:#1e293b; margin-bottom:4px;">356</p>
                        <p style="display: flex; align-items: center; gap: 4px; font-size:11px;">
                            <span style="color: #16a34a; display: flex; align-items: center; font-weight: 600;">
                                <svg viewBox="0 0 24 24" width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m7 17 10-10"/><path d="M17 17V7H7"/></svg>
                                +8
                            </span>
                            <span style="color: #64748b;">dari bulan lalu</span>
                        </p>
                    </div>
                </article>

                <article class="stat-card" style="display:flex; gap:16px; align-items:center; background:white; border:1px solid #e2e8f0; border-radius:14px; padding:12px;">
                    <div style="width:48px; height:48px; min-width:48px; border-radius:12px; display:flex; align-items:center; justify-content:center; background: #ecfdf5; color: #10b981;">
                        <svg viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12V7H5a2 2 0 0 1 0-4h14v4"/><path d="M3 5v14a2 2 0 0 0 2 2h16v-5"/><path d="M18 12a2 2 0 0 0 0 4h4v-4Z"/></svg>
                    </div>
                    <div style="flex:1;">
                        <p style="font-size:12px; font-weight:600; color:#475569; margin-bottom:4px;">Total Simpanan</p>
                        <p style="font-size:20px; font-weight:700; color:#1e293b; margin-bottom:4px;">Rp 487,6 jt</p>
                        <p style="display: flex; align-items: center; gap: 4px; font-size:11px;">
                            <span style="color: #16a34a; display: flex; align-items: center; font-weight: 600;">
                                <svg viewBox="0 0 24 24" width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m7 17 10-10"/><path d="M17 17V7H7"/></svg>
                                +12,4%
                            </span>
                            <span style="color: #64748b;">dari bulan lalu</span>
                        </p>
                    </div>
                </article>

                <article class="stat-card" style="display:flex; gap:16px; align-items:center; background:white; border:1px solid #e2e8f0; border-radius:14px; padding:12px;">
                    <div style="width:48px; height:48px; min-width:48px; border-radius:12px; display:flex; align-items:center; justify-content:center; background: #fef2f2; color: #ef4444;">
                        <i class="fi fi-rr-hand-holding-heart" style="font-size: 24px; line-height: 1;"></i>
                    </div>
                    <div style="flex:1;">
                        <p style="font-size:12px; font-weight:600; color:#475569; margin-bottom:4px;">Total Pinjaman</p>
                        <p style="font-size:20px; font-weight:700; color:#1e293b; margin-bottom:4px;">Rp 325,0 jt</p>
                        <p style="display: flex; align-items: center; gap: 4px; font-size:11px;">
                            <span style="color: #ef4444; display: flex; align-items: center; font-weight: 600;">
                                <svg viewBox="0 0 24 24" width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m7 7 10 10"/><path d="M17 7v10H7"/></svg>
                                +9,8%
                            </span>
                            <span style="color: #64748b;">dari bulan lalu</span>
                        </p>
                    </div>
                </article>

                <article class="stat-card" style="display:flex; gap:16px; align-items:center; background:white; border:1px solid #e2e8f0; border-radius:14px; padding:12px;">
                    <div style="width:48px; height:48px; min-width:48px; border-radius:12px; display:flex; align-items:center; justify-content:center; background: #fffbeb; color: #f59e0b;">
                        <svg viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 3v18h18"/><path d="M18 17V9"/><path d="M13 17V5"/><path d="M8 17v-3"/><polyline points="8 10 13 4 18 12"/></svg>
                    </div>
                    <div style="flex:1;">
                        <p style="font-size:12px; font-weight:600; color:#475569; margin-bottom:4px;">SHU (YTD)</p>
                        <p style="font-size:20px; font-weight:700; color:#1e293b; margin-bottom:4px;">Rp 48,2 jt</p>
                        <p style="display: flex; align-items: center; gap: 4px; font-size:11px;">
                            <span style="color: #16a34a; display: flex; align-items: center; font-weight: 600;">
                                <svg viewBox="0 0 24 24" width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m7 17 10-10"/><path d="M17 17V7H7"/></svg>
                                +15,2%
                            </span>
                            <span style="color: #64748b;">dari tahun lalu</span>
                        </p>
                    </div>
                </article>

                <article class="stat-card" style="display:flex; gap:16px; align-items:center; background:white; border:1px solid #e2e8f0; border-radius:14px; padding:12px;">
                    <div style="width:48px; height:48px; min-width:48px; border-radius:12px; display:flex; align-items:center; justify-content:center; background: #faf5ff; color: #a855f7;">
                        <svg viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="20" height="14" x="2" y="7" rx="2" ry="2"/><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/></svg>
                    </div>
                    <div style="flex:1;">
                        <p style="font-size:12px; font-weight:600; color:#475569; margin-bottom:4px;">Unit Usaha Aktif</p>
                        <p style="font-size:20px; font-weight:700; color:#1e293b; margin-bottom:4px;">5</p>
                        <a href="#" style="font-size:11px; color:#1e293b; text-decoration:none; font-weight:600; display:flex; align-items:center; justify-content:space-between; width:100%; margin-top:8px;">
                            Lihat detail unit usaha
                            <svg viewBox="0 0 24 24" width="12" height="12" fill="none" stroke="#2d5cf6" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></svg>
                        </a>
                    </div>
                </article>
            </section>{{-- Charts Row --}}
                        <section style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 8px;">
                {{-- Line Chart --}}
                <div class="chart-card" style="display:flex; flex-direction:column; padding:12px; background:white; border:1px solid #e2e8f0; border-radius:14px;">
                    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:16px;">
                        <h2 style="font-size:14px; font-weight:600; color:#1e293b; margin:0;">Perkembangan Simpanan & Pinjaman</h2>
                        <button style="display:flex; align-items:center; gap:4px; background:white; border:1px solid #e2e8f0; padding:6px 12px; border-radius:8px; font-size:12px; font-weight:500; color:#475569; cursor:pointer;">
                            6 Bulan Terakhir
                            <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><polyline points="6 9 12 15 18 9"/></svg>
                        </button>
                    </div>
                    <div style="display:flex; gap:16px; margin-bottom:16px; font-size:11px; font-weight:500; color:#475569;">
                        <div style="display:flex; align-items:center; gap:6px;">
                            <span style="display:inline-block; width:10px; height:10px; border-radius:50%; background:#10b981;"></span>
                            Total Simpanan (Rp jt)
                        </div>
                        <div style="display:flex; align-items:center; gap:6px;">
                            <span style="display:inline-block; width:10px; height:10px; border-radius:50%; background:#3b82f6;"></span>
                            Total Pinjaman (Rp jt)
                        </div>
                    </div>
                    <div style="flex:1; min-height:80px; position:relative;">
                        <canvas id="lineChart"></canvas>
                    </div>
                </div>

                {{-- Donut Chart --}}
                <div class="chart-card" style="display:flex; flex-direction:column; padding:12px; background:white; border:1px solid #e2e8f0; border-radius:14px;">
                    <div style="margin-bottom:16px;">
                        <h2 style="font-size:14px; font-weight:600; color:#1e293b; margin:0;">Komposisi Simpanan</h2>
                    </div>
                    <div style="display:flex; flex-direction:row; gap:48px; align-items:center; padding:16px 24px;">
                        <div style="width:160px; height:160px; position:relative;">
                            <canvas id="donutChart"></canvas>
                        </div>
                        <div style="display:flex; flex-direction:column; gap:16px; font-size:12px; color:#475569; font-weight:500; width: 200px;">
                            <div style="display:flex; justify-content:space-between; align-items:center;">
                                <div style="display:flex; align-items:center; gap:6px;"><span style="display:inline-block; width:8px; height:8px; border-radius:50%; background:#3b82f6;"></span> Simpanan Pokok</div>
                                <span style="color:#1e293b; font-weight:600;">25%</span>
                            </div>
                            <div style="display:flex; justify-content:space-between; align-items:center;">
                                <div style="display:flex; align-items:center; gap:6px;"><span style="display:inline-block; width:8px; height:8px; border-radius:50%; background:#10b981;"></span> Simpanan Wajib</div>
                                <span style="color:#1e293b; font-weight:600;">40%</span>
                            </div>
                            <div style="display:flex; justify-content:space-between; align-items:center;">
                                <div style="display:flex; align-items:center; gap:6px;"><span style="display:inline-block; width:8px; height:8px; border-radius:50%; background:#f59e0b;"></span> Simpanan Sukarela</div>
                                <span style="color:#1e293b; font-weight:600;">20%</span>
                            </div>
                            <div style="display:flex; justify-content:space-between; align-items:center;">
                                <div style="display:flex; align-items:center; gap:6px;"><span style="display:inline-block; width:8px; height:8px; border-radius:50%; background:#a855f7;"></span> Simpanan Berjangka</div>
                                <span style="color:#1e293b; font-weight:600;">10%</span>
                            </div>
                            <div style="display:flex; justify-content:space-between; align-items:center;">
                                <div style="display:flex; align-items:center; gap:6px;"><span style="display:inline-block; width:8px; height:8px; border-radius:50%; background:#94a3b8;"></span> Lainnya</div>
                                <span style="color:#1e293b; font-weight:600;">5%</span>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            {{-- Tables Row --}}
            <section style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 8px;">
                {{-- Unit Usaha Koperasi --}}
                <div class="chart-card" style="display:flex; flex-direction:column; background:white; border:1px solid #e2e8f0; border-radius:14px; padding:12px;">
                    <div class="chart-header" style="margin-bottom:16px;">
                        <h2 class="chart-title" style="font-size:14px; font-weight:600; color:#1e293b; margin:0;">Unit Usaha Koperasi</h2>
                    </div>
                    <table style="width: 100%; border-collapse: separate; border-spacing: 0; text-align: left;">
                        <thead style="background: #f8fafc;">
                            <tr>
                                <th style="padding: 10px 12px; font-weight: 600; color: #334155;  font-size: 11px; border-bottom: 1px solid #e2e8f0; border-top-left-radius: 6px; border-bottom-left-radius: 6px;">Nama Unit Usaha</th>
                                <th style="padding: 6px 8px; font-weight: 600; color: #334155;  font-size: 11px; border-bottom: 1px solid #e2e8f0;">Jenis Usaha</th>
                                <th style="padding: 6px 8px; font-weight: 600; color: #334155;  font-size: 11px; border-bottom: 1px solid #e2e8f0;">Pendapatan (Bulan Ini)</th>
                                <th style="padding: 10px 12px; font-weight: 600; color: #334155;  font-size: 11px; border-bottom: 1px solid #e2e8f0; border-top-right-radius: 6px; border-bottom-right-radius: 6px;">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td style="padding: 6px 8px; border-bottom: 1px solid #f1f5f9; color: #475569; font-weight: 500; font-size: 11px; border-bottom: 1px solid #e2e8f0;"><div style="display:flex; align-items:center; gap:8px;"><svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color:#64748b;"><rect width="20" height="14" x="2" y="7" rx="2" ry="2"/><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/></svg><span style="font-weight: 500; color:#1e293b;">Toko Sembako</span></div></td>
                                <td style="padding: 6px 8px; border-bottom: 1px solid #f1f5f9; color: #475569; font-weight: 500; font-size: 11px; border-bottom: 1px solid #e2e8f0;">Perdagangan</td>
                                <td style="padding: 6px 8px; border-bottom: 1px solid #f1f5f9; color: #475569; font-weight: 500; font-size: 11px; border-bottom: 1px solid #e2e8f0;"><span style="font-weight: 600; color: #1e293b;">Rp 28,5 jt</span></td>
                                <td style="padding: 6px 8px; border-bottom: 1px solid #f1f5f9;"><span style="color:#16a34a; background:#ecfdf5; padding:4px 8px; border-radius:6px; font-weight:600; font-size: 11px; border-bottom: 1px solid #e2e8f0;">Aktif</span></td>
                            </tr>
                            <tr>
                                <td style="padding: 6px 8px; border-bottom: 1px solid #f1f5f9; color: #475569; font-weight: 500; font-size: 11px; border-bottom: 1px solid #e2e8f0;"><div style="display:flex; align-items:center; gap:8px;"><svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color:#64748b;"><rect width="20" height="14" x="2" y="7" rx="2" ry="2"/><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/></svg><span style="font-weight: 500; color:#1e293b;">Unit Pangan</span></div></td>
                                <td style="padding: 6px 8px; border-bottom: 1px solid #f1f5f9; color: #475569; font-weight: 500; font-size: 11px; border-bottom: 1px solid #e2e8f0;">Pengolahan</td>
                                <td style="padding: 6px 8px; border-bottom: 1px solid #f1f5f9; color: #475569; font-weight: 500; font-size: 11px; border-bottom: 1px solid #e2e8f0;"><span style="font-weight: 600; color: #1e293b;">Rp 18,7 jt</span></td>
                                <td style="padding: 6px 8px; border-bottom: 1px solid #f1f5f9;"><span style="color:#16a34a; background:#ecfdf5; padding:4px 8px; border-radius:6px; font-weight:600; font-size: 11px; border-bottom: 1px solid #e2e8f0;">Aktif</span></td>
                            </tr>
                            <tr>
                                <td style="padding: 6px 8px; border-bottom: 1px solid #f1f5f9; color: #475569; font-weight: 500; font-size: 11px; border-bottom: 1px solid #e2e8f0;"><div style="display:flex; align-items:center; gap:8px;"><svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color:#64748b;"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg><span style="font-weight: 500; color:#1e293b;">Jasa Penggilingan</span></div></td>
                                <td style="padding: 6px 8px; border-bottom: 1px solid #f1f5f9; color: #475569; font-weight: 500; font-size: 11px; border-bottom: 1px solid #e2e8f0;">Jasa</td>
                                <td style="padding: 6px 8px; border-bottom: 1px solid #f1f5f9; color: #475569; font-weight: 500; font-size: 11px; border-bottom: 1px solid #e2e8f0;"><span style="font-weight: 600; color: #1e293b;">Rp 9,3 jt</span></td>
                                <td style="padding: 6px 8px; border-bottom: 1px solid #f1f5f9;"><span style="color:#16a34a; background:#ecfdf5; padding:4px 8px; border-radius:6px; font-weight:600; font-size: 11px; border-bottom: 1px solid #e2e8f0;">Aktif</span></td>
                            </tr>
                            <tr>
                                <td style="padding: 6px 8px; border-bottom: 1px solid #f1f5f9; color: #475569; font-weight: 500; font-size: 11px; border-bottom: 1px solid #e2e8f0;"><div style="display:flex; align-items:center; gap:8px;"><svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color:#64748b;"><rect width="18" height="11" x="3" y="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg><span style="font-weight: 500; color:#1e293b;">Simpan Pinjam</span></div></td>
                                <td style="padding: 6px 8px; border-bottom: 1px solid #f1f5f9; color: #475569; font-weight: 500; font-size: 11px; border-bottom: 1px solid #e2e8f0;">Keuangan</td>
                                <td style="padding: 6px 8px; border-bottom: 1px solid #f1f5f9; color: #475569; font-weight: 500; font-size: 11px; border-bottom: 1px solid #e2e8f0;"><span style="font-weight: 600; color: #1e293b;">Rp 12,0 jt</span></td>
                                <td style="padding: 6px 8px; border-bottom: 1px solid #f1f5f9;"><span style="color:#16a34a; background:#ecfdf5; padding:4px 8px; border-radius:6px; font-weight:600; font-size: 11px; border-bottom: 1px solid #e2e8f0;">Aktif</span></td>
                            </tr>
                            <tr>
                                <td style="padding: 6px 8px; border-bottom: 1px solid #f1f5f9; color: #475569; font-weight: 500; font-size: 11px; border-bottom: 1px solid #e2e8f0;"><div style="display:flex; align-items:center; gap:8px;"><svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color:#64748b;"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg><span style="font-weight: 500; color:#1e293b;">Agen BRILink</span></div></td>
                                <td style="padding: 6px 8px; border-bottom: 1px solid #f1f5f9; color: #475569; font-weight: 500; font-size: 11px; border-bottom: 1px solid #e2e8f0;">Layanan</td>
                                <td style="padding: 6px 8px; border-bottom: 1px solid #f1f5f9; color: #475569; font-weight: 500; font-size: 11px; border-bottom: 1px solid #e2e8f0;"><span style="font-weight: 600; color: #1e293b;">Rp 4,8 jt</span></td>
                                <td style="padding: 6px 8px; border-bottom: 1px solid #f1f5f9;"><span style="color:#16a34a; background:#ecfdf5; padding:4px 8px; border-radius:6px; font-weight:600; font-size: 11px; border-bottom: 1px solid #e2e8f0;">Aktif</span></td>
                            </tr>
                        </tbody>
                    </table>
                    <div style="margin-top:auto; padding-top:16px; text-align:center; display:flex; justify-content:center;">
                        <a href="#" style="font-size:13px; font-weight:600; color:#2d5cf6; text-decoration:none; display:flex; align-items:center; gap:4px;">Kelola Unit Usaha <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></svg></a>
                    </div>
                </div>

                {{-- Pinjaman Terbaru --}}
                <div class="chart-card" style="display:flex; flex-direction:column; background:white; border:1px solid #e2e8f0; border-radius:14px; padding:12px;">
                    <div class="chart-header" style="margin-bottom:16px;">
                        <h2 class="chart-title" style="font-size:14px; font-weight:600; color:#1e293b; margin:0;">Pinjaman Terbaru</h2>
                    </div>
                    <table style="width: 100%; border-collapse: separate; border-spacing: 0; text-align: left;">
                        <thead style="background: #f8fafc;">
                            <tr>
                                <th style="padding: 10px 12px; font-weight: 600; color: #334155;  font-size: 11px; border-bottom: 1px solid #e2e8f0; border-top-left-radius: 6px; border-bottom-left-radius: 6px;">Nama Anggota</th>
                                <th style="padding: 6px 8px; font-weight: 600; color: #334155;  font-size: 11px; border-bottom: 1px solid #e2e8f0;">Tujuan Pinjaman</th>
                                <th style="padding: 6px 8px; font-weight: 600; color: #334155;  font-size: 11px; border-bottom: 1px solid #e2e8f0;">Jumlah</th>
                                <th style="padding: 10px 12px; font-weight: 600; color: #334155;  font-size: 11px; border-bottom: 1px solid #e2e8f0; border-top-right-radius: 6px; border-bottom-right-radius: 6px;">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td style="padding: 6px 8px; border-bottom: 1px solid #f1f5f9; color: #475569; font-weight: 500; font-size: 11px; border-bottom: 1px solid #e2e8f0;"><div style="display:flex; align-items:center; gap:8px;"><div style="width:24px; height:24px; border-radius:50%; background:#f1f5f9; display:flex; align-items:center; justify-content:center; font-size:11px; font-weight:600; color:#475569;">SA</div><span style="font-weight: 500; color:#1e293b;">Siti Aminah</span></div></td>
                                <td style="padding: 6px 8px; border-bottom: 1px solid #f1f5f9; color: #475569; font-weight: 500; font-size: 11px; border-bottom: 1px solid #e2e8f0;">Modal Usaha</td>
                                <td style="padding: 6px 8px; border-bottom: 1px solid #f1f5f9; color: #475569; font-weight: 500; font-size: 11px; border-bottom: 1px solid #e2e8f0;"><span style="font-weight: 600; color: #1e293b;">Rp 5.000.000</span></td>
                                <td style="padding: 6px 8px; border-bottom: 1px solid #f1f5f9;"><span style="background: #dcfce7; color: #16a34a; padding: 4px 10px; border-radius: 6px; font-weight: 600; font-size: 11px; border-bottom: 1px solid #e2e8f0;">Disetujui</span></td>
                            </tr>
                            <tr>
                                <td style="padding: 6px 8px; border-bottom: 1px solid #f1f5f9; color: #475569; font-weight: 500; font-size: 11px; border-bottom: 1px solid #e2e8f0;"><div style="display:flex; align-items:center; gap:8px;"><div style="width:24px; height:24px; border-radius:50%; background:#f1f5f9; display:flex; align-items:center; justify-content:center; font-size:11px; font-weight:600; color:#475569;">AS</div><span style="font-weight: 500; color:#1e293b;">Andi Setiawan</span></div></td>
                                <td style="padding: 6px 8px; border-bottom: 1px solid #f1f5f9; color: #475569; font-weight: 500; font-size: 11px; border-bottom: 1px solid #e2e8f0;">Pendidikan</td>
                                <td style="padding: 6px 8px; border-bottom: 1px solid #f1f5f9; color: #475569; font-weight: 500; font-size: 11px; border-bottom: 1px solid #e2e8f0;"><span style="font-weight: 600; color: #1e293b;">Rp 3.000.000</span></td>
                                <td style="padding: 6px 8px; border-bottom: 1px solid #f1f5f9;"><span style="background: #dcfce7; color: #16a34a; padding: 4px 10px; border-radius: 6px; font-weight: 600; font-size: 11px; border-bottom: 1px solid #e2e8f0;">Disetujui</span></td>
                            </tr>
                            <tr>
                                <td style="padding: 6px 8px; border-bottom: 1px solid #f1f5f9; color: #475569; font-weight: 500; font-size: 11px; border-bottom: 1px solid #e2e8f0;"><div style="display:flex; align-items:center; gap:8px;"><div style="width:24px; height:24px; border-radius:50%; background:#f1f5f9; display:flex; align-items:center; justify-content:center; font-size:11px; font-weight:600; color:#475569;">BS</div><span style="font-weight: 500; color:#1e293b;">Budi Santoso</span></div></td>
                                <td style="padding: 6px 8px; border-bottom: 1px solid #f1f5f9; color: #475569; font-weight: 500; font-size: 11px; border-bottom: 1px solid #e2e8f0;">Modal Usaha</td>
                                <td style="padding: 6px 8px; border-bottom: 1px solid #f1f5f9; color: #475569; font-weight: 500; font-size: 11px; border-bottom: 1px solid #e2e8f0;"><span style="font-weight: 600; color: #1e293b;">Rp 8.000.000</span></td>
                                <td style="padding: 6px 8px; border-bottom: 1px solid #f1f5f9;"><span style="background: #fffbeb; color: #f59e0b; padding: 4px 10px; border-radius: 6px; font-weight: 600; font-size: 11px; border-bottom: 1px solid #e2e8f0;">Proses</span></td>
                            </tr>
                            <tr>
                                <td style="padding: 6px 8px; border-bottom: 1px solid #f1f5f9; color: #475569; font-weight: 500; font-size: 11px; border-bottom: 1px solid #e2e8f0;"><div style="display:flex; align-items:center; gap:8px;"><div style="width:24px; height:24px; border-radius:50%; background:#f1f5f9; display:flex; align-items:center; justify-content:center; font-size:11px; font-weight:600; color:#475569;">DL</div><span style="font-weight: 500; color:#1e293b;">Dewi Lestari</span></div></td>
                                <td style="padding: 6px 8px; border-bottom: 1px solid #f1f5f9; color: #475569; font-weight: 500; font-size: 11px; border-bottom: 1px solid #e2e8f0;">Renovasi Rumah</td>
                                <td style="padding: 6px 8px; border-bottom: 1px solid #f1f5f9; color: #475569; font-weight: 500; font-size: 11px; border-bottom: 1px solid #e2e8f0;"><span style="font-weight: 600; color: #1e293b;">Rp 10.000.000</span></td>
                                <td style="padding: 6px 8px; border-bottom: 1px solid #f1f5f9;"><span style="background: #dcfce7; color: #16a34a; padding: 4px 10px; border-radius: 6px; font-weight: 600; font-size: 11px; border-bottom: 1px solid #e2e8f0;">Disetujui</span></td>
                            </tr>
                            <tr>
                                <td style="padding: 6px 8px; border-bottom: 1px solid #f1f5f9; color: #475569; font-weight: 500; font-size: 11px; border-bottom: 1px solid #e2e8f0;"><div style="display:flex; align-items:center; gap:8px;"><div style="width:24px; height:24px; border-radius:50%; background:#f1f5f9; display:flex; align-items:center; justify-content:center; font-size:11px; font-weight:600; color:#475569;">RH</div><span style="font-weight: 500; color:#1e293b;">Rudi Hartono</span></div></td>
                                <td style="padding: 6px 8px; border-bottom: 1px solid #f1f5f9; color: #475569; font-weight: 500; font-size: 11px; border-bottom: 1px solid #e2e8f0;">Modal Usaha</td>
                                <td style="padding: 6px 8px; border-bottom: 1px solid #f1f5f9; color: #475569; font-weight: 500; font-size: 11px; border-bottom: 1px solid #e2e8f0;"><span style="font-weight: 600; color: #1e293b;">Rp 6.000.000</span></td>
                                <td style="padding: 6px 8px; border-bottom: 1px solid #f1f5f9;"><span style="background: #fee2e2; color: #ef4444; padding: 4px 10px; border-radius: 6px; font-weight: 600; font-size: 11px; border-bottom: 1px solid #e2e8f0;">Ditolak</span></td>
                            </tr>
                        </tbody>
                    </table>
                    <div style="margin-top:auto; padding-top:16px; text-align:center; display:flex; justify-content:center;">
                        <a href="#" style="font-size:13px; font-weight:600; color:#2d5cf6; text-decoration:none; display:flex; align-items:center; gap:4px;">Kelola Pinjaman <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></svg></a>
                    </div>
                </div>
            </section>
            <x-app-footer />

        </div>
    </main>

    {{-- ===== CHART SCRIPTS ===== --}}
        <script>
    document.addEventListener('DOMContentLoaded', () => {
        Chart.register(ChartDataLabels);
        // ── Line Chart ──
        const lineCtx = document.getElementById('lineChart')?.getContext('2d');
        if (lineCtx) {
            new Chart(lineCtx, {
                type: 'line',
                data: {
                    labels: ['Des 2024', 'Jan 2025', 'Feb 2025', 'Mar 2025', 'Apr 2025', 'Mei 2025'],
                    datasets: [
                        {
                            label: 'Total Simpanan',
                            data: [390.2, 402.1, 421.8, 438.9, 461.2, 487.6],
                            borderColor: '#10b981',
                            backgroundColor: 'transparent',
                            borderWidth: 2.5,
                            tension: 0,
                            pointRadius: 4,
                            pointBackgroundColor: '#ffffff',
                            pointBorderColor: '#10b981',
                            pointBorderWidth: 2,
                            datalabels: {
                                color: '#16a34a',
                                align: 'top',
                                anchor: 'end',
                                font: { family: 'Poppins', size: 10, weight: 600 },
                                formatter: function(value) {
                                    return value.toFixed(1).replace('.', ',');
                                }
                            }
                        },
                        {
                            label: 'Total Pinjaman',
                            data: [250.3, 262.7, 275.4, 289.6, 304.7, 325.0],
                            borderColor: '#3b82f6',
                            backgroundColor: 'transparent',
                            borderWidth: 2.5,
                            tension: 0,
                            pointRadius: 4,
                            pointBackgroundColor: '#ffffff',
                            pointBorderColor: '#3b82f6',
                            pointBorderWidth: 2,
                            datalabels: {
                                color: '#1e293b',
                                align: 'bottom',
                                anchor: 'start',
                                font: { family: 'Poppins', size: 10, weight: 600 },
                                formatter: function(value) {
                                    return value.toFixed(1).replace('.', ',');
                                }
                            }
                        }
                    ]
                },
                options: {
                    layout: {
                        padding: {
                            left: 10,
                            right: 15,
                            top: 15,
                            bottom: 5
                        }
                    },
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: '#1e293b',
                            titleFont: { family: 'Poppins', size: 12 },
                            bodyFont: { family: 'Poppins', size: 12 },
                            padding: 10,
                            cornerRadius: 8,
                            callbacks: {
                                label: (ctx) => `Rp ${ctx.parsed.y} jt`,
                            }
                        }
                    },
                    scales: {
                        x: {
                            grid: { display: false },
                            ticks: { font: { family: 'Poppins', size: 11 }, color: '#64748b' },
                            border: { display: false },
                            offset: true
                        },
                        y: {
                            grid: { color: '#f1f5f9' },
                            ticks: {
                                font: { family: 'Poppins', size: 11 },
                                color: '#64748b',
                                stepSize: 150,
                                callback: (v) => v === 0 ? '0' : v + ' jt',
                            },
                            border: { display: false },
                            min: 0,
                            suggestedMax: 600,
                        }
                    }
                }
            });
        }

        // ── Donut Chart ──
        const donutCtx = document.getElementById('donutChart')?.getContext('2d');
        if (donutCtx) {
            new Chart(donutCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Simpanan Pokok', 'Simpanan Wajib', 'Simpanan Sukarela', 'Simpanan Berjangka', 'Lainnya'],
                    datasets: [{
                        data: [25, 40, 20, 10, 5],
                        backgroundColor: ['#3b82f6', '#10b981', '#f59e0b', '#a855f7', '#94a3b8'],
                        borderWidth: 0,
                        hoverOffset: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '70%',
                    plugins: {
                        legend: { display: false },
                        datalabels: { display: false },
                        tooltip: {
                            backgroundColor: '#1e293b',
                            bodyFont: { family: 'Poppins', size: 12 },
                            padding: 10,
                            cornerRadius: 8,
                            callbacks: {
                                label: (ctx) => ` ${ctx.label}: ${ctx.parsed}%`
                            }
                        }
                    }
                }
            });
        }
    });
    </script>
</body>
</html>
