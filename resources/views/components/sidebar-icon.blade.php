@props(['name'])

@php
    $paths = [
        'dashboard' => '<rect x="2" y="2" width="8" height="8" rx="2.5" fill="currentColor" stroke="none"/><rect x="14.75" y="2.75" width="6.5" height="6.5" rx="1.75" fill="none" stroke="currentColor" stroke-width="1.5"/><rect x="2.75" y="14.75" width="6.5" height="6.5" rx="1.75" fill="none" stroke="currentColor" stroke-width="1.5"/><rect x="14" y="14" width="8" height="8" rx="2.5" fill="currentColor" stroke="none"/>',
        'pendataan-desa' => '<rect width="8" height="4" x="8" y="2" rx="1" ry="1"/><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/><path d="M12 11h4"/><path d="M12 16h4"/><path d="M8 11h.01"/><path d="M8 16h.01"/>',
        'penduduk' => '<path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>',
        'potensi-desa' => '<circle cx="12" cy="12" r="10"/><circle cx="12" cy="12" r="6"/><circle cx="12" cy="12" r="2"/>',
        'kopdes' => '<path d="M4 11v8a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-8H4Z M14 20v-3a2 2 0 0 1 4 0v3 M7 20v-1a2 2 0 0 1 4 0v1 M6 15h2 M11 15h2 M3 11l8-6h9v5"/>',
        'bumdes' => '<path d="M5.5 10.5 12 5l6.5 5.5"/><path d="M6.5 10.5V20h11V10.5"/><rect x="8.5" y="12.6" width="2" height="2" rx="0.35" fill="none"/><rect x="13.5" y="12.6" width="2" height="2" rx="0.35" fill="none"/><path d="M9 16h6"/><path d="M10.5 20v-4.5h3V20"/>',
        'umkm' => '<path d="m2 7 4.41-4.41A2 2 0 0 1 7.83 2h8.34a2 2 0 0 1 1.42.59L22 7"/><path d="M4 12v8a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-8"/><path d="M15 22v-4a2 2 0 0 0-2-2h-2a2 2 0 0 0-2 2v4"/><path d="M2 9.782a2 2 0 0 0 2.22 1.993 2.01 2.01 0 0 0 3.824 0 2 2 0 0 0 3.825 0 2 2 0 0 0 3.825 0 2.01 2.01 0 0 0 3.825 0 2 2 0 0 0 2.22-1.993A2 2 0 0 0 20 7H4a2 2 0 0 0-2 2.782Z"/>',
        'pasar-desa' => '<circle cx="8" cy="21" r="1"/><circle cx="19" cy="21" r="1"/><path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12"/>',
        'rantai-pasok' => '<rect x="16" y="16" width="6" height="6" rx="1"/><rect x="2" y="16" width="6" height="6" rx="1"/><rect x="9" y="2" width="6" height="6" rx="1"/><path d="M5 16v-3a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1v3"/><path d="M12 12V8"/>',
        'gudang' => '<path d="M10 17h4V5H2v12h3"/><path d="M20 17h2v-3.34a4 4 0 0 0-1.17-2.83L19 9h-5"/><path d="M14 17h1"/><circle cx="7.5" cy="17.5" r="2.5"/><circle cx="17.5" cy="17.5" r="2.5"/>',
        'penerima-manfaat' => '<polyline points="20 12 20 22 4 22 4 12"/><rect width="20" height="5" x="2" y="7"/><line x1="12" x2="12" y1="22" y2="7"/><path d="M12 7H7.5a2.5 2.5 0 0 1 0-5C11 2 12 7 12 7z"/><path d="M12 7h4.5a2.5 2.5 0 0 0 0-5C13 2 12 7 12 7z"/>',
        'keuangan' => '<rect width="20" height="12" x="2" y="6" rx="2"/><circle cx="12" cy="12" r="2"/><path d="M6 12h.01M18 12h.01"/>',
        'dashboard-analitik' => '<path d="M3 3v18h18"/><rect width="4" height="7" x="7" y="14" rx="1"/><rect width="4" height="12" x="15" y="9" rx="1"/>',
        'laporan' => '<path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z"/><path d="M14 2v4a2 2 0 0 0 2 2h4"/><path d="M10 9H8"/><path d="M16 13H8"/><path d="M16 17H8"/>',
        'pengaturan' => '<path d="M12.22 2h-.44a2 2 0 0 0-2 2v.18a2 2 0 0 1-1 1.73l-.43.25a2 2 0 0 1-2 0l-.15-.08a2 2 0 0 0-2.73.73l-.22.38a2 2 0 0 0 .73 2.73l.15.1a2 2 0 0 1 1 1.72v.51a2 2 0 0 1-1 1.74l-.15.09a2 2 0 0 0-.73 2.73l.22.38a2 2 0 0 0 2.73.73l.15-.08a2 2 0 0 1 2 0l.43.25a2 2 0 0 1 1 1.73V20a2 2 0 0 0 2 2h.44a2 2 0 0 0 2-2v-.18a2 2 0 0 1 1-1.73l.43-.25a2 2 0 0 1 2 0l.15.08a2 2 0 0 0 2.73-.73l.22-.38a2 2 0 0 0-.73-2.73l-.15-.09a2 2 0 0 1-1-1.74v-.51a2 2 0 0 1 1-1.72l.15-.1a2 2 0 0 0 .73-2.73l-.22-.38a2 2 0 0 0-2.73-.73l-.15.08a2 2 0 0 1-2 0l-.43-.25a2 2 0 0 1-1-1.73V4a2 2 0 0 0-2-2Z"/><circle cx="12" cy="12" r="3"/>',
        'collapse' => '<rect width="18" height="18" x="3" y="3" rx="2" ry="2"/><path d="M9 3v18"/><path d="m16 15-3-3 3-3"/>',
    ];
    $paths['pendataan'] = $paths['pendataan-desa'];
    $paths['mbg'] = $paths['rantai-pasok'];
@endphp

<svg {{ $attributes->merge(['class' => 'sidebar-svg-icon']) }} viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
    {!! $paths[$name] ?? $paths['dashboard'] !!}
</svg>
