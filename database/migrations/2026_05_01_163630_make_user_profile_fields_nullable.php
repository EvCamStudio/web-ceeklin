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
        Schema::table('users', function (Blueprint $table) {
            $table->string('nik', 16)->nullable()->change();
            $table->string('ktp_photo')->nullable()->change();
            $table->text('address')->nullable()->change();
            $table->string('province_id')->nullable()->change();
            $table->string('city_id')->nullable()->change();
            $table->string('district_id')->nullable()->change();
            $table->string('bank_name')->nullable()->change();
            $table->string('bank_account_name')->nullable()->change();
            $table->string('bank_account_number')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('nik', 16)->nullable(false)->change();
            $table->string('ktp_photo')->nullable(false)->change();
            $table->text('address')->nullable(false)->change();
            $table->string('province_id')->nullable(false)->change();
            $table->string('city_id')->nullable(false)->change();
            $table->string('district_id')->nullable(false)->change();
            $table->string('bank_name')->nullable(false)->change();
            $table->string('bank_account_name')->nullable(false)->change();
            $table->string('bank_account_number')->nullable(false)->change();
        });
    }
};
