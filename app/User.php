<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Laravel\Passport\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;
use Zizaco\Entrust\Traits\EntrustUserTrait;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements MustVerifyEmail
{
    //use SoftDeletes;
    use HasApiTokens, Notifiable, EntrustUserTrait;

    protected $dates = ['deleted_at'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name', 'last_name', 'phone_no', 'user_uuid', 'email', 'password', 'active', 'activation_token'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'activation_token', 'user_uuid', 'id', 'created_at', 'updated_at', 'deleted_at'
    ];

    public function roles()
    {
        return $this->belongsToMany('App\Models\Role');
    }
    public function healthWorkerProfile()
    {
        return $this->hasOne('App\Models\WorkerProfile', 'user_uuid', 'user_uuid');
    }

}
