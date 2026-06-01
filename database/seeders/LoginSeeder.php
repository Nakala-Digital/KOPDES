<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class LoginSeeder extends Seeder
{
    private const DEMO_PIN = '123456';

    public function run(): void
    {
        User::where('email', 'superadmin@gmail.com')->whereNull('phone')->delete();

        $accounts = [
            ['name' => 'Super Admin', 'email' => 'superadmin@kopdes.local', 'phone' => '081100000001', 'nik' => '3201010000000001', 'role' => 'super_admin'],
            ['name' => 'Admin Desa Maju', 'email' => 'admin.desa@kopdes.local', 'phone' => '081100000002', 'nik' => '3201010000000002', 'role' => 'admin_desa'],
            ['name' => 'Pengurus Kopdes', 'email' => 'kopdes@kopdes.local', 'phone' => '081100000003', 'nik' => '3201010000000003', 'role' => 'pengurus_kopdes'],
            ['name' => 'Pengurus BUMDes', 'email' => 'bumdes@kopdes.local', 'phone' => '081100000004', 'nik' => '3201010000000004', 'role' => 'pengurus_bumdes'],
            ['name' => 'UMKM Petani', 'email' => 'umkm@kopdes.local', 'phone' => '081100000005', 'nik' => '3201010000000005', 'role' => 'umkm_petani'],
            ['name' => 'Operator MBG', 'email' => 'mbg@kopdes.local', 'phone' => '081100000006', 'nik' => '3201010000000006', 'role' => 'operator_mbg'],
            ['name' => 'Warga Desa', 'email' => 'warga@kopdes.local', 'phone' => '081100000007', 'nik' => '3201010000000007', 'role' => 'warga'],
            ['name' => 'Pemda Viewer', 'email' => 'pemda@kopdes.local', 'phone' => '081100000008', 'nik' => '3201010000000008', 'role' => 'pemda_viewer'],
        ];

        foreach ($accounts as $account) {
            $role = Role::where('slug', $account['role'])->firstOrFail();

            User::updateOrCreate(
                ['phone' => $account['phone']],
                [
                    'name' => $account['name'],
                    'email' => $account['email'],
                    'nik' => $account['nik'],
                    'role_id' => $role->id,
                    'village_name' => 'Desa Maju',
                    'password' => Hash::make('password'),
                    'pin_hash' => Hash::make(self::DEMO_PIN),
                    'status' => 'active',
                    'failed_login_attempts' => 0,
                    'locked_until' => null,
                    'must_change_pin' => false,
                ]
            );
        }

        $this->command?->info('Akun demo siap. Login pakai No. HP/NIK dan PIN '.self::DEMO_PIN.'.');
    }
}
