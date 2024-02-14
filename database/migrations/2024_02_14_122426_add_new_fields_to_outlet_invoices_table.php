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
        Schema::table('outlet_invoices', function (Blueprint $table) {
            $table->float('delivery_charge', 6,2)->after('vat')->default(0.00);
            $table->float('total_with_charge', 6,2)->after('grand_total')->default(0.00);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('outlet_invoices', function (Blueprint $table) {
            $table->dropColumn(['delivery_charge', 'total_with_charge']);
        });
    }
};
