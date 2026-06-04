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
            <a href="{{ route('pasar-desa.index') }}" class="sidebar-link">
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
            <a href="{{ route('keuangan.index') }}" class="sidebar-link active">
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
            <span class="topbar-title">Keuangan & Transaksi</span>
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
        <div class="main-inner fin-main-inner">

            {{-- ── Header + Tabs + Actions ── --}}
            <div class="fin-page-header">
                <div class="fin-page-header-top">
                    <div>
                        <p class="fin-page-desc">Kelola arus kas, transaksi pemasukan dan pengeluaran, pembayaran, dan ringkasan keuangan desa secara terstruktur dan terintegrasi.</p>
                    </div>
                    <div class="fin-page-actions">
                        <button class="fin-btn-outline" id="fin-export-btn">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" x2="12" y1="15" y2="3"/></svg>
                            Export Data
                        </button>
                        <button class="fin-btn-primary" id="fin-add-btn">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" x2="12" y1="5" y2="19"/><line x1="5" x2="19" y1="12" y2="12"/></svg>
                            Tambah Transaksi
                        </button>
                    </div>
                </div>

                {{-- Tabs --}}
                <div class="fin-tabs">
                    <button class="fin-tab active" data-tab="ringkasan">Ringkasan</button>
                    <button class="fin-tab" data-tab="pemasukan">Pemasukan</button>
                    <button class="fin-tab" data-tab="pengeluaran">Pengeluaran</button>
                    <button class="fin-tab" data-tab="pembayaran">Pembayaran</button>
                    <button class="fin-tab" data-tab="rekonsiliasi">Rekonsiliasi</button>
                    <button class="fin-tab" data-tab="laporan">Laporan</button>
                </div>
            </div>

            {{-- ── KPI Stat Cards ── --}}
            <section class="fin-kpi-cards">
                {{-- Total Pemasukan --}}
                <article class="stat-card" id="fin-stat-pemasukan">
                    <div class="stat-card-icon" style="background: #dcfce7; color: #16a34a;">
                        <svg viewBox="0 0 24 24" fill="currentColor" width="24" height="24">
                            <path d="M4 19h16v2H4zM20 5h-5v2h3.58l-7.08 7.08-4-4-5.8 5.8 1.42 1.42 4.38-4.38 4 4 8.5-8.5V12h2V5z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="stat-card-label" style="font-weight: 700; color: var(--sidebar-bg);">Total Pemasukan</p>
                        <p class="stat-card-value" style="color: var(--sidebar-bg);">Rp 248,6 jt</p>
                        <p class="stat-card-change" style="display: flex; align-items: center; gap: 3px;">
                            <span style="color: #16a34a; display: flex; align-items: center; font-weight: 600; gap: 2px;">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="width: 14px; height: 14px;"><line x1="12" y1="19" x2="12" y2="5"/><polyline points="5 12 12 5 19 12"/></svg>
                                18,7%
                            </span>
                            <span style="color: #64748b;">dari bulan lalu</span>
                        </p>
                    </div>
                </article>

                {{-- Total Pengeluaran --}}
                <article class="stat-card" id="fin-stat-pengeluaran">
                    <div class="stat-card-icon" style="background: #ffedd5; color: #ea580c;">
                        <svg viewBox="0 0 24 24" fill="currentColor" width="24" height="24">
                            <path d="M12 16l-7-7h4V3h6v6h4l-7 7zm-8 2h16v2H4v-2z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="stat-card-label" style="font-weight: 700; color: var(--sidebar-bg);">Total Pengeluaran</p>
                        <p class="stat-card-value" style="color: var(--sidebar-bg);">Rp 186,4 jt</p>
                        <p class="stat-card-change" style="display: flex; align-items: center; gap: 3px;">
                            <span style="color: #16a34a; display: flex; align-items: center; font-weight: 600; gap: 2px;">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="width: 14px; height: 14px;"><line x1="12" y1="19" x2="12" y2="5"/><polyline points="5 12 12 5 19 12"/></svg>
                                9,4%
                            </span>
                            <span style="color: #64748b;">dari bulan lalu</span>
                        </p>
                    </div>
                </article>

                {{-- Saldo Kas --}}
                <article class="stat-card" id="fin-stat-saldo">
                    <div class="stat-card-icon" style="background: #dcfce7; color: #16a34a;">
                        <svg viewBox="0 0 24 24" fill="currentColor" width="24" height="24">
                            <path d="M21 7.28V5c0-1.1-.9-2-2-2H5c-1.11 0-2 .9-2 2v14c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2v-2.28A2 2 0 0 0 22 15V9a2 2 0 0 0-1-1.72zM20 15h-4V9h4v6z"/>
                            <circle cx="18" cy="12" r="1.5" fill="#dcfce7"/>
                        </svg>
                    </div>
                    <div>
                        <p class="stat-card-label" style="font-weight: 700; color: var(--sidebar-bg);">Saldo Kas</p>
                        <p class="stat-card-value" style="color: var(--sidebar-bg);">Rp 62,2 jt</p>
                        <p class="stat-card-change" style="display: flex; align-items: center; gap: 3px;">
                            <span style="color: #16a34a; display: flex; align-items: center; font-weight: 600; gap: 2px;">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="width: 14px; height: 14px;"><line x1="12" y1="19" x2="12" y2="5"/><polyline points="5 12 12 5 19 12"/></svg>
                                22,1%
                            </span>
                            <span style="color: #64748b;">dari bulan lalu</span>
                        </p>
                    </div>
                </article>

                {{-- Transaksi Bulan Ini --}}
                <article class="stat-card" id="fin-stat-transaksi">
                    <div class="stat-card-icon" style="background: #f3e8ff; color: #7e22ce;">
                        <svg viewBox="0 0 24 24" fill="currentColor" width="24" height="24">
                            <path d="M19 3H5c-1.1 0-2 .9-2 2v16l3.5-2.5L12 21l3.5-2.5L19 21V5c0-1.1-.9-2-2-2zm-4 12H9v-1.5h6V15zm0-3.5H9V10h6v1.5zm0-3.5H9V6.5h6V8z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="stat-card-label" style="font-weight: 700; color: var(--sidebar-bg);">Transaksi Bulan Ini</p>
                        <p class="stat-card-value" style="color: var(--sidebar-bg);">1.284</p>
                        <p class="stat-card-change" style="display: flex; align-items: center; gap: 3px;">
                            <span style="color: #16a34a; display: flex; align-items: center; font-weight: 600; gap: 2px;">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="width: 14px; height: 14px;"><line x1="12" y1="19" x2="12" y2="5"/><polyline points="5 12 12 5 19 12"/></svg>
                                16,3%
                            </span>
                            <span style="color: #64748b;">dari bulan lalu</span>
                        </p>
                    </div>
                </article>

                {{-- Pembayaran Digital --}}
                <article class="stat-card" id="fin-stat-digital">
                    <div class="stat-card-icon" style="background: #dbeafe; color: #2563eb;">
                        <svg viewBox="0 0 24 24" fill="currentColor" width="24" height="24">
                            <path d="M20 4H4c-1.11 0-2 .89-2 2v12c0 1.11.89 2 2 2h16c1.11 0 2-.89 2-2V6c0-1.11-.89-2-2-2z"/>
                            <path d="M4 6h16v3H4z" fill="#dbeafe" />
                            <rect x="15" y="14" width="3" height="2" rx="0.5" fill="#dbeafe" />
                        </svg>
                    </div>
                    <div>
                        <p class="stat-card-label" style="font-weight: 700; color: var(--sidebar-bg);">Pembayaran Digital</p>
                        <p class="stat-card-value" style="color: var(--sidebar-bg);">78,5%</p>
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

            {{-- ── Charts Row ── --}}
            <section class="fin-chart-row">
                {{-- Tren Arus Kas --}}
                <div class="chart-card">
                    <div class="chart-header">
                        <h2 class="chart-title">Tren Arus Kas</h2>
                        <div class="fin-chart-legend">
                            <span class="fin-legend-item"><span class="fin-legend-dot" style="background: #2d5cf6;"></span> Pemasukan</span>
                            <span class="fin-legend-item"><span class="fin-legend-dot" style="background: #22c55e;"></span> Pengeluaran</span>
                        </div>
                    </div>
                    <div class="line-chart-container" style="height: 220px;">
                        <canvas id="financeCashflowChart"></canvas>
                    </div>
                </div>

                {{-- Komposisi Pengeluaran --}}
                <div class="chart-card">
                    <div class="chart-header">
                        <h2 class="chart-title">Komposisi Pengeluaran</h2>
                    </div>
                    <div class="fin-expense-chart-container">
                        <div class="fin-donut-wrapper">
                            <canvas id="financeExpenseChart"></canvas>
                        </div>
                        <div class="fin-expense-legend">
                            <div class="fin-expense-legend-item">
                                <span class="fin-expense-legend-label"><span class="donut-legend-dot" style="background: #2d5cf6;"></span> Operasional Desa</span>
                                <span class="fin-expense-legend-values">Rp 62,8 jt <span class="fin-expense-pct">(33,7%)</span></span>
                            </div>
                            <div class="fin-expense-legend-item">
                                <span class="fin-expense-legend-label"><span class="donut-legend-dot" style="background: #22c55e;"></span> Program Sosial</span>
                                <span class="fin-expense-legend-values">Rp 48,6 jt <span class="fin-expense-pct">(26,1%)</span></span>
                            </div>
                            <div class="fin-expense-legend-item">
                                <span class="fin-expense-legend-label"><span class="donut-legend-dot" style="background: #f59e0b;"></span> MBG</span>
                                <span class="fin-expense-legend-values">Rp 32,4 jt <span class="fin-expense-pct">(17,4%)</span></span>
                            </div>
                            <div class="fin-expense-legend-item">
                                <span class="fin-expense-legend-label"><span class="donut-legend-dot" style="background: #a855f7;"></span> Infrastruktur</span>
                                <span class="fin-expense-legend-values">Rp 24,2 jt <span class="fin-expense-pct">(13,0%)</span></span>
                            </div>
                            <div class="fin-expense-legend-item">
                                <span class="fin-expense-legend-label"><span class="donut-legend-dot" style="background: #ef4444;"></span> Lainnya</span>
                                <span class="fin-expense-legend-values">Rp 18,4 jt <span class="fin-expense-pct">(9,8%)</span></span>
                            </div>
                            <div class="fin-expense-total">
                                <span>Total</span>
                                <span class="fin-expense-total-value">Rp 186,4 jt</span>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            {{-- ── Bottom Tables Row ── --}}
            <section class="fin-tables-row">
                {{-- Ringkasan Akun / Pos Keuangan --}}
                <div class="fin-table-card">
                    <div class="bottom-card-header">
                        <h3 class="bottom-card-title">Ringkasan Akun / Pos Keuangan</h3>
                    </div>
                    <div class="fin-table-wrapper">
                        <table class="fin-table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Pos</th>
                                    <th>Jenis</th>
                                    <th>Realisasi Bulan Ini</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>1</td>
                                    <td class="fin-td-name">Dana Desa</td>
                                    <td>Pemasukan</td>
                                    <td class="fin-td-amount">Rp 142.500.000</td>
                                    <td><span class="fin-badge fin-badge-success">Aktif</span></td>
                                </tr>
                                <tr>
                                    <td>2</td>
                                    <td class="fin-td-name">Pendapatan Usaha BUMDes</td>
                                    <td>Pemasukan</td>
                                    <td class="fin-td-amount">Rp 48.600.000</td>
                                    <td><span class="fin-badge fin-badge-success">Aktif</span></td>
                                </tr>
                                <tr>
                                    <td>3</td>
                                    <td class="fin-td-name">Retribusi Pasar</td>
                                    <td>Pemasukan</td>
                                    <td class="fin-td-amount">Rp 22.300.000</td>
                                    <td><span class="fin-badge fin-badge-success">Aktif</span></td>
                                </tr>
                                <tr>
                                    <td>4</td>
                                    <td class="fin-td-name">Donasi / CSR</td>
                                    <td>Pemasukan</td>
                                    <td class="fin-td-amount">Rp 18.200.000</td>
                                    <td><span class="fin-badge fin-badge-warning">Perlu Review</span></td>
                                </tr>
                                <tr>
                                    <td>5</td>
                                    <td class="fin-td-name">Lainnya</td>
                                    <td>Pemasukan</td>
                                    <td class="fin-td-amount">Rp 17.000.000</td>
                                    <td><span class="fin-badge fin-badge-success">Aktif</span></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="fin-table-footer">
                        <a href="#" class="fin-table-link">Lihat Semua Pos Keuangan <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width: 14px; height: 14px;"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg></a>
                    </div>
                </div>

                {{-- Transaksi Terbaru / Aktivitas Keuangan --}}
                <div class="fin-table-card">
                    <div class="bottom-card-header">
                        <h3 class="bottom-card-title">Transaksi Terbaru / Aktivitas Keuangan</h3>
                    </div>
                    <div class="fin-table-wrapper">
                        <table class="fin-table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Tanggal</th>
                                    <th>Aktivitas</th>
                                    <th>Nominal</th>
                                    <th>Sumber / Tujuan</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>1</td>
                                    <td class="fin-td-date">22 Mei 2025</td>
                                    <td class="fin-td-name">Pembayaran Supplier ATK</td>
                                    <td class="fin-td-amount fin-amount-expense">- Rp 5.250.000</td>
                                    <td>Operasional Desa</td>
                                    <td><span class="fin-badge fin-badge-success">Selesai</span></td>
                                </tr>
                                <tr>
                                    <td>2</td>
                                    <td class="fin-td-date">22 Mei 2025</td>
                                    <td class="fin-td-name">Penerimaan Retribusi Pasar</td>
                                    <td class="fin-td-amount fin-amount-income">+ Rp 3.750.000</td>
                                    <td>Pasar Desa</td>
                                    <td><span class="fin-badge fin-badge-success">Selesai</span></td>
                                </tr>
                                <tr>
                                    <td>3</td>
                                    <td class="fin-td-date">21 Mei 2025</td>
                                    <td class="fin-td-name">Pencairan Bantuan MBG</td>
                                    <td class="fin-td-amount fin-amount-income">+ Rp 24.000.000</td>
                                    <td>Kemenkeu</td>
                                    <td><span class="fin-badge fin-badge-info">Diproses</span></td>
                                </tr>
                                <tr>
                                    <td>4</td>
                                    <td class="fin-td-date">21 Mei 2025</td>
                                    <td class="fin-td-name">Pembayaran Honor Perangkat</td>
                                    <td class="fin-td-amount fin-amount-expense">- Rp 7.800.000</td>
                                    <td>Operasional Desa</td>
                                    <td><span class="fin-badge fin-badge-warning">Menunggu Verifikasi</span></td>
                                </tr>
                                <tr>
                                    <td>5</td>
                                    <td class="fin-td-date">20 Mei 2025</td>
                                    <td class="fin-td-name">Pembelian Material Infrastruktur</td>
                                    <td class="fin-td-amount fin-amount-expense">- Rp 18.450.000</td>
                                    <td>Dana Desa</td>
                                    <td><span class="fin-badge fin-badge-danger">Tertunda</span></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="fin-table-footer">
                        <a href="#" class="fin-table-link">Lihat Semua Aktivitas <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width: 14px; height: 14px;"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg></a>
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
                        <div style="color: var(--text-secondary);">Kemitraan Strategis untuk Desa Digital</div>
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
        // ── Inline datalabels plugin for cashflow chart ──
        const cashflowDatalabels = {
            id: 'cashflowDatalabels',
            afterDatasetsDraw(chart) {
                const { ctx } = chart;
                chart.data.datasets.forEach((dataset, i) => {
                    const meta = chart.getDatasetMeta(i);
                    meta.data.forEach((point, index) => {
                        const value = dataset.data[index];
                        ctx.save();
                        ctx.font = '600 10px Inter, sans-serif';
                        ctx.fillStyle = dataset.borderColor;
                        ctx.textAlign = 'center';
                        ctx.textBaseline = 'bottom';
                        ctx.fillText(value + ' jt', point.x, point.y - 8);
                        ctx.restore();
                    });
                });
            }
        };

        // ── Tren Arus Kas (Line Chart) ──
        const cashflowCtx = document.getElementById('financeCashflowChart')?.getContext('2d');
        if (cashflowCtx) {
            const gradientBlue = cashflowCtx.createLinearGradient(0, 0, 0, 220);
            gradientBlue.addColorStop(0, 'rgba(45, 92, 246, 0.12)');
            gradientBlue.addColorStop(1, 'rgba(45, 92, 246, 0.01)');

            const gradientGreen = cashflowCtx.createLinearGradient(0, 0, 0, 220);
            gradientGreen.addColorStop(0, 'rgba(34, 197, 94, 0.08)');
            gradientGreen.addColorStop(1, 'rgba(34, 197, 94, 0.01)');

            new Chart(cashflowCtx, {
                type: 'line',
                data: {
                    labels: ['Des 2024', 'Jan 2025', 'Feb 2025', 'Mar 2025', 'Apr 2025', 'Mei 2025'],
                    datasets: [
                        {
                            label: 'Pemasukan',
                            data: [162, 178, 192, 210, 228, 248],
                            borderColor: '#2d5cf6',
                            backgroundColor: gradientBlue,
                            borderWidth: 2.5,
                            tension: 0.35,
                            fill: true,
                            pointRadius: 4,
                            pointBackgroundColor: '#2d5cf6',
                            pointBorderColor: '#ffffff',
                            pointBorderWidth: 2,
                            pointHoverRadius: 6,
                        },
                        {
                            label: 'Pengeluaran',
                            data: [112, 124, 138, 156, 172, 186],
                            borderColor: '#22c55e',
                            backgroundColor: gradientGreen,
                            borderWidth: 2.5,
                            tension: 0.35,
                            fill: true,
                            pointRadius: 4,
                            pointBackgroundColor: '#22c55e',
                            pointBorderColor: '#ffffff',
                            pointBorderWidth: 2,
                            pointHoverRadius: 6,
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
                            callbacks: {
                                label: (ctx) => ` ${ctx.dataset.label}: Rp ${ctx.parsed.y} jt`,
                            }
                        }
                    },
                    scales: {
                        x: {
                            grid: { display: false },
                            ticks: { font: { family: 'Inter', size: 10 }, color: '#9ca3af' },
                            border: { display: false }
                        },
                        y: {
                            grid: { color: '#f3f4f6' },
                            ticks: {
                                font: { family: 'Inter', size: 10 },
                                color: '#9ca3af',
                                callback: (v) => v === 0 ? '0' : v + ' jt',
                                stepSize: 50,
                            },
                            border: { display: false },
                            min: 0,
                            max: 300,
                        }
                    }
                },
                plugins: [cashflowDatalabels]
            });
        }

        // ── Komposisi Pengeluaran (Donut Chart) ──
        const expenseCtx = document.getElementById('financeExpenseChart')?.getContext('2d');
        if (expenseCtx) {
            new Chart(expenseCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Operasional Desa', 'Program Sosial', 'MBG', 'Infrastruktur', 'Lainnya'],
                    datasets: [{
                        data: [62.8, 48.6, 32.4, 24.2, 18.4],
                        backgroundColor: ['#2d5cf6', '#22c55e', '#f59e0b', '#a855f7', '#ef4444'],
                        borderWidth: 3,
                        borderColor: '#ffffff',
                        hoverOffset: 6,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    cutout: '62%',
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: '#1a1d2e',
                            titleFont: { family: 'Inter', size: 12 },
                            bodyFont: { family: 'Inter', size: 12 },
                            padding: 10,
                            cornerRadius: 8,
                            callbacks: {
                                label: (ctx) => ` ${ctx.label}: Rp ${ctx.parsed} jt`,
                            }
                        }
                    }
                }
            });
        }

        // ── Tab switching ──
        const tabs = document.querySelectorAll('.fin-tab');
        tabs.forEach(tab => {
            tab.addEventListener('click', () => {
                tabs.forEach(t => t.classList.remove('active'));
                tab.classList.add('active');
            });
        });

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
