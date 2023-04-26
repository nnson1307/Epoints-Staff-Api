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

Route::middleware('auth')->prefix('survey')->group(function() {
    Route::prefix('list')->group(function() {
        Route::post('/mission', 'ListController@missionAction');
        Route::post('/history', 'ListController@historyAction');
        Route::post('/history-preview', 'ListController@historyPreviewAction');
    });

    Route::post('/count', 'ListController@countAction');
    Route::post('/detail', 'InfoController@detailAction');

    Route::prefix('process')->group(function() {
        Route::post('/start', 'SurveyProcessController@startAction');
        Route::post('/submit', 'SurveyProcessController@submitAction');
        Route::post('/finish', 'SurveyProcessController@finishAction');
    });
});