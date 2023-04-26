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

Route::middleware('chat-hub')->prefix('chathub')->group(function() {
    Route::post('get-customer', 'ChatHubController@getCustomer');
    Route::prefix('customer-lead')->group(function () {
         //chi tiet KHTN
         Route::post('detail', 'CustomerLeadController@getDetail');
         Route::post('update-journey', 'CustomerLeadController@updateJourney');
    });
    Route::prefix('order')->group(function () {
        //Lấy danh sách đơn hàng
        Route::post('/', 'OrderController@getOrders');
   });
   Route::prefix('product')->group(function () {
        //Lấy danh sách sản phẩm
        Route::post('/', 'ProductController@getProducts');
    });
    Route::prefix('customer')->group(function () {
        //Lấy danh sách sản phẩm
        Route::post('detail', 'CustomerController@getDetail');
    });
});
