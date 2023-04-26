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

Route::middleware('auth')->prefix('manage-work')->group(function () {

    /**
     * Tổng công việc trang home
     */
    Route::post('total-home', 'ManageWorkController@totalWork');

    Route::post('total-home-support', 'ManageWorkController@totalWorkSupport');

    /**
     * Tổng quan công việc
     */
    Route::post('job-overview', 'ManageWorkController@jobOverview');

    //Tổng quan công việc (V2)
    Route::post('job-overview-v2', 'ManageWorkController@jobOverviewV2');

    /**
     * Danh sách chi nhánh
     */
    Route::post('list-branch', 'ManageWorkController@listBranch');

    /**
     * Danh sách phòng ban
     */
    Route::post('list-department', 'ManageWorkController@listDepartment');

    /**
     * Tạo nhắc nhở
     */
    Route::post('create-reminder', 'ManageWorkController@createReminder');

    /**
     * Danh sách công việc
     */
    Route::post('list-work', 'ManageWorkController@listWork');

    /**
     * Danh sách công việc
     */
    Route::post('list-work-parent', 'ManageWorkController@listWorkParent');

    /**
     * Chi tiết công việc
     */
    Route::post('work-detail', 'ManageWorkController@workDetail');

    /**
     * Danh sách công việc
     */
    Route::post('list-work-parent', 'ManageWorkController@listWorkParent');

    /**
     * Duyệt công việc
     */
    Route::post('work-approve', 'ManageWorkController@workApprove');

    /**
     * Danh sách bình luận
     */
    Route::post('list-comment', 'ManageWorkController@listComment');

    /**
     * Tạo comment
     */
    Route::post('created-comment', 'ManageWorkController@createdComment');

    /**
     * Danh sách nhắc nhở
     */
    Route::post('list-remind', 'ManageWorkController@listRemind');

    /**
     * Danh sách file trong hồ sơ
     */
    Route::post('list-file', 'ManageWorkController@listFile');

    /**
     * Danh sách lịch sử
     */
    Route::post('list-history', 'ManageWorkController@listHistory');

    /**
     * Thêm công việc
     */
    Route::post('add-work', 'ManageWorkController@addWork');

    /**
     * Chỉnh sửa công việc
     */
    Route::post('edit-work', 'ManageWorkController@editWork');

    /**
     * Thêm dự án
     */
    Route::post('add-project', 'ManageWorkController@addProject');

    /**
     * Danh sách dự án
     */
    Route::post('list-project', 'ManageWorkController@listProject');

    /**
     * Thêm loại công việc
     */
    Route::post('add-type-work', 'ManageWorkController@addTypeWork');

    /**
     * Danh sách tags
     */
    Route::post('list-tags', 'ManageWorkController@listTags');

    /**
     * Danh sách nhân viên
     */
    Route::post('list-staff', 'ManageWorkController@listStaff');

    /**
     * Upload file
     */
    Route::post('upload-file', 'ManageWorkController@uploadFile');

    /**
     * Danh sách hồ sơ
     */
    Route::post('list-document', 'ManageWorkController@listDocument');

    /**
     * Cập nhật file hồ sơ
     */
    Route::post('upload-file-document', 'ManageWorkController@uploadFileDocument');

    /**
     * Cập nhật tag trong công việc
     */
    Route::post('update-work-tag', 'ManageWorkController@updateWorkTag');

    /**
     * Danh sách tác vụ con
     */
    Route::post('list-child-work', 'ManageWorkController@listChildWork');

    /**
     * Chỉnh sửa việc lặp lại
     */
    Route::post('edit-repeat-work', 'ManageWorkController@editRepeatWork');

    /**
     * Công việc của tôi search
     */
    Route::post('my-work-search-overdue', 'ManageWorkController@myWorkSearchOverdue');

    Route::post('my-work-search', 'ManageWorkController@myWorkSearch');

    /**
     * Công việc của tôi
     */
    Route::post('my-work', 'ManageWorkController@myWork');

    /**
     * Công việc của tôi tab tôi giao
     */
    Route::post('my-assign-work', 'ManageWorkController@myAssignWork');

    /**
     * Danh sách nhắc nhở của tôi
     */
    Route::post('my-remind-work', 'ManageWorkController@myRemindWork');

    /**
     * Xoá nhắc nhở
     */
    Route::post('delete-remind', 'ManageWorkController@deleteRemind');

    /**
     * Danh sách trạng thái
     */
    Route::post('list-status', 'ManageWorkController@listStatus');

    /**
     * Xoá bình luận
     */
    Route::post('delete-comment', 'ManageWorkController@deleteComment');

    /**
     * Cập nhật nhân viên liên quan
     */
    Route::post('updated-staff-support', 'ManageWorkController@updateStaffSupport');

    /**
     * Xoá công việc
     */
    Route::post('delete-work', 'ManageWorkController@deleteWork');

    Route::post('list-type-work', 'ManageWorkController@listTypeWork');

    /**
     * Cập nhật nhanh công việc
     */
    Route::post('quick-update-work', 'ManageWorkController@quickUpdateWork');

    /**
     * Danh sách khách hàng
     */
    Route::post('list-customer', 'ManageWorkController@listCustomer');

    /**
     * Thêm tag mới
     */
    Route::post('add-tag', 'ManageWorkController@addTag');

    /**
     * Xoá file hồ sơ
     */
    Route::post('delete-document-file', 'ManageWorkController@deleteDocumentFile');

    /**
     * Danh sách công việc cần duyệt
     */
    Route::post('list-work-approve', 'ManageWorkController@getListWorkApprove');

    /**
     * Loại khách hàng
     */
    Route::post('type-customer', 'ManageWorkController@typeCustomer');

    //DS phòng ban
    Route::post('get-department', 'ManageWorkController@getDepartment');
    //Thêm vị trí công việc
    Route::post('create-location', 'ManageWorkController@createLocation');
    //Lấy vị trí làm việc
    Route::post('list-location', 'ManageWorkController@listLocation');
    //Xoá vị trí
    Route::post('remove-location', 'ManageWorkController@removeLocation');

    /**
     * Danh sách trạng thái (màn hình bộ lọc)
     */
    Route::post('list-status-v2', 'ManageWorkController@listStatusV2');
});
Route::prefix('manage-work')->group(function () {
    Route::post('send-noti-work', 'ManageWorkController@staffNotification');
});
