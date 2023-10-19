<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderSubscriptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_subscriptions', function (Blueprint $table) {
            $table->id();
            $table->string('order_no');
            $table->string('start_date')->nullable();
            $table->string('end_date')->nullable();
            $table->string('next_refill_date')->nullable();
            $table->boolean('is_paused')->default(false);
            $table->string('updated_by')->nullable();
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
        Schema::dropIfExists('order_subscriptions');
    }
}
