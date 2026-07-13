import './firebase';
import Sortable from 'sortablejs';
import autoAnimate from '@formkit/auto-animate';
import { createGrid } from 'ag-grid-community';
import 'ag-grid-community/styles/ag-grid.css';
import 'ag-grid-community/styles/ag-theme-quartz.css';

window.Sortable = Sortable;
window.autoAnimate = autoAnimate;
window.agGrid = { createGrid };

document.addEventListener('DOMContentLoaded', () => {
    const sidebar = document.getElementById('appSidebar');
    const workspace = document.getElementById('workspace');
    const toggle = document.getElementById('sidebarToggle');

    const setSidebar = (collapsed) => {
        sidebar?.classList.toggle('is-collapsed', collapsed);
        workspace?.classList.toggle('sidebar-collapsed', collapsed);
        toggle?.setAttribute('aria-label', collapsed ? 'Expand navigation' : 'Collapse navigation');
    };
    setSidebar(localStorage.getItem('solcon-sidebar') === 'collapsed');
    toggle?.addEventListener('click', () => {
        const collapsed = !sidebar.classList.contains('is-collapsed');
        setSidebar(collapsed);
        localStorage.setItem('solcon-sidebar', collapsed ? 'collapsed' : 'expanded');
    });

    const clock = document.getElementById('liveClock');
    const updateClock = () => {
        if (clock) clock.textContent = new Intl.DateTimeFormat('en-IN', { hour: '2-digit', minute: '2-digit', hour12: true, timeZone: 'Asia/Kolkata' }).format(new Date());
    };
    updateClock();
    window.setInterval(updateClock, 30000);

    const palette = document.getElementById('searchPalette');
    const input = document.getElementById('globalSearchInput');
    const openSearch = () => { palette?.classList.remove('hidden'); palette?.classList.add('flex'); window.setTimeout(() => input?.focus(), 60); };
    const closeSearch = () => { palette?.classList.add('hidden'); palette?.classList.remove('flex'); };
    document.getElementById('globalSearchButton')?.addEventListener('click', openSearch);
    document.querySelector('[data-close-search]')?.addEventListener('click', closeSearch);
    palette?.addEventListener('click', (event) => { if (event.target === palette) closeSearch(); });
    document.addEventListener('keydown', (event) => {
        if ((event.metaKey || event.ctrlKey) && event.key.toLowerCase() === 'k') { event.preventDefault(); openSearch(); }
        if (event.key === 'Escape') closeSearch();
    });

    // Mobile Drawer Toggle
    const mobileToggle = document.getElementById('mobileSidebarToggle');
    const overlay = document.getElementById('sidebarOverlay');

    mobileToggle?.addEventListener('click', () => {
        sidebar?.classList.add('is-mobile-open');
        overlay?.classList.add('is-visible');
    });

    overlay?.addEventListener('click', () => {
        sidebar?.classList.remove('is-mobile-open');
        overlay?.classList.remove('is-visible');
    });

    // Global form submission loading spinner
    document.addEventListener('submit', (e) => {
        if (e.target.hasAttribute('data-no-loading') || e.target.id === 'globalSearchInput' || e.target.classList.contains('ajax-form')) {
            return;
        }
        const submitBtn = e.target.querySelector('button[type="submit"]');
        if (submitBtn) {
            submitBtn.disabled = true;
            submitBtn.innerHTML = `
                <span class="inline-flex items-center gap-2">
                    <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span>Processing...</span>
                </span>
            `;
        }
    });

    document.querySelectorAll('.flash-message').forEach((message) => window.setTimeout(() => {
        message.style.opacity = '0'; message.style.transform = 'translateY(-6px)';
        window.setTimeout(() => message.remove(), 240);
    }, 4500));

    document.querySelectorAll('.page-content table').forEach((table) => table.classList.add('responsive-table'));
    document.querySelectorAll('.page-content table').forEach((table) => {
        const labels = [...table.querySelectorAll('thead th')].map((th) => th.textContent.trim());
        table.querySelectorAll('tbody tr').forEach((row) => [...row.children].forEach((cell, index) => {
            if (!cell.hasAttribute('colspan')) cell.dataset.label = labels[index] || '';
        }));
    });
});
