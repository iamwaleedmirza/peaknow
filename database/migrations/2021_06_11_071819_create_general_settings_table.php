<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateGeneralSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('general_settings', function (Blueprint $table) {
            $table->id();
            $table->string('site_title')->nullable();
            $table->string('site_logo_light')->nullable();
            $table->string('site_logo_dark')->nullable();
            $table->string('site_favicon')->nullable();
            $table->string('footer_text')->nullable();
            $table->string('facebook_link')->nullable();
            $table->string('twitter_link')->nullable();
            $table->string('instagram_link')->nullable();
            $table->string('support_mail')->nullable();
            $table->binary('privacy_policy_page')->nullable();
            $table->binary('terms_condition_page')->nullable();
            $table->binary('cookie_policy_page')->nullable();
            $table->string('allowed_states')->nullable();
            $table->timestamps();
        });

        DB::statement("ALTER TABLE general_settings CHANGE privacy_policy_page privacy_policy_page LONGBLOB NULL DEFAULT NULL;");
        DB::statement("ALTER TABLE general_settings CHANGE terms_condition_page terms_condition_page LONGBLOB NULL DEFAULT NULL;");
        DB::statement("ALTER TABLE general_settings CHANGE cookie_policy_page cookie_policy_page LONGBLOB NULL DEFAULT NULL;");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('general_settings');
    }
}
