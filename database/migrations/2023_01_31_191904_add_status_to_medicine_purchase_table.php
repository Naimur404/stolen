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
        Schema::table('medicine_purchases', function (Blueprint $table) {
            $table->integer('status')->unsigned()->default(1)->after('payment_method_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('medicine_purchases', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};
