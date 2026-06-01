<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('umkm_profile_id')->constrained('umkm_profiles')->cascadeOnDelete();
            $table->string('name');
            $table->text('description');
            $table->decimal('price', 16, 2);
            $table->string('unit');
            $table->decimal('stock', 16, 2)->default(0);
            $table->decimal('minimum_stock', 16, 2)->default(0);
            $table->string('category');
            $table->string('photo_url')->nullable();
            $table->string('status')->default('aktif');
            $table->boolean('is_marketplace_visible')->default(true);
            $table->timestamps();
        });

        Schema::create('marketplace_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('umkm_profile_id')->constrained('umkm_profiles')->cascadeOnDelete();
            $table->string('order_number')->unique();
            $table->string('buyer_name');
            $table->string('buyer_phone')->nullable();
            $table->text('shipping_address');
            $table->text('notes')->nullable();
            $table->decimal('total_amount', 16, 2)->default(0);
            $table->string('status')->default('menunggu_konfirmasi');
            $table->timestamp('confirmed_at')->nullable();
            $table->timestamp('shipped_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->timestamps();
        });

        Schema::create('marketplace_order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('marketplace_orders')->cascadeOnDelete();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->decimal('quantity', 16, 2);
            $table->string('unit');
            $table->decimal('price', 16, 2);
            $table->decimal('subtotal', 16, 2);
            $table->timestamps();
        });

        Schema::create('umkm_notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('umkm_profile_id')->nullable()->constrained('umkm_profiles')->nullOnDelete();
            $table->foreignId('order_id')->nullable()->constrained('marketplace_orders')->nullOnDelete();
            $table->string('recipient_type');
            $table->string('recipient_name')->nullable();
            $table->string('channel')->default('system');
            $table->string('title');
            $table->text('message');
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
        });

        Schema::create('umkm_financial_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('umkm_profile_id')->constrained('umkm_profiles')->cascadeOnDelete();
            $table->foreignId('order_id')->nullable()->constrained('marketplace_orders')->nullOnDelete();
            $table->string('type');
            $table->decimal('amount', 16, 2);
            $table->string('description');
            $table->date('transaction_date');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('umkm_financial_transactions');
        Schema::dropIfExists('umkm_notifications');
        Schema::dropIfExists('marketplace_order_items');
        Schema::dropIfExists('marketplace_orders');
        Schema::dropIfExists('products');
    }
};
