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

Route::middleware('auth')->prefix('report')->group(function() {

    Route::prefix('revenue-order')->group(function () {
        Route::post('get-branch', 'ReportRevenueOrderController@getBranch');
        Route::post('total', 'ReportRevenueOrderController@totalRevenue');
        Route::post('detail', 'ReportRevenueOrderController@detailRevenue');
    });

    Route::prefix('inventory')->group(function () {
        Route::post('total', 'ReportInventoryController@totalInventory');
        Route::post('list-detail', 'ReportInventoryController@listDetailInventory');
        //Api tồn kho mới
        Route::post('total-new', 'ReportInventoryController@totalInventoryNew');
        Route::post('list-detail-new', 'ReportInventoryController@listDetailInventoryNew');
    });

    Route::prefix('staff-commission')->group(function () {
        Route::post('total', 'ReportStaffCommissionController@totalCommission');
        Route::post('list-detail', 'ReportStaffCommissionController@detailCommission');
    });
});