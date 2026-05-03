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
        Schema::create('distributor_orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // The distributor
            $table->integer('quantity');
            $table->string('status')->default('Menunggu Proses'); // Menunggu Proses, Diproses, Dikirim, Selesai
            $table->string('tracking_number')->nullable();
            $table->timestamps();
        });

        Schema::create('sales_reports', function (Blueprint $table) {
            $table->id();
            $table->string('region'); // National, Province Name, etc.
            $table->string('period'); // Month/Year, e.g., '2024-07'
            $table->integer('volume');
            $table->decimal('revenue', 15, 2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales_reports');
        Schema::dropIfExists('distributor_orders');
    }
};
