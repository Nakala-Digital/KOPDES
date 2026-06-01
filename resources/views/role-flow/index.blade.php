<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Role Flow | {{ config('app.name', 'KopDes') }}</title>
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
</head>
<body class="min-h-screen bg-red-50 text-red-950">
@php
    $totalPermissions = collect($roles)->flatMap(fn ($role) => $role['permissions'] ?? [])->unique()->count();
    $totalMenus = collect($roles)->flatMap(fn ($role) => $role['menus'] ?? [])->unique()->count();
    $validationStep = collect($flowSteps)->firstWhere('type', 'validation');
    $successBranch = collect($validationStep['branches'] ?? [])->firstWhere('status', 'success');
    $failBranch = collect($validationStep['branches'] ?? [])->firstWhere('status', 'fail');
@endphp
<div class="min-h-screen lg:flex">
    <div data-sidebar-overlay class="fixed inset-0 z-30 hidden bg-red-950/40 lg:hidden"></div>

    <header data-topbar class="fixed inset-x-0 left-20 top-0 z-20 flex h-16 items-center justify-between border-b border-red-100 bg-white px-4 shadow-sm transition-all duration-200 lg:left-72 sm:px-6">
        <div class="flex items-center gap-3">
            <button type="button" data-sidebar-toggle class="inline-flex h-10 w-10 items-center justify-center rounded-lg border border-red-100 bg-white text-red-700 transition hover:bg-red-50" aria-label="Buka tutup sidebar">
                <span class="flex flex-col gap-1.5">
                    <span class="block h-0.5 w-5 bg-red-700"></span>
                    <span class="block h-0.5 w-5 bg-red-700"></span>
                    <span class="block h-0.5 w-5 bg-red-700"></span>
                </span>
            </button>
            <div>
                <p class="text-sm font-bold text-red-950">{{ config('app.name', 'KopDes') }}</p>
                <p class="text-xs font-semibold text-red-700">Role Flow</p>
            </div>
        </div>
        <div class="flex items-center gap-3">
            <span class="hidden text-sm font-semibold text-red-700 sm:inline">{{ auth()->user()->name }}</span>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="rounded-lg bg-red-700 px-4 py-2 text-sm font-semibold text-white transition hover:bg-red-800">Logout</button>
            </form>
        </div>
    </header>

    <aside data-sidebar class="fixed inset-y-0 left-0 z-40 flex w-20 flex-col overflow-hidden bg-red-700 text-white transition-all duration-200 lg:w-72">
        <div class="flex items-center gap-3 px-6 py-5 lg:px-7 lg:py-7">
            <a href="{{ route('dashboard') }}" class="flex h-11 w-11 items-center justify-center rounded-lg bg-white text-lg font-bold text-red-700 shadow-sm">K</a>
            <div data-sidebar-brand-text>
                <p class="text-lg font-bold leading-tight">KopDes</p>
                <p class="text-xs font-medium text-red-100">Merah Putih</p>
            </div>
        </div>

        <nav class="flex gap-2 overflow-x-auto px-4 pb-5 lg:mt-4 lg:flex-1 lg:flex-col lg:overflow-visible lg:px-5 lg:pb-0">
            <a href="{{ route('dashboard') }}" class="flex items-center gap-3 whitespace-nowrap rounded-lg px-4 py-3 text-sm font-semibold text-red-50 transition hover:bg-white/10 lg:w-full"><x-sidebar-icon name="dashboard" /><span>Dashboard</span></a>
            <a href="{{ route('role-flow.index') }}" class="flex items-center gap-3 whitespace-nowrap rounded-lg bg-white px-4 py-3 text-sm font-semibold text-red-700 shadow-sm lg:w-full"><x-sidebar-icon name="role" /><span>Role Flow</span></a>
            <a href="{{ route('pendataan.index') }}" class="flex items-center gap-3 whitespace-nowrap rounded-lg px-4 py-3 text-sm font-semibold text-red-50 transition hover:bg-white/10 lg:w-full"><x-sidebar-icon name="pendataan" /><span>Pendataan</span></a>
            <a href="{{ route('kopdes.index') }}" class="flex items-center gap-3 whitespace-nowrap rounded-lg px-4 py-3 text-sm font-semibold text-red-50 transition hover:bg-white/10 lg:w-full"><x-sidebar-icon name="kopdes" /><span>Kopdes</span></a>
            <a href="{{ route('bumdes.index') }}" class="flex items-center gap-3 whitespace-nowrap rounded-lg px-4 py-3 text-sm font-semibold text-red-50 transition hover:bg-white/10 lg:w-full"><x-sidebar-icon name="bumdes" /><span>BUMDes</span></a>
            <a href="{{ route('umkm.index') }}" class="flex items-center gap-3 whitespace-nowrap rounded-lg px-4 py-3 text-sm font-semibold text-red-50 transition hover:bg-white/10 lg:w-full"><x-sidebar-icon name="umkm" /><span>UMKM</span></a>
            <a href="{{ route('mbg.index') }}" class="flex items-center gap-3 whitespace-nowrap rounded-lg px-4 py-3 text-sm font-semibold text-red-50 transition hover:bg-white/10 lg:w-full"><x-sidebar-icon name="mbg" /><span>MBG</span></a>
        </nav>
    </aside>

    <main data-main-content class="ml-20 min-w-0 px-5 pb-6 pt-24 transition-all duration-200 sm:px-8 lg:ml-72 lg:px-10 lg:pb-8">
        <div class="mx-auto max-w-7xl">
            <header class="rounded-lg border border-red-100 bg-white p-6 shadow-lg shadow-red-900/5">
                <p class="text-sm font-semibold text-red-700">Role Flow</p>
                <div class="mt-2 flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-red-950">Alur Login dan Hak Akses</h1>
                        <p class="mt-2 max-w-3xl text-sm leading-6 text-red-800">Dokumentasi ringkas untuk melihat urutan login, validasi role, dashboard tujuan, menu, dan permission setiap pengguna.</p>
                    </div>
                    <div class="rounded-lg bg-red-50 px-4 py-3 text-sm font-bold text-red-700">
                        {{ count($roles) }} role aktif
                    </div>
                </div>
            </header>

            <section class="mt-6 grid gap-4 sm:grid-cols-3">
                <div class="rounded-lg border border-red-100 bg-white p-5 shadow-sm">
                    <p class="text-sm font-semibold text-red-600">Step Alur</p>
                    <p class="mt-2 text-3xl font-bold text-red-950">{{ count($flowSteps) }}</p>
                </div>
                <div class="rounded-lg border border-red-100 bg-white p-5 shadow-sm">
                    <p class="text-sm font-semibold text-red-600">Permission</p>
                    <p class="mt-2 text-3xl font-bold text-red-950">{{ $totalPermissions }}</p>
                </div>
                <div class="rounded-lg border border-red-100 bg-white p-5 shadow-sm">
                    <p class="text-sm font-semibold text-red-600">Menu Unik</p>
                    <p class="mt-2 text-3xl font-bold text-red-950">{{ $totalMenus }}</p>
                </div>
            </section>

            <section class="mt-6 rounded-lg border border-red-100 bg-white p-6 shadow-sm">
                <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h2 class="text-xl font-bold text-red-950">Alur Aplikasi</h2>
                        <p class="mt-1 text-sm text-red-800">Dari login sampai dashboard role.</p>
                    </div>
                    <span class="rounded-lg bg-red-700 px-3 py-2 text-xs font-bold text-white">Flow</span>
                </div>

                <div class="mt-6 grid gap-4 lg:grid-cols-6">
                    @foreach ($flowSteps as $index => $step)
                        <article class="relative rounded-lg border border-red-100 bg-red-50 p-4">
                            <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-white text-sm font-bold text-red-700">{{ $index + 1 }}</div>
                            <h3 class="mt-4 text-sm font-bold text-red-950">{{ $step['title'] }}</h3>
                            <p class="mt-2 text-xs leading-5 text-red-800">{{ $step['description'] }}</p>
                            @if ($step['type'] === 'validation')
                                <div class="mt-4 grid gap-2">
                                    @if ($successBranch)
                                        <div class="rounded-lg border border-red-100 bg-white px-3 py-2 text-xs font-semibold text-red-700">Berhasil: {{ $successBranch['description'] }}</div>
                                    @endif
                                    @if ($failBranch)
                                        <div class="rounded-lg border border-red-100 bg-white px-3 py-2 text-xs font-semibold text-red-700">Gagal: {{ $failBranch['description'] }}</div>
                                    @endif
                                </div>
                            @endif
                        </article>
                    @endforeach
                </div>
            </section>

            <section class="mt-6 rounded-lg border border-red-100 bg-white p-6 shadow-sm">
                <h2 class="text-xl font-bold text-red-950">Dashboard per Role</h2>
                <div class="mt-5 grid gap-4 md:grid-cols-2 xl:grid-cols-3">
                    @foreach ($roles as $role)
                        <article class="rounded-lg border border-red-100 bg-white p-5 shadow-sm">
                            <div class="flex items-start justify-between gap-4">
                                <div>
                                    <h3 class="font-bold text-red-950">{{ $role['name'] }}</h3>
                                    <p class="mt-1 text-sm text-red-700">{{ $role['description'] }}</p>
                                </div>
                                <span class="rounded-lg bg-red-50 px-3 py-2 text-xs font-bold text-red-700">{{ $role['access_level'] }}</span>
                            </div>
                            <div class="mt-4 rounded-lg border border-red-100 bg-red-50 p-4">
                                <p class="text-xs font-semibold text-red-600">Dashboard Tujuan</p>
                                <p class="mt-1 text-sm font-bold text-red-950">{{ $role['dashboard'] }}</p>
                                <p class="mt-1 text-xs text-red-800">{{ $role['dashboard_description'] }}</p>
                            </div>
                        </article>
                    @endforeach
                </div>
            </section>

            <section class="mt-6 grid gap-6 xl:grid-cols-[1fr_1.2fr]">
                <div class="rounded-lg border border-red-100 bg-white p-6 shadow-sm">
                    <h2 class="text-xl font-bold text-red-950">Ringkasan Permission</h2>
                    <div class="mt-5 space-y-4">
                        @foreach ($roles as $role)
                            <div>
                                <div class="mb-2 flex items-center justify-between gap-3">
                                    <p class="text-sm font-bold text-red-950">{{ $role['name'] }}</p>
                                    <p class="text-sm font-semibold text-red-700">{{ count($role['permissions']) }}</p>
                                </div>
                                <div class="h-3 overflow-hidden rounded-lg bg-red-50">
                                    <div class="h-full rounded-lg bg-red-700" style="width: {{ max(8, (count($role['permissions']) / max(1, $totalPermissions)) * 100) }}%"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="rounded-lg border border-red-100 bg-white p-6 shadow-sm">
                    <h2 class="text-xl font-bold text-red-950">Detail Hak Akses</h2>
                    <div class="mt-5 space-y-4">
                        @foreach ($roles as $role)
                            <details class="rounded-lg border border-red-100 bg-red-50 p-4">
                                <summary class="cursor-pointer text-sm font-bold text-red-950">{{ $role['name'] }}</summary>
                                <div class="mt-4 grid gap-4 lg:grid-cols-2">
                                    <div>
                                        <p class="text-xs font-bold text-red-700">Tanggung Jawab</p>
                                        <ul class="mt-2 space-y-2">
                                            @foreach ($role['responsibilities'] as $item)
                                                <li class="text-sm text-red-900">- {{ $item }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                    <div>
                                        <p class="text-xs font-bold text-red-700">Menu</p>
                                        <div class="mt-2 flex flex-wrap gap-2">
                                            @foreach ($role['menus'] as $menu)
                                                <span class="rounded-lg border border-red-100 bg-white px-3 py-2 text-xs font-semibold text-red-700">{{ $menu }}</span>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-4">
                                    <p class="text-xs font-bold text-red-700">Permission</p>
                                    <div class="mt-2 flex flex-wrap gap-2">
                                        @foreach ($role['permissions'] as $permission)
                                            <span class="rounded-lg border border-red-100 bg-white px-3 py-2 text-xs font-semibold text-red-700">{{ $permission }}</span>
                                        @endforeach
                                    </div>
                                </div>
                            </details>
                        @endforeach
                    </div>
                </div>
            </section>
        </div>
    </main>
</div>
</body>
</html>
