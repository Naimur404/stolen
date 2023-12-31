<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('outlet_invoices', function (Blueprint $table) {
            $table->double('payable_amount', 8,2)->default(0.00)->after('grand_total');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('outlet_invoices', function (Blueprint $table) {
            $table->dropColumn('payable_amount');
        });
    }
};
