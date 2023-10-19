<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangePromocodeTableField extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('peaks_promo_codes', function (Blueprint $table) {
            $table->uuid('product_id')->nullable(true)->after('promo_type');
            $table->uuid('plan_type_id')->nullable(true)->after('product_id');
            $table->uuid('medicine_variant_id')->nullable(true)->after('plan_type_id');
            $table->boolean('promo_type')->default(0)->comment('0 = Applicable for all plans, 1 = Selected Plan')->change();
            $table->decimal('promo_value',6,2)->default(0)->change();
            $table->dropColumn('plan_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('peaks_promo_codes', function (Blueprint $table) {
            //
        });
    }
}
