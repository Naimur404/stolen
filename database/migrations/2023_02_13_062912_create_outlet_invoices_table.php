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
        Schema::create('outlet_invoices', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('outlet_id')->unsigned();
            $table->foreign('outlet_id')->references('id')->on('outlets');
            $table->bigInteger('customer_id')->unsigned()->nullable();
            $table->foreign('customer_id')->references('id')->on('customers');
            $table->date('sale_date');
            $table->float('sub_total', 10,2)->default(0.00);
            $table->float('vat', 6,2)->default(0.00);
            $table->float('total_discount', 10,2)->default(0.00);
            $table->float('grand_total', 10,2)->default(0.00);
            $table->float('paid_amount', 10,2)->default(0.00);
            $table->float('due_amount', 10,2)->default(0.00);
            $table->float('earn_point', 10,2)->default(0.00);
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
        Schema::dropIfExists('outlet_invoices');
    }
};
