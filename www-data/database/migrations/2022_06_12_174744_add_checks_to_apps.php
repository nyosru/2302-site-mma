<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        \Illuminate\Support\Facades\DB::unprepared("DROP TABLE IF EXISTS `apps`;");
        \Illuminate\Support\Facades\DB::unprepared("CREATE TABLE `apps` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `status` tinyint DEFAULT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `banner_url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `app_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `download_url` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `game_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `banned_partners_filter` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `banned_countries` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `banned_devices` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `banned_time` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `banned_time_end` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ban_by_tid` tinyint DEFAULT NULL,
  `allowed_countries` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `allowed_countries_filter` tinyint(1) DEFAULT NULL,
  `banned_devices_filter` tinyint(1) DEFAULT NULL,
  `banned_time_filter` tinyint(1) DEFAULT NULL,
  `banned_partners` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `dev_key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `af_dev_key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `unity_dev_key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `unity_campaign_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fb_access_token` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `country_by_ip` int DEFAULT NULL,
  `country_detection_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ban_if_no_country` tinyint(1) DEFAULT NULL,
  `suspect_devices_list` text COLLATE utf8mb4_unicode_ci,
  `ban_if_countries_not_matched` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `apps_app_id_unique` (`app_id`),
  KEY `name` (`name`),
  KEY `status` (`status`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('apps', function (Blueprint $table) {
            $table->dropColumn([
                'ban_if_countries_not_matched',
                'ban_if_no_country',
                'banned_partners_filter',
                'deleted_at',
            ]);
        });
    }
};
