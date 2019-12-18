<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RequestConfirmations extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',  'status_id'
    ];

    public function user()
    {
        return $this->hasOne('App\User', 'id', 'user_id');
    }
    public function status()
    {
        return $this->hasOne('App\Models\Statuses', 'id', 'status_id');
    }
}