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

    Route::namespace('Patient')->group(function () {

        Route::post('register', 'AuthController@register');
        Route::post('login', 'AuthController@login');

        Route::middleware('auth:api')->group(function () {
            Route::post('forget-password', 'AuthController@forget_password');
            Route::post('reset-password', 'AuthController@reset_password');
            Route::get('verify-reset-token', 'AuthController@verify_password');

            Route::get('get-random-doctors', 'PatientController@getRandomDoctors');
            Route::get(
                'get-random-hospitals',
                'PatientController@getRandomHospitals'
            );
            Route::post('logout', 'AuthController@logout');
            Route::get('get-doctors', 'PatientController@getDoctors');
            Route::get('get-doctor/{unique_id}', 'PatientController@getDoctor');
            Route::get('get-hospitals', 'PatientController@getHospitals');
            Route::get('get-hospital/{unique_id}', 'PatientController@getHospital');

            Route::get('get-dashboard', 'PatientController@getDashboard');
            Route::post('update-profile/{unique_id}', 'PatientController@updateProfile');

            // appointment APIs
            Route::post('create-appointment', 'AppointmentController@createAppointment');
            Route::post('appointment/details', 'AppointmentController@appointmentDetails');
            Route::get('appointments', 'AppointmentController@getAll');
            Route::get('appointment/{id}', 'AppointmentController@findAppointment');
            Route::put('reschedule-appointment/{id}', 'AppointmentController@rescheduleAppointment');
            Route::post('cancel-appointment/{id}', 'AppointmentController@cancelAppointment');
            Route::get('appointment-report/{id}', 'AppointmentController@viewAppointmentReport');

            // Route::post('payment', 'PaymentController@makePayment');
            // Route::get('payment/confirm/{txnReference}', 'PaymentController@payment_status');
            // Route::get('payment/status', 'PaymentController@payment_status')->name('payment.status');
            // Route::post('webhook-receiving-url', 'PaymentController@txnCompletion');

            //Zoom
            Route::get('meetings', 'ZoomMeetingController@index');
            Route::post('create-meeting', 'ZoomMeetingController@store');

            // Payment APIs
            Route::post('payment', 'WaveController@add');
        });
        Route::get('payment/status', 'WaveController@status')->name('payment.status');
    });

    // Route::webhooks('webhook-receiving-url');


    Route::namespace('Doctor')->prefix('doctor')->group(function () {

        Route::Post('doctor-login', 'DoctorAuthController@doctorsLogin');

        Route::post('add-test-Doctor', 'DoctorController@testDoctor');

        Route::middleware('auth:doctor_api')->group(function () {
            Route::Post(
                'doctor-forget-password',
                'DoctorAuthController@doctorsForgetPassword'
            );
            Route::Post('doctor-reset-password', 'DoctorAuthController@reset_password');
            Route::post('save-schedule', 'DoctorController@setSchedule');
            // Route::get('get-days', 'DoctorController@getDays');
            Route::get('get-schedule', 'DoctorController@getSchedule');
            Route::get('search-by-date/{date}', 'DoctorController@searchSchedule');
            Route::get('get-dashboard', 'DoctorController@getDoctorsDashboard');
        });
    });

    Route::namespace('Hospital')->prefix('hospital')->group(function () {
        //abass api code goes here
        Route::post('hospital-login', 'HospitalAuthController@hospitalLogin');

        Route::post('add-test-Hospital', 'HospitalController@testHospital');

        Route::middleware('auth:hospital_api')->group(function () {
            Route::post('add-doctor', 'HospitalController@addDoctor');
            Route::get('dashboard', 'HospitalController@getDashboard');
            Route::put('update', 'HospitalController@update');
            Route::post('forget-password', 'HospitalAuthController@hospitalForgetPassword');
            Route::post('reset-password', 'HospitalAuthController@hospitalResetPassword');
            Route::get('verify-reset-token', 'HospitalAuthController@hospitalVerifyPassword');

            Route::get('setting', 'SettingsController@index');
            Route::post('setting', 'SettingsController@settings');
        });
    });
});
