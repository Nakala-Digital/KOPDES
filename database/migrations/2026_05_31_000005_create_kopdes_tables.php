<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kopdes_settings', function (Blueprint $table) {
            $table->id();
            $table->string('village_name')->nullable();
            $table->decimal('monthly_interest_rate', 5, 2)->default(1.5);
            $table->decimal('principal_saving_amount', 16, 2)->default(0);
            $table->decimal('mandatory_saving_amount', 16, 2)->default(0);
            $table->timestamps();
        });

        Schema::create('koperasi_members', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('member_number')->unique();
            $table->string('name');
            $table->string('phone')->nullable();
            $table->string('nik')->nullable();
            $table->string('village_name')->nullable();
            $table->text('address')->nullable();
            $table->string('status')->default('active');
            $table->date('joined_at')->nullable();
            $table->timestamps();
        });

        Schema::create('koperasi_savings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('member_id')->constrained('koperasi_members')->cascadeOnDelete();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('type');
            $table->decimal('amount', 16, 2);
            $table->date('paid_at');
            $table->string('period')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('koperasi_loans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('member_id')->constrained('koperasi_members')->cascadeOnDelete();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->decimal('principal_amount', 16, 2);
            $table->decimal('interest_rate', 5, 2);
            $table->unsignedSmallInteger('tenor_months');
            $table->decimal('monthly_installment', 16, 2);
            $table->decimal('total_payable', 16, 2);
            $table->string('status')->default('active');
            $table->date('approved_at')->nullable();
            $table->date('due_start_date')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('koperasi_installments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('loan_id')->constrained('koperasi_loans')->cascadeOnDelete();
            $table->unsignedSmallInteger('installment_number');
            $table->date('due_date');
            $table->decimal('amount_due', 16, 2);
            $table->decimal('amount_paid', 16, 2)->default(0);
            $table->date('paid_at')->nullable();
            $table->string('status')->default('unpaid');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('koperasi_installments');
        Schema::dropIfExists('koperasi_loans');
        Schema::dropIfExists('koperasi_savings');
        Schema::dropIfExists('koperasi_members');
        Schema::dropIfExists('kopdes_settings');
    }
};
