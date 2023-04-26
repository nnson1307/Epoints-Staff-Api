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

Route::middleware('auth')->prefix('order')->group(function() {
    //Danh sách đơn hàng
    Route::post('/', 'OrderController@getOrders');
    Route::post('detail', 'OrderController@getDetailOrder');
    //Thêm đơn hàng
    Route::post('store', 'OrderController@store');
    //Thêm đơn hàng (v2)
    Route::post('store-v2', 'OrderController@storeV2');

    Route::post('get-discount-member', 'OrderController@getDiscountMember');
    Route::post('/branch', 'OrderController@getOptionBranch');
    Route::post('cancel', 'OrderController@cancelAction');
    //Lấy phương thức thanh toán
    Route::post('payment-method', 'OrderController@getPaymentMethod');
    Route::post('re-order', 'OrderController@reOrder');
    Route::post('check-inventory', 'OrderController@checkInventory');
    Route::post('order-payment', 'OrderController@orderPayment');
    Route::post('check-transport-charge', 'OrderController@checkTransportCharge');

    // check quà tặng khi lên đơn hàng
    Route::post('check-promotion-gift', 'OrderController@checkPromotionGift');
    //Upload ảnh trước/sau khi sử dụng
    Route::post('upload-image', 'OrderController@uploadImage');
    Route::post('remove-image', 'OrderController@removeImage');
    //Lấy mã giảm giá
    Route::post('get-voucher', 'OrderController@getVoucher');
    //Chỉnh sửa đơn hàng
    Route::post('update', 'OrderController@update');
    //Chỉnh sửa đ hàng (v2)
    Route::post('update-v2', 'OrderController@updateV2');

    //Tạo qr code thanh toán vn pay
    Route::post('create-qr-code', 'OrderController@createQrCodeVnPay');
    //Lấy trạng thái thanh toán VN PAY
    Route::post('get-status-vn-pay', 'OrderController@getStatusVnPay');

    //Lấy phương thức vận chuyển
    Route::post('get-transport-method', 'OrderController@getTransportMethod');
    Route::prefix('print')->group(function() {
        Route::post('', 'OrderController@getPrintBillAction');
        Route::post('devices', 'OrderController@getPrintBillDeviceAction');
    });
});

Route::prefix('order')->group(function() {
    Route::match(['get', 'post'], 'payment-cancel', 'OrderController@paymentCancel')->name('order.payment-cancel');
    Route::match(['get', 'post'], 'payment-success', 'OrderController@paymentSuccess')->name('order.payment-success');
    Route::match(['get', 'post'], 'payment-fail', 'OrderController@paymentFail')->name('order.payment-fail');
    Route::match(['get', 'post'], 'cancel-transaction', 'OrderController@cancelTransactionPayment')->name('order.cancel-transaction');
});