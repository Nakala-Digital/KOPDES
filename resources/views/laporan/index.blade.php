<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Laporan | DesaHub</title>
    <meta name="description" content="Kelola, pantau, dan unduh laporan lintas modul secara terstruktur dan terintegrasi.">
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>
    <style>
        /* Tabs styling */
        .lap-tabs {
            display: flex;
            gap: 24px;
            border-bottom: 1px solid var(--border-color);
            margin-bottom: 24px;
            padding: 0 4px;
        }
        .lap-tab {
            padding: 12px 4px;
            font-size: 14px;
            font-weight: 500;
            color: var(--text-secondary);
            cursor: pointer;
            border-bottom: 2px solid transparent;
        }
        .lap-tab.active {
            color: var(--primary);
            border-bottom-color: var(--primary);
        }
    </style>
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
    <div class="sidebar-overlay" data-sidebar-overlay></div>

    {{-- ===== SIDEBAR ===== --}}
    <aside class="sidebar" data-sidebar>
        {{-- Brand --}}
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

        {{-- Navigation --}}
        <nav class="sidebar-nav">
            <a href="{{ route('dashboard') }}" class="sidebar-link">
                <x-sidebar-icon name="dashboard" />
                <span>Dashboard</span>
            </a>

            <p class="sidebar-category">Data & Informasi</p>
            <a href="{{ route('pendataan.index') }}" class="sidebar-link">
                <x-sidebar-icon name="pendataan-desa" />
                <span>Pendataan Desa</span>
            </a>
            <a href="{{ route('pendataan.index') }}" class="sidebar-link">
                <x-sidebar-icon name="penduduk" />
                <span>Penduduk</span>
            </a>
            <a href="{{ route('pendataan.index') }}" class="sidebar-link">
                <x-sidebar-icon name="potensi-desa" />
                <span>Potensi Desa</span>
            </a>

            <p class="sidebar-category">Kelembagaan</p>
            <a href="{{ route('kopdes.index') }}" class="sidebar-link">
                <x-sidebar-icon name="kopdes" />
                <span>Kopdes/KDMP</span>
            </a>
            <a href="{{ route('bumdes.index') }}" class="sidebar-link">
                <x-sidebar-icon name="bumdes" />
                <span>BUMDes</span>
            </a>

            <p class="sidebar-category">Ekonomi</p>
            <a href="{{ route('umkm.index') }}" class="sidebar-link">
                <x-sidebar-icon name="umkm" />
                <span>UMKM & Produk</span>
            </a>
            <a href="{{ route('umkm.index') }}" class="sidebar-link">
                <x-sidebar-icon name="pasar-desa" />
                <span>Pasar Desa</span>
            </a>

            <p class="sidebar-category">Supply Chain & MBG</p>
            <a href="{{ route('mbg.index') }}" class="sidebar-link">
                <x-sidebar-icon name="rantai-pasok" />
                <span>Rantai Pasok MBG</span>
            </a>
            <a href="{{ route('mbg.index') }}" class="sidebar-link">
                <x-sidebar-icon name="gudang" />
                <span>Gudang & Logistik</span>
            </a>
            <a href="{{ route('mbg.index') }}" class="sidebar-link">
                <x-sidebar-icon name="penerima-manfaat" />
                <span>Penerima Manfaat</span>
            </a>

            <p class="sidebar-category">Keuangan</p>
            <a href="{{ route('keuangan.index') }}" class="sidebar-link">
                <x-sidebar-icon name="keuangan" />
                <span>Keuangan & Transaksi</span>
            </a>

            <p class="sidebar-category">Laporan</p>
            <a href="{{ route('analitik.index') }}" class="sidebar-link">
                <x-sidebar-icon name="dashboard-analitik" />
                <span>Dashboard & Analitik</span>
            </a>
            <a href="{{ route('laporan.index') }}" class="sidebar-link active">
                <x-sidebar-icon name="laporan" />
                <span>Laporan</span>
            </a>
            <a href="{{ route('pengaturan.index') }}" class="sidebar-link">
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

    {{-- ===== TOP BAR ===== --}}
    <header class="topbar" data-topbar>
        <div class="topbar-left" style="display: flex; align-items: center; gap: 16px;">
            <button class="topbar-hamburger" id="menu-toggle" aria-label="Toggle Menu" data-sidebar-toggle style="margin-right: 0;">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width: 20px; height: 20px;">
                    <line x1="4" x2="20" y1="12" y2="12"/><line x1="4" x2="20" y1="6" y2="6"/><line x1="4" x2="14" y1="18" y2="18"/>
                </svg>
            </button>
            <div style="display: flex; flex-direction: column;">
                <span class="topbar-title" style="margin: 0; line-height: 1.2;">Laporan</span>
                <span style="font-size: 13px; color: #64748b; margin-top: 2px;">Kelola, pantau, dan unduh laporan lintas modul secara terstruktur dan terintegrasi.</span>
            </div>
        </div>

        <div class="topbar-right" style="display: flex; align-items: center;">
            <div class="fin-page-actions" style="display: flex; gap: 12px; align-items: center; margin-right: 16px;">
                <button class="fin-btn-outline" style="height: 34px; padding: 0 12px; font-size: 13px; background: white; border: 1px solid var(--primary); border-radius: 8px; color: var(--primary); display: flex; align-items: center; gap: 6px; cursor: pointer;">
                    <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" x2="12" y1="15" y2="3"/></svg>
                    Export Data
                </button>
                <button class="fin-btn-primary" style="height: 34px; padding: 0 12px; font-size: 13px; background: var(--primary); border: none; border-radius: 8px; color: white; display: flex; align-items: center; gap: 6px; cursor: pointer;">
                    <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                    Buat Laporan
                </button>
            </div>
            <button class="topbar-icon-btn" aria-label="Help">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"/><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/><path d="M12 17h.01"/>
                    </svg>
                </button>
                <button class="topbar-icon-btn" aria-label="Notifications" style="position: relative;">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9"/><path d="M10.3 21a1.94 1.94 0 0 0 3.4 0"/>
                    </svg>
                <span class="notification-badge">2</span>
            </button>

            <div class="topbar-user" style="position: relative;" id="user-dropdown-trigger">
                <img src="https://i.pravatar.cc/150?img=11" alt="User Avatar" class="topbar-user-avatar" />
                <div class="topbar-user-info">
                    <div class="topbar-user-name">{{ $roleLabel }}</div>
                    <div class="topbar-user-role">{{ $villageName }}</div>
                </div>
                <svg class="topbar-user-chevron" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>

                {{-- Dropdown --}}
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

    {{-- ===== MAIN CONTENT ===== --}}
    <main class="main-content fin-main-content" data-main-content>
        <div class="main-inner fin-main-inner">
            
            {{-- Tabs --}}
            <div class="lap-tabs">
                <div class="lap-tab active">Ringkasan</div>
                <div class="lap-tab">Laporan Bulanan</div>
                <div class="lap-tab">Laporan Keuangan</div>
                <div class="lap-tab">Laporan MBG</div>
                <div class="lap-tab">Laporan UMKM</div>
                <div class="lap-tab">Arsip</div>
            </div>

            {{-- ── KPI Stat Cards ── --}}
            <section class="fin-kpi-cards" style="grid-template-columns: repeat(5, 1fr);">
                {{-- Total Laporan --}}
                <article class="stat-card">
                    <div class="stat-card-icon" style="background: #e0e7ff; color: #4f46e5;">
                        <svg viewBox="0 0 24 24" fill="currentColor" width="24" height="24">
                            <path d="M14 2H6c-1.1 0-1.99.9-1.99 2L4 20c0 1.1.89 2 1.99 2H18c1.1 0 2-.9 2-2V8l-6-6zm-1 7V3.5L18.5 9H13zm-2 9H8v-2h3v2zm4-4H8v-2h7v2z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="stat-card-label" style="font-weight: 700; color: var(--sidebar-bg);">Total Laporan</p>
                        <p class="stat-card-value" style="color: var(--sidebar-bg);">128</p>
                        <p class="stat-card-change" style="display: flex; align-items: center; gap: 3px;">
                            <span style="color: #16a34a; display: flex; align-items: center; font-weight: 600; gap: 2px;">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="width: 14px; height: 14px;"><line x1="12" y1="19" x2="12" y2="5"/><polyline points="5 12 12 5 19 12"/></svg>
                                12,5%
                            </span>
                            <span style="color: #64748b;">dari bulan lalu</span>
                        </p>
                    </div>
                </article>

                {{-- Laporan Bulan Ini --}}
                <article class="stat-card">
                    <div class="stat-card-icon" style="background: #dcfce7; color: #16a34a;">
                        <svg viewBox="0 0 24 24" fill="currentColor" width="24" height="24">
                            <path d="M19 3h-1V1h-2v2H8V1H6v2H5c-1.11 0-1.99.9-1.99 2L3 19a2 2 0 0 0 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm0 16H5V8h14v11zm-5-9H7v2h7v-2zm-3 4H7v2h4v-2z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="stat-card-label" style="font-weight: 700; color: var(--sidebar-bg);">Laporan Bulan Ini</p>
                        <p class="stat-card-value" style="color: var(--sidebar-bg);">24</p>
                        <p class="stat-card-change" style="display: flex; align-items: center; gap: 3px;">
                            <span style="color: #16a34a; display: flex; align-items: center; font-weight: 600; gap: 2px;">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="width: 14px; height: 14px;"><line x1="12" y1="19" x2="12" y2="5"/><polyline points="5 12 12 5 19 12"/></svg>
                                9,1%
                            </span>
                            <span style="color: #64748b;">dari bulan lalu</span>
                        </p>
                    </div>
                </article>

                {{-- Siap Diunduh --}}
                <article class="stat-card">
                    <div class="stat-card-icon" style="background: #f3e8ff; color: #9333ea;">
                        <svg viewBox="0 0 24 24" fill="currentColor" width="24" height="24">
                            <path d="M19.35 10.04A7.49 7.49 0 0 0 12 4C9.11 4 6.6 5.64 5.35 8.04A5.994 5.994 0 0 0 0 14c0 3.31 2.69 6 6 6h13c2.76 0 5-2.24 5-5 0-2.64-2.05-4.78-4.65-4.96zM17 13l-5 5-5-5h3V9h4v4h3z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="stat-card-label" style="font-weight: 700; color: var(--sidebar-bg);">Siap Diunduh</p>
                        <p class="stat-card-value" style="color: var(--sidebar-bg);">18</p>
                        <p class="stat-card-change" style="display: flex; align-items: center; gap: 3px;">
                            <span style="color: #16a34a; display: flex; align-items: center; font-weight: 600; gap: 2px;">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="width: 14px; height: 14px;"><line x1="12" y1="19" x2="12" y2="5"/><polyline points="5 12 12 5 19 12"/></svg>
                                5,6%
                            </span>
                            <span style="color: #64748b;">dari bulan lalu</span>
                        </p>
                    </div>
                </article>

                {{-- Menunggu Persetujuan --}}
                <article class="stat-card">
                    <div class="stat-card-icon" style="background: #ffedd5; color: #ea580c;">
                        <svg viewBox="0 0 24 24" fill="currentColor" width="24" height="24">
                            <path d="M6 2v6l4 4-4 4v6h12v-6l-4-4 4-4V2H6zm10 14.5V20H8v-3.5l4-4 4 4zm-4-5l-4-4V4h8v3.5l-4 4z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="stat-card-label" style="font-weight: 700; color: var(--sidebar-bg);">Menunggu Persetujuan</p>
                        <p class="stat-card-value" style="color: var(--sidebar-bg);">6</p>
                        <p class="stat-card-change" style="display: flex; align-items: center; gap: 3px;">
                            <span style="color: #64748b; font-weight: 500;">
                                Sama dengan bulan lalu
                            </span>
                        </p>
                    </div>
                </article>

                {{-- Otomatis Terjadwal --}}
                <article class="stat-card">
                    <div class="stat-card-icon" style="background: #dcfce7; color: #16a34a;">
                        <svg viewBox="0 0 24 24" fill="currentColor" width="24" height="24">
                            <path d="M11.99 2C6.47 2 2 6.48 2 12s4.47 10 9.99 10C17.52 22 22 17.52 22 12S17.52 2 11.99 2zM12 20c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8 8-3.58 8-8 8zm.5-13H11v6l5.25 3.15.75-1.23-4.5-2.67z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="stat-card-label" style="font-weight: 700; color: var(--sidebar-bg);">Otomatis Terjadwal</p>
                        <p class="stat-card-value" style="color: var(--sidebar-bg);">12</p>
                        <p class="stat-card-change" style="display: flex; align-items: center; gap: 3px;">
                            <span style="color: #16a34a; display: flex; align-items: center; font-weight: 600; gap: 2px;">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="width: 14px; height: 14px;"><line x1="12" y1="19" x2="12" y2="5"/><polyline points="5 12 12 5 19 12"/></svg>
                                20,0%
                            </span>
                            <span style="color: #64748b;">dari bulan lalu</span>
                        </p>
                    </div>
                </article>
            </section>

            {{-- ── Charts Row (Tren Pembuatan & Sebaran Jenis) ── --}}
            <section class="ana-chart-row">
                {{-- Tren Pembuatan Laporan --}}
                <div class="chart-card">
                    <div class="chart-header" style="justify-content: flex-start; gap: 32px;">
                        <h2 class="chart-title">Tren Pembuatan Laporan</h2>
                        <div class="fin-chart-legend" style="gap: 16px;">
                            <span class="fin-legend-item"><span class="fin-legend-dot" style="background: #2563eb;"></span> Laporan Dibuat</span>
                            <span class="fin-legend-item"><span class="fin-legend-dot" style="background: #16a34a;"></span> Laporan Diunduh</span>
                        </div>
                    </div>
                    <div class="line-chart-container" style="height: 240px; margin-top: 10px;">
                        <canvas id="reportTrendChart"></canvas>
                    </div>
                </div>

                {{-- Sebaran Jenis Laporan --}}
                <div class="chart-card">
                    <div class="chart-header">
                        <h2 class="chart-title">Sebaran Jenis Laporan</h2>
                    </div>
                    <div class="fin-expense-chart-container" style="gap: 16px; margin-top: 10px;">
                        <div class="fin-donut-wrapper" style="width: 140px; height: 140px;">
                            <canvas id="reportTypeChart"></canvas>
                        </div>
                        <div class="fin-expense-legend" style="flex: 1; padding-left: 12px;">
                            <div class="fin-expense-legend-item" style="margin-bottom: 8px;">
                                <span class="fin-expense-legend-label" style="font-size: 11px;"><span class="donut-legend-dot" style="background: #2563eb;"></span> Keuangan</span>
                                <span class="fin-expense-legend-values" style="font-size: 11px;">40 <span class="fin-expense-pct">(31%)</span></span>
                            </div>
                            <div class="fin-expense-legend-item" style="margin-bottom: 8px;">
                                <span class="fin-expense-legend-label" style="font-size: 11px;"><span class="donut-legend-dot" style="background: #16a34a;"></span> MBG</span>
                                <span class="fin-expense-legend-values" style="font-size: 11px;">28 <span class="fin-expense-pct">(22%)</span></span>
                            </div>
                            <div class="fin-expense-legend-item" style="margin-bottom: 8px;">
                                <span class="fin-expense-legend-label" style="font-size: 11px;"><span class="donut-legend-dot" style="background: #f59e0b;"></span> UMKM</span>
                                <span class="fin-expense-legend-values" style="font-size: 11px;">22 <span class="fin-expense-pct">(17%)</span></span>
                            </div>
                            <div class="fin-expense-legend-item" style="margin-bottom: 8px;">
                                <span class="fin-expense-legend-label" style="font-size: 11px;"><span class="donut-legend-dot" style="background: #9333ea;"></span> Pasar Desa</span>
                                <span class="fin-expense-legend-values" style="font-size: 11px;">20 <span class="fin-expense-pct">(16%)</span></span>
                            </div>
                            <div class="fin-expense-legend-item" style="margin-bottom: 8px;">
                                <span class="fin-expense-legend-label" style="font-size: 11px;"><span class="donut-legend-dot" style="background: #64748b;"></span> Operasional Lainnya</span>
                                <span class="fin-expense-legend-values" style="font-size: 11px;">18 <span class="fin-expense-pct">(14%)</span></span>
                            </div>
                            
                            <div style="border-top: 1px solid #e5e7eb; margin-top: 12px; padding-top: 12px; display: flex; justify-content: space-between; font-size: 12px; font-weight: 600; color: var(--sidebar-bg);">
                                <span>Total Laporan</span>
                                <span>128</span>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            {{-- ── Bottom Row (Table + Insights) ── --}}
            <section class="ana-chart-row">
                <div class="fin-table-card" style="height: 100%;">
                    <div class="bottom-card-header">
                        <h3 class="bottom-card-title">Daftar Laporan Terbaru</h3>
                    </div>
                    <div class="fin-table-wrapper" style="margin-top: 12px;">
                        <table class="fin-table" style="width: 100%; border-collapse: collapse;">
                            <thead>
                                <tr>
                                    <th style="width: 40px; text-align: left; padding: 12px 16px; font-weight: 500; font-size: 12px; color: #64748b; border-bottom: 1px solid #e2e8f0;">No</th>
                                    <th style="text-align: left; padding: 12px 16px; font-weight: 500; font-size: 12px; color: #64748b; border-bottom: 1px solid #e2e8f0;">Nama Laporan</th>
                                    <th style="text-align: left; padding: 12px 16px; font-weight: 500; font-size: 12px; color: #64748b; border-bottom: 1px solid #e2e8f0;">Modul</th>
                                    <th style="text-align: left; padding: 12px 16px; font-weight: 500; font-size: 12px; color: #64748b; border-bottom: 1px solid #e2e8f0;">Periode</th>
                                    <th style="text-align: left; padding: 12px 16px; font-weight: 500; font-size: 12px; color: #64748b; border-bottom: 1px solid #e2e8f0;">Tanggal</th>
                                    <th style="text-align: left; padding: 12px 16px; font-weight: 500; font-size: 12px; color: #64748b; border-bottom: 1px solid #e2e8f0;">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td style="padding: 12px 16px; font-size: 13px; color: var(--text-primary); border-bottom: 1px solid #e2e8f0;">1</td>
                                    <td style="padding: 12px 16px; font-size: 13px; font-weight: 500; color: var(--text-primary); border-bottom: 1px solid #e2e8f0;">Laporan Keuangan Bulanan</td>
                                    <td style="padding: 12px 16px; font-size: 13px; color: var(--text-secondary); border-bottom: 1px solid #e2e8f0;">Keuangan & Transaksi</td>
                                    <td style="padding: 12px 16px; font-size: 13px; color: var(--text-secondary); border-bottom: 1px solid #e2e8f0;">Mei 2025</td>
                                    <td style="padding: 12px 16px; font-size: 13px; color: var(--text-secondary); border-bottom: 1px solid #e2e8f0;">21 Mei 2025 09:30</td>
                                    <td style="padding: 12px 16px; border-bottom: 1px solid #e2e8f0;"><span class="fin-badge fin-badge-success">Siap</span></td>
                                </tr>
                                <tr>
                                    <td style="padding: 12px 16px; font-size: 13px; color: var(--text-primary); border-bottom: 1px solid #e2e8f0;">2</td>
                                    <td style="padding: 12px 16px; font-size: 13px; font-weight: 500; color: var(--text-primary); border-bottom: 1px solid #e2e8f0;">Rekap Penyaluran MBG</td>
                                    <td style="padding: 12px 16px; font-size: 13px; color: var(--text-secondary); border-bottom: 1px solid #e2e8f0;">MBG</td>
                                    <td style="padding: 12px 16px; font-size: 13px; color: var(--text-secondary); border-bottom: 1px solid #e2e8f0;">Mei 2025</td>
                                    <td style="padding: 12px 16px; font-size: 13px; color: var(--text-secondary); border-bottom: 1px solid #e2e8f0;">21 Mei 2025 08:45</td>
                                    <td style="padding: 12px 16px; border-bottom: 1px solid #e2e8f0;"><span class="fin-badge fin-badge-success">Siap</span></td>
                                </tr>
                                <tr>
                                    <td style="padding: 12px 16px; font-size: 13px; color: var(--text-primary); border-bottom: 1px solid #e2e8f0;">3</td>
                                    <td style="padding: 12px 16px; font-size: 13px; font-weight: 500; color: var(--text-primary); border-bottom: 1px solid #e2e8f0;">Laporan Aktivitas Pasar Desa</td>
                                    <td style="padding: 12px 16px; font-size: 13px; color: var(--text-secondary); border-bottom: 1px solid #e2e8f0;">Pasar Desa</td>
                                    <td style="padding: 12px 16px; font-size: 13px; color: var(--text-secondary); border-bottom: 1px solid #e2e8f0;">Mei 2025</td>
                                    <td style="padding: 12px 16px; font-size: 13px; color: var(--text-secondary); border-bottom: 1px solid #e2e8f0;">20 Mei 2025 16:20</td>
                                    <td style="padding: 12px 16px; border-bottom: 1px solid #e2e8f0;"><span class="fin-badge fin-badge-warning" style="background: #ffedd5; color: #ea580c;">Review</span></td>
                                </tr>
                                <tr>
                                    <td style="padding: 12px 16px; font-size: 13px; color: var(--text-primary); border-bottom: 1px solid #e2e8f0;">4</td>
                                    <td style="padding: 12px 16px; font-size: 13px; font-weight: 500; color: var(--text-primary); border-bottom: 1px solid #e2e8f0;">Ringkasan Kinerja UMKM</td>
                                    <td style="padding: 12px 16px; font-size: 13px; color: var(--text-secondary); border-bottom: 1px solid #e2e8f0;">UMKM & Produk</td>
                                    <td style="padding: 12px 16px; font-size: 13px; color: var(--text-secondary); border-bottom: 1px solid #e2e8f0;">April 2025</td>
                                    <td style="padding: 12px 16px; font-size: 13px; color: var(--text-secondary); border-bottom: 1px solid #e2e8f0;">20 Mei 2025 14:10</td>
                                    <td style="padding: 12px 16px; border-bottom: 1px solid #e2e8f0;"><span class="fin-badge fin-badge-info" style="background: #e0e7ff; color: #4f46e5;">Draft</span></td>
                                </tr>
                                <tr>
                                    <td style="padding: 12px 16px; font-size: 13px; color: var(--text-primary);">5</td>
                                    <td style="padding: 12px 16px; font-size: 13px; font-weight: 500; color: var(--text-primary);">Laporan Stok Gudang</td>
                                    <td style="padding: 12px 16px; font-size: 13px; color: var(--text-secondary);">Gudang & Logistik</td>
                                    <td style="padding: 12px 16px; font-size: 13px; color: var(--text-secondary);">Mei 2025</td>
                                    <td style="padding: 12px 16px; font-size: 13px; color: var(--text-secondary);">20 Mei 2025 10:05</td>
                                    <td style="padding: 12px 16px;"><span class="fin-badge" style="background: #f3e8ff; color: #9333ea;">Disetujui</span></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div style="text-align: center; margin-top: 16px;">
                        <a href="#" style="color: var(--primary); font-size: 13px; font-weight: 600; text-decoration: none;">Lihat Semua Laporan &rarr;</a>
                    </div>
                </div>

                <div class="chart-card" style="height: 100%;">
                    <div class="chart-header">
                        <h2 class="chart-title">Insight & Status Laporan</h2>
                    </div>
                    
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-top: 16px;">
                        <div style="border: 1px solid var(--border-color); border-radius: 8px; padding: 12px;">
                            <div style="font-size: 11px; color: var(--text-secondary); margin-bottom: 6px; display: flex; align-items: center; gap: 6px;">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#2563eb" stroke-width="2"><circle cx="12" cy="12" r="10"/><circle cx="12" cy="12" r="6"/><circle cx="12" cy="12" r="2"/></svg>
                                Ketepatan Laporan
                            </div>
                            <div style="font-size: 18px; font-weight: 700; color: var(--sidebar-bg); margin-bottom: 2px;">94%</div>
                            <div style="font-size: 11px; color: #16a34a; font-weight: 600;">&uarr; 6% <span style="color: var(--text-secondary); font-weight: 400;">dari bulan lalu</span></div>
                        </div>
                        <div style="border: 1px solid var(--border-color); border-radius: 8px; padding: 12px;">
                            <div style="font-size: 11px; color: var(--text-secondary); margin-bottom: 6px; display: flex; align-items: center; gap: 6px;">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#16a34a" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                                Laporan Tepat Waktu
                            </div>
                            <div style="font-size: 18px; font-weight: 700; color: var(--sidebar-bg); margin-bottom: 2px;">91%</div>
                            <div style="font-size: 11px; color: #16a34a; font-weight: 600;">&uarr; 4% <span style="color: var(--text-secondary); font-weight: 400;">dari bulan lalu</span></div>
                        </div>
                        <div style="border: 1px solid var(--border-color); border-radius: 8px; padding: 12px;">
                            <div style="font-size: 11px; color: var(--text-secondary); margin-bottom: 6px; display: flex; align-items: center; gap: 6px;">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#ea580c" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                                Persetujuan Selesai
                            </div>
                            <div style="font-size: 18px; font-weight: 700; color: var(--sidebar-bg); margin-bottom: 2px;">88%</div>
                            <div style="font-size: 11px; color: #16a34a; font-weight: 600;">&uarr; 7% <span style="color: var(--text-secondary); font-weight: 400;">dari bulan lalu</span></div>
                        </div>
                        <div style="border: 1px solid var(--border-color); border-radius: 8px; padding: 12px;">
                            <div style="font-size: 11px; color: var(--text-secondary); margin-bottom: 6px; display: flex; align-items: center; gap: 6px;">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#9333ea" stroke-width="2"><path d="M14 2H6c-1.1 0-1.99.9-1.99 2L4 20c0 1.1.89 2 1.99 2H18c1.1 0 2-.9 2-2V8l-6-6zm-1 7V3.5L18.5 9H13z"/></svg>
                                Template Aktif
                            </div>
                            <div style="font-size: 18px; font-weight: 700; color: var(--sidebar-bg); margin-bottom: 2px;">14</div>
                            <div style="font-size: 11px; color: var(--text-secondary);">Sama dengan bulan lalu</div>
                        </div>
                    </div>

                    <div style="margin-top: 20px;">
                        <div style="display: flex; align-items: center; padding: 12px 0; border-bottom: 1px solid #e5e7eb;">
                            <div style="width: 28px; height: 28px; background: #ffedd5; border-radius: 6px; display: flex; align-items: center; justify-content: center; margin-right: 12px;">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#ea580c" stroke-width="2"><path d="M6 2v6l4 4-4 4v6h12v-6l-4-4 4-4V2H6z"/></svg>
                            </div>
                            <div style="flex: 1;">
                                <div style="font-size: 13px; font-weight: 500; color: var(--text-primary);">2 laporan menunggu review lebih dari 3 hari</div>
                            </div>
                            <div style="font-size: 11px; color: var(--text-secondary); display: flex; align-items: center; gap: 8px;">
                                21 Mei 2025 09:30
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg>
                            </div>
                        </div>
                        <div style="display: flex; align-items: center; padding: 12px 0; border-bottom: 1px solid #e5e7eb;">
                            <div style="width: 28px; height: 28px; background: #dcfce7; border-radius: 6px; display: flex; align-items: center; justify-content: center; margin-right: 12px;">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#16a34a" stroke-width="2"><path d="M19.35 10.04A7.49 7.49 0 0 0 12 4C9.11 4 6.6 5.64 5.35 8.04A5.994 5.994 0 0 0 0 14c0 3.31 2.69 6 6 6h13c2.76 0 5-2.24 5-5 0-2.64-2.05-4.78-4.65-4.96zM17 13l-5 5-5-5h3V9h4v4h3z"/></svg>
                            </div>
                            <div style="flex: 1;">
                                <div style="font-size: 13px; font-weight: 500; color: var(--text-primary);">18 laporan siap diunduh</div>
                            </div>
                            <div style="font-size: 11px; color: var(--text-secondary); display: flex; align-items: center; gap: 8px;">
                                21 Mei 2025 08:45
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg>
                            </div>
                        </div>
                        <div style="display: flex; align-items: center; padding: 12px 0;">
                            <div style="width: 28px; height: 28px; background: #e0e7ff; border-radius: 6px; display: flex; align-items: center; justify-content: center; margin-right: 12px;">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#2563eb" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                            </div>
                            <div style="flex: 1;">
                                <div style="font-size: 13px; font-weight: 500; color: var(--text-primary);">Jadwal laporan otomatis besok</div>
                            </div>
                            <div style="font-size: 11px; color: var(--text-secondary); display: flex; align-items: center; gap: 8px;">
                                21 Mei 2025 08:00
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            {{-- Footer --}}
            <footer class="dashboard-footer" style="padding: 24px 0 16px; display: flex; align-items: center; justify-content: space-between; border-top: 1px solid var(--border-color); margin-top: 24px;">
                <div style="display: flex; align-items: center; gap: 60px;">
                    <div class="footer-left" style="font-size: 12px; color: var(--text-secondary);">
                        <span style="font-weight: 600; color: var(--sidebar-bg);">DesaHub</span> — Ekosistem Ekonomi Desa
                    </div>
                    
                    <div class="footer-center" style="font-size: 12px; color: var(--text-secondary);">
                        © 2025 DesaHub. All rights reserved.
                    </div>
                </div>

                <div class="footer-right" style="display: flex; align-items: center; justify-content: flex-end; gap: 20px;">
                    <div class="footer-partner-text" style="text-align: right; font-size: 11px; line-height: 1.4;">
                        <div style="font-weight: 600; color: var(--sidebar-bg);">Nakala Digital &times; Romulus Digital</div>
                        <div style="color: var(--text-secondary);">Strategic Partner — Singapore/Vietnam</div>
                    </div>
                    <div style="display: flex; align-items: center; gap: 12px;">
                        {{-- Nakala Logo --}}
                        <img src="{{ asset('assets/desahub/logo-nakala-transparent.png') }}" alt="Nakala Digital" style="height: 52px; width: auto; object-fit: contain; display: block;">

                        {{-- Romulus Logo --}}
                        <img src="{{ asset('assets/desahub/logo-romulus-biru.png') }}" alt="Romulus Digital" style="height: 52px; width: auto; object-fit: contain; display: block;">
                    </div>
                </div>
            </footer>

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

        // ── Tren Pembuatan Laporan (Line Chart) ──
        const trenCtx = document.getElementById('reportTrendChart')?.getContext('2d');
        if (trenCtx) {
            new Chart(trenCtx, {
                type: 'line',
                data: {
                    labels: ['Dec 2024', 'Jan 2025', 'Feb 2025', 'Mar 2025', 'Apr 2025', 'May 2025'],
                    datasets: [
                        {
                            label: 'Laporan Dibuat',
                            data: [22, 28, 32, 38, 45, 55],
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
                            label: 'Laporan Diunduh',
                            data: [14, 18, 21, 26, 31, 38],
                            borderColor: '#16a34a',
                            borderWidth: 2,
                            tension: 0,
                            fill: false,
                            pointRadius: 4,
                            pointBackgroundColor: '#16a34a',
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
                                stepSize: 10,
                            },
                            border: { display: false },
                            min: 0,
                            max: 60,
                        }
                    }
                },
                plugins: [lineDatalabels]
            });
        }

        // ── Sebaran Jenis Laporan (Doughnut Chart) ──
        const modulCtx = document.getElementById('reportTypeChart')?.getContext('2d');
        if (modulCtx) {
            new Chart(modulCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Keuangan', 'MBG', 'UMKM', 'Pasar Desa', 'Operasional Lainnya'],
                    datasets: [{
                        data: [40, 28, 22, 20, 18],
                        backgroundColor: [
                            '#2563eb', // biru
                            '#16a34a', // hijau
                            '#f59e0b', // kuning/oranye
                            '#9333ea', // ungu
                            '#64748b'  // abu-abu
                        ],
                        borderWidth: 0,
                        hoverOffset: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '65%',
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: '#1a1d2e',
                            titleFont: { family: 'Inter', size: 12 },
                            bodyFont: { family: 'Inter', size: 12 },
                            padding: 10,
                            cornerRadius: 8,
                            callbacks: {
                                label: function(context) {
                                    let label = context.label || '';
                                    if (label) {
                                        label += ': ';
                                    }
                                    if (context.parsed !== null) {
                                        label += context.parsed;
                                    }
                                    return label;
                                }
                            }
                        }
                    }
                }
            });
        }

        // Dropdown User Toggle
        const userTrigger = document.getElementById('user-dropdown-trigger');
        const userDropdown = document.getElementById('user-dropdown');
        if(userTrigger && userDropdown) {
            userTrigger.addEventListener('click', (e) => {
                userDropdown.classList.toggle('show');
                e.stopPropagation();
            });
            document.addEventListener('click', (e) => {
                if(!userTrigger.contains(e.target)) {
                    userDropdown.classList.remove('show');
                }
            });
        }

        // Sidebar Toggle
        const menuToggle = document.getElementById('menu-toggle');
        const sidebarCollapseBtn = document.querySelector('.sidebar-collapse-btn');
        const sidebar = document.querySelector('[data-sidebar]');
        const mainContent = document.querySelector('[data-main-content]');
        const sidebarOverlay = document.querySelector('[data-sidebar-overlay]');

        if (menuToggle && sidebarOverlay && sidebar) {
            menuToggle.addEventListener('click', () => {
                sidebar.classList.toggle('show');
                sidebarOverlay.classList.toggle('show');
            });
            sidebarOverlay.addEventListener('click', () => {
                sidebar.classList.remove('show');
                sidebarOverlay.classList.remove('show');
            });
        }

        if (sidebarCollapseBtn && sidebar && mainContent) {
            sidebarCollapseBtn.addEventListener('click', () => {
                sidebar.classList.toggle('collapsed');
                mainContent.classList.toggle('sidebar-collapsed');
            });
        }
    });
    </script>
</body>
</html>
