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

// Route::middleware('auth:api')->get('/timeoffdays', function (Request $request) {
//     return $request->user();
// });

// Route::prefix('timeoffdays')->group(function() {
    
   
// });

Route::middleware('auth')->prefix('timeoffdays')->group(function() {

    Route::prefix('time-off-days-total')->group(function() {
        Route::get('create', 'TimeOffDaysTotalController@create');
    });

    Route::prefix('time-off-days-config-approve')->group(function() {
        Route::get('create', 'TimeOffDaysConfigApproveController@create');
    });
    Route::prefix('time-off-type')->group(function() {
        Route::post('list', 'TimeOffTypeController@list');
    });

    Route::prefix('staffs')->group(function() {
        Route::post('list', 'StaffsController@list');
    });

    Route::prefix('time-working-staffs')->group(function() {
        Route::post('list', 'TimeWorkingStaffsController@list');
    });

    Route::prefix('time-off-days-log')->group(function() {
        Route::post('list', 'TimeOffDaysLogController@list');
    });

    Route::prefix('sf-shifts')->group(function() {
        Route::post('list', 'SFShiftsController@list');
    });

    Route::prefix('time')->group(function() {
        Route::post('list', 'TimeController@list');
    });


    Route::post('activity', 'TimeOffDaysController@activity');
    Route::post('list', 'TimeOffDaysController@list');
    Route::post('search', 'TimeOffDaysController@search');
    Route::post('create', 'TimeOffDaysController@create');
    Route::post('cancel', 'TimeOffDaysController@cancel');
    Route::post('detail', 'TimeOffDaysController@detail');
    Route::post('remove', 'TimeOffDaysController@remove');
    Route::post('edit', 'TimeOffDaysController@edit');
    Route::post('total', 'TimeOffDaysController@total');
    Route::post('count', 'TimeOffDaysController@count');
});