<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderRefillsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_refills', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('order_no');
            $table->integer('refill_number');
            $table->string('refill_status')->default('Pending');
            $table->date('refill_date')->nullable();
            $table->string('subscription_paynum', 10)->nullable();
            $table->string('transaction_id')->nullable();
            $table->string('transaction_type')->nullable();
            $table->string('amount')->nullable();
            $table->string('invoice')->nullable();
            $table->bigInteger('invoice_no')->nullable();
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
        Schema::dropIfExists('order_refills');
    }
}
