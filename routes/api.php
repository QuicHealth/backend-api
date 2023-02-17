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

    Route::middleware('cors')->group(function () {

        Route::namespace('Patient')->group(function () {

            Route::post('register', 'AuthController@register');
            Route::post('login', 'AuthController@login');

            Route::middleware('auth:api')->group(function () {

                Route::post('forget-password', 'AuthController@forget_password');
                Route::post('reset-password', 'AuthController@reset_password');
                Route::get('verify-reset-token', 'AuthController@verify_password');

                Route::post('update_password', 'PatientController@updatePassword');

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

                Route::get('get-health-profile', 'PatientController@getHealthProfile');
                Route::post('update-health-profile', 'PatientController@updateHealthProfile');

                Route::get('settings', 'PatientController@getsetting');
                Route::post('settings', 'PatientController@updateSetting');

                Route::post('upload_image', 'PatientController@uploadImage');
                Route::post('remove_image', 'PatientController@removeImage');

                Route::post('password/update', 'PatientController@updatePassword');

                // appointment APIs
                Route::post('create-appointment', 'AppointmentController@createAppointment');
                Route::post('appointment/details', 'AppointmentController@appointmentDetails');
                Route::get('appointments', 'AppointmentController@getAll');
                Route::get('appointment-by-payment-status', 'AppointmentController@appointmentByPaymentStatus');
                Route::get('appointment/{id}', 'AppointmentController@findAppointment');
                Route::put('reschedule-appointment/{id}', 'AppointmentController@rescheduleAppointment');
                Route::post('cancel-appointment/{id}', 'AppointmentController@cancelAppointment');
                Route::get('appointment-report/{id}', 'AppointmentController@viewAppointmentReport');

                // Notifications
                Route::get('notifications', 'PatientController@getAllNotification');
                Route::post('notification', 'PatientController@markNotificationAsRead');

                // Health record
                Route::get('history', 'PatientController@history');

                Route::post('payment', 'WaveController@add');

                //Zoom APIs
                // Route::get('zoom', 'ZoomMeetingController@getZoomUrl');
                // Route::get('redirect', 'ZoomMeetingController@redirect');
                Route::post('refresh-token', 'ZoomMeetingController@refreshToken');
                Route::post('create-zoom-meeting', 'ZoomMeetingController@createZoomMeeting');

                Route::get('get-zoom-meetings', 'ZoomMeetingController@getMeetingsByPatient');
            });
            // Payment APIs

            Route::get('payment/status', 'WaveController@status')->name('payment.status');

            Route::get('zoom', 'ZoomMeetingController@getZoomUrl');
            Route::get('redirect', 'ZoomMeetingController@redirect');

            // Route::post('create-zoom-meeting', 'ZoomMeetingController@createZoomMeeting');

        });

        Route::namespace('Doctor')->prefix('doctor')->group(function () {

            Route::Post('doctor-login', 'DoctorAuthController@doctorsLogin');

            Route::post('add-test-Doctor', 'DoctorController@testDoctor');

            Route::middleware('auth:doctor_api')->group(function () {

                Route::Post(
                    'doctor-forget-password',
                    'DoctorAuthController@doctorsForgetPassword'
                );
                Route::Post('doctor-reset-password', 'DoctorAuthController@reset_password');

                Route::post('update_password', 'DoctorController@updatePassword');

                Route::post('save-schedule', 'DoctorController@setSchedule');
                Route::get('appointment-by-payment-status', 'DoctorController@appointmentByPaymentStatus');
                // Route::get('get-days', 'DoctorController@getDays');
                Route::get('get-zoom-meetings', 'DoctorController@getMeetingsByDoctor');
                Route::get('get-schedule', 'DoctorController@getSchedule');
                Route::get('search-by-date/{date}', 'DoctorController@searchSchedule');
                Route::get('get-dashboard', 'DoctorController@getDoctorsDashboard');

                Route::get('settings', 'DoctorController@getsetting');
                Route::post('settings', 'DoctorController@updateSetting');

                Route::post('password/update', 'DoctorController@updatePassword');

                Route::get('get/appointment/details/{appointment_id}', 'DoctorController@getAppointmentDetail');

                // Notifications
                Route::get('notifications', 'DoctorController@getAllNotification');
                Route::post('notification', 'DoctorController@markNotificationAsRead');

                // Route::post('write-report/{appointment_id}', 'ReportController@store');
                Route::post('add-emr', 'DoctorController@recordHealthHistory');
                Route::get('get-emr/{appointment_id}', 'DoctorController@getEMR');
                Route::post('update-emr/{appointment_id}', 'DoctorController@UpdateEMR');

                Route::post('upload_image', 'DoctorController@uploadImage');
                Route::post('remove_image', 'DoctorController@removeImage');
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
});


/**
 * PLEASE DONT TOUCH THIS NOTE
 * ***************************
 *
 * Had the same error, solved it by running sudo mysql which logged me in as root without a password,
 * then I ran ALTER USER 'root'@'localhost' IDENTIFIED WITH mysql_native_password by 'mynewpassword';

 * Following this I was able to run mysql_secure_installation again and this time
 * I got to choose to use existing password (the one I set with above SQL command).
 * However now I can no longer login without a password, running sudo mysql now denies root user access.
 */