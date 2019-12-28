<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSystemData extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Eloquent::unguard();
        $male = \App\Models\Gender::create(['name' => 'Male']);
        $female = \App\Models\Gender::create(['name' => 'Female']);

        \App\Models\Statuses::create(['name' => 'Pending']);
        \App\Models\Statuses::create(['name' => 'Active']);
        \App\Models\Statuses::create(['name' => 'Completed']);
        \App\Models\Statuses::create(['name' => 'Cancelled']);

        $doctor = \App\Models\WorkerCategory::create(['name' => 'Doctor']);
        $nurse = \App\Models\WorkerCategory::create(['name' => 'Nurse']);
        $clinicalOfficer = \App\Models\WorkerCategory::create(['name' => 'Clinical Officer (CO)']);
        $pharmacist = \App\Models\WorkerCategory::create(['name' => 'Pharmacist']);

        \App\Models\WorkerSubCategory::create([
            'worker_category_id' => $doctor->id,
            'name' => 'Medical Officer'
        ]);
        \App\Models\WorkerSubCategory::create([
            'worker_category_id' => $nurse->id,
            'name' => 'Theatre Nurse'
        ]);
        \App\Models\WorkerSubCategory::create([
            'worker_category_id' => $nurse->id,
            'name' => 'Midwife'
        ]);
        \App\Models\WorkerSubCategory::create([
            'worker_category_id' => $nurse->id,
            'name' => 'General Nurse'
        ]);
        \App\Models\WorkerSubCategory::create([
            'worker_category_id' => $nurse->id,
            'name' => 'Hospice Nurse'
        ]);
        \App\Models\WorkerSubCategory::create([
            'worker_category_id' => $nurse->id,
            'name' => 'Psychiatric Nurse'
        ]);
        \App\Models\WorkerSubCategory::create([
            'worker_category_id' => $nurse->id,
            'name' => 'Palliative Care Nurse'
        ]);
        \App\Models\WorkerSubCategory::create([
            'worker_category_id' => $nurse->id,
            'name' => 'Pediatric Nurse'
        ]);
        \App\Models\WorkerSubCategory::create([
            'worker_category_id' => $nurse->id,
            'name' => 'Intensive Care Nurse'
        ]);
        \App\Models\WorkerSubCategory::create([
            'worker_category_id' => $clinicalOfficer->id,
            'name' => 'CO Anaesthetist'
        ]);
        \App\Models\WorkerSubCategory::create([
            'worker_category_id' => $clinicalOfficer->id,
            'name' => 'CO Paediatrics'
        ]);
        \App\Models\WorkerSubCategory::create([
            'worker_category_id' => $clinicalOfficer->id,
            'name' => 'General Clinician'
        ]);
        \App\Models\WorkerSubCategory::create([
            'worker_category_id' => $pharmacist->id,
            'name' => 'Pharmacist'
        ]);
        \App\Models\WorkerSubCategory::create([
            'worker_category_id' => $pharmacist->id,
            'name' => 'Pharm Tech'
        ]);

        Eloquent::reguard();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //Schema::dropIfExists('system_data');
    }
}
