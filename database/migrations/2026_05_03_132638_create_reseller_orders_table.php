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
        Schema::create('reseller_orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique();
            $table->foreignId('reseller_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('distributor_id')->constrained('users')->onDelete('cascade');
            $table->integer('quantity');
            $table->decimal('total_price', 15, 2)->default(0);
            $table->string('status')->default('Menunggu Konfirmasi'); // Menunggu Konfirmasi, Diproses, Dikirim, Selesai, Ditolak
            $table->string('payment_status')->default('Belum Dibayar'); // Belum Dibayar, Dibayar, Dikonfirmasi
            $table->string('payment_proof')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reseller_orders');
    }
};
