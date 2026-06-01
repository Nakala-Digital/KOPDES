<?php

namespace Database\Seeders;

use App\Models\AsetDesa;
use App\Models\BumdesUnit;
use App\Models\BumdesUnitTransaction;
use App\Models\KoperasiInstallment;
use App\Models\KoperasiLoan;
use App\Models\KoperasiMember;
use App\Models\KoperasiSaving;
use App\Models\MarketplaceOrder;
use App\Models\MarketplaceOrderItem;
use App\Models\MbgDistribution;
use App\Models\MbgFinancialTransaction;
use App\Models\MbgIncident;
use App\Models\MbgOrder;
use App\Models\MbgSupplierConfirmation;
use App\Models\Product;
use App\Models\UmkmFinancialTransaction;
use App\Models\UmkmProfile;
use App\Models\User;
use App\Models\VillageCommodity;
use App\Models\VillageHumanResource;
use Illuminate\Database\Seeder;

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('phone', '081100000002')->first();
        $umkmUser = User::where('phone', '081100000005')->first();
        $village = 'Desa Maju';

        AsetDesa::updateOrCreate(
            ['name' => 'Lahan Pertanian Blok A', 'village_name' => $village],
            [
                'created_by' => $admin?->id,
                'category' => 'tanah',
                'location_description' => 'Dusun Tengah',
                'estimated_value' => 150000000,
                'condition' => 'baik',
                'notes' => 'Lahan produktif untuk padi dan sayur.',
            ]
        );

        VillageHumanResource::updateOrCreate(
            ['name' => 'Kelompok Tani Subur', 'village_name' => $village],
            [
                'created_by' => $admin?->id,
                'category' => 'petani',
                'phone' => '081222333444',
                'address' => 'Dusun Timur',
            ]
        );

        VillageCommodity::updateOrCreate(
            ['name' => 'Beras Premium', 'village_name' => $village],
            [
                'created_by' => $admin?->id,
                'monthly_production_volume' => 2500,
                'unit' => 'kg',
                'price' => 12500,
                'location_description' => 'Area sawah Desa Maju',
            ]
        );

        $umkm = UmkmProfile::updateOrCreate(
            ['phone' => '081233344455'],
            [
                'created_by' => $admin?->id,
                'owner_user_id' => $umkmUser?->id,
                'village_name' => $village,
                'business_name' => 'Dapur Desa Sari',
                'owner_name' => 'Ibu Sari',
                'business_type' => 'Makanan',
                'main_products' => ['Keripik pisang', 'Kue basah'],
                'production_capacity' => 300,
                'production_unit' => 'bungkus',
                'production_period' => 'bulan',
                'needs_capital' => true,
                'address' => 'Dusun Barat RT 02',
            ]
        );

        $product = Product::updateOrCreate(
            ['umkm_profile_id' => $umkm->id, 'name' => 'Keripik Pisang'],
            [
                'description' => 'Keripik pisang renyah produksi UMKM Desa Maju.',
                'price' => 15000,
                'unit' => 'bungkus',
                'stock' => 2,
                'minimum_stock' => 5,
                'category' => 'Makanan',
                'status' => 'aktif',
                'is_marketplace_visible' => true,
            ]
        );

        $order = MarketplaceOrder::updateOrCreate(
            ['order_number' => 'ORD-DEMO-001'],
            [
                'umkm_profile_id' => $umkm->id,
                'buyer_name' => 'Bu Ani',
                'buyer_phone' => '081299988877',
                'shipping_address' => 'Dusun Utara',
                'notes' => 'Kirim sore hari.',
                'total_amount' => 30000,
                'status' => 'diproses',
                'confirmed_at' => now(),
            ]
        );

        MarketplaceOrderItem::updateOrCreate(
            ['order_id' => $order->id, 'product_id' => $product->id],
            [
                'quantity' => 2,
                'unit' => 'bungkus',
                'price' => 15000,
                'subtotal' => 30000,
            ]
        );

        $todayTransaction = UmkmFinancialTransaction::updateOrCreate(
            ['order_id' => $order->id, 'type' => 'penjualan'],
            [
                'umkm_profile_id' => $umkm->id,
                'amount' => 30000,
                'description' => 'Penjualan Keripik Pisang',
                'transaction_date' => now()->toDateString(),
            ]
        );
        $todayTransaction->forceFill(['created_at' => now(), 'updated_at' => now()])->save();

        $yesterdayTransaction = UmkmFinancialTransaction::updateOrCreate(
            ['umkm_profile_id' => $umkm->id, 'description' => 'Penjualan kemarin'],
            [
                'type' => 'penjualan',
                'amount' => 20000,
                'transaction_date' => now()->subDay()->toDateString(),
            ]
        );
        $yesterdayTransaction->forceFill(['created_at' => now()->subDay(), 'updated_at' => now()->subDay()])->save();

        $member = KoperasiMember::updateOrCreate(
            ['member_number' => 'KOP-DEMO-001'],
            [
                'created_by' => $admin?->id,
                'name' => 'Bapak Jaya',
                'phone' => '081244455566',
                'nik' => '3201011234567890',
                'village_name' => $village,
                'address' => 'Dusun Tengah',
                'status' => 'active',
                'joined_at' => now()->subMonths(6)->toDateString(),
            ]
        );

        KoperasiSaving::updateOrCreate(
            ['member_id' => $member->id, 'type' => 'pokok'],
            [
                'created_by' => $admin?->id,
                'amount' => 1000000,
                'paid_at' => now()->subMonths(6)->toDateString(),
                'notes' => 'Simpanan pokok demo.',
            ]
        );

        KoperasiSaving::updateOrCreate(
            ['member_id' => $member->id, 'type' => 'wajib', 'period' => now()->format('Y-m')],
            [
                'created_by' => $admin?->id,
                'amount' => 100000,
                'paid_at' => now()->toDateString(),
                'notes' => 'Simpanan wajib bulan ini.',
            ]
        );

        $loan = KoperasiLoan::updateOrCreate(
            ['member_id' => $member->id, 'principal_amount' => 1500000],
            [
                'created_by' => $admin?->id,
                'interest_rate' => 1.5,
                'tenor_months' => 6,
                'monthly_installment' => 272500,
                'total_payable' => 1635000,
                'status' => 'active',
                'approved_at' => now()->subMonths(3)->toDateString(),
                'due_start_date' => now()->subMonths(2)->toDateString(),
                'notes' => 'Pinjaman modal kerja demo.',
            ]
        );

        KoperasiInstallment::updateOrCreate(
            ['loan_id' => $loan->id, 'installment_number' => 1],
            [
                'due_date' => now()->subDays(70)->toDateString(),
                'amount_due' => 272500,
                'amount_paid' => 0,
                'status' => 'unpaid',
            ]
        );

        $mbgOrder = MbgOrder::updateOrCreate(
            ['order_number' => 'MBG-DEMO-001'],
            [
                'village_name' => $village,
                'beneficiary_name' => 'SD Negeri 1 Desa Maju',
                'target_portions' => 300,
                'realized_portions' => 275,
                'distribution_date' => now()->toDateString(),
                'status' => 'approved',
            ]
        );

        $supplier = MbgSupplierConfirmation::updateOrCreate(
            ['mbg_order_id' => $mbgOrder->id, 'supplier_name' => 'Petani Makmur', 'product_name' => 'Beras'],
            [
                'supplier_type' => 'desa_sendiri',
                'quantity' => 100,
                'unit' => 'kg',
                'delivery_date' => now()->addDay()->toDateString(),
                'estimated_unit_price' => 12000,
                'estimated_total_cost' => 1200000,
                'status' => 'menunggu_konfirmasi',
                'notified_at' => now()->subHours(7),
                'reminder_due_at' => now()->subHour(),
                'confirmation_due_at' => now()->addHours(17),
            ]
        );

        MbgDistribution::updateOrCreate(
            ['mbg_order_id' => $mbgOrder->id, 'school_name' => 'SD Negeri 1 Desa Maju'],
            [
                'target_portions' => 300,
                'realized_portions' => 275,
                'distributed_at' => now()->toDateString(),
                'status' => 'selesai',
                'notes' => 'Distribusi berjalan baik, ada kekurangan 25 porsi.',
            ]
        );

        MbgFinancialTransaction::updateOrCreate(
            ['mbg_order_id' => $mbgOrder->id, 'supplier_confirmation_id' => $supplier->id],
            [
                'supplier_name' => 'Petani Makmur',
                'supplier_type' => 'desa_sendiri',
                'amount' => 1200000,
                'description' => 'Komitmen pembelian beras MBG',
                'transaction_date' => now()->toDateString(),
            ]
        );

        MbgIncident::updateOrCreate(
            ['mbg_order_id' => $mbgOrder->id, 'supplier_confirmation_id' => $supplier->id, 'type' => 'konfirmasi_terlambat'],
            [
                'description' => 'Supplier belum konfirmasi lebih dari 6 jam.',
                'status' => 'open',
            ]
        );

        $bumdesUnit = BumdesUnit::updateOrCreate(
            ['name' => 'Wardes Maju', 'village_name' => $village],
            [
                'created_by' => $admin?->id,
                'category' => 'wardes',
                'pades_percentage' => 25,
                'status' => 'active',
                'notes' => 'Unit warung desa untuk kebutuhan pokok warga.',
            ]
        );

        BumdesUnitTransaction::updateOrCreate(
            ['transaction_number' => 'BUM-DEMO-IN-001'],
            [
                'unit_id' => $bumdesUnit->id,
                'created_by' => $admin?->id,
                'type' => 'income',
                'category' => 'penjualan',
                'amount' => 5000000,
                'transaction_date' => now()->toDateString(),
                'description' => 'Penjualan kebutuhan pokok bulan ini',
                'status' => 'posted',
            ]
        );

        BumdesUnitTransaction::updateOrCreate(
            ['transaction_number' => 'BUM-DEMO-EX-001'],
            [
                'unit_id' => $bumdesUnit->id,
                'created_by' => $admin?->id,
                'type' => 'expense',
                'category' => 'belanja_stok',
                'amount' => 3200000,
                'transaction_date' => now()->toDateString(),
                'description' => 'Belanja stok warung desa',
                'status' => 'posted',
            ]
        );

        $this->command?->info('Data demo dashboard, pendataan, UMKM, Kopdes, BUMDes, dan MBG berhasil diisi.');
    }
}
