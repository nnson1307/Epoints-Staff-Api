<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::prefix('payment')->group(function() {
    Route::prefix('eway')->group(function() {
        Route::match(['get', 'post'], '/', 'EwayController@indexAction')->name('payment.eway');
        Route::match(['get', 'post'], '/callback', 'EwayController@callbackAction')->name('payment.eway.callback');
        Route::match(['get', 'post'], '/response', 'EwayController@responseAction')->name('payment.eway.response');
    });
});
