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
   //     'key',
        //        'allows',
        //        'name',
        //        'description'
        Schema::create('user_roles', function (Blueprint $table) {
            $table->id();
            $table->string('key')->nullable();
            $table->string('allows')->nullable();
            $table->string('description')->nullable();
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
