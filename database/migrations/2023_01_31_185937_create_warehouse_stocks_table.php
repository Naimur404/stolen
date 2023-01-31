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
        Schema::create('warehouse_stocks', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('warehouse_id')->unsigned();
            $table->foreign('warehouse_id')->references('id')->on('warehouses');
            $table->bigInteger('medicine_id')->unsigned();
            $table->foreign('medicine_id')->references('id')->on('medicines');
            $table->date('expiry_date')->nullable();
            $table->integer('quantity')->default(0);
            $table->float('price', 8,2)->default(0.0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('warehouse_stocks');
    }
};
