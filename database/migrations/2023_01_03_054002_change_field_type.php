<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeFieldType extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('new_plans', function (Blueprint $table) {
            $table->uuid('product_name')->change();
            $table->uuid('plan_type')->comment('0 = One Time,1 = Subscription')->change();
            $table->uuid('medicine_varient')->change();
            $table->renameColumn('product_name', 'product_id');
            $table->renameColumn('plan_type', 'plan_type_id');
            $table->renameColumn('medicine_varient', 'medicine_varient_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('new_plans', function (Blueprint $table) {
            //
        });
    }
}
