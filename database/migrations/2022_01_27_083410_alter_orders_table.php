<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->text('shipping_fullname')->after('payment_method')->nullable();
            $table->text('shipping_address_line')->after('shipping_fullname')->nullable();
            $table->text('shipping_city')->after('shipping_address_line')->nullable();
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
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('shipping_fullname');
            $table->dropColumn('shipping_address_line');
            $table->dropColumn('shipping_city');
            $table->dropColumn('shipping_zipcode');
            $table->dropColumn('shipping_state');
            $table->dropColumn('shipping_phone');
        });
    }
}
