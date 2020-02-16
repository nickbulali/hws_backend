<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HospitalList extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'requester_uuid', 'recepient_uuid', 'longitude', 'latitude', 'from_date', 'to_date', 'from', 'to', 'categiry_id', 'status_id'
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
    public function healthWorkerProfile()
    {
        return $this->hasOne('App\Models\WorkerProfile', 'user_uuid', 'recepient_uuid');
    }

    protected $table = 'hospital_lists';

}