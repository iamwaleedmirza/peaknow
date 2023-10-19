<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddFieldsToGeneralSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('general_settings', function (Blueprint $table) {
            $table->binary('telehealth_consent_page')->nullable()->after('cookie_policy_page');
            $table->binary('refund_policy_page')->nullable()->after('telehealth_consent_page');
        });

        DB::statement("ALTER TABLE general_settings CHANGE telehealth_consent_page telehealth_consent_page LONGBLOB NULL DEFAULT NULL;");
        DB::statement("ALTER TABLE general_settings CHANGE refund_policy_page refund_policy_page LONGBLOB NULL DEFAULT NULL;");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('general_settings', function (Blueprint $table) {
            $table->dropColumn('telehealth_consent_page');
            $table->dropColumn('refund_policy_page');
        });
    }
}
