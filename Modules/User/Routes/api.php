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

Route::middleware('api')->group(function () {
    Route::post('check-version', 'DeviceController@checkVersionAction');
});

Route::prefix('v2/user')->group(function () {
    Route::post('login', 'AuthenController@loginV2Action');
    Route::middleware('auth')->group(function () {
        Route::post('permission', 'AuthenController@getInfoPermissionAction');
    });
});
Route::prefix('user')->group(function () {
    Route::post('register', 'RegisterController@infoAction');
    Route::post('verify-otp', 'RegisterController@verifyOtpAction');
    //Đăng nhập username + password
    Route::post('login', 'AuthenController@loginAction');

    Route::post('refresh-token', 'AuthenController@refreshTokenAction');
    //Đăng nhập fb/gg/zalo
    Route::post('login-service', 'AuthenController@loginService');

    Route::prefix('forgot-password')->group(function () {
        Route::post('send-otp', 'ForgotPasswordController@sendOtpAction');
        Route::post('verify-otp', 'ForgotPasswordController@verifyOtpAction');
    });
    //Danh sách đầu số được phép đăng kí
    Route::post('get-service-num', 'RegisterController@getServiceNumList');
    //Nhân viên giao hàng
    Route::post('login-carrier', 'AuthenController@loginCarrierAction');
    Route::post('refresh-token-carrier', 'AuthenController@refreshTokenCarrierAction');
    //Đăng nhập nhanh
    Route::post('quick-login', 'AuthenController@quickLogin');
    //Test send sms
    Route::post('test-send-sms', 'UserInfoController@testSendSms');
    //Đăng ký device token trên portal
    Route::post('register-device-token', 'AuthenController@registerDeviceToken');
});

Route::middleware('auth')->prefix('user')->group(function () {
    Route::post('logout', 'AuthenController@logoutAction');

    //Đổi mật khẩu mới
    Route::prefix('forgot-password')->group(function () {
        Route::post('update', 'ForgotPasswordController@updatePasswordAction');
    });

    //Lấy thông tin quyền
    Route::post('get-permission', 'AuthenController@getPermission');
    //    Upload File
    Route::post('upload-avatar', 'AuthenController@uploadAvatarAction');

    //Update image
    Route::post('upload-file', 'AuthenController@uploadFile');

    //Delete user
    Route::post('delete', 'AuthenController@deleteAction');

    //upload ảnh app truyền links
    Route::post('upload-image-by-app-links', 'AuthenController@uploadImageByAppLinks');
});

//Lấy ds brand theo client_key
Route::post('get-brand', 'BrandController@getBrand');
