<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Application Flow Steps
    |--------------------------------------------------------------------------
    |
    | Setiap langkah dalam alur aplikasi dari buka aplikasi hingga logout.
    | type: process, validation, gateway, terminal
    |
    */

    'flow_steps' => [
        [
            'id' => 1,
            'title' => 'Halaman login',
            'description' => 'User memasukkan No. HP / NIK + PIN / Password.',
            'type' => 'process',
            'icon' => '🔐',
            'color' => ['border' => '#dc2626', 'text' => '#991b1b'],
        ],
        [
            'id' => 2,
            'title' => 'Validasi kredensial',
            'description' => 'Sistem memeriksa data login user.',
            'type' => 'validation',
            'icon' => '⚙️',
            'color' => ['border' => '#b91c1c', 'text' => '#7f1d1d'],
            'branches' => [
                [
                    'status' => 'fail',
                    'label' => 'Gagal',
                    'description' => 'Tampil pesan error.',
                    'action' => 'Coba lagi',
                    'action_target' => 2,
                    'color' => ['border' => '#ef4444', 'bg' => '#fef2f2', 'text' => '#b91c1c'],
                ],
                [
                    'status' => 'success',
                    'label' => 'Berhasil',
                    'description' => 'Sistem melanjutkan ke pengecekan role pengguna.',
                    'next' => 3,
                    'color' => ['border' => '#dc2626', 'text' => '#991b1b'],
                ],
            ],
        ],
        [
            'id' => 3,
            'title' => 'Cek role pengguna',
            'description' => 'Sistem membaca role, permission, dan hak akses user.',
            'type' => 'process',
            'icon' => '👤',
            'color' => ['border' => '#b91c1c', 'text' => '#7f1d1d'],
        ],
        [
            'id' => 4,
            'title' => 'Masuk dashboard sesuai role',
            'description' => 'User diarahkan ke dashboard berdasarkan role masing-masing.',
            'type' => 'gateway',
            'icon' => '📊',
            'color' => ['border' => '#b91c1c', 'text' => '#7f1d1d'],
        ],
        [
            'id' => 5,
            'title' => 'Sesi aktif / JWT / session token',
            'description' => 'Sistem membuat JWT / session token untuk user.',
            'type' => 'process',
            'icon' => '🔄',
            'color' => ['border' => '#dc2626', 'text' => '#991b1b'],
            'details' => [
                'Auto-refresh token',
                'Expire 8 jam',
                'Remember me 30 hari',
            ],
        ],
        [
            'id' => 6,
            'title' => 'Logout / sesi berakhir',
            'description' => 'Sistem menghapus token dan mengembalikan user ke halaman login.',
            'type' => 'terminal',
            'icon' => '🚪',
            'color' => ['border' => '#fecaca', 'text' => '#991b1b'],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Roles
    |--------------------------------------------------------------------------
    |
    | Seluruh role dalam sistem. Role baru cukup ditambahkan di sini.
    | Blade akan me-loop data ini secara otomatis.
    |
    */

    'roles' => [
        [
            'name' => 'Super Admin',
            'slug' => 'super_admin',
            'description' => 'Nakala/Romulus',
            'dashboard' => 'UMKM / Petani',
            'dashboard_description' => 'Produsen lokal',
            'access_level' => 'Full access',
            'color' => ['border' => '#b91c1c', 'bg' => '#fef2f2', 'text' => '#7f1d1d', 'light_border' => '#fecaca'],
            'responsibilities' => [
                'Mengelola seluruh user',
                'Mengelola role dan permission',
                'Monitoring seluruh desa',
                'Monitoring UMKM dan petani',
                'Melihat laporan global',
                'Mengatur konfigurasi sistem',
            ],
            'permissions' => [
                'user.create', 'user.read', 'user.update', 'user.delete',
                'role.manage', 'permission.manage',
                'village.monitor',
                'umkm.monitor', 'farmer.monitor',
                'report.global',
                'system.setting',
            ],
            'menus' => [
                'Dashboard', 'Manajemen User', 'Role & Permission',
                'Data Desa', 'Data UMKM', 'Data Petani',
                'Laporan Global', 'Pengaturan Sistem',
            ],
        ],
        [
            'name' => 'Admin Desa',
            'slug' => 'admin_desa',
            'description' => 'Kepala desa / staf',
            'dashboard' => 'Operator MBG',
            'dashboard_description' => 'Sekolah / penerima',
            'access_level' => 'Village admin access',
            'color' => ['border' => '#dc2626', 'bg' => '#ffffff', 'text' => '#991b1b', 'light_border' => '#fca5a5'],
            'responsibilities' => [
                'Mengelola data warga desa',
                'Mengelola UMKM desa',
                'Mengelola data petani',
                'Mengelola penerima program',
                'Monitoring distribusi MBG',
                'Membuat laporan desa',
            ],
            'permissions' => [
                'citizen.create', 'citizen.read', 'citizen.update',
                'umkm.create', 'umkm.read', 'umkm.update',
                'farmer.create', 'farmer.read', 'farmer.update',
                'mbg.monitor',
                'village.report',
            ],
            'menus' => [
                'Dashboard Desa', 'Data Warga', 'Data UMKM',
                'Data Petani', 'Penerima Program', 'Monitoring MBG',
                'Laporan Desa',
            ],
        ],
        [
            'name' => 'Pengurus Kopdes',
            'slug' => 'pengurus_kopdes',
            'description' => 'KDMP / Koperasi',
            'dashboard' => 'Warga desa',
            'dashboard_description' => 'Marketplace / beli',
            'access_level' => 'Cooperative access',
            'color' => ['border' => '#ef4444', 'bg' => '#ffffff', 'text' => '#991b1b', 'light_border' => '#fecaca'],
            'responsibilities' => [
                'Mengelola produk koperasi',
                'Mengelola stok barang',
                'Mengelola pesanan warga',
                'Mengelola transaksi marketplace',
                'Monitoring anggota koperasi',
                'Membuat laporan penjualan',
            ],
            'permissions' => [
                'cooperative.product.create', 'cooperative.product.read', 'cooperative.product.update',
                'cooperative.stock.manage',
                'order.read', 'order.update',
                'transaction.read',
                'marketplace.manage',
                'cooperative.member.read',
                'sales.report',
            ],
            'menus' => [
                'Dashboard Koperasi', 'Produk Koperasi', 'Stok Barang',
                'Pesanan', 'Transaksi', 'Marketplace',
                'Anggota Koperasi', 'Laporan Penjualan',
            ],
        ],
        [
            'name' => 'Pengurus BUMDes',
            'slug' => 'pengurus_bumdes',
            'description' => 'Manajer unit usaha',
            'dashboard' => 'Pemda / Viewer',
            'dashboard_description' => 'Read-only laporan',
            'access_level' => 'Business unit access',
            'color' => ['border' => '#dc2626', 'bg' => '#ffffff', 'text' => '#7f1d1d', 'light_border' => '#fca5a5'],
            'responsibilities' => [
                'Mengelola unit usaha desa',
                'Mengelola produk dan layanan BUMDes',
                'Monitoring pendapatan usaha',
                'Monitoring operasional unit',
                'Membuat laporan usaha',
                'Rekap aktivitas desa',
            ],
            'permissions' => [
                'bumdes.unit.create', 'bumdes.unit.read', 'bumdes.unit.update',
                'bumdes.product.manage', 'bumdes.service.manage',
                'revenue.monitor', 'operation.monitor',
                'business.report',
                'village.activity.read',
            ],
            'menus' => [
                'Dashboard BUMDes', 'Unit Usaha', 'Produk & Layanan',
                'Operasional', 'Pendapatan', 'Aktivitas Desa',
                'Laporan Usaha',
            ],
        ],
        [
            'name' => 'Pemda / Viewer',
            'slug' => 'pemda_viewer',
            'description' => 'Pemerintah daerah / pemantau',
            'dashboard' => 'Monitoring laporan',
            'dashboard_description' => 'Read-only laporan desa',
            'access_level' => 'Read-only access',
            'color' => ['border' => '#e11d48', 'bg' => '#fff1f2', 'text' => '#881337', 'light_border' => '#fda4af'],
            'responsibilities' => [
                'Melihat laporan desa',
                'Melihat laporan UMKM',
                'Melihat laporan petani',
                'Melihat laporan koperasi',
                'Melihat laporan BUMDes',
                'Monitoring perkembangan program',
            ],
            'permissions' => [
                'report.village.read', 'report.umkm.read',
                'report.farmer.read', 'report.cooperative.read',
                'report.bumdes.read',
                'program.monitor.read',
            ],
            'menus' => [
                'Dashboard Monitoring', 'Laporan Desa', 'Laporan UMKM',
                'Laporan Petani', 'Laporan Koperasi', 'Laporan BUMDes',
                'Monitoring Program',
            ],
        ],
        [
            'name' => 'Warga Desa',
            'slug' => 'warga_desa',
            'description' => 'Pengguna umum desa',
            'dashboard' => 'Marketplace / layanan desa',
            'dashboard_description' => 'Beli produk dan akses layanan',
            'access_level' => 'Public user access',
            'color' => ['border' => '#b91c1c', 'bg' => '#ffffff', 'text' => '#7f1d1d', 'light_border' => '#fecaca'],
            'responsibilities' => [
                'Melihat produk marketplace',
                'Membeli produk UMKM / koperasi',
                'Melihat layanan desa',
                'Melihat status pesanan',
                'Mengelola profil pribadi',
            ],
            'permissions' => [
                'marketplace.read', 'product.read',
                'order.create', 'order.read',
                'profile.read', 'profile.update',
                'village.service.read',
            ],
            'menus' => [
                'Beranda', 'Marketplace', 'Produk UMKM',
                'Produk Koperasi', 'Pesanan Saya', 'Layanan Desa',
                'Profil Saya',
            ],
        ],
    ],

];
