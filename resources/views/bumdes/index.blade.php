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
    <div class="sidebar-overlay" data-sidebar-overlay></div>

    {{-- ===== SIDEBAR ===== --}}
    <aside class="sidebar" data-sidebar>
        {{-- Brand --}}
        <div class="sidebar-brand" style="display: block; padding: 24px 20px 16px;">
            <img src="{{ asset('assets/desahub/logo-desahub-transparent.png') }}" alt="DesaHub Logo" style="width: 140px; height: auto; object-fit: contain; display: block;">
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
            <a href="{{ route('penduduk.index') }}" class="sidebar-link">
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
            <a href="{{ route('bumdes.index') }}" class="sidebar-link active">
                <x-sidebar-icon name="bumdes" />
                <span>BUMDes</span>
            </a>

            <p class="sidebar-category">Ekonomi</p>
            <a href="{{ route('umkm.index') }}" class="sidebar-link">
                <x-sidebar-icon name="umkm" />
                <span>UMKM & Produk</span>
            </a>
            <a href="{{ route('pasar-desa.index') }}" class="sidebar-link">
                <x-sidebar-icon name="pasar-desa" />
                <span>Pasar Desa</span>
            </a>

            <p class="sidebar-category">Supply Chain & MBG</p>
            <a href="{{ route('rantai-pasok-mbg.index') }}" class="sidebar-link">
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
            <a href="{{ route('laporan.index') }}" class="sidebar-link">
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
        <div class="topbar-left">
            <button class="topbar-hamburger" id="menu-toggle" aria-label="Toggle Menu" data-sidebar-toggle>
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width: 20px; height: 20px;">
                    <line x1="4" x2="20" y1="12" y2="12"/><line x1="4" x2="20" y1="6" y2="6"/><line x1="4" x2="14" y1="18" y2="18"/>
                </svg>
            </button>
            <span class="topbar-title">BUMDes</span>
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
                <span class="notification-badge">3</span>
            </button>

            <div class="topbar-user" style="position: relative;" id="user-dropdown-trigger">
                <img src="{{ asset('assets/desahub/kepala-desa.png') }}" alt="User Avatar" class="topbar-user-avatar" style="object-fit: cover;" />
                <div class="topbar-user-info">
                    <div class="topbar-user-name">Kepala Desa</div>
                    <div class="topbar-user-role">Desa Maju</div>
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
    <main class="main-content" data-main-content>
        <div class="main-inner">
            {{-- Kopdes Header --}}
            <div class="greeting-header">
                <div>
                    <div style="display:flex; align-items:center; gap:12px;">
                        <h1>BUMDes Sukamaju Sejahtera</h1>
                        <span style="background: #ecfdf5; color: #16a34a; font-size:11px; font-weight:600; padding:2px 8px; border-radius:12px;">Aktif</span>
                    </div>
                    <p>BUMDes &bull; Desa Sukamaju</p>
                </div>
                <button class="export-btn" style="background:#2d5cf6; color:white; border-radius:8px; padding:8px 14px; border:none; font-weight:600; display:flex; align-items:center; gap:8px;">
                    <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                    Lihat Profil BUMDes
                </button>
            </div>

            {{-- Nav Tabs --}}
            <div class="pendataan-tabs" style="margin-bottom: 12px; margin-top: 12px;">
                <a href="#" class="pendataan-tab-item active">Ringkasan</a>
                <a href="#" class="pendataan-tab-item">Unit Usaha</a>
                <a href="#" class="pendataan-tab-item">Keuangan</a>
                <a href="#" class="pendataan-tab-item">Aset</a>
                <a href="#" class="pendataan-tab-item">Transaksi</a>
                <a href="#" class="pendataan-tab-item">Laporan</a>
                <a href="#" class="pendataan-tab-item">Dokumen</a>
            </div>
            <section class="stat-cards" style="display: grid; grid-template-columns: repeat(5, 1fr); gap: 16px; margin-bottom: 24px;">
                <article class="stat-card" style="display:flex; gap:16px; align-items:center; background:white; border:1px solid #e2e8f0; border-radius:14px; padding:12px;">
                    <div style="width:48px; height:48px; min-width:48px; border-radius:12px; display:flex; align-items:center; justify-content:center; background: #eff6ff; color: #3b82f6;">
                        <svg viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                    </div>
                    <div style="flex:1;">
                        <p style="font-size:12px; font-weight:600; color:#475569; margin-bottom:4px;">Total Unit Usaha</p>
                        <p style="font-size:20px; font-weight:700; color:#1e293b; margin-bottom:4px;">4</p>
                        <p style="display: flex; align-items: center; gap: 4px; font-size:11px;">
                            <span style="color: #16a34a; display: flex; align-items: center; font-weight: 600;">
                                --
                            </span>
                            <span style="color: #64748b;">dari tahun lalu</span>
                        </p>
                    </div>
                </article>

                <article class="stat-card" style="display:flex; gap:16px; align-items:center; background:white; border:1px solid #e2e8f0; border-radius:14px; padding:12px;">
                    <div style="width:48px; height:48px; min-width:48px; border-radius:12px; display:flex; align-items:center; justify-content:center; background: #ecfdf5; color: #10b981;">
                        <svg viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12V7H5a2 2 0 0 1 0-4h14v4"/><path d="M3 5v14a2 2 0 0 0 2 2h16v-5"/><path d="M18 12a2 2 0 0 0 0 4h4v-4Z"/></svg>
                    </div>
                    <div style="flex:1;">
                        <p style="font-size:12px; font-weight:600; color:#475569; margin-bottom:4px;">Pendapatan Bulan Ini</p>
                        <p style="font-size:20px; font-weight:700; color:#1e293b; margin-bottom:4px;">Rp 86,5 jt</p>
                        <p style="display: flex; align-items: center; gap: 4px; font-size:11px;">
                            <span style="color: #16a34a; display: flex; align-items: center; font-weight: 600;">
                                <svg viewBox="0 0 24 24" width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m7 17 10-10"/><path d="M17 17V7H7"/></svg>
                                + 18,7%
                            </span>
                            <span style="color: #64748b;">dari tahun lalu</span>
                        </p>
                    </div>
                </article>

                <article class="stat-card" style="display:flex; gap:16px; align-items:center; background:white; border:1px solid #e2e8f0; border-radius:14px; padding:12px;">
                    <div style="width:48px; height:48px; min-width:48px; border-radius:12px; display:flex; align-items:center; justify-content:center; background: #fef2f2; color: #ef4444;">
                        <svg viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="22 7 13.5 15.5 8.5 10.5 2 17"/><polyline points="16 7 22 7 22 13"/></svg>
                    </div>
                    <div style="flex:1;">
                        <p style="font-size:12px; font-weight:600; color:#475569; margin-bottom:4px;">Laba Bersih (YTD)</p>
                        <p style="font-size:20px; font-weight:700; color:#1e293b; margin-bottom:4px;">Rp 24,8 jt</p>
                        <p style="display: flex; align-items: center; gap: 4px; font-size:11px;">
                            <span style="color: #ef4444; display: flex; align-items: center; font-weight: 600;">
                                <svg viewBox="0 0 24 24" width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m7 7 10 10"/><path d="M17 7v10H7"/></svg>
                                + 21,3%
                            </span>
                            <span style="color: #64748b;">dari tahun lalu</span>
                        </p>
                    </div>
                </article>

                <article class="stat-card" style="display:flex; gap:16px; align-items:center; background:white; border:1px solid #e2e8f0; border-radius:14px; padding:12px;">
                    <div style="width:48px; height:48px; min-width:48px; border-radius:12px; display:flex; align-items:center; justify-content:center; background: #fffbeb; color: #f59e0b;">
                        <svg viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 3v18h18"/><path d="M18 17V9"/><path d="M13 17V5"/><path d="M8 17v-3"/><polyline points="8 10 13 4 18 12"/></svg>
                    </div>
                    <div style="flex:1;">
                        <p style="font-size:12px; font-weight:600; color:#475569; margin-bottom:4px;">Aset Kelolaan</p>
                        <p style="font-size:20px; font-weight:700; color:#1e293b; margin-bottom:4px;">Rp 512,0 jt</p>
                        <p style="display: flex; align-items: center; gap: 4px; font-size:11px;">
                            <span style="color: #16a34a; display: flex; align-items: center; font-weight: 600;">
                                <svg viewBox="0 0 24 24" width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m7 17 10-10"/><path d="M17 17V7H7"/></svg>
                                + 12,6%
                            </span>
                            <span style="color: #64748b;">dari tahun lalu</span>
                        </p>
                    </div>
                </article>

                <article class="stat-card" style="display:flex; gap:16px; align-items:center; background:white; border:1px solid #e2e8f0; border-radius:14px; padding:12px;">
                    <div style="width:48px; height:48px; min-width:48px; border-radius:12px; display:flex; align-items:center; justify-content:center; background: #faf5ff; color: #a855f7;">
                        <svg viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
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
                        <h2 style="font-size:14px; font-weight:600; color:#1e293b; margin:0;">Grafik Kinerja Usaha</h2>
                        <button style="display:flex; align-items:center; gap:4px; background:white; border:1px solid #e2e8f0; padding:6px 12px; border-radius:8px; font-size:12px; font-weight:500; color:#475569; cursor:pointer;">
                            6 Bulan Terakhir
                            <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><polyline points="6 9 12 15 18 9"/></svg>
                        </button>
                    </div>
                    <div style="display:flex; gap:16px; margin-bottom:16px; font-size:11px; font-weight:500; color:#475569;">
                        <div style="display:flex; align-items:center; gap:6px;">
                            <span style="display:inline-block; width:10px; height:10px; border-radius:50%; background:#3b82f6;"></span>
                            Pendapatan (Rp jt)
                        </div>
                        <div style="display:flex; align-items:center; gap:6px;">
                            <span style="display:inline-block; width:10px; height:10px; border-radius:50%; background:#10b981;"></span>
                            Laba Bersih (Rp jt)
                        </div>
                    </div>
                    <div style="flex:1; min-height:80px; position:relative;">
                        <canvas id="lineChart"></canvas>
                    </div>
                </div>

                {{-- Donut Chart --}}
                <div class="chart-card" style="display:flex; flex-direction:column; padding:12px; background:white; border:1px solid #e2e8f0; border-radius:14px;">
                    <div style="margin-bottom:16px;">
                        <h2 style="font-size:14px; font-weight:600; color:#1e293b; margin:0;">Komposisi Unit Usaha</h2>
                    </div>
                    <div style="display:flex; flex-direction:row; gap:48px; align-items:center; padding:16px 24px;">
                        <div style="width:160px; height:160px; position:relative;">
                            <canvas id="donutChart"></canvas>
                        </div>
                        <div style="display:flex; flex-direction:column; gap:16px; font-size:12px; color:#475569; font-weight:500; width: 200px;">
                            <div style="display:flex; justify-content:space-between; align-items:center;">
                                <div style="display:flex; align-items:center; gap:6px;"><span style="display:inline-block; width:8px; height:8px; border-radius:50%; background:#3b82f6;"></span> Perdagangan</div>
                                <span style="color:#1e293b; font-weight:600;">40%</span>
                            </div>
                            <div style="display:flex; justify-content:space-between; align-items:center;">
                                <div style="display:flex; align-items:center; gap:6px;"><span style="display:inline-block; width:8px; height:8px; border-radius:50%; background:#10b981;"></span> Jasa</div>
                                <span style="color:#1e293b; font-weight:600;">25%</span>
                            </div>
                            <div style="display:flex; justify-content:space-between; align-items:center;">
                                <div style="display:flex; align-items:center; gap:6px;"><span style="display:inline-block; width:8px; height:8px; border-radius:50%; background:#f59e0b;"></span> Wisata Desa</div>
                                <span style="color:#1e293b; font-weight:600;">20%</span>
                            </div>
                            <div style="display:flex; justify-content:space-between; align-items:center;">
                                <div style="display:flex; align-items:center; gap:6px;"><span style="display:inline-block; width:8px; height:8px; border-radius:50%; background:#a855f7;"></span> Pengolahan Pangan</div>
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
            <section style="display: grid; grid-template-columns: 1fr 1.5fr; gap: 12px; margin-bottom: 8px;">
                {{-- Daftar Unit Usaha --}}
                <div class="chart-card" style="display:flex; flex-direction:column; background:white; border:1px solid #e2e8f0; border-radius:14px; padding:12px;">
                    <div class="chart-header" style="margin-bottom:16px;">
                        <h2 class="chart-title" style="font-size:14px; font-weight:700; color:#1e3a8a; margin:0;">Daftar Unit Usaha</h2>
                    </div>
                    <table style="width: 100%; border-collapse: separate; border-spacing: 0; text-align: left;">
                        <thead style="background: #ffffff;">
                            <tr>
                                <th style="padding: 8px 8px; font-weight: 600; color: #1e3a8a; font-size: 10px; white-space: nowrap; border-bottom: 1px solid #f1f5f9; border-top-left-radius: 6px; border-bottom-left-radius: 6px;">Nama Unit</th>
                                <th style="padding: 8px 8px; font-weight: 600; color: #1e3a8a; font-size: 10px; white-space: nowrap; border-bottom: 1px solid #f1f5f9;">Jenis Usaha</th>
                                <th style="padding: 8px 8px; font-weight: 600; color: #1e3a8a; font-size: 10px; white-space: nowrap; border-bottom: 1px solid #f1f5f9;">Pendapatan (Bulan Ini)</th>
                                <th style="padding: 8px 8px; font-weight: 600; color: #1e3a8a; font-size: 10px; white-space: nowrap; border-bottom: 1px solid #f1f5f9; border-top-right-radius: 6px; border-bottom-right-radius: 6px;">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td style="padding: 8px 8px; border-bottom: 1px solid #f8fafc; color: #1e3a8a; font-weight: 600; font-size: 10px; white-space: nowrap;">Toko Desa</td>
                                <td style="padding: 8px 8px; border-bottom: 1px solid #f8fafc; color: #1e3a8a; font-weight: 600; font-size: 10px; white-space: nowrap;">Perdagangan</td>
                                <td style="padding: 8px 8px; border-bottom: 1px solid #f8fafc; color: #1e3a8a; font-weight: 600; font-size: 10px; white-space: nowrap;">Rp 42,0 jt</td>
                                <td style="padding: 8px 8px; border-bottom: 1px solid #f8fafc;"><span style="color:#16a34a; background:#ecfdf5; padding:4px 8px; border-radius:6px; font-weight:600; font-size: 10px; white-space: nowrap;">Aktif</span></td>
                            </tr>
                            <tr>
                                <td style="padding: 8px 8px; border-bottom: 1px solid #f8fafc; color: #1e3a8a; font-weight: 600; font-size: 10px; white-space: nowrap;">Agen Pembayaran</td>
                                <td style="padding: 8px 8px; border-bottom: 1px solid #f8fafc; color: #1e3a8a; font-weight: 600; font-size: 10px; white-space: nowrap;">Jasa</td>
                                <td style="padding: 8px 8px; border-bottom: 1px solid #f8fafc; color: #1e3a8a; font-weight: 600; font-size: 10px; white-space: nowrap;">Rp 18,5 jt</td>
                                <td style="padding: 8px 8px; border-bottom: 1px solid #f8fafc;"><span style="color:#16a34a; background:#ecfdf5; padding:4px 8px; border-radius:6px; font-weight:600; font-size: 10px; white-space: nowrap;">Aktif</span></td>
                            </tr>
                            <tr>
                                <td style="padding: 8px 8px; border-bottom: 1px solid #f8fafc; color: #1e3a8a; font-weight: 600; font-size: 10px; white-space: nowrap;">Wisata Edukasi</td>
                                <td style="padding: 8px 8px; border-bottom: 1px solid #f8fafc; color: #1e3a8a; font-weight: 600; font-size: 10px; white-space: nowrap;">Wisata Desa</td>
                                <td style="padding: 8px 8px; border-bottom: 1px solid #f8fafc; color: #1e3a8a; font-weight: 600; font-size: 10px; white-space: nowrap;">Rp 15,0 jt</td>
                                <td style="padding: 8px 8px; border-bottom: 1px solid #f8fafc;"><span style="color:#16a34a; background:#ecfdf5; padding:4px 8px; border-radius:6px; font-weight:600; font-size: 10px; white-space: nowrap;">Aktif</span></td>
                            </tr>
                            <tr>
                                <td style="padding: 8px 8px; border-bottom: 1px solid #f8fafc; color: #1e3a8a; font-weight: 600; font-size: 10px; white-space: nowrap;">Pengolahan Keripik Pisang</td>
                                <td style="padding: 8px 8px; border-bottom: 1px solid #f8fafc; color: #1e3a8a; font-weight: 600; font-size: 10px; white-space: nowrap;">Pengolahan Pangan</td>
                                <td style="padding: 8px 8px; border-bottom: 1px solid #f8fafc; color: #1e3a8a; font-weight: 600; font-size: 10px; white-space: nowrap;">Rp 11,0 jt</td>
                                <td style="padding: 8px 8px; border-bottom: 1px solid #f8fafc;"><span style="color:#16a34a; background:#ecfdf5; padding:4px 8px; border-radius:6px; font-weight:600; font-size: 10px; white-space: nowrap;">Aktif</span></td>
                            </tr>
                        </tbody>
                    </table>
                    <div style="margin-top:auto; padding-top:16px; text-align:center; display:flex; justify-content:center;">
                        <a href="#" style="font-size:13px; font-weight:600; color:#2d5cf6; text-decoration:none; display:flex; align-items:center; gap:4px;">Kelola Unit Usaha <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></svg></a>
                    </div>
                </div>

                {{-- Transaksi / Aktivitas Terbaru --}}
                <div class="chart-card" style="display:flex; flex-direction:column; background:white; border:1px solid #e2e8f0; border-radius:14px; padding:12px;">
                    <div class="chart-header" style="margin-bottom:16px;">
                        <h2 class="chart-title" style="font-size:14px; font-weight:600; color:#1e293b; margin:0;">Transaksi / Aktivitas Terbaru</h2>
                    </div>
                    <table style="width: 100%; border-collapse: separate; border-spacing: 0; text-align: left;">
                        <thead style="background: #f8fafc;">
                            <tr>
                                <th style="padding: 10px 12px; font-weight: 600; color: #334155;  font-size: 11px; border-bottom: 1px solid #e2e8f0; border-top-left-radius: 6px; border-bottom-left-radius: 6px;">Tanggal</th>
                                <th style="padding: 10px 12px; font-weight: 600; color: #334155;  font-size: 11px; border-bottom: 1px solid #e2e8f0;">Unit Usaha</th>
                                <th style="padding: 6px 8px; font-weight: 600; color: #334155;  font-size: 11px; border-bottom: 1px solid #e2e8f0;">Aktivitas</th>
                                <th style="padding: 6px 8px; font-weight: 600; color: #334155;  font-size: 11px; border-bottom: 1px solid #e2e8f0;">Nominal</th>
                                <th style="padding: 10px 12px; font-weight: 600; color: #334155;  font-size: 11px; border-bottom: 1px solid #e2e8f0; border-top-right-radius: 6px; border-bottom-right-radius: 6px;">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td style="padding: 6px 12px; border-bottom: 1px solid #e2e8f0; color: #475569; font-weight: 500; font-size: 11px;">24 Mei 2025</td>
                                <td style="padding: 6px 12px; border-bottom: 1px solid #e2e8f0; color: #1e293b; font-weight: 500; font-size: 11px;">Toko Sembako</td>
                                <td style="padding: 6px 8px; border-bottom: 1px solid #e2e8f0; color: #475569; font-weight: 500; font-size: 11px;">Penjualan Harian</td>
                                <td style="padding: 6px 8px; border-bottom: 1px solid #e2e8f0; color: #16a34a; font-weight: 600; font-size: 11px;">+ Rp 1.250.000</td>
                                <td style="padding: 6px 12px; border-bottom: 1px solid #e2e8f0;"><span style="color:#16a34a; background:#ecfdf5; padding:4px 8px; border-radius:6px; font-weight:600; font-size: 11px;">Selesai</span></td>
                            </tr>
                            <tr>
                                <td style="padding: 6px 12px; border-bottom: 1px solid #e2e8f0; color: #475569; font-weight: 500; font-size: 11px;">24 Mei 2025</td>
                                <td style="padding: 6px 12px; border-bottom: 1px solid #e2e8f0; color: #1e293b; font-weight: 500; font-size: 11px;">Unit Pangan</td>
                                <td style="padding: 6px 8px; border-bottom: 1px solid #e2e8f0; color: #475569; font-weight: 500; font-size: 11px;">Pembelian Bahan Baku</td>
                                <td style="padding: 6px 8px; border-bottom: 1px solid #e2e8f0; color: #ef4444; font-weight: 600; font-size: 11px;">- Rp 450.000</td>
                                <td style="padding: 6px 12px; border-bottom: 1px solid #e2e8f0;"><span style="color:#16a34a; background:#ecfdf5; padding:4px 8px; border-radius:6px; font-weight:600; font-size: 11px;">Selesai</span></td>
                            </tr>
                            <tr>
                                <td style="padding: 6px 12px; border-bottom: 1px solid #e2e8f0; color: #475569; font-weight: 500; font-size: 11px;">23 Mei 2025</td>
                                <td style="padding: 6px 12px; border-bottom: 1px solid #e2e8f0; color: #1e293b; font-weight: 500; font-size: 11px;">Jasa Penggilingan</td>
                                <td style="padding: 6px 8px; border-bottom: 1px solid #e2e8f0; color: #475569; font-weight: 500; font-size: 11px;">Pendapatan Jasa</td>
                                <td style="padding: 6px 8px; border-bottom: 1px solid #e2e8f0; color: #16a34a; font-weight: 600; font-size: 11px;">+ Rp 320.000</td>
                                <td style="padding: 6px 12px; border-bottom: 1px solid #e2e8f0;"><span style="color:#16a34a; background:#ecfdf5; padding:4px 8px; border-radius:6px; font-weight:600; font-size: 11px;">Selesai</span></td>
                            </tr>
                            <tr>
                                <td style="padding: 6px 12px; border-bottom: 1px solid #e2e8f0; color: #475569; font-weight: 500; font-size: 11px;">22 Mei 2025</td>
                                <td style="padding: 6px 12px; border-bottom: 1px solid #e2e8f0; color: #1e293b; font-weight: 500; font-size: 11px;">Simpan Pinjam</td>
                                <td style="padding: 6px 8px; border-bottom: 1px solid #e2e8f0; color: #475569; font-weight: 500; font-size: 11px;">Pencairan Pinjaman</td>
                                <td style="padding: 6px 8px; border-bottom: 1px solid #e2e8f0; color: #ef4444; font-weight: 600; font-size: 11px;">- Rp 5.000.000</td>
                                <td style="padding: 6px 12px; border-bottom: 1px solid #e2e8f0;"><span style="color:#f59e0b; background:#fffbeb; padding:4px 8px; border-radius:6px; font-weight:600; font-size: 11px;">Proses</span></td>
                            </tr>
                            <tr>
                                <td style="padding: 6px 12px; border-bottom: 1px solid #e2e8f0; color: #475569; font-weight: 500; font-size: 11px;">22 Mei 2025</td>
                                <td style="padding: 6px 12px; border-bottom: 1px solid #e2e8f0; color: #1e293b; font-weight: 500; font-size: 11px;">Agen BRILink</td>
                                <td style="padding: 6px 8px; border-bottom: 1px solid #e2e8f0; color: #475569; font-weight: 500; font-size: 11px;">Setor Tunai</td>
                                <td style="padding: 6px 8px; border-bottom: 1px solid #e2e8f0; color: #16a34a; font-weight: 600; font-size: 11px;">+ Rp 2.500.000</td>
                                <td style="padding: 6px 12px; border-bottom: 1px solid #e2e8f0;"><span style="color:#ef4444; background:#fee2e2; padding:4px 8px; border-radius:6px; font-weight:600; font-size: 11px;">Perlu Tindak Lanjut</span></td>
                            </tr>
                        </tbody>
                    </table>
                    <div style="margin-top:auto; padding-top:16px; text-align:center; display:flex; justify-content:center;">
                        <a href="#" style="font-size:13px; font-weight:600; color:#2d5cf6; text-decoration:none; display:flex; align-items:center; gap:4px;">Lihat Semua Aktivitas <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></svg></a>
                    </div>
                </div>
            </section>
{{-- Footer --}}
            <footer class="dashboard-footer">
                <div class="footer-left">
                    © 2025 DesaHub. All rights reserved.
                </div>
                <div class="footer-right">
                    <span class="footer-partner-text">Nakala Digital × Romulus Digital<br>Strategic Partner – Singapore/Vietnam</span>
                    <img src="{{ asset('assets/desahub/logo-nakala-transparent.png') }}" alt="Nakala Digital" style="height: 40px; width: auto; margin-left: 16px;">
                    <img src="{{ asset('assets/desahub/logo-romulus-biru.png') }}" alt="Romulus" style="height: 40px; width: auto; margin-left: 16px;">
                </div>
            </footer>

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
                            label: 'Pendapatan',
                            data: [58.2, 62.1, 68.4, 72.6, 78.9, 86.5],
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
                                align: 'top',
                                anchor: 'end',
                                font: { family: 'Poppins', size: 10, weight: 600 },
                                formatter: function(value) {
                                    return value.toFixed(1).replace('.', ',');
                                }
                            }
                        },
                        {
                            label: 'Laba Bersih',
                            data: [14.2, 15.6, 17.3, 19.1, 21.7, 24.8],
                            borderColor: '#10b981',
                            backgroundColor: 'transparent',
                            borderWidth: 2.5,
                            tension: 0,
                            pointRadius: 4,
                            pointBackgroundColor: '#ffffff',
                            pointBorderColor: '#10b981',
                            pointBorderWidth: 2,
                            datalabels: {
                                color: '#1e293b',
                                align: 'top',
                                anchor: 'end',
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
                                stepSize: 20
                            },
                            border: { display: false },
                            min: 0,
                            suggestedMax: 100,
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
                    labels: ['Perdagangan', 'Jasa', 'Wisata Desa', 'Pengolahan Pangan', 'Lainnya'],
                    datasets: [{
                        data: [40, 25, 20, 10, 5],
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
