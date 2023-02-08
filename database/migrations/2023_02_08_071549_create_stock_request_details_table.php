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
        Schema::create('stock_request_details', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('stock_request_id')->unsigned();
            $table->foreign('stock_request_id')->references('id')->on('stock_requests');
            $table->bigInteger('medicine_id')->unsigned();
            $table->foreign('medicine_id')->references('id')->on('medicines');
            $table->string('medicine_name')->nullable();
            $table->integer('quantity')->default(1);
            $table->boolean('has_accepted')->default(false);
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
        Schema::dropIfExists('stock_request_details');
    }
};
