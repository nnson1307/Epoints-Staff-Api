<?php
/**
 * Created by PhpStorm
 * User: Mr Son
 * Date: 08-04-02020
 * Time: 10:24 AM
 */

namespace Modules\Notification\Repositories\Notification;


interface NotificationRepoInterface
{
    /**
     * Lấy danh sách thông báo
     * @param $input
     * @return mixed
     */
    public function getNotifications($input);

    /**
     * Chi tiết thông báo
     *
     * @param $input
     * @return mixed
     */
    public function getNotificationDetail($input);

    /**
     * Xóa thông báo
     *
     * @param $input
     * @return mixed
     */
    public function deleteNotification($input);

    /**
     * Gửi thông báo
     *
     * @param $input
     * @return mixed
     */
    public function sendNotification($input);

    /**
     * Đếm số lượng thông báo mới
     *
     * @return mixed
     */
    public function countNotification();

    /**
     * Clear thông báo mới
     *
     * @return mixed
     */
    public function clearNotificationNew();

    /**
     * Đọc thông báo
     *
     * @param $input
     * @return mixed
     */
    public function readNotification($input);

    /**
     * Đọc tất cả thông báo
     *
     * @return mixed
     */
    public function readAllNotification();

    /**
     * Gửi thông báo nhân viên
     *
     * @param $input
     * @return mixed
     */
    public function sendStaffNotification($input);

    /**
     * Gửi thông báo nhân viên không lưu dữ liệu
     *
     * @param $input
     * @return mixed
     */
    public function sendNotifyNotData($input);
}