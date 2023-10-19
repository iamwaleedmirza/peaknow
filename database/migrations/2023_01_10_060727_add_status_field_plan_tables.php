<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStatusFieldPlanTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('new_plans', function (Blueprint $table) {
            $table->dropColumn('deleted_at');
            $table->boolean('is_active')->default(0)->after('category_plan2');
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
