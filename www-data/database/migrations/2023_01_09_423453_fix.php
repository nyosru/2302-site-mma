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
        DBWrap::getDB()::insert("INSERT INTO settings(`key`,`value`,`created_at`) VALUES ('timezone','3', NOW())");
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
