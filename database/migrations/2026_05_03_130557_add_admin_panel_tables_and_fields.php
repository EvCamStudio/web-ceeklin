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
        // Add stock to users table for distributors
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'stock')) {
                $table->integer('stock')->default(0)->after('status');
            }
        });

        // Table for pricing control
        Schema::create('pricings', function (Blueprint $table) {
            $table->id();
            $table->string('tier'); // distributor, reseller
            $table->decimal('price', 15, 2);
            $table->timestamps();
        });

        // Table for general settings (e.g., targets)
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->timestamps();
        });

        // Table for bonus allocations
        Schema::create('bonus_allocations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->decimal('amount', 15, 2);
            $table->string('status')->default('pending'); // pending, paid
            $table->string('quarter'); // e.g., Q3 2024
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bonus_allocations');
        Schema::dropIfExists('settings');
        Schema::dropIfExists('pricings');
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('stock');
        });
    }
};
