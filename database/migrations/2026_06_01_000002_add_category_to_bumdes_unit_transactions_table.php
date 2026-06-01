<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bumdes_unit_transactions', function (Blueprint $table) {
            if (! Schema::hasColumn('bumdes_unit_transactions', 'category')) {
                $table->string('category')->default('umum')->after('type');
            }
        });
    }

    public function down(): void
    {
        Schema::table('bumdes_unit_transactions', function (Blueprint $table) {
            if (Schema::hasColumn('bumdes_unit_transactions', 'category')) {
                $table->dropColumn('category');
            }
        });
    }
};
