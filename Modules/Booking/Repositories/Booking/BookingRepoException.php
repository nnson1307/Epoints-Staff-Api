<?php
/**
 * Created by PhpStorm   .
 * User: Mr Son
 * Date: 2020-02-17
 * Time: 2:32 PM
 * @author SonDepTrai
 */

namespace Modules\Booking\Repositories\Booking;


use MyCore\Repository\RepositoryExceptionAbstract;

class BookingRepoException extends RepositoryExceptionAbstract
{
    const GET_SETTING_OTHER_FAILED = 0;
    const GET_TIME_WORK_FAILED = 1;
    const GET_STAFF_FAILED = 2;
    const GET_SERViCE_LIST_FAILED = 3;
    const CHECK_APPOINTMENT_FAILED = 4;
    const STORE_APPOINTMENT_FAILED = 5;
    const UPDATE_APPOINTMENT_FAILED = 6;
    const GET_TIME_BOOKING_FAILED = 7;
    const GET_BOOKING_HISTORY_LIST_FAILED = 8;
    const GET_BOOKING_HISTORY_DETAIL_FAILED = 9;
    const GET_STATUS_FAILED = 10;
    const CANCEL_APPOINTMENT_FAILED = 11;
    const RE_BOOKING_FAILED = 12;
    const GET_PRICE_SERVICE_FAILED = 13;
    const GET_ROOM_FAILED = 14;
    const GET_APPOINTMENT_SOURCE_FAILED = 15;
    const GET_LIST_FAILED = 16;
    const GET_LIST_RANGE_TIME_FAILED = 17;

    public function __construct(int $code = 0, string $message = "")
    {
        parent::__construct($message ? : $this->transMessage($code), $code);
    }

    protected function transMessage($code)
    {
        switch ($code)
        {
            case self::GET_SETTING_OTHER_FAILED :
                return __('Lấy danh sách cấu hình khác thất bại.');

            case self::GET_TIME_WORK_FAILED :
                return __('Lấy thời gian làm việc thất bại.');

            case self::GET_STAFF_FAILED :
                return __('Lấy danh sách kỹ thuật viên thất bại.');

            case self::GET_SERViCE_LIST_FAILED :
                return __('Lấy danh sách dịch vụ thất bại.');

            case self::CHECK_APPOINTMENT_FAILED :
                return __('Kiểm tra số lần đặt lịch thất bại.');

            case self::STORE_APPOINTMENT_FAILED :
                return __('Thêm lịch hẹn thất bại.');

            case self::UPDATE_APPOINTMENT_FAILED :
                return __('Cập nhật lịch hẹn thất bại.');

            case self::GET_TIME_BOOKING_FAILED :
                return __('Lấy thời gian đặt lịch thất bại.');

            case self::GET_BOOKING_HISTORY_LIST_FAILED :
                return __('Lấy danh sách lịch sử đặt lịch thất bại.');

            case self::GET_BOOKING_HISTORY_DETAIL_FAILED :
                return __('Lấy chi tiết lịch sử đặt lịch thất bại.');

            case self::GET_STATUS_FAILED :
                return __('Lấy trạng thái lịch hẹn thất bại.');

            case self::CANCEL_APPOINTMENT_FAILED :
                return __('Hủy lịch hẹn thất bại.');

            case self::RE_BOOKING_FAILED :
                return __('Đặt lịch hẹn lại thất bại.');

            case self::GET_PRICE_SERVICE_FAILED :
                return __('Lấy giá dịch vụ thất bại.');

            case self::GET_ROOM_FAILED :
                return __('Lấy phòng phục vụ thất bại.');

            case self::GET_APPOINTMENT_SOURCE_FAILED :
                return __('Lấy nguồn lịch hẹn thất bại.');

            case self::GET_LIST_FAILED :
                return __('Lấy danh sách lịch hẹn thất bại.');

            case self::GET_LIST_RANGE_TIME_FAILED :
                return __('Lấy danh sách lịch hẹn theo khung giờ thất bại.');

            default:
                return null;
        }
    }
}