<?php
/**
 * Created by PhpStorm
 * User: Mr Son
 * Date: 08-04-02020
 * Time: 10:24 AM
 */

namespace Modules\Notification\Repositories\Notification;


use MyCore\Repository\RepositoryExceptionAbstract;

class NotificationRepoException extends RepositoryExceptionAbstract
{
    const GET_NOTIFICATION_LIST_FAILED = 0;
    const COUNT_NOTIFICATION_FAILED = 1;
    const NOTIFICATION_DELETE_FAILED = 2;
    const NOTIFICATION_NOT_FOUND = 3;
    const SEND_NOTIFICATION_FAILED = 4;
    const CLEAR_NOTIFICATION_FAILED = 5;
    const READ_NOTIFICATION_FAILED = 6;
    const READ_ALL_NOTIFICATION_FAILED = 7;

    public function __construct(int $code = 0, string $message = "")
    {
        parent::__construct($message ? : $this->transMessage($code), $code);
    }

    protected function transMessage($code)
    {
        switch ($code)
        {
            case self::GET_NOTIFICATION_LIST_FAILED :
                return __('Lấy danh thông báo thất bại.');

            case self::COUNT_NOTIFICATION_FAILED :
                return __('Lấy số lượng thông báo mới thất bại.');

            case self::NOTIFICATION_DELETE_FAILED :
                return __('Xóa thông báo thất bại.');

            case self::NOTIFICATION_NOT_FOUND :
                return __('Thông báo không tồn tại.');

            case self::SEND_NOTIFICATION_FAILED :
                return __('Gửi thông báo thất bại.');

            case self::CLEAR_NOTIFICATION_FAILED :
                return __('Xóa thông báo mới thất bại.');

            case self::READ_NOTIFICATION_FAILED :
                return __('Đọc thông báo thất bại.');

            case self::READ_ALL_NOTIFICATION_FAILED :
                return __('Đọc tất cả thông báo thất bại.');

            default:
                return null;
        }
    }
}