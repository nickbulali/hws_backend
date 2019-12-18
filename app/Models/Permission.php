<?php
namespace App\Models;

use Zizaco\Entrust\EntrustPermission;
class Permission extends EntrustPermission
{
    protected $fillable = ['name', 'display_name'];

    public function roles()
    {
        return $this->belongsToMany('App\Models\Role');
    }

    public function permissionRole()
    {
        return $this->hasMany('App\Models\PermissionRole');
    }

    /*
     * Checks if the Permission has a Role by its name.
     *
     * @param string $name Role name.
     *
     * @return bool
     */
    public function hasRole($name)
    {
        foreach ($this->roles as $role) {
            if ($role->name == $name) {
                return true;
            }
        }

        return false;
    }
}
