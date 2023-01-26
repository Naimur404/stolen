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
        Schema::create('medicine_purchases', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('warehouse_id')->unsigned();
            $table->foreign('warehouse_id')->references('id')->on('warehouses');
            $table->bigInteger('supplier_id')->unsigned();
            $table->foreign('supplier_id')->references('id')->on('suppliers');
            $table->string('invoice_no')->nullable();
            $table->string('invoice_image')->nullable();
            $table->float('sub_total', 10,2)->default(0.00);
            $table->float('vat', 6,2)->default(0.00);
            $table->float('total_discount', 10,2)->default(0.00);
            $table->float('grand_total', 10,2)->default(0.00);
            $table->float('paid_amount', 10,2)->default(0.00);
            $table->float('due_amount', 10,2)->default(0.00);
            $table->date('purchase_date')->nullable();
            $table->text('purchase_details')->nullable();
            $table->bigInteger('payment_method_id')->unsigned();
            $table->foreign('payment_method_id')->references('id')->on('payment_methods');
            $table->bigInteger('added_by')->unsigned()->nullable(); // Auth User ID
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
        Schema::dropIfExists('medicine_purchases');
    }
};
