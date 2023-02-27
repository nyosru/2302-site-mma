<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class BannedAttributions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('apps', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->text('banned_devices')->nullable();
            $table->string('banned_time')->nullable();
            $table->string('banned_time_end')->nullable();
            $table->tinyInteger('ban_by_tid')->nullable();

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
