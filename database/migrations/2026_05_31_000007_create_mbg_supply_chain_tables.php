<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mbg_orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique();
            $table->string('village_name');
            $table->string('beneficiary_name');
            $table->unsignedInteger('target_portions');
            $table->unsignedInteger('realized_portions')->default(0);
            $table->date('distribution_date')->nullable();
            $table->string('status')->default('approved');
            $table->timestamps();
        });

        Schema::create('mbg_supplier_confirmations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mbg_order_id')->constrained('mbg_orders')->cascadeOnDelete();
            $table->string('supplier_name');
            $table->string('supplier_type')->default('desa_sendiri');
            $table->string('product_name');
            $table->decimal('quantity', 16, 2);
            $table->string('unit');
            $table->date('delivery_date');
            $table->decimal('estimated_unit_price', 16, 2)->default(0);
            $table->decimal('estimated_total_cost', 16, 2)->default(0);
            $table->string('status')->default('menunggu_konfirmasi');
            $table->timestamp('notified_at')->nullable();
            $table->timestamp('reminder_due_at')->nullable();
            $table->timestamp('confirmation_due_at')->nullable();
            $table->timestamp('confirmed_at')->nullable();
            $table->timestamp('failed_at')->nullable();
            $table->boolean('admin_approval_required')->default(false);
            $table->text('risk_note')->nullable();
            $table->timestamps();
        });

        Schema::create('mbg_notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mbg_order_id')->nullable()->constrained('mbg_orders')->nullOnDelete();
            $table->foreignId('supplier_confirmation_id')->nullable()->constrained('mbg_supplier_confirmations')->nullOnDelete();
            $table->string('recipient_type');
            $table->string('recipient_name');
            $table->string('channel')->default('in_app');
            $table->string('title');
            $table->text('message');
            $table->timestamp('send_at')->nullable();
            $table->timestamps();
        });

        Schema::create('mbg_distributions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mbg_order_id')->constrained('mbg_orders')->cascadeOnDelete();
            $table->string('school_name');
            $table->unsignedInteger('target_portions');
            $table->unsignedInteger('realized_portions');
            $table->date('distributed_at');
            $table->string('status')->default('selesai');
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('mbg_financial_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mbg_order_id')->nullable()->constrained('mbg_orders')->nullOnDelete();
            $table->foreignId('supplier_confirmation_id')->nullable()->constrained('mbg_supplier_confirmations')->nullOnDelete();
            $table->string('supplier_name');
            $table->string('supplier_type');
            $table->decimal('amount', 16, 2);
            $table->string('description');
            $table->date('transaction_date');
            $table->timestamps();
        });

        Schema::create('mbg_incidents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mbg_order_id')->nullable()->constrained('mbg_orders')->nullOnDelete();
            $table->foreignId('supplier_confirmation_id')->nullable()->constrained('mbg_supplier_confirmations')->nullOnDelete();
            $table->string('type');
            $table->text('description');
            $table->string('status')->default('open');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mbg_incidents');
        Schema::dropIfExists('mbg_financial_transactions');
        Schema::dropIfExists('mbg_distributions');
        Schema::dropIfExists('mbg_notifications');
        Schema::dropIfExists('mbg_supplier_confirmations');
        Schema::dropIfExists('mbg_orders');
    }
};
