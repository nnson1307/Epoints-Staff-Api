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

Route::middleware('auth')->prefix('product')->group(function() {

    Route::post('history', 'ProductController@getHistoryProducts');
    Route::post('get-all-products', 'ProductController@getAllProducts');
    //Lấy ds sản phẩm có phân trang
    Route::post('get-products', 'ProductController@getProducts');
    Route::post('product-detail', 'ProductController@getDetailProduct');
    Route::post('scan-product', 'ProductController@scanProduct');

    Route::prefix('product-category')->group(function () {
        Route::post('', 'ProductCategoryController@getProductCategories');
        Route::post('get-option', 'ProductCategoryController@getOption');
    });

    //Banner + san pham noi bat + san pham khuyen mai
    Route::post('general-info', 'ProductController@generalInfo');

    //Lấy ds sản phẩm cho màn hình home page
    Route::post('get-product-home', 'ProductController@getProductHome');
    Route::post('hot-keyword', 'ProductController@hotKeywords');
    //Sản phẩm yêu thích
    Route::post('like-unlike', 'ProductController@likeUnlikeAction');
    Route::post('list-product-like', 'ProductController@getListProductLikes');
    //Un active product khi không có image
    Route::post('un-active-product', 'ProductController@unActiveProduct');
});

Route::prefix('product-category')->group(function() {
    Route::post('get-product-category', 'ProductCategoryController@getProductCategoryETL');
});

Route::prefix('product')->group(function() {
    Route::post('get-product', 'ProductController@getProductETL');
});
