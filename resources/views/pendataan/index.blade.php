<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pendataan Desa | {{ config('app.name', 'DesaHub') }}</title>
    
    <!-- Google Fonts: Poppins for headings, Inter for body -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Poppins:wght@500;600;700&display=swap" rel="stylesheet">
    
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
    
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
            <a href="{{ route('dashboard') }}" class="sidebar-link">
                <x-sidebar-icon name="dashboard" />
                <span>Dashboard</span>
            </a>

            <p class="sidebar-category">Data & Informasi</p>
            <a href="{{ route('pendataan.index') }}" class="sidebar-link active">
                <x-sidebar-icon name="pendataan-desa" />
                <span>Pendataan Desa</span>
            </a>
            <a href="#" class="sidebar-link">
                <x-sidebar-icon name="penduduk" />
                <span>Penduduk</span>
            </a>
            <a href="#" class="sidebar-link">
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
            <a href="#" class="sidebar-link">
                <x-sidebar-icon name="pasar-desa" />
                <span>Pasar Desa</span>
            </a>

            <p class="sidebar-category">Supply Chain & MBG</p>
            <a href="{{ route('mbg.index') }}" class="sidebar-link">
                <x-sidebar-icon name="rantai-pasok" />
                <span>Rantai Pasok MBG</span>
            </a>
            <a href="#" class="sidebar-link">
                <x-sidebar-icon name="gudang" />
                <span>Gudang & Logistik</span>
            </a>
            <a href="#" class="sidebar-link">
                <x-sidebar-icon name="penerima-manfaat" />
                <span>Penerima Manfaat</span>
            </a>

            <p class="sidebar-category">Keuangan</p>
            <a href="#" class="sidebar-link">
                <x-sidebar-icon name="keuangan" />
                <span>Keuangan & Transaksi</span>
            </a>

            <p class="sidebar-category">Laporan</p>
            <a href="#" class="sidebar-link">
                <x-sidebar-icon name="dashboard-analitik" />
                <span>Dashboard & Analitik</span>
            </a>
            <a href="#" class="sidebar-link">
                <x-sidebar-icon name="laporan" />
                <span>Laporan</span>
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
            <span class="topbar-title">Pendataan Desa</span>
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

            {{-- Greeting --}}
            <div class="greeting-header">
                <div>
                    <h1>Pendataan Desa</h1>
                    <p>Kelola dan pantau seluruh data desa secara terstruktur dan terintegrasi.</p>
                </div>
                <button class="export-btn" id="tambah-data-btn">
                    <svg class="export-icon-left" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line>
                    </svg>
                    Tambah Data
                    <svg class="export-icon-right" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
                </button>
            </div>

            {{-- Tabs --}}
            <div class="pendataan-tabs">
                <a href="#" class="pendataan-tab-item active">Ringkasan</a>
                <a href="#" class="pendataan-tab-item">Kependudukan</a>
                <a href="#" class="pendataan-tab-item">Potensi & Aset</a>
                <a href="#" class="pendataan-tab-item">Fasilitas</a>
                <a href="#" class="pendataan-tab-item">Peta Desa</a>
                <a href="#" class="pendataan-tab-item">Riwayat Pendataan</a>
            </div>

            {{-- Stat Cards --}}
            <section class="stat-cards">
                <article class="stat-card">
                    <div class="stat-card-icon" style="background: #eff6ff; color: #3b82f6; border-radius: 50%;">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                    </div>
                    <div>
                        <p class="stat-card-label">Total Penduduk</p>
                        <p class="stat-card-value">3.250</p>
                        <p class="stat-card-desc" style="color:var(--text-muted); font-size:11px; font-weight:500; margin-top:2px;">1.685 KK</p>
                    </div>
                </article>

                <article class="stat-card">
                    <div class="stat-card-icon" style="background: #ecfdf5; color: #22c55e; border-radius: 50%;">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                    </div>
                    <div>
                        <p class="stat-card-label">Keluarga</p>
                        <p class="stat-card-value">1.685</p>
                        <p class="stat-card-desc" style="color:var(--text-muted); font-size:11px; font-weight:500; margin-top:2px;">KK</p>
                    </div>
                </article>

                <article class="stat-card">
                    <div class="stat-card-icon" style="background: #fff7ed; color: #f97316; border-radius: 50%;">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M3 10.5 12 4.5l9 6"/><path d="M5 10.5V20h14V10.5"/><path d="M5 20h14"/><path d="M10 20v-4.5h4V20"/><path d="M8 12.5h2.5v2.5H8z"/><path d="M13.5 12.5H16v2.5h-2.5z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="stat-card-label">Dusun / RW</p>
                        <p class="stat-card-value">4</p>
                        <p class="stat-card-desc" style="color:var(--text-muted); font-size:11px; font-weight:500; margin-top:2px;">Dusun</p>
                    </div>
                </article>

                <article class="stat-card">
                    <div class="stat-card-icon" style="background: #f3e8ff; color: #8b5cf6; border-radius: 50%;">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="3" y1="22" x2="21" y2="22"/><line x1="6" y1="18" x2="6" y2="11"/><line x1="10" y1="18" x2="10" y2="11"/><line x1="14" y1="18" x2="14" y2="11"/><line x1="18" y1="18" x2="18" y2="11"/><polygon points="12 2 20 7 4 7"/></svg>
                    </div>
                    <div>
                        <p class="stat-card-label">RT</p>
                        <p class="stat-card-value">16</p>
                        <p class="stat-card-desc" style="color:var(--text-muted); font-size:11px; font-weight:500; margin-top:2px;">RT</p>
                    </div>
                </article>

                <article class="stat-card">
                    <div class="stat-card-icon" style="background: #ccfbf1; color: #14b8a6; border-radius: 50%;">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/><path d="M8 14h.01"/><path d="M12 14h.01"/><path d="M16 14h.01"/><path d="M8 18h.01"/><path d="M12 18h.01"/><path d="M16 18h.01"/></svg>
                    </div>
                    <div>
                        <p class="stat-card-label">Update Terakhir</p>
                        <p class="stat-card-value" style="font-size:15px;">20 Mei 2025</p>
                        <p class="stat-card-desc" style="color:var(--text-muted); font-size:11px; font-weight:500; margin-top:2px;">10:30 WIB</p>
                    </div>
                </article>
            </section>

            {{-- Mid Row --}}
            <div class="pendataan-mid-row">
                <!-- Peta Desa -->
                <div class="chart-card" style="display:flex; flex-direction:column; height:100%;">
                    <div class="card-title-link">
                        <p class="bottom-card-title">Peta Wilayah Desa</p>
                        <a href="#">Lihat Peta Lengkap <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg></a>
                    </div>
                    <div class="map-placeholder" style="border-radius: 8px; overflow: hidden; flex: 1; min-height: 0; position: relative; display: block;">
                        <img src="{{ asset('assets/desahub/map-placeholder.png') }}" alt="Peta Wilayah Desa" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; object-fit: cover;">
                    </div>
                </div>

                <!-- Potensi Desa -->
                <div class="bottom-card" style="display:flex; flex-direction:column; height:100%;">
                    <div class="bottom-card-header">
                        <p class="bottom-card-title">Potensi Desa <span style="font-size:11px; font-weight:normal; color:var(--text-muted);">(Ringkasan)</span></p>
                    </div>
                    <div class="data-list">
                        <div class="data-list-item">
                            <div class="data-list-left">
                                <div class="data-list-icon" style="background:#dcfce7; color:#16a34a;"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M7 20h10"/><path d="M10 20c5.5-2.5.8-6.4 3-10"/><path d="M9.5 9.4c1.1.8 1.8 2.2 2.3 3.7-2 .4-3.5.4-4.8-.3-1.2-.6-2.3-1.9-3-4.2 2.8-.5 4.4 0 5.5.8z"/><path d="M14.1 6a7 7 0 0 0-1.1 4c1.9-.1 3.3-.6 4.3-1.4 1-1 1.6-2.3 1.7-4.6-2.7.1-4 1-4.9 2z"/></svg></div>
                                <span class="data-list-label">Pertanian</span>
                            </div>
                            <div><span class="data-list-value">120</span> <span class="data-list-unit">Ha</span></div>
                        </div>
                        <div class="data-list-item">
                            <div class="data-list-left">
                                <div class="data-list-icon" style="background:#fef3c7; color:#d97706;"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10 5.172C10 3.782 8.423 2.679 6.5 3c-2.823.47-4.113 6.006-4 7 .08.7.54 1.794 1.5 2.238A8.8 8.8 0 0 0 7 15c0 3.14-1.2 5.09-3 6h16c-1.8-.91-3-2.86-3-6 0-1.03.35-2.02.9-2.82.95-.5 1.4-1.5 1.5-2.25.13-1.04-1.26-6.45-4-7C13.523 2.632 12 3.736 12 5.127V6"/><path d="M14 11h.01"/><path d="M10 11h.01"/><path d="M10 15h4"/></svg></div>
                                <span class="data-list-label">Peternakan</span>
                            </div>
                            <div><span class="data-list-value">85</span> <span class="data-list-unit">Unit</span></div>
                        </div>
                        <div class="data-list-item">
                            <div class="data-list-left">
                                <div class="data-list-icon" style="background:#e0f2fe; color:#0284c7;"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6.5 12c.94-3.46 4.94-6 8.5-6 3.56 0 6.06 2.54 6 6-.06 3.46-2.44 6-6 6-3.56 0-7.56-2.54-8.5-6Z"/><path d="M18 12v.01"/><path d="M3 15c.66.66 1.44 1.3 2.1 1.9"/><path d="M3 9c.66-.66 1.44-1.3 2.1-1.9"/><path d="M11.52 7c2.44-1.7 4.12-3.3 4.48-4"/><path d="M11.52 17c2.44 1.7 4.12 3.3 4.48-4"/></svg></div>
                                <span class="data-list-label">Perikanan</span>
                            </div>
                            <div><span class="data-list-value">40</span> <span class="data-list-unit">Unit</span></div>
                        </div>
                        <div class="data-list-item">
                            <div class="data-list-left">
                                <div class="data-list-icon" style="background:#dcfce7; color:#16a34a;"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 20A7 7 0 0 1 9.8 6.1C15.5 5 17 4.48 19 2c1 2 2 4.18 2 8 0 5.5-4.78 10-10 10Z"/><path d="M2 21c0-3 1.85-5.36 5.08-6C9.5 14.52 12 13 13 12"/></svg></div>
                                <span class="data-list-label">Perkebunan</span>
                            </div>
                            <div><span class="data-list-value">60</span> <span class="data-list-unit">Ha</span></div>
                        </div>
                        <div class="data-list-item">
                            <div class="data-list-left">
                                <div class="data-list-icon" style="background:#fee2e2; color:#ef4444;"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m2 7 4.41-4.41A2 2 0 0 1 7.83 2h8.34a2 2 0 0 1 1.42.59L22 7"/><path d="M4 12v8a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-8"/><path d="M15 22v-4a2 2 0 0 0-2-2h-2a2 2 0 0 0-2 2v4"/><path d="M2 7h20"/><path d="M22 7v3a2 2 0 0 1-2 2v0a2.7 2.7 0 0 1-1.59-.63.7.7 0 0 0-.82 0A2.7 2.7 0 0 1 16 12a2.7 2.7 0 0 1-1.59-.63.7.7 0 0 0-.82 0A2.7 2.7 0 0 1 12 12a2.7 2.7 0 0 1-1.59-.63.7.7 0 0 0-.82 0A2.7 2.7 0 0 1 8 12a2.7 2.7 0 0 1-1.59-.63.7.7 0 0 0-.82 0A2.7 2.7 0 0 1 4 12v0a2 2 0 0 1-2-2V7"/></svg></div>
                                <span class="data-list-label">UMKM</span>
                            </div>
                            <div><span class="data-list-value">128</span> <span class="data-list-unit">Unit</span></div>
                        </div>
                        <div class="data-list-item">
                            <div class="data-list-left">
                                <div class="data-list-icon" style="background:#f3e8ff; color:#9333ea;"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg></div>
                                <span class="data-list-label">Pariwisata</span>
                            </div>
                            <div><span class="data-list-value">12</span> <span class="data-list-unit">Lokasi</span></div>
                        </div>
                    </div>
                    <div style="margin-top:auto; padding-top:12px;">
                        <a href="#" style="display:block; width:100%; text-align:center; padding:8px 0; border:1px solid var(--border-color); border-radius:8px; font-size:12px; font-weight:600; color:var(--primary); text-decoration:none;">Lihat Semua Potensi</a>
                    </div>
                </div>
            </div>

            {{-- Bottom Row --}}
            <div class="pendataan-bot-row">
                <!-- Fasilitas -->
                <div class="bottom-card">
                    <div class="bottom-card-header">
                        <p class="bottom-card-title">Fasilitas Desa</p>
                    </div>
                    <div class="data-list">
                        <div class="data-list-item">
                            <div class="data-list-left">
                                <div class="data-list-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/></svg></div>
                                <span class="data-list-label">Sekolah</span>
                            </div>
                            <span class="data-list-value">5</span>
                        </div>
                        <div class="data-list-item">
                            <div class="data-list-left">
                                <div class="data-list-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 12h-4l-3 9L9 3l-3 9H2"/></svg></div>
                                <span class="data-list-label">Puskesmas / Posyandu</span>
                            </div>
                            <span class="data-list-value">2</span>
                        </div>
                        <div class="data-list-item">
                            <div class="data-list-left">
                                <div class="data-list-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 21h18"/><path d="M9 8h1"/><path d="M9 12h1"/><path d="M9 16h1"/><path d="M14 8h1"/><path d="M14 12h1"/><path d="M14 16h1"/><path d="M5 21V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2v16"/></svg></div>
                                <span class="data-list-label">Balai Desa / Kantor</span>
                            </div>
                            <span class="data-list-value">1</span>
                        </div>
                        <div class="data-list-item">
                            <div class="data-list-left">
                                <div class="data-list-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg></div>
                                <span class="data-list-label">Pasar Desa</span>
                            </div>
                            <span class="data-list-value">1</span>
                        </div>
                        <div class="data-list-item">
                            <div class="data-list-left">
                                <div class="data-list-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M2 22h20"/><path d="M12 2v8"/><path d="M8 6h8"/><path d="M12 10l8 4v8H4v-8l8-4z"/></svg></div>
                                <span class="data-list-label">Tempat Ibadah</span>
                            </div>
                            <span class="data-list-value">8</span>
                        </div>
                    </div>
                    <div style="margin-top:12px;">
                        <a href="#" style="display:block; width:100%; text-align:center; padding:8px 0; border:1px solid var(--border-color); border-radius:8px; font-size:12px; font-weight:600; color:var(--primary); text-decoration:none;">Lihat Semua Fasilitas</a>
                    </div>
                </div>

                <!-- Aset -->
                <div class="bottom-card">
                    <div class="bottom-card-header">
                        <p class="bottom-card-title">Aset Desa</p>
                    </div>
                    <div class="data-list">
                        <div class="data-list-item">
                            <div class="data-list-left">
                                <div class="data-list-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><line x1="3" y1="9" x2="21" y2="9"/><line x1="9" y1="21" x2="9" y2="9"/></svg></div>
                                <span class="data-list-label">Tanah Kas Desa</span>
                            </div>
                            <div><span class="data-list-value">12</span> <span class="data-list-unit">Ha</span></div>
                        </div>
                        <div class="data-list-item">
                            <div class="data-list-left">
                                <div class="data-list-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 21h18"/><path d="M9 8h1"/><path d="M9 12h1"/><path d="M9 16h1"/><path d="M14 8h1"/><path d="M14 12h1"/><path d="M14 16h1"/><path d="M5 21V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2v16"/></svg></div>
                                <span class="data-list-label">Bangunan</span>
                            </div>
                            <div><span class="data-list-value">18</span> <span class="data-list-unit">Unit</span></div>
                        </div>
                        <div class="data-list-item">
                            <div class="data-list-left">
                                <div class="data-list-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 16H9m10 0h3v-3.15a1 1 0 0 0-.84-.99L16 11l-2.7-3.6a2 2 0 0 0-1.6-.8H5a2 2 0 0 0-2 2v7.2a2 2 0 0 0 2 2h2.5"/><circle cx="7.5" cy="16.5" r="2.5"/><circle cx="16.5" cy="16.5" r="2.5"/></svg></div>
                                <span class="data-list-label">Kendaraan / Alat</span>
                            </div>
                            <div><span class="data-list-value">7</span> <span class="data-list-unit">Unit</span></div>
                        </div>
                        <div class="data-list-item">
                            <div class="data-list-left">
                                <div class="data-list-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2v20"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg></div>
                                <span class="data-list-label">Irigasi / Sumber Air</span>
                            </div>
                            <div><span class="data-list-value">4</span> <span class="data-list-unit">Titik</span></div>
                        </div>
                        <div class="data-list-item">
                            <div class="data-list-left">
                                <div class="data-list-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/><polyline points="3.27 6.96 12 12.01 20.73 6.96"/><line x1="12" y1="22.08" x2="12" y2="12"/></svg></div>
                                <span class="data-list-label">Aset Lainnya</span>
                            </div>
                            <div><span class="data-list-value">32</span> <span class="data-list-unit">Unit</span></div>
                        </div>
                    </div>
                    <div style="margin-top:12px;">
                        <a href="#" style="display:block; width:100%; text-align:center; padding:8px 0; border:1px solid var(--border-color); border-radius:8px; font-size:12px; font-weight:600; color:var(--primary); text-decoration:none;">Lihat Semua Aset</a>
                    </div>
                </div>

                <!-- Kelengkapan -->
                <div class="bottom-card" style="display:flex; flex-direction:column; height:100%;">
                    <div class="bottom-card-header">
                        <p class="bottom-card-title">Status Kelengkapan Data</p>
                    </div>
                    <div style="display:flex; align-items:center; gap:20px; flex:1;">
                        <div style="width: 130px; height: 130px; position:relative;">
                            <canvas id="kelengkapanChart"></canvas>
                        </div>
                        <div style="flex:1;">
                            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:12px;">
                                <div style="display:flex; align-items:center; gap:6px;">
                                    <div style="width:8px; height:8px; border-radius:50%; background:#22c55e;"></div>
                                    <span style="font-size:13px; font-weight:500; color:var(--text-dark);">Lengkap</span>
                                </div>
                                <span style="font-size:13px; font-weight:600; color:var(--text-dark);">75% <span style="font-size:11px; font-weight:normal; color:var(--text-muted);">(24 data)</span></span>
                            </div>
                            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:12px;">
                                <div style="display:flex; align-items:center; gap:6px;">
                                    <div style="width:8px; height:8px; border-radius:50%; background:#eab308;"></div>
                                    <span style="font-size:13px; font-weight:500; color:var(--text-dark);">Perlu Diperbarui</span>
                                </div>
                                <span style="font-size:13px; font-weight:600; color:var(--text-dark);">18% <span style="font-size:11px; font-weight:normal; color:var(--text-muted);">(5 data)</span></span>
                            </div>
                            <div style="display:flex; justify-content:space-between; align-items:center;">
                                <div style="display:flex; align-items:center; gap:6px;">
                                    <div style="width:8px; height:8px; border-radius:50%; background:#ef4444;"></div>
                                    <span style="font-size:13px; font-weight:500; color:var(--text-dark);">Belum Lengkap</span>
                                </div>
                                <span style="font-size:13px; font-weight:600; color:var(--text-dark);">7% <span style="font-size:11px; font-weight:normal; color:var(--text-muted);">(2 data)</span></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Footer --}}
            <footer class="dashboard-footer">
                <div class="footer-left">
                    © 2025 DesaHub. All rights reserved.
                </div>
                <div class="footer-right">
                    <span class="footer-partner-text">Nakala Digital × Romulus Digital<br>Strategic Partner – Singapore/Vietnam</span>
                    <img src="{{ asset('assets/desahub/logo-nakala-transparent.png') }}" alt="Nakala Digital" style="height: 24px; width: auto; margin-left: 8px;">
                    <img src="{{ asset('assets/desahub/logo-romulus-biru.png') }}" alt="Romulus" style="height: 24px; width: auto; margin-left: 8px;">
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

        // Kelengkapan Chart
        const ctx = document.getElementById('kelengkapanChart');
        if (ctx) {
            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: ['Lengkap', 'Perlu Diperbarui', 'Belum Lengkap'],
                    datasets: [{
                        data: [75, 18, 7],
                        backgroundColor: ['#22c55e', '#eab308', '#ef4444'],
                        borderWidth: 0,
                        hoverOffset: 2
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '70%',
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
