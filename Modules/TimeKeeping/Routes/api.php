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

Route::middleware('auth')->prefix('timekeeping')->group(function () {
    Route::post('shift', 'TimeKeepingController@getShiftAction');
    Route::post('check-in', 'TimeKeepingController@checkInAction');
    Route::post('check-out', 'TimeKeepingController@checkOutAction');
    Route::post('histories', 'TimeKeepingController@getHistoryAction');
    Route::post('personal-history', 'TimeKeepingController@getPersonalHistoryAction');
    //Lấy ngày lễ
    Route::post('get-day-holiday', 'TimeKeepingController@getDayHoliday');
});