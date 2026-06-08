<div class="sidebar-overlay" data-sidebar-overlay></div>

<aside class="sidebar" data-sidebar>
    <div class="sidebar-brand">
        <div class="sidebar-brand-mark" aria-hidden="true">
            <img src="{{ asset('assets/desahub/logo-desahub-transparent.png') }}" alt="">
        </div>
        <div class="sidebar-brand-copy" data-sidebar-brand-text>
            <strong><span>Desa</span>Hub</strong>
            <small>Ekosistem Ekonomi Desa</small>
        </div>
    </div>

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

    <button class="sidebar-collapse-btn" data-sidebar-toggle>
        <x-sidebar-icon name="collapse" />
        <span data-sidebar-brand-text>Persempit Menu</span>
    </button>
</aside>
