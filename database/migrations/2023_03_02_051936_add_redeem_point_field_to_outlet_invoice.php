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
            $table->integer('redeem_point')->unsigned()->after('earn_point')->default(0)->nullable();
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
            $table->dropColumn('redeem_point');
        });
    }
};
