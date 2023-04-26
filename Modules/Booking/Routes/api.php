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

Route::middleware('auth')->prefix('booking')->group(function() {

    Route::post('province', 'AddressController@getProvinces');
    Route::post('district', 'AddressController@getDistricts');
    Route::post('province-full', 'AddressController@getProvinceFull');
    Route::post('ward', 'AddressController@getWard');

    Route::post('history', 'BookingController@getHistoryAppointments');
    //DS lịch hẹn theo ngày/tuần/tháng
    Route::post('list-date-week-month', 'BookingController@getListByDayWeekMonth');
    //Ds lịch hẹn theo khung giờ
    Route::post('list-date-range-time', 'BookingController@getListRangeTime');
    //Chi tiết lịch hẹn
    Route::post('detail', 'BookingController@historyAppointmentDetail');

    //Danh sách nhân viên
    Route::post('get-staff', 'BookingController@getStaffs');
    //Kiểm tra số lần đặt lịch của KH
    Route::post('check-appointment', 'BookingController@checkAppointment');
    Route::post('store', 'BookingController@store');
    Route::post('update', 'BookingController@update');

    //Lấy thời gian đặt lịch
    Route::post('time-booking', 'BookingController@timeBooking');
    Route::post('cancel', 'BookingController@cancelAction');
    Route::post('re-booking', 'BookingController@reBooking');
    //Lấy giá dv theo ngày đặt lịch
    Route::post('get-price-service', 'BookingController@getPriceService');

    //Lấy trạng thái lịch hẹn
    Route::post('get-status', 'BookingController@getStatusBooking');
    //Danh sách phòng phục vụ
    Route::post('get-room', 'BookingController@getRoom');
    //Nguồn lịch hẹn
    Route::post('get-appointment-source', 'BookingController@getAppointmentSource');
    //DS LH của khách hàng
    Route::post('list-booking-customer', 'BookingController@getListCustomer');
});