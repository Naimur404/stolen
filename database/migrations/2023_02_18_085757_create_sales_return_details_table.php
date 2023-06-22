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
        Schema::create('sales_return_details', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('sales_return_id')->unsigned();
            $table->foreign('sales_return_id')->references('id')->on('sales_returns');
            $table->bigInteger('medicine_id')->unsigned();
            $table->foreign('medicine_id')->references('id')->on('medicines');
            $table->string('medicine_name')->nullable();
            $table->string('size')->nullable();
            $table->string('create_date')->nullable();
            $table->integer('sold_qty')->default(1)->unsigned();
            $table->integer('return_qty')->default(0)->unsigned();
            $table->float('rate', 8,2)->default(0.00);
            $table->float('total_price', 10,2)->default(0.00);
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
        Schema::dropIfExists('sales_return_details');
    }
};
