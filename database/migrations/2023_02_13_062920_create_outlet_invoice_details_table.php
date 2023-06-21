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
        Schema::create('outlet_invoice_details', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('outlet_invoice_id')->unsigned();
            $table->foreign('outlet_invoice_id')->references('id')->on('outlet_invoices');
            $table->bigInteger('medicine_id')->unsigned();
            $table->foreign('medicine_id')->references('id')->on('medicines');
            $table->string('medicine_name')->nullable();
            $table->string('size')->nullable();
            $table->timestamp('create_date')->nullable();
            $table->integer('available_qty')->default(1)->unsigned();
            $table->integer('quantity')->default(1);
            $table->float('rate', 8,2)->default(0.00);
            $table->float('discount', 8,2)->default(0.00);
            $table->float('total_price', 10,2)->default(0.00);
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
        Schema::dropIfExists('outlet_invoice_details');
    }
};
