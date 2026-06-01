<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('aset_desa', function (Blueprint $table) {
            $table->id();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('village_name')->nullable();
            $table->string('name');
            $table->string('category');
            $table->string('location_description');
            $table->decimal('estimated_value', 16, 2)->default(0);
            $table->string('condition');
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->string('photo_url')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('umkm_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('owner_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('village_name')->nullable();
            $table->string('business_name');
            $table->string('owner_name');
            $table->string('phone');
            $table->string('business_type');
            $table->json('main_products');
            $table->decimal('production_capacity', 16, 2);
            $table->string('production_unit');
            $table->string('production_period');
            $table->boolean('needs_capital');
            $table->text('address');
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->timestamps();
        });

        Schema::create('village_human_resources', function (Blueprint $table) {
            $table->id();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('village_name')->nullable();
            $table->string('name');
            $table->string('category');
            $table->string('phone')->nullable();
            $table->text('address')->nullable();
            $table->timestamps();
        });

        Schema::create('village_commodities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('village_name')->nullable();
            $table->string('name');
            $table->decimal('monthly_production_volume', 16, 2);
            $table->string('unit');
            $table->decimal('price', 16, 2);
            $table->string('location_description');
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('village_commodities');
        Schema::dropIfExists('village_human_resources');
        Schema::dropIfExists('umkm_profiles');
        Schema::dropIfExists('aset_desa');
    }
};
