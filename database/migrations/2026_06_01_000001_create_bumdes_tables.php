<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bumdes_units', function (Blueprint $table) {
            $table->id();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('village_name')->nullable();
            $table->string('name');
            $table->string('category');
            $table->decimal('pades_percentage', 5, 2)->default(20);
            $table->string('status')->default('active');
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('bumdes_unit_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('unit_id')->constrained('bumdes_units')->cascadeOnDelete();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('reversal_of_id')->nullable()->constrained('bumdes_unit_transactions')->nullOnDelete();
            $table->string('transaction_number')->unique();
            $table->string('type');
            $table->string('category')->default('umum');
            $table->decimal('amount', 16, 2);
            $table->date('transaction_date');
            $table->string('description');
            $table->string('status')->default('posted');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bumdes_unit_transactions');
        Schema::dropIfExists('bumdes_units');
    }
};
