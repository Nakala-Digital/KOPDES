@props(['name'])

@php
    $paths = [
        'dashboard' => '<path d="M3 11.5 12 4l9 7.5"/><path d="M5 10.5V20h5v-5h4v5h5v-9.5"/>',
        'role' => '<path d="M8 7a4 4 0 1 0 8 0 4 4 0 0 0-8 0Z"/><path d="M4 21a8 8 0 0 1 16 0"/>',
        'flow' => '<path d="M6 4v6h6"/><path d="M6 10a6 6 0 0 0 12 0V4"/><path d="M18 20v-6h-6"/>',
        'pendataan' => '<path d="M7 3h10l2 2v16H5V5l2-2Z"/><path d="M9 9h6"/><path d="M9 13h6"/><path d="M9 17h4"/>',
        'kopdes' => '<path d="M4 10h16"/><path d="M6 10v9"/><path d="M10 10v9"/><path d="M14 10v9"/><path d="M18 10v9"/><path d="M3 19h18"/><path d="M12 4 4 10h16l-8-6Z"/>',
        'bumdes' => '<path d="M4 21V8l8-5 8 5v13"/><path d="M9 21v-7h6v7"/><path d="M8 10h.01"/><path d="M16 10h.01"/>',
        'umkm' => '<path d="M4 9h16l-1 12H5L4 9Z"/><path d="M8 9a4 4 0 0 1 8 0"/><path d="M9 14h6"/>',
        'mbg' => '<path d="M5 11h14"/><path d="M7 11v8h10v-8"/><path d="M9 7a3 3 0 0 1 6 0v4"/><path d="M12 14v3"/>',
    ];
@endphp

<svg {{ $attributes->merge(['class' => 'h-5 w-5 shrink-0']) }} viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
    {!! $paths[$name] ?? $paths['dashboard'] !!}
</svg>
