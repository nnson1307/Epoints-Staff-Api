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

Route::middleware('auth')->prefix('notification')->group(function () {
    Route::post('/', 'NotificationController@getNotifications');
    Route::post('detail', 'NotificationController@getNotificationDetail');
    Route::post('delete', 'NotificationController@deleteNotification');
    Route::post('count', 'NotificationController@countNotification');
    Route::post('clear-noti-new', 'NotificationController@clearNotificationNew');
    Route::post('read', 'NotificationController@readNotification');
    Route::post('read-all', 'NotificationController@readAllNotification');
});

Route::prefix('notification')->group(function () {
    Route::post('send-staff-notification', 'NotificationController@sendStaffNotification');
    //Gửi thông báo ko lưu data
    Route::post('send-notify-not-data', 'NotificationController@sendNotifyNotData');
});

