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

Route::prefix('branch')->group(function() {
    Route::post('get-branch', 'BranchController@getBranchETL');

});

Route::middleware('auth')->prefix('branch')->group(function() {
    Route::post('get-list', 'BranchController@getBranch');
});