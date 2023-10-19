<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeDatatypeOrderRefillTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE order_refills MODIFY amount DECIMAL (6,2) NOT NULL DEFAULT 0 AFTER transaction_type");
        DB::statement("ALTER TABLE order_refills MODIFY shipping_cost DECIMAL (6,2) NOT NULL DEFAULT 0 AFTER amount");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('order_refills', function (Blueprint $table) {
            //
        });
    }
}
