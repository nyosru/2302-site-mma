<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class BannedIdsAndPartners extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('banned_ids', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->increments('id');
            $table->integer('app_id')->references('id')->on('app')->onDelete('cascade');
            $table->string('client_bid');
            $table->timestamps();
        });

        Schema::table('apps', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->text('banned_partners')->nullable();

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
