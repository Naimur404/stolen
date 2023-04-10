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
        Schema::table('outlet_invoice_details', function (Blueprint $table) {
            $table->bigInteger('stock_id')->unsigned()->nullable()->after('outlet_invoice_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('outlet_invoice_details', function (Blueprint $table) {
            $table->dropColumn('stock_id');
        });
    }
};
