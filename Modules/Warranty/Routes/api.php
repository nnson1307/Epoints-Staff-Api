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

Route::middleware('auth')->prefix('warranty')->group(function() {
    //Phiếu bảo hành
    Route::prefix('warranty-card')->group(function() {
        //DS gói bảo hành
        Route::post('get-package', 'WarrantyCardController@getPackage');
        //DS phiếu bảo hành
        Route::post('get-warranty-card', 'WarrantyCardController@getWarrantyCard');
        //Chi tiết phiếu bảo hành
        Route::post('detail', 'WarrantyCardController@show');
        //Cập nhật nhanh trạng thái
        Route::post('quick-update', 'WarrantyCardController@quickUpdate');
        //Cập nhật phiếu bảo hành
        Route::post('update', 'WarrantyCardController@update');
        //DS trạng thái của phiếu bảo hành
        Route::post('list-status', 'WarrantyCardController@listStatus');
    });

    //Phiếu bảo trì
    Route::prefix('maintenance')->group(function() {
        //DS phiếu bảo trì
        Route::post('get-maintenance', 'MaintenanceController@getMaintenance');
        //Lấy ds phiếu bảo trì của KH
        Route::post('get-warranty-card-customer', 'MaintenanceController@getWarrantyCardCustomer');
        //Chi phí phát sinh
        Route::post('get-cost-type', 'MaintenanceController@getCostType');
        //Thêm phiếu bảo trì
        Route::post('store', "MaintenanceController@store");
        //Chi tiết phiếu bảo trì
        Route::post('detail', 'MaintenanceController@show');
        //Cập nhật phiếu bảo trì
        Route::post('update', 'MaintenanceController@update');
        //Thanh toán phiếu bảo trì
        Route::post('receipt', 'MaintenanceController@receiptMaintenance');
        //Ds trạng thái phiếu bảo trì
        Route::post('list-status', 'MaintenanceController@listStatus');
    });
});