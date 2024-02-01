<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Patient\AuthController;
use App\Http\Controllers\Patient\WaveController;
use App\Http\Controllers\Doctor\DoctorController;
use App\Http\Controllers\Patient\PatientController;
use App\Http\Controllers\Patient\PaymentController;
use App\Http\Controllers\Doctor\DoctorAuthController;
use App\Http\Controllers\Hospital\HospitalController;
use App\Http\Controllers\Hospital\SettingsController;
use App\Http\Controllers\Patient\AppointmentController;
use App\Http\Controllers\Patient\ZoomMeetingController;
use App\Http\Controllers\Hospital\HospitalAuthController;

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

Route::fallback(function () {
    return response()->json([
        'message' => 'Endpoint Not Found. If error persists, contact admin'
    ], 404);
});

Route::prefix('v1')->group(function () {

    Route::middleware('cors')->group(function () {

        Route::namespace('Patient')->group(function () {

            Route::post('register', [AuthController::class, 'register']);
            Route::post('authenicateWithGoogle', [AuthController::class, 'authenicateWithGoogle']);
            Route::post('login', [AuthController::class, 'login']);
            Route::get('/forgot-password', [AuthController::class, 'forget_password']);
            Route::post('/reset-password/{token}', [AuthController::class, 'reset_password']);

            Route::middleware('auth:api')->group(function () {

                Route::post('forget-password', [AuthController::class, 'forget_password']);
                Route::post('reset-password', [AuthController::class, 'reset_password']);
                Route::get('verify-reset-token', [AuthController::class, 'verify_password']);

                Route::post('update_password', [PatientController::class, 'updatePassword']);

                Route::get('get-random-doctors', [PatientController::class, 'getRandomDoctors']);
                Route::get('get-random-hospitals', [PatientController::class, 'getRandomHospitals']);

                Route::post('logout', [AuthController::class, 'logout']);
                Route::get('get-doctors', [PatientController::class, 'getDoctors']);
                Route::get('get-doctor/{unique_id}', [PatientController::class, 'getDoctor']);
                Route::get('get-hospitals', [PatientController::class, 'getHospitals']);
                Route::get('get-hospital/{unique_id}', [PatientController::class, 'getHospital']);

                Route::get('get-dashboard', [PatientController::class, 'getDashboard']);
                Route::post('update-profile/{unique_id}', [PatientController::class, 'updateProfile']);

                Route::get('get-health-profile', [PatientController::class, 'getHealthProfile']);
                Route::post('update-health-profile', [PatientController::class, 'updateHealthProfile']);

                Route::get('settings', [PatientController::class, 'getsetting']);
                Route::post('settings', [PatientController::class, 'updateSetting']);

                Route::post('upload_image', [PatientController::class, 'uploadImage']);
                Route::post('remove_image', [PatientController::class, 'removeImage']);

                Route::post('password/update', [PatientController::class, 'updatePassword']);

                // appointment APIs
                Route::post('create-appointment', [AppointmentController::class, 'createAppointment']);
                Route::post('appointment/details', [AppointmentController::class, 'appointmentDetails']);
                Route::get('appointments', [AppointmentController::class, 'getAll']);
                Route::get('appointment-by-payment-status', [AppointmentController::class, 'appointmentByPaymentStatus']);
                Route::get('appointment/{id}', [AppointmentController::class, 'findAppointment']);
                Route::put('reschedule-appointment/{id}', [AppointmentController::class, 'rescheduleAppointment']);
                Route::post('cancel-appointment/{id}', [AppointmentController::class, 'cancelAppointment']);
                Route::get('appointment-report/{id}', [AppointmentController::class, 'viewAppointmentReport']);
                Route::post('/appointment/update', [AppointmentController::class, 'updateAppointment']);

                // Notifications
                Route::get('notifications', [PatientController::class, 'getAllNotification']);
                Route::post('notification', [PatientController::class, 'markNotificationAsRead']);

                // Health record
                Route::get('history', [PatientController::class, 'history']);

                Route::post('payment', [WaveController::class, 'add']);

                Route::post('save_payment', [PaymentController::class, 'savePayment']);


                //Zoom APIs
                // Route::get('zoom', 'ZoomMeetingController@getZoomUrl');
                // Route::get('redirect', 'ZoomMeetingController@redirect');
                Route::post('refresh-token', [ZoomMeetingController::class, 'refreshToken']);
                Route::post('create-zoom-meeting', [ZoomMeetingController::class, 'createZoomMeeting']);
                Route::post('update-meeting', [ZoomMeetingController::class, 'updateZoomStatus']);


                Route::get('get-zoom-meetings', [ZoomMeetingController::class, 'getMeetingsByPatient']);
                Route::get('get-meeting-status/{meeting_id}', [ZoomMeetingController::class, 'getZoomStatus']);
            });
            // Payment APIs

            Route::get('payment/status', [WaveController::class, 'status'])->name('payment.status');



            Route::get('zoom', [ZoomMeetingController::class, 'getZoomUrl']);
            Route::get('redirect', [ZoomMeetingController::class, 'redirect']);

            // Route::post('create-zoom-meeting', 'ZoomMeetingController@createZoomMeeting');

        });

        Route::namespace('Doctor')->prefix('doctor')->group(function () {

            Route::Post('doctor-login', [DoctorAuthController::class, 'doctorsLogin']);

            Route::post('add-test-Doctor', [DoctorController::class, 'testDoctor']);

            Route::middleware('auth:doctor_api')->group(function () {

                Route::Post('doctor-forget-password', [DoctorAuthController::class, 'doctorsForgetPassword']);
                Route::Post('doctor-reset-password', [DoctorAuthController::class, 'reset_password']);

                Route::post('update_password', [DoctorController::class, 'updatePassword']);

                Route::post('save-schedule', [DoctorController::class, 'setSchedule']);
                Route::get('appointment-by-payment-status', [DoctorController::class, 'appointmentByPaymentStatus']);
                // Route::get('get-days', 'DoctorController@getDays');
                Route::get('get-zoom-meetings', [DoctorController::class, 'getMeetingsByDoctor']);
                Route::get('get-schedule', [DoctorController::class, 'getSchedule']);
                Route::get('search-by-date/{date}', [DoctorController::class, 'searchSchedule']);
                Route::get('get-dashboard', [DoctorController::class, 'getDoctorsDashboard']);

                Route::get('settings', [DoctorController::class, 'getsetting']);
                Route::post('settings', [DoctorController::class, 'updateSetting']);

                Route::post('password/update', [DoctorController::class, 'updatePassword']);

                Route::get('get/appointment/details/{appointment_id}', [DoctorController::class, 'getAppointmentDetail']);

                // Notifications
                Route::get('notifications', [DoctorController::class, 'getAllNotification']);
                Route::post('notification', [DoctorController::class, 'markNotificationAsRead']);

                Route::get('get-account-details', [DoctorController::class, 'getAccountDetails']);
                Route::post('save-account', [DoctorController::class, 'account']);

                Route::get('get-health-profile', [DoctorController::class, 'getHealthProfile']);
                Route::post('save-health-profile', [DoctorController::class, 'healthProfile']);


                // Route::post('write-report/{appointment_id}', 'ReportController@store');
                Route::post('add-emr', [DoctorController::class, 'recordHealthHistory']);
                Route::get('get-emr/{appointment_id}', [DoctorController::class, 'getEMR']);
                Route::get('get-history', [DoctorController::class, 'history']);
                Route::get('find-appointment/{unique_id}', [DoctorController::class, 'findAppointment']);
                Route::post('update-emr/{appointment_id}', [DoctorController::class, 'UpdateEMR']);

                Route::post('upload_image', [DoctorController::class, 'uploadImage']);
                Route::post('remove_image', [DoctorController::class, 'removeImage']);
            });
        });

        Route::namespace('Hospital')->prefix('hospital')->group(function () {
            //abass api code goes here
            Route::post('hospital-login', [HospitalAuthController::class, 'hospitalLogin']);

            Route::post('add-test-Hospital', [HospitalController::class, 'testHospital']);

            Route::middleware('auth:hospital_api')->group(function () {
                Route::post('add-doctor', [HospitalController::class, 'addDoctor']);
                Route::get('dashboard', [HospitalController::class, 'getDashboard']);
                Route::put('update', [HospitalController::class, 'update']);
                Route::post('forget-password', [HospitalAuthController::class, 'hospitalForgetPassword']);
                Route::post('reset-password', [HospitalAuthController::class, 'hospitalResetPassword']);
                Route::get('verify-reset-token', [HospitalAuthController::class, 'hospitalVerifyPassword']);

                Route::get('setting', [SettingsController::class, 'index']);
                Route::post('setting', [SettingsController::class, 'settings']);
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
