<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Potensi Desa | DesaHub</title>
    <meta name="description" content="Dashboard Potensi Desa DesaHub - Kelola dan pantau potensi utama desa secara terstruktur dan terintegrasi.">
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
        <div class="topbar-left">
            <button class="topbar-hamburger" id="menu-toggle" aria-label="Toggle Menu" data-sidebar-toggle style="margin-right: 0;">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width: 20px; height: 20px;">
                    <line x1="4" x2="20" y1="12" y2="12"/><line x1="4" x2="20" y1="6" y2="6"/><line x1="4" x2="14" y1="18" y2="18"/>
                </svg>
            </button>
            <span class="topbar-title">Potensi Desa</span>
        </div>

        <div class="topbar-right">
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
        <div class="main-inner fin-main-inner" style="padding-bottom: 10px;">

            {{-- â”€â”€ Header + Tabs + Actions â”€â”€ --}}
            <div class="fin-page-header" style="margin-bottom: 14px;">
                <div class="fin-page-header-top">
                    <div>
                        <h2 style="font-size: 18px; font-weight: 800; color: var(--sidebar-bg); margin: 0 0 2px 0;">Potensi Desa</h2>
                        <p class="fin-page-desc" style="font-size: 12px; margin: 0;">Kelola dan pantau potensi utama desa secara terstruktur dan terintegrasi.</p>
                    </div>
                    <div class="fin-page-actions" style="gap: 8px;">
                        <button class="fin-btn-primary" style="display: flex; align-items: center; gap: 5px; height: 34px; padding: 0 14px; font-size: 12px;">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width: 15px; height: 15px;"><line x1="12" x2="12" y1="5" y2="19"/><line x1="5" x2="19" y1="12" y2="12"/></svg>
                            Tambah Potensi
                            <svg viewBox="0 0 24 24" width="12" height="12" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"></polyline></svg>
                        </button>
                        <button class="fin-btn-outline" style="display: flex; align-items: center; gap: 5px; height: 34px; padding: 0 14px; font-size: 12px;">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width: 15px; height: 15px;"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" x2="12" y1="15" y2="3"/></svg>
                            Export Data
                            <svg viewBox="0 0 24 24" width="12" height="12" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"></polyline></svg>
                        </button>
                    </div>
                </div>

                {{-- Tabs --}}
                <div class="fin-tabs" style="margin-top: 10px;">
                    <button class="fin-tab active" data-tab="ringkasan">Ringkasan</button>
                    <button class="fin-tab" data-tab="pertanian">Pertanian</button>
                    <button class="fin-tab" data-tab="peternakan">Peternakan</button>
                    <button class="fin-tab" data-tab="perikanan">Perikanan</button>
                    <button class="fin-tab" data-tab="perkebunan">Perkebunan</button>
                    <button class="fin-tab" data-tab="umkm">UMKM</button>
                    <button class="fin-tab" data-tab="pariwisata">Pariwisata</button>
                </div>
            </div>

            {{-- â”€â”€ KPI Stat Cards â”€â”€ --}}
            <section class="fin-kpi-cards" style="grid-template-columns: repeat(6, 1fr); gap: 14px; margin-top: 0;">
                {{-- Lahan Pertanian --}}
                <article class="stat-card" style="border: 1px solid #e2e8f0; border-radius: 14px; padding: 14px 16px;">
                    <div class="stat-card-icon" style="background: #dcfce7; color: #16a34a; width: 40px; height: 40px; border-radius: 10px;">
                        <svg viewBox="0 0 24 24" fill="currentColor" width="20" height="20"><path d="M12 22c4.97 0 9-4.03 9-9-4.97 0-9 4.03-9 9zM5.6 10.25c0 1.38 1.12 2.5 2.5 2.5.53 0 1.01-.16 1.42-.44l-.02.19c0 1.38 1.12 2.5 2.5 2.5s2.5-1.12 2.5-2.5l-.02-.19c.4.28.89.44 1.42.44 1.38 0 2.5-1.12 2.5-2.5 0-1-.59-1.85-1.43-2.25.84-.4 1.43-1.25 1.43-2.25 0-1.38-1.12-2.5-2.5-2.5-.53 0-1.01.16-1.42.44l.02-.19C14.5 2.12 13.38 1 12 1S9.5 2.12 9.5 3.5l.02.19c-.4-.28-.89-.44-1.42-.44-1.38 0-2.5 1.12-2.5 2.5 0 1 .59 1.85 1.43 2.25-.84.4-1.43 1.25-1.43 2.25z"/></svg>
                    </div>
                    <div>
                        <p class="stat-card-label" style="font-weight: 600; color: #64748b; font-size: 11px; margin-bottom: 2px;">Lahan Pertanian</p>
                        <p class="stat-card-value" style="color: var(--sidebar-bg); font-size: 22px; font-weight: 800; line-height: 1.1;">120 <span style="font-size: 13px; font-weight: 600;">Ha</span></p>
                        <p class="stat-card-change" style="display: flex; align-items: center; gap: 3px; font-size: 11px; margin-top: 4px;">
                            <span style="color: #16a34a; display: flex; align-items: center; font-weight: 600; gap: 1px;">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="width: 12px; height: 12px;"><line x1="12" y1="19" x2="12" y2="5"/><polyline points="5 12 12 5 19 12"/></svg>
                                +8 Ha
                            </span>
                            <span style="color: #94a3b8;">dari bulan lalu</span>
                        </p>
                    </div>
                </article>

                {{-- Peternakan --}}
                <article class="stat-card" style="border: 1px solid #e2e8f0; border-radius: 14px; padding: 14px 16px;">
                    <div class="stat-card-icon" style="background: #ffedd5; color: #ea580c; width: 40px; height: 40px; border-radius: 10px;">
                        <svg viewBox="0 0 24 24" fill="currentColor" width="20" height="20"><path d="M20,8.5A2.5,2.5 0 0,1 17.5,11C16.42,11 15.5,10.31 15.16,9.36C14.72,9.75 14.14,10 13.5,10C12.94,10 12.42,9.81 12,9.5C11.58,9.81 11.07,10 10.5,10C9.86,10 9.28,9.75 8.84,9.36C8.5,10.31 7.58,11 6.5,11A2.5,2.5 0 0,1 4,8.5C4,7.26 4.91,6.23 6.1,6.04C6.04,5.87 6,5.69 6,5.5A1.5,1.5 0 0,1 7.5,4C7.7,4 7.89,4.04 8.06,4.11C8.23,3.47 8.81,3 9.5,3C9.75,3 10,3.07 10.18,3.17C10.5,2.5 11.19,2 12,2C12.81,2 13.5,2.5 13.82,3.17C14,3.07 14.25,3 14.5,3C15.19,3 15.77,3.47 15.94,4.11C16.11,4.04 16.3,4 16.5,4A1.5,1.5 0 0,1 18,5.5C18,5.69 17.96,5.87 17.9,6.04C19.09,6.23 20,7.26 20,8.5M10,12A1,1 0 0,0 9,13A1,1 0 0,0 10,14A1,1 0 0,0 11,13A1,1 0 0,0 10,12M14,12A1,1 0 0,0 13,13A1,1 0 0,0 14,14A1,1 0 0,0 15,13A1,1 0 0,0 14,12M20.23,10.66C19.59,11.47 18.61,12 17.5,12C17.05,12 16.62,11.9 16.21,11.73C16.2,14.28 15.83,17.36 14.45,18.95C13.93,19.54 13.3,19.86 12.5,19.96V18H11.5V19.96C10.7,19.86 10.07,19.55 9.55,18.95C8.16,17.35 7.79,14.29 7.78,11.74C7.38,11.9 6.95,12 6.5,12C5.39,12 4.41,11.47 3.77,10.66C2.88,11.55 2,12 2,12C2,12 3,14 5,14C5.36,14 5.64,13.96 5.88,13.91C6.22,17.73 7.58,22 12,22C16.42,22 17.78,17.73 18.12,13.91C18.36,13.96 18.64,14 19,14C21,14 22,12 22,12C22,12 21.12,11.55 20.23,10.66Z"/></svg>
                    </div>
                    <div>
                        <p class="stat-card-label" style="font-weight: 600; color: #64748b; font-size: 11px; margin-bottom: 2px;">Peternakan</p>
                        <p class="stat-card-value" style="color: var(--sidebar-bg); font-size: 22px; font-weight: 800; line-height: 1.1;">85 <span style="font-size: 13px; font-weight: 600;">Unit</span></p>
                        <p class="stat-card-change" style="display: flex; align-items: center; gap: 3px; font-size: 11px; margin-top: 4px;">
                            <span style="color: #16a34a; display: flex; align-items: center; font-weight: 600; gap: 1px;">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="width: 12px; height: 12px;"><line x1="12" y1="19" x2="12" y2="5"/><polyline points="5 12 12 5 19 12"/></svg>
                                +5 Unit
                            </span>
                            <span style="color: #94a3b8;">dari bulan lalu</span>
                        </p>
                    </div>
                </article>

                {{-- Perikanan --}}
                <article class="stat-card" style="border: 1px solid #e2e8f0; border-radius: 14px; padding: 14px 16px;">
                    <div class="stat-card-icon" style="background: #dbeafe; color: #2563eb; width: 40px; height: 40px; border-radius: 10px;">
                        <svg viewBox="0 0 24 24" fill="currentColor" width="20" height="20"><path d="M 12.0 20 L 11.24 17 C 14.5 16.79 17.41 15.4 18.25 13.58 C 18.34 14.06 18.47 14.5 18.67 14.83 C 19.33 16 20.67 16 22.0 16 C 20.9 16 20.5 14.43 20.5 12.5 C 20.5 10.57 20.9 9 22.0 9 C 20.67 9 19.33 9 18.67 10.17 C 18.47 10.5 18.34 10.94 18.25 11.42 C 17.6 10 15.68 8.85 13.34 8.32 L 15.0 5 C 13.0 5 11.0 5 9.67 5.67 C 8.54 6.23 7.89 7.27 7.31 8.38 C 4.39 9.08 2.0 10.66 2.0 12.5 C 2.0 14.38 4.5 16 7.5 16.66 C 8.33 17.76 9.14 18.78 9.83 19.33 C 10.67 20 11.33 20 12.0 20 M 7.0 11 A 1 1 0 0 1 8.0 12 A 1 1 0 0 1 7.0 13 A 1 1 0 0 1 6.0 12 A 1 1 0 0 1 7.0 11 Z"/></svg>
                    </div>
                    <div>
                        <p class="stat-card-label" style="font-weight: 600; color: #64748b; font-size: 11px; margin-bottom: 2px;">Perikanan</p>
                        <p class="stat-card-value" style="color: var(--sidebar-bg); font-size: 22px; font-weight: 800; line-height: 1.1;">40 <span style="font-size: 13px; font-weight: 600;">Unit</span></p>
                        <p class="stat-card-change" style="display: flex; align-items: center; gap: 3px; font-size: 11px; margin-top: 4px;">
                            <span style="color: #16a34a; display: flex; align-items: center; font-weight: 600; gap: 1px;">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="width: 12px; height: 12px;"><line x1="12" y1="19" x2="12" y2="5"/><polyline points="5 12 12 5 19 12"/></svg>
                                +3 Unit
                            </span>
                            <span style="color: #94a3b8;">dari bulan lalu</span>
                        </p>
                    </div>
                </article>

                {{-- Perkebunan --}}
                <article class="stat-card" style="border: 1px solid #e2e8f0; border-radius: 14px; padding: 14px 16px;">
                    <div class="stat-card-icon" style="background: #fef3c7; color: #d97706; width: 40px; height: 40px; border-radius: 10px;">
                        <svg viewBox="0 0 24 24" fill="currentColor" width="20" height="20"><path d="M17 8C8 10 5.9 16.17 3.82 21.34l1.89.66.95-2.3c.48.17.98.3 1.34.3C19 20 22 3 22 3c-1 2-8 2.25-13 3.25S2 11.5 2 13.5s1.75 3.75 1.75 3.75C7 8 17 8 17 8z"/></svg>
                    </div>
                    <div>
                        <p class="stat-card-label" style="font-weight: 600; color: #64748b; font-size: 11px; margin-bottom: 2px;">Perkebunan</p>
                        <p class="stat-card-value" style="color: var(--sidebar-bg); font-size: 22px; font-weight: 800; line-height: 1.1;">60 <span style="font-size: 13px; font-weight: 600;">Ha</span></p>
                        <p class="stat-card-change" style="display: flex; align-items: center; gap: 3px; font-size: 11px; margin-top: 4px;">
                            <span style="color: #16a34a; display: flex; align-items: center; font-weight: 600; gap: 1px;">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="width: 12px; height: 12px;"><line x1="12" y1="19" x2="12" y2="5"/><polyline points="5 12 12 5 19 12"/></svg>
                                +4 Ha
                            </span>
                            <span style="color: #94a3b8;">dari bulan lalu</span>
                        </p>
                    </div>
                </article>

                {{-- UMKM --}}
                <article class="stat-card" style="border: 1px solid #e2e8f0; border-radius: 14px; padding: 14px 16px;">
                    <div class="stat-card-icon" style="background: #f3e8ff; color: #7e22ce; width: 40px; height: 40px; border-radius: 10px;">
                        <svg viewBox="0 0 24 24" fill="currentColor" width="20" height="20"><path d="M20 4H4v2h16V4zm1 10v-2l-1-5H4l-1 5v2h1v6h10v-6h4v6h2v-6h1zm-9 4H6v-4h6v4z"/></svg>
                    </div>
                    <div>
                        <p class="stat-card-label" style="font-weight: 600; color: #64748b; font-size: 11px; margin-bottom: 2px;">UMKM</p>
                        <p class="stat-card-value" style="color: var(--sidebar-bg); font-size: 22px; font-weight: 800; line-height: 1.1;">128 <span style="font-size: 13px; font-weight: 600;">Unit</span></p>
                        <p class="stat-card-change" style="display: flex; align-items: center; gap: 3px; font-size: 11px; margin-top: 4px;">
                            <span style="color: #16a34a; display: flex; align-items: center; font-weight: 600; gap: 1px;">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="width: 12px; height: 12px;"><line x1="12" y1="19" x2="12" y2="5"/><polyline points="5 12 12 5 19 12"/></svg>
                                +10 Unit
                            </span>
                            <span style="color: #94a3b8;">dari bulan lalu</span>
                        </p>
                    </div>
                </article>

                {{-- Pariwisata --}}
                <article class="stat-card" style="border: 1px solid #e2e8f0; border-radius: 14px; padding: 14px 16px;">
                    <div class="stat-card-icon" style="background: #fce7f3; color: #db2777; width: 40px; height: 40px; border-radius: 10px;">
                        <svg viewBox="0 0 24 24" fill="currentColor" width="20" height="20"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7z"/></svg>
                    </div>
                    <div>
                        <p class="stat-card-label" style="font-weight: 600; color: #64748b; font-size: 11px; margin-bottom: 2px;">Pariwisata</p>
                        <p class="stat-card-value" style="color: var(--sidebar-bg); font-size: 22px; font-weight: 800; line-height: 1.1;">12 <span style="font-size: 13px; font-weight: 600;">Lokasi</span></p>
                        <p class="stat-card-change" style="display: flex; align-items: center; gap: 3px; font-size: 11px; margin-top: 4px;">
                            <span style="color: #16a34a; display: flex; align-items: center; font-weight: 600; gap: 1px;">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="width: 12px; height: 12px;"><line x1="12" y1="19" x2="12" y2="5"/><polyline points="5 12 12 5 19 12"/></svg>
                                +1 Lokasi
                            </span>
                            <span style="color: #94a3b8;">dari bulan lalu</span>
                        </p>
                    </div>
                </article>
            </section>

            {{-- â”€â”€ Row: Peta + Komposisi â”€â”€ --}}
            <section style="display: grid; grid-template-columns: 1.7fr 1fr; gap: 14px; margin-top: 14px;">
                {{-- Peta Sebaran Potensi --}}
                <div style="background: white; border: 1px solid #e2e8f0; border-radius: 14px; padding: 16px;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
                        <h3 style="font-size: 13px; font-weight: 700; color: var(--sidebar-bg); margin: 0;">Peta Sebaran Potensi</h3>
                        <a href="#" style="font-size: 11px; color: var(--primary); font-weight: 600; text-decoration: none;">Lihat Peta Lengkap</a>
                    </div>
                    {{-- Enhanced Mock Map --}}
                    <div style="position: relative; height: 300px; border-radius: 10px; overflow: hidden; background: #e2e8f0;">
                        <img src="{{ asset('assets/desahub/peta potensi desa.png') }}" alt="Peta Sebaran Potensi" style="width: 100%; height: 100%; object-fit: cover; object-position: center;">
                    </div>
                </div>

                {{-- Komposisi Potensi Desa --}}
                <div style="background: white; border: 1px solid #e2e8f0; border-radius: 14px; padding: 16px; display: flex; flex-direction: column;">
                    <h3 style="font-size: 13px; font-weight: 700; color: var(--sidebar-bg); margin: 0 0 8px 0;">Komposisi Potensi Desa</h3>
                    <div style="flex: 1;">
                        @php
                            $komposisi = [
                                ['color' => '#16a34a', 'icon' => 'M12 22c4.97 0 9-4.03 9-9-4.97 0-9 4.03-9 9zM5.6 10.25c0 1.38 1.12 2.5 2.5 2.5.53 0 1.01-.16 1.42-.44l-.02.19c0 1.38 1.12 2.5 2.5 2.5s2.5-1.12 2.5-2.5l-.02-.19c.4.28.89.44 1.42.44 1.38 0 2.5-1.12 2.5-2.5 0-1-.59-1.85-1.43-2.25.84-.4 1.43-1.25 1.43-2.25 0-1.38-1.12-2.5-2.5-2.5-.53 0-1.01.16-1.42.44l.02-.19C14.5 2.12 13.38 1 12 1S9.5 2.12 9.5 3.5l.02.19c-.4-.28-.89-.44-1.42-.44-1.38 0-2.5 1.12-2.5 2.5 0 1 .59 1.85 1.43 2.25-.84.4-1.43 1.25-1.43 2.25z', 'label' => 'Pertanian', 'value' => '120', 'unit' => 'Ha'],
                                ['color' => '#ea580c', 'icon' => 'M20,8.5A2.5,2.5 0 0,1 17.5,11C16.42,11 15.5,10.31 15.16,9.36C14.72,9.75 14.14,10 13.5,10C12.94,10 12.42,9.81 12,9.5C11.58,9.81 11.07,10 10.5,10C9.86,10 9.28,9.75 8.84,9.36C8.5,10.31 7.58,11 6.5,11A2.5,2.5 0 0,1 4,8.5C4,7.26 4.91,6.23 6.1,6.04C6.04,5.87 6,5.69 6,5.5A1.5,1.5 0 0,1 7.5,4C7.7,4 7.89,4.04 8.06,4.11C8.23,3.47 8.81,3 9.5,3C9.75,3 10,3.07 10.18,3.17C10.5,2.5 11.19,2 12,2C12.81,2 13.5,2.5 13.82,3.17C14,3.07 14.25,3 14.5,3C15.19,3 15.77,3.47 15.94,4.11C16.11,4.04 16.3,4 16.5,4A1.5,1.5 0 0,1 18,5.5C18,5.69 17.96,5.87 17.9,6.04C19.09,6.23 20,7.26 20,8.5M10,12A1,1 0 0,0 9,13A1,1 0 0,0 10,14A1,1 0 0,0 11,13A1,1 0 0,0 10,12M14,12A1,1 0 0,0 13,13A1,1 0 0,0 14,14A1,1 0 0,0 15,13A1,1 0 0,0 14,12M20.23,10.66C19.59,11.47 18.61,12 17.5,12C17.05,12 16.62,11.9 16.21,11.73C16.2,14.28 15.83,17.36 14.45,18.95C13.93,19.54 13.3,19.86 12.5,19.96V18H11.5V19.96C10.7,19.86 10.07,19.55 9.55,18.95C8.16,17.35 7.79,14.29 7.78,11.74C7.38,11.9 6.95,12 6.5,12C5.39,12 4.41,11.47 3.77,10.66C2.88,11.55 2,12 2,12C2,12 3,14 5,14C5.36,14 5.64,13.96 5.88,13.91C6.22,17.73 7.58,22 12,22C16.42,22 17.78,17.73 18.12,13.91C18.36,13.96 18.64,14 19,14C21,14 22,12 22,12C22,12 21.12,11.55 20.23,10.66Z', 'label' => 'Peternakan', 'value' => '85', 'unit' => 'Unit'],
                                ['color' => '#2563eb', 'icon' => 'M 12.0 20 L 11.24 17 C 14.5 16.79 17.41 15.4 18.25 13.58 C 18.34 14.06 18.47 14.5 18.67 14.83 C 19.33 16 20.67 16 22.0 16 C 20.9 16 20.5 14.43 20.5 12.5 C 20.5 10.57 20.9 9 22.0 9 C 20.67 9 19.33 9 18.67 10.17 C 18.47 10.5 18.34 10.94 18.25 11.42 C 17.6 10 15.68 8.85 13.34 8.32 L 15.0 5 C 13.0 5 11.0 5 9.67 5.67 C 8.54 6.23 7.89 7.27 7.31 8.38 C 4.39 9.08 2.0 10.66 2.0 12.5 C 2.0 14.38 4.5 16 7.5 16.66 C 8.33 17.76 9.14 18.78 9.83 19.33 C 10.67 20 11.33 20 12.0 20 M 7.0 11 A 1 1 0 0 1 8.0 12 A 1 1 0 0 1 7.0 13 A 1 1 0 0 1 6.0 12 A 1 1 0 0 1 7.0 11 Z', 'label' => 'Perikanan', 'value' => '40', 'unit' => 'Unit'],
                                ['color' => '#d97706', 'icon' => 'M17 8C8 10 5.9 16.17 3.82 21.34l1.89.66.95-2.3c.48.17.98.3 1.34.3C19 20 22 3 22 3c-1 2-8 2.25-13 3.25S2 11.5 2 13.5s1.75 3.75 1.75 3.75C7 8 17 8 17 8z', 'label' => 'Perkebunan', 'value' => '60', 'unit' => 'Ha'],
                                ['color' => '#7e22ce', 'icon' => 'M20 4H4v2h16V4zm1 10v-2l-1-5H4l-1 5v2h1v6h10v-6h4v6h2v-6h1zm-9 4H6v-4h6v4z', 'label' => 'UMKM', 'value' => '128', 'unit' => 'Unit'],
                                ['color' => '#db2777', 'icon' => 'M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7z', 'label' => 'Pariwisata', 'value' => '12', 'unit' => 'Lokasi'],
                            ];
                        @endphp
                        @foreach($komposisi as $i => $item)
                        <div style="display: flex; align-items: center; justify-content: space-between; padding: 10px 0; {{ $i < count($komposisi)-1 ? 'border-bottom: 1px solid #f1f5f9;' : '' }}">
                            <div style="display: flex; align-items: center; gap: 10px;">
                                <div style="width: 28px; height: 28px; border-radius: 6px; background: {{ $item['color'] }}15; display: flex; align-items: center; justify-content: center;">
                                    <svg viewBox="0 0 24 24" fill="{{ $item['color'] }}" width="14" height="14"><path d="{{ $item['icon'] }}"/></svg>
                                </div>
                                <span style="font-size: 12.5px; font-weight: 500; color: var(--sidebar-bg);">{{ $item['label'] }}</span>
                            </div>
                            <span style="font-size: 12.5px; font-weight: 700; color: var(--sidebar-bg);">{{ $item['value'] }} <span style="font-weight: 500; color: #94a3b8;">{{ $item['unit'] }}</span></span>
                        </div>
                        @endforeach
                    </div>
                    <div style="text-align: center; margin-top: 10px; padding-top: 10px; border-top: 1px solid #f1f5f9;">
                        <a href="#" style="font-size: 11.5px; color: var(--primary); font-weight: 600; text-decoration: none;">Lihat Detail Komposisi</a>
                    </div>
                </div>
            </section>

            {{-- â”€â”€ Row: Top Potensi Unggulan + Analitik Potensi â”€â”€ --}}
            <section style="display: grid; grid-template-columns: 1.15fr 1fr; gap: 14px; margin-top: 14px;">
                {{-- Top Potensi Unggulan --}}
                <div style="background: white; border: 1px solid #e2e8f0; border-radius: 14px; padding: 16px; display: flex; flex-direction: column;">
                    <h3 style="font-size: 13px; font-weight: 700; color: var(--sidebar-bg); margin: 0 0 12px 0;">Top Potensi Unggulan</h3>
                    <div style="overflow-x: auto; flex: 1;">
                        <table style="width: 100%; border-collapse: collapse; font-size: 11.5px;">
                            <thead>
                                <tr>
                                    <th style="padding: 8px 10px; text-align: left; font-weight: 700; color: #64748b; border-bottom: 1px solid #e2e8f0; font-size: 10px; letter-spacing: 0.3px;">#</th>
                                    <th style="padding: 8px 10px; text-align: left; font-weight: 700; color: #64748b; border-bottom: 1px solid #e2e8f0; font-size: 10px; letter-spacing: 0.3px;">Potensi Unggulan</th>
                                    <th style="padding: 8px 10px; text-align: left; font-weight: 700; color: #64748b; border-bottom: 1px solid #e2e8f0; font-size: 10px; letter-spacing: 0.3px;">Kategori</th>
                                    <th style="padding: 8px 10px; text-align: left; font-weight: 700; color: #64748b; border-bottom: 1px solid #e2e8f0; font-size: 10px; letter-spacing: 0.3px;">Lokasi</th>
                                    <th style="padding: 8px 10px; text-align: left; font-weight: 700; color: #64748b; border-bottom: 1px solid #e2e8f0; font-size: 10px; letter-spacing: 0.3px;">Luas / Jumlah</th>
                                    <th style="padding: 8px 10px; text-align: left; font-weight: 700; color: #64748b; border-bottom: 1px solid #e2e8f0; font-size: 10px; letter-spacing: 0.3px;">Status</th>
                                    <th style="padding: 8px 10px; text-align: left; font-weight: 700; color: #64748b; border-bottom: 1px solid #e2e8f0; font-size: 10px; letter-spacing: 0.3px;">Potensi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $potensi = [
                                        ['no' => 1, 'name' => 'Padi Organik', 'kat' => 'Pertanian', 'loc' => 'Dusun Sukamaju', 'size' => '45 Ha', 'status' => 'Aktif', 'stars' => 5],
                                        ['no' => 2, 'name' => 'Peternakan Sapi', 'kat' => 'Peternakan', 'loc' => 'Dusun Mekarjaya', 'size' => '35 Unit', 'status' => 'Aktif', 'stars' => 4],
                                        ['no' => 3, 'name' => 'Budidaya Lele', 'kat' => 'Perikanan', 'loc' => 'Dusun Cibeureum', 'size' => '20 Unit', 'status' => 'Aktif', 'stars' => 4],
                                        ['no' => 4, 'name' => 'Kebun Kopi', 'kat' => 'Perkebunan', 'loc' => 'Dusun Sukaresmi', 'size' => '30 Ha', 'status' => 'Berkembang', 'stars' => 4],
                                        ['no' => 5, 'name' => 'Keripik Pisang', 'kat' => 'UMKM', 'loc' => 'Desa Sukamaju', 'size' => '18 Unit', 'status' => 'Aktif', 'stars' => 4],
                                        ['no' => 6, 'name' => 'Curug Sukamaju', 'kat' => 'Pariwisata', 'loc' => 'Dusun Sukamaju', 'size' => '1 Lokasi', 'status' => 'Berkembang', 'stars' => 4],
                                    ];
                                @endphp
                                @foreach($potensi as $row)
                                <tr style="border-bottom: 1px solid #f1f5f9;">
                                    <td style="padding: 8px 10px; color: #94a3b8;">{{ $row['no'] }}</td>
                                    <td style="padding: 8px 10px; font-weight: 600; color: var(--sidebar-bg);">{{ $row['name'] }}</td>
                                    <td style="padding: 8px 10px; color: #64748b;">{{ $row['kat'] }}</td>
                                    <td style="padding: 8px 10px; color: #64748b;">{{ $row['loc'] }}</td>
                                    <td style="padding: 8px 10px; font-weight: 600; color: var(--sidebar-bg);">{{ $row['size'] }}</td>
                                    <td style="padding: 8px 10px;">
                                        @if($row['status'] === 'Aktif')
                                        <span style="background: #dcfce7; color: #16a34a; padding: 2px 10px; border-radius: 20px; font-size: 10px; font-weight: 600;">Aktif</span>
                                        @else
                                        <span style="background: #ffedd5; color: #ea580c; padding: 2px 10px; border-radius: 20px; font-size: 10px; font-weight: 600;">Berkembang</span>
                                        @endif
                                    </td>
                                    <td style="padding: 8px 10px; font-size: 12px; letter-spacing: -1px;">
                                        <span style="color: #16a34a;">@for($s=0;$s<$row['stars'];$s++)&#9733;@endfor</span><span style="color: #e2e8f0;">@for($s=$row['stars'];$s<5;$s++)&#9733;@endfor</span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div style="text-align: center; margin-top: 10px; padding-top: 10px; border-top: 1px solid #f1f5f9;">
                        <a href="#" style="font-size: 11.5px; color: var(--primary); font-weight: 600; text-decoration: none;">Lihat Semua Potensi</a>
                    </div>
                </div>

                {{-- Analitik Potensi --}}
                <div style="background: white; border: 1px solid #e2e8f0; border-radius: 14px; padding: 16px; display: flex; flex-direction: column;">
                    <h3 style="font-size: 13px; font-weight: 700; color: var(--sidebar-bg); margin: 0 0 12px 0;">Analitik Potensi</h3>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 14px; flex: 1;">
                        {{-- Left: Doughnut Chart --}}
                        <div style="border-right: 1px solid #f1f5f9; padding-right: 14px;">
                            <p style="font-size: 11px; font-weight: 700; color: var(--sidebar-bg); margin: 0 0 10px 0;">Komposisi Berdasarkan Kategori</p>
                            <div style="display: flex; align-items: center; gap: 16px;">
                                {{-- Donut Chart --}}
                                <div style="width: 130px; flex-shrink: 0;">
                                    <canvas id="potentialCategoryChart"></canvas>
                                </div>
                                {{-- Legend beside chart --}}
                                <div style="font-size: 10.5px; flex: 1;">
                                    <div style="display: flex; align-items: center; gap: 6px; margin-bottom: 6px;"><div style="width: 8px; height: 8px; border-radius: 50%; background: #16a34a; flex-shrink: 0;"></div><span style="color: var(--sidebar-bg); font-weight: 500;">Pertanian</span><span style="color: #64748b; margin-left: auto; white-space: nowrap;">32% (120 Ha)</span></div>
                                    <div style="display: flex; align-items: center; gap: 6px; margin-bottom: 6px;"><div style="width: 8px; height: 8px; border-radius: 50%; background: #ea580c; flex-shrink: 0;"></div><span style="color: var(--sidebar-bg); font-weight: 500;">Peternakan</span><span style="color: #64748b; margin-left: auto; white-space: nowrap;">23% (85 Unit)</span></div>
                                    <div style="display: flex; align-items: center; gap: 6px; margin-bottom: 6px;"><div style="width: 8px; height: 8px; border-radius: 50%; background: #2563eb; flex-shrink: 0;"></div><span style="color: var(--sidebar-bg); font-weight: 500;">Perikanan</span><span style="color: #64748b; margin-left: auto; white-space: nowrap;">11% (40 Unit)</span></div>
                                    <div style="display: flex; align-items: center; gap: 6px; margin-bottom: 6px;"><div style="width: 8px; height: 8px; border-radius: 50%; background: #d97706; flex-shrink: 0;"></div><span style="color: var(--sidebar-bg); font-weight: 500;">Perkebunan</span><span style="color: #64748b; margin-left: auto; white-space: nowrap;">16% (60 Ha)</span></div>
                                    <div style="display: flex; align-items: center; gap: 6px; margin-bottom: 6px;"><div style="width: 8px; height: 8px; border-radius: 50%; background: #7e22ce; flex-shrink: 0;"></div><span style="color: var(--sidebar-bg); font-weight: 500;">UMKM</span><span style="color: #64748b; margin-left: auto; white-space: nowrap;">13% (128 Unit)</span></div>
                                    <div style="display: flex; align-items: center; gap: 6px;"><div style="width: 8px; height: 8px; border-radius: 50%; background: #db2777; flex-shrink: 0;"></div><span style="color: var(--sidebar-bg); font-weight: 500;">Pariwisata</span><span style="color: #64748b; margin-left: auto; white-space: nowrap;">5% (12 Lokasi)</span></div>
                                </div>
                            </div>
                        </div>

                        {{-- Right: Insight Utama --}}
                        <div>
                            <p style="font-size: 11px; font-weight: 700; color: var(--sidebar-bg); margin: 0 0 10px 0;">Insight Utama</p>
                            <div style="background: linear-gradient(135deg, #f0fdf4 0%, #ecfdf5 100%); border: 1px solid #bbf7d0; border-radius: 10px; padding: 14px;">
                                <div style="display: flex; align-items: flex-start; gap: 8px; margin-bottom: 8px;">
                                    <div style="width: 28px; height: 28px; background: #dcfce7; border-radius: 7px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="#16a34a" stroke-width="2" width="15" height="15"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/><polyline points="17 6 23 6 23 12"/></svg>
                                    </div>
                                    <p style="font-size: 12.5px; font-weight: 700; color: var(--sidebar-bg); margin: 0; line-height: 1.3;">Sektor Pertanian menjadi potensi utama</p>
                                </div>
                                <p style="font-size: 11px; color: #475569; line-height: 1.6; margin: 0;">
                                    Kontribusi terbesar adalah Pertanian (32%), diikuti Peternakan (23%) dan Perkebunan (16%). Fokus pengembangan pada hilirisasi produk dan perluasan pasar akan meningkatkan nilai ekonomi desa.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            {{-- Footer --}}
            <footer class="dashboard-footer" style="padding: 16px 0 10px; display: flex; align-items: center; justify-content: space-between; border-top: 1px solid var(--border-color); margin-top: 16px;">
                <div style="display: flex; align-items: center; gap: 40px;">
                    <div style="font-size: 11px; color: var(--text-secondary);">
                        <span style="font-weight: 600; color: var(--sidebar-bg);">DesaHub</span> &mdash; Ekosistem Ekonomi Desa
                    </div>
                    <div style="font-size: 11px; color: var(--text-secondary);">
                        &copy; 2025 DesaHub. All rights reserved.
                    </div>
                </div>

                <div style="display: flex; align-items: center; gap: 16px;">
                    <div style="text-align: right; font-size: 10px; line-height: 1.4;">
                        <div style="font-weight: 600; color: var(--sidebar-bg);">Nakala Digital &times; Romulus Digital</div>
                        <div style="color: var(--text-secondary);">Strategic Partner &mdash; Singapore/Vietnam</div>
                    </div>
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <img src="{{ asset('assets/desahub/logo-nakala-transparent.png') }}" alt="Nakala Digital" style="height: 36px; width: auto; object-fit: contain;">
                        <img src="{{ asset('assets/desahub/logo-romulus-biru.png') }}" alt="Romulus Digital" style="height: 36px; width: auto; object-fit: contain;">
                    </div>
                </div>
            </footer>

        </div>
    </main>

    {{-- ===== CHART & INTERACTION SCRIPTS ===== --}}
    <script>
    document.addEventListener('DOMContentLoaded', () => {
        // â”€â”€ Potensi Category Doughnut Chart â”€â”€
        const potCtx = document.getElementById('potentialCategoryChart')?.getContext('2d');
        if (potCtx) {
            new Chart(potCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Pertanian', 'Peternakan', 'Perikanan', 'Perkebunan', 'UMKM', 'Pariwisata'],
                    datasets: [{
                        data: [32, 23, 11, 16, 13, 5],
                        backgroundColor: ['#16a34a', '#ea580c', '#2563eb', '#d97706', '#7e22ce', '#db2777'],
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
                            titleFont: { family: 'Inter', size: 11 },
                            bodyFont: { family: 'Inter', size: 11 },
                            padding: 8,
                            cornerRadius: 6,
                            callbacks: {
                                label: function(context) {
                                    return context.label + ': ' + context.parsed + '%';
                                }
                            }
                        }
                    }
                }
            });
        }

        // â”€â”€ User dropdown toggle â”€â”€
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
