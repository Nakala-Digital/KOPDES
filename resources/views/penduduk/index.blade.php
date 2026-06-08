<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Data Penduduk | {{ config('app.name', 'DesaHub') }}</title>
    
    <!-- Google Fonts: Poppins for headings, Inter for body -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Poppins:wght@500;600;700&display=swap" rel="stylesheet">
    
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
    
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .filter-bar {
            display: flex;
            align-items: center;
            gap: 16px;
            background: var(--card-bg);
            padding: 16px;
            border-radius: 12px;
            border: 1px solid var(--border-color);
            margin-bottom: 16px;
        }
        .filter-input-group {
            display: flex;
            align-items: center;
            background: #f9fafb;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            padding: 8px 12px;
            flex: 1.5;
        }
        .filter-input-group input {
            background: transparent;
            border: none;
            outline: none;
            font-size: 11px;
            width: 100%;
            margin-left: 8px;
            color: var(--text-dark);
        }
        .filter-select {
            background: #ffffff;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            padding: 8px 12px;
            font-size: 11px;
            color: var(--text-dark);
            outline: none;
            width: 100%;
            appearance: none;
            background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right 12px center;
            background-size: 14px;
            padding-right: 32px;
        }
        .reset-filter-btn {
            display: flex;
            align-items: center;
            gap: 6px;
            padding: 8px 16px;
            background: #ffffff;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            font-size: 11px;
            font-weight: 500;
            color: var(--text-dark);
            cursor: pointer;
            margin-left: auto;
        }
        .reset-filter-btn:hover {
            background: #f3f4f6;
        }
        .main-grid {
            display: grid;
            grid-template-columns: 4fr 1fr;
            gap: 16px;
        }
        .data-table-card {
            background: var(--card-bg);
            border-radius: 12px;
            border: 1px solid var(--border-color);
            overflow: hidden;
        }
        .data-table {
            width: 100%;
            border-collapse: collapse;
        }
        .data-table th {
            background: #f9fafb;
            padding: 4px 6px;
            text-align: left;
            font-size: 9px;
            font-weight: 600;
            color: var(--text-secondary);
            text-transform: uppercase;
            letter-spacing: 0.05em;
            border-bottom: 1px solid var(--border-color);
            white-space: nowrap;
        }
        .data-table td {
            padding: 4px 6px;
            font-size: 10px;
            color: var(--text-dark);
            border-bottom: 1px solid var(--border-color);
            white-space: nowrap;
        }
        .data-table tbody tr:hover {
            background: #f9fafb;
        }
        .badge {
            padding: 2px 4px;
            border-radius: 20px;
            font-size: 9px;
            font-weight: 500;
            white-space: nowrap;
        }
        .badge-blue { background: #eff6ff; color: #2d5cf6; }
        .badge-pink { background: #fdf2f8; color: #db2777; }
        .badge-green { background: #ecfdf5; color: #16a34a; }
        .badge-yellow { background: #fef9c3; color: #ca8a04; }
        .badge-red { background: #fef2f2; color: #dc2626; }
        .badge-outline { border: 1px solid var(--border-color); color: var(--text-secondary); }
        .badge-outline-green { border: 1px solid #16a34a; color: #16a34a; background: #ecfdf5; }

        .action-btns {
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .action-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 28px;
            height: 28px;
            border-radius: 6px;
            border: 1px solid var(--border-color);
            background: #ffffff;
            color: var(--text-secondary);
            cursor: pointer;
        }
        .action-btn:hover {
            background: #f9fafb;
            color: var(--text-dark);
        }
        .action-btn.view-btn {
            color: var(--primary);
        }
        .action-btn.view-btn:hover {
            background: var(--primary-light);
            border-color: #93c5fd;
        }
        
        .pagination {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 12px 16px;
            background: #ffffff;
            border-top: 1px solid var(--border-color);
        }
        .page-info {
            font-size: 12px;
            color: var(--text-secondary);
        }
        .page-numbers {
            display: flex;
            align-items: center;
            gap: 16px;
        }
        .page-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            min-width: 26px;
            height: 26px;
            border-radius: 6px;
            border: none;
            outline: none;
            background: transparent;
            font-size: 12px;
            font-weight: 500;
            color: var(--text-dark);
            cursor: pointer;
            padding: 0 4px;
        }
        .page-btn:hover {
            background: #f3f4f6;
        }
        .page-btn.active {
            background: #2d5cf6;
            color: #ffffff;
        }
        .page-nav-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 26px;
            height: 26px;
            border-radius: 6px;
            border: 1px solid var(--border-color);
            background: #ffffff;
            color: var(--text-dark);
            cursor: pointer;
        }
        .page-nav-btn:hover {
            background: #f3f4f6;
        }
        .page-size-select {
            background: #ffffff;
            border: 1px solid var(--border-color);
            border-radius: 6px;
            padding: 4px 8px;
            font-size: 12px;
            color: var(--text-dark);
            outline: none;
        }

        .side-charts {
            display: flex;
            flex-direction: column;
            gap: 16px;
        }
        
        .progress-bar-container {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 8px;
        }
        .progress-label {
            width: 70px;
            font-size: 9px;
            color: var(--text-dark);
            white-space: nowrap;
        }
        .progress-track {
            flex: 1;
            height: 8px;
            background: #f3f4f6;
            border-radius: 4px;
            overflow: hidden;
        }
        .progress-fill {
            height: 100%;
            background: var(--primary);
            border-radius: 4px;
        }
        .progress-value {
            width: 65px;
            text-align: right;
            font-size: 9px;
            font-weight: 600;
            color: var(--text-dark);
            white-space: nowrap;
        }

    </style>
</head>
<body class="antialiased">

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
            <button class="topbar-hamburger" id="menu-toggle" aria-label="Toggle Menu" data-sidebar-toggle>
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width: 20px; height: 20px;">
                    <line x1="4" x2="20" y1="12" y2="12"/><line x1="4" x2="20" y1="6" y2="6"/><line x1="4" x2="14" y1="18" y2="18"/>
                </svg>
            </button>
            <span class="topbar-title">Data Penduduk</span>
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
                    <div class="topbar-user-role">Desa Sukamaju</div>
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

            {{-- Greeting --}}
            <div class="greeting-header">
                <div>
                    <h1>Data Penduduk</h1>
                    <p>Kelola dan pantau data penduduk desa secara terstruktur dan terintegrasi.</p>
                </div>
                <div style="display: flex; gap: 12px;">
                    <button class="export-btn" id="tambah-data-btn">
                        <svg class="export-icon-left" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line>
                        </svg>
                        Tambah Penduduk
                    </button>
                    <button class="export-btn" style="background: #ffffff; color: var(--text-dark); border: 1px solid var(--border-color);" id="export-data-btn">
                        <svg class="export-icon-left" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                        Export Data
                        <svg class="export-icon-right" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
                    </button>
                </div>
            </div>

            {{-- Tabs --}}
            <div class="pendataan-tabs">
                <a href="#" class="pendataan-tab-item">Ringkasan</a>
                <a href="#" class="pendataan-tab-item active">Daftar Penduduk</a>
                <a href="#" class="pendataan-tab-item">Demografi</a>
                <a href="#" class="pendataan-tab-item">Penerima Manfaat</a>
                <a href="#" class="pendataan-tab-item">Riwayat Perubahan</a>
            </div>

            {{-- Stat Cards --}}
            <section class="stat-cards" style="grid-template-columns: repeat(5, 1fr);">
                <article class="stat-card">
                    <div class="stat-card-icon" style="background: #eff6ff; color: #3b82f6;">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                    </div>
                    <div>
                        <p class="stat-card-label">Total Penduduk</p>
                        <p class="stat-card-value">3.250</p>
                        <p class="stat-card-change" style="display: flex; align-items: center; gap: 3px;">
                            <span style="color: #16a34a; display: flex; align-items: center; font-weight: 600; gap: 2px;">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="width: 12px; height: 12px;"><path d="m7 17 10-10"/><path d="M17 17V7H7"/></svg>
                                +2,3%
                            </span>
                            <span style="color: #64748b; font-size:11px;">dari bulan lalu</span>
                        </p>
                    </div>
                </article>

                <article class="stat-card">
                    <div class="stat-card-icon" style="background: #ecfdf5; color: #22c55e;">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="10" cy="14" r="5"/><line x1="13.5" y1="10.5" x2="21" y2="3"/><polyline points="16 3 21 3 21 8"/></svg>
                    </div>
                    <div>
                        <p class="stat-card-label">Laki-laki</p>
                        <p class="stat-card-value">1.685</p>
                        <p class="stat-card-desc" style="color:var(--text-muted); font-size:11px; font-weight:500; margin-top:2px;">51,9% dari total</p>
                    </div>
                </article>

                <article class="stat-card">
                    <div class="stat-card-icon" style="background: #fdf2f8; color: #db2777;">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="7" r="4"/><path d="M9.5 11h5l1.5 10h-8z"/></svg>
                    </div>
                    <div>
                        <p class="stat-card-label">Perempuan</p>
                        <p class="stat-card-value">1.565</p>
                        <p class="stat-card-desc" style="color:var(--text-muted); font-size:11px; font-weight:500; margin-top:2px;">48,1% dari total</p>
                    </div>
                </article>

                <article class="stat-card">
                    <div class="stat-card-icon" style="background: #fff7ed; color: #f97316;">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><path d="M9 22V12h6v10"/><path d="M18 3v5"/></svg>
                    </div>
                    <div>
                        <p class="stat-card-label">Kepala Keluarga</p>
                        <p class="stat-card-value">1.285</p>
                        <p class="stat-card-desc" style="color:var(--text-muted); font-size:11px; font-weight:500; margin-top:2px;">KK Aktif</p>
                    </div>
                </article>

                <article class="stat-card">
                    <div class="stat-card-icon" style="background: #f3e8ff; color: #8b5cf6;">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="8" r="3"/>
                            <path d="M9 14v-2c0-1.1.9-2 2-2h2c1.1 0 2 .9 2 2v2"/>
                            <path d="M4 14c0 2 1.5 3 3 3h1.5"/>
                            <path d="M20 14c0 2-1.5 3-3 3h-1.5"/>
                            <path d="M7 17v2c0 1.1.9 2 2 2h6c1.1 0 2-.9 2-2v-2"/>
                        </svg>
                    </div>
                    <div>
                        <p class="stat-card-label">Penerima Bantuan</p>
                        <p class="stat-card-value">642</p>
                        <p class="stat-card-change" style="display: flex; align-items: center; gap: 3px;">
                            <span style="color: #16a34a; display: flex; align-items: center; font-weight: 600; gap: 2px;">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="width: 12px; height: 12px;"><path d="m7 17 10-10"/><path d="M17 17V7H7"/></svg>
                                19,8%
                            </span>
                            <span style="color: #64748b; font-size:11px;">dari total</span>
                        </p>
                    </div>
                </article>
            </section>

            {{-- Filter Bar --}}
            <div class="filter-bar">
                <div class="filter-input-group">
                    <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="var(--text-muted)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                    <input type="text" placeholder="Cari nama atau NIK...">
                </div>
                
                <div style="display:flex; flex-direction:column; gap:4px; flex:1;">
                    <span style="font-size:9px; font-weight:600; color:var(--text-secondary); text-transform:uppercase;">Dusun / RW</span>
                    <select class="filter-select">
                        <option>Semua Dusun</option>
                        <option>Mekarjaya</option>
                        <option>Sukamaju</option>
                        <option>Cibeureum</option>
                        <option>Sukaesmi</option>
                    </select>
                </div>
                
                <div style="display:flex; flex-direction:column; gap:4px; flex:1;">
                    <span style="font-size:9px; font-weight:600; color:var(--text-secondary); text-transform:uppercase;">Gender</span>
                    <select class="filter-select">
                        <option>Semua</option>
                        <option>Laki-laki</option>
                        <option>Perempuan</option>
                    </select>
                </div>

                <div style="display:flex; flex-direction:column; gap:4px; flex:1;">
                    <span style="font-size:9px; font-weight:600; color:var(--text-secondary); text-transform:uppercase;">Rentang Usia</span>
                    <select class="filter-select">
                        <option>Semua</option>
                        <option>0-14 Tahun</option>
                        <option>15-24 Tahun</option>
                        <option>25-44 Tahun</option>
                        <option>45-64 Tahun</option>
                        <option>65+ Tahun</option>
                    </select>
                </div>

                <div style="display:flex; flex-direction:column; gap:4px; flex:1;">
                    <span style="font-size:9px; font-weight:600; color:var(--text-secondary); text-transform:uppercase;">Status Ekonomi</span>
                    <select class="filter-select">
                        <option>Semua</option>
                        <option>Menengah</option>
                        <option>Rentan</option>
                        <option>Miskin</option>
                    </select>
                </div>

                <button class="reset-filter-btn">
                    <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/><path d="M3 3v5h5"/></svg>
                    Reset Filter
                </button>
            </div>

            <div class="main-grid">
                {{-- Data Table --}}
                <div class="data-table-card">
                    <div style="overflow-x: auto;">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>Nama</th>
                                    <th>NIK</th>
                                    <th>No. KK</th>
                                    <th>Dusun / RW</th>
                                    <th>Gender</th>
                                    <th>Umur</th>
                                    <th>Pekerjaan</th>
                                    <th>Ekonomi</th>
                                    <th>Bantuan</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td style="font-weight: 500;">Ahmad Fauzi</td>
                                    <td>3203021203980001</td>
                                    <td>3203021203980001</td>
                                    <td>Mekarjaya / 01</td>
                                    <td><span class="badge badge-blue">Laki-laki</span></td>
                                    <td>45</td>
                                    <td>Petani</td>
                                    <td><span class="badge badge-green">Menengah</span></td>
                                    <td><span class="badge badge-outline-green">Penerima BLT</span></td>
                                    <td>
                                        <div class="action-btns">
                                            <button class="action-btn view-btn" title="Lihat"><svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg></button>
                                            <button class="action-btn" title="Edit"><svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"/></svg></button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="font-weight: 500;">Siti Nurhaliza</td>
                                    <td>3203025505010002</td>
                                    <td>3203025505010002</td>
                                    <td>Sukamaju / 02</td>
                                    <td><span class="badge badge-pink">Perempuan</span></td>
                                    <td>37</td>
                                    <td>Ibu Rumah Tangga</td>
                                    <td><span class="badge badge-yellow">Rentan</span></td>
                                    <td>-</td>
                                    <td>
                                        <div class="action-btns">
                                            <button class="action-btn view-btn" title="Lihat"><svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg></button>
                                            <button class="action-btn" title="Edit"><svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"/></svg></button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="font-weight: 500;">Budi Santoso</td>
                                    <td>3203021502850003</td>
                                    <td>3203021502850003</td>
                                    <td>Mekarjaya / 01</td>
                                    <td><span class="badge badge-blue">Laki-laki</span></td>
                                    <td>39</td>
                                    <td>Buruh Tani</td>
                                    <td><span class="badge badge-green">Menengah</span></td>
                                    <td><span class="badge badge-outline-green">Penerima BLT</span></td>
                                    <td>
                                        <div class="action-btns">
                                            <button class="action-btn view-btn" title="Lihat"><svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg></button>
                                            <button class="action-btn" title="Edit"><svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"/></svg></button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="font-weight: 500;">Dewi Kartika</td>
                                    <td>3203020207020004</td>
                                    <td>3203020207020004</td>
                                    <td>Cibeureum / 03</td>
                                    <td><span class="badge badge-pink">Perempuan</span></td>
                                    <td>50</td>
                                    <td>Pedagang</td>
                                    <td><span class="badge badge-green">Menengah</span></td>
                                    <td>-</td>
                                    <td>
                                        <div class="action-btns">
                                            <button class="action-btn view-btn" title="Lihat"><svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg></button>
                                            <button class="action-btn" title="Edit"><svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"/></svg></button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="font-weight: 500;">Joko Prasetyo</td>
                                    <td>3203021001800005</td>
                                    <td>3203021001800005</td>
                                    <td>Sukamaju / 02</td>
                                    <td><span class="badge badge-blue">Laki-laki</span></td>
                                    <td>63</td>
                                    <td>Pensiunan</td>
                                    <td><span class="badge badge-yellow">Rentan</span></td>
                                    <td><span class="badge badge-outline-green">Penerima BLT</span></td>
                                    <td>
                                        <div class="action-btns">
                                            <button class="action-btn view-btn" title="Lihat"><svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg></button>
                                            <button class="action-btn" title="Edit"><svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"/></svg></button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="font-weight: 500;">Nani Wulandari</td>
                                    <td>3203022204900006</td>
                                    <td>3203022204900006</td>
                                    <td>Mekarjaya / 01</td>
                                    <td><span class="badge badge-pink">Perempuan</span></td>
                                    <td>34</td>
                                    <td>Guru</td>
                                    <td><span class="badge badge-green">Menengah</span></td>
                                    <td>-</td>
                                    <td>
                                        <div class="action-btns">
                                            <button class="action-btn view-btn" title="Lihat"><svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg></button>
                                            <button class="action-btn" title="Edit"><svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"/></svg></button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="font-weight: 500;">Hendra Wijaya</td>
                                    <td>3203020503900007</td>
                                    <td>3203020503900007</td>
                                    <td>Cibeureum / 03</td>
                                    <td><span class="badge badge-blue">Laki-laki</span></td>
                                    <td>54</td>
                                    <td>Peternak</td>
                                    <td><span class="badge badge-yellow">Rentan</span></td>
                                    <td><span class="badge badge-outline-green">Penerima BLT</span></td>
                                    <td>
                                        <div class="action-btns">
                                            <button class="action-btn view-btn" title="Lihat"><svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg></button>
                                            <button class="action-btn" title="Edit"><svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"/></svg></button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="font-weight: 500;">Rina Marlina</td>
                                    <td>3203023008800008</td>
                                    <td>3203023008800008</td>
                                    <td>Sukamaju / 02</td>
                                    <td><span class="badge badge-pink">Perempuan</span></td>
                                    <td>56</td>
                                    <td>Penjahit</td>
                                    <td><span class="badge badge-green">Menengah</span></td>
                                    <td>-</td>
                                    <td>
                                        <div class="action-btns">
                                            <button class="action-btn view-btn" title="Lihat"><svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg></button>
                                            <button class="action-btn" title="Edit"><svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"/></svg></button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="font-weight: 500;">Tatang Supriatna</td>
                                    <td>3203021201700009</td>
                                    <td>3203021201700009</td>
                                    <td>Mekarjaya / 01</td>
                                    <td><span class="badge badge-blue">Laki-laki</span></td>
                                    <td>73</td>
                                    <td>Pensiunan</td>
                                    <td><span class="badge badge-red">Lansia</span></td>
                                    <td><span class="badge badge-outline-green">Penerima BLT</span></td>
                                    <td>
                                        <div class="action-btns">
                                            <button class="action-btn view-btn" title="Lihat"><svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg></button>
                                            <button class="action-btn" title="Edit"><svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"/></svg></button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="font-weight: 500;">Yuni Astuti</td>
                                    <td>3203021806950010</td>
                                    <td>3203021806950010</td>
                                    <td>Cibeureum / 03</td>
                                    <td><span class="badge badge-pink">Perempuan</span></td>
                                    <td>29</td>
                                    <td>Karyawan Swasta</td>
                                    <td><span class="badge badge-green">Menengah</span></td>
                                    <td>-</td>
                                    <td>
                                        <div class="action-btns">
                                            <button class="action-btn view-btn" title="Lihat"><svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg></button>
                                            <button class="action-btn" title="Edit"><svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"/></svg></button>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="pagination">
                        <div class="page-info">Menampilkan 1 - 10 dari 3.250 data</div>
                        <div class="page-numbers">
                            <button class="page-nav-btn"><svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"/></svg></button>
                            <button class="page-btn active">1</button>
                            <button class="page-btn">2</button>
                            <button class="page-btn">3</button>
                            <button class="page-btn">4</button>
                            <button class="page-btn">5</button>
                            <span style="color:var(--text-muted); margin:0 2px;">...</span>
                            <button class="page-btn">325</button>
                            <button class="page-nav-btn"><svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg></button>
                        </div>
                        <div>
                            <select class="page-size-select">
                                <option>10 / halaman</option>
                                <option>20 / halaman</option>
                                <option>50 / halaman</option>
                            </select>
                        </div>
                    </div>
                </div>

                {{-- Side Charts --}}
                <div class="side-charts">
                    <div class="bottom-card" style="display:flex; flex-direction:column;">
                        <div class="bottom-card-header" style="margin-bottom: 8px;">
                            <p class="bottom-card-title">Kelompok Usia Penduduk</p>
                        </div>
                        <div style="display:flex; align-items:center; gap:12px;">
                            <div style="width: 100px; height: 100px; position:relative; flex-shrink:0;">
                                <canvas id="usiaChart"></canvas>
                            </div>
                            <div style="flex:1;">
                                <div style="display:grid; grid-template-columns: 8px 1fr auto auto; align-items:center; gap:8px 6px; width:100%;">
                                    <div style="width:8px; height:8px; border-radius:50%; background:#3b82f6;"></div>
                                    <span style="font-size:9px; font-weight:500; color:var(--text-dark); white-space:nowrap;">0-14 tahun</span>
                                    <span style="font-size:9px; font-weight:600; color:var(--text-dark); text-align:right;">18%</span>
                                    <span style="font-size:9px; color:var(--text-muted); text-align:right;">(585)</span>
                                    
                                    <div style="width:8px; height:8px; border-radius:50%; background:#22c55e;"></div>
                                    <span style="font-size:9px; font-weight:500; color:var(--text-dark); white-space:nowrap;">15-24 tahun</span>
                                    <span style="font-size:9px; font-weight:600; color:var(--text-dark); text-align:right;">17%</span>
                                    <span style="font-size:9px; color:var(--text-muted); text-align:right;">(553)</span>
                                    
                                    <div style="width:8px; height:8px; border-radius:50%; background:#f59e0b;"></div>
                                    <span style="font-size:9px; font-weight:500; color:var(--text-dark); white-space:nowrap;">25-44 tahun</span>
                                    <span style="font-size:9px; font-weight:600; color:var(--text-dark); text-align:right;">32%</span>
                                    <span style="font-size:9px; color:var(--text-muted); text-align:right;">(1.040)</span>
                                    
                                    <div style="width:8px; height:8px; border-radius:50%; background:#ef4444;"></div>
                                    <span style="font-size:9px; font-weight:500; color:var(--text-dark); white-space:nowrap;">45-64 tahun</span>
                                    <span style="font-size:9px; font-weight:600; color:var(--text-dark); text-align:right;">23%</span>
                                    <span style="font-size:9px; color:var(--text-muted); text-align:right;">(748)</span>
                                    
                                    <div style="width:8px; height:8px; border-radius:50%; background:#a855f7;"></div>
                                    <span style="font-size:9px; font-weight:500; color:var(--text-dark); white-space:nowrap;">65+ tahun</span>
                                    <span style="font-size:9px; font-weight:600; color:var(--text-dark); text-align:right;">10%</span>
                                    <span style="font-size:9px; color:var(--text-muted); text-align:right;">(324)</span>
                                </div>
                            </div>
                        </div>
                        <div style="margin-top:20px; padding-top:12px; border-top:1px solid var(--border-color); text-align:center;">
                            <a href="#" style="font-size:12px; font-weight:600; color:var(--primary); text-decoration:none;">Lihat Detail Demografi</a>
                        </div>
                    </div>

                    <div class="bottom-card" style="display:flex; flex-direction:column; flex: 1;">
                        <div class="bottom-card-header" style="margin-bottom: 12px;">
                            <p class="bottom-card-title">Distribusi Penduduk per Dusun</p>
                        </div>
                        
                        <div class="progress-bar-container">
                            <div class="progress-label">Mekarjaya</div>
                            <div class="progress-track"><div class="progress-fill" style="width: 34.5%; background:#2d5cf6;"></div></div>
                            <div class="progress-value">1.120 <span style="font-weight:normal; color:var(--text-muted);">(34,5%)</span></div>
                        </div>
                        <div class="progress-bar-container">
                            <div class="progress-label">Sukamaju</div>
                            <div class="progress-track"><div class="progress-fill" style="width: 30.2%; background:#2d5cf6;"></div></div>
                            <div class="progress-value">980 <span style="font-weight:normal; color:var(--text-muted);">(30,2%)</span></div>
                        </div>
                        <div class="progress-bar-container">
                            <div class="progress-label">Cibeureum</div>
                            <div class="progress-track"><div class="progress-fill" style="width: 22.8%; background:#2d5cf6;"></div></div>
                            <div class="progress-value">740 <span style="font-weight:normal; color:var(--text-muted);">(22,8%)</span></div>
                        </div>
                        <div class="progress-bar-container">
                            <div class="progress-label">Sukaesmi</div>
                            <div class="progress-track"><div class="progress-fill" style="width: 12.6%; background:#2d5cf6;"></div></div>
                            <div class="progress-value">410 <span style="font-weight:normal; color:var(--text-muted);">(12,6%)</span></div>
                        </div>

                        <div style="display:flex; justify-content:space-between; align-items:center; margin-top:12px; padding-top:12px; border-top:1px solid var(--border-color);">
                            <span style="font-size:12px; font-weight:600; color:var(--text-dark);">Total</span>
                            <span style="font-size:12px; font-weight:600; color:var(--text-dark);">3.250 Penduduk</span>
                        </div>
                        
                        <div style="margin-top:16px; text-align:center;">
                            <a href="#" style="font-size:12px; font-weight:600; color:var(--primary); text-decoration:none;">Lihat Peta Wilayah Desa</a>
                        </div>
                        

                    </div>
                </div>
            </div>

            {{-- Footer --}}
            <footer class="dashboard-footer" style="margin-top: 24px;">
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

    <script>
    document.addEventListener('DOMContentLoaded', () => {
        const toggleBtn = document.getElementById('mobile-toggle');
        const sidebar = document.querySelector('[data-sidebar]');
        const closeBtn = document.getElementById('sidebar-close');

        if(toggleBtn && sidebar) {
            toggleBtn.addEventListener('click', () => {
                sidebar.classList.add('active');
            });
        }
        if(closeBtn && sidebar) {
            closeBtn.addEventListener('click', () => {
                sidebar.classList.remove('active');
            });
        }

        // Kelompok Usia Chart
        const ctxUsia = document.getElementById('usiaChart');
        if (ctxUsia) {
            new Chart(ctxUsia, {
                type: 'doughnut',
                data: {
                    labels: ['0-14 tahun', '15-24 tahun', '25-44 tahun', '45-64 tahun', '65+ tahun'],
                    datasets: [{
                        data: [18, 17, 32, 23, 10],
                        backgroundColor: ['#3b82f6', '#22c55e', '#f59e0b', '#ef4444', '#a855f7'],
                        borderWidth: 0,
                        hoverOffset: 2
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '65%',
                    plugins: {
                        legend: { display: false },
                        tooltip: { enabled: true }
                    }
                }
            });
        }
    });
    </script>

</body>
</html>
