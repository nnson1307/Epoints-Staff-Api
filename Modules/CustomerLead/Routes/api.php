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

Route::middleware('auth')->prefix('customer-deals')->group(function () {
    /**
     * Danh sách bình luận
     */
    Route::post('list-comment', 'CustomerDealsController@listComment');

    /**
     * Tạo comment
     */
    Route::post('created-comment', 'CustomerDealsController@createdComment');
});
Route::middleware('auth')->prefix('customer-lead')->group(function () {
    /**
     * Danh sách bình luận
     */
    Route::post('list-comment', 'CustomerLeadController@listComment');

    /**
     * Tạo comment
     */
    Route::post('created-comment', 'CustomerLeadController@createdComment');

    Route::prefix('customer-lead')->group(function () {
        //        them khach hang tiem nang
        Route::post('get-customer-option', 'CustomerLeadController@getCustomerOption');
        Route::post('get-pipeline', 'CustomerLeadController@getPipeline');
        Route::post('get-journey', 'CustomerLeadController@getJourney');
        Route::post('get-province', 'CustomerLeadController@getProvince');
        Route::post('get-district', 'CustomerLeadController@getDistrict');
        Route::post('get-allocator', 'CustomerLeadController@getAllocator');
        Route::post('get-ward', 'CustomerLeadController@getWard');
        Route::post('get-status-work', 'CustomerLeadController@getStatusWork');
        Route::post('get-list-business', 'CustomerLeadController@getListBusiness');
        Route::post('get-type-work', 'CustomerLeadController@getTypeWork');
        Route::post('add-tag', 'CustomerLeadController@addTag');
        Route::post('list-business-areas', 'CustomerLeadController@listBusinessAreas');
        Route::post('add-business-areas', 'CustomerLeadController@addBusinessAreas');
        ///chức vụ
        Route::post('position', 'CustomerLeadController@getPosition');

        ///lưu thông tin chăm sóc khách hàng(lưu công việc)
        Route::post('save-work', 'CustomerLeadController@saveWork');

        //tao KHTN
        Route::post('add-lead', 'CustomerLeadController@addLead');
        //thêm thông tin người liên hệ đối với KHTN là Bussiness
        Route::post('add-contact', 'CustomerLeadController@addContact');
        //danh sach KHTN
        Route::post('list-customer-lead', 'CustomerLeadController@getListCustomerLead');

        //chi tiet KHTN
        Route::post('detail-lead', 'CustomerLeadController@getDetailLead');
        ///chi tiet KHTN=thông tin deal
        Route::post('detail-lead-info-deal', 'CustomerLeadController@detailLeadInfoDeal');
        //danh sách tin nhắn trao đổi lead
        Route::post('message-lead', 'CustomerLeadController@getListMessageLead');
        //tạo comment lead
        Route::post('create-message-lead', 'CustomerLeadController@createMessageLead');
        //xóa comment lead
        Route::post('delete-message-lead', 'CustomerLeadController@deleteMessageLead');
        ///danh sách liên hệ đối với khách hàng là bussiness
        Route::post('contact-list', 'CustomerLeadController@getContactList');
        ///chi tiet KHTN-chăm sóc KH
        Route::post('care-lead', 'CustomerLeadController@getCareLead');
        //chi tiet KHTN
        Route::post('detail', 'CustomerLeadController@getDetail');
        //chinh sua lead
        Route::post('update-lead', 'CustomerLeadController@updateLead');
        //xoa lead
        Route::post('delete-lead', 'CustomerLeadController@deleteLead');
        //Phân bổ và thu hồi lead
        Route::post('assign-revoke-lead', 'CustomerLeadController@assignRevokeLead');

        /// tao co hoi ban hang
        Route::post('get-deal-name', 'CustomerLeadController@getDealName');
        Route::post('get-branch', 'CustomerLeadController@getBranch');
        Route::post('get-customer', 'CustomerLeadController@getCustomer');
        Route::post('get-order-source', 'CustomerLeadController@getOrderSource');

        Route::post('add-deals', 'CustomerDealsController@addDeals');
        //danh sach deal
        Route::post('list-deal', 'CustomerDealsController@getListDeal');

        //chi tiet deal
        Route::post('detail-deal', 'CustomerDealsController@getDetailDeal');
        ///lịch sử đơn hàng của deal
        Route::post('order-history', 'CustomerDealsController@getOrderHistory');
        ///chi tiết deals-chăm sóc KH
        Route::post('care-deal', 'CustomerDealsController@getCareDeal');
        //danh sách tin nhắn trao đổi deal
        Route::post('message-deal', 'CustomerDealsController@getListMessageDeal');
        //tạo comment deal
        Route::post('create-message-deal', 'CustomerDealsController@createMessageDeal');
        //xóa comment deal
        Route::post('delete-message-deal', 'CustomerDealsController@deleteMessageDeal');
        //chinh sua deal
        Route::post('update-deal', 'CustomerDealsController@updateDeal');
        //xoa deal
        Route::post('delete-deal', 'CustomerDealsController@deleteDeal');
        //Lấy tag KHTN
        Route::post('get-tag', "CustomerLeadController@getTag");
        //Phân bổ và thu hồi deal
        Route::post('assign-revoke-deal', 'CustomerDealsController@assignRevokeDeal');
    });
});


Route::prefix('customer-lead')->group(function () {
    Route::post('add-brand-lead', 'CustomerLeadController@addLead');
});
