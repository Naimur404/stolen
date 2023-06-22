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
        Schema::create('warehouse_return_details', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('warehouse_return_id')->unsigned();
            $table->foreign('warehouse_return_id')->references('id')->on('warehouse_returns');
            $table->bigInteger('medicine_id')->unsigned();
            $table->foreign('medicine_id')->references('id')->on('medicines');
            $table->string('medicine_name')->nullable();
            $table->string('size')->nullable();
            $table->string('create_date')->nullable();
            $table->integer('quantity')->default(1);
            $table->boolean('has_received')->default(false);
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
        Schema::dropIfExists('warehouse_return_details');
    }
};
