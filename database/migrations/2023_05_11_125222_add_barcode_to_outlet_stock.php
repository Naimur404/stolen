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
        Schema::table('outlet_stocks', function (Blueprint $table) {
            $table->bigInteger('warehouse_stock_id')->unsigned()->nullable()->after('outlet_id');
            $table->string('barcode_text')->after('medicine_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('outlet_stocks', function (Blueprint $table) {
            $table->dropColumn('warehouse_stock_id');
            $table->dropColumn('barcode_text');
        });
    }
};
