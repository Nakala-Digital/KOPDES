<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard Utama | DesaHub</title>
    <meta name="description" content="Dashboard Utama DesaHub - Ekosistem Ekonomi Desa. Kelola data dan pantau perkembangan ekonomi desa secara real-time.">
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
            <a href="{{ route('dashboard') }}" class="sidebar-link active">
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
            <a href="{{ route('laporan.index') }}" class="sidebar-link">
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
            <span class="topbar-title">Dashboard Utama</span>
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
    <main class="main-content" data-main-content>
        <div class="main-inner">

            {{-- Greeting --}}
            <div class="greeting-header">
                <div>
                    <h1>{{ $greeting }}, {{ $roleLabel }} 👋</h1>
                    <p>Kelola data dan pantau perkembangan ekonomi desa secara real-time</p>
                </div>
                <button class="export-btn" id="export-btn">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" x2="12" y1="15" y2="3"/>
                    </svg>
                    Export Laporan
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
                </button>
            </div>

            {{-- Stat Cards --}}
            <section class="stat-cards">
                <article class="stat-card" id="stat-penduduk">
                    <div class="stat-card-icon" style="background: #eff6ff; color: #3b82f6;">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                        </svg>
                    </div>
                    <div>
                        <p class="stat-card-label">Total Penduduk</p>
                        <p class="stat-card-value">3.250</p>
                        <p class="stat-card-change" style="display: flex; align-items: center; gap: 3px;">
                            <span style="color: #16a34a; display: flex; align-items: center; font-weight: 600; gap: 2px;">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="width: 12px; height: 12px;"><path d="m7 17 10-10"/><path d="M17 17V7H7"/></svg>
                                +2,3%
                            </span>
                            <span style="color: #64748b;">dari bulan lalu</span>
                        </p>
                    </div>
                </article>

                <article class="stat-card" id="stat-umkm">
                    <div class="stat-card-icon" style="background: #f0fdf4; color: #22c55e;">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="m2 7 4.41-4.41A2 2 0 0 1 7.83 2h8.34a2 2 0 0 1 1.42.59L22 7"/><path d="M4 12v8a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-8"/><path d="M15 22v-4a2 2 0 0 0-2-2h-2a2 2 0 0 0-2 2v4"/><path d="M2 9.782a2 2 0 0 0 2.22 1.993 2.01 2.01 0 0 0 3.824 0 2 2 0 0 0 3.825 0 2 2 0 0 0 3.825 0 2.01 2.01 0 0 0 3.825 0 2 2 0 0 0 2.22-1.993A2 2 0 0 0 20 7H4a2 2 0 0 0-2 2.782Z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="stat-card-label">Total UMKM</p>
                        <p class="stat-card-value">128</p>
                        <p class="stat-card-change" style="display: flex; align-items: center; gap: 3px;">
                            <span style="color: #16a34a; display: flex; align-items: center; font-weight: 600; gap: 2px;">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="width: 12px; height: 12px;"><path d="m7 17 10-10"/><path d="M17 17V7H7"/></svg>
                                +8
                            </span>
                            <span style="color: #64748b;">UMKM baru</span>
                        </p>
                    </div>
                </article>

                <article class="stat-card" id="stat-kopdes">
                    <div class="stat-card-icon" style="background: #fffbeb; color: #d97706;">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M4 11v8a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-8H4Z M14 20v-3a2 2 0 0 1 4 0v3 M7 20v-1a2 2 0 0 1 4 0v1 M6 15h2 M11 15h2 M3 11l8-6h9v5"/>
                        </svg>
                    </div>
                    <div>
                        <p class="stat-card-label">Kopdes/KDMP</p>
                        <p class="stat-card-value">1</p>
                        <p class="stat-card-change" style="color: #64748b; font-size: 13px;">Aktif & Beroperasi</p>
                    </div>
                </article>

                <article class="stat-card" id="stat-bumdes">
                    <div class="stat-card-icon" style="width: 56px; height: 56px; border-radius: 14px; background: linear-gradient(180deg, #f7f2ff 0%, #ece2ff 100%); box-shadow: 0 1px 2px rgba(17, 24, 39, 0.04), 0 8px 20px rgba(139, 92, 246, 0.12); color: #6d3df0;">
                        <svg viewBox="0 0 24 24" fill="none" aria-hidden="true" style="width: 28px; height: 28px; display: block;">
                            <g stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M5.5 10.5 12 5l6.5 5.5"/>
                                <path d="M6.5 10.5V20h11V10.5"/>
                                <rect x="8.5" y="12.6" width="2" height="2" rx="0.35"/>
                                <rect x="13.5" y="12.6" width="2" height="2" rx="0.35"/>
                                <path d="M9 16h6"/>
                                <path d="M10.5 20v-4.5h3V20"/>
                            </g>
                        </svg>
                    </div>
                    <div style="min-width: 0;">
                        <p class="stat-card-label" style="font-size: 14px; font-weight: 500; color: #374151; margin: 0 0 4px;">BUMDes</p>
                        <p class="stat-card-value" style="font-size: 28px; line-height: 1; margin: 0 0 8px;">2</p>
                        <p class="stat-card-change" style="display: flex; align-items: center; gap: 4px; margin: 0; color: #16a34a; font-size: 12px; font-weight: 500;">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width: 13px; height: 13px; flex-shrink: 0;">
                                <path d="M12 20V9"/>
                                <path d="M8 13.5 12 9l4 4.5"/>
                                <path d="M5 16.5c2.5 0 4.5-2 4.5-4.5S7.5 7.5 5 7.5C5 10 4 12 4 12s1 2 1 4.5Z"/>
                            </svg>
                            <span>Aktif</span>
                        </p>
                    </div>
                </article>

                <article class="stat-card" id="stat-transaksi">
                    <div class="stat-card-icon" style="background: #fce7f3; color: #f43f5e;">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="8" cy="21" r="1"/><circle cx="19" cy="21" r="1"/><path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12"/>
                        </svg>
                    </div>
                    <div>
                        <p class="stat-card-label">Transaksi Bulan Ini</p>
                        <p class="stat-card-value">Rp 125,4 jt</p>
                        <p class="stat-card-change" style="display: flex; align-items: center; gap: 3px;">
                            <span style="color: #16a34a; display: flex; align-items: center; font-weight: 600; gap: 2px;">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="width: 12px; height: 12px;"><path d="m7 17 10-10"/><path d="M17 17V7H7"/></svg>
                                +15,7%
                            </span>
                            <span style="color: #64748b;">dari bulan lalu</span>
                        </p>
                    </div>
                </article>
            </section>

            {{-- Charts Row --}}
            <section class="chart-row">
                {{-- Line Chart —- Perkembangan Ekonomi Desa --}}
                <div class="chart-card">
                    <div class="chart-header">
                        <h2 class="chart-title">Perkembangan Ekonomi Desa</h2>
                        <button class="chart-dropdown">
                            6 Bulan Terakhir
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><polyline points="6 9 12 15 18 9"/></svg>
                        </button>
                    </div>
                    <p class="chart-subtitle">Total Transaksi (Rp)</p>
                    <p class="chart-big-value">Rp 125,4 jt</p>
                    <p class="chart-change">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><polyline points="18 15 12 9 6 15"/></svg>
                        +15,7% dari 6 bulan sebelumnya
                    </p>
                    <div class="line-chart-container">
                        <canvas id="lineChart"></canvas>
                    </div>
                </div>

                {{-- Donut Chart — Kategori Transaksi --}}
                <div class="chart-card">
                    <div class="chart-header">
                        <h2 class="chart-title">Kategori Transaksi Terbesar</h2>
                        <button class="chart-dropdown">
                            Bulan Ini
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><polyline points="6 9 12 15 18 9"/></svg>
                        </button>
                    </div>
                    <div class="donut-chart-container">
                        <div class="donut-wrapper">
                            <canvas id="donutChart"></canvas>
                        </div>
                        <div class="donut-legend">
                            <div class="donut-legend-item">
                                <span class="donut-legend-label"><span class="donut-legend-dot" style="background: #2d5cf6;"></span> Pangan & Hasil Pertanian</span>
                                <span class="donut-legend-value">45%</span>
                            </div>
                            <div class="donut-legend-item">
                                <span class="donut-legend-label"><span class="donut-legend-dot" style="background: #22c55e;"></span> Perdagangan & Jasa</span>
                                <span class="donut-legend-value">25%</span>
                            </div>
                            <div class="donut-legend-item">
                                <span class="donut-legend-label"><span class="donut-legend-dot" style="background: #f59e0b;"></span> Peternakan & Perikanan</span>
                                <span class="donut-legend-value">15%</span>
                            </div>
                            <div class="donut-legend-item">
                                <span class="donut-legend-label"><span class="donut-legend-dot" style="background: #a855f7;"></span> Kerajinan & Industri</span>
                                <span class="donut-legend-value">10%</span>
                            </div>
                            <div class="donut-legend-item">
                                <span class="donut-legend-label"><span class="donut-legend-dot" style="background: #ef4444;"></span> Lainnya</span>
                                <span class="donut-legend-value">5%</span>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            {{-- Bottom Row --}}
            <section class="bottom-row">
                {{-- Rantai Pasok MBG --}}
                <div class="bottom-card">
                    <div class="bottom-card-header">
                        <h3 class="bottom-card-title">Rantai Pasok MBG</h3>
                        <button class="chart-dropdown">
                            Bulan Ini
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><polyline points="6 9 12 15 18 9"/></svg>
                        </button>
                    </div>

                    <div class="mbg-stats">
                        <div class="mbg-stat-item">
                            <div class="mbg-stat-icon" style="background: #eff6ff; color: #3b82f6;">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <rect width="18" height="18" x="3" y="3" rx="2"/><circle cx="12" cy="10" r="3"/><path d="M7 21v-2a2 2 0 0 1 2-2h6a2 2 0 0 1 2 2v2"/>
                                </svg>
                            </div>
                            <p class="mbg-stat-value">2.850</p>
                            <p class="mbg-stat-label">Total Demand</p>
                            <p class="mbg-stat-label" style="color: var(--text-muted);">Porsi</p>
                        </div>
                        <div class="mbg-stat-item">
                            <div class="mbg-stat-icon" style="background: #dcfce7; color: #16a34a;">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M10 17h4V5H2v12h3"/><path d="M20 17h2v-3.34a4 4 0 0 0-1.17-2.83L19 9h-5"/><path d="M14 17h1"/><circle cx="7.5" cy="17.5" r="2.5"/><circle cx="17.5" cy="17.5" r="2.5"/>
                                </svg>
                            </div>
                            <p class="mbg-stat-value">2.430</p>
                            <p class="mbg-stat-label">Total Suplai</p>
                            <p class="mbg-stat-label" style="color: var(--text-muted);">Porsi</p>
                        </div>
                        <div class="mbg-stat-item">
                            <div class="mbg-stat-icon" style="background: #fef3c7; color: #d97706;">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="12" cy="12" r="10"/><path d="m9 12 2 2 4-4"/>
                                </svg>
                            </div>
                            <p class="mbg-stat-value">85,3%</p>
                            <p class="mbg-stat-label">Pemenuhan</p>
                            <p class="mbg-stat-change negative">-2,1% dari bulan lalu</p>
                        </div>
                    </div>

                    <div>
                        <div class="mbg-progress-label">
                            <span>Progress Pemenuhan MBG</span>
                            <span style="font-weight: 700; color: var(--text-dark);">85,3%</span>
                        </div>
                        <div class="mbg-progress-bar">
                            <div class="mbg-progress-fill" style="width: 85.3%;"></div>
                        </div>
                    </div>
                </div>

                {{-- UMKM Terbaru --}}
                <div class="bottom-card">
                    <div class="bottom-card-header">
                        <h3 class="bottom-card-title">UMKM Terbaru</h3>
                        <a href="{{ route('umkm.index') }}" class="bottom-card-link">Lihat Semua</a>
                    </div>

                    <div class="umkm-list-item">
                        <div class="umkm-avatar" style="background: #fef3c7;">🍌</div>
                        <div class="umkm-info">
                            <p class="umkm-name">Keripik Pisang Makmur</p>
                            <p class="umkm-category">Makanan & Minuman</p>
                        </div>
                        <span class="umkm-time">2 hari yang lalu</span>
                    </div>

                    <div class="umkm-list-item">
                        <div class="umkm-avatar" style="background: #e0e7ff;">👖</div>
                        <div class="umkm-info">
                            <p class="umkm-name">Denimji Craft</p>
                            <p class="umkm-category">Kerajinan Tangan</p>
                        </div>
                        <span class="umkm-time">5 hari yang lalu</span>
                    </div>

                    <div class="umkm-list-item">
                        <div class="umkm-avatar" style="background: #dcfce7;">🐄</div>
                        <div class="umkm-info">
                            <p class="umkm-name">Ternak Sapi Berkah</p>
                            <p class="umkm-category">Peternakan</p>
                        </div>
                        <span class="umkm-time">1 minggu yang lalu</span>
                    </div>
                </div>

                {{-- Notifikasi & Informasi --}}
                <div class="bottom-card">
                    <div class="bottom-card-header">
                        <h3 class="bottom-card-title">Notifikasi & Informasi</h3>
                        <a href="#" class="bottom-card-link">Lihat Semua</a>
                    </div>

                    <div class="notif-item">
                        <div class="notif-icon" style="background: #dcfce7; color: #16a34a;">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M2 9a3 3 0 0 1 0 6v2a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-2a3 3 0 0 1 0-6V7a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2Z"/><path d="M13 5v2"/><path d="M13 17v2"/><path d="M13 11v2"/>
                            </svg>
                        </div>
                        <div class="notif-content">
                            <p class="notif-title">Transaksi baru di Pasar Desa</p>
                            <p class="notif-desc">Total transaksi hari ini mencapai Rp 4,2 jt</p>
                            <p class="notif-time">10 menit yang lalu</p>
                        </div>
                    </div>

                    <div class="notif-item">
                        <div class="notif-icon" style="background: #fef3c7; color: #d97706;">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/><path d="M12 9v4"/><path d="M12 17h.01"/>
                            </svg>
                        </div>
                        <div class="notif-content">
                            <p class="notif-title">Permintaan MBG meningkat</p>
                            <p class="notif-desc">Demand untuk minggu depan naik 8%</p>
                            <p class="notif-time">2 jam yang lalu</p>
                        </div>
                    </div>

                    <div class="notif-item">
                        <div class="notif-icon" style="background: #eff6ff; color: #3b82f6;">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z"/><path d="M14 2v4a2 2 0 0 0 2 2h4"/><path d="M10 9H8"/><path d="M16 13H8"/><path d="M16 17H8"/>
                            </svg>
                        </div>
                        <div class="notif-content">
                            <p class="notif-title">Laporan bulanan siap</p>
                            <p class="notif-desc">Laporan ekonomi desa bulan Mei 2025</p>
                            <p class="notif-time">1 hari yang lalu</p>
                        </div>
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
                    <span class="footer-logo footer-logo-nakala">
                        <svg width="18" height="18" viewBox="0 0 40 40" fill="none">
                            <rect width="40" height="40" rx="6" fill="#0f1b4c"/>
                            <path d="M12 10h4l6 10-6 10h-4l6-10-6-10z" fill="#fff"/>
                            <path d="M18 10h4l6 10-6 10h-4l6-10-6-10z" fill="rgba(255,255,255,0.5)"/>
                        </svg>
                        Nakala Digital
                    </span>
                    <span class="footer-logo footer-logo-romulus">
                        <svg width="18" height="18" viewBox="0 0 40 40" fill="none">
                            <rect width="40" height="40" rx="6" fill="#1a1d2e"/>
                            <text x="10" y="27" font-size="18" font-weight="700" fill="#fff" font-family="Inter, sans-serif">R</text>
                        </svg>
                        Romulus
                    </span>
                </div>
            </footer>

        </div>
    </main>

    {{-- ===== CHART SCRIPTS ===== --}}
    <script>
    document.addEventListener('DOMContentLoaded', () => {
        // ── Line Chart ──
        const lineCtx = document.getElementById('lineChart')?.getContext('2d');
        if (lineCtx) {
            const gradient = lineCtx.createLinearGradient(0, 0, 0, 200);
            gradient.addColorStop(0, 'rgba(45, 92, 246, 0.15)');
            gradient.addColorStop(1, 'rgba(45, 92, 246, 0.01)');

            new Chart(lineCtx, {
                type: 'line',
                data: {
                    labels: ['Des 2024', 'Jan 2025', 'Feb 2025', 'Mar 2025', 'Apr 2025', 'Mei 2025'],
                    datasets: [{
                        data: [45, 62, 55, 85, 110, 125.4],
                        borderColor: '#2d5cf6',
                        backgroundColor: gradient,
                        borderWidth: 2.5,
                        tension: 0.4,
                        fill: true,
                        pointRadius: 4,
                        pointBackgroundColor: '#2d5cf6',
                        pointBorderColor: '#ffffff',
                        pointBorderWidth: 2,
                        pointHoverRadius: 6,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: '#1a1d2e',
                            titleFont: { family: 'Inter', size: 12 },
                            bodyFont: { family: 'Inter', size: 12 },
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
                            ticks: { font: { family: 'Inter', size: 11 }, color: '#9ca3af' },
                            border: { display: false }
                        },
                        y: {
                            grid: { color: '#f3f4f6' },
                            ticks: {
                                font: { family: 'Inter', size: 11 },
                                color: '#9ca3af',
                                callback: (v) => v === 0 ? '0' : v + ' jt',
                            },
                            border: { display: false },
                            min: 0,
                            suggestedMax: 200,
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
                    labels: ['Pangan & Hasil Pertanian', 'Perdagangan & Jasa', 'Peternakan & Perikanan', 'Kerajinan & Industri', 'Lainnya'],
                    datasets: [{
                        data: [45, 25, 15, 10, 5],
                        backgroundColor: ['#2d5cf6', '#22c55e', '#f59e0b', '#a855f7', '#ef4444'],
                        borderWidth: 3,
                        borderColor: '#ffffff',
                        hoverOffset: 6,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
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
                                label: (ctx) => ` ${ctx.label}: ${ctx.parsed}%`,
                            }
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
