<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBelugaOrderDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('beluga_order_details', function (Blueprint $table) {
            $table->id();
            $table->string('order_no');
            $table->string('visitId')->nullable();
            $table->boolean('sent_to_beluga')->default(false);
            $table->boolean('is_consult_concluded')->default(false);
            $table->timestamp('consult_concluded_at')->nullable();
            $table->boolean('is_rx_written')->default(false);
            $table->timestamp('rx_written_at')->nullable();
            $table->ipAddress('beluga_ip')->nullable();
            $table->text('beluga_event_endpoint')->nullable();
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
        Schema::dropIfExists('beluga_order_details');
    }
}
