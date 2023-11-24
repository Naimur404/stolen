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
            $table->tinyInteger('is_exchange')->after('payment_method_id')->default('0');
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
            $table->dropColumn('is_exchange');
        });
    }
};
