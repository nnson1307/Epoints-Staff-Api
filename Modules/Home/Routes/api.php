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

Route::middleware('auth')->prefix('home')->group(function() {
    Route::post('index', 'HomeController@getHome');
    Route::post('get-service', 'HomeController@getService');
    Route::post('get-all-products', 'HomeController@getAllProducts');
    Route::post('search-all', 'HomeController@searchAll');
});
