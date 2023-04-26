<?php
/**
 * Created by PhpStorm
 * User: Mr Son
 * Date: 08-04-02020
 * Time: 10:24 AM
 */

namespace Modules\Notification\Repositories\Notification;

use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use Modules\Notification\Models\ConfigNotificationTable;
use Modules\Notification\Models\ConfigStaffNotificationTable;
use Modules\Notification\Models\ConfigTable;
use Modules\Notification\Models\CustomerAppointmentTable;
use Modules\Notification\Models\CustomerServiceCardTable;
use Modules\Notification\Models\CustomerTable;
use Modules\Notification\Models\DeliveryHistoryTable;
use Modules\Notification\Models\MapRoleGroupStaffTable;
use Modules\Notification\Models\NotificationDetailTable;
use Modules\Notification\Models\NotificationTable;
use Modules\Notification\Models\OrderDetailTable;
use Modules\Notification\Models\OrderTable;
use Modules\Notification\Models\ResetRankLogTable;
use Modules\Notification\Models\StaffNotificationDetailTable;
use Modules\Notification\Models\StaffNotificationReceiverTable;
use Modules\Notification\Models\StaffNotificationTable;
use Modules\Notification\Models\StaffTable;
use Modules\Ticket\Models\TicketAcceptanceTable;
use Modules\Ticket\Models\TicketRequestMaterialTable;
use Modules\Ticket\Models\TicketTable;
use MyCore\Repository\PagingTrait;

class NotificationRepo implements NotificationRepoInterface
{
    use PagingTrait;

    protected $notify;

    public function __construct(
        StaffNotificationTable $notify
    )
    {
        $this->notify = $notify;
    }

    /**
     * Lấy danh sách thông báo
     *
     * Nếu thông báo có type = brand
     *     thêm field "data":{
     *
     *                }
     *
     * @param $input
     * @return mixed
     * @throws NotificationRepoException
     */
    public function getNotifications($input)
    {
        try {
            $data = $this->notify->getNotifications(
                $input,
                auth()->user()->getAuthIdentifier()
            );
            return $this->toPagingData($data);
        } catch (\Exception $exception) {
            throw new NotificationRepoException(NotificationRepoException::GET_NOTIFICATION_LIST_FAILED, $exception->getMessage());
        }
    }

    /**
     * Chi tiết thông báo
     *
     * @param $input
     * @return mixed
     * @throws NotificationRepoException
     */
    public function getNotificationDetail($input)
    {
        // Cập nhật trạng thái đã đọc
        $this->notify->updateNotificationRead(
            $input['staff_notification_id'],
            auth()->user()->getAuthIdentifier()
        );

        // Lấy chi tiết thông báo từ notification_detail_id
        $data = $this->notify->getNotificationDetail(
            $input['staff_notification_id'],
            auth()->user()->getAuthIdentifier()
        );

        return $data;
    }

    /**
     * Xóa thông báo
     *
     * @param $data
     * @return mixed
     * @throws NotificationRepoException
     */
    public function deleteNotification($data)
    {
        try {
            if (isset($data['staff_notification_id'])) {
                $this->deleteNotificationById(
                    $data['staff_notification_id'],
                    auth()->user()->getAuthIdentifier());
            } else {
                $this->deleteAllNotificationByUser(
                    auth()->user()->getAuthIdentifier(),
                    isset($data['brand_id']) ? $data['brand_id'] : 0);
            }
        } catch (\Exception $exception) {
            throw new NotificationRepoException(NotificationRepoException::NOTIFICATION_DELETE_FAILED, $exception->getMessage());
        }
    }

    /**
     * Xóa 1 thông báo
     *
     * @param $idNotification
     * @param $idUser
     * @return mixed
     * @throws NotificationRepoException
     */
    private function deleteNotificationById($idNotification, $idUser)
    {
        try {
            $notification = $this->notify->getNotificationById(
                $idNotification,
                $idUser
            );
            if (empty($notification)) {
                throw new NotificationRepoException(NotificationRepoException::NOTIFICATION_NOT_FOUND);
            }
            // Xóa thông báo
            $this->notify->deleteNotificationById(
                $idNotification,
                $idUser
            );
        } catch (\Exception $exception) {
            throw new NotificationRepoException(NotificationRepoException::NOTIFICATION_DELETE_FAILED);
        }
    }

    /**
     * Xóa tất cả thông báo của user
     *
     * @param $idUser
     * @param $idBrand
     * @throws NotificationRepoException
     */
    private function deleteAllNotificationByUser($idUser, $idBrand)
    {
        try {
            // Lấy danh sách id chi tiết thông báo của user
//            $arrNotificationId = $this->notify->getNotificationDetailIdByUser($idUser, $isBrand);
            // Xóa chi tiết thông báo
//            app(NotificationDetailTable::class)->deleteNotificationByNotificationList($arrNotificationId);
            // Xóa thông báo theo user
            $this->notify->deleteNotificationByUser($idUser, $idBrand);
        } catch (\Exception $exception) {
            throw new NotificationRepoException(NotificationRepoException::NOTIFICATION_DELETE_FAILED, $exception->getMessage());
        }
    }

    /**
     * Gửi thông báo
     *
     * @param $input
     * @return mixed|string
     * @throws NotificationRepoException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function sendNotification($input)
    {
        try {
            $mConfig = app()->get(ConfigNotificationTable::class);

            //Kiểm tra config notification
            $config = $mConfig->getInfo($input['key']);

            if ($config == null) {
//                throw new NotificationRepoException(NotificationRepoException::SEND_NOTIFICATION_FAILED);
                return '';
            }
            //Replace nội dung thông báo
            $replaceData = $this->replaceContentNotification($config, $input['customer_id'], $input['object_id']);
            //Kiểm tra send type
            if ($config['send_type'] == "immediately") {
                //Insert thông báo
                $this->insertNotificationLog($replaceData['dataNotificationDetail'], $replaceData['dataNotification']);
            } else if ($config['send_type'] == "in_time") {
                $dateCheck = Carbon::createFromFormat('d/m/Y H:i', $replaceData['dateCheck'])->format('d/m/Y H:i');
                //Kiểm tra thời gian hiện tại bằng với thời gian config thì gửi
                if ($dateCheck == Carbon::now()->format('d/m/Y H:i')) {
                    //Insert thông báo
                    $this->insertNotificationLog($replaceData['dataNotificationDetail'], $replaceData['dataNotification']);
                }
            } else {
                $dateCheck = $replaceData['dateCheck'];
                if ($config['send_type'] == "before") {
                    $dateCheck = Carbon::createFromFormat('d/m/Y H:i', $replaceData['dateCheck'])->sub($config['value'], $config['schedule_unit'])->format('d/m/Y H:i');
                } else if ($config['send_type'] == "after") {
                    $dateCheck = Carbon::createFromFormat('d/m/Y H:i', $replaceData['dateCheck'])->add($config['value'], $config['schedule_unit'])->format('d/m/Y H:i');
                }
                //Kiểm tra thời gian hiện tại bằng với thời gian config thì gửi
                if ($dateCheck == Carbon::now()->format('d/m/Y H:i')) {
                    //Insert thông báo
                    $this->insertNotificationLog($replaceData['dataNotificationDetail'], $replaceData['dataNotification']);
                }
            }
        } catch (\Exception $exception) {
            throw new NotificationRepoException(NotificationRepoException::SEND_NOTIFICATION_FAILED, $exception->getMessage() . $exception->getLine());
        }
    }

    /**
     * Chỉnh nội dung thông báo phù hợp với config theo object_id
     *
     * @param $config
     * @param $userId
     * @param $objectId
     * @return array
     * @throws NotificationRepoException
     */
    private function replaceContentNotification($config, $userId, $objectId)
    {
        try {
            $mOrder = app()->get(OrderTable::class);
            $mCustomerAppointment = app()->get(CustomerAppointmentTable::class);
            $mCustomerServiceCard = app()->get(CustomerServiceCardTable::class);
            $mCustomer = app()->get(CustomerTable::class);
            $mResetRank = app()->get(ResetRankLogTable::class);
            $mDeliveryHistory = app()->get(DeliveryHistoryTable::class);
            //Data
            $dataNotificationDetail = [];
            $dataNotification = [];

            switch ($config['key']) {
                //Hủy lịch hẹn
                case 'appointment_C':
                    //Nhắc lịch
                case 'appointment_R':
                    //Xác nhận lịch hẹn
                case 'appointment_A':
                    //Cập nhật lịch hẹn
                case 'appointment_U':
                    //Lịch hẹn mới
                case 'appointment_W':
                    //Thông tin lịch hẹn
                    $info = $mCustomerAppointment->getInfo($objectId);
                    $message = str_replace(
                        [
                            '[branch_name]',
                            '[date]',
                            '[time]',
                            '[appointment_code]'
                        ],
                        [
                            $info['branch_name'],
                            Carbon::parse($info['date'])->format('d/m/Y'),
                            Carbon::parse($info['time'])->format('H:i'),
                            $info['customer_appointment_code']
                        ], $config['message']);
                    $content = str_replace(
                        [
                            '[branch_name]',
                            '[date]',
                            '[time]',
                            '[appointment_code]'
                        ],
                        [
                            $info['branch_name'],
                            Carbon::parse($info['date'])->format('d/m/Y'),
                            Carbon::parse($info['time'])->format('H:i'),
                            $info['customer_appointment_code']
                        ], $config['detail_content']);
                    $params = str_replace(
                        [
                            '[:customer_appointment_id]',
                            '[:user_id]',
                            '[:brand_url]',
                            '[:brand_name]',
                            '[:brand_id]'
                        ],
                        [
                            $info['customer_appointment_id'],
                            $userId,
                            '',
                            '',
                            0
                        ], $config['detail_action_params']);
                    //Data insert
                    $dataNotificationDetail = [
                        'background' => $config['detail_background'],
                        'content' => $content,
                        'action_name' => $config['detail_action_name'],
                        'action' => $config['detail_action'],
                        'action_params' => $params
                    ];
                    $dataNotification = [
                        'user_id' => $userId,
                        'notification_avatar' => $config['avatar'],
                        'notification_title' => $config['title'],
                        'notification_message' => $message
                    ];

                    $dateCheck = Carbon::now()->format('d/m/Y H:i');

                    if ($config['send_type'] == "in_time") {
                        $dateCheck = Carbon::now()->format("d/m/Y") . $config['value'];
                    } else if ($config['send_type'] == "before" || $config['send_type'] == "after") {
                        $dateCheck = Carbon::parse($info['date'] . $info['time'])->format('d/m/Y H:i');
                    }

                    return [
                        'dataNotificationDetail' => $dataNotificationDetail,
                        'dataNotification' => $dataNotification,
                        'dateCheck' => $dateCheck
                    ];
                    break;
                //Chúc mừng sinh nhật
                case 'customer_birthday':
                    $info = $mCustomer->getInfo($userId);
                    $message = $config['message'];
                    $content = $config['detail_content'];
                    $params = $config['detail_action_params'];
                    //Data insert
                    $dataNotificationDetail = [
                        'background' => $config['detail_background'],
                        'content' => $content,
                        'action_name' => $config['detail_action_name'],
                        'action' => $config['detail_action'],
                        'action_params' => $params
                    ];
                    $dataNotification = [
                        'user_id' => $userId,
                        'notification_avatar' => $config['avatar'],
                        'notification_title' => $config['title'],
                        'notification_message' => $message
                    ];

                    $dateCheck = Carbon::now()->format('d/m/Y H:i');

                    if ($config['send_type'] == "in_time") {
                        $dateCheck = Carbon::now()->format("d/m/Y") . $config['value'];
                    } else if ($config['send_type'] == "before" || $config['send_type'] == "after") {
                        $dateCheck = Carbon::parse($info['created_at'])->format('d/m/Y H:i');
                    }

                    return [
                        'dataNotificationDetail' => $dataNotificationDetail,
                        'dataNotification' => $dataNotification,
                        'dateCheck' => $dateCheck
                    ];
                    break;
                //Thăng hạng thành viên
                case 'customer_ranking':
                    $info = $mResetRank->getLastResetRank($userId);

                    if ($info['point_new'] <= $info['point_old']) {
                        throw new NotificationRepoException(NotificationRepoException::SEND_NOTIFICATION_FAILED);
                    }
                    $message = str_replace(['[name]'], [$info['rank_new_name']], $config['message']);
                    $content = str_replace(['[name]'], [$info['rank_new_name']], $config['detail_content']);
                    $params = str_replace(
                        [
                            '[name]',
                        ],
                        [
                            $info['rank_new_name'],
                        ], $config['detail_action_params']);
                    //Data insert
                    $dataNotificationDetail = [
                        'background' => $config['detail_background'],
                        'content' => $content,
                        'action_name' => $config['detail_action_name'],
                        'action' => $config['detail_action'],
                        'action_params' => $params
                    ];
                    $dataNotification = [
                        'user_id' => $userId,
                        'notification_avatar' => $config['avatar'],
                        'notification_title' => $config['title'],
                        'notification_message' => $message
                    ];

                    $dateCheck = Carbon::now()->format('d/m/Y H:i');

                    if ($config['send_type'] == "in_time") {
                        $dateCheck = Carbon::now()->format("d/m/Y") . $config['value'];
                    } else if ($config['send_type'] == "before" || $config['send_type'] == "after") {
                        $dateCheck = Carbon::parse($info['created_at'])->format('d/m/Y H:i');
                    }

                    return [
                        'dataNotificationDetail' => $dataNotificationDetail,
                        'dataNotification' => $dataNotification,
                        'dateCheck' => $dateCheck
                    ];
                    break;
                //Khách hàng mới
                case 'customer_agent_W':
                case 'customer_W':
                    $info = $mCustomer->getInfo($userId);
                    $message = str_replace(['[name]'], [$info['full_name']], $config['message']);
                    $content = str_replace(['[name]'], [$info['full_name']], $config['detail_content']);
                    $params = $config['detail_action_params'];
                    //Data insert
                    $dataNotificationDetail = [
                        'background' => $config['detail_background'],
                        'content' => $content,
                        'action_name' => $config['detail_action_name'],
                        'action' => $config['detail_action'],
                        'action_params' => $params
                    ];
                    $dataNotification = [
                        'user_id' => $userId,
                        'notification_avatar' => $config['avatar'],
                        'notification_title' => $config['title'],
                        'notification_message' => $message
                    ];

                    $dateCheck = Carbon::now()->format('d/m/Y H:i');

                    if ($config['send_type'] == "in_time") {
                        $dateCheck = Carbon::now()->format("d/m/Y") . $config['value'];
                    } else if ($config['send_type'] == "before" && $config['send_type'] == "after") {
                        $dateCheck = Carbon::parse($info['created_at'])->format('d/m/Y H:i');
                    }

                    return [
                        'dataNotificationDetail' => $dataNotificationDetail,
                        'dataNotification' => $dataNotification,
                        'dateCheck' => $dateCheck
                    ];

                    break;
                //Đơn hàng đang giao hàng
                case 'order_status_D':
                    //Đơn hàng đã giao hàng
                case 'order_status_I':
                    //Đơn hàng đã trã hàng
                case 'order_status_B':
                    //Đơn hàng đã thanh toán
                case 'order_status_S':
                    //Hủy đơn hàng
                case 'order_status_C':
                    //Xác nhận đơn hàng
                case 'order_status_A':
                    //Đơn hàng mới
                case 'order_status_W':
                    //Thông tin đơn hàng
                    $info = $mOrder->getInfo($objectId, $userId);
                    $message = str_replace(['[order_code]'], [$info['order_code']], $config['message']);
                    $content = str_replace(['[order_code]'], [$info['order_code']], $config['detail_content']);
                    $params = str_replace(
                        [
                            '[:order_id]',
                            '[:user_id]',
                            '[:brand_url]',
                            '[:brand_name]',
                            '[:brand_id]'
                        ],
                        [
                            $info['order_id'],
                            $info['customer_id'],
                            '',
                            '',
                            0
                        ], $config['detail_action_params']);
                    //Data insert
                    $dataNotificationDetail = [
                        'background' => $config['detail_background'],
                        'content' => $content,
                        'action_name' => $config['detail_action_name'],
                        'action' => $config['detail_action'],
                        'action_params' => $params
                    ];
                    $dataNotification = [
                        'user_id' => $info['customer_id'],
                        'notification_avatar' => $config['avatar'],
                        'notification_title' => $config['title'],
                        'notification_message' => $message
                    ];

                    $dateCheck = Carbon::now()->format('d/m/Y H:i');

                    if ($config['send_type'] == "in_time") {
                        $dateCheck = Carbon::now()->format("d/m/Y") . $config['value'];
                    } else if ($config['send_type'] == "before" || $config['send_type'] == "after") {
                        $dateCheck = Carbon::parse($info['created_at'])->format('d/m/Y H:i');
                    }

                    return [
                        'dataNotificationDetail' => $dataNotificationDetail,
                        'dataNotification' => $dataNotification,
                        'dateCheck' => $dateCheck
                    ];
                    break;
                //Thẻ dịch vụ sắp hết hạn sử dụng
                case 'service_card_nearly_expired':
                    //Thẻ dịch vụ hết hạn sử dụng
                case 'service_card_expired':
                    $info = $mCustomerServiceCard->getInfo($objectId);
                    $message = str_replace(['[name]', '[expired_date]'], [$info['service_card_name'], Carbon::parse($info['expired_date'])->format('d/m/Y')], $config['message']);
                    $content = str_replace(['[name]', '[expired_date]'], [$info['service_card_name'], Carbon::parse($info['expired_date'])->format('d/m/Y')], $config['detail_content']);
                    $params = $config['detail_action_params'];
                    //Data insert
                    $dataNotificationDetail = [
                        'background' => $config['detail_background'],
                        'content' => $content,
                        'action_name' => $config['detail_action_name'],
                        'action' => $config['detail_action'],
                        'action_params' => $params
                    ];
                    $dataNotification = [
                        'user_id' => $userId,
                        'notification_avatar' => $config['avatar'],
                        'notification_title' => $config['title'],
                        'notification_message' => $message
                    ];

                    $dateCheck = Carbon::now()->format('d/m/Y H:i');

                    if ($config['send_type'] == "in_time") {
                        $dateCheck = Carbon::now()->format("d/m/Y") . $config['value'];
                    } else if ($config['send_type'] == "before" || $config['send_type'] == "after") {
                        $dateCheck = Carbon::parse($info['expired_date'])->format('d/m/Y H:i');
                    }

                    return [
                        'dataNotificationDetail' => $dataNotificationDetail,
                        'dataNotification' => $dataNotification,
                        'dateCheck' => $dateCheck
                    ];
                    break;
                //Thẻ dịch vụ hết số lần sử dụng
                case 'service_card_over_number_used':
                    $info = $mCustomerServiceCard->getInfo($objectId);
                    if ($info['number_using'] == $info['count_using']) {
                        $message = str_replace(['[name]'], [$info['service_card_name']], $config['message']);
                        $content = str_replace(['[name]'], [$info['service_card_name']], $config['detail_content']);
                        $params = $config['detail_action_params'];
                        //Data insert
                        $dataNotificationDetail = [
                            'background' => $config['detail_background'],
                            'content' => $content,
                            'action_name' => $config['detail_action_name'],
                            'action' => $config['detail_action'],
                            'action_params' => $params
                        ];
                        $dataNotification = [
                            'user_id' => $userId,
                            'notification_avatar' => $config['avatar'],
                            'notification_title' => $config['title'],
                            'notification_message' => $message
                        ];

                        $dateCheck = Carbon::now()->format('d/m/Y H:i');

                        if ($config['send_type'] == "in_time") {
                            $dateCheck = Carbon::now()->format("d/m/Y") . $config['value'];
                        } else if ($config['send_type'] == "before" || $config['send_type'] == "after") {
                            $dateCheck = Carbon::parse($info['created_at'])->format('d/m/Y H:i');
                        }

                        return [
                            'dataNotificationDetail' => $dataNotificationDetail,
                            'dataNotification' => $dataNotification,
                            'dateCheck' => $dateCheck
                        ];
                    } else {
                        throw new NotificationRepoException(NotificationRepoException::SEND_NOTIFICATION_FAILED);
                    }
                    break;
                //Phiếu giao hàng mới
                case 'delivery_W';
                    $info = $mDeliveryHistory->getInfo($objectId);
                    $message = str_replace(['[delivery_history_code]'], [$info['delivery_history_code']], $config['message']);
                    $content = str_replace(['[delivery_history_code]'], [$info['delivery_history_code']], $config['detail_content']);
                    $params = str_replace(
                        [
                            '[:order_id]',
                            '[:delivery_history_id]',
                            '[:user_id]',
                            '[:brand_url]',
                            '[:brand_name]',
                            '[:brand_id]'
                        ],
                        [
                            $info['order_id'],
                            $info['delivery_history_id'],
                            $userId,
                            '',
                            '',
                            0
                        ], $config['detail_action_params']);

                    //Data insert
                    $dataNotificationDetail = [
                        'background' => $config['detail_background'],
                        'content' => $content,
                        'action_name' => $config['detail_action_name'],
                        'action' => $config['detail_action'],
                        'action_params' => $params
                    ];
                    $dataNotification = [
                        'user_id' => $userId,
                        'notification_avatar' => $config['avatar'],
                        'notification_title' => $config['title'],
                        'notification_message' => $message
                    ];

                    $dateCheck = Carbon::now()->format('d/m/Y H:i');

                    if ($config['send_type'] == "in_time") {
                        $dateCheck = Carbon::now()->format("d/m/Y") . $config['value'];
                    } else if ($config['send_type'] == "before" || $config['send_type'] == "after") {
                        $dateCheck = Carbon::parse($info['created_at'])->format('d/m/Y H:i');
                    }

                    return [
                        'dataNotificationDetail' => $dataNotificationDetail,
                        'dataNotification' => $dataNotification,
                        'dateCheck' => $dateCheck
                    ];
                    break;
                default:
                    return [
                        'dataNotificationDetail' => $dataNotificationDetail,
                        'dataNotification' => $dataNotification
                    ];
                    break;
            }
        } catch (\Exception $exception) {
            throw new NotificationRepoException(NotificationRepoException::SEND_NOTIFICATION_FAILED, $exception->getMessage());
        }
    }

    /**
     * Gửi notification cho user và insert log
     *
     * @param $dataNotificationDetail
     * @param $dataNotification
     * @return string
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    private function insertNotificationLog($dataNotificationDetail, $dataNotification)
    {
        try {
            $mNotificationDetail = app()->get(NotificationDetailTable::class);

            //Insert notification detail
            $idNotificationDetail = $mNotificationDetail->add($dataNotificationDetail);
            //Insert notification
//            $dataNotification['notification_detail_id'] = $idNotificationDetail;
//            $this->notify->add($dataNotification);
            //Push notification
            $oClient = new Client();

            $response = $oClient->request('POST', NAE_SERVICE_URL . '/notification/push', [
                'json' => [
                    'tenant_id' => session()->get('idTenant'),
                    'user_id' => $dataNotification['user_id'],
                    'title' => $dataNotification['notification_title'],
                    'message' => $dataNotification['notification_message'],
                    'detail_id' => $idNotificationDetail,
                    'avatar' => $dataNotification['notification_avatar']
                ]
            ]);
        } catch (\Exception $exception) {
//            throw new NotificationRepoException(NotificationRepoException::SEND_NOTIFICATION_FAILED, $exception->getMessage());
            return '';
        }
    }

    /**
     * Đếm số lượng thông báo mới
     *
     * @return mixed|void
     * @throws NotificationRepoException
     */
    public function countNotification()
    {
        try {
            $data = $this->notify->countNotification(Auth()->id());

            return [
                'number' => $data
            ];
        } catch (\Exception $exception) {
            throw new NotificationRepoException(NotificationRepoException::COUNT_NOTIFICATION_FAILED, $exception->getMessage());
        }
    }

    /**
     * Clear thông báo mới
     *
     * @return mixed|void
     * @throws NotificationRepoException
     */
    public function clearNotificationNew()
    {
        try {
            $this->notify->clearNotificationNew(Auth()->id());
        } catch (\Exception $exception) {
            throw new NotificationRepoException(NotificationRepoException::CLEAR_NOTIFICATION_FAILED, $exception->getMessage());
        }
    }

    /**
     * Đọc thông báo
     *
     * @param $input
     * @return mixed|void
     * @throws NotificationRepoException
     */
    public function readNotification($input)
    {
        try {
            $this->notify->updateNotificationRead($input['staff_notification_id'], Auth()->id());

        } catch (\Exception $e) {
            throw new NotificationRepoException(NotificationRepoException::READ_NOTIFICATION_FAILED, $e->getMessage());
        }
    }

    /**
     * Đọc tất cả thông báo
     *
     * @return mixed|void
     * @throws NotificationRepoException
     */
    public function readAllNotification()
    {
        try {
            $this->notify->readAllNotification(Auth()->id());

        } catch (\Exception $e) {
            throw new NotificationRepoException(NotificationRepoException::READ_ALL_NOTIFICATION_FAILED, $e->getMessage());
        }
    }

    /**
     * Gửi thông báo nhân viên
     *
     * @param $input
     * @return mixed|string
     * @throws NotificationRepoException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function sendStaffNotification($input)
    {
        try {
            $key = [
                'ticket_assign', 'ticket_edit', 'ticket_operater', 'request_material_create', 'request_material_remove', 'request_material_approve',
                'request_material_reject', 'acceptance_create', 'acceptance_edit', 'ticket_image', 'request_material_edit', 'ticket_finish_operater',
                'ticket_finish_processor', 'ticket_close_operater', 'ticket_close_processor', 'request_material_create_staff', 'ticket_rating', 'ticket_comment_new'
            ];
            $mConfig = app()->get(ConfigStaffNotificationTable::class);

            //Kiểm tra config notification
            $config = $mConfig->getInfo($input['key']);

            if ($config == null) {
//                throw new NotificationRepoException(NotificationRepoException::SEND_NOTIFICATION_FAILED);
                return '';
            }
            //Replace thông báo
            $replaceData = $this->replaceContentStaffNotify($config, $input['customer_id'], $input['object_id']);
            //Gắn chi nhánh thông báo
            $replaceData['dataNotification']['branch_id'] = isset($input['branch_id']) ? $input['branch_id'] : '';

            //Kiểm tra send type
            if ($config['send_type'] == "immediately") {
                //Insert thông báo
                if (in_array($input['key'], $key)) {
                    $this->insertStaffNotifyLogTicket($replaceData['dataNotificationDetail'], $replaceData['dataNotification'], $input['customer_id']);
                } else {
                    $this->insertStaffNotifyLog($replaceData['dataNotificationDetail'], $replaceData['dataNotification'], $input);
                }
            } else if ($config['send_type'] == "in_time") {
                $dateCheck = Carbon::createFromFormat('d/m/Y H:i', $replaceData['dateCheck'])->format('d/m/Y H:i');
                //Kiểm tra thời gian hiện tại bằng với thời gian config thì gửi
                if ($dateCheck == Carbon::now()->format('d/m/Y H:i')) {
                    //Insert thông báo
                    if (in_array($input['key'], $key)) {
                        $this->insertStaffNotifyLogTicket($replaceData['dataNotificationDetail'], $replaceData['dataNotification'], $input['customer_id']);
                    } else {
                        $this->insertStaffNotifyLog($replaceData['dataNotificationDetail'], $replaceData['dataNotification'], $input);
                    }
                }
            } else {
                $dateCheck = $replaceData['dateCheck'];
                if ($config['send_type'] == "before") {
                    $dateCheck = Carbon::createFromFormat('d/m/Y H:i', $replaceData['dateCheck'])->sub($config['value'], $config['schedule_unit'])->format('d/m/Y H:i');
                } else if ($config['send_type'] == "after") {
                    $dateCheck = Carbon::createFromFormat('d/m/Y H:i', $replaceData['dateCheck'])->add($config['value'], $config['schedule_unit'])->format('d/m/Y H:i');
                }
                //Kiểm tra thời gian hiện tại bằng với thời gian config thì gửi
                if ($dateCheck == Carbon::now()->format('d/m/Y H:i')) {
                    //Insert thông báo
                    if (in_array($input['key'], $key)) {
                        $this->insertStaffNotifyLogTicket($replaceData['dataNotificationDetail'], $replaceData['dataNotification'], $input['customer_id']);
                    } else {
                        $this->insertStaffNotifyLog($replaceData['dataNotificationDetail'], $replaceData['dataNotification'], $input);
                    }
                }
            }
        } catch (\Exception $e) {
            throw new NotificationRepoException(NotificationRepoException::SEND_NOTIFICATION_FAILED, $e->getMessage() . $e->getLine());
        }
    }

    /**
     * Replace nội dung thông báo nhân viên
     *
     * @param $config
     * @param $userId
     * @param $objectId
     * @return array
     * @throws NotificationRepoException
     */
    private function replaceContentStaffNotify($config, $userId, $objectId)
    {
        try {
            $mOrder = app()->get(OrderTable::class);
            $mOrderDetail = app()->get(OrderDetailTable::class);
            $mCustomerAppointment = app()->get(CustomerAppointmentTable::class);
            $mCustomerServiceCard = app()->get(CustomerServiceCardTable::class);
            $mCustomer = app()->get(CustomerTable::class);
            $mResetRank = app()->get(ResetRankLogTable::class);
            $mDeliveryHistory = app()->get(DeliveryHistoryTable::class);
            $mConfig = app()->get(ConfigTable::class);

            $mTicket = new TicketTable();
            $mTicketAcceptance = new TicketAcceptanceTable();
            $mTicketRequestMaterial = new TicketRequestMaterialTable();

            //Data
            $dataNotificationDetail = [];
            $dataNotification = [];

            switch ($config['key']) {
                //Hủy lịch hẹn
                case 'appointment_C':
                    //Nhắc lịch
                case 'appointment_R':
                    //Xác nhận lịch hẹn
                case 'appointment_A':
                    //Cập nhật lịch hẹn
                case 'appointment_U':
                    //Lịch hẹn mới
                case 'appointment_W':
                    //Thông tin lịch hẹn
                    $info = $mCustomerAppointment->getInfo($objectId);
                    $message = str_replace(
                        [
                            '[branch_name]',
                            '[date]',
                            '[time]',
                            '[appointment_code]'
                        ],
                        [
                            $info['branch_name'],
                            Carbon::parse($info['date'])->format('d/m/Y'),
                            Carbon::parse($info['time'])->format('H:i'),
                            $info['customer_appointment_code']
                        ], $config['message']);
                    $content = str_replace(
                        [
                            '[branch_name]',
                            '[date]',
                            '[time]',
                            '[appointment_code]'
                        ],
                        [
                            $info['branch_name'],
                            Carbon::parse($info['date'])->format('d/m/Y'),
                            Carbon::parse($info['time'])->format('H:i'),
                            $info['customer_appointment_code']
                        ], $config['detail_content']);
                    $params = str_replace(
                        [
                            '[:customer_appointment_id]',
                            '[:user_id]',
                            '[:brand_url]',
                            '[:brand_name]',
                            '[:brand_id]'
                        ],
                        [
                            $info['customer_appointment_id'],
                            $userId,
                            '',
                            '',
                            0
                        ], $config['detail_action_params']);
                    //Data insert
                    $dataNotificationDetail = [
                        'background' => $config['detail_background'],
                        'content' => $content,
                        'action_name' => $config['detail_action_name'],
                        'action' => $config['detail_action'],
                        'action_params' => $params
                    ];
                    $dataNotification = [
                        'user_id' => $userId,
                        'notification_avatar' => $config['avatar'],
                        'notification_title' => $config['title'],
                        'notification_message' => $message
                    ];

                    $dateCheck = Carbon::now()->format('d/m/Y H:i');

                    if ($config['send_type'] == "in_time") {
                        $dateCheck = Carbon::now()->format("d/m/Y") . $config['value'];
                    } else if ($config['send_type'] == "before" || $config['send_type'] == "after") {
                        $dateCheck = Carbon::parse($info['date'] . $info['time'])->format('d/m/Y H:i');
                    }

                    return [
                        'dataNotificationDetail' => $dataNotificationDetail,
                        'dataNotification' => $dataNotification,
                        'dateCheck' => $dateCheck
                    ];
                    break;
                //Chúc mừng sinh nhật
                case 'customer_birthday':
                    $info = $mCustomer->getInfo($userId);
                    $message = $config['message'];
                    $content = $config['detail_content'];
                    $params = $config['detail_action_params'];
                    //Data insert
                    $dataNotificationDetail = [
                        'background' => $config['detail_background'],
                        'content' => $content,
                        'action_name' => $config['detail_action_name'],
                        'action' => $config['detail_action'],
                        'action_params' => $params
                    ];
                    $dataNotification = [
                        'user_id' => $userId,
                        'notification_avatar' => $config['avatar'],
                        'notification_title' => $config['title'],
                        'notification_message' => $message
                    ];

                    $dateCheck = Carbon::now()->format('d/m/Y H:i');

                    if ($config['send_type'] == "in_time") {
                        $dateCheck = Carbon::now()->format("d/m/Y") . $config['value'];
                    } else if ($config['send_type'] == "before" || $config['send_type'] == "after") {
                        $dateCheck = Carbon::parse($info['created_at'])->format('d/m/Y H:i');
                    }

                    return [
                        'dataNotificationDetail' => $dataNotificationDetail,
                        'dataNotification' => $dataNotification,
                        'dateCheck' => $dateCheck
                    ];
                    break;
                //Thăng hạng thành viên
                case 'customer_ranking':
                    $info = $mResetRank->getLastResetRank($userId);

                    if ($info['point_new'] <= $info['point_old']) {
                        throw new NotificationRepoException(NotificationRepoException::SEND_NOTIFICATION_FAILED);
                    }
                    $message = str_replace(['[name]'], [$info['rank_new_name']], $config['message']);
                    $content = str_replace(['[name]'], [$info['rank_new_name']], $config['detail_content']);
                    $params = str_replace(
                        [
                            '[name]',
                        ],
                        [
                            $info['rank_new_name'],
                        ], $config['detail_action_params']);
                    //Data insert
                    $dataNotificationDetail = [
                        'background' => $config['detail_background'],
                        'content' => $content,
                        'action_name' => $config['detail_action_name'],
                        'action' => $config['detail_action'],
                        'action_params' => $params
                    ];
                    $dataNotification = [
                        'user_id' => $userId,
                        'notification_avatar' => $config['avatar'],
                        'notification_title' => $config['title'],
                        'notification_message' => $message
                    ];

                    $dateCheck = Carbon::now()->format('d/m/Y H:i');

                    if ($config['send_type'] == "in_time") {
                        $dateCheck = Carbon::now()->format("d/m/Y") . $config['value'];
                    } else if ($config['send_type'] == "before" || $config['send_type'] == "after") {
                        $dateCheck = Carbon::parse($info['created_at'])->format('d/m/Y H:i');
                    }

                    return [
                        'dataNotificationDetail' => $dataNotificationDetail,
                        'dataNotification' => $dataNotification,
                        'dateCheck' => $dateCheck
                    ];
                    break;
                //Khách hàng mới
                case 'customer_agent_W':
                case 'customer_W':
                    $info = $mCustomer->getInfo($userId);
                    $message = str_replace(['[name]'], [$info['full_name']], $config['message']);
                    $content = str_replace(['[name]'], [$info['full_name']], $config['detail_content']);
                    $params = $config['detail_action_params'];
                    //Data insert
                    $dataNotificationDetail = [
                        'background' => $config['detail_background'],
                        'content' => $content,
                        'action_name' => $config['detail_action_name'],
                        'action' => $config['detail_action'],
                        'action_params' => $params
                    ];
                    $dataNotification = [
                        'user_id' => $userId,
                        'notification_avatar' => $config['avatar'],
                        'notification_title' => $config['title'],
                        'notification_message' => $message
                    ];

                    $dateCheck = Carbon::now()->format('d/m/Y H:i');

                    if ($config['send_type'] == "in_time") {
                        $dateCheck = Carbon::now()->format("d/m/Y") . $config['value'];
                    } else if ($config['send_type'] == "before" && $config['send_type'] == "after") {
                        $dateCheck = Carbon::parse($info['created_at'])->format('d/m/Y H:i');
                    }

                    return [
                        'dataNotificationDetail' => $dataNotificationDetail,
                        'dataNotification' => $dataNotification,
                        'dateCheck' => $dateCheck
                    ];

                    break;
                //Đơn hàng đang giao hàng
                case 'order_status_D':
                    //Đơn hàng đã giao hàng
                case 'order_status_I':
                    //Đơn hàng đã trã hàng
                case 'order_status_B':
                    //Đơn hàng đã thanh toán
                case 'order_status_S':
                    //Hủy đơn hàng
                case 'order_status_C':
                    //Xác nhận đơn hàng
                case 'order_status_A':
                    //Đơn hàng mới
                case 'order_status_W':
                    //Lấy cấu hình số lẻ
                    $decimalNumber = intval($mConfig->getConfig('decimal_number')['value']);
                    //Thông tin đơn hàng
                    $info = $mOrder->getInfo($objectId, $userId);
                    //Đếm số lượng sp/dv/thẻ dv của đơn hàng
                    $totalProduct = $mOrderDetail->sumTotalProduct($info['order_id']);

                    $message = str_replace(
                        [
                            '[order_code]',
                            '[customer_name]',
                            '[total_product]',
                            '[total_amount]'
                        ],
                        [
                            $info['order_code'],
                            $info['customer_name'],
                            intval($totalProduct['total_quantity']),
                            number_format($info['amount'], $decimalNumber ? $decimalNumber : 0)
                        ], $config['message']);
                    $content = str_replace(
                        [
                            '[order_code]',
                            '[customer_name]',
                            '[total_product]',
                            '[total_amount]'
                        ],
                        [
                            $info['order_code'],
                            $info['customer_name'],
                            intval($totalProduct['total_quantity']),
                            number_format($info['amount'], $decimalNumber ? $decimalNumber : 0)
                        ], $config['detail_content']);

                    $params = str_replace(
                        [
                            '[:order_id]',
                            '[:user_id]',
                            '[:brand_url]',
                            '[:brand_name]',
                            '[:brand_id]'
                        ],
                        [
                            $info['order_id'],
                            $info['customer_id'],
                            '',
                            '',
                            0
                        ], $config['detail_action_params']);
                    //Data insert
                    $dataNotificationDetail = [
                        'background' => $config['detail_background'],
                        'content' => $content,
                        'action_name' => $config['detail_action_name'],
                        'action' => $config['detail_action'],
                        'action_params' => $params
                    ];
                    $dataNotification = [
                        'user_id' => $info['customer_id'],
                        'notification_avatar' => $config['avatar'],
                        'notification_title' => $config['title'],
                        'notification_message' => $message
                    ];

                    $dateCheck = Carbon::now()->format('d/m/Y H:i');

                    if ($config['send_type'] == "in_time") {
                        $dateCheck = Carbon::now()->format("d/m/Y") . $config['value'];
                    } else if ($config['send_type'] == "before" || $config['send_type'] == "after") {
                        $dateCheck = Carbon::parse($info['created_at'])->format('d/m/Y H:i');
                    }

                    return [
                        'dataNotificationDetail' => $dataNotificationDetail,
                        'dataNotification' => $dataNotification,
                        'dateCheck' => $dateCheck
                    ];
                    break;
                //Thẻ dịch vụ sắp hết hạn sử dụng
                case 'service_card_nearly_expired':
                    //Thẻ dịch vụ hết hạn sử dụng
                case 'service_card_expired':
                    $info = $mCustomerServiceCard->getInfo($objectId);
                    $message = str_replace(['[name]', '[expired_date]'], [$info['service_card_name'], Carbon::parse($info['expired_date'])->format('d/m/Y')], $config['message']);
                    $content = str_replace(['[name]', '[expired_date]'], [$info['service_card_name'], Carbon::parse($info['expired_date'])->format('d/m/Y')], $config['detail_content']);
                    $params = $config['detail_action_params'];
                    //Data insert
                    $dataNotificationDetail = [
                        'background' => $config['detail_background'],
                        'content' => $content,
                        'action_name' => $config['detail_action_name'],
                        'action' => $config['detail_action'],
                        'action_params' => $params
                    ];
                    $dataNotification = [
                        'user_id' => $userId,
                        'notification_avatar' => $config['avatar'],
                        'notification_title' => $config['title'],
                        'notification_message' => $message
                    ];

                    $dateCheck = Carbon::now()->format('d/m/Y H:i');

                    if ($config['send_type'] == "in_time") {
                        $dateCheck = Carbon::now()->format("d/m/Y") . $config['value'];
                    } else if ($config['send_type'] == "before" || $config['send_type'] == "after") {
                        $dateCheck = Carbon::parse($info['expired_date'])->format('d/m/Y H:i');
                    }

                    return [
                        'dataNotificationDetail' => $dataNotificationDetail,
                        'dataNotification' => $dataNotification,
                        'dateCheck' => $dateCheck
                    ];
                    break;
                //Thẻ dịch vụ hết số lần sử dụng
                case 'service_card_over_number_used':
                    $info = $mCustomerServiceCard->getInfo($objectId);
                    if ($info['number_using'] == $info['count_using']) {
                        $message = str_replace(['[name]'], [$info['service_card_name']], $config['message']);
                        $content = str_replace(['[name]'], [$info['service_card_name']], $config['detail_content']);
                        $params = $config['detail_action_params'];
                        //Data insert
                        $dataNotificationDetail = [
                            'background' => $config['detail_background'],
                            'content' => $content,
                            'action_name' => $config['detail_action_name'],
                            'action' => $config['detail_action'],
                            'action_params' => $params
                        ];
                        $dataNotification = [
                            'user_id' => $userId,
                            'notification_avatar' => $config['avatar'],
                            'notification_title' => $config['title'],
                            'notification_message' => $message
                        ];

                        $dateCheck = Carbon::now()->format('d/m/Y H:i');

                        if ($config['send_type'] == "in_time") {
                            $dateCheck = Carbon::now()->format("d/m/Y") . $config['value'];
                        } else if ($config['send_type'] == "before" || $config['send_type'] == "after") {
                            $dateCheck = Carbon::parse($info['created_at'])->format('d/m/Y H:i');
                        }

                        return [
                            'dataNotificationDetail' => $dataNotificationDetail,
                            'dataNotification' => $dataNotification,
                            'dateCheck' => $dateCheck
                        ];
                    } else {
                        throw new NotificationRepoException(NotificationRepoException::SEND_NOTIFICATION_FAILED);
                    }
                    break;
                //Phiếu giao hàng mới
                case 'delivery_W';
                    $info = $mDeliveryHistory->getInfo($objectId);
                    $message = str_replace(['[delivery_history_code]'], [$info['delivery_history_code']], $config['message']);
                    $content = str_replace(['[delivery_history_code]'], [$info['delivery_history_code']], $config['detail_content']);
                    $params = str_replace(
                        [
                            '[:order_id]',
                            '[:delivery_history_id]',
                            '[:user_id]',
                            '[:brand_url]',
                            '[:brand_name]',
                            '[:brand_id]'
                        ],
                        [
                            $info['order_id'],
                            $info['delivery_history_id'],
                            $userId,
                            '',
                            '',
                            0
                        ], $config['detail_action_params']);

                    //Data insert
                    $dataNotificationDetail = [
                        'background' => $config['detail_background'],
                        'content' => $content,
                        'action_name' => $config['detail_action_name'],
                        'action' => $config['detail_action'],
                        'action_params' => $params
                    ];
                    $dataNotification = [
                        'user_id' => $userId,
                        'notification_avatar' => $config['avatar'],
                        'notification_title' => $config['title'],
                        'notification_message' => $message
                    ];

                    $dateCheck = Carbon::now()->format('d/m/Y H:i');

                    if ($config['send_type'] == "in_time") {
                        $dateCheck = Carbon::now()->format("d/m/Y") . $config['value'];
                    } else if ($config['send_type'] == "before" || $config['send_type'] == "after") {
                        $dateCheck = Carbon::parse($info['created_at'])->format('d/m/Y H:i');
                    }

                    return [
                        'dataNotificationDetail' => $dataNotificationDetail,
                        'dataNotification' => $dataNotification,
                        'dateCheck' => $dateCheck
                    ];
                    break;
                //                Ticket được phân công
                case 'ticket_assign';
                case 'ticket_edit';
                case 'ticket_operater';
                case 'ticket_comment_new';
                case 'ticket_image';
                    //Lấy thông tin ticket
                    $info = $mTicket->getInfoNoti($objectId);

                    $message = str_replace(
                        [
                            '[staff_name]',
                            '[ticket_code]',
                            '[ticket_title]'
                        ],
                        [
                            $info['full_name_updated'],
                            $info['ticket_code'],
                            $info['title']
                        ], $config['message']);

                    $content = str_replace(
                        [
                            '[staff_name]',
                            '[ticket_code]',
                            '[ticket_title]'
                        ],
                        [
                            $info['full_name_updated'],
                            $info['ticket_code'],
                            $info['title']
                        ], $config['detail_content']);

                    $params = str_replace(
                        [
                            '[:ticket_id]'
                        ],
                        [
                            $info['ticket_id']
                        ], $config['detail_action_params']);

                    //Data insert
                    $dataNotificationDetail = [
                        'background' => $config['detail_background'],
                        'content' => $content,
                        'action_name' => $config['detail_action_name'],
                        'action' => $config['detail_action'],
                        'action_params' => $params
                    ];
                    $dataNotification = [
                        'user_id' => $userId,
                        'notification_avatar' => $config['avatar'],
                        'notification_title' => $config['title'],
                        'notification_message' => $message
                    ];

                    $dateCheck = Carbon::now()->format('d/m/Y H:i');

                    if ($config['send_type'] == "in_time") {
                        $dateCheck = Carbon::now()->format("d/m/Y") . $config['value'];
                    } else if ($config['send_type'] == "before" || $config['send_type'] == "after") {
                        $dateCheck = Carbon::parse($info['created_at'])->format('d/m/Y H:i');
                    }

                    return [
                        'dataNotificationDetail' => $dataNotificationDetail,
                        'dataNotification' => $dataNotification,
                        'dateCheck' => $dateCheck
                    ];
                    break;
                case 'request_material_create';
                case 'request_material_edit';
                case 'request_material_remove';
                case 'request_material_approve';
                case 'request_material_reject';
                case 'request_material_create_staff';
                    $info = $mTicketRequestMaterial->getDetailForNoti($objectId);
                    $message = str_replace(['[staff_name]', '[ticket_request_material_code]', '[ticket_code]'], [$info['full_name_updated'], $info['ticket_request_material_code'], $info['ticket_code']], $config['message']);
                    $content = str_replace(['[staff_name]', '[ticket_request_material_code]', '[ticket_code]'], [$info['full_name_updated'], $info['ticket_request_material_code'], $info['ticket_code']], $config['detail_content']);
                    $params = str_replace(
                        [
                            '[:ticket_id]',
                            '[:ticket_request_material_id]',
                        ],
                        [
                            $info['ticket_id'],
                            $info['ticket_request_material_id'],
                        ], $config['detail_action_params']);

                    //Data insert
                    $dataNotificationDetail = [
                        'background' => $config['detail_background'],
                        'content' => $content,
                        'action_name' => $config['detail_action_name'],
                        'action' => $config['detail_action'],
                        'action_params' => $params
                    ];
                    $dataNotification = [
                        'user_id' => $userId,
                        'notification_avatar' => $config['avatar'],
                        'notification_title' => $config['title'],
                        'notification_message' => $message
                    ];

                    $dateCheck = Carbon::now()->format('d/m/Y H:i');

                    if ($config['send_type'] == "in_time") {
                        $dateCheck = Carbon::now()->format("d/m/Y") . $config['value'];
                    } else if ($config['send_type'] == "before" || $config['send_type'] == "after") {
                        $dateCheck = Carbon::parse($info['created_at'])->format('d/m/Y H:i');
                    }

                    return [
                        'dataNotificationDetail' => $dataNotificationDetail,
                        'dataNotification' => $dataNotification,
                        'dateCheck' => $dateCheck
                    ];
                    break;
                case 'acceptance_create';
                case 'acceptance_edit';
                    $info = $mTicketAcceptance->getDetailForNoti($objectId);
                    $message = str_replace(['[staff_name]', '[ticket_acceptance_code]', '[ticket_code]'], [$info['full_name_updated'], $info['ticket_acceptance_code'], $info['ticket_code']], $config['message']);
                    $content = str_replace(['[staff_name]', '[ticket_acceptance_code]', '[ticket_code]'], [$info['full_name_updated'], $info['ticket_acceptance_code'], $info['ticket_code']], $config['detail_content']);
                    $params = str_replace(
                        [
                            '[:ticket_id]',
                            '[:ticket_acceptance_id]',
                        ],
                        [
                            $info['ticket_id'],
                            $info['ticket_acceptance_id'],
                        ], $config['detail_action_params']);

                    //Data insert
                    $dataNotificationDetail = [
                        'background' => $config['detail_background'],
                        'content' => $content,
                        'action_name' => $config['detail_action_name'],
                        'action' => $config['detail_action'],
                        'action_params' => $params
                    ];
                    $dataNotification = [
                        'user_id' => $userId,
                        'notification_avatar' => $config['avatar'],
                        'notification_title' => $config['title'],
                        'notification_message' => $message
                    ];

                    $dateCheck = Carbon::now()->format('d/m/Y H:i');

                    if ($config['send_type'] == "in_time") {
                        $dateCheck = Carbon::now()->format("d/m/Y") . $config['value'];
                    } else if ($config['send_type'] == "before" || $config['send_type'] == "after") {
                        $dateCheck = Carbon::parse($info['created_at'])->format('d/m/Y H:i');
                    }

                    return [
                        'dataNotificationDetail' => $dataNotificationDetail,
                        'dataNotification' => $dataNotification,
                        'dateCheck' => $dateCheck
                    ];
                    break;
                case 'ticket_finish_operater';
                case 'ticket_finish_processor';
                    $info = $mTicket->getInfoNoti($objectId);
                    $message = str_replace(['[staff_name]', '[ticket_code]'], [$info['full_name_updated'], $info['ticket_code']], $config['message']);
                    $content = str_replace(['[staff_name]', '[ticket_code]'], [$info['full_name_updated'], $info['ticket_code']], $config['detail_content']);
                    $params = str_replace(
                        [
                            '[:ticket_id]'
                        ],
                        [
                            $info['ticket_id']
                        ], $config['detail_action_params']);

                    //Data insert
                    $dataNotificationDetail = [
                        'background' => $config['detail_background'],
                        'content' => $content,
                        'action_name' => $config['detail_action_name'],
                        'action' => $config['detail_action'],
                        'action_params' => $params
                    ];
                    $dataNotification = [
                        'user_id' => $userId,
                        'notification_avatar' => $config['avatar'],
                        'notification_title' => $config['title'],
                        'notification_message' => $message
                    ];

                    $dateCheck = Carbon::now()->format('d/m/Y H:i');

                    if ($config['send_type'] == "in_time") {
                        $dateCheck = Carbon::now()->format("d/m/Y") . $config['value'];
                    } else if ($config['send_type'] == "before" || $config['send_type'] == "after") {
                        $dateCheck = Carbon::parse($info['created_at'])->format('d/m/Y H:i');
                    }

                    return [
                        'dataNotificationDetail' => $dataNotificationDetail,
                        'dataNotification' => $dataNotification,
                        'dateCheck' => $dateCheck
                    ];
                    break;
                case 'ticket_close_operater';
                case 'ticket_close_processor';
                    $info = $mTicket->getInfoNoti($objectId);
                    $message = str_replace(['[ticket_code]'], [$info['ticket_code']], $config['message']);
                    $content = str_replace(['[ticket_code]'], [$info['ticket_code']], $config['detail_content']);
                    $params = str_replace(
                        [
                            '[:ticket_id]'
                        ],
                        [
                            $info['ticket_id']
                        ], $config['detail_action_params']);

                    //Data insert
                    $dataNotificationDetail = [
                        'background' => $config['detail_background'],
                        'content' => $content,
                        'action_name' => $config['detail_action_name'],
                        'action' => $config['detail_action'],
                        'action_params' => $params
                    ];
                    $dataNotification = [
                        'user_id' => $userId,
                        'notification_avatar' => $config['avatar'],
                        'notification_title' => $config['title'],
                        'notification_message' => $message
                    ];

                    $dateCheck = Carbon::now()->format('d/m/Y H:i');

                    if ($config['send_type'] == "in_time") {
                        $dateCheck = Carbon::now()->format("d/m/Y") . $config['value'];
                    } else if ($config['send_type'] == "before" || $config['send_type'] == "after") {
                        $dateCheck = Carbon::parse($info['created_at'])->format('d/m/Y H:i');
                    }

                    return [
                        'dataNotificationDetail' => $dataNotificationDetail,
                        'dataNotification' => $dataNotification,
                        'dateCheck' => $dateCheck
                    ];
                    break;
                case 'ticket_rating';
                    $info = $mTicket->getInfoNoti($objectId);
                    $message = str_replace(['[staff_name]', '[ticket_code]'], [$info['full_name_created'], $info['ticket_code']], $config['message']);
                    $content = str_replace(['[staff_name]', '[ticket_code]'], [$info['full_name_created'], $info['ticket_code']], $config['detail_content']);
                    $params = str_replace(
                        [
                            '[:ticket_id]'
                        ],
                        [
                            $info['ticket_id']
                        ], $config['detail_action_params']);

                    //Data insert
                    $dataNotificationDetail = [
                        'background' => $config['detail_background'],
                        'content' => $content,
                        'action_name' => $config['detail_action_name'],
                        'action' => $config['detail_action'],
                        'action_params' => $params
                    ];
                    $dataNotification = [
                        'user_id' => $userId,
                        'notification_avatar' => $config['avatar'],
                        'notification_title' => $config['title'],
                        'notification_message' => $message
                    ];

                    $dateCheck = Carbon::now()->format('d/m/Y H:i');

                    if ($config['send_type'] == "in_time") {
                        $dateCheck = Carbon::now()->format("d/m/Y") . $config['value'];
                    } else if ($config['send_type'] == "before" || $config['send_type'] == "after") {
                        $dateCheck = Carbon::parse($info['created_at'])->format('d/m/Y H:i');
                    }

                    return [
                        'dataNotificationDetail' => $dataNotificationDetail,
                        'dataNotification' => $dataNotification,
                        'dateCheck' => $dateCheck
                    ];
                    break;
                default:
                    return [
                        'dataNotificationDetail' => $dataNotificationDetail,
                        'dataNotification' => $dataNotification
                    ];
                    break;
            }
        } catch (\Exception $e) {
            throw new NotificationRepoException(NotificationRepoException::SEND_NOTIFICATION_FAILED, $e->getMessage());
        }
    }

    /**
     * Lưu log thông báo nhân viên
     *
     * @param $dataNotificationDetail
     * @param $dataNotification
     * @param $input
     * @return string
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    private function insertStaffNotifyLog($dataNotificationDetail, $dataNotification, $input)
    {
        try {
            $mNotificationDetail = app()->get(StaffNotificationDetailTable::class);
            $mStaff = app()->get(StaffTable::class);
            $mReceiver = app()->get(StaffNotificationReceiverTable::class);
            $mMapRoleGroup = app()->get(MapRoleGroupStaffTable::class);

            $arrRoleGroup = [];

            //Lấy nhóm quyền được nhận notify
            $getReceiver = $mReceiver->getReceiverByKey($input['key']);

            if (count($getReceiver) > 0) {
                foreach ($getReceiver as $v) {
                    $arrRoleGroup [] = $v['role_group_id'];
                }
            }

            //Lấy ds nhân viên
            $getStaff = $mMapRoleGroup->getStaffByArrayRole($arrRoleGroup, $input['branch_id']);

            if (count($getStaff) > 0) {
                foreach ($getStaff as $v) {
                    //Insert notification detail
                    $idNotificationDetail = $mNotificationDetail->add($dataNotificationDetail);
                    //Push notification
                    $oClient = new Client();

                    $response = $oClient->request('POST', NAE_SERVICE_URL . '/notification/push', [
                        'json' => [
                            'tenant_id' => session()->get('idTenant'),
                            'staff_id' => $v['staff_id'],
                            'title' => $dataNotification['notification_title'],
                            'message' => $dataNotification['notification_message'],
                            'detail_id' => $idNotificationDetail,
                            'avatar' => $dataNotification['notification_avatar']
                        ]
                    ]);
                }
            }
        } catch (\Exception $exception) {
            return '';
        }
    }

    /**
     * Push noti ticket
     * @param $dataNotificationDetail
     * @param $dataNotification
     * @return string
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function insertStaffNotifyLogTicket($dataNotificationDetail, $dataNotification, $staff_id)
    {
        try {
            $mNotification = app()->get(StaffNotificationTable::class);
            $mNotificationDetail = app()->get(StaffNotificationDetailTable::class);

            $idNotificationDetail = $mNotificationDetail->add($dataNotificationDetail);
            //Push notification
            $oClient = new Client();

            $response = $oClient->request('POST', NAE_SERVICE_URL . '/notification/push', [
                'json' => [
                    'tenant_id' => session()->get('idTenant'),
                    'staff_id' => $staff_id,
                    'title' => $dataNotification['notification_title'],
                    'message' => $dataNotification['notification_message'],
                    'detail_id' => $idNotificationDetail,
                    'avatar' => $dataNotification['notification_avatar']
                ]
            ]);
        } catch (\Exception $exception) {
            return '';
        }
    }

    /**
     * Gửi thông báo nhân viên không lưu dữ liệu
     *
     * @param $input
     * @return mixed|void
     * @throws NotificationRepoException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function sendNotifyNotData($input)
    {
        try {
            //Push notification
            $oClient = new Client();

            $response = $oClient->request('POST', NAE_SERVICE_URL . '/notification/push', [
                'json' => [
                    'tenant_id' => session()->get('idTenant'),
                    'staff_id' => $input['staff_id'],
                    'title' => $input['title'],
                    'message' => $input['message'],
                    'data' => isset($input['data']) ? $input['data'] : []
                ]
            ]);

//            dd(json_decode($response->getBody(), true));
        } catch (\Exception $e) {
            throw new NotificationRepoException(NotificationRepoException::SEND_NOTIFICATION_FAILED, $e->getMessage());
        }
    }

}