<?php

use App\Hospital;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AdminAuthController;

// Route::get('/', function () {
//     //     dd(rand(111111,999999));
//     //     $hospitals = Hospital::where('status', 1)->paginate(12);
//     //    dd($hospitals);
//     //     return view('welcome');
// });

// Auth::routes();

// Route::get('/', [AdminAuthController::class, 'login']);
// Route::get('/dashboard', [AdminController::class, 'dashboard']);

// Route::get('/home', 'HomeController@index')->name('home');

Route::namespace('Admin')->prefix('admin')->group(function () {
    Route::get('login', 'AdminLoginController@getLogin')->name('admin.login');
    Route::post('login', 'AdminLoginController@login');

    Route::middleware('auth:admin')->group(function () {
        Route::get('/', 'AdminController@index')->name('admin.home');

        Route::get('users', 'AdminController@users')->name('admin.users');
        Route::get('user/{id}', 'AdminController@userId')->name('admin.user.details');
        Route::get('user/{id}/block', 'AdminController@blockUserId')->name('admin.user.block');

        Route::get('verify-hospital', 'AdminController@verifyHospital')->name('admin.verifyHospital');
        Route::get('hospitals', 'AdminController@hospitals')->name('admin.hospitals');
        Route::get('doctors', 'AdminController@doctors')->name('admin.doctors');

        Route::get('sendMail', 'AdminController@sendEmail')->name('admin.email');
        Route::get('complains', 'AdminController@complains')->name('admin.complains');
        Route::get('messages', 'AdminController@messages')->name('admin.messages');

        Route::get('admins', 'AdminController@admins')->name('admin.admins');
        Route::get('passwordreset', 'AdminController@passwordReset')->name('admin.passwordReset');

        Route::get('hospital/payout', 'AdminController@hospitalPayout')->name('admin.hospital.payout');

        Route::get('logout', 'AdminController@logout')->name('admin.logout');
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
