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
        //   'key_name',
        //        'name',
        //        'description',
        //        'parent_id',
        Schema::create('user_role_permissions', function (Blueprint $table) {
            $table->id();
            $table->string('key_name')->nullable();
            $table->string('description')->nullable();
            $table->bigInteger('parent_id')->nullable();
            $table->string('name', 1024)->nullable();
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
        Schema::dropIfExists('user_role_permissions');
    }
};
