<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserRating extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'client_uuid', 'worker_uuid', 'rating', 'comment'
    ];

    protected $hidden = [
        'client_uuid', 'client_uuid'
    ];


    public function client()
    {
        return $this->hasOne('App\User', 'user_uuid', 'client_uuid');
    }
    public function worker()
    {
        return $this->hasOne('App\User', 'user_uuid', 'worker_uuid');
    }
}