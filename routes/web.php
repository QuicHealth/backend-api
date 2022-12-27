<?php

use App\Hospital;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AdminAuthController;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return "home";
});

// Auth::routes();

// Route::get('/', [AdminAuthController::class, 'login']);
// Route::get('/dashboard', [AdminController::class, 'dashboard']);

// Route::get('/home', 'HomeController@index')->name('home');

Route::namespace('Admin')->prefix('admin')->group(function () {

    Route::get('login', [AdminAuthController::class, 'login'])->name('admin.login');
    Route::post('login', [AdminAuthController::class, 'authentiate'])->name('admin.authentiate');

    Route::middleware('auth:admin')->group(function () {
        Route::get('/', 'AdminController@index')->name('admin.home');

        Route::get('/404', 'AdminController@notfound')->name('admin.notfound');


        // Route::get('users', 'AdminController@users')->name('get_users');

        // Route::get('verify-hospital', 'AdminController@verifyHospital')->name('admin.verifyHospital');
        // Route::get('hospitals', 'AdminController@hospitals')->name('admin.hospitals');
        // Route::get('doctors', 'AdminController@doctors')->name('admin.doctors');

        // Route::get('sendMail', 'AdminController@sendEmail')->name('admin.email');
        // Route::get('complains', 'AdminController@complains')->name('admin.complains');
        // Route::get('messages', 'AdminController@messages')->name('admin.messages');


        Route::prefix('user')->group(function () {
            Route::get('/', 'AdminController@users')->name('admin.users');
            Route::post('add', 'AdminController@addUser')->name('admin.user.add');
            Route::prefix('{unique_id}')->group(function () {
                Route::get('/', 'AdminController@userId')->name('admin.user.details');
                Route::post('update', 'AdminController@updateUser')->name('admin.user.update');
                Route::get('delete', 'AdminController@deleteUser')->name('admin.user.delete');
            });
        });

        Route::prefix('hospital')->group(function () {
            Route::get('/', 'AdminController@hospitals')->name('admin.hospitals');
            Route::post('add', 'AdminController@addHospital')->name('admin.hospital.add');
            Route::get('verify', 'AdminController@verifyHospital')->name('admin.verifyHospital');

            Route::prefix('{unique_id}')->group(function () {
                Route::get('/', 'AdminController@hospital')->name('admin.hospital.detail');
                Route::post('/approve', 'AdminController@approveHospital')->name('admin.hospital.approve');
                Route::post('update', 'AdminController@updateHospital')->name('admin.hospital.update');
                Route::get('delete', 'AdminController@deleteHospital');
            });
        });

        Route::prefix('doctor')->group(function () {
            Route::get('/', 'AdminController@doctors')->name('admin.doctors');
            Route::post('add', 'AdminController@addDoctor');
            Route::prefix('{unique_id}')->group(function () {
                Route::get('/', 'AdminController@doctor')->name('admin.doctor.detail');
                Route::post('update', 'AdminController@updateDoctor')->name('admin.doctor.update');
                Route::get('delete', 'AdminController@deleteDoctor');
            });
        });

        Route::prefix('meetings')->group(function () {
            Route::get('/', 'AdminController@meeting')->name('admin.meeting.index');
        });

        Route::prefix('financial')->group(function () {
            Route::get('payments', 'AdminController@payment')->name('admin.financial.payment');
            Route::get('payout', 'AdminController@hospitalPayout')->name('admin.financial.hospitalpayout');
            // Route::get('hospital/payout', 'AdminController@hospitalPayout')->name('admin.hospital.payout');

        });

        Route::prefix('messages')->group(function () {
            Route::get('sendMail', 'AdminController@sendEmail')->name('admin.email');
            Route::get('complains', 'AdminController@complains')->name('admin.complains');
            Route::get('messages', 'AdminController@messages')->name('admin.messages');
        });

        Route::prefix('settings')->group(function () {
            Route::get('passwordreset', 'AdminController@passwordReset')->name('admin.passwordReset');

            Route::prefix('admin')->group(function () {
                Route::get('/', 'AdminController@admins')->name('admin.admins');
                Route::post('add', 'AdminController@addAdmin')->name('admin.admins.add');
            });
        });


        Route::get('logout', [AdminAuthController::class, 'logout'])->name('admin.logout');
    });
});

// Route::namespace('Admin')->prefix('admin')->group(function () {
    // Route::get('login', 'AdminLoginController@getLogin')->name('admin.login');
    // Route::post('login', 'AdminLoginController@login');

    // Route::middleware('auth:admin')->group(function () {
    //     Route::get('/', 'AdminController@index');
    //     Route::get('hospital/{id}', 'AdminController@hospital');
    //     Route::get('hospitals', 'AdminController@hospitals');
    //     Route::post('add-hospital', 'AdminController@addHospital');
    //     Route::post('update-hospital', 'AdminController@updateHospital');
    //     Route::post('delete-hospital', 'AdminController@deleteHospital');

        // Route::post('add-doctor', 'AdminController@addDoctor');
        // Route::get('doctors', 'AdminController@doctors');
        // Route::get('doctor/{id}', 'AdminController@doctor');
        // Route::post('update-doctor', 'AdminController@updateDoctor');
        // Route::get('delete-doctor', 'AdminController@deleteDoctor');

    //     Route::post('logout', 'AdminController@logout');
    // });
// });
