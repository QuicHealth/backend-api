<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('v1')->group(function () {

    Route::get('/test', function () {
        return "pascal";
    });

    Route::namespace ('Patient')->group(function () {
        //pascal api code goes here
        //        Route::resource('patient', 'PatientController');
        Route::post('register', 'LoginController@register');
        Route::post('login', 'LoginController@login');
        Route::post('forget-password', 'LoginController@forget_password');
        Route::post('reset-password', 'LoginController@reset_password');
        Route::get('verify-reset-token', 'LoginController@verify_password');

        // patient auth route
        Route::get('get-random-doctors', 'PatientController@getRandomDoctors');
        Route::get('get-random-hospitals', 'PatientController@getRandomHospitals');

        Route::get('get-doctors', 'PatientController@getDoctors');
        Route::get('get-doctor/{unique_id}', 'PatientController@getDoctor');
        Route::get('get-hospitals', 'PatientController@getHospitals');
        Route::get('get-hospital/{unique_id}', 'PatientController@getHospital');

        Route::get('get-dashboard', 'PatientController@getDashboard');
        Route::post('update-profile', 'PatientController@updateProfile');

    });

    Route::namespace ('Hospital')->group(function () {
        //abass api code goes here

        Route::post('hospital_login', 'LoginController@login');
        Route::post('hospital-forget-password', 'LoginController@forgetPassword');
        Route::post('hospital-reset-password', 'LoginController@resetPassword');
        Route::post('hospital/update/{id}', 'Hospital\HospitalController@UpdateProfile');
    });
});
