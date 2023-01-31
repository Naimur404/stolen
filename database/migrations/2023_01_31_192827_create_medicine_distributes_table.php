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
        Schema::create('medicine_distributes', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->bigInteger('warehouse_id')->unsigned();
            $table->foreign('warehouse_id')->references('id')->on('warehouses');
            $table->bigInteger('outlet_id')->unsigned();
            $table->foreign('outlet_id')->references('id')->on('outlets');
            $table->bigInteger('added_by')->unsigned()->nullable(); // Auth User ID
            $table->string('remarks')->nullable();
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
        Schema::dropIfExists('medicine_distributes');
    }
};
