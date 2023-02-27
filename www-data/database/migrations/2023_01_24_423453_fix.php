<?php

use Illuminate\Database\Migrations\Migration;
use SoftInvest\Helpers\DBWrap;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DBWrap::getDB()::insert("INSERT INTO settings(`key`,`value`,`created_at`) VALUES ('banned_countries','', NOW())");
        DBWrap::getDB()::insert("INSERT INTO settings(`key`,`value`,`created_at`) VALUES ('banned_devices','', NOW())");
        DBWrap::getDB()::insert("INSERT INTO settings(`key`,`value`,`created_at`) VALUES ('banned_ip','', NOW())");
        DBWrap::getDB()::insert("INSERT INTO settings(`key`,`value`,`created_at`) VALUES ('trusted_devices','', NOW())");
        DBWrap::getDB()::insert("INSERT INTO settings(`key`,`value`,`created_at`) VALUES ('suspect_devices','', NOW())");
        DBWrap::getDB()::insert("INSERT INTO settings(`key`,`value`,`created_at`) VALUES ('trusted_ips','', NOW())");

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
    }
};
