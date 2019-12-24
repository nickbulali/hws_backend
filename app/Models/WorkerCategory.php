<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkerCategory extends Model
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

    public function workerSubCategory()
    {
        return $this->hasMany('App\Models\WorkerSubCategory');
    }
}