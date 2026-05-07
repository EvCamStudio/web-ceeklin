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
            $table->string('evidence_photo')->nullable()->after('notes');
        });

        Schema::table('reseller_orders', function (Blueprint $table) {
            $table->string('evidence_photo')->nullable()->after('note');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('distributor_orders', function (Blueprint $table) {
            $table->dropColumn('evidence_photo');
        });

        Schema::table('reseller_orders', function (Blueprint $table) {
            $table->dropColumn('evidence_photo');
        });
    }
};
