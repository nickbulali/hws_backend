<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHrhTables extends Migration
{
  public function up()
    {
       Schema::create('profiles', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->string('fname')->nullable();
            $table->string('lname')->nullable();
            $table->string('phone_number')->nullable();
            $table->string('profile_pic')->nullable();

            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
        });


         Schema::create('workers_profile', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->string('fname')->nullable();
            $table->string('lname')->nullable();
            $table->string('gender')->nullable();
            $table->string('id_number')->nullable();
            $table->string('phone_number')->nullable();
            $table->string('proffesion')->nullable();
            $table->string('licence_number')->nullable();
            $table->string('date_licence_renewal')->nullable();
            $table->string('qualification')->nullable();
            $table->string('specialization')->nullable();
            $table->string('residence')->nullable();
            $table->string('experience_years')->nullable();
            $table->string('profile_pic')->nullable();

            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
        });


        Schema::create('categories', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
        });
        Schema::create('status', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
        });
             Schema::create('facility_level', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
        });
        Schema::create('facilities', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->string('name');
            $table->string('type');
            $table->unsignedInteger('level_id');
            $table->string('license_number')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
             $table->foreign('level_id')->references('id')->on('facility_level');
        });
    
        Schema::create('user_requests', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('status_id');
            $table->integer('comment')->nullable();
           
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('status_id')->references('id')->on('status');
        });
        Schema::create('request_confirmations', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('status_id');

            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('status_id')->references('id')->on('status');
        });
        Schema::create('user_devices', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->string('token');

            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
        });
        Schema::create('notifications', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('type');
            $table->morphs('notifiable');
            $table->text('data');
            $table->timestamp('read_at')->nullable();
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
        Schema::drop('categories');
        Schema::drop('status');
        Schema::drop('user_requests');
        Schema::drop('request_confirmations');
        Schema::drop('notifications');
        Schema::drop('facility');
        Schema::drop('workers_profile');
        
    }
}
