<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeDatatypeOrderTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE orders MODIFY product_price DECIMAL (6,2) AFTER product_description");
        DB::statement("ALTER TABLE orders MODIFY shipping_cost DECIMAL (6,2) AFTER product_price");
        DB::statement("ALTER TABLE orders MODIFY plan_discount DECIMAL (6,2) AFTER shipping_cost");
        DB::statement("ALTER TABLE orders MODIFY product_total_price DECIMAL (6,2) AFTER plan_discount");
        DB::statement("ALTER TABLE orders MODIFY is_promo_active BOOLEAN AFTER product_total_price");
        DB::statement("ALTER TABLE orders MODIFY promo_code VARCHAR (50) AFTER is_promo_active");
        DB::statement("ALTER TABLE orders MODIFY promo_discount_percent DECIMAL (6,2) AFTER promo_code");
        DB::statement("ALTER TABLE orders MODIFY promo_discount DECIMAL (6,2) AFTER promo_discount_percent");
        DB::statement("ALTER TABLE orders MODIFY total_price DECIMAL (6,2) AFTER promo_discount");
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
