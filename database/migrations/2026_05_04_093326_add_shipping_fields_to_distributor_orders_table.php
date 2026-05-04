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
        Schema::table('distributor_orders', function (Blueprint $table) {
            $table->string('courier_name')->nullable()->after('tracking_number');
            $table->text('notes')->nullable()->after('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('distributor_orders', function (Blueprint $table) {
            $table->dropColumn(['courier_name', 'notes']);
        });
    }
};
