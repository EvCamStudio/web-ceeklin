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
        Schema::dropIfExists('sales_reports');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::create('sales_reports', function (Blueprint $table) {
            $table->id();
            $table->string('region');
            $table->string('period');
            $table->integer('volume');
            $table->decimal('revenue', 15, 2)->default(0);
            $table->timestamps();
        });
    }
};
