<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkerSubCategory extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'worker_category_id'
    ];
    public $timestamps = false;

    public function workerCategory()
    {
        return $this->belongsToOne('App\Models\WorkerCategory');
    }
}