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
        Schema::create('medicine_distribute_details', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('medicine_distribute_id')->unsigned();
            $table->foreign('medicine_distribute_id')->references('id')->on('medicine_distributes');
            $table->bigInteger('medicine_id')->unsigned();
            $table->foreign('medicine_id')->references('id')->on('medicines');
            $table->string('medicine_name')->nullable();
            $table->string('rack_no')->nullable();
            $table->date('expiry_date')->nullable();
            $table->integer('quantity')->default(1);
            $table->float('rate', 8,2)->default(0.00);
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
        Schema::dropIfExists('medicine_distribute_details');
    }
};
