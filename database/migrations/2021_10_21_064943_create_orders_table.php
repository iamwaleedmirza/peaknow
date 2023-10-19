<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_no')->unique();
            $table->integer('user_id');
            $table->integer('question_ans_id');
            $table->integer('subscription_plan_id')->nullable();
            $table->integer('plan_id')->nullable();
            $table->string('product_image')->nullable();
            $table->string('product_name')->nullable();
            $table->text('product_description')->nullable();
            $table->string('product_price');
            $table->string('promo_discount')->nullable();
            $table->string('total_price');
            $table->boolean('is_refill')->default(0);
            $table->string('status', 100)->default('Pending');
            $table->integer('is_refunded')->nullable()->default(0);
            $table->string('cancel_reason')->nullable();
            $table->string('doctor_name', 100)->nullable();
            $table->string('doctor_response')->nullable();
            $table->string('payment_status', 60)->default('Unpaid');
            $table->string('transaction_id')->nullable();
            $table->string('payment_method', 100)->nullable();
            $table->text('shipping_address')->nullable();
            $table->string('invoice')->nullable();
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
        Schema::dropIfExists('orders');
    }
}
