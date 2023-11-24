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
        Schema::create('outlet_exchange_details', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('outlet_exchange_id')->unsigned();
            $table->foreign('outlet_exchange_id')->references('id')->on('outlet_exchanges');
            $table->bigInteger('medicine_id')->unsigned();
            $table->foreign('medicine_id')->references('id')->on('medicines');
            $table->string('medicine_name')->nullable();
            $table->string('size')->nullable();
            $table->integer('available_qty')->default(1)->unsigned();
            $table->integer('quantity')->default(1);
            $table->float('rate', 8,2)->default(0.00);
            $table->float('total_price', 10,2)->default(0.00);
            $table->tinyInteger('is_exchange')->nullable();
            $table->softDeletes();
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
        Schema::dropIfExists('outlet_exchange_details');
    }
};
