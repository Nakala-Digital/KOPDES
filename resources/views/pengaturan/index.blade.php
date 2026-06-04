<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pengaturan | DesaHub</title>
    <meta name="description" content="Kelola profil, pengguna, hak akses, integrasi, notifikasi, dan preferensi sistem secara terstruktur dan terintegrasi.">
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
    <style>
        /* Tabs styling */
        .set-tabs {
            display: flex;
            gap: 24px;
            border-bottom: 1px solid var(--border-color);
            margin-bottom: 16px;
            padding: 0 4px;
        }
        .set-tab {
            padding: 12px 4px;
            font-size: 14px;
            font-weight: 600;
            color: var(--text-secondary);
            cursor: pointer;
            border-bottom: 2px solid transparent;
        }
        .set-tab.active {
            color: var(--primary);
            border-bottom-color: var(--primary);
        }
        
        .set-grid-2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
            margin-bottom: 16px;
            align-items: stretch;
        }
        
        .set-card {
            background: white;
            border: 1px solid #dbe4ff;
            border-radius: 16px;
            padding: 24px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.02);
            display: flex;
            flex-direction: column;
            height: 100%;
        }
        
        .set-card-title {
            font-size: 16px;
            font-weight: 700;
            color: var(--sidebar-bg);
            margin-bottom: 20px;
        }
        
        .set-list-item {
            display: flex;
            align-items: center;
            padding: 12px 0;
            border-bottom: 1px solid #f1f5f9;
        }
        .set-list-item:last-child {
            border-bottom: none;
            padding-bottom: 0;
        }
        
        .set-list-label {
            width: 160px;
            font-size: 13px;
            color: var(--text-secondary);
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .set-list-value {
            flex: 1;
            font-size: 13px;
            font-weight: 600;
            color: var(--text-primary);
        }
        .set-list-action {
            font-size: 13px;
            font-weight: 600;
            color: var(--primary);
            text-decoration: none;
            padding: 4px 8px;
        }
        
        /* Toggle Switch */
        .set-toggle {
            width: 44px;
            height: 24px;
            border-radius: 12px;
            background: #cbd5e1;
            position: relative;
            cursor: pointer;
            transition: 0.3s;
        }
        .set-toggle.on {
            background: #10b981;
        }
        .set-toggle::after {
            content: '';
            position: absolute;
            top: 2px;
            left: 2px;
            width: 20px;
            height: 20px;
            background: white;
            border-radius: 50%;
            transition: 0.3s;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .set-toggle.on::after {
            left: 22px;
        }
        
        /* Badges */
        .badge-soft-success {
            background: #dcfce7;
            color: #16a34a;
            padding: 4px 10px;
            border-radius: 6px;
            font-size: 11px;
            font-weight: 600;
        }
        .badge-soft-info {
            background: #e0f2fe;
            color: #0284c7;
            padding: 4px 10px;
            border-radius: 6px;
            font-size: 11px;
            font-weight: 600;
        }
        .badge-soft-warning {
            background: #ffedd5;
            color: #ea580c;
            padding: 4px 10px;
            border-radius: 6px;
            font-size: 11px;
            font-weight: 600;
        }
        
        /* Tables */
        .table-wrapper-polished {
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.02);
            margin-bottom: 8px;
        }
        .set-table {
            width: 100%;
            border-collapse: collapse;
        }
        .set-table th {
            text-align: left;
            padding: 14px 16px;
            font-size: 12px;
            font-weight: 700;
            color: #1e3a8a;
            background-color: #f8fafc;
            border-bottom: 1px solid #e0e7ff;
        }
        .set-table td {
            padding: 14px 16px;
            font-size: 13px;
            border-bottom: 1px solid #f1f5f9;
        }
        .set-table tbody tr:last-child td {
            border-bottom: none;
        }

        .kpi-row {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 16px;
            margin-bottom: 24px;
        }
        
        .kpi-card {
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 16px;
            display: flex;
            align-items: center;
            gap: 14px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02);
        }
        
        .kpi-icon-box {
            width: 42px;
            height: 42px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        
        .kpi-title {
            font-size: 12px;
            color: #64748b;
            margin-bottom: 4px;
        }
        
        .kpi-val {
            font-size: 20px;
            font-weight: 700;
            color: var(--sidebar-bg);
            line-height: 1.2;
            margin-bottom: 4px;
        }
        
        .kpi-sub {
            font-size: 11px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 4px;
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

    <div class="sidebar-overlay" data-sidebar-overlay></div>

    <aside class="sidebar" data-sidebar>
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
            <a href="{{ route('potensi.index') }}" class="sidebar-link">
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
            <a href="{{ route('rantai-pasok-mbg.index') }}" class="sidebar-link">
                <x-sidebar-icon name="rantai-pasok" />
                <span>Rantai Pasok MBG</span>
            </a>
            <a href="{{ route('gudang-logistik.index') }}" class="sidebar-link">
                <x-sidebar-icon name="gudang" />
                <span>Gudang & Logistik</span>
            </a>
            <a href="{{ route('penerima-manfaat.index') }}" class="sidebar-link">
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
            <a href="{{ route('pengaturan.index') }}" class="sidebar-link active">
                <x-sidebar-icon name="pengaturan" />
                <span>Pengaturan</span>
            </a>
        </nav>

        <button class="sidebar-collapse-btn" data-sidebar-toggle>
            <x-sidebar-icon name="collapse" />
            <span data-sidebar-brand-text>Persempit Menu</span>
        </button>
    </aside>

    <header class="topbar" data-topbar>
        <div class="topbar-left" style="display: flex; align-items: center; gap: 16px;">
            <button class="topbar-hamburger" id="menu-toggle" aria-label="Toggle Menu" data-sidebar-toggle style="margin-right: 0;">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width: 20px; height: 20px;">
                    <line x1="4" x2="20" y1="12" y2="12"/><line x1="4" x2="20" y1="6" y2="6"/><line x1="4" x2="14" y1="18" y2="18"/>
                </svg>
            </button>
            <div style="display: flex; flex-direction: column;">
                <span class="topbar-title" style="margin: 0; line-height: 1.2;">Pengaturan</span>
                <span style="font-size: 13px; color: #64748b; margin-top: 2px;">Kelola profil, pengguna, hak akses, integrasi, notifikasi, dan preferensi sistem secara terstruktur dan terintegrasi.</span>
            </div>
        </div>

        <div class="topbar-right" style="display: flex; align-items: center;">
            <div class="fin-page-actions" style="display: flex; gap: 12px; align-items: center; margin-right: 16px;">
                <button class="fin-btn-outline" style="height: 36px; padding: 0 16px; font-size: 13px; font-weight: 600; background: white; border: 1px solid #dbe4ff; border-radius: 8px; color: var(--primary); display: flex; align-items: center; gap: 8px; cursor: pointer;">
                    <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" x2="12" y1="15" y2="3"/></svg>
                    Export Data
                </button>
                <button class="fin-btn-primary" style="height: 36px; padding: 0 16px; font-size: 13px; font-weight: 600; background: var(--primary); border: none; border-radius: 8px; color: white; display: flex; align-items: center; gap: 8px; cursor: pointer;">
                    <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                    Simpan Pengaturan
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

    <main class="main-content fin-main-content" data-main-content>
        <div class="main-inner fin-main-inner" style="padding-top: 0px;">
            
            <div class="set-tabs">
                <div class="set-tab active">Ringkasan</div>
                <div class="set-tab">Profil Desa</div>
                <div class="set-tab">Pengguna & Akses</div>
                <div class="set-tab">Integrasi</div>
                <div class="set-tab">Notifikasi</div>
                <div class="set-tab">Keamanan</div>
            </div>

            {{-- ── KPI Stat Cards ── --}}
            <section class="kpi-row">
                <article class="kpi-card">
                    <div class="kpi-icon-box" style="background: #2563eb; color: #ffffff;">
                        <svg viewBox="0 0 24 24" fill="currentColor" width="24" height="24"><path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5C6.34 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z"/></svg>
                    </div>
                    <div>
                        <div class="kpi-title">Total Pengguna</div>
                        <div class="kpi-val">24</div>
                        <div class="kpi-sub">
                            <span style="color: #10b981; display: flex; align-items: center; gap: 2px;"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="width: 14px; height: 14px;"><line x1="12" y1="19" x2="12" y2="5"/><polyline points="5 12 12 5 19 12"/></svg> 14%</span>
                            <span style="color: #64748b; font-weight: 500;">dari bulan lalu</span>
                        </div>
                    </div>
                </article>

                <article class="kpi-card">
                    <div class="kpi-icon-box" style="background: transparent; color: #10b981; padding: 0;">
                        <svg viewBox="0 0 24 24" width="48" height="48">
                            <!-- Perfect Pointed Shield (Material Design standard) -->
                            <path d="M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4z" fill="currentColor"/>
                            <!-- Smooth User silhouette -->
                            <path d="M12 7c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3zm0 10c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z" fill="white"/>
                        </svg>
                    </div>
                    <div>
                        <div class="kpi-title">Role Aktif</div>
                        <div class="kpi-val">5</div>
                        <div class="kpi-sub">
                            <span style="color: #64748b; font-weight: 500;">Sama dengan bulan lalu</span>
                        </div>
                    </div>
                </article>

                <article class="kpi-card">
                    <div class="kpi-icon-box" style="background: #8b5cf6; color: #ffffff;">
                        <svg viewBox="0 0 24 24" width="24" height="24">
                            <!-- Left nub -->
                            <rect x="3.5" y="11.5" width="4" height="3" rx="1" fill="currentColor" />
                            <!-- Top nub -->
                            <rect x="8.5" y="5" width="7" height="4" rx="1.5" fill="currentColor" />
                            <!-- Main body -->
                            <rect x="6.5" y="9" width="11" height="8" rx="2.5" fill="currentColor" />
                            <!-- Cable routing -->
                            <path d="M14.5 17 v 2.5 a 3 3 0 0 0 6 0 v -2" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" />
                            <!-- End dot -->
                            <circle cx="20.5" cy="17.5" r="2" fill="currentColor" />
                        </svg>
                    </div>
                    <div>
                        <div class="kpi-title">Integrasi Aktif</div>
                        <div class="kpi-val">4</div>
                        <div class="kpi-sub">
                            <span style="color: #10b981; display: flex; align-items: center; gap: 2px;"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="width: 14px; height: 14px;"><line x1="12" y1="19" x2="12" y2="5"/><polyline points="5 12 12 5 19 12"/></svg> 1</span>
                            <span style="color: #64748b; font-weight: 500;">dari bulan lalu</span>
                        </div>
                    </div>
                </article>

                <article class="kpi-card">
                    <div class="kpi-icon-box" style="background: #f97316; color: #ffffff;">
                        <svg viewBox="0 0 24 24" fill="currentColor" width="24" height="24"><path d="M12 22c1.1 0 2-.9 2-2h-4c0 1.1.89 2 2 2zm6-6v-5c0-3.07-1.64-5.64-4.5-6.32V4c0-.83-.67-1.5-1.5-1.5s-1.5.67-1.5 1.5v.68C7.63 5.36 6 7.92 6 11v5l-2 2v1h16v-1l-2-2z"/></svg>
                    </div>
                    <div>
                        <div class="kpi-title">Notifikasi Aktif</div>
                        <div class="kpi-val">18</div>
                        <div class="kpi-sub">
                            <span style="color: #10b981; display: flex; align-items: center; gap: 2px;"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="width: 14px; height: 14px;"><line x1="12" y1="19" x2="12" y2="5"/><polyline points="5 12 12 5 19 12"/></svg> 3</span>
                            <span style="color: #64748b; font-weight: 500;">dari bulan lalu</span>
                        </div>
                    </div>
                </article>

                <article class="kpi-card">
                    <div class="kpi-icon-box" style="background: #14b8a6; color: #ffffff;">
                        <svg viewBox="0 0 24 24" fill="currentColor" width="24" height="24"><path d="M19.35 10.04A7.49 7.49 0 0 0 12 4C9.11 4 6.6 5.64 5.35 8.04A5.994 5.994 0 0 0 0 14c0 3.31 2.69 6 6 6h13c2.76 0 5-2.24 5-5 0-2.64-2.05-4.78-4.65-4.96zM14 13v4h-4v-4H7l5-5 5 5h-3z"/></svg>
                    </div>
                    <div>
                        <div class="kpi-title">Backup Terakhir</div>
                        <div class="kpi-val">Hari Ini</div>
                        <div class="kpi-sub">
                            <span style="color: #64748b; font-weight: 500;">21 Mei 2025 06:00</span>
                        </div>
                    </div>
                </article>
            </section>

            {{-- ── Profil & Pengguna Row ── --}}
            <section class="set-grid-2">
                {{-- Profil Desa & Konfigurasi Umum --}}
                <div class="set-card">
                    <h2 class="set-card-title" style="margin-bottom: 20px;">Profil Desa & Konfigurasi Umum</h2>
                    <div style="display: grid; grid-template-columns: 1fr 280px; gap: 24px; flex: 1;">
                        
                        {{-- Left Box --}}
                        <div style="border: 1px solid #e2e8f0; border-radius: 12px; padding: 0 16px; display: flex; flex-direction: column; justify-content: center;">
                            <div class="set-list-item" style="padding: 10px 0;">
                                <div class="set-list-label">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                                    Nama Desa
                                </div>
                                <div class="set-list-value">Desa Sukamaju</div>
                                <a href="#" class="set-list-action">Edit</a>
                            </div>
                            <div class="set-list-item" style="padding: 10px 0;">
                                <div class="set-list-label">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                                    Kecamatan
                                </div>
                                <div class="set-list-value">Cibeunying Kaler</div>
                                <a href="#" class="set-list-action">Edit</a>
                            </div>
                            <div class="set-list-item" style="padding: 10px 0;">
                                <div class="set-list-label">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                                    Kabupaten/Kota
                                </div>
                                <div class="set-list-value">Kota Bandung</div>
                                <a href="#" class="set-list-action">Edit</a>
                            </div>
                            <div class="set-list-item" style="padding: 10px 0;">
                                <div class="set-list-label">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
                                    Kode Desa
                                </div>
                                <div class="set-list-value">DSH-3201</div>
                                <a href="#" class="set-list-action">Edit</a>
                            </div>
                            <div class="set-list-item" style="padding: 10px 0;">
                                <div class="set-list-label">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                                    Zona Waktu
                                </div>
                                <div class="set-list-value">WIB (Asia/Jakarta)</div>
                                <a href="#" class="set-list-action">Edit</a>
                            </div>
                            <div class="set-list-item" style="padding: 10px 0; border-bottom: none;">
                                <div class="set-list-label">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="2" y1="12" x2="22" y2="12"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/></svg>
                                    Bahasa Sistem
                                </div>
                                <div class="set-list-value">Indonesia</div>
                                <a href="#" class="set-list-action">Edit</a>
                            </div>
                        </div>
                        
                        {{-- Right Box (Branding Desa) --}}
                        <div style="border: 1px solid #e2e8f0; border-radius: 12px; padding: 16px; display: flex; flex-direction: column; justify-content: flex-start;">
                            <div style="font-size: 14px; font-weight: 700; color: var(--sidebar-bg); margin-bottom: 20px;">Branding Desa</div>
                            
                            <div style="display: flex; gap: 16px; margin-bottom: 24px; align-items: center;">
                                <div style="width: 80px; height: 80px; background: white; border: 1px solid #e2e8f0; border-radius: 12px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; overflow: hidden; padding: 4px;">
                                    <img src="{{ asset('images/logo-sukamaju.jpg') }}" alt="Logo Desa" style="width: 100%; height: 100%; object-fit: contain;">
                                </div>
                                <div>
                                    <div style="font-size: 13px; font-weight: 700; color: var(--sidebar-bg);">Logo Desa</div>
                                    <div style="font-size: 11px; font-weight: 500; color: #94a3b8; margin-bottom: 8px;">PNG &bull; 512&times;512 px</div>
                                    <button class="fin-btn-outline" style="height: 28px; padding: 0 12px; font-size: 11px; border-radius: 6px; background: white; border: 1px solid #cbd5e1; color: var(--sidebar-bg); font-weight: 600; cursor: pointer;">Ubah Logo</button>
                                </div>
                            </div>
                            
                            <div style="background: #f8fafc; padding: 12px 16px; border-radius: 10px; border: 1px solid #f1f5f9;">
                                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
                                    <div style="font-size: 13px; font-weight: 700; color: var(--sidebar-bg);">Warna Tema</div>
                                    <button class="fin-btn-outline" style="height: 28px; padding: 0 12px; font-size: 11px; border-radius: 6px; background: white; border: 1px solid #cbd5e1; color: var(--sidebar-bg); font-weight: 600; cursor: pointer;">Ubah Tema</button>
                                </div>
                                <div style="display: flex; gap: 10px;">
                                    <div style="width: 24px; height: 24px; border-radius: 50%; background: #2563eb; cursor: pointer; box-shadow: 0 0 0 2px white, 0 0 0 3px #2563eb;"></div>
                                    <div style="width: 24px; height: 24px; border-radius: 50%; background: #10b981; cursor: pointer;"></div>
                                    <div style="width: 24px; height: 24px; border-radius: 50%; background: #f97316; cursor: pointer;"></div>
                                    <div style="width: 24px; height: 24px; border-radius: 50%; background: #8b5cf6; cursor: pointer;"></div>
                                    <div style="width: 24px; height: 24px; border-radius: 50%; background: #475569; cursor: pointer;"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Pengguna, Role & Hak Akses --}}
                <div class="set-card">
                    <h2 class="set-card-title">Pengguna, Role & Hak Akses</h2>
                    <div style="flex: 1; display: flex; flex-direction: column; justify-content: space-between;">
                        <div class="table-wrapper-polished">
                            <table class="set-table">
                            <thead>
                                <tr>
                                    <th>Role</th>
                                    <th style="text-align: center;">Jumlah User</th>
                                    <th>Modul Akses Utama</th>
                                    <th style="text-align: center;">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td style="font-weight: 600; color: var(--sidebar-bg);">
                                        <div style="display: flex; align-items: center; gap: 8px;">
                                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                                            Admin Desa
                                        </div>
                                    </td>
                                    <td style="text-align: center; color: var(--text-secondary); font-weight: 500;">3</td>
                                    <td style="color: var(--text-secondary); font-weight: 500;">Semua Modul</td>
                                    <td style="text-align: center;"><span class="badge-soft-success">Aktif</span></td>
                                </tr>
                                <tr>
                                    <td style="font-weight: 600; color: var(--sidebar-bg);">
                                        <div style="display: flex; align-items: center; gap: 8px;">
                                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                                            Operator Pendataan
                                        </div>
                                    </td>
                                    <td style="text-align: center; color: var(--text-secondary); font-weight: 500;">6</td>
                                    <td style="color: var(--text-secondary); font-weight: 500;">Pendataan, Penduduk</td>
                                    <td style="text-align: center;"><span class="badge-soft-success">Aktif</span></td>
                                </tr>
                                <tr>
                                    <td style="font-weight: 600; color: var(--sidebar-bg);">
                                        <div style="display: flex; align-items: center; gap: 8px;">
                                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                                            Pengelola BUMDes
                                        </div>
                                    </td>
                                    <td style="text-align: center; color: var(--text-secondary); font-weight: 500;">4</td>
                                    <td style="color: var(--text-secondary); font-weight: 500;">BUMDes, Keuangan</td>
                                    <td style="text-align: center;"><span class="badge-soft-success">Aktif</span></td>
                                </tr>
                                <tr>
                                    <td style="font-weight: 600; color: var(--sidebar-bg);">
                                        <div style="display: flex; align-items: center; gap: 8px;">
                                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                                            Admin MBG
                                        </div>
                                    </td>
                                    <td style="text-align: center; color: var(--text-secondary); font-weight: 500;">3</td>
                                    <td style="color: var(--text-secondary); font-weight: 500;">MBG, Rantai Pasok, Gudang</td>
                                    <td style="text-align: center;"><span class="badge-soft-success">Aktif</span></td>
                                </tr>
                                <tr>
                                    <td style="font-weight: 600; color: var(--sidebar-bg);">
                                        <div style="display: flex; align-items: center; gap: 8px;">
                                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                                            Auditor
                                        </div>
                                    </td>
                                    <td style="text-align: center; color: var(--text-secondary); font-weight: 500;">2</td>
                                    <td style="color: var(--text-secondary); font-weight: 500;">Laporan, Dashboard</td>
                                    <td style="text-align: center;"><span class="badge-soft-success">Aktif</span></td>
                                </tr>
                            </tbody>
                            </table>
                        </div>
                        
                        <div style="text-align: center; margin-top: 24px; padding-top: 16px; border-top: 1px solid transparent;">
                            <a href="#" style="color: var(--primary); font-size: 13px; font-weight: 700; text-decoration: none;">Lihat Semua Pengguna &rarr;</a>
                        </div>
                    </div>
                </div>
            </section>

            {{-- ── Notifikasi & Keamanan Row ── --}}
            <section class="set-grid-2">
                {{-- Notifikasi --}}
                <div class="set-card">
                    <h2 class="set-card-title" style="margin-bottom: 20px;">Notifikasi & Integrasi Sistem</h2>
                    
                    {{-- Boxes Grid --}}
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px; flex: 1;">
                        
                        {{-- Left Box --}}
                        <div style="border: 1px solid #e2e8f0; border-radius: 12px; padding: 16px;">
                            <div style="font-size: 13px; font-weight: 700; color: var(--sidebar-bg); margin-bottom: 12px;">Preferensi Notifikasi</div>
                            <div class="set-list-item" style="padding: 6px 0;">
                                <div class="set-list-label" style="flex: 1; color: var(--sidebar-bg); font-weight: 500;">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                                    Email
                                </div>
                                <div class="set-toggle on"></div>
                            </div>
                            <div class="set-list-item" style="padding: 6px 0;">
                                <div class="set-list-label" style="flex: 1; color: var(--sidebar-bg); font-weight: 500;">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/></svg>
                                    WhatsApp
                                </div>
                                <div class="set-toggle on"></div>
                            </div>
                            <div class="set-list-item" style="padding: 6px 0;">
                                <div class="set-list-label" style="flex: 1; color: var(--sidebar-bg); font-weight: 500;">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
                                    In-app Notification
                                </div>
                                <div class="set-toggle on"></div>
                            </div>
                            <div class="set-list-item" style="padding: 6px 0;">
                                <div class="set-list-label" style="flex: 1; color: var(--sidebar-bg); font-weight: 500;">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H5.5"/><polyline points="8.5 12 5.5 15 8.5 18"/><line x1="20" y1="4" x2="20" y2="14"/><line x1="15" y1="9" x2="20" y2="14"/><line x1="25" y1="9" x2="20" y2="14"/></svg>
                                    Approval Request
                                </div>
                                <div class="set-toggle on"></div>
                            </div>
                            <div class="set-list-item" style="padding: 6px 0;">
                                <div class="set-list-label" style="flex: 1; color: var(--sidebar-bg); font-weight: 500;">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#ea580c" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                                    Low Stock Alert
                                </div>
                                <div class="set-toggle on"></div>
                            </div>
                            <div class="set-list-item" style="padding: 6px 0; border-bottom: none;">
                                <div class="set-list-label" style="flex: 1; color: var(--sidebar-bg); font-weight: 500;">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
                                    Laporan Bulanan
                                </div>
                                <div class="set-toggle"></div>
                            </div>
                        </div>
                        
                        {{-- Right Box --}}
                        <div style="border: 1px solid #e2e8f0; border-radius: 12px; padding: 16px;">
                            <div style="font-size: 13px; font-weight: 700; color: var(--sidebar-bg); margin-bottom: 12px;">Status Integrasi Sistem</div>
                            <div class="set-list-item" style="padding: 10px 0;">
                                <div class="set-list-label" style="flex: 1; color: var(--sidebar-bg); font-weight: 500;">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>
                                    Payment Gateway
                                </div>
                                <div><span class="badge-soft-success">Aktif</span></div>
                            </div>
                            <div class="set-list-item" style="padding: 10px 0;">
                                <div class="set-list-label" style="flex: 1; color: var(--sidebar-bg); font-weight: 500;">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#10b981" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/></svg>
                                    WhatsApp Gateway
                                </div>
                                <div><span class="badge-soft-success">Aktif</span></div>
                            </div>
                            <div class="set-list-item" style="padding: 10px 0;">
                                <div class="set-list-label" style="flex: 1; color: var(--sidebar-bg); font-weight: 500;">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="4" y="4" width="16" height="16" rx="2" ry="2"/><rect x="9" y="9" width="6" height="6"/><line x1="9" y1="1" x2="9" y2="4"/><line x1="15" y1="1" x2="15" y2="4"/><line x1="9" y1="20" x2="9" y2="23"/><line x1="15" y1="20" x2="15" y2="23"/><line x1="20" y1="9" x2="23" y2="9"/><line x1="20" y1="14" x2="23" y2="14"/><line x1="1" y1="9" x2="4" y2="9"/><line x1="1" y1="14" x2="4" y2="14"/></svg>
                                    Satu Data Internal
                                </div>
                                <div><span class="badge-soft-info">Sinkron</span></div>
                            </div>
                            <div class="set-list-item" style="padding: 10px 0;">
                                <div class="set-list-label" style="flex: 1; color: var(--sidebar-bg); font-weight: 500;">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                                    GIS / Map
                                </div>
                                <div><span class="badge-soft-info">Sinkron</span></div>
                            </div>
                            <div class="set-list-item" style="padding: 10px 0; border-bottom: none;">
                                <div class="set-list-label" style="flex: 1; color: var(--sidebar-bg); font-weight: 500;">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17.5 19H9a7 7 0 1 1 6.71-9h1.79a4.5 4.5 0 1 1 0 9Z"/></svg>
                                    Cloud Storage
                                </div>
                                <div><span class="badge-soft-warning">Review</span></div>
                            </div>
                        </div>
                    </div>

                    {{-- Link Below Grid --}}
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px; margin-top: 16px;">
                        <div style="text-align: center;">
                            <a href="#" style="color: var(--primary); font-size: 13px; font-weight: 700; text-decoration: none;">Kelola Integrasi &rarr;</a>
                        </div>
                        <div></div>
                    </div>
                </div>

                {{-- Keamanan & Audit Log --}}
                <div class="set-card">
                    <h2 class="set-card-title">Keamanan & Audit Log</h2>
                    
                    <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 12px; margin-bottom: 24px;">
                        <div style="border: 1px solid #e2e8f0; border-radius: 12px; padding: 12px; display: flex; align-items: center; gap: 12px; background: white;">
                            <div style="width: 36px; height: 36px; background: #1e3a8a; border-radius: 8px; display: flex; align-items: center; justify-content: center; color: white; flex-shrink: 0;">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                            </div>
                            <div>
                                <div style="font-size: 11px; font-weight: 600; color: var(--sidebar-bg);">2FA Aktif</div>
                                <div style="font-size: 15px; font-weight: 800; color: var(--sidebar-bg); line-height: 1.2;">92%</div>
                                <div style="font-size: 10px; color: #64748b; font-weight: 500;">Dari total user</div>
                            </div>
                        </div>
                        <div style="border: 1px solid #e2e8f0; border-radius: 12px; padding: 12px; display: flex; align-items: center; gap: 12px; background: white;">
                            <div style="width: 36px; height: 36px; background: white; border: 1px solid #cbd5e1; border-radius: 8px; display: flex; align-items: center; justify-content: center; color: var(--sidebar-bg); flex-shrink: 0;">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                            </div>
                            <div>
                                <div style="font-size: 11px; font-weight: 600; color: #64748b;">Password Policy</div>
                                <div style="font-size: 15px; font-weight: 800; color: var(--sidebar-bg); line-height: 1.2;">Kuat</div>
                                <div style="font-size: 10px; color: #64748b; font-weight: 500;">Minimal 8 karakter</div>
                            </div>
                        </div>
                        <div style="border: 1px solid #e2e8f0; border-radius: 12px; padding: 12px; display: flex; align-items: center; gap: 12px; background: white;">
                            <div style="width: 36px; height: 36px; background: white; border: 1px solid #cbd5e1; border-radius: 8px; display: flex; align-items: center; justify-content: center; color: var(--sidebar-bg); flex-shrink: 0;">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                            </div>
                            <div>
                                <div style="font-size: 11px; font-weight: 600; color: #64748b;">Session Timeout</div>
                                <div style="font-size: 15px; font-weight: 800; color: var(--sidebar-bg); line-height: 1.2;">30 Menit</div>
                                <div style="font-size: 10px; color: #64748b; font-weight: 500;">Tidak aktif otomatis</div>
                            </div>
                        </div>
                        <div style="border: 1px solid #e2e8f0; border-radius: 12px; padding: 12px; display: flex; align-items: center; gap: 12px; background: white;">
                            <div style="width: 36px; height: 36px; background: #1e3a8a; border-radius: 8px; display: flex; align-items: center; justify-content: center; color: white; flex-shrink: 0;">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19.35 10.04A7.49 7.49 0 0 0 12 4C9.11 4 6.6 5.64 5.35 8.04A5.994 5.994 0 0 0 0 14c0 3.31 2.69 6 6 6h13c2.76 0 5-2.24 5-5 0-2.64-2.05-4.78-4.65-4.96zM14 13v4h-4v-4H7l5-5 5 5h-3z"/></svg>
                            </div>
                            <div>
                                <div style="font-size: 11px; font-weight: 600; color: var(--sidebar-bg);">Backup Otomatis</div>
                                <div style="font-size: 15px; font-weight: 800; color: var(--sidebar-bg); line-height: 1.2;">Aktif</div>
                                <div style="font-size: 10px; color: #64748b; font-weight: 500;">Setiap hari 02:00</div>
                            </div>
                        </div>
                    </div>

                    <div style="flex: 1; display: flex; flex-direction: column; justify-content: space-between;">
                        <div>
                            <h3 style="font-size: 13px; font-weight: 700; color: var(--sidebar-bg); margin-bottom: 12px;">Audit Log Aktivitas Terbaru</h3>
                            <table class="set-table">
                                <thead>
                                    <tr>
                                        <th>Waktu</th>
                                        <th>Pengguna</th>
                                        <th>Aktivitas</th>
                                        <th>Modul</th>
                                        <th>IP Address</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td style="color: var(--text-secondary); font-weight: 500; font-size: 11px;">21 Mei 2025 10:30</td>
                                        <td style="font-weight: 600; color: var(--sidebar-bg); font-size: 12px;">Admin Desa</td>
                                        <td style="color: var(--text-primary); font-weight: 500; font-size: 12px;">Update role pengguna</td>
                                        <td style="color: var(--text-secondary); font-weight: 500; font-size: 11px;">Pengguna & Akses</td>
                                        <td style="color: var(--text-secondary); font-weight: 500; font-size: 11px;">103.21.45.67</td>
                                    </tr>
                                    <tr>
                                        <td style="color: var(--text-secondary); font-weight: 500; font-size: 11px;">21 Mei 2025 09:45</td>
                                        <td style="font-weight: 600; color: var(--sidebar-bg); font-size: 12px;">Operator Pendataan</td>
                                        <td style="color: var(--text-primary); font-weight: 500; font-size: 12px;">Perubahan profil desa</td>
                                        <td style="color: var(--text-secondary); font-weight: 500; font-size: 11px;">Profil Desa</td>
                                        <td style="color: var(--text-secondary); font-weight: 500; font-size: 11px;">103.21.45.67</td>
                                    </tr>
                                    <tr>
                                        <td style="color: var(--text-secondary); font-weight: 500; font-size: 11px;">21 Mei 2025 08:20</td>
                                        <td style="font-weight: 600; color: var(--sidebar-bg); font-size: 12px;">System</td>
                                        <td style="color: var(--text-primary); font-weight: 500; font-size: 12px;">Sinkronisasi integrasi Satu Data</td>
                                        <td style="color: var(--text-secondary); font-weight: 500; font-size: 11px;">Integrasi</td>
                                        <td style="color: var(--text-secondary); font-weight: 500; font-size: 11px;">103.21.45.67</td>
                                    </tr>
                                    <tr>
                                        <td style="color: var(--text-secondary); font-weight: 500; font-size: 11px;">21 Mei 2025 07:15</td>
                                        <td style="font-weight: 600; color: var(--sidebar-bg); font-size: 12px;">Admin MBG</td>
                                        <td style="color: var(--text-primary); font-weight: 500; font-size: 12px;">Reset password pengguna</td>
                                        <td style="color: var(--text-secondary); font-weight: 500; font-size: 11px;">Keamanan</td>
                                        <td style="color: var(--text-secondary); font-weight: 500; font-size: 11px;">103.21.45.67</td>
                                    </tr>
                                    <tr>
                                        <td style="color: var(--text-secondary); font-weight: 500; font-size: 11px;">21 Mei 2025 06:00</td>
                                        <td style="font-weight: 600; color: var(--sidebar-bg); font-size: 12px;">System</td>
                                        <td style="color: var(--text-primary); font-weight: 500; font-size: 12px;">Backup otomatis berhasil</td>
                                        <td style="color: var(--text-secondary); font-weight: 500; font-size: 11px;">Keamanan</td>
                                        <td style="color: var(--text-secondary); font-weight: 500; font-size: 11px;">103.21.45.67</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        
                        <div style="text-align: center; margin-top: 24px; padding-top: 16px;">
                            <a href="#" style="color: var(--primary); font-size: 13px; font-weight: 700; text-decoration: none;">Lihat Semua Aktivitas &rarr;</a>
                        </div>
                    </div>
                </div>
            </section>

            <footer class="dashboard-footer" style="padding: 24px 0 16px; display: flex; align-items: center; justify-content: space-between; border-top: 1px solid #e2e8f0; margin-top: 32px;">
                <div style="display: flex; align-items: center; gap: 60px;">
                    <div class="footer-left" style="font-size: 13px; color: var(--text-secondary);">
                        <span style="font-weight: 700; color: var(--sidebar-bg);">DesaHub</span> — Ekosistem Ekonomi Desa
                    </div>
                    
                    <div class="footer-center" style="font-size: 13px; color: var(--text-secondary); font-weight: 500;">
                        © 2025 DesaHub. All rights reserved.
                    </div>
                </div>

                <div class="footer-right" style="display: flex; align-items: center; justify-content: flex-end; gap: 24px;">
                    <div class="footer-partner-text" style="text-align: right; font-size: 11px; line-height: 1.5;">
                        <div style="font-weight: 700; color: var(--sidebar-bg);">Nakala Digital &times; Romulus Digital</div>
                        <div style="color: var(--text-secondary); font-weight: 500;">Strategic Partner — Singapore/Vietnam</div>
                    </div>
                    <div style="display: flex; align-items: center; gap: 16px;">
                        <img src="{{ asset('assets/desahub/logo-nakala-transparent.png') }}" alt="Nakala Digital" style="height: 48px; width: auto; object-fit: contain; display: block;">
                        <img src="{{ asset('assets/desahub/logo-romulus-biru.png') }}" alt="Romulus Digital" style="height: 48px; width: auto; object-fit: contain; display: block;">
                    </div>
                </div>
            </footer>

        </div>
    </main>

    <script>
    document.addEventListener('DOMContentLoaded', () => {
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
        
        // Toggles interactive for visual
        document.querySelectorAll('.set-toggle').forEach(t => {
            t.addEventListener('click', function() {
                this.classList.toggle('on');
            });
        });
    });
    </script>
</body>
</html>
