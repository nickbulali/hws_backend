<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkerProfile extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_uuid', 'bio', 'gender_id', 'id_number', 'worker_category_id', 'worker_sub_category_id', 'licence_number', 'date_licence_renewal', 'qualification', 'specialization', 'residence', 'experience_years', 'profile_pic','status_id'
    ];

    protected $hidden = [
        'user_uuid', 'id'
    ];

    public function workerCategory()
    {
        return $this->hasOne('App\Models\WorkerCategory', 'id', 'worker_category_id');
    }

    public function workerSubCategory()
    {
        return $this->hasOne('App\Models\WorkerSubCategory', 'id', 'worker_sub_category_id');
    }

       
    public function status()
    {
        return $this->hasOne('App\Models\Statuses', 'id', 'status_id');
    }
}