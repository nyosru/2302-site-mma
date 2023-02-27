<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UnityFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('clients', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->string('ifa')->nullable();
            $table->string('ifa_md5')->nullable();
            $table->string('android_id_md5')->nullable();
            $table->string('country_code')->nullable();
            $table->string('campaign_id')->nullable();
            $table->string('campaign_name')->nullable();
            $table->bigInteger('game_id')->nullable();
            $table->bigInteger('source_game_id')->nullable();
            $table->string('os')->nullable();
            $table->string('device_type')->nullable();
            $table->string('creative_pack')->nullable();
            $table->string('creative_pack_id')->nullable();
            $table->string('language')->nullable();
            $table->string('user_agent')->nullable();

            $table->string('device_make')->nullable();
            $table->string('device_model')->nullable();
            $table->string('cpi')->nullable();
            $table->string('video_orientation')->nullable();
            $table->string('screen_size')->nullable();
            $table->string('screen_density')->nullable();
        });

        Schema::table('views', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->string('ifa')->nullable();
            $table->string('ifa_md5')->nullable();
            $table->string('android_id_md5')->nullable();
            $table->string('country_code')->nullable();
            $table->string('campaign_id')->nullable();
            $table->string('campaign_name')->nullable();
            $table->bigInteger('game_id')->nullable();
            $table->bigInteger('source_game_id')->nullable();
            $table->string('os')->nullable();
            $table->ipAddress('ip')->nullable();
            $table->string('device_type')->nullable();
            $table->string('creative_pack')->nullable();
            $table->string('creative_pack_id')->nullable();
            $table->string('language')->nullable();
            $table->string('user_agent')->nullable();

            $table->string('device_make')->nullable();
            $table->string('device_model')->nullable();
            $table->string('cpi')->nullable();
            $table->string('video_orientation')->nullable();
            $table->string('screen_size')->nullable();
            $table->string('screen_density')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
