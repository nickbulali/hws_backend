<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserRequest extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'requester_uuid', 'recepient_uuid', 'longitude', 'latitude', 'from', 'to', 'categiry_id', 'status_id', 'from_date', 'to_date'
    ];

    protected $hidden = [
        'requester_uuid', 'recepient_uuid'
    ];


    public function requester()
    {
        return $this->hasOne('App\User', 'user_uuid', 'requester_uuid');
    }
    public function recipient()
    {
        return $this->hasOne('App\User', 'user_uuid', 'recepient_uuid');
    }
    public function facility()
    {
        return $this->hasOne('App\Models\Facility', 'user_id', 'id');
    }
   
    public function status()
    {
        return $this->hasOne('App\Models\Statuses', 'id', 'status_id');
    }
}