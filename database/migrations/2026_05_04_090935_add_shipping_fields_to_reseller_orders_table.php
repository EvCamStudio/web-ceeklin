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
        Schema::table('reseller_orders', function (Blueprint $table) {
            $table->string('courier_name')->nullable()->after('status');
            $table->string('tracking_number')->nullable()->after('courier_name');
            $table->text('reject_reason')->nullable()->after('tracking_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reseller_orders', function (Blueprint $table) {
            $table->dropColumn(['courier_name', 'tracking_number', 'reject_reason']);
        });
    }
};
