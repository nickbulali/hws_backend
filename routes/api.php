<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('email', 'API\AdminController@testemail');



Route::post('/register', 'Auth\APIController@register');
Route::post('/login', 'Auth\APIController@login');
Route::get('/auth/signup/activate/{token}', 'Auth\APIController@signupActivate');

Route::group([    
	    'namespace' => 'Auth',    
	    'middleware' => 'api',    
	    'prefix' => 'password'
	], function () {
	    Route::post('create', 'PasswordResetController@create');
	    Route::get('find/{token}', 'PasswordResetController@find');
	    Route::post('reset', 'PasswordResetController@reset');
});

Route::group([    
	    'namespace' => 'Auth',    
	    'middleware' => 'api',    
	    'prefix' => 'sms'
	], function () {
	    Route::post('create', 'SMSController@register');

});

Route::resource('role', 'API\RoleController')->only('index');
  
Route::middleware('auth:api')->group( function () {

Route::group(['middleware'=>['permission:manage']],function(){


  Route::get('monthlyrequest','API\AdminController@Monthlyrequest');
Route::get('frequency','API\AdminController@Frequency');

Route::get('dailyrequest','API\AdminController@DailyRequest');
Route::get('ratings','API\AdminController@Ratings');
Route::get('allusers','API\AdminController@Allusers');
Route::get('allrequests','API\AdminController@Allrequests');
Route::get('healthworkers','API\AdminController@healthworkers');
Route::get('countusers','API\AdminController@countUsers');
Route::get('countdoctors','API\AdminController@countDoctors');
Route::get('countnurses','API\AdminController@countNurse');
Route::get('countclinicians','API\AdminController@countClinician');
Route::get('countpharmacist','API\AdminController@countPharmacist');
Route::get('countfacilities','API\AdminController@countFacilities');
Route::get('pending','API\AdminController@pending');
Route::get('complete','API\AdminController@complete');
Route::get('rejected','API\AdminController@rejected');
Route::resource('permission', 'API\PermissionController');
Route::get('permissionrole/attach', 'API\PermissionRoleController@attach');
Route::get('permissionrole/detach', 'API\PermissionRoleController@detach');
Route::get('permissionrole', 'API\PermissionRoleController@index');
Route::resource('role', 'API\RoleController')->except('index');
Route::get('roleuser/attach', 'API\RoleUserController@attach');
Route::get('roleuser/detach', 'API\RoleUserController@detach');
Route::get('roleuser', 'API\RoleUserController@index');



Route::get('verify/{id}','API\AdminController@verifyWorker')->name('verify');


 
});



	Route::post('/logout', 'Auth\APIController@logout');
	Route::get('/get-user', 'Auth\APIController@getUser');

	Route::get('/notifications', 'Auth\APIController@notifications');
    Route::get('/notificationRead/{id}', 'Auth\APIController@notificationRead');

	Route::resource('/user', 'API\UserController');
	Route::resource('/userDevice', 'API\UserDeviceController');
	Route::resource('/userRating', 'API\UserRatingController');
	Route::resource('/userFavourite', 'API\UserFavouriteController');
	Route::resource('/workerCategory', 'API\WorkerCategoryController');
	Route::resource('/workerSubCategory', 'API\WorkerSubCategoryController');
	Route::resource('/userRequest', 'API\UserRequestController');
	Route::resource('/facilities', 'API\FacilityController');
	Route::resource('/facilities_level', 'API\FacilityLevel
		Controller');
	
});
