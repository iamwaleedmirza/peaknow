<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldForStatus extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order_refund_histories', function (Blueprint $table) {
            $table->boolean('status')->default(1)->comment('0 = Failed, 1 = Success')->after('refill_number');
            $table->string('failure_reason')->nullable(true)->after('amount');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('order_refund_histories', function (Blueprint $table) {
            //
        });
    }
}
