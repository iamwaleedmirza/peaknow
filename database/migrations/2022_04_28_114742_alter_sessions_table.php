<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class AlterSessionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
//        Schema::table('sessions', function (Blueprint $table) {
//            $table->timestamp('created_at')->useCurrent()->change();
//        });
        DB::statement('ALTER TABLE sessions MODIFY COLUMN created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('ALTER TABLE sessions MODIFY COLUMN created_at TIMESTAMP NULL DEFAULT NULL');
    }
}
