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
        Schema::create('medicine_purchase_details', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('warehouse_id')->unsigned();
            $table->foreign('warehouse_id')->references('id')->on('warehouses');
            $table->bigInteger('medicine_purchase_id')->unsigned();
            $table->foreign('medicine_purchase_id')->references('id')->on('medicine_purchases');
            $table->bigInteger('medicine_id')->unsigned();
            $table->foreign('medicine_id')->references('id')->on('medicines');
            $table->string('medicine_name')->nullable();
            $table->string('product_type')->nullable()->default('t-shirt');
            $table->string('rack_no')->nullable();
            $table->string('size')->nullable();
            $table->timestamp('create_date')->nullable();
            $table->integer('quantity')->default(1);
            $table->float('manufacturer_price', 10,2)->default(0.00);
            $table->float('box_mrp', 10,2)->default(0.00);
            $table->float('rate', 8,2)->default(0.00);
            $table->float('total_price', 12,2)->default(0.00);
            $table->float('vat',8,2)->default(0.00);
            $table->float('total_discount', 8,2)->default(0.00);
            $table->float('total_amount', 12,2)->default(0.00);
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
        Schema::dropIfExists('medicine_purchase_details');
    }
};
