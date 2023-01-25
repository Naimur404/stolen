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
        Schema::create('medicines', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('manufacturer_id')->unsigned();
            $table->foreign('manufacturer_id')->references('id')->on('manufacturers');
            $table->bigInteger('category_id')->unsigned();
            $table->foreign('category_id')->references('id')->on('categories');
            $table->bigInteger('unit_id')->unsigned();
            $table->foreign('unit_id')->references('id')->on('units');
            $table->bigInteger('type_id')->nullable();
            $table->string('medicine_name')->nullable();
            $table->string('generic_name')->nullable();
            $table->string('strength')->nullable();
            $table->string('box_size')->nullable();
            $table->float('price',8,2)->default(0.00);
            $table->string('tax')->nullable();
            $table->float('manufacturer_price',10,2)->default(0.00);
            $table->text('medicine_details')->nullable();
            $table->string('image')->nullable();
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
        Schema::dropIfExists('medicines');
    }
};
