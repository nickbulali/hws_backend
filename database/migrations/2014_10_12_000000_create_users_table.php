<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->uuid('user_uuid')->unique();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->nullable()->unique();
            $table->string('phone_no')->nullable()->unique();
            $table->boolean('active')->default(false);
            $table->string('activation_token');
            $table->string('image')->default('default.png');
            $table->string('password')->nullable();
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
