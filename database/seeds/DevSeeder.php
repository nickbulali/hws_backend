<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DevSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $individualRole = \App\Models\Role::find(3);
        $hospitalRole = \App\Models\Role::find(2);
        $workerRole = App\Models\Role::find(1);

        $individualUser = \App\User::create([
            'user_uuid' => Str::uuid(),
            'first_name' => 'Individual',
            'last_name' => 'Account',
            'email' => 'individual@hrh.local',
            'phone_no' => '0711 000 111',
            'password'=>'$2y$10$ClS05CwMFgK7dxtj4gma3OddLThoskmVJqMVTSX3KwMfMOzgIMpv2',
            'active'=>1
        ]);
        $individualUser->attachRole($individualRole);

        $hospitalUser = \App\User::create([
            'user_uuid' => Str::uuid(),
            'first_name' => 'Hospital',
            'last_name' => 'Account',
            'email' => 'hospital@hrh.local',
            'phone_no' => '0711 991 999',
            'password'=>'$2y$10$ClS05CwMFgK7dxtj4gma3OddLThoskmVJqMVTSX3KwMfMOzgIMpv2',
            'active'=>1
        ]);
        $hospitalUser->attachRole($hospitalRole);

        $workerUser = \App\User::create([
            'user_uuid' => Str::uuid(),
            'first_name' => 'Worker',
            'last_name' => 'Account',
            'email' => 'worker@hrh.local',
            'phone_no' => '0711 999 000',
            'password'=>'$2y$10$ClS05CwMFgK7dxtj4gma3OddLThoskmVJqMVTSX3KwMfMOzgIMpv2',
            'active'=>1
        ]);
        $workerUser->attachRole($workerRole);
    }
}