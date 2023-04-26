<?php


namespace Modules\TimeKeeping\Repositories;


use MyCore\Repository\RepositoryExceptionAbstract;

class TimeKeepingRepoException extends RepositoryExceptionAbstract
{
    const STAFF_NOT_HAVE_TIME_WORKING = 1;
    const EMPTY_TIME_WORKING = 2;
    const HAVE_BEEN_CHECK_IN = 3;
    const HAVE_BEEN_CHECK_OUT = 4;
    const PAYLOAD_HAVE_BEEN_CHANGED = 5;
    const WIFI_IP_HAVE_BEEN_CHANGED = 6;
    const WIFI_IP_NOT_CONFIG = 7;
    const WIFI_CHECK_IN_NOT_CORRECT = 8;
    const WIFI_CHECK_OUT_NOT_CORRECT = 9;
    const GET_DAY_HOLIDAY_FAILED = 10;

    public function __construct(int $code = 0, string $message = "")
    {
        parent::__construct($message ?: $this->transMessage($code), $code);
    }

    protected function transMessage($code)
    {
        switch ($code) {
            case self::STAFF_NOT_HAVE_TIME_WORKING :
                return __('Hôm nay không có lịch làm việc, hoặc trong thời gian nghĩ lễ.');
            case self::EMPTY_TIME_WORKING :
                return __('Không tìm thấy ca làm việc.');
            case self::HAVE_BEEN_CHECK_IN :
                return __('Ca đã được check in.');
            case self::HAVE_BEEN_CHECK_OUT :
                return __('Ca đã được check out.');
            case self::PAYLOAD_HAVE_BEEN_CHANGED :
                return __('Dữ liệu đã bị thay đổi.');
            case self::WIFI_IP_HAVE_BEEN_CHANGED :
                return __('Địa chỉ IP wifi đã bị thay đổi.');
            case self::WIFI_IP_NOT_CONFIG :
                return __('Wifi chưa được cấu hình.');
            case self::WIFI_CHECK_IN_NOT_CORRECT :
                return __('Địa điểm check in không đúng.');
            case self::WIFI_CHECK_OUT_NOT_CORRECT :
                return __('Địa điểm check out không đúng.');
            case self::GET_DAY_HOLIDAY_FAILED :
                return __('Lấy ngày lễ thất bại.');
            default:
                return null;
        }
    }
}