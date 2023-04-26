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

Route::middleware('auth')->prefix('project-management')->group(function() {
//Route::prefix('project-management')->group(function() {
    //ds trang thai
    Route::post('get-status', 'ProjectManagementController@getStatus');
    //ds nguoi quan tri
    Route::post('get-manage', 'ProjectManagementController@getManage');
    //ds phong ban
    Route::post('get-department', 'ProjectManagementController@getDepartment');
    //ds nhan vien
    Route::post('get-staffs', 'ProjectManagementController@getStaffs');
    //loại khach hang
    Route::post('get-customer-type', 'ProjectManagementController@getCustomerType');
    //danh sach khach hang
    Route::post('get-customer', 'ProjectManagementController@getCustomer');
    //danh sach tag
    Route::post('get-tag', 'ProjectManagementController@getTag');
    //danh sach chi nhanh
    Route::post('get-branch', 'ProjectManagementController@getBranch');
    //quyen truy cap
    Route::post('get-permission', 'ProjectManagementController@getPermission');
    //lay vai tro
    Route::post('get-role', 'ProjectManagementController@getRole');
    //danh sach chuc vu
    Route::post('get-staff-title', 'ProjectManagementController@getStaffTitle');
    //danh sách loại công việc
    Route::post('type-work', 'ProjectManagementController@getTypeWork');
    //danh sach du an
    Route::post('list-project', 'ProjectManagementController@listProject');
    //chinh sua du an
    Route::post('edit-project', 'ProjectManagementController@editProject');
    //thêm du an
    Route::post('add-project', 'ProjectManagementController@addProject');
    //xoa du an
    Route::post('delete-project', 'ProjectManagementController@deleteProject');
    //trang thai xoa
    Route::post('is-delete', 'ProjectManagementController@isDelete');
    //danh sach hop dong
    Route::post('list-contract', 'ProjectManagementController@listContract');

    //thong tin du an
    Route::post('project-info', 'ProjectManagementController@projectInfo');

    ///thông tin dự án --tab phân tích lượng tổng thành viên-công việc-đang thực hiện-chưa thực hiện-hoàn thành-đã đóng-quán hạn
    Route::post('statistical-tab', 'ProjectManagementController@statisticalTab');
    //thông tin dự án --thống kê số lượng tổng thành viên-công việc-đang thực hiện-chưa thực hiện-hoàn thành-đã đóng-quán hạn
    Route::post('statistical', 'ProjectManagementController@statistical');
    //cập nhật trạng thái dự án
    Route::post('update-project-status', 'ProjectManagementController@projectStatus');

    //thêm vấn đề dự án
    Route::post('add-issue', 'ProjectManagementController@addIssue');
    //thông tin dự án --danh sách vấn đề dự án
    Route::post('list-issue', 'ProjectManagementController@listIssue');

    //danh sách chi tiết giai đoạn
    Route::post('phase-detail', 'ProjectManagementController@phaseDetail');
    //thông tin dự án --danh sách công việc + search
    Route::post('work-list', 'ProjectManagementController@workList');

    //lấy thông tin báo cáo
    Route::post('report-information', 'ProjectManagementController@reportInformation');

    //lich du hoat dong
    Route::post('activities-history', 'ProjectManagementController@activitiesHistory');
    //danh sach tai lieu
    Route::post('list-document', 'ProjectManagementController@listDocument');
    //thêm tài liệu dự án
    Route::post('add-document', 'ProjectManagementController@addDocument');
    //Xóa tài liệu dự án
    Route::post('delete-document', 'ProjectManagementController@deleteDocument');

    //thanh vien du an
    Route::post('member-project', 'ProjectManagementController@memberProject');
    //them thanh vien
    Route::post('add-member', 'ProjectManagementController@addMember');
    //chinh sua thanh vien
    Route::post('edit-member', 'ProjectManagementController@editMember');
    //xoa thanh vien
    Route::post('delete-member', 'ProjectManagementController@deleteMember');

    //thêm bình luận dự án
    Route::post('add-comment', 'ProjectManagementController@addComment');
    //danh sách lịch sử bình luận
    Route::post('history-comment', 'ProjectManagementController@historyComment');

    //Tạo nhắc nhở
    Route::post('create-reminder', 'ProjectManagementController@createReminder');
    //Danh sách nhắc nhở
    Route::post('list-remind', 'ProjectManagementController@listRemind');

    //danh sách phiếu thu chi
    Route::post('list-expenditure', 'ProjectManagementController@listExpenditure');

});