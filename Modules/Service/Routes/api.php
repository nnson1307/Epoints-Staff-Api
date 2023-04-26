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

Route::middleware('auth')->prefix('service')->group(function() {
    //Danh sách dịch vụ khi bán hàng
    Route::post('get-services', 'ServiceController@getServices');
    //Danh sách dịch vụ theo chi nhánh chính
    Route::post('get-service-representative', 'ServiceController@getServiceRepresentative');
    Route::post('detail', 'ServiceController@getDetail');
    Route::post('history', 'ServiceController@getHistoryServices');

    //Banner + dich vu noi bat + dich vu khuyen mai
    Route::post('general-info', 'ServiceController@generalInfo');

    Route::prefix('service-category')->group(function () {
        Route::post('', 'ServiceCategoryController@getServiceCategories');
        Route::post('get-option', 'ServiceCategoryController@getOption');
    });
    //Dịch vụ yêu thích
    Route::post('like-unlike', 'ServiceController@likeUnlikeService');
    Route::post('list-product-like', 'ServiceController@getListServiceLikes');
});