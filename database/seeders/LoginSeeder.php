<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class LoginSeeder extends Seeder
{
    private const DEMO_PIN = 'admin';

    public function run(): void
    {
        User::where('email', 'superadmin@gmail.com')->whereNull('phone')->delete();

        $accounts = [
            ['name' => 'Super Admin', 'email' => 'superadmin@desahub.test', 'phone' => '081100000001', 'nik' => '3201010000000001', 'role' => 'super_admin', 'village_name' => 'Semua Desa'],
            ['name' => 'Kepala Desa', 'email' => 'admin@desahub.test', 'phone' => '081100000002', 'nik' => 'admin', 'role' => 'admin_desa', 'village_name' => 'Desa Sukamaju'],
            ['name' => 'Pengurus Kopdes', 'email' => 'kopdes@desahub.test', 'phone' => '081100000003', 'nik' => '3201010000000003', 'role' => 'pengurus_kopdes', 'village_name' => 'Desa Sukamaju'],
            ['name' => 'Pengurus BUMDes', 'email' => 'bumdes@desahub.test', 'phone' => '081100000004', 'nik' => '3201010000000004', 'role' => 'pengurus_bumdes', 'village_name' => 'Desa Sukamaju'],
            ['name' => 'UMKM Petani', 'email' => 'umkm@desahub.test', 'phone' => '081100000005', 'nik' => '3201010000000005', 'role' => 'umkm_petani', 'village_name' => 'Desa Sukamaju'],
            ['name' => 'Operator MBG', 'email' => 'mbg@desahub.test', 'phone' => '081100000006', 'nik' => '3201010000000006', 'role' => 'operator_mbg', 'village_name' => 'Desa Sukamaju'],
            ['name' => 'Warga Desa', 'email' => 'warga@desahub.test', 'phone' => '081100000007', 'nik' => '3201010000000007', 'role' => 'warga', 'village_name' => 'Desa Sukamaju'],
            ['name' => 'Pemda Viewer', 'email' => 'pemda@desahub.test', 'phone' => '081100000008', 'nik' => '3201010000000008', 'role' => 'pemda_viewer', 'village_name' => 'Desa Sukamaju'],
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
                    'village_name' => $account['village_name'],
                    'password' => Hash::make(self::DEMO_PIN),
                    'pin_hash' => Hash::make(self::DEMO_PIN),
                    'status' => 'active',
                    'failed_login_attempts' => 0,
                    'locked_until' => null,
                    'must_change_pin' => false,
                ]
            );
        }

        $this->command?->info('Akun demo siap. Login admin/admin atau pakai No. HP/NIK akun demo lain dengan PIN '.self::DEMO_PIN.'.');
    }
}
