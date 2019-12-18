<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderStatuses extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name'
    ];
    public $timestamps = false;
}