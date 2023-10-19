<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldForIsNewOrders extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('plan_name')->after('product_name');
            $table->string('medicine_variant')->after('plan_name');
            $table->string('plan_title')->after('medicine_variant');
            $table->string('plan_image')->after('plan_title');
            $table->string('strength',4)->after('plan_image');
            $table->string('plan_discount')->after('product_price');
            $table->removeColumn('plan_detail');
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
            //
        });
    }
}
