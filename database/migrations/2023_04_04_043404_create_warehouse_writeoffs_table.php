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
        Schema::create('warehouse_writeoffs', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('warehouse_id')->unsigned();
            $table->foreign('warehouse_id')->references('id')->on('warehouses');
            $table->bigInteger('warehouse_stock_id')->unsigned();
            $table->foreign('warehouse_stock_id')->references('id')->on('warehouse_stocks');
            $table->bigInteger('medicine_id')->unsigned();
            $table->string('medicine_name')->nullable();
            $table->integer('previous_stock')->default(1);
            $table->integer('quantity')->default(1);
            $table->enum('reason', ['Expired', 'Damage', 'Lost', 'Adjust', 'Others'])->nullable();
            $table->string('remarks')->nullable();
            $table->bigInteger('added_by')->unsigned()->nullable();
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
        Schema::dropIfExists('warehouse_writeoffs');
    }
};
