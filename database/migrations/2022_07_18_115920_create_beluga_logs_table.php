<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBelugaLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('beluga_logs', function (Blueprint $table) {
            $table->id();
            $table->string('master_id')->nullable();
            $table->string('request_sent_from')->nullable();
            $table->string('request_sent_to')->nullable();
            $table->json('event_response')->nullable();
            $table->string('ip_address')->nullable();
            $table->text('endpoint_url')->nullable();
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
        Schema::dropIfExists('beluga_logs');
    }
}
