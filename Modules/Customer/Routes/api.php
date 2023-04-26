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

Route::middleware('auth')->prefix('customer')->group(function() {
    Route::post('get-customer', 'CustomerController@getCustomer');
    Route::post('detail', 'CustomerController@getDetail');
    //Khách hàng tiềm năng
    Route::post('add-customer','CustomerController@getAdd');
    //Lấy nhóm khách hàng
    Route::post('get-customer-group', 'CustomerController@getCustomerGroup');
    Route::post('get-province', 'CustomerController@getProvince');
    Route::post('store', 'CustomerController@store');
    Route::post('update', 'CustomerController@update');

    Route::post('history-order', 'CustomerController@historyOrder');

    /**
     * Danh sách bình luận
     */
    Route::post('list-comment', 'CustomerController@listComment');

    /**
     * Tạo comment
     */
    Route::post('created-comment', 'CustomerController@createdComment');

    //Địa chỉ giao hàng
    Route::prefix('customer-contact')->group(function() {
        Route::post('store', 'CustomerContactController@store');
        Route::post('remove', 'CustomerContactController@remove');
        Route::post('set-default', 'CustomerContactController@setDefault');
        Route::post('update', 'CustomerContactController@update');


    });
});