<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAddressFieldsToOrderRefillsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order_refills', function (Blueprint $table) {
            $table->text('shipping_fullname')->after('shipping_cost')->nullable();
            $table->text('shipping_address_line')->after('shipping_fullname')->nullable();
            $table->text('shipping_address_line2')->after('shipping_address_line')->nullable();
            $table->text('shipping_city')->after('shipping_address_line2')->nullable();
            $table->text('shipping_zipcode')->after('shipping_city')->nullable();
            $table->text('shipping_state')->after('shipping_zipcode')->nullable();
            $table->text('shipping_phone')->after('shipping_state')->nullable();
        });
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
