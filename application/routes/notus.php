<?php

Route::prefix('administrator')->group(function () {
    Route::get('dashboard', 'Admin\DashboardController@index')->name('notus_dashboard')->middleware('auth');
    Route::get('users', 'Admin\UserController@list')->name('notus_users')->middleware('auth');
    //currencies
    Route::get('currencies', 'Admin\CurrencyController@list')->name('notus_currencies')->middleware('auth');
    Route::get('currencies/add', 'Admin\CurrencyController@add')->name('notus_currencies')->middleware('auth');
    Route::post('currencies/create', 'Admin\CurrencyController@create')->middleware('auth');
});