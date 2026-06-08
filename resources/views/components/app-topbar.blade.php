@props(['title' => 'Dashboard Utama'])

<header class="topbar" data-topbar>
    <div class="topbar-left">
        <button class="topbar-hamburger" id="menu-toggle" aria-label="Toggle Menu" data-sidebar-toggle>
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width: 20px; height: 20px;">
                <line x1="4" x2="20" y1="12" y2="12"/>
                <line x1="4" x2="20" y1="6" y2="6"/>
                <line x1="4" x2="14" y1="18" y2="18"/>
            </svg>
        </button>
        <span class="topbar-title">{{ $title }}</span>
    </div>

    <div class="topbar-right">
        <button class="topbar-icon-btn" aria-label="Help">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"/>
                <path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/>
                <path d="M12 17h.01"/>
            </svg>
        </button>
        <button class="topbar-icon-btn" aria-label="Notifications" style="position: relative;">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9"/>
                <path d="M10.3 21a1.94 1.94 0 0 0 3.4 0"/>
            </svg>
            <span class="notification-badge">3</span>
        </button>

        <div class="topbar-user" style="position: relative;" data-user-dropdown-trigger>
            <img src="{{ asset('assets/desahub/kepala-desa.png') }}" alt="User Avatar" class="topbar-user-avatar" style="object-fit: cover;" />
            <div class="topbar-user-info">
                <div class="topbar-user-name">Kepala Desa</div>
                <div class="topbar-user-role">Desa Maju</div>
            </div>
            <svg class="topbar-user-chevron" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="m6 9 6 6 6-6"/>
            </svg>

            <div class="user-dropdown" data-user-dropdown>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="m7 17 10-10"/>
                            <path d="M17 17V7H7"/>
                        </svg>
                        Logout
                    </button>
                </form>
            </div>
        </div>
    </div>
</header>
