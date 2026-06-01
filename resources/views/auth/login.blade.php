<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Masuk | {{ config('app.name', 'KopDes') }}</title>
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
</head>
<body class="min-h-screen bg-red-50 text-red-950">
    <main class="min-h-screen lg:grid lg:grid-cols-[0.95fr_1.05fr]">
        <section class="flex min-h-[260px] items-center bg-red-700 px-6 py-10 text-white sm:px-10 lg:min-h-screen lg:px-14">
            <div class="w-full max-w-xl">
                <a href="{{ url('/') }}" class="inline-flex h-12 w-12 items-center justify-center rounded-lg bg-white text-lg font-bold text-red-700 shadow-sm">
                    K
                </a>
                <h1 class="mt-8 max-w-lg text-4xl font-bold leading-tight sm:text-5xl">KopDes Merah Putih</h1>
                <p class="mt-4 max-w-md text-base leading-7 text-red-50">
                    Masuk untuk mengelola data koperasi desa dengan tampilan yang sederhana, bersih, dan mudah dipakai.
                </p>

                <div class="mt-10 grid gap-3 sm:grid-cols-2">
                    <div class="rounded-lg border border-white/25 bg-white/10 p-4">
                        <p class="text-sm text-red-50">Akses cepat</p>
                        <p class="mt-1 text-xl font-semibold">Dashboard</p>
                    </div>
                    <div class="rounded-lg border border-white/25 bg-white/10 p-4">
                        <p class="text-sm text-red-50">Akun aman</p>
                        <p class="mt-1 text-xl font-semibold">Login</p>
                    </div>
                </div>
            </div>
        </section>

        <section class="flex items-center justify-center px-6 py-10 sm:px-10 lg:px-14">
            <div class="w-full max-w-md rounded-lg border border-red-100 bg-white p-6 shadow-xl shadow-red-900/10 sm:p-8">
                <div>
                    <p class="text-sm font-semibold text-red-700">Selamat datang</p>
                    <h2 class="mt-2 text-3xl font-bold text-red-950">Masuk ke akun</h2>
                    <p class="mt-2 text-sm leading-6 text-red-800">Gunakan No. HP atau NIK dan PIN akun Anda.</p>
                </div>

                @if ($errors->any())
                    <div class="mt-5 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                        {{ $errors->first() }}
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}" class="mt-7 space-y-5">
                    @csrf

                    <div>
                        <label for="identifier" class="block text-sm font-semibold text-red-950">No. HP / NIK</label>
                        <input id="identifier" name="identifier" type="text" value="{{ old('identifier') }}" required autofocus inputmode="numeric" autocomplete="username"
                               class="mt-2 w-full rounded-lg border border-red-100 bg-white px-4 py-3 text-red-950 outline-none transition placeholder:text-red-300 focus:border-red-500 focus:ring-4 focus:ring-red-100"
                               placeholder="Masukkan No. HP atau NIK">
                    </div>

                    <div>
                        <label for="pin" class="block text-sm font-semibold text-red-950">PIN</label>
                        <input id="pin" name="pin" type="password" required inputmode="numeric" autocomplete="current-password"
                               class="mt-2 w-full rounded-lg border border-red-100 bg-white px-4 py-3 text-red-950 outline-none transition placeholder:text-red-300 focus:border-red-500 focus:ring-4 focus:ring-red-100"
                               placeholder="Masukkan PIN">
                    </div>

                    <div class="flex items-center justify-between gap-4">
                        <label class="inline-flex items-center gap-2 text-sm text-red-900">
                            <input type="checkbox" name="remember" class="h-4 w-4 rounded border-red-300 text-red-600 accent-red-600">
                            Ingat saya
                        </label>
                    </div>

                    <button type="submit" class="w-full rounded-lg bg-red-700 px-4 py-3 font-semibold text-white shadow-lg shadow-red-700/20 transition hover:bg-red-800 focus:outline-none focus:ring-4 focus:ring-red-100">
                        Masuk
                    </button>
                </form>

                <p class="mt-6 text-center text-sm text-red-800">
                    Belum punya akun?
                    <a href="{{ route('register') }}" class="font-semibold text-red-700 hover:text-red-800">Daftar sekarang</a>
                </p>
            </div>
        </section>
    </main>
</body>
</html>
