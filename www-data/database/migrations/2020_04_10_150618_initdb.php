<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Initdb extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('apps', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->increments('id');
            $table->tinyInteger('status')->nullable();
            $table->string('name');
            $table->string('url');
            $table->string('banner_url');
            $table->string('app_id')->unique();
            $table->timestamps();
        });

        Schema::create('clients', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->increments('id');
            $table->integer('app_id')->references('id')->on('app')->onDelete('cascade');
            $table->tinyInteger('status')->nullable();
            $table->string('tid');
            $table->ipAddress('ip');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('apps');
        Schema::dropIfExists('clients');
    }
}
