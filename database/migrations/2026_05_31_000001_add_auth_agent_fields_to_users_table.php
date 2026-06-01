<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('pin_hash')->nullable()->after('password');
            $table->string('status')->default('active')->after('pin_hash');
            $table->unsignedTinyInteger('failed_login_attempts')->default(0)->after('status');
            $table->timestamp('locked_until')->nullable()->after('failed_login_attempts');
            $table->timestamp('last_login_at')->nullable()->after('locked_until');
            $table->text('remember_refresh_token')->nullable()->after('remember_token');
            $table->timestamp('remember_refresh_token_expires_at')->nullable()->after('remember_refresh_token');
            $table->boolean('must_change_pin')->default(false)->after('remember_refresh_token_expires_at');
            $table->string('village_name')->nullable()->after('nik');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'pin_hash',
                'status',
                'failed_login_attempts',
                'locked_until',
                'last_login_at',
                'remember_refresh_token',
                'remember_refresh_token_expires_at',
                'must_change_pin',
                'village_name',
            ]);
        });
    }
};
