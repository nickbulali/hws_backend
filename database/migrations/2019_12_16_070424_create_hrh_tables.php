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
            $table->string('user_uuid');
            $table->string('profile_pic')->nullable();

            $table->timestamps();

            $table->foreign('user_uuid')->references('user_uuid')->on('users');
        });

        Schema::create('hospital_lists', function (Blueprint $table) {
            $table->increments('id');
            $table->string('requester_uuid');
            $table->string('recepient_uuid');
            $table->string('longitude');
            $table->string('latitude');
            $table->string('from_date');
            $table->string('to_date');
            $table->string('from');
            $table->string('to');
            $table->unsignedInteger('categiry_id');

            $table->timestamps();

            $table->foreign('requester_uuid')->references('user_uuid')->on('users');
            $table->foreign('recepient_uuid')->references('user_uuid')->on('users');
        });

        Schema::create('genders', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
        });

        Schema::create('worker_categories', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
        });

        Schema::create('worker_packages', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('worker_category_id');
            $table->integer('amount');

            $table->foreign('worker_category_id')->references('id')->on('worker_categories');
        });

        Schema::create('worker_sub_categories', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('worker_category_id');
            $table->string('name');

            $table->foreign('worker_category_id')->references('id')->on('worker_categories');
        });

        Schema::create('worker_profiles', function (Blueprint $table) {
            $table->increments('id');
            $table->string('user_uuid');
            $table->string('bio');
            $table->unsignedInteger('gender_id')->nullable();
            $table->string('id_number')->nullable();
            $table->unsignedInteger('worker_category_id');
            $table->unsignedInteger('worker_sub_category_id');
            $table->string('licence_number')->nullable();
            $table->date('date_licence_renewal')->nullable();
            $table->string('qualification')->nullable();
            $table->string('specialization')->nullable();
            $table->string('residence')->nullable();
            $table->string('experience_years')->nullable();
            $table->string('profile_pic')->nullable();
            $table->string('verified')->default(0);
            $table->string('active')->default(1);

            $table->timestamps();

            $table->foreign('worker_category_id')->references('id')->on('worker_categories');
            $table->foreign('worker_sub_category_id')->references('id')->on('worker_sub_categories');
            $table->foreign('user_uuid')->references('user_uuid')->on('users');
            $table->foreign('gender_id')->references('id')->on('genders');
        });

        Schema::create('statuses', function (Blueprint $table) {
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
            $table->string('requester_uuid');
            $table->string('recepient_uuid');
            $table->string('longitude');
            $table->string('latitude');
            $table->string('from_date');
            $table->string('from');
            $table->string('to_date');
            $table->string('to');
            $table->unsignedInteger('categiry_id');
            $table->unsignedInteger('status_id');

            $table->timestamps();

            $table->foreign('requester_uuid')->references('user_uuid')->on('users');
            $table->foreign('recepient_uuid')->references('user_uuid')->on('users');
            $table->foreign('status_id')->references('id')->on('statuses');
        });

        Schema::create('user_requests_durations', function (Blueprint $table) {
            $table->increments('id');
            $table->string('start_time');
            $table->string('end_time')->nullable();
            $table->unsignedInteger('user_request_id');
            $table->integer('bill')->nullable();
            $table->integer('status')->default(0);

            $table->timestamps();

            $table->foreign('user_request_id')->references('id')->on('user_requests');
        });

        Schema::create('user_ratings', function (Blueprint $table) {
            $table->increments('id');
            $table->string('client_uuid');
            $table->string('worker_uuid');
            $table->integer('rating')->nullable();
            $table->string('comment')->nullable();

            $table->timestamps();

            $table->foreign('client_uuid')->references('user_uuid')->on('users');
            $table->foreign('worker_uuid')->references('user_uuid')->on('users');
        });

        Schema::create('user_favourites', function (Blueprint $table) {
            $table->increments('id');
            $table->string('client_uuid');
            $table->string('worker_uuid');

            $table->timestamps();

            $table->foreign('client_uuid')->references('user_uuid')->on('users');
            $table->foreign('worker_uuid')->references('user_uuid')->on('users');
        });

        Schema::create('request_confirmations', function (Blueprint $table) {
            $table->increments('id');
            $table->string('requester_uuid');
            $table->string('recepient_uuid');
            $table->string('longitude');
            $table->string('latitude');
            $table->string('from');
            $table->string('to');
            $table->unsignedInteger('categiry_id');
            $table->unsignedInteger('status_id');

            $table->timestamps();

            $table->foreign('requester_uuid')->references('user_uuid')->on('users');
            $table->foreign('recepient_uuid')->references('user_uuid')->on('users');
            $table->foreign('status_id')->references('id')->on('statuses');
        });
        Schema::create('user_devices', function (Blueprint $table) {
            $table->increments('id');
            $table->string('user_uuid');
            $table->string('firebase_token')->nullable();
            $table->string('longitude')->nullable();
            $table->string('latitude')->nullable();

            $table->timestamps();

            $table->foreign('user_uuid')->references('user_uuid')->on('users');
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
        Schema::drop('statuses');
        Schema::drop('user_requests');
        Schema::drop('request_confirmations');
        Schema::drop('notifications');
        Schema::drop('facility');
        Schema::drop('workers_profile');
        
    }
}
