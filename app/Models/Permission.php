<?php

namespace App\Models;

use Zizaco\Entrust\EntrustRole;

class Role extends EntrustRole
{
    /**
     * Function for getting the admin role, currently the first user.
     */
    public static function getAdminRole()
    {
        return Role::find(1);
    }

    public function permissionRole()
    {
        return $this->hasMany('App\Models\PermissionRole');
    }

    public function permissions()
    {
        return $this->belongsToMany('App\Models\Permission');
    }
}
