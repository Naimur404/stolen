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
        Schema::create('sales_returns', function (Blueprint $table) {
            $table->id();
            $table->date('return_date');
            $table->bigInteger('outlet_id')->unsigned();
            $table->foreign('outlet_id')->references('id')->on('outlets');
            $table->bigInteger('customer_id')->unsigned()->nullable();
            $table->foreign('customer_id')->references('id')->on('customers');
            $table->bigInteger('invoice_id')->unsigned()->nullable();
            $table->foreign('invoice_id')->references('id')->on('outlet_invoices');
            $table->float('sub_total', 10,2)->default(0.00);
            $table->float('deduct_amount', 10,2)->default(0.00);
            $table->float('grand_total', 10,2)->default(0.00);
            $table->float('paid_amount', 10,2)->default(0.00);
            $table->float('due_amount', 10,2)->default(0.00);
            $table->float('deduct_point', 10,2)->default(0.00);
            $table->bigInteger('payment_method_id')->unsigned();
            $table->foreign('payment_method_id')->references('id')->on('payment_methods');
            $table->bigInteger('added_by')->unsigned()->nullable(); // Auth User ID
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
        Schema::dropIfExists('sales_returns');
    }
};
