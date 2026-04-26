<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('username')->unique();
            $table->string('email')->nullable()->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('role')->default('reseller'); // admin, produsen, distributor, reseller
            $table->string('status')->default('pending'); // active, pending, suspended
            
            // Profile Info (Wajib)
            $table->string('phone');
            $table->string('nik', 16)->unique();
            $table->string('ktp_photo');
            $table->text('address');
            $table->string('province_id'); // Menyimpan code wilayah
            $table->string('city_id');     // Menyimpan code wilayah
            $table->string('district_id'); // Menyimpan code wilayah
            
            // Bank Info (Wajib)
            $table->string('bank_name');
            $table->string('bank_account_name');
            $table->string('bank_account_number')->unique();
            
            // Referral/Network Info (Opsional)
            $table->string('referral_code')->nullable();
            $table->foreignId('upline_id')->nullable()->constrained('users');
            
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
