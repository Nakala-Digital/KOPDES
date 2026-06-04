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
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="20" height="20"><path d="M7 20h10"/><path d="M10 20c5.5-2.5.8-6.4 3-10"/><path d="M9.5 9.4c1.1.8 1.8 2.2 2.3 3.7-2 .4-3.5.4-4.8-.3-1.2-.6-2.3-1.9-3-4.2 2.8-.5 4.4 0 5.5.8z"/><path d="M14.1 6a7 7 0 0 0-1.1 4c1.9-.1 3.3-.6 4.3-1.4 1-1 1.6-2.3 1.7-4.6-2.7.1-4 1-4.9 2z"/></svg>
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
                        <svg viewBox="0 0 24 24" fill="currentColor" width="20" height="20"><path d="M22 10c0-1-1-2-2-2h-1V6c0-.6-.4-1-1-1s-1 .4-1 1v1c-1-.6-2-1-4-1s-3 .4-4 1V6c0-.6-.4-1-1-1s-1 .4-1 1v2H6c-1 0-2 1-2 2l-1 1v3h1v2c0 .6.4 1 1 1s1-.4 1-1v-2h12v2c0 .6.4 1 1 1s1-.4 1-1v-2h1v-3l-1-1zM7 11.5c-.6 0-1-.4-1-1s.4-1 1-1 1 .4 1 1-.4 1-1 1z"/></svg>
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
                        <svg viewBox="0 0 24 24" fill="currentColor" width="20" height="20"><path d="M2 12c2-2 4-3 4-3l2 2c1-1 3-2 5-2h3c2 0 3 .5 4 1l2-2v8l-2-2c-1 .5-2 1-4 1h-3c-2 0-4-1-5-2l-2 2s-2-1-4-3zm6 0c.6 0 1-.4 1-1s-.4-1-1-1-1 .4-1 1 .4 1 1 1z"/></svg>
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
                    <div class="stat-card-icon" style="background: #dcfce7; color: #16a34a; width: 40px; height: 40px; border-radius: 10px;">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="20" height="20"><path d="M12 22V8"/><path d="M5 12H2a10 10 0 0 1 20 0h-3"/><path d="M8 12a4 4 0 0 1 8 0"/><path d="M12 8a6 6 0 0 0-6 6"/><path d="M12 8a6 6 0 0 1 6 6"/><circle cx="12" cy="5" r="2"/></svg>
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
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="20" height="20"><path d="m2 7 4.41-4.41A2 2 0 0 1 7.83 2h8.34a2 2 0 0 1 1.42.59L22 7"/><path d="M4 12v8a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-8"/><path d="M15 22v-4a2 2 0 0 0-2-2h-2a2 2 0 0 0-2 2v4"/><path d="M2 7h20"/><path d="M22 7v3a2 2 0 0 1-2 2 2.7 2.7 0 0 1-1.59-.63.7.7 0 0 0-.82 0A2.7 2.7 0 0 1 16 12a2.7 2.7 0 0 1-1.59-.63.7.7 0 0 0-.82 0A2.7 2.7 0 0 1 12 12a2.7 2.7 0 0 1-1.59-.63.7.7 0 0 0-.82 0A2.7 2.7 0 0 1 8 12a2.7 2.7 0 0 1-1.59-.63.7.7 0 0 0-.82 0A2.7 2.7 0 0 1 4 12a2 2 0 0 1-2-2V7"/></svg>
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
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="20" height="20"><path d="m8 3 4 8 5-5 5 15H2L8 3z"/><path d="M4.14 15.08c2.62-1.57 5.24-1.43 7.86.42 2.74 1.94 5.49 2 8.23.19"/></svg>
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
                    <div style="position: relative; height: 300px; background: linear-gradient(160deg, #4a8c5c 0%, #5a9e6a 15%, #6aaf78 30%, #5a9e6a 50%, #4a8c5c 70%, #5a9e6a 85%, #6aaf78 100%); border-radius: 10px; overflow: hidden;">
                        {{-- Terrain texture overlay --}}
                        <div style="position: absolute; inset: 0; background: radial-gradient(ellipse at 20% 30%, rgba(255,255,255,0.08) 0%, transparent 50%), radial-gradient(ellipse at 70% 60%, rgba(255,255,255,0.06) 0%, transparent 40%), radial-gradient(ellipse at 50% 80%, rgba(0,0,0,0.05) 0%, transparent 40%);"></div>
                        {{-- Field patches --}}
                        <div style="position: absolute; top: 8%; left: 5%; width: 70px; height: 45px; background: rgba(139,195,74,0.25); border-radius: 4px; border: 1px solid rgba(139,195,74,0.3); transform: rotate(-3deg);"></div>
                        <div style="position: absolute; top: 65%; right: 10%; width: 85px; height: 35px; background: rgba(139,195,74,0.2); border-radius: 4px; border: 1px solid rgba(139,195,74,0.25); transform: rotate(2deg);"></div>
                        <div style="position: absolute; top: 40%; left: 60%; width: 55px; height: 40px; background: rgba(255,235,59,0.12); border-radius: 4px; border: 1px solid rgba(255,235,59,0.15); transform: rotate(-5deg);"></div>
                        <div style="position: absolute; top: 75%; left: 25%; width: 60px; height: 30px; background: rgba(139,195,74,0.2); border-radius: 4px; border: 1px solid rgba(139,195,74,0.25);"></div>
                        {{-- Roads --}}
                        <svg style="position: absolute; inset: 0; width: 100%; height: 100%; pointer-events: none;" viewBox="0 0 700 300">
                            <path d="M 0 130 Q 120 120, 250 150 Q 380 180, 500 130 Q 620 80, 700 120" fill="none" stroke="rgba(255,255,255,0.35)" stroke-width="3"/>
                            <path d="M 200 0 Q 220 60, 250 120 Q 270 160, 250 200 Q 230 250, 260 300" fill="none" stroke="rgba(255,255,255,0.25)" stroke-width="2.5"/>
                            <path d="M 450 0 Q 470 80, 500 130 Q 510 160, 480 220 Q 460 260, 490 300" fill="none" stroke="rgba(255,255,255,0.2)" stroke-width="2"/>
                            <path d="M 0 230 Q 150 210, 300 240 Q 450 270, 600 230 Q 650 220, 700 240" fill="none" stroke="rgba(255,255,255,0.2)" stroke-width="2"/>
                        </svg>
                        {{-- River --}}
                        <svg style="position: absolute; inset: 0; width: 100%; height: 100%; pointer-events: none;" viewBox="0 0 700 300">
                            <path d="M 0 190 Q 80 170, 170 200 Q 280 240, 380 190 Q 480 140, 580 170 Q 640 190, 700 175" fill="none" stroke="rgba(66,165,245,0.55)" stroke-width="7" stroke-linecap="round"/>
                            <path d="M 0 193 Q 80 173, 170 203 Q 280 243, 380 193 Q 480 143, 580 173 Q 640 193, 700 178" fill="none" stroke="rgba(66,165,245,0.25)" stroke-width="12" stroke-linecap="round"/>
                            <path d="M 130 0 Q 160 60, 170 120 Q 175 160, 170 200" fill="none" stroke="rgba(66,165,245,0.35)" stroke-width="4" stroke-linecap="round"/>
                        </svg>

                        {{-- Marker: Dusun Sukamaju --}}
                        <div style="position: absolute; top: 20%; left: 15%;">
                            <div style="width: 16px; height: 16px; border-radius: 50%; background: #16a34a; border: 2.5px solid white; box-shadow: 0 2px 8px rgba(0,0,0,0.35);"></div>
                            <div style="position: absolute; top: -24px; left: 50%; transform: translateX(-50%); background: rgba(255,255,255,0.95); padding: 2px 8px; border-radius: 4px; font-size: 9.5px; font-weight: 700; color: var(--sidebar-bg); white-space: nowrap; box-shadow: 0 1px 4px rgba(0,0,0,0.15);">Dusun Sukamaju</div>
                        </div>
                        {{-- Marker: Dusun Mekarjaya --}}
                        <div style="position: absolute; top: 25%; right: 22%;">
                            <div style="width: 16px; height: 16px; border-radius: 50%; background: #ea580c; border: 2.5px solid white; box-shadow: 0 2px 8px rgba(0,0,0,0.35);"></div>
                            <div style="position: absolute; top: -24px; left: 50%; transform: translateX(-50%); background: rgba(255,255,255,0.95); padding: 2px 8px; border-radius: 4px; font-size: 9.5px; font-weight: 700; color: var(--sidebar-bg); white-space: nowrap; box-shadow: 0 1px 4px rgba(0,0,0,0.15);">Dusun Mekarjaya</div>
                        </div>
                        {{-- Marker: Dusun Cibeureum --}}
                        <div style="position: absolute; top: 58%; left: 32%;">
                            <div style="width: 16px; height: 16px; border-radius: 50%; background: #2563eb; border: 2.5px solid white; box-shadow: 0 2px 8px rgba(0,0,0,0.35);"></div>
                            <div style="position: absolute; top: -24px; left: 50%; transform: translateX(-50%); background: rgba(255,255,255,0.95); padding: 2px 8px; border-radius: 4px; font-size: 9.5px; font-weight: 700; color: var(--sidebar-bg); white-space: nowrap; box-shadow: 0 1px 4px rgba(0,0,0,0.15);">Dusun Cibeureum</div>
                        </div>
                        {{-- Marker: Dusun Sukaresmi --}}
                        <div style="position: absolute; top: 42%; right: 10%;">
                            <div style="width: 16px; height: 16px; border-radius: 50%; background: #7e22ce; border: 2.5px solid white; box-shadow: 0 2px 8px rgba(0,0,0,0.35);"></div>
                            <div style="position: absolute; top: -24px; left: 50%; transform: translateX(-50%); background: rgba(255,255,255,0.95); padding: 2px 8px; border-radius: 4px; font-size: 9.5px; font-weight: 700; color: var(--sidebar-bg); white-space: nowrap; box-shadow: 0 1px 4px rgba(0,0,0,0.15);">Dusun Sukaresmi</div>
                        </div>
                        {{-- Extra scattered markers --}}
                        <div style="position: absolute; top: 70%; left: 52%;"><div style="width: 10px; height: 10px; border-radius: 50%; background: #d97706; border: 2px solid white; box-shadow: 0 1px 4px rgba(0,0,0,0.25);"></div></div>
                        <div style="position: absolute; top: 32%; left: 50%;"><div style="width: 10px; height: 10px; border-radius: 50%; background: #db2777; border: 2px solid white; box-shadow: 0 1px 4px rgba(0,0,0,0.25);"></div></div>
                        <div style="position: absolute; top: 48%; left: 6%;"><div style="width: 10px; height: 10px; border-radius: 50%; background: #16a34a; border: 2px solid white; box-shadow: 0 1px 4px rgba(0,0,0,0.25);"></div></div>
                        <div style="position: absolute; top: 15%; left: 42%;"><div style="width: 9px; height: 9px; border-radius: 50%; background: #ea580c; border: 2px solid white; box-shadow: 0 1px 4px rgba(0,0,0,0.2);"></div></div>
                        <div style="position: absolute; top: 80%; right: 30%;"><div style="width: 9px; height: 9px; border-radius: 50%; background: #2563eb; border: 2px solid white; box-shadow: 0 1px 4px rgba(0,0,0,0.2);"></div></div>
                        <div style="position: absolute; top: 38%; left: 72%;"><div style="width: 9px; height: 9px; border-radius: 50%; background: #16a34a; border: 2px solid white; box-shadow: 0 1px 4px rgba(0,0,0,0.2);"></div></div>
                    </div>
                </div>

                {{-- Komposisi Potensi Desa --}}
                <div style="background: white; border: 1px solid #e2e8f0; border-radius: 14px; padding: 16px; display: flex; flex-direction: column;">
                    <h3 style="font-size: 13px; font-weight: 700; color: var(--sidebar-bg); margin: 0 0 8px 0;">Komposisi Potensi Desa</h3>
                    <div style="flex: 1;">
                        @php
                            $komposisi = [
                                ['color' => '#16a34a', 'icon' => 'M12 22c4.97 0 9-4.03 9-9-4.97 0-9 4.03-9 9zM5.6 10.25c0 1.38 1.12 2.5 2.5 2.5.53 0 1.01-.16 1.42-.44l-.02.19c0 1.38 1.12 2.5 2.5 2.5s2.5-1.12 2.5-2.5l-.02-.19c.4.28.89.44 1.42.44 1.38 0 2.5-1.12 2.5-2.5 0-1-.59-1.85-1.43-2.25.84-.4 1.43-1.25 1.43-2.25 0-1.38-1.12-2.5-2.5-2.5-.53 0-1.01.16-1.42.44l.02-.19C14.5 2.12 13.38 1 12 1S9.5 2.12 9.5 3.5l.02.19c-.4-.28-.89-.44-1.42-.44-1.38 0-2.5 1.12-2.5 2.5 0 1 .59 1.85 1.43 2.25-.84.4-1.43 1.25-1.43 2.25z', 'label' => 'Pertanian', 'value' => '120', 'unit' => 'Ha'],
                                ['color' => '#ea580c', 'icon' => 'M18.5 4c-1.38 0-2.5 1.12-2.5 2.5 0 .42.1.8.29 1.14L13.7 9.92c-.34-.19-.72-.29-1.14-.29-.35 0-.68.08-.97.23L8.83 7.3c.11-.27.17-.56.17-.87C9 5.12 7.88 4 6.5 4S4 5.12 4 6.5c0 .87.44 1.63 1.11 2.08L4.46 11.4c-.23-.07-.47-.11-.71-.11C2.23 11.29 1 12.52 1 14.04s1.23 2.75 2.75 2.75z', 'label' => 'Peternakan', 'value' => '85', 'unit' => 'Unit'],
                                ['color' => '#2563eb', 'icon' => 'M12 20L1 12l11-8 11 8-11 8z', 'label' => 'Perikanan', 'value' => '40', 'unit' => 'Unit'],
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
