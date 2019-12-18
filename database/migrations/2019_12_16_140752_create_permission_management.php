<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePermissionManagement extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Eloquent::unguard();
        $healthWorkerRole = \App\Models\Role::create(['name' => 'Health Worker']);
        $hospitalRole = \App\Models\Role::create(['name' => 'Hospital']);
        $individualRole = \App\Models\Role::create(['name' => 'Private Individual']);

        $individualPermissions = [
            //request services
            ['name' => 'individual_request_service', 'display_name' => 'Can Request Service'],
            //individual profile
            ['name' => 'individual_profile', 'display_name' => 'Can Setup Individual Profile'],
        ];
        $hospitalPermissions = [
            //request services
            ['name' => 'hospital_request_service', 'display_name' => 'Can Request Service'],
            //hospital profile
            ['name' => 'hospital_profile', 'display_name' => 'Can Setup Hospital Profile'],
        ];
        $healthWorkerPermissions = [
            //receive service request
            ['name' => 'receive_service', 'display_name' => 'Can Receive Service Request'],
            //health worker profile
            ['name' => 'health_worker_profile', 'display_name' => 'Can Setup Health Worker Profile'],
        ];

        foreach ($individualPermissions as $permission) {
            \App\Models\Permission::create($permission);
        }
        foreach ($hospitalPermissions as $permission) {
            \App\Models\Permission::create($permission);
        }
        foreach ($healthWorkerPermissions as $permission) {
            \App\Models\Permission::create($permission);
        }

        $individualPermissions = \App\Models\Permission::whereId(1)->orWhere('id',2)->get();
        //Assign all individualPermissions to role individualRole
        foreach ($individualPermissions as $permission) {
            $individualRole->attachPermission($permission);
        }
        $hospitalPermissions = \App\Models\Permission::whereId(3)->orWhere('id',4)->get();
        //Assign all hospitalPermissions to role hospitalRole
        foreach ($hospitalPermissions as $permission) {
            $hospitalRole->attachPermission($permission);
        }
        $healthWorkerPermissions = \App\Models\Permission::whereId(5)->orWhere('id',6)->get();
        //Assign all healthWorkerPermissions to role healthWorkerRole
        foreach ($healthWorkerPermissions as $permission) {
            $healthWorkerRole->attachPermission($permission);
        }


        Eloquent::reguard();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //Schema::dropIfExists('permission_management');
    }
}
