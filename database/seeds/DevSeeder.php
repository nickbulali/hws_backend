<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DevSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $individualRole = \App\Models\Role::find(3);
        $hospitalRole = \App\Models\Role::find(2);
        $workerRole = App\Models\Role::find(1);

        \App\User::truncate();
        \App\Models\WorkerProfile::truncate();
        $individualUser = \App\User::create([
            'user_uuid' => Str::uuid(),
            'first_name' => 'Individual',
            'last_name' => 'Account',
            'email' => 'individual@hrh.local',
            'phone_no' => '0711 000 111',
            'password'=>'$2y$10$ClS05CwMFgK7dxtj4gma3OddLThoskmVJqMVTSX3KwMfMOzgIMpv2',
            'active'=>1
        ]);
        $individualUser->attachRole($individualRole);

        $hospitalUser = \App\User::create([
            'user_uuid' => Str::uuid(),
            'first_name' => 'Hospital',
            'last_name' => 'Account',
            'email' => 'hospital@hrh.local',
            'phone_no' => '0711 991 999',
            'password'=>'$2y$10$ClS05CwMFgK7dxtj4gma3OddLThoskmVJqMVTSX3KwMfMOzgIMpv2',
            'active'=>1
        ]);
        $hospitalUser->attachRole($hospitalRole);

        $workerUser = \App\User::create([
            'user_uuid' => Str::uuid(),
            'first_name' => 'Worker',
            'last_name' => 'Account',
            'email' => 'worker@hrh.local',
            'phone_no' => '0711 999 000',
            'password'=>'$2y$10$ClS05CwMFgK7dxtj4gma3OddLThoskmVJqMVTSX3KwMfMOzgIMpv2',
            'active'=>1
        ]);
        $workerUser->attachRole($workerRole);
        \App\Models\WorkerProfile::create([
            'user_uuid' => $workerUser->user_uuid,
            'bio' => 'XX DD, MD, MPH, is the Robert P. Kelch, MD Research Professor of Pediatrics at the University of Nairobi Medical School and Professor at the Medical School and at the University of Nairobi School of Public Health. She serves as the Associate Chief Medical Information Officer for Pediatric Research at Nairobi Medicine and as Associate Chair for Health Metrics and Learning Health Systems for the Department of Pediatrics.',
            'gender_id' => 2,
            'id_number' => 21222094,
            'worker_category_id' => 1,
            'worker_sub_category_id' => 1,
            'licence_number' => 55454,
            'date_licence_renewal' => '2019-12-24',
            'qualification' => 'MD, MPH',
            'specialization' => 'Surgery',
            'residence' => 'Nairobi, Kenya',
            'experience_years' => 10,

        ]);
        \App\Models\UserDevice::create([
            'user_uuid' => $workerUser->user_uuid,
            'firebase_token' => 'ewksdbqD_Pg:APA91bF3jlY3vbHOz95FMIIRG_3oUX9fzs2RO8d-tglf3jQZB-cbqxUge7eJM82QnwMfNT4SrFvu0IMwGP3wUcwHW1kVeETQZpCiKCwH7O-ydY4UefWeHmtmfW17EZ-KVbP4Rsn1Ghet',
            'longitude' => '36.8001948',
            'latitude' => '-1.311504'
        ]);

        $nurseWorkerUser = \App\User::create([
            'user_uuid' => Str::uuid(),
            'first_name' => 'Cynthia',
            'last_name' => 'Akinyi',
            'email' => 'cynthia@hrh.local',
            'phone_no' => '0721 999 000',
            'password'=>'$2y$10$ClS05CwMFgK7dxtj4gma3OddLThoskmVJqMVTSX3KwMfMOzgIMpv2',
            'active'=>1
        ]);
        $nurseWorkerUser->attachRole($workerRole);
        \App\Models\WorkerProfile::create([
            'user_uuid' => $nurseWorkerUser->user_uuid,
            'bio' => 'XX DD, MD, MPH, is the Robert P. Kelch, MD Research Professor of Pediatrics at the University of Nairobi Medical School and Professor at the Medical School and at the University of Nairobi School of Public Health. She serves as the Associate Chief Medical Information Officer for Pediatric Research at Nairobi Medicine and as Associate Chair for Health Metrics and Learning Health Systems for the Department of Pediatrics.',
            'gender_id' => 2,
            'id_number' => 21332394,
            'worker_category_id' => 2,
            'worker_sub_category_id' => 2,
            'licence_number' => 892828,
            'date_licence_renewal' => '2018-10-24',
            'qualification' => 'NER+',
            'specialization' => 'Nursing',
            'residence' => 'Kisumu, Kenya',
            'experience_years' => 5,

        ]);
        \App\Models\UserDevice::create([
            'user_uuid' => $nurseWorkerUser->user_uuid,
            'firebase_token' => 'ewksdbqD_Pg:APA91bF3jlY3vbHOz95FMIIRG_3oUX9fzs2RO8d-tglf3jQZB-cbqxUge7eJM82QnwMfNT4SrFvu0IMwGP3wUcwHW1kVeETQZpCiKCwH7O-ydY4UefWeHmtmfW17EZ-KVbP4Rsn1Ghet',
            'longitude' => '34.7680',
            'latitude' => '-0.0917'
        ]);

        $nurseAWorkerUser = \App\User::create([
            'user_uuid' => Str::uuid(),
            'first_name' => 'Susan',
            'last_name' => 'Kamau',
            'email' => 'susan@hrh.local',
            'phone_no' => '0721 222 000',
            'password'=>'$2y$10$ClS05CwMFgK7dxtj4gma3OddLThoskmVJqMVTSX3KwMfMOzgIMpv2',
            'active'=>1
        ]);
        $nurseAWorkerUser->attachRole($workerRole);
        \App\Models\WorkerProfile::create([
            'user_uuid' => $nurseAWorkerUser->user_uuid,
            'bio' => 'XX DD, MD, MPH, is the Robert P. Kelch, MD Research Professor of Pediatrics at the University of Nairobi Medical School and Professor at the Medical School and at the University of Nairobi School of Public Health. She serves as the Associate Chief Medical Information Officer for Pediatric Research at Nairobi Medicine and as Associate Chair for Health Metrics and Learning Health Systems for the Department of Pediatrics.',
            'gender_id' => 2,
            'id_number' => 21332421,
            'worker_category_id' => 2,
            'worker_sub_category_id' => 3,
            'licence_number' => 823332,
            'date_licence_renewal' => '2018-11-24',
            'qualification' => 'NER+',
            'specialization' => 'Nursing',
            'residence' => 'Nairobi, Kenya',
            'experience_years' => 7,

        ]);
        \App\Models\UserDevice::create([
            'user_uuid' => $nurseAWorkerUser->user_uuid,
            'firebase_token' => 'ewksdbqD_Pg:APA91bF3jlY3vbHOz95FMIIRG_3oUX9fzs2RO8d-tglf3jQZB-cbqxUge7eJM82QnwMfNT4SrFvu0IMwGP3wUcwHW1kVeETQZpCiKCwH7O-ydY4UefWeHmtmfW17EZ-KVbP4Rsn1Ghet',
            'longitude' => '36.7476',
            'latitude' => '-1.3641'
        ]);

        $nurseBWorkerUser = \App\User::create([
            'user_uuid' => Str::uuid(),
            'first_name' => 'Mike',
            'last_name' => 'Njoroge',
            'email' => 'mike@hrh.local',
            'phone_no' => '0721 252 000',
            'password'=>'$2y$10$ClS05CwMFgK7dxtj4gma3OddLThoskmVJqMVTSX3KwMfMOzgIMpv2',
            'active'=>1
        ]);
        $nurseBWorkerUser->attachRole($workerRole);
        \App\Models\WorkerProfile::create([
            'user_uuid' => $nurseBWorkerUser->user_uuid,
            'bio' => 'XX DD, MD, MPH, is the Robert P. Kelch, MD Research Professor of Pediatrics at the University of Nairobi Medical School and Professor at the Medical School and at the University of Nairobi School of Public Health. She serves as the Associate Chief Medical Information Officer for Pediatric Research at Nairobi Medicine and as Associate Chair for Health Metrics and Learning Health Systems for the Department of Pediatrics.',
            'gender_id' => 1,
            'id_number' => 3232421,
            'worker_category_id' => 2,
            'worker_sub_category_id' => 4,
            'licence_number' => 84244,
            'date_licence_renewal' => '2017-08-24',
            'qualification' => 'NER+',
            'specialization' => 'Nursing',
            'residence' => 'Nairobi, Kenya',
            'experience_years' => 2,

        ]);
        \App\Models\UserDevice::create([
            'user_uuid' => $nurseBWorkerUser->user_uuid,
            'firebase_token' => 'ewksdbqD_Pg:APA91bF3jlY3vbHOz95FMIIRG_3oUX9fzs2RO8d-tglf3jQZB-cbqxUge7eJM82QnwMfNT4SrFvu0IMwGP3wUcwHW1kVeETQZpCiKCwH7O-ydY4UefWeHmtmfW17EZ-KVbP4Rsn1Ghet',
            'longitude' => '36.8815',
            'latitude' => '-1.2922'
        ]);

        $nurseBWorkerUser = \App\User::create([
            'user_uuid' => Str::uuid(),
            'first_name' => 'Walter',
            'last_name' => 'Omondi',
            'email' => 'walter@hrh.local',
            'phone_no' => '0770 252 000',
            'password'=>'$2y$10$ClS05CwMFgK7dxtj4gma3OddLThoskmVJqMVTSX3KwMfMOzgIMpv2',
            'active'=>1
        ]);
        $nurseBWorkerUser->attachRole($workerRole);
        \App\Models\WorkerProfile::create([
            'user_uuid' => $nurseBWorkerUser->user_uuid,
            'bio' => 'XX DD, MD, MPH, is the Robert P. Kelch, MD Research Professor of Pediatrics at the University of Nairobi Medical School and Professor at the Medical School and at the University of Nairobi School of Public Health. She serves as the Associate Chief Medical Information Officer for Pediatric Research at Nairobi Medicine and as Associate Chair for Health Metrics and Learning Health Systems for the Department of Pediatrics.',
            'gender_id' => 1,
            'id_number' => 3232424,
            'worker_category_id' => 2,
            'worker_sub_category_id' => 5,
            'licence_number' => 8442244,
            'date_licence_renewal' => '2019-08-24',
            'qualification' => 'NER+',
            'specialization' => 'Nursing',
            'residence' => 'Nairobi, Kenya',
            'experience_years' => 21,

        ]);
        \App\Models\UserDevice::create([
            'user_uuid' => $nurseBWorkerUser->user_uuid,
            'firebase_token' => 'ewksdbqD_Pg:APA91bF3jlY3vbHOz95FMIIRG_3oUX9fzs2RO8d-tglf3jQZB-cbqxUge7eJM82QnwMfNT4SrFvu0IMwGP3wUcwHW1kVeETQZpCiKCwH7O-ydY4UefWeHmtmfW17EZ-KVbP4Rsn1Ghet',
            'longitude' => '36.8338',
            'latitude' => '-1.2877'
        ]);

        $nurseBWorkerUser = \App\User::create([
            'user_uuid' => Str::uuid(),
            'first_name' => 'Angela',
            'last_name' => 'Waeni',
            'email' => 'angela@hrh.local',
            'phone_no' => '0790 252 000',
            'password'=>'$2y$10$ClS05CwMFgK7dxtj4gma3OddLThoskmVJqMVTSX3KwMfMOzgIMpv2',
            'active'=>1
        ]);
        $nurseBWorkerUser->attachRole($workerRole);
        \App\Models\WorkerProfile::create([
            'user_uuid' => $nurseBWorkerUser->user_uuid,
            'bio' => 'XX DD, MD, MPH, is the Robert P. Kelch, MD Research Professor of Pediatrics at the University of Nairobi Medical School and Professor at the Medical School and at the University of Nairobi School of Public Health. She serves as the Associate Chief Medical Information Officer for Pediatric Research at Nairobi Medicine and as Associate Chair for Health Metrics and Learning Health Systems for the Department of Pediatrics.',
            'gender_id' => 2,
            'id_number' => 32422424,
            'worker_category_id' => 2,
            'worker_sub_category_id' => 6,
            'licence_number' => 8355535,
            'date_licence_renewal' => '2019-11-20',
            'qualification' => 'NER+',
            'specialization' => 'Nursing',
            'residence' => 'Nairobi, Kenya',
            'experience_years' => 8,

        ]);
        \App\Models\UserDevice::create([
            'user_uuid' => $nurseBWorkerUser->user_uuid,
            'firebase_token' => 'ewksdbqD_Pg:APA91bF3jlY3vbHOz95FMIIRG_3oUX9fzs2RO8d-tglf3jQZB-cbqxUge7eJM82QnwMfNT4SrFvu0IMwGP3wUcwHW1kVeETQZpCiKCwH7O-ydY4UefWeHmtmfW17EZ-KVbP4Rsn1Ghet',
            'longitude' => '36.7281',
            'latitude' => '-1.2890'
        ]);

        $nurseBWorkerUser = \App\User::create([
            'user_uuid' => Str::uuid(),
            'first_name' => 'Mary',
            'last_name' => 'Ogutu',
            'email' => 'mary@hrh.local',
            'phone_no' => '0721 111 030',
            'password'=>'$2y$10$ClS05CwMFgK7dxtj4gma3OddLThoskmVJqMVTSX3KwMfMOzgIMpv2',
            'active'=>1
        ]);
        $nurseBWorkerUser->attachRole($workerRole);
        \App\Models\WorkerProfile::create([
            'user_uuid' => $nurseBWorkerUser->user_uuid,
            'bio' => 'XX DD, MD, MPH, is the Robert P. Kelch, MD Research Professor of Pediatrics at the University of Nairobi Medical School and Professor at the Medical School and at the University of Nairobi School of Public Health. She serves as the Associate Chief Medical Information Officer for Pediatric Research at Nairobi Medicine and as Associate Chair for Health Metrics and Learning Health Systems for the Department of Pediatrics.',
            'gender_id' => 2,
            'id_number' => 32423131,
            'worker_category_id' => 2,
            'worker_sub_category_id' => 7,
            'licence_number' => 3331331,
            'date_licence_renewal' => '2017-11-20',
            'qualification' => 'NER+',
            'specialization' => 'Nursing',
            'residence' => 'Narok, Kenya',
            'experience_years' => 2,

        ]);
        \App\Models\UserDevice::create([
            'user_uuid' => $nurseBWorkerUser->user_uuid,
            'firebase_token' => 'ewksdbqD_Pg:APA91bF3jlY3vbHOz95FMIIRG_3oUX9fzs2RO8d-tglf3jQZB-cbqxUge7eJM82QnwMfNT4SrFvu0IMwGP3wUcwHW1kVeETQZpCiKCwH7O-ydY4UefWeHmtmfW17EZ-KVbP4Rsn1Ghet',
            'longitude' => '36.7947',
            'latitude' => '-1.2108'
        ]);

        $nurseBWorkerUser = \App\User::create([
            'user_uuid' => Str::uuid(),
            'first_name' => 'David',
            'last_name' => 'Otieno',
            'email' => 'david@hrh.local',
            'phone_no' => '0731 131 030',
            'password'=>'$2y$10$ClS05CwMFgK7dxtj4gma3OddLThoskmVJqMVTSX3KwMfMOzgIMpv2',
            'active'=>1
        ]);
        $nurseBWorkerUser->attachRole($workerRole);
        \App\Models\WorkerProfile::create([
            'user_uuid' => $nurseBWorkerUser->user_uuid,
            'bio' => 'XX DD, MD, MPH, is the Robert P. Kelch, MD Research Professor of Pediatrics at the University of Nairobi Medical School and Professor at the Medical School and at the University of Nairobi School of Public Health. She serves as the Associate Chief Medical Information Officer for Pediatric Research at Nairobi Medicine and as Associate Chair for Health Metrics and Learning Health Systems for the Department of Pediatrics.',
            'gender_id' => 1,
            'id_number' => 3232131,
            'worker_category_id' => 2,
            'worker_sub_category_id' => 8,
            'licence_number' => 3331232,
            'date_licence_renewal' => '2018-11-20',
            'qualification' => 'NER+',
            'specialization' => 'Nursing',
            'residence' => 'Kisumu, Kenya',
            'experience_years' => 4,

        ]);
        \App\Models\UserDevice::create([
            'user_uuid' => $nurseBWorkerUser->user_uuid,
            'firebase_token' => 'ewksdbqD_Pg:APA91bF3jlY3vbHOz95FMIIRG_3oUX9fzs2RO8d-tglf3jQZB-cbqxUge7eJM82QnwMfNT4SrFvu0IMwGP3wUcwHW1kVeETQZpCiKCwH7O-ydY4UefWeHmtmfW17EZ-KVbP4Rsn1Ghet',
            'longitude' => '34.7768',
            'latitude' => '-0.0786'
        ]);

        $nurseBWorkerUser = \App\User::create([
            'user_uuid' => Str::uuid(),
            'first_name' => 'Charlse',
            'last_name' => 'Nassir',
            'email' => 'charlse@hrh.local',
            'phone_no' => '0770 155 050',
            'password'=>'$2y$10$ClS05CwMFgK7dxtj4gma3OddLThoskmVJqMVTSX3KwMfMOzgIMpv2',
            'active'=>1
        ]);
        $nurseBWorkerUser->attachRole($workerRole);
        \App\Models\WorkerProfile::create([
            'user_uuid' => $nurseBWorkerUser->user_uuid,
            'bio' => 'XX DD, MD, MPH, is the Robert P. Kelch, MD Research Professor of Pediatrics at the University of Nairobi Medical School and Professor at the Medical School and at the University of Nairobi School of Public Health. She serves as the Associate Chief Medical Information Officer for Pediatric Research at Nairobi Medicine and as Associate Chair for Health Metrics and Learning Health Systems for the Department of Pediatrics.',
            'gender_id' => 1,
            'id_number' => 2049424,
            'worker_category_id' => 2,
            'worker_sub_category_id' => 9,
            'licence_number' => 24441232,
            'date_licence_renewal' => '2017-09-20',
            'qualification' => 'NER+',
            'specialization' => 'Nursing',
            'residence' => 'Garissa, Kenya',
            'experience_years' => 10,

        ]);
        \App\Models\UserDevice::create([
            'user_uuid' => $nurseBWorkerUser->user_uuid,
            'firebase_token' => 'ewksdbqD_Pg:APA91bF3jlY3vbHOz95FMIIRG_3oUX9fzs2RO8d-tglf3jQZB-cbqxUge7eJM82QnwMfNT4SrFvu0IMwGP3wUcwHW1kVeETQZpCiKCwH7O-ydY4UefWeHmtmfW17EZ-KVbP4Rsn1Ghet',
            'longitude' => '39.6461',
            'latitude' => '-0.4532'
        ]);

        $nurseBWorkerUser = \App\User::create([
            'user_uuid' => Str::uuid(),
            'first_name' => 'Ken',
            'last_name' => 'Peters',
            'email' => 'kene@hrh.local',
            'phone_no' => '0790 355 150',
            'password'=>'$2y$10$ClS05CwMFgK7dxtj4gma3OddLThoskmVJqMVTSX3KwMfMOzgIMpv2',
            'active'=>1
        ]);
        $nurseBWorkerUser->attachRole($workerRole);
        \App\Models\WorkerProfile::create([
            'user_uuid' => $nurseBWorkerUser->user_uuid,
            'bio' => 'XX DD, MD, MPH, is the Robert P. Kelch, MD Research Professor of Pediatrics at the University of Nairobi Medical School and Professor at the Medical School and at the University of Nairobi School of Public Health. She serves as the Associate Chief Medical Information Officer for Pediatric Research at Nairobi Medicine and as Associate Chair for Health Metrics and Learning Health Systems for the Department of Pediatrics.',
            'gender_id' => 1,
            'id_number' => 23232311,
            'worker_category_id' => 3,
            'worker_sub_category_id' => 10,
            'licence_number' => 29999432,
            'date_licence_renewal' => '2019-01-20',
            'qualification' => 'CO+',
            'specialization' => 'Anaesthetist',
            'residence' => 'Nairobi, Kenya',
            'experience_years' => 15,

        ]);
        \App\Models\UserDevice::create([
            'user_uuid' => $nurseBWorkerUser->user_uuid,
            'firebase_token' => 'ewksdbqD_Pg:APA91bF3jlY3vbHOz95FMIIRG_3oUX9fzs2RO8d-tglf3jQZB-cbqxUge7eJM82QnwMfNT4SrFvu0IMwGP3wUcwHW1kVeETQZpCiKCwH7O-ydY4UefWeHmtmfW17EZ-KVbP4Rsn1Ghet',
            'longitude' => '36.7041',
            'latitude' => '-1.3204'
        ]);

        $nurseBWorkerUser = \App\User::create([
            'user_uuid' => Str::uuid(),
            'first_name' => 'Olivia',
            'last_name' => 'Shiro',
            'email' => 'olivia@hrh.local',
            'phone_no' => '0793 356 650',
            'password'=>'$2y$10$ClS05CwMFgK7dxtj4gma3OddLThoskmVJqMVTSX3KwMfMOzgIMpv2',
            'active'=>1
        ]);
        $nurseBWorkerUser->attachRole($workerRole);
        \App\Models\WorkerProfile::create([
            'user_uuid' => $nurseBWorkerUser->user_uuid,
            'bio' => 'XX DD, MD, MPH, is the Robert P. Kelch, MD Research Professor of Pediatrics at the University of Nairobi Medical School and Professor at the Medical School and at the University of Nairobi School of Public Health. She serves as the Associate Chief Medical Information Officer for Pediatric Research at Nairobi Medicine and as Associate Chair for Health Metrics and Learning Health Systems for the Department of Pediatrics.',
            'gender_id' => 2,
            'id_number' => 309232311,
            'worker_category_id' => 3,
            'worker_sub_category_id' => 11,
            'licence_number' => 299921212,
            'date_licence_renewal' => '2016-03-21',
            'qualification' => 'CO+',
            'specialization' => 'Anaesthetist',
            'residence' => 'Nairobi, Kenya',
            'experience_years' => 15,

        ]);
        \App\Models\UserDevice::create([
            'user_uuid' => $nurseBWorkerUser->user_uuid,
            'firebase_token' => 'ewksdbqD_Pg:APA91bF3jlY3vbHOz95FMIIRG_3oUX9fzs2RO8d-tglf3jQZB-cbqxUge7eJM82QnwMfNT4SrFvu0IMwGP3wUcwHW1kVeETQZpCiKCwH7O-ydY4UefWeHmtmfW17EZ-KVbP4Rsn1Ghet',
            'longitude' => '36.8017',
            'latitude' => '-1.3066'
        ]);

        $nurseBWorkerUser = \App\User::create([
            'user_uuid' => Str::uuid(),
            'first_name' => 'Rihanna',
            'last_name' => 'Washington',
            'email' => 'rihanna@hrh.local',
            'phone_no' => '0700 233 950',
            'password'=>'$2y$10$ClS05CwMFgK7dxtj4gma3OddLThoskmVJqMVTSX3KwMfMOzgIMpv2',
            'active'=>1
        ]);
        $nurseBWorkerUser->attachRole($workerRole);
        \App\Models\WorkerProfile::create([
            'user_uuid' => $nurseBWorkerUser->user_uuid,
            'bio' => 'XX DD, MD, MPH, is the Robert P. Kelch, MD Research Professor of Pediatrics at the University of Nairobi Medical School and Professor at the Medical School and at the University of Nairobi School of Public Health. She serves as the Associate Chief Medical Information Officer for Pediatric Research at Nairobi Medicine and as Associate Chair for Health Metrics and Learning Health Systems for the Department of Pediatrics.',
            'gender_id' => 2,
            'id_number' => 309666664,
            'worker_category_id' => 3,
            'worker_sub_category_id' => 12,
            'licence_number' => 2994244444,
            'date_licence_renewal' => '2018-03-21',
            'qualification' => 'CO+',
            'specialization' => 'Anaesthetist',
            'residence' => 'Nairobi, Kenya',
            'experience_years' => 10,

        ]);
        \App\Models\UserDevice::create([
            'user_uuid' => $nurseBWorkerUser->user_uuid,
            'firebase_token' => 'ewksdbqD_Pg:APA91bF3jlY3vbHOz95FMIIRG_3oUX9fzs2RO8d-tglf3jQZB-cbqxUge7eJM82QnwMfNT4SrFvu0IMwGP3wUcwHW1kVeETQZpCiKCwH7O-ydY4UefWeHmtmfW17EZ-KVbP4Rsn1Ghet',
            'longitude' => '36.5017',
            'latitude' => '-1.1066'
        ]);

        $nurseBWorkerUser = \App\User::create([
            'user_uuid' => Str::uuid(),
            'first_name' => 'Beyonce',
            'last_name' => 'Carter',
            'email' => 'beyonce@hrh.local',
            'phone_no' => '0799 133 153',
            'password'=>'$2y$10$ClS05CwMFgK7dxtj4gma3OddLThoskmVJqMVTSX3KwMfMOzgIMpv2',
            'active'=>1
        ]);
        $nurseBWorkerUser->attachRole($workerRole);
        \App\Models\WorkerProfile::create([
            'user_uuid' => $nurseBWorkerUser->user_uuid,
            'bio' => 'XX DD, MD, MPH, is the Robert P. Kelch, MD Research Professor of Pediatrics at the University of Nairobi Medical School and Professor at the Medical School and at the University of Nairobi School of Public Health. She serves as the Associate Chief Medical Information Officer for Pediatric Research at Nairobi Medicine and as Associate Chair for Health Metrics and Learning Health Systems for the Department of Pediatrics.',
            'gender_id' => 2,
            'id_number' => 30932464,
            'worker_category_id' => 4,
            'worker_sub_category_id' => 13,
            'licence_number' => 2994249094,
            'date_licence_renewal' => '2019-03-21',
            'qualification' => 'CO+',
            'specialization' => 'Anaesthetist',
            'residence' => 'Nairobi, Kenya',
            'experience_years' => 17,

        ]);
        \App\Models\UserDevice::create([
            'user_uuid' => $nurseBWorkerUser->user_uuid,
            'firebase_token' => 'ewksdbqD_Pg:APA91bF3jlY3vbHOz95FMIIRG_3oUX9fzs2RO8d-tglf3jQZB-cbqxUge7eJM82QnwMfNT4SrFvu0IMwGP3wUcwHW1kVeETQZpCiKCwH7O-ydY4UefWeHmtmfW17EZ-KVbP4Rsn1Ghet',
            'longitude' => '36.3017',
            'latitude' => '-1.3066'
        ]);

        $nurseBWorkerUser = \App\User::create([
            'user_uuid' => Str::uuid(),
            'first_name' => 'Wayne',
            'last_name' => 'Carter',
            'email' => 'wayne@hrh.local',
            'phone_no' => '0744 143 124',
            'password'=>'$2y$10$ClS05CwMFgK7dxtj4gma3OddLThoskmVJqMVTSX3KwMfMOzgIMpv2',
            'active'=>1
        ]);
        $nurseBWorkerUser->attachRole($workerRole);
        \App\Models\WorkerProfile::create([
            'user_uuid' => $nurseBWorkerUser->user_uuid,
            'bio' => 'XX DD, MD, MPH, is the Robert P. Kelch, MD Research Professor of Pediatrics at the University of Nairobi Medical School and Professor at the Medical School and at the University of Nairobi School of Public Health. She serves as the Associate Chief Medical Information Officer for Pediatric Research at Nairobi Medicine and as Associate Chair for Health Metrics and Learning Health Systems for the Department of Pediatrics.',
            'gender_id' => 1,
            'id_number' => 22422464,
            'worker_category_id' => 4,
            'worker_sub_category_id' => 14,
            'licence_number' => 2994249424,
            'date_licence_renewal' => '2015-03-21',
            'qualification' => 'CO+',
            'specialization' => 'Anaesthetist',
            'residence' => 'Nairobi, Kenya',
            'experience_years' => 32,

        ]);
        \App\Models\UserDevice::create([
            'user_uuid' => $nurseBWorkerUser->user_uuid,
            'firebase_token' => 'ewksdbqD_Pg:APA91bF3jlY3vbHOz95FMIIRG_3oUX9fzs2RO8d-tglf3jQZB-cbqxUge7eJM82QnwMfNT4SrFvu0IMwGP3wUcwHW1kVeETQZpCiKCwH7O-ydY4UefWeHmtmfW17EZ-KVbP4Rsn1Ghet',
            'longitude' => '36.2017',
            'latitude' => '-1.4066'
        ]);

        $nurseWorkerUser = \App\User::create([
            'user_uuid' => Str::uuid(),
            'first_name' => 'Gloria',
            'last_name' => 'Anyango',
            'email' => 'gloria@hrh.local',
            'phone_no' => '0721 549 000',
            'password'=>'$2y$10$ClS05CwMFgK7dxtj4gma3OddLThoskmVJqMVTSX3KwMfMOzgIMpv2',
            'active'=>1
        ]);
        $nurseWorkerUser->attachRole($workerRole);
        \App\Models\WorkerProfile::create([
            'user_uuid' => $nurseWorkerUser->user_uuid,
            'bio' => 'XX DD, MD, MPH, is the Robert P. Kelch, MD Research Professor of Pediatrics at the University of Nairobi Medical School and Professor at the Medical School and at the University of Nairobi School of Public Health. She serves as the Associate Chief Medical Information Officer for Pediatric Research at Nairobi Medicine and as Associate Chair for Health Metrics and Learning Health Systems for the Department of Pediatrics.',
            'gender_id' => 2,
            'id_number' => 21332234,
            'worker_category_id' => 2,
            'worker_sub_category_id' => 2,
            'licence_number' => 322828,
            'date_licence_renewal' => '2017-10-24',
            'qualification' => 'NER+',
            'specialization' => 'Nursing',
            'residence' => 'Kisumu, Kenya',
            'experience_years' => 5,

        ]);
        \App\Models\UserDevice::create([
            'user_uuid' => $nurseWorkerUser->user_uuid,
            'firebase_token' => 'ewksdbqD_Pg:APA91bF3jlY3vbHOz95FMIIRG_3oUX9fzs2RO8d-tglf3jQZB-cbqxUge7eJM82QnwMfNT4SrFvu0IMwGP3wUcwHW1kVeETQZpCiKCwH7O-ydY4UefWeHmtmfW17EZ-KVbP4Rsn1Ghet',
            'longitude' => '33.9680',
            'latitude' => '-0.0517'
        ]);

        $nurseWorkerUser = \App\User::create([
            'user_uuid' => Str::uuid(),
            'first_name' => 'Rebecca',
            'last_name' => 'Watson',
            'email' => 'rebecca@hrh.local',
            'phone_no' => '0734 945 230',
            'password'=>'$2y$10$ClS05CwMFgK7dxtj4gma3OddLThoskmVJqMVTSX3KwMfMOzgIMpv2',
            'active'=>1
        ]);
        $nurseWorkerUser->attachRole($workerRole);
        \App\Models\WorkerProfile::create([
            'user_uuid' => $nurseWorkerUser->user_uuid,
            'bio' => 'XX DD, MD, MPH, is the Robert P. Kelch, MD Research Professor of Pediatrics at the University of Nairobi Medical School and Professor at the Medical School and at the University of Nairobi School of Public Health. She serves as the Associate Chief Medical Information Officer for Pediatric Research at Nairobi Medicine and as Associate Chair for Health Metrics and Learning Health Systems for the Department of Pediatrics.',
            'gender_id' => 2,
            'id_number' => 21332394,
            'worker_category_id' => 2,
            'worker_sub_category_id' => 2,
            'licence_number' => 892828,
            'date_licence_renewal' => '2018-10-24',
            'qualification' => 'NER+',
            'specialization' => 'Nursing',
            'residence' => 'Kisumu, Kenya',
            'experience_years' => 5,

        ]);
        \App\Models\UserDevice::create([
            'user_uuid' => $nurseWorkerUser->user_uuid,
            'firebase_token' => 'ewksdbqD_Pg:APA91bF3jlY3vbHOz95FMIIRG_3oUX9fzs2RO8d-tglf3jQZB-cbqxUge7eJM82QnwMfNT4SrFvu0IMwGP3wUcwHW1kVeETQZpCiKCwH7O-ydY4UefWeHmtmfW17EZ-KVbP4Rsn1Ghet',
            'longitude' => '35.6680',
            'latitude' => '-0.1917'
        ]);

        $nurseWorkerUser = \App\User::create([
            'user_uuid' => Str::uuid(),
            'first_name' => 'Robert',
            'last_name' => 'Omondi',
            'email' => 'robert@hrh.local',
            'phone_no' => '0721 949 034',
            'password'=>'$2y$10$ClS05CwMFgK7dxtj4gma3OddLThoskmVJqMVTSX3KwMfMOzgIMpv2',
            'active'=>1
        ]);
        $nurseWorkerUser->attachRole($workerRole);
        \App\Models\WorkerProfile::create([
            'user_uuid' => $nurseWorkerUser->user_uuid,
            'bio' => 'XX DD, MD, MPH, is the Robert P. Kelch, MD Research Professor of Pediatrics at the University of Nairobi Medical School and Professor at the Medical School and at the University of Nairobi School of Public Health. She serves as the Associate Chief Medical Information Officer for Pediatric Research at Nairobi Medicine and as Associate Chair for Health Metrics and Learning Health Systems for the Department of Pediatrics.',
            'gender_id' => 1,
            'id_number' => 22322394,
            'worker_category_id' => 2,
            'worker_sub_category_id' => 2,
            'licence_number' => 232828,
            'date_licence_renewal' => '2019-10-24',
            'qualification' => 'NER+',
            'specialization' => 'Nursing',
            'residence' => 'Kisumu, Kenya',
            'experience_years' => 5,

        ]);
        \App\Models\UserDevice::create([
            'user_uuid' => $nurseWorkerUser->user_uuid,
            'firebase_token' => 'ewksdbqD_Pg:APA91bF3jlY3vbHOz95FMIIRG_3oUX9fzs2RO8d-tglf3jQZB-cbqxUge7eJM82QnwMfNT4SrFvu0IMwGP3wUcwHW1kVeETQZpCiKCwH7O-ydY4UefWeHmtmfW17EZ-KVbP4Rsn1Ghet',
            'longitude' => '33.7680',
            'latitude' => '-0.1917'
        ]);

        $nurseWorkerUser = \App\User::create([
            'user_uuid' => Str::uuid(),
            'first_name' => 'Walter',
            'last_name' => 'Were',
            'email' => 'walter21@hrh.local',
            'phone_no' => '0700 239 2300',
            'password'=>'$2y$10$ClS05CwMFgK7dxtj4gma3OddLThoskmVJqMVTSX3KwMfMOzgIMpv2',
            'active'=>1
        ]);
        $nurseWorkerUser->attachRole($workerRole);
        \App\Models\WorkerProfile::create([
            'user_uuid' => $nurseWorkerUser->user_uuid,
            'bio' => 'XX DD, MD, MPH, is the Robert P. Kelch, MD Research Professor of Pediatrics at the University of Nairobi Medical School and Professor at the Medical School and at the University of Nairobi School of Public Health. She serves as the Associate Chief Medical Information Officer for Pediatric Research at Nairobi Medicine and as Associate Chair for Health Metrics and Learning Health Systems for the Department of Pediatrics.',
            'gender_id' => 1,
            'id_number' => 21332394,
            'worker_category_id' => 2,
            'worker_sub_category_id' => 2,
            'licence_number' => 812828,
            'date_licence_renewal' => '2018-11-24',
            'qualification' => 'NER+',
            'specialization' => 'Nursing',
            'residence' => 'Kisumu, Kenya',
            'experience_years' => 5,

        ]);
        \App\Models\UserDevice::create([
            'user_uuid' => $nurseWorkerUser->user_uuid,
            'firebase_token' => 'ewksdbqD_Pg:APA91bF3jlY3vbHOz95FMIIRG_3oUX9fzs2RO8d-tglf3jQZB-cbqxUge7eJM82QnwMfNT4SrFvu0IMwGP3wUcwHW1kVeETQZpCiKCwH7O-ydY4UefWeHmtmfW17EZ-KVbP4Rsn1Ghet',
            'longitude' => '32.7680',
            'latitude' => '-0.2917'
        ]);
    }
}