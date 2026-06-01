<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Daftar | {{ config('app.name', 'KopDes') }}</title>
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
                <h1 class="mt-8 max-w-lg text-4xl font-bold leading-tight sm:text-5xl">Mulai bersama KopDes</h1>
                <p class="mt-4 max-w-md text-base leading-7 text-red-50">
                    Buat akun baru untuk mengakses dashboard koperasi desa dengan proses yang singkat dan jelas.
                </p>

                <div class="mt-10 grid gap-3 sm:grid-cols-2">
                    <div class="rounded-lg border border-white/25 bg-white/10 p-4">
                        <p class="text-sm text-red-50">Pendaftaran</p>
                        <p class="mt-1 text-xl font-semibold">Cepat</p>
                    </div>
                    <div class="rounded-lg border border-white/25 bg-white/10 p-4">
                        <p class="text-sm text-red-50">Tampilan</p>
                        <p class="mt-1 text-xl font-semibold">Sederhana</p>
                    </div>
                </div>
            </div>
        </section>

        <section class="flex items-center justify-center px-6 py-10 sm:px-10 lg:px-14">
            <div class="w-full max-w-md rounded-lg border border-red-100 bg-white p-6 shadow-xl shadow-red-900/10 sm:p-8">
                <div>
                    <p class="text-sm font-semibold text-red-700">Akun baru</p>
                    <h2 class="mt-2 text-3xl font-bold text-red-950">Daftar akun</h2>
                    <p class="mt-2 text-sm leading-6 text-red-800">Isi data singkat di bawah untuk mulai menggunakan aplikasi.</p>
                </div>

                @if ($errors->any())
                    <div class="mt-5 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                        {{ $errors->first() }}
                    </div>
                @endif

                <form method="POST" action="{{ route('register') }}" class="mt-7 space-y-5">
                    @csrf

                    <div>
                        <label for="name" class="block text-sm font-semibold text-red-950">Nama lengkap</label>
                        <input id="name" name="name" type="text" value="{{ old('name') }}" required autofocus autocomplete="name"
                               class="mt-2 w-full rounded-lg border border-red-100 bg-white px-4 py-3 text-red-950 outline-none transition placeholder:text-red-300 focus:border-red-500 focus:ring-4 focus:ring-red-100"
                               placeholder="Nama Anda">
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-semibold text-red-950">Email</label>
                        <input id="email" name="email" type="email" value="{{ old('email') }}" required autocomplete="email"
                               class="mt-2 w-full rounded-lg border border-red-100 bg-white px-4 py-3 text-red-950 outline-none transition placeholder:text-red-300 focus:border-red-500 focus:ring-4 focus:ring-red-100"
                               placeholder="nama@email.com">
                    </div>

                    <div class="grid gap-5 sm:grid-cols-2">
                        <div>
                            <label for="phone" class="block text-sm font-semibold text-red-950">No. HP</label>
                            <input id="phone" name="phone" type="text" value="{{ old('phone') }}" autocomplete="tel"
                                   class="mt-2 w-full rounded-lg border border-red-100 bg-white px-4 py-3 text-red-950 outline-none transition placeholder:text-red-300 focus:border-red-500 focus:ring-4 focus:ring-red-100"
                                   placeholder="Opsional">
                        </div>

                        <div>
                            <label for="nik" class="block text-sm font-semibold text-red-950">NIK</label>
                            <input id="nik" name="nik" type="text" value="{{ old('nik') }}"
                                   class="mt-2 w-full rounded-lg border border-red-100 bg-white px-4 py-3 text-red-950 outline-none transition placeholder:text-red-300 focus:border-red-500 focus:ring-4 focus:ring-red-100"
                                   placeholder="Opsional">
                        </div>
                    </div>

                    <div>
                            <label for="password" class="block text-sm font-semibold text-red-950">PIN</label>
                        <input id="password" name="password" type="password" required autocomplete="new-password"
                               class="mt-2 w-full rounded-lg border border-red-100 bg-white px-4 py-3 text-red-950 outline-none transition placeholder:text-red-300 focus:border-red-500 focus:ring-4 focus:ring-red-100"
                               placeholder="Minimal 8 karakter">
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-sm font-semibold text-red-950">Konfirmasi PIN</label>
                        <input id="password_confirmation" name="password_confirmation" type="password" required autocomplete="new-password"
                               class="mt-2 w-full rounded-lg border border-red-100 bg-white px-4 py-3 text-red-950 outline-none transition placeholder:text-red-300 focus:border-red-500 focus:ring-4 focus:ring-red-100"
                               placeholder="Ulangi PIN">
                    </div>

                    <button type="submit" class="w-full rounded-lg bg-red-700 px-4 py-3 font-semibold text-white shadow-lg shadow-red-700/20 transition hover:bg-red-800 focus:outline-none focus:ring-4 focus:ring-red-100">
                        Daftar
                    </button>
                </form>

                <p class="mt-6 text-center text-sm text-red-800">
                    Sudah punya akun?
                    <a href="{{ route('login') }}" class="font-semibold text-red-700 hover:text-red-800">Masuk</a>
                </p>
            </div>
        </section>
    </main>
</body>
</html>
