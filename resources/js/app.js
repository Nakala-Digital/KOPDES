import './bootstrap';

document.addEventListener('DOMContentLoaded', () => {
    const sidebar = document.querySelector('[data-sidebar]');
    const overlay = document.querySelector('[data-sidebar-overlay]');
    const content = document.querySelector('[data-main-content]');
    const topbar = document.querySelector('[data-topbar]');
    const toggles = document.querySelectorAll('[data-sidebar-toggle]');
    const sidebarLinks = sidebar?.querySelectorAll('nav a') ?? [];
    const sidebarLabels = sidebar?.querySelectorAll('nav a span, [data-sidebar-brand-text]') ?? [];
    let desktopCollapsed = false;
    let mobileExpanded = false;

    if (!sidebar || toggles.length === 0) {
        return;
    }

    const setCompactSidebar = (collapsed, breakpoint = '') => {
        const widthClass = breakpoint ? `${breakpoint}:w-20` : 'w-20';
        const expandedClass = breakpoint ? `${breakpoint}:w-72` : 'w-72';
        const justifyClass = breakpoint ? `${breakpoint}:justify-center` : 'justify-center';
        const paddingClass = breakpoint ? `${breakpoint}:px-3` : 'px-3';
        const hiddenClass = breakpoint ? `${breakpoint}:hidden` : 'hidden';

        sidebar.classList.toggle(widthClass, collapsed);
        sidebar.classList.toggle(expandedClass, !collapsed);

        sidebarLinks.forEach((link) => {
            link.classList.toggle(justifyClass, collapsed);
            link.classList.toggle(paddingClass, collapsed);
        });

        sidebarLabels.forEach((label) => {
            label.classList.toggle(hiddenClass, collapsed);
        });
    };

    const setMobile = (expanded) => {
        mobileExpanded = expanded;
        setCompactSidebar(!expanded);
        overlay?.classList.toggle('hidden', !expanded);
    };

    const toggleDesktop = () => {
        desktopCollapsed = !desktopCollapsed;
        setCompactSidebar(desktopCollapsed, 'lg');
        content?.classList.toggle('lg:ml-72', !desktopCollapsed);
        content?.classList.toggle('lg:ml-20', desktopCollapsed);
        topbar?.classList.toggle('lg:left-72', !desktopCollapsed);
        topbar?.classList.toggle('lg:left-20', desktopCollapsed);
    };

    toggles.forEach((button) => {
        button.addEventListener('click', () => {
            if (window.innerWidth >= 1024) {
                toggleDesktop();
                return;
            }

            setMobile(!mobileExpanded);
        });
    });

    overlay?.addEventListener('click', () => setMobile(false));

    window.addEventListener('resize', () => {
        if (window.innerWidth < 1024) {
            desktopCollapsed = false;
            sidebar.classList.remove('lg:w-20');
            sidebar.classList.add('lg:w-72');
            content?.classList.add('lg:ml-72');
            content?.classList.remove('lg:ml-20');
            topbar?.classList.add('lg:left-72');
            topbar?.classList.remove('lg:left-20');
            sidebarLinks.forEach((link) => {
                link.classList.remove('lg:justify-center', 'lg:px-3');
            });
            sidebarLabels.forEach((label) => {
                label.classList.remove('lg:hidden');
            });
        }
    });

    if (window.innerWidth < 1024) {
        setMobile(false);
    }
});
