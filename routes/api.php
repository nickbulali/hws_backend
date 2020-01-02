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

Route::resource('role', 'API\RoleController');
  
Route::middleware('auth:api')->group( function () {
	Route::post('/logout', 'Auth\APIController@logout');
	Route::get('/get-user', 'Auth\APIController@getUser');

	Route::get('/notifications', 'Auth\APIController@notifications');
    Route::get('/notificationRead/{id}', 'Auth\APIController@notificationRead');

	Route::resource('/user', 'API\UserController');
	Route::resource('/userDevice', 'API\UserDeviceController');
	Route::resource('/workerCategory', 'API\WorkerCategoryController');
	Route::resource('/workerSubCategory', 'API\WorkerSubCategoryController');
	Route::resource('/userRequest', 'API\UserRequestController');
	
});
