<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePeaksPromoCodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('peaks_promo_codes', function (Blueprint $table) {
            $table->id();
            $table->string('promo_name');
            $table->text('plan_id');
            $table->string('promo_type');
            $table->double('promo_value');
            $table->boolean('promo_status')->default('1');
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
        Schema::dropIfExists('peaks_promo_codes');
    }
}
