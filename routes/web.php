<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::namespace('Admin')->prefix('admin')->group(function(){
    Route::get('login', 'AdminLoginController@getLogin')->name('admin.login');
    Route::post('login', 'AdminLoginController@login');

    Route::middleware('auth:admin')->group(function(){
        Route::get('/', 'AdminController@index');
        Route::get('hospital/{id}', 'AdminController@hospital');
        Route::get('hospitals', 'AdminController@hospitals');
        Route::post('add-hospital', 'AdminController@addHospital');
        Route::post('update-hospital', 'AdminController@updateHospital');
        Route::post('delete-hospital', 'AdminController@deleteHospital');
    });

});