<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        Role::updateOrCreate(['slug' => 'super_admin'], [
            'name' => 'Super Admin',
            'description' => 'Nakala / Romulus',
            'dashboard_type' => 'super_admin',
            'dashboard_label' => 'Super Admin',
        ]);

        Role::updateOrCreate(['slug' => 'admin_desa'], [
            'name' => 'Admin Desa',
            'description' => 'Kepala desa / staf',
            'dashboard_type' => 'admin_desa',
            'dashboard_label' => 'Admin Desa',
        ]);

        Role::updateOrCreate(['slug' => 'pengurus_kopdes'], [
            'name' => 'Pengurus Kopdes',
            'description' => 'KDMP / Koperasi',
            'dashboard_type' => 'pengurus_kopdes',
            'dashboard_label' => 'Pengurus Kopdes',
        ]);

        Role::updateOrCreate(['slug' => 'pengurus_bumdes'], [
            'name' => 'Pengurus BUMDes',
            'description' => 'Manajer unit usaha',
            'dashboard_type' => 'pengurus_bumdes',
            'dashboard_label' => 'Pengurus BUMDes',
        ]);

        Role::updateOrCreate(['slug' => 'umkm_petani'], [
            'name' => 'UMKM / Petani',
            'description' => 'Pelaku usaha desa dan petani',
            'dashboard_type' => 'umkm_petani',
            'dashboard_label' => 'UMKM / Petani',
        ]);

        Role::updateOrCreate(['slug' => 'operator_mbg'], [
            'name' => 'Operator MBG',
            'description' => 'Operator makan bergizi',
            'dashboard_type' => 'operator_mbg',
            'dashboard_label' => 'Operator MBG',
        ]);

        Role::updateOrCreate(['slug' => 'warga'], [
            'name' => 'Warga',
            'description' => 'Warga desa',
            'dashboard_type' => 'warga',
            'dashboard_label' => 'Warga',
        ]);

        Role::updateOrCreate(['slug' => 'pemda_viewer'], [
            'name' => 'Pemda / Viewer',
            'description' => 'Pemantau pemerintah daerah',
            'dashboard_type' => 'pemda_viewer',
            'dashboard_label' => 'Pemda / Viewer',
        ]);
    }
}
