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
        Schema::table('medicine_distribute_details', function (Blueprint $table) {
            $table->boolean('has_sent')->default(false)->after('rate');
            $table->boolean('has_received')->default(false)->after('has_sent');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('medicine_distribute_details', function (Blueprint $table) {
            $table->dropColumn('has_sent');
            $table->dropColumn('has_received');
        });
    }
};
