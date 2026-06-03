import './bootstrap';

document.addEventListener('DOMContentLoaded', () => {
    const sidebar = document.querySelector('[data-sidebar]');
    const overlay = document.querySelector('[data-sidebar-overlay]');
    const content = document.querySelector('[data-main-content]');
    const topbar = document.querySelector('[data-topbar]');
    const toggles = document.querySelectorAll('[data-sidebar-toggle]');
    const sidebarTexts = sidebar?.querySelectorAll('.sidebar-nav span, [data-sidebar-brand-text], .sidebar-category') ?? [];
    let desktopCollapsed = false;
    let mobileOpen = false;

    if (!sidebar || toggles.length === 0) return;

    const isDesktop = () => window.innerWidth >= 1024;

    // Collapse sidebar (desktop)
    const setDesktopCollapsed = (collapsed) => {
        desktopCollapsed = collapsed;

        if (collapsed) {
            sidebar.style.width = '72px';
            if (topbar) topbar.style.left = '72px';
            if (content) content.style.marginLeft = '72px';
        } else {
            sidebar.style.width = '260px';
            if (topbar) topbar.style.left = '260px';
            if (content) content.style.marginLeft = '260px';
        }

        sidebarTexts.forEach(el => {
            el.style.display = collapsed ? 'none' : '';
        });
    };

    // Mobile open/close
    const setMobileOpen = (open) => {
        mobileOpen = open;
        if (open) {
            sidebar.classList.add('open');
            overlay?.classList.add('show');
        } else {
            sidebar.classList.remove('open');
            overlay?.classList.remove('show');
        }
    };

    toggles.forEach(btn => {
        btn.addEventListener('click', () => {
            if (isDesktop()) {
                setDesktopCollapsed(!desktopCollapsed);
            } else {
                setMobileOpen(!mobileOpen);
            }
        });
    });

    overlay?.addEventListener('click', () => setMobileOpen(false));

    // Handle resize
    window.addEventListener('resize', () => {
        if (isDesktop()) {
            // Reset mobile state
            sidebar.classList.remove('open');
            overlay?.classList.remove('show');
            mobileOpen = false;

            // Apply desktop state
            setDesktopCollapsed(desktopCollapsed);
        } else {
            // Reset desktop widths to CSS defaults
            sidebar.style.width = '';
            if (topbar) topbar.style.left = '';
            if (content) content.style.marginLeft = '';
            sidebarTexts.forEach(el => el.style.display = '');
        }
    });

    // Initial mobile setup
    if (!isDesktop()) {
        sidebar.style.width = '';
        if (topbar) topbar.style.left = '';
        if (content) content.style.marginLeft = '';
    }
});
