<?php
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


use Modules\Brand\Http\Controllers\RegisterBrandController;

Route::prefix('brand')->group(function() {
    Route::post('register', [RegisterBrandController::class, 'registerBrandAction']);
    Route::post('scan', [RegisterBrandController::class, 'scanQRCodeAction']);
    Route::post('generate-key', [RegisterBrandController::class, 'generateKeyAction']);
});