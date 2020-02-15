<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkerPackage extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'worker_category_id', 'amount'
    ];

    public function workerCategory()
    {
        return $this->hasOne('App\Models\WorkerCategory', 'id', 'worker_category_id');
    }

    public $timestamps = false;
}