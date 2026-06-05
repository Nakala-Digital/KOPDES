<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Masuk | DesaHub</title>
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
    <style>
        input:-webkit-autofill,
        input:-webkit-autofill:hover,
        input:-webkit-autofill:focus {
            -webkit-box-shadow: 0 0 0 1000px #ffffff inset;
            -webkit-text-fill-color: #001f4f;
            transition: background-color 9999s ease-in-out 0s;
        }
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #f4f7fb;
        }
    </style>
</head>
<body class="h-screen w-screen overflow-hidden antialiased text-[#001f4f]">

    <!-- Main Content Area -->
    <main class="absolute top-0 left-0 right-0 bottom-[94px] overflow-hidden bg-[#f4f7fb]">

        <!-- Left Background Layer -->
        <div class="absolute top-0 left-0 bottom-0 w-[66%] z-0 bg-cover bg-bottom bg-no-repeat" style="background-image: url('{{ asset('assets/desahub/background-login.png') }}');"></div>

        <!-- SPLIT WAVES LAYER (Matches Image 17: Thin, low contrast, positioned exactly behind the text below icons) -->
        <div class="absolute top-[140px] left-0 w-[66%] h-[400px] z-15 pointer-events-none xl:top-[140px] overflow-hidden">
            <svg viewBox="0 0 1440 400" xmlns="http://www.w3.org/2000/svg" class="w-full h-full opacity-[0.85] drop-shadow-[0_4px_12px_rgba(0,0,0,0.08)]" preserveAspectRatio="none">
                <!-- LEFT RIBBON (Thin, extremely short, ends just past Masyarakat) -->
                <path fill="#e11f2d" d="M 0,150 C 150,160 250,180 330,190 C 250,185 150,175 0,170 Z" />
                <path fill="#ffffff" d="M 0,170 C 150,175 250,185 330,190 C 250,195 150,190 0,190 Z" />

                <!-- RIGHT RIBBON (Thin, extremely short, ends at Rantai Pasok & MBG icon) -->
                <path fill="#e11f2d" d="M 1440,100 C 1200,140 1000,180 800,190 C 1000,190 1200,160 1440,130 Z" />
                <path fill="#ffffff" d="M 1440,130 C 1200,160 1000,190 800,190 C 1000,200 1200,180 1440,160 Z" />
            </svg>
        </div>

        <!-- Left Content -->
        <div class="absolute top-[35px] left-0 w-[55%] z-20 flex flex-col items-center">

            <!-- Logo DesaHub (Enlarged and shifted slightly left) -->
            <img src="{{ asset('assets/desahub/logo-desahub-transparent.png') }}" alt="DesaHub" class="w-[620px] max-w-[90%] -ml-6 object-contain drop-shadow-sm">

            <!-- Description -->
            <p class="-mt-3 max-w-[550px] -ml-6 text-center text-[16px] font-medium leading-[1.7] text-[#2c3e50]">
                Menghubungkan masyarakat, Kopdes/KDMP, BUMDes, UMKM,<br>
                dan rantai pasok untuk ekonomi desa yang maju dan mandiri.
            </p>

            <!-- Container for Icons -->
            <div class="relative mt-[15px] flex w-[120%] -ml-6 items-center justify-center">
                <!-- Icons Group -->
                <div class="relative z-10 flex items-center justify-center gap-5">
                    <div class="flex flex-col items-center gap-1">
                        <div class="flex h-[60px] w-[60px] items-center justify-center rounded-full bg-white text-[#009045] shadow-[0_4px_24px_rgb(0,0,0,0.1)]">
                            <svg viewBox="0 0 640 512" fill="currentColor" class="h-7 w-7"><path d="M144 0a80 80 0 1 1 0 160A80 80 0 1 1 144 0zM512 0a80 80 0 1 1 0 160A80 80 0 1 1 512 0zM0 298.7C0 239.8 47.8 192 106.7 192l42.7 0c15.9 0 31 3.5 44.6 9.7c-1.3 7.2-1.9 14.7-1.9 22.3c0 38.2 16.8 72.5 43.3 96c-.2 0-.4 0-.7 0L21.3 320C9.6 320 0 310.4 0 298.7zM405.3 320c-.2 0-.4 0-.7 0c26.6-23.5 43.3-57.8 43.3-96c0-7.6-.7-15-1.9-22.3c13.6-6.3 28.7-9.7 44.6-9.7l42.7 0C592.2 192 640 239.8 640 298.7c0 11.8-9.6 21.3-21.3 21.3l-213.3 0zM224 224a96 96 0 1 1 192 0 96 96 0 1 1 -192 0zM128 485.3C128 411.7 187.7 352 261.3 352l117.3 0C452.3 352 512 411.7 512 485.3c0 14.7-11.9 26.7-26.7 26.7l-330.7 0c-14.7 0-26.7-11.9-26.7-26.7z"/></svg>
                        </div>
                        <span class="text-[12px] font-bold text-[#001f4f]">Masyarakat</span>
                    </div>
                    <div class="flex flex-col items-center gap-1">
                        <div class="flex h-[60px] w-[60px] items-center justify-center rounded-full bg-white text-[#0056b3] shadow-[0_4px_24px_rgb(0,0,0,0.1)]">
                            <svg viewBox="0 0 640 512" fill="currentColor" class="h-7 w-7"><path d="M323.4 85.2l-96.8 78.4c-16.1 13-19.2 36.4-7 53.1c12.9 17.8 38 21.3 55.3 7.8l99.3-77.2c7-5.4 17-4.2 22.5 2.8s4.2 17-2.8 22.5l-20.9 16.2L512 316.8 512 128l-.7 0-3.9-2.5L434.8 79c-15.3-9.8-33.2-15-51.4-15c-21.8 0-43 7.5-60 21.2zm22.8 124.4l-51.7 40.2C263 274.4 217.3 268 193.7 235.6c-22.2-30.5-16.6-73.1 12.7-96.8l83.2-67.3c-11.6-4.9-24.1-7.4-36.8-7.4C234 64 215.7 69.6 200 80l-72 48 0 224 28.2 0 91.4 83.4c19.6 17.9 49.9 16.5 67.8-3.1c5.5-6.1 9.2-13.2 11.1-20.6l17 15.6c19.5 17.9 49.9 16.6 67.8-2.9c4.5-4.9 7.8-10.6 9.9-16.5c19.4 13 45.8 10.3 62.1-7.5c17.9-19.5 16.6-49.9-2.9-67.8l-134.2-123zM16 128c-8.8 0-16 7.2-16 16L0 352c0 17.7 14.3 32 32 32l32 0c17.7 0 32-14.3 32-32l0-224-80 0zM48 320a16 16 0 1 1 0 32 16 16 0 1 1 0-32zM544 128l0 224c0 17.7 14.3 32 32 32l32 0c17.7 0 32-14.3 32-32l0-208c0-8.8-7.2-16-16-16l-80 0zm32 208a16 16 0 1 1 32 0 16 16 0 1 1 -32 0z"/></svg>
                        </div>
                        <span class="text-[12px] font-bold text-[#001f4f]">Kopdes/KDMP</span>
                    </div>
                    <div class="flex flex-col items-center gap-1">
                        <div class="flex h-[60px] w-[60px] items-center justify-center rounded-full bg-white text-[#009045] shadow-[0_4px_24px_rgb(0,0,0,0.1)]">
                            <svg viewBox="0 0 576 512" fill="currentColor" class="h-7 w-7"><path d="M575.8 255.5c0 18-15 32.1-32 32.1l-32 0 .7 160.2c0 2.7-.2 5.4-.5 8.1l0 16.2c0 22.1-17.9 40-40 40l-16 0c-1.1 0-2.2 0-3.3-.1c-1.4 .1-2.8 .1-4.2 .1L416 512l-24 0c-22.1 0-40-17.9-40-40l0-24 0-64c0-17.7-14.3-32-32-32l-64 0c-17.7 0-32 14.3-32 32l0 64 0 24c0 22.1-17.9 40-40 40l-24 0-31.9 0c-1.5 0-3-.1-4.5-.2c-1.2 .1-2.4 .2-3.6 .2l-16 0c-22.1 0-40-17.9-40-40l0-112c0-.9 0-1.9 .1-2.8l0-69.7-32 0c-18 0-32-14-32-32.1c0-9 3-17 10-24L266.4 8c7-7 15-8 22-8s15 2 21 7L564.8 231.5c8 7 12 15 11 24z"/></svg>
                        </div>
                        <span class="text-[12px] font-bold text-[#001f4f]">BUMDes</span>
                    </div>
                    <div class="flex flex-col items-center gap-1">
                        <div class="flex h-[60px] w-[60px] items-center justify-center rounded-full bg-white text-[#f39c12] shadow-[0_4px_24px_rgb(0,0,0,0.1)]">
                            <svg viewBox="0 0 576 512" fill="currentColor" class="h-7 w-7"><path d="M547.6 103.8L490.3 13.1C485.2 5 476.1 0 466.4 0L109.6 0C99.9 0 90.8 5 85.7 13.1L28.3 103.8c-29.6 46.8-3.4 111.9 51.9 119.4c4 .5 8.1 .8 12.1 .8c26.1 0 49.3-11.4 65.2-29c15.9 17.6 39.1 29 65.2 29c26.1 0 49.3-11.4 65.2-29c15.9 17.6 39.1 29 65.2 29c26.2 0 49.3-11.4 65.2-29c16 17.6 39.1 29 65.2 29c4.1 0 8.1-.3 12.1-.8c55.5-7.4 81.8-72.5 52.1-119.4zM499.7 254.9c0 0 0 0-.1 0c-5.3 .7-10.7 1.1-16.2 1.1c-12.4 0-24.3-1.9-35.4-5.3L448 384l-320 0 0-133.4c-11.2 3.5-23.2 5.4-35.6 5.4c-5.5 0-11-.4-16.3-1.1l-.1 0c-4.1-.6-8.1-1.3-12-2.3L64 384l0 64c0 35.3 28.7 64 64 64l320 0c35.3 0 64-28.7 64-64l0-64 0-131.4c-4 1-8 1.8-12.3 2.3z"/></svg>
                        </div>
                        <span class="text-[12px] font-bold text-[#001f4f]">UMKM</span>
                    </div>
                    <div class="flex flex-col items-center gap-1">
                        <div class="flex h-[60px] w-[60px] items-center justify-center rounded-full bg-white text-[#5e35b1] shadow-[0_4px_24px_rgb(0,0,0,0.1)]">
                            <svg viewBox="0 0 512 512" fill="currentColor" class="h-7 w-7"><path d="M234.5 5.7c13.9-5 29.1-5 43.1 0l192 68.6C495 83.4 512 107.5 512 134.6l0 242.9c0 27-17 51.2-42.5 60.3l-192 68.6c-13.9 5-29.1 5-43.1 0l-192-68.6C17 428.6 0 404.5 0 377.4L0 134.6c0-27 17-51.2 42.5-60.3l192-68.6zM256 66L82.3 128 256 190l173.7-62L256 66zm32 368.6l160-57.1 0-188L288 246.6l0 188z"/></svg>
                        </div>
                        <span class="text-[12px] font-bold text-[#001f4f]">Rantai Pasok & MBG</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Badge -->
        <div class="absolute bottom-[50px] left-[60px] z-20 flex items-center gap-4 rounded-[18px] bg-[#001f4f] px-6 py-4 shadow-2xl scale-105 origin-bottom-left">
            <div class="flex h-[40px] w-[40px] shrink-0 items-center justify-center rounded-full bg-white text-[#001f4f]">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="h-6 w-6"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><path d="m9 12 2 2 4-4"/></svg>
            </div>
            <p class="text-[14px] font-medium leading-[1.5] text-white">
                Data akurat, transaksi transparan,<br>ekonomi desa tumbuh berkelanjutan.
            </p>
        </div>

        <!-- Right White Card Layer -->
        <div class="absolute bottom-[12px] right-[48px] top-[28px] z-30 flex w-[44vw] min-w-[680px] max-w-[840px] flex-col rounded-[24px] bg-white shadow-[0_8px_40px_rgb(0,0,0,0.08)]">

            <!-- Nakala Logo inside Card -->
            <div class="absolute right-[70px] top-[45px]">
                <img src="{{ asset('assets/desahub/logo-nakala-transparent.png') }}" alt="Nakala Digital" class="w-[180px] object-contain">
            </div>

            <!-- Content inside the card -->
            <!-- We will use a relatively positioned container to easily set the exact offsets mentioned: 150px and 260px from top -->

            <!-- Title and Subtitle -->
            <div class="absolute left-0 right-0 top-[150px] text-center">
                <h1 class="text-[30px] font-bold text-[#001f4f]">Selamat Datang Kembali</h1>
                <p class="mt-2 text-[15px] font-medium text-gray-500">Silakan masuk untuk melanjutkan ke DesaHub</p>
            </div>

            <!-- Form Wrapper -->
            <div class="absolute left-0 right-0 top-[260px] mx-auto flex w-[610px] max-w-[72%] flex-col">

                @if (isset($errors) && $errors->any())
                    <div class="mb-6 rounded-lg border border-red-200 bg-red-50 p-4 text-[14.5px] font-semibold text-red-700">
                        {{ $errors->first() }}
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}" class="flex w-full flex-col gap-6">
                    @csrf

                    <!-- Username/Email Field -->
                    <div>
                        <label for="identifier" class="mb-1.5 block text-[14.5px] font-bold text-[#001f4f]">Email atau Username</label>
                        <div class="relative">
                            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4.5">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-5 w-5 text-gray-400"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                            </div>
                            <input id="identifier" name="identifier" type="text" value="{{ old('identifier') }}" required autofocus autocomplete="username"
                                   class="block h-[58px] w-full rounded-lg border border-[#cfd8e3] bg-white pl-12 pr-4 text-[16px] font-medium text-[#001f4f] placeholder-gray-400 outline-none transition focus:border-[#001f4f] focus:ring-1 focus:ring-[#001f4f]"
                                   placeholder="Masukkan email atau username">
                        </div>
                    </div>

                    <!-- Password Field -->
                    <div>
                        <label for="pin" class="mb-1.5 block text-[14.5px] font-bold text-[#001f4f]">Password</label>
                        <div class="relative">
                            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4.5">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-5 w-5 text-gray-400"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                            </div>
                            <input id="pin" name="pin" type="password" required autocomplete="current-password"
                                   class="block h-[58px] w-full rounded-lg border border-[#cfd8e3] bg-white pl-12 pr-12 text-[16px] font-medium text-[#001f4f] placeholder-gray-400 outline-none transition focus:border-[#001f4f] focus:ring-1 focus:ring-[#001f4f]"
                                   placeholder="Masukkan password">
                            <div class="absolute inset-y-0 right-0 flex items-center pr-4">
                                <button type="button" data-password-toggle class="text-gray-400 hover:text-gray-600 focus:outline-none" aria-label="Tampilkan password">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-5 w-5"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                </button>
                            </div>
                        </div>
                        <div class="mt-2.5 flex justify-end">
                            <a href="#" class="text-[14px] font-bold text-[#004dff] hover:underline">Lupa Password?</a>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="mt-1 flex h-[62px] w-full items-center justify-center gap-3 rounded-lg bg-[#001f4f] text-[16px] font-bold text-white transition hover:bg-[#001536]">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/><polyline points="10 17 15 12 10 7"/><line x1="15" y1="12" x2="3" y2="12"/></svg>
                        Masuk
                    </button>

                    <!-- Separator -->
                    <div class="my-1 flex items-center justify-center gap-4">
                        <div class="h-px flex-1 bg-gray-200"></div>
                        <span class="text-[14px] font-medium text-gray-400">atau masuk dengan</span>
                        <div class="h-px flex-1 bg-gray-200"></div>
                    </div>

                    <!-- Google Button -->
                    <button type="button" class="flex h-[58px] w-full items-center justify-center gap-3 rounded-lg border border-[#cfd8e3] bg-white text-[15px] font-bold text-[#333333] transition hover:bg-gray-50">
                        <svg viewBox="0 0 24 24" class="h-5 w-5" xmlns="http://www.w3.org/2000/svg"><path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/><path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/><path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/><path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/></svg>
                        Masuk dengan Google
                    </button>
                </form>

                <p class="mt-8 text-center text-[14.5px] font-medium text-gray-500">
                    Belum punya akun? <a href="#" class="font-bold text-[#004dff] hover:underline">Hubungi Administrator Desa</a>
                </p>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="absolute bottom-0 left-0 right-0 z-50 h-[94px] bg-[#001f4f]">
        <!-- Tapered Red & White Lines -->
        <div class="absolute inset-x-0 bottom-full w-full h-[12px]">
            <svg viewBox="0 0 1440 12" preserveAspectRatio="none" class="w-full h-full block drop-shadow-sm" xmlns="http://www.w3.org/2000/svg">
                <!-- Red Polygon (Thick on left, thin on right) -->
                <polygon fill="#e11f2d" points="0,0 1440,9 1440,11 0,8" />
                <!-- White Polygon (Thick on left, thin on right) -->
                <polygon fill="#ffffff" points="0,8 1440,11 1440,12 0,12" />
            </svg>
        </div>

        <div class="flex h-full w-full items-center gap-[50px] px-[50px] xl:gap-[80px] xl:px-[70px] 2xl:gap-[120px] 2xl:px-[80px] overflow-hidden whitespace-nowrap">
            <!-- Nakala Logo -->
            <img src="{{ asset('assets/desahub/logo-nakala-transparent.png') }}" alt="Nakala Digital" class="h-[44px] flex-shrink-0 object-contain xl:h-[48px] 2xl:h-[56px]">

            <!-- Romulus Group -->
            <div class="flex flex-shrink-0 items-center gap-[16px] xl:gap-[24px]">
                <span class="text-[15px] text-white/80 xl:text-[16px] 2xl:text-[18px]">Strategic Partner of</span>
                <!-- Romulus Logo -->
                <img src="{{ asset('assets/desahub/logo-romulus.png') }}" alt="Romulus Digital" class="h-[32px] object-contain mix-blend-screen xl:h-[38px] 2xl:h-[48px]">
            </div>

            <!-- Contact Info -->
            <div class="hidden flex-shrink-0 items-center gap-[50px] text-[15px] text-white lg:flex xl:gap-[60px] xl:text-[16px] 2xl:gap-[80px] 2xl:text-[18px]">
                <div class="flex items-center gap-[10px] xl:gap-[12px]">
                    <svg viewBox="0 0 24 24" fill="none" stroke="#10d5ff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5 xl:h-[22px] xl:w-[22px] 2xl:h-6 2xl:w-6"><circle cx="12" cy="12" r="10"/><path d="M12 2a14.5 14.5 0 0 0 0 20 14.5 14.5 0 0 0 0-20"/><path d="M2 12h20"/></svg>
                    romulus.digital
                </div>
                <div class="flex items-center gap-[10px] xl:gap-[12px]">
                    <svg viewBox="0 0 24 24" fill="none" stroke="#10d5ff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5 xl:h-[22px] xl:w-[22px] 2xl:h-6 2xl:w-6"><rect width="20" height="16" x="2" y="4" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/></svg>
                    contact@nakala.digital
                </div>
                <div class="flex items-center gap-[10px] xl:gap-[12px]">
                    <svg viewBox="0 0 24 24" fill="none" stroke="#10d5ff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5 xl:h-[22px] xl:w-[22px] 2xl:h-6 2xl:w-6"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
                    Bandung, Indonesia
                </div>
                <div class="flex items-center gap-[10px] xl:gap-[12px]">
                    <svg viewBox="0 0 24 24" fill="none" stroke="#10d5ff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5 xl:h-[22px] xl:w-[22px] 2xl:h-6 2xl:w-6"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/><path d="M14.05 2a9 9 0 0 1 8 7.94" /><path d="M14.05 6A5 5 0 0 1 18 10" /></svg>
                    +62 822-9570-6304
                </div>
            </div>
        </div>
    </footer>

    <script>
        document.querySelector('[data-password-toggle]')?.addEventListener('click', () => {
            const input = document.getElementById('pin');
            if (!input) return;
            input.type = input.type === 'password' ? 'text' : 'password';
        });
    </script>
</body>
</html>
