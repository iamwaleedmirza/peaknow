<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPromoFieldsToOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->double('product_price_after_discount')->after('product_price')->nullable();
            $table->boolean('is_promo_active')->after('total_price')->default(false);
            $table->string('promo_code')->after('is_promo_active')->nullable();
            $table->double('promo_discount_percent')->after('promo_code')->nullable();
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
            $table->dropColumn('product_price_after_discount');
            $table->dropColumn('is_promo_active');
            $table->dropColumn('promo_code');
            $table->dropColumn('promo_discount_percent');
        });
    }
}
