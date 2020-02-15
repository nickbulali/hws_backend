<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserRequestsDuration extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'start_time', 'end_time', 'user_request_id', 'bill'
    ];

    public function userRequest()
    {
        return $this->hasOne('App\UserRequest', 'id', 'user_request_id');
    }
}