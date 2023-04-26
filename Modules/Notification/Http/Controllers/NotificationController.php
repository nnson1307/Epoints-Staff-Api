<?php

namespace Modules\Notification\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

use Modules\Notification\Http\Requests\Notification\GetListRequest;
use Modules\Notification\Http\Requests\Notification\NotificationDeleteRequest;
use Modules\Notification\Http\Requests\Notification\NotificationListRequest;
use Modules\Notification\Http\Requests\Notification\NotificationDetailRequest;
use Modules\Notification\Http\Requests\Notification\ReadNotificationRequest;
use Modules\Notification\Http\Requests\Notification\SendNotificationRequest;
use Modules\Notification\Http\Requests\Notification\SendNotifyNotDataRequest;
use Modules\Notification\Http\Requests\Notification\SendStaffNotificationRequest;
use Modules\Notification\Repositories\Notification\NotificationRepoException;
use Modules\Notification\Repositories\Notification\NotificationRepoInterface;

class NotificationController extends Controller
{
    protected $notification;

    public function __construct(
        NotificationRepoInterface $notification
    )
    {
        $this->notification = $notification;
    }

    /**
     * Lấy danh sách thông báo theo filter
     *
     * @param NotificationListRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getNotifications(NotificationListRequest $request)
    {
        try {
            $data = $this->notification->getNotifications($request->all());

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (NotificationRepoException $ex) {
            return $this->responseJson(CODE_ERROR, $ex->getMessage());
        }
    }

    /**
     * Lấy chi tiết thông báo
     *
     * @param NotificationDetailRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getNotificationDetail(NotificationDetailRequest $request)
    {
        try {
            $data = $this->notification->getNotificationDetail($request->all());

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (NotificationRepoException $ex) {
            return $this->responseJson(CODE_ERROR, $ex->getMessage());
        }
    }

    /**
     * Xóa thông báo
     *
     * @param NotificationDeleteRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteNotification(NotificationDeleteRequest $request)
    {
        try {
            $this->notification->deleteNotification($request->all());

            return $this->responseJson(CODE_SUCCESS, __('Xóa thông báo thành công.'), null);
        } catch (NotificationRepoException $ex) {
            return $this->responseJson(CODE_ERROR, $ex->getMessage());
        }
    }

    /**
     * Gửi thông báo
     *
     * @param SendNotificationRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendNotification(SendNotificationRequest $request)
    {
        try {
            $data = $this->notification->sendNotification($request->all());

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (NotificationRepoException $ex) {
            return $this->responseJson(CODE_ERROR, $ex->getMessage());
        }
    }

    /**
     * Gửi thông báo nhân viên
     *
     * @param SendStaffNotificationRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendStaffNotification(SendStaffNotificationRequest $request)
    {
        try {
            $data = $this->notification->sendStaffNotification($request->all());

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (NotificationRepoException $ex) {
            return $this->responseJson(CODE_ERROR, $ex->getMessage());
        }
    }

    /**
     * Đếm số lượng thông báo mới
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function countNotification()
    {
        try {
            $data = $this->notification->countNotification();

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (NotificationRepoException $ex) {
            return $this->responseJson(CODE_ERROR, $ex->getMessage());
        }
    }

    /**
     * Clear thông báo mới
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function clearNotificationNew()
    {
        try {
            $data = $this->notification->clearNotificationNew();

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (NotificationRepoException $ex) {
            return $this->responseJson(CODE_ERROR, $ex->getMessage());
        }
    }

    /**
     * Đọc thông báo
     *
     * @param ReadNotificationRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function readNotification(ReadNotificationRequest $request)
    {
        try {
            $data = $this->notification->readNotification($request->all());

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (NotificationRepoException $ex) {
            return $this->responseJson(CODE_ERROR, $ex->getMessage());
        }
    }

    /**
     * Đọc tất cả thông báo
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function readAllNotification()
    {
        try {
            $data = $this->notification->readAllNotification();

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (NotificationRepoException $ex) {
            return $this->responseJson(CODE_ERROR, $ex->getMessage());
        }
    }

    /**
     * Gửi thông báo nhân viên không lưu dữ liệu
     *
     * @param SendNotifyNotDataRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendNotifyNotData(SendNotifyNotDataRequest $request)
    {
        try {
            $data = $this->notification->sendNotifyNotData($request->all());

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (NotificationRepoException $ex) {
            return $this->responseJson(CODE_ERROR, $ex->getMessage());
        }
    }
}
