<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderRefundHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_refund_histories', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('order_no')->nullable();
            $table->string('refill_number')->nullable();
            $table->string('transaction_id')->nullable();
            $table->string('amount')->nullable();
            $table->integer('subscription_paynum')->nullable();
            $table->boolean('is_auto_refunded')->default(0)->nullable();
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
        Schema::dropIfExists('order_refund_histories');
    }
}
