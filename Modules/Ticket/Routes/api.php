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

Route::middleware('auth')->prefix('ticket')->group(function() {
//    Tổng ticket trang home
    Route::post('total-home', 'TicketController@totalTicket');

//    Danh sách ticket , chưa phân công và đã phân công
    Route::post('my-ticket', 'TicketController@myTicket');

//    Danh sách ticket
    Route::post('list-ticket', 'TicketController@listTicket');

//    Thông tin ticket chưa hoàn thành
    Route::post('ticket-not-completed', 'TicketController@ticketNotCompleted');

//    Chi tiết ticket
    Route::post('ticket-detail', 'TicketController@ticketDetail');

//    Cập nhật ticket
    Route::post('ticket-edit', 'TicketController@ticketEdit');

//    Tạo phiếu yêu cầu
    Route::post('add-request-form', 'TicketController@addRequestForm');

//    Xoá phiếu yêu cầu
    Route::post('delete-request-form', 'TicketController@deleteRequestForm');

//    Chỉnh sửa phiếu yêu cầu
    Route::post('edit-request-form', 'TicketController@editRequestForm');

//    Thông tin vật tư
    Route::post('info-materials', 'TicketController@infoMaterials');

//    Chi tiết vật tư
    Route::post('info-materials-detail', 'TicketController@infoMaterialsDetail');

//    Chi tiết đánh giá
    Route::post('rating-detail', 'TicketController@ratingDetail');

//    Danh sách lịch sử
    Route::post('history', 'TicketController@history');

//    Danh sách ảnh
    Route::post('image', 'TicketController@image');

//    Thêm ảnh
    Route::post('add-image', 'TicketController@addImage');

//    Thông tin biên bản nghiệm thu
    Route::post('acceptance-record', 'TicketController@acceptanceRecord');

//    Tạo biên bản nghiệm thu
    Route::post('acceptance-record-create', 'TicketController@acceptanceRecordCreate');

//    Chỉnh sửa biên bản nghiệm thu
    Route::post('acceptance-record-edit', 'TicketController@acceptanceRecordEdit');

//    Tìm kiếm vật tư
    Route::post('search-materials', 'TicketController@searchMaterials');

//    Danh sách nhóm yêu cầu
    Route::post('issue-group', 'TicketController@issueGroup');

//    Danh sách yêu cầu
    Route::post('issue', 'TicketController@issue');

//    Danh sách trạng thái
    Route::post('list-status', 'TicketController@listStatus');

//    Danh sách queue có quyền xem
    Route::post('list-queue', 'TicketController@listQueue');

//    Upload File
    Route::post('upload-file', 'TicketController@uploadFile');

//    Lấy danh sách nhân viên theo queue
    Route::post('get-list-staff-by-queue', 'TicketController@getListStaffByQueue');

    //Lấy ds công việc của ticket
    Route::post('list-task-of-ticket', 'TicketController@listTaskOfTicket');

    //Lấy ds mức độ ưu tiên
    Route::post('list-priority', 'TicketController@getListPriority');

    //Tạo mới ticket
    Route::post('ticket-add', 'TicketController@ticketAdd');

    //Lấy danh sách staff theo queue
    Route::post('get-staff-queue', 'TicketController@loadStaffByQueue');

    //Tạo danh sách queue
    Route::post('get-list-queue', 'TicketController@getListQueue');

     /**
     * Danh sách bình luận
     */
    Route::post('list-comment', 'TicketController@listComment');

    /**
     * Tạo comment
     */
    Route::post('created-comment', 'TicketController@createdComment');
     //Lấy ds location của ticket
     Route::prefix('location')->group(function() {
        Route::post('list', 'TicketController@getLocationTicket');
        Route::post('add', 'TicketController@addLocationTicket');
        Route::post('delete', 'TicketController@deleteLocationTicket');
    });
    

});