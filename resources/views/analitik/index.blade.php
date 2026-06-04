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
    <div class="sidebar-overlay" data-sidebar-overlay></div>

    {{-- ===== SIDEBAR ===== --}}
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

    {{-- ===== TOP BAR ===== --}}
    <header class="topbar" data-topbar>
        <div class="topbar-left" style="display: flex; align-items: center; gap: 16px;">
            <button class="topbar-hamburger" id="menu-toggle" aria-label="Toggle Menu" data-sidebar-toggle style="margin-right: 0;">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width: 20px; height: 20px;">
                    <line x1="4" x2="20" y1="12" y2="12"/><line x1="4" x2="20" y1="6" y2="6"/><line x1="4" x2="14" y1="18" y2="18"/>
                </svg>
            </button>
            <div style="display: flex; flex-direction: column;">
                <span class="topbar-title" style="margin: 0; line-height: 1.2;">Dashboard & Analitik</span>
                <span style="font-size: 13px; color: #64748b; margin-top: 2px;">Pantau insight lintas modul, KPI strategis, dan performa desa secara real-time.</span>
            </div>
        </div>

        <div class="topbar-right" style="display: flex; align-items: center;">
            <div class="fin-page-actions" style="display: flex; gap: 12px; align-items: center; margin-right: 16px;">
                <div style="display: flex; background: #fff; border: 1px solid var(--border-color); border-radius: 8px; padding: 6px 12px; font-size: 13px; align-items: center; gap: 8px; color: var(--text-primary); cursor: pointer;">
                    <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                    1 - 31 Mei 2025
                    <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"></polyline></svg>
                </div>
                <div style="display: flex; background: #fff; border: 1px solid var(--border-color); border-radius: 8px; padding: 6px 12px; font-size: 13px; align-items: center; gap: 8px; color: var(--text-primary); cursor: pointer;">
                    <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
                    Semua Desa
                    <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"></polyline></svg>
                </div>
                <button class="fin-btn-outline" style="height: 34px; padding: 0 12px; font-size: 13px; background: white; border: 1px solid var(--border-color); border-radius: 8px; color: var(--primary); display: flex; align-items: center; gap: 6px; cursor: pointer;">
                    <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" x2="12" y1="15" y2="3"/></svg>
                    Export Data
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
