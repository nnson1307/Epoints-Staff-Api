<?php


namespace Modules\Ticket\Repositories;


use App\Jobs\FunctionSendNotify;
use Carbon\Carbon;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Modules\Home\Models\ServiceCardTable;
use Modules\Home\Models\ServiceTable;
use Modules\Home\Repositories\Home\HomeRepoInterface;
use Modules\Notification\Http\Controllers\NotificationController;
use Modules\Notification\Repositories\Notification\NotificationRepo;
use Modules\Notification\Repositories\Notification\NotificationRepoInterface;
use Modules\Ticket\Libs\UploadImage;
use Modules\Product\Repositories\Product\ProductRepo;
use Modules\Product\Repositories\Product\ProductRepoException;
use Modules\Ticket\Models\CustomerTable;
use Modules\Ticket\Models\ManageWorkTable;
use Modules\Ticket\Models\ProductChildTable;
use Modules\Ticket\Models\ProductImageTable;
use Modules\Ticket\Models\ProductInventorysTable;
use Modules\Ticket\Models\PromotionDailyTimeTable;
use Modules\Ticket\Models\PromotionDateTimeTable;
use Modules\Ticket\Models\PromotionDetailTable;
use Modules\Ticket\Models\PromotionMonthlyTimeTable;
use Modules\Ticket\Models\PromotionObjectApplyTable;
use Modules\Ticket\Models\PromotionWeeklyTimeTable;
use Modules\Ticket\Models\TicketAcceptanceIncurredTable;
use Modules\Ticket\Models\TicketAcceptanceTable;
use Modules\Ticket\Models\TicketAlertTable;
use Modules\Ticket\Models\TicketFileTable;
use Modules\Ticket\Models\TicketHistoryTable;
use Modules\Ticket\Models\TicketIssueGroupTable;
use Modules\Ticket\Models\TicketIssueTable;
use Modules\Ticket\Models\TicketOperaterTable;
use Modules\Ticket\Models\TicketProcessorTable;
use Modules\Ticket\Models\TicketRatingTable;
use Modules\Ticket\Models\TicketRequestMaterialDetailTable;
use Modules\Ticket\Models\TicketRequestMaterialTable;
use Modules\Ticket\Models\TicketRoleQueueTable;
use Modules\Ticket\Models\TicketRoleStatusMapTable;
use Modules\Ticket\Models\TicketStaffQueueMapTable;
use Modules\Ticket\Models\TicketStaffQueueTable;
use Modules\Ticket\Models\TicketStatusTable;
use Modules\Ticket\Models\TicketTable;
use Modules\Ticket\Models\TicketLocationTable;
use Modules\Ticket\Models\ContractTable;
use Modules\Ticket\Models\TicketQueueTable;
use Modules\Ticket\Models\TicketCommentTable;
use MyCore\Repository\PagingTrait;

class TicketRepository implements TicketRepositoryInterface
{
    use PagingTrait;

    protected $mTicket;
    protected $notiRepo;

    const STATUS_NEW = 1; //Mới
    const STATUS_PROCESSING = 2; // Đang xử lý
    const STATUS_COMPLETED = 3; //Hoàn thành
    const STATUS_CLOSE = 4; //Đóng
    const STATUS_CANCEL = 5; //Huỷ
    const STATUS_REOPEN = 6; //Reopen

    public function __construct(TicketTable $mTicket, NotificationRepoInterface $notiRepo)
    {
        $this->mTicket = $mTicket;
        $this->notiRepo = $notiRepo;
    }


    public function getQueue()
    {

//            Lấy danh sách ticket mà user quản lý

        $ticketStaffQueueMap = new TicketStaffQueueMapTable();
        $ticketRoleQueue = new TicketRoleQueueTable();

        //Lấy ds queue của nhân viên (bảng map 1 nv có nhiều queue)
        $listQueueView = $ticketStaffQueueMap->getListQueueView(Auth::id());
        //Lấy ds queue của nhân viên (cũ 1 nhân viên 1 queue)
//        $listQueueView = $ticketRoleQueue->getTicketRoleQueue(Auth::id());

        $roleStaff = null;
        $listQueue = [];

        foreach ($listQueueView as $item) {
            $roleStaff = $item['ticket_role_queue_id'];
            $listQueue[$item['ticket_queue_id']] = $item['ticket_queue_id'];
            $listQueue[$item['ticket_view_queue_id']] = $item['ticket_view_queue_id'];
        }

        return [
            'roleStaff' => $roleStaff,
            'listQueue' => $listQueue
        ];
    }

//    Tổng ticket trang home
    public function getTotalTicket()
    {
        try {
            $roleStaff = null;
            $listQueue = [];

            $getQueue = $this->getQueue();

            $roleStaff = $getQueue['roleStaff'];
            $listQueue = $getQueue['listQueue'];

//            $totalTicket = $this->mTicket->getTotalForStatus(Auth::id(),[self::STATUS_NEW, self::STATUS_PROCESSING],$roleStaff,$listQueue,'unfinished');
//            $totalTicket = $this->mTicket->getTotalForStatus(Auth::id(), [], $roleStaff, $listQueue, 'unfinished');

//            Lấy tổng ticket theo trạng thái mới
            // $totalNew = $this->mTicket->getTotalForStatusUnexpired(Auth::id(),[self::STATUS_NEW],$roleStaff,$listQueue);
            $totalNew = $this->mTicket->getTotalTicketNew();

//            Tổng ticket của tôi
//             $totalMyTicket = $this->mTicket->getTotalForStatus(Auth::id(),[],$roleStaff,$listQueue,'my-ticket');
            $totalMyTicket = $this->mTicket->getTotalTicketForMe();

//            Ticket chưa hoàn thành (đang xử lý)
            // $totalUnfinished = $this->mTicket->getTotalForStatus(Auth::id(),[self::STATUS_NEW, self::STATUS_PROCESSING,self::STATUS_COMPLETED],$roleStaff,$listQueue,'unfinished');
            $totalUnfinished = $this->mTicket->getTotalTicketProccessing();

//            Ticket quá hạn
            // $totalMyTicketExpired = $this->mTicket->getTotalForStatusExpired(Auth::id(),[self::STATUS_NEW, self::STATUS_PROCESSING,self::STATUS_COMPLETED],$roleStaff,$listQueue);
            $totalMyTicketExpired = $this->mTicket->getTotalTicketExpire();

            //Total = 4 cái + lại (update 20/03/2023)
            $totalTicket = $totalNew  + $totalUnfinished + $totalMyTicketExpired;

            $chart = $this->mTicket->getChart(Auth::id(), [self::STATUS_CLOSE], $roleStaff, $listQueue);
            $arrChart = [];

            foreach ($chart as $item) {
                $arrChart[$item['fulltime']][$item['fulltime']] = $item['totalTicket'];
            }

            $data = [
                'total_ticket' => $totalTicket,
                'new_ticket' => $totalNew,
                'my_ticket' => $totalMyTicket,
                'unfinished_ticket' => $totalUnfinished,
                'overdue_ticket' => $totalMyTicketExpired,
                'chart' => array_values($arrChart)
            ];
            return $data;
        } catch (\Exception|QueryException $e) {
            throw new TicketRepoException(TicketRepoException::GET_TICKET_LIST_FAILED, $e->getMessage());
        }
    }

//    Danh sách ticket mới , của tôi, quá hạn .
    public function getMyTicket($data)
    {
        try {
            $data['staff_id'] = Auth::id();

            $getQueue = $this->getQueue();

            $data['roleStaff'] = $getQueue['roleStaff'];
            $data['listQueue'] = $getQueue['listQueue'];

//            Kiểm tra nếu là tìm kiếm theo ticket mới, ticket của tôi, ticket quá hạn
            if (isset($data['type_ticket'])) {
                if ($data['type_ticket'] != 'my-ticket') {
                    if ($data['type_ticket'] == 'new') {
                        $data['ticket_status_id'] = self::STATUS_NEW;
                    } else {
                        unset($data['ticket_status_id']);
                    }
                }
            }
            $list = $this->mTicket->getMyTicket($data);

            $mTicketAlert = new TicketAlertTable();

            if (isset($data['type_ticket'])) {
                if ($data['type_ticket'] == 'expired') {
                    foreach ($list as $key => $item) {
                        $minutes = Carbon::now()->diffInMinutes($item['date_expected']);
                        $hours = Carbon::now()->diffInHours($item['date_expected']);
                        $day = Carbon::now()->diffInDays($item['date_expected']);
//                        Thời gian quá hạn
                        if ($minutes < 60) {
                            $list[$key]['expired_time'] = $minutes . ' ' . __('Phút');
                        } else {
                            if ($hours < 24) {
                                $list[$key]['expired_time'] = $hours . ' ' . __('Giờ') . ' ';
                                if (($minutes - $hours * 60) != 0) {
                                    $list[$key]['expired_time'] = $list[$key]['expired_time'] . ($minutes - $hours * 60) . ' ' . __('Phút');
                                }
                            } else {
                                $list[$key]['expired_time'] = $day . ' ' . __('Ngày') . ' ';
                                if (($hours - $day * 24) != 0) {
                                    $list[$key]['expired_time'] = $list[$key]['expired_time'] . ($hours - $day * 24) . ' ' . __('Giờ') . ' ';
                                }
                                if (($minutes - ($day * 24 * 60 + ($hours - $day * 24) * 60)) != 0) {
                                    $list[$key]['expired_time'] = $list[$key]['expired_time'] . ($minutes - ($day * 24 * 60 + ($hours - $day * 24) * 60)) . ' ' . __('Phút');
                                }
                            }
                        }

                        $getAlert = $mTicketAlert->checkTimeAlert($minutes);
//                        Mức độ cảnh báo
                        $list[$key]['warning_level'] = '';
                        if ($getAlert != null) {
                            if ($getAlert['ticket_alert_id'] == 1) {
                                $list[$key]['warning_level'] = __('Cảnh báo mức 1');
                            } else if ($getAlert['ticket_alert_id'] == 2) {
                                $list[$key]['warning_level'] = __('Cảnh báo mức 2');
                            } else if ($getAlert['ticket_alert_id'] == 3) {
                                $list[$key]['warning_level'] = __('Cảnh báo mức 3');
                            }
                        }
                    }
                }
            }

            return $list;
        } catch (\Exception|QueryException $exception) {
            throw new TicketRepoException(TicketRepoException::GET_TICKET_LIST_FAILED);
        }
    }

//    Danh sách ticket , chưa phân công và đã phân công
    public function getListTicket($data)
    {
        try {
            $data['staff_id'] = Auth::id();

            $getQueue = $this->getQueue();

            $data['arr_queue'] = $getQueue['listQueue'];

            $list = $this->mTicket->getListTicket($data);
            return $list;
        } catch (\Exception|QueryException $exception) {
            throw new TicketRepoException(TicketRepoException::GET_TICKET_LIST_FAILED);
        }
    }

//    Danh sách ticket chưa hoàn thành
    public function getTicketNotCompleted()
    {
        try {
//            $data['arr_ticket_status_id'] = [self::STATUS_NEW,self::STATUS_PROCESSING,self::STATUS_COMPLETED];
            $data['staff_id'] = Auth::id();

            $ticketStatus = new TicketStatusTable();

            $getQueue = $this->getQueue();

            $data['roleStaff'] = $getQueue['roleStaff'];
            $data['listQueue'] = $getQueue['listQueue'];

            $listNotCompleted = $this->mTicket->getListNotCompleted($data);

            $info = [];
            if (count($listNotCompleted) != 0) {
                $listNotCompleted = collect($listNotCompleted)->groupBy('ticket_queue_id');

                foreach ($listNotCompleted as $key => $item) {
                    $queue_total = $new_total = $processing_total = $completed_total = $overdue_total = 0;
                    $new_total = collect($item)->where('ticket_status_id', 1)->where('date_expected', '>=', Carbon::now())->count();
                    $processing_total = collect($item)->where('ticket_status_id', 2)->where('date_expected', '>=', Carbon::now())->count();
                    $completed_total = collect($item)->where('ticket_status_id', 3)->count();
                    $overdue_total = collect($item)->where('ticket_status_id', '<>', 3)->where('date_expected', '<', Carbon::now())->count();
                    $info[$key] = [
                        'ticket_queue_id' => $key,
                        'queue_name' => $item[0]['queue_name'],
                        'queue_total' => $new_total + $processing_total + $completed_total + $overdue_total,
//                        'new_total' => $new_total,
//                        'processing_total' => $processing_total,
//                        'completed_total' => $completed_total,
//                        'overdue_total' => $overdue_total,
                        'items' => $ticketStatus->getListTicketForNotCompleted([1, 2, 3], $new_total, $processing_total, $completed_total)
                    ];

                    $info[$key]['items'][] = [
                        "status_name" => "Quá hạn",
                        "overdue_ticket_check" => 1,
                        "total" => $overdue_total
                    ];
                }
            }
            return array_values($info);
        } catch (\Exception|QueryException $exception) {
            throw new TicketRepoException(TicketRepoException::GET_TICKET_NOT_COMPLETED_FAILED);
        }
    }

//    Kiểm tra tài khoản được phân công cho ticket
    public function checkTicket($ticketId)
    {
//        Kiểm tra phân công theo chủ trì
        $mTicket = new TicketTable();
        $mTicketOperator = new TicketOperaterTable();
        $mTicketProcessor = new TicketProcessorTable();
        $mTicketRoleStatusMap = new TicketRoleStatusMapTable();

        $detailTicket = $mTicket->ticketDetailByTicket($ticketId);
//        Check role
        $checkRoleStaatus = $mTicketRoleStatusMap->checkRoleStatus(Auth::id(), $detailTicket['ticket_status_id']);

        if ($checkRoleStaatus != null) {
            $check = $mTicket->checkTicket($ticketId, Auth::id());
            if ($check != null) {
                return 1;
            } else {
                $check = $mTicketProcessor->checkTicket($ticketId, Auth::id());
                if ($check != null) {
                    return 1;
                }
                return 0;
            }
        } else {
            return 0;
        }

    }

//    Chi tiết ticket
    public function getDetail($data)
    {
        try {

            $mTicketOperater = new TicketOperaterTable();
            $mTicketProcessor = new TicketProcessorTable();
            $mTicketFile = new TicketFileTable();
            $mTicketLocation = new TicketLocationTable();


            //Lấy thông tin ticket
            $info = $this->mTicket->getDetail($data['ticket_id']);

            $info['manage_work_customer_type'] = 'customer';

//            $info['staff_host'] = $mTicketOperater->getListHostStaff($data['ticket_id']);
            $info['staff_handler'] = $mTicketProcessor->getListHandlerStaff($data['ticket_id']);
            $info['attached'] = $mTicketFile->getListFile($data['ticket_id'], 'ticket');
            if (!\Auth::user()->is_admin) {
                $info['edit_ticket'] = $this->checkTicket($data['ticket_id']);
            } else {
                $info['edit_ticket'] = 1;
            }

            $info['list_status'] = $this->listStatus($data['ticket_id']);
            $info['list_location'] = $mTicketLocation->getListLocation($data['ticket_id']);

            if (isset($info['date_estimated']) && $info['date_estimated'] == '0000-00-00 00:00:00') {
                $info['date_estimated'] = null;
            }


            return $info;
        } catch (\Exception|QueryException $exception) {
            throw new TicketRepoException(TicketRepoException::GET_TICKET_DETAIL_FAILED, $exception->getMessage());
        }
    }

//    Chỉnh sửa ticket
    public function editTicket($data)
    {
        try {
            DB::beginTransaction();
            $ticketId = $data['ticket_id'];

            $mTicketOperator = new TicketOperaterTable();
            $mTicketProcessor = new TicketProcessorTable();

            $ticketDetail = $this->mTicket->getDetail($data['ticket_id']);
            $ticketDetailGetCreated = $this->mTicket->ticketDetailByTicket($data['ticket_id']);

            $infoUpdate = [
                'ticket_issue_group_id' => isset($data['ticket_issue_group_id']) ? $data['ticket_issue_group_id'] : null,
                'ticket_issue_id' => $data['ticket_issue_id'],
                'title' => $data['title'],
                'description' => isset($data['description']) ? $data['description'] : null,
                'customer_address' => isset($data['customer_address']) ? $data['customer_address'] : null,
                'date_estimated' => isset($data['date_estimated']) ? Carbon::createFromFormat('d/m/Y H:i', $data['date_estimated'])->format('Y-m-d H:i:00') : null,
                'ticket_status_id' => $data['ticket_status_id'],
                'updated_by' => Auth::id(),
                'updated_at' => Carbon::now()
            ];

            $finish = 0;
            $close = 0;
            if (isset($data['ticket_status_id']) && $data['ticket_status_id'] != $ticketDetail['ticket_status_id']) {
                if ($data['ticket_status_id'] == 3) {
                    $finish = 1;
                }

                if ($data['ticket_status_id'] == 4) {
                    $close = 1;
                }
            }

            if (isset($data['ticket_status_id']) && $data['ticket_status_id'] == 3) {
                $infoUpdate['date_finished'] = Carbon::now();
            }

            if (isset($data['staff_host_id'])) {
                if ($data['staff_host_id'] == null) {
                    throw new TicketRepoException(TicketRepoException::GET_TICKET_EDIT_FAILED);
                }

                $infoUpdate['operate_by'] = $data['staff_host_id'];
            }

            if (isset($data['ticket_queue_id'])) {
                $infoUpdate['queue_process_id'] = $data['ticket_queue_id'];
            }

//            Cập  nhật ticket
            $updateTicket = $this->mTicket->updateTicket($ticketId, $infoUpdate);

//            Cập nhật danh sách nhân viên xử lý
            if (isset($data['staff_handler'])) {
                if (count($data['staff_handler']) == 0) {
                    throw new TicketRepoException(TicketRepoException::GET_TICKET_EDIT_FAILED);
                }
//                Xoá nhân viên chủ trì cũ
                $mTicketProcessor->deleteListStaff($data['ticket_id']);
                $arrStaff = [];
                foreach ($data['staff_handler'] as $item) {
                    $arrStaff[] = [
                        'ticket_id' => $data['ticket_id'],
                        'process_by' => $item['staff_id'],
                        'created_at' => Carbon::now(),
                        'created_by' => Auth::id(),
                        'updated_at' => Carbon::now(),
                        'updated_by' => Auth::id()
                    ];
                }

                $mTicketProcessor->createdStaff($arrStaff);

            }

            $ticketNew = $this->mTicket->getDetail($data['ticket_id']);

            if ($ticketDetail['status_name'] != $ticketNew['status_name']) {
                $note = 'Đã cập nhật trạng thái ticket thành ' . $ticketNew['status_name'];
            } else {
                $note = 'Đã cập thông tin ticket';
            }

            $this->createHistory($ticketId, $note);

//            lấy danh sách nhân viên được phân công

            $listCustomer = $this->listStaffs($data['ticket_id']);

            DB::commit();

            foreach ($listCustomer as $itemCustomer) {

                $keyNoti = '';
                if ($finish == 1 || $close == 1) {
                    if ($finish == 1) {
                        if ($itemCustomer == $ticketDetail['staff_host_id']) {
                            $keyNoti = 'ticket_finish_operater';
                        } else {
                            $keyNoti = 'ticket_finish_processor';
                        }
                    }

                    if ($close == 1) {
                        if ($itemCustomer == $ticketDetail['staff_host_id']) {
                            $keyNoti = 'ticket_close_operater';
                        } else {
                            $keyNoti = 'ticket_close_processor';
                        }
                    }

                    $varNoti = [
                        'key' => $keyNoti,
                        'customer_id' => $itemCustomer,
                        'object_id' => $data['ticket_id']

                    ];
                } else {
                    $varNoti = [
                        'key' => 'ticket_edit',
                        'customer_id' => $itemCustomer,
                        'object_id' => $data['ticket_id']

                    ];
                }

                $this->notiRepo->sendStaffNotification($varNoti);
            }

            if ($finish == 1) {
                $varNoti = [
                    'key' => 'ticket_finish_processor',
                    'customer_id' => $ticketDetailGetCreated['created_by'],
                    'object_id' => $data['ticket_id']

                ];
                $this->notiRepo->sendStaffNotification($varNoti);
            }
            $detail = $this->getDetail($data);

            return $detail;
        } catch (\Exception|QueryException $exception) {
            DB::rollBack();
            throw new TicketRepoException(TicketRepoException::GET_TICKET_EDIT_FAILED);
        }
    }

//    Tạo phiếu yêu cầu
    public function addRequestForm($data)
    {
        try {
            DB::beginTransaction();

            if (!isset($data['material']) || count($data['material']) == 0) {
                throw new TicketRepoException(TicketRepoException::GET_MATERIAL_LIST_FAILED);
            };
            $requestMaterial = [
                'ticket_request_material_code' => $this->createdCode('request-form'),
                'ticket_id' => $data['ticket_id'],
                'proposer_by' => Auth::id(),
                'proposer_date' => Carbon::now(),
                'description' => $data['description'],
                'status' => 'new',
                'created_at' => Carbon::now(),
                'created_by' => Auth::id(),
                'updated_at' => Carbon::now(),
                'updated_by' => Auth::id()
            ];

            $mRequestMaterial = new TicketRequestMaterialTable();

//            Tạo phiếu yêu cầu vật tư
            $idRequestMaterial = $mRequestMaterial->createdRequestForm($requestMaterial);

            if (isset($data['material']) && count($data['material']) != 0) {
                $arrProduct = [];
                foreach ($data['material'] as $item) {
                    $arrProduct[] = [
                        'ticket_request_material_id' => $idRequestMaterial,
                        'product_id' => $item['product_id'],
                        'quantity' => $item['quantity'],
                        'quantity_approve' => 0,
                        'quantity_return' => 0,
                        'status' => 'new'
                    ];
                }
                $mRequestMaterialDetail = new TicketRequestMaterialDetailTable();
                $mRequestMaterialDetail->createdRequestFormDetail($arrProduct);
            }

            $note = 'Đã tạo phiếu yêu cầu vật tư';
            $this->createHistory($data['ticket_id'], $note);

            $listCustomer = $this->listStaffs($data['ticket_id']);

            DB::commit();

            foreach ($listCustomer as $item) {
                $varNoti = [
                    'key' => 'request_material_create',
                    'customer_id' => $item,
                    'object_id' => $idRequestMaterial

                ];
                $this->notiRepo->sendStaffNotification($varNoti);
            }

            $detail = $this->infoMaterialsDetail(['ticket_request_material_id' => $idRequestMaterial]);

            return $detail;

        } catch (\Exception|QueryException $exception) {
            DB::rollBack();
            throw new TicketRepoException(TicketRepoException::GET_REQUEST_FORM_ADD_FAILED);
        }
    }

//    Chỉnh sửa phiếu yêu cầu
    public function editRequestForm($data)
    {
        try {

            DB::beginTransaction();

            if (!isset($data['material']) || count($data['material']) == 0) {
                throw new TicketRepoException(TicketRepoException::GET_MATERIAL_LIST_FAILED);
            }
            $mRequestMaterialDetail = new TicketRequestMaterialDetailTable();

            $ticketRequestMaterialId = $data['ticket_request_material_id'];

            $requestMaterial = [
                'description' => $data['description'],
                'updated_at' => Carbon::now(),
                'updated_by' => Auth::id()
            ];

            $mRequestMaterial = new TicketRequestMaterialTable();

//            Cập nhật phiếu yêu cầu vật tư
            $idRequestMaterial = $mRequestMaterial->updateRequestForm($requestMaterial, $ticketRequestMaterialId);

            if (isset($data['material']) && count($data['material']) != 0) {
                $arrProduct = [];
//                Xoá các vậy tư bị dư
                $arrIdDetail = collect($data['material'])->pluck('ticket_request_material_detail_id')->toArray();
                $arrIdDetail = array_filter($arrIdDetail, function ($var) {
                    return !is_null($var);
                });
                $mRequestMaterialDetail->deleteRequestFormDetail($arrIdDetail, $ticketRequestMaterialId);

                foreach ($data['material'] as $item) {
//                    Cập nhật các vật tư
                    if (isset($item['ticket_request_material_detail_id'])) {
                        $mRequestMaterialDetail->updateRequestFormDetail(['quantity' => $item['quantity']], $item['ticket_request_material_detail_id']);
                    } else {
//                        Thêm vật tư
                        $arrProduct[] = [
                            'ticket_request_material_id' => $ticketRequestMaterialId,
                            'product_id' => $item['product_id'],
                            'quantity' => $item['quantity'],
                            'quantity_approve' => 0,
                            'quantity_return' => 0,
                            'quantity_reality' => 0,
                            'status' => 'new'
                        ];
                    }
                }
                if (count($arrProduct) != 0) {
                    $mRequestMaterialDetail->createdRequestFormDetail($arrProduct);
                }
            }

//            Lấy thông tin phiếu yêu cầu vật tư

            $requestMaterialDetail = $mRequestMaterial->getDetail($ticketRequestMaterialId);

            $note = 'Đã cập nhật phiếu yêu cầu vật tư';
            $this->createHistory($requestMaterialDetail['ticket_id'], $note);

            $listCustomer = $this->listStaffs($requestMaterialDetail['ticket_id']);

            DB::commit();

            foreach ($listCustomer as $item) {
                $varNoti = [
                    'key' => 'request_material_edit',
                    'customer_id' => $item,
                    'object_id' => $ticketRequestMaterialId

                ];
                $this->notiRepo->sendStaffNotification($varNoti);
            }

            $detail = $this->infoMaterialsDetail(['ticket_request_material_id' => $ticketRequestMaterialId]);

            return $detail;
        } catch (\Exception|QueryException $exception) {
            throw new TicketRepoException(TicketRepoException::GET_REQUEST_FORM_EDIT_FAILED);
        }
    }

//    Xoá phiếu yêu cầu
    public function deleteRequestForm($data)
    {
        try {
            $mTicketRequestMaterial = new TicketRequestMaterialTable();
            $mTicketRequestMaterialDetail = new TicketRequestMaterialDetailTable();

            $dataUpdate = [
                'updated_at' => Carbon::now(),
                'updated_by' => Auth::id()
            ];
            $mTicketRequestMaterial->updateRequestForm($dataUpdate, $data['ticket_request_material_id']);

            $requestMaterialDetail = $mTicketRequestMaterial->getDetail($data['ticket_request_material_id']);

            $note = 'Đã xoá phiếu yêu cầu vật tư';
            $this->createHistory($requestMaterialDetail['ticket_id'], $note);

            $listCustomer = $this->listStaffs($requestMaterialDetail['ticket_id']);

            foreach ($listCustomer as $item) {
                $varNoti = [
                    'key' => 'request_material_remove',
                    'customer_id' => $item,
                    'object_id' => $data['ticket_request_material_id']

                ];
                $this->notiRepo->sendStaffNotification($varNoti);
            }

            $mTicketRequestMaterial->deleteForm($data['ticket_request_material_id']);
            $mTicketRequestMaterialDetail->deleteForm($data['ticket_request_material_id']);

            return true;

        } catch (\Exception|QueryException $exception) {
            throw new TicketRepoException(TicketRepoException::GET_REQUEST_FORM_DELETE_FAILED);
        }
    }

//    Thông tin vật tư
    public function infoMaterials($data)
    {
        try {
            $mRequestMaterial = new TicketRequestMaterialTable();
            $mRequestMaterialDetail = new TicketRequestMaterialDetailTable();
            $mTicketAcceptanceIncurred = new TicketAcceptanceIncurredTable();
//            Lấy danh sách phiếu yêu cầu

            $listRequestMaterial = $mRequestMaterial->listRequestMaterial($data);
            $arrInfo['request_form'] = $listRequestMaterial;
            if (count($listRequestMaterial) != 0) {
//                Lấy danh sách id phiếu yêu cầu đề xuất
                $tmpListIdRequestMaterial = collect($listRequestMaterial)->pluck('ticket_request_material_id')->toArray();

//                Lấy danh sách vật tư của yêu cầu đề xuất
                $arrInfo['proposed-materials'] = $mRequestMaterialDetail->listRequestMaterialDetailList($tmpListIdRequestMaterial);
            }

            $arrInfo['incurred-materials'] = [];
            if (isset($data['ticket_id'])) {
                $arrInfo['incurred-materials'] = $mTicketAcceptanceIncurred->getAcceptanceIncurredByTicket($data['ticket_id']);
            }

            return $arrInfo;
        } catch (\Exception|QueryException $exception) {
            throw new TicketRepoException(TicketRepoException::GET_MATERIALS_FAILED);
        }
    }

//    Thông tin vật tư chi tiết
    public function infoMaterialsDetail($data)
    {
        try {

            $mRequestMaterial = new TicketRequestMaterialTable();
            $mRequestMaterialDetail = new TicketRequestMaterialDetailTable();

            $info = $mRequestMaterial->getDetail($data['ticket_request_material_id']);
            $info['proposed-materials'] = $mRequestMaterialDetail->listRequestMaterialDetail([$data['ticket_request_material_id']]);

            return $info;
        } catch (\Exception|QueryException $exception) {
            return [
                'message' => $exception->getMessage() . '|' . $exception->getLine()
            ];
            throw new TicketRepoException(TicketRepoException::GET_MATERIALS_DETAIL_FAILED);
        }
    }

//    Chi tiết đánh giá
    public function ratingDetail($data)
    {
        try {

            $mTicketRating = new TicketRatingTable();

            $info = $mTicketRating->getRating($data['ticket_id']);

            return $info;
        } catch (\Exception|QueryException $exception) {
            throw new TicketRepoException(TicketRepoException::GET_RATING_DETAIL_FAILED);
        }
    }

//    Lịch sử
    public function getHistory($data)
    {
        try {

            $mTicketHistory = new TicketHistoryTable();

            $list = $mTicketHistory->getListHistory($data['ticket_id']);

            return $list;
        } catch (\Exception|QueryException $exception) {
            throw new TicketRepoException(TicketRepoException::GET_HISTORY_FAILED);
        }
    }

    public function getImage($data)
    {
        try {

            $mTicketFile = new TicketFileTable();

            $listImage = $mTicketFile->getListFile($data['ticket_id'], 'image');

            return $listImage;
        } catch (\Exception|QueryException $exception) {
            throw new TicketRepoException(TicketRepoException::GET_IMAGE_FAILED);
        }
    }

//    Thêm hình ảnh
    public function addImage($data)
    {
        try {

            $mTicketFile = new TicketFileTable();

//            Xoá ảnh cũ
            $mTicketFile->deleteFile($data['ticket_id'], 'image');

            $arrList = [];

            if (isset($data['list-image']) && count($data['list-image']) != 0) {
                foreach ($data['list-image'] as $item) {
                    $arrList[] = [
                        'ticket_id' => $data['ticket_id'],
                        'type' => 'image',
                        'group' => 'image',
                        'path' => $item['path'],
                        'created_at' => Carbon::now(),
                        'created_by' => Auth::id(),
                        'updated_at' => Carbon::now()
                    ];
                }

//                Tạo hình ảnh mới
                $mTicketFile->createFile($arrList);
            }

            $listCustomer = $this->listStaffs($data['ticket_id']);

            foreach ($listCustomer as $item) {
                $varNoti = [
                    'key' => 'ticket_image',
                    'customer_id' => $item,
                    'object_id' => $data['ticket_id']

                ];
                $this->notiRepo->sendStaffNotification($varNoti);
            }


            return true;
        } catch (\Exception|QueryException $exception) {
            throw new TicketRepoException(TicketRepoException::GET_ADD_IMAGE_FAILED);
        }
    }

//    Thông tin phiếu nghiệm thu
    public function acceptanceRecord($data)
    {
        try {

            $mTicketAcceptance = new TicketAcceptanceTable();
            $mRequestMaterialDetail = new TicketRequestMaterialDetailTable();
            $mTicketAcceptanceIncurred = new TicketAcceptanceIncurredTable();
            $mTicketFile = new TicketFileTable();
            $mTicket = new TicketTable();

            $detailTicket = $mTicket->getDetail($data['ticket_id']);

            $info = $mTicketAcceptance->getDetail($data['ticket_id']);
            $info['acceptance_name_default'] = __('Biên bản nghiệm thu ticket ') . $detailTicket['ticket_code'];
            $info['proposed-materials'] = $mRequestMaterialDetail->listRequestMaterialAcceptanceDetailByTicketId($data['ticket_id']);
            $info['incurred-materials'] = [];
            if (isset($info['ticket_acceptance_id'])) {
                $info['incurred-materials'] = $mTicketAcceptanceIncurred->getAcceptanceIncurred($info['ticket_acceptance_id']);
            }

            $info['attached'] = $mTicketFile->getListFile($data['ticket_id'], 'acceptance');


            return $info;
        } catch (\Exception|QueryException $exception) {
            throw new TicketRepoException(TicketRepoException::GET_ACCEPTANCE_FAILED);
        }
    }

//    Tạo biên bản nghiệm thu
    public function acceptanceRecordCreate($data)
    {
        try {
            DB::beginTransaction();
//            Kiểm tra danh sách vật tư

            if (!isset($data['proposed-materials']) || count($data['proposed-materials']) == 0) {
                throw new TicketRepoException(TicketRepoException::GET_MATERIAL_LIST_FAILED);
            }
            $mTicket = new TicketTable();
            $mTicketAcceptance = new TicketAcceptanceTable();
            $mTicketAcceptanceIncurred = new TicketAcceptanceIncurredTable();
            $mticketMaterialDetail = new TicketRequestMaterialDetailTable();

            $ticketAcceptance = $mTicketAcceptance->getDetail($data['ticket_id']);
            if ($ticketAcceptance != null) {
                throw new TicketRepoException(TicketRepoException::CREATED_ACCEPTANCE_FAILED);
            }

//            Chi tiết ticket

            $ticketDetail = $mTicket->ticketDetailByTicket($data['ticket_id']);
//            Tạo bản nghiệm thu

            $dataAcceptance = [
                'ticket_acceptance_code' => $this->createdCode('acceptance'),
                'ticket_id' => $data['ticket_id'],
                'title' => $data['title'],
                'customer_id' => $ticketDetail['customer_id'],
                'status' => 'new',
                'created_at' => Carbon::now(),
                'created_by' => Auth::id(),
                'updated_at' => Carbon::now(),
                'updated_by' => Auth::id()
            ];

            $ticketAcceptanceId = $mTicketAcceptance->createdTicketAcceptance($dataAcceptance);

//            Tạo file đính kèm

            if (count($data['attached']) != 0) {
                $listFile = [];
                $ticketFile = new TicketFileTable();
                foreach ($data['attached'] as $item) {
                    $listFile[] = [
                        'ticket_id' => $data['ticket_id'],
                        'type' => 'file',
                        'group' => 'acceptance',
                        'path' => $item['path'],
                        'created_at' => Carbon::now(),
                        'created_by' => Auth::id(),
                        'updated_at' => Carbon::now()
                    ];
                }
                $ticketFile->createFile($listFile);
            }

//            Cập nhật vật tư
            if (isset($data['proposed-materials']) && count($data['proposed-materials']) != 0) {
                foreach ($data['proposed-materials'] as $item) {
//                lấy chi tiết vật tư
                    $detailMaterialDetail = $mticketMaterialDetail->getDetail($item['ticket_request_material_detail_id']);

//                Số lượng hoàn ứng . Nếu số lượng hoàn ứng lớn hơn 0 thì do chưa dùng hết số lượng được duyệt
                    $quantity_return = $detailMaterialDetail['quantity_approve'] - $item['quantity_reality'];
                    $mticketMaterialDetail->updateRequestFormDetail(['quantity_return' => $quantity_return > 0 ? $quantity_return : 0, 'quantity_reality' => $item['quantity_reality']], $item['ticket_request_material_detail_id']);
                }
            }


            $productChild = new ProductChildTable();

//            Tạo vật tư phát sinh
            $arrIncurred = [];
            if (isset($data['incurred-materials']) && count($data['incurred-materials']) != 0) {
                foreach ($data['incurred-materials'] as $item) {
                    $detailProduct = null;
                    if (isset($item['product_id']) && $item['product_id'] != null) {
                        $detailProduct = $productChild->getInfo($item['product_id']);
                    }
                    $arrIncurred[] = [
                        'ticket_acceptance_id' => $ticketAcceptanceId,
                        'product_id' => isset($item['product_id']) ? $item['product_id'] : null,
                        'product_name' => isset($item['product_name']) ? $item['product_name'] : null,
                        'product_code' => isset($item['product_code']) ? $item['product_code'] : null,
                        'unit_name' => isset($item['unit_name']) ? $item['unit_name'] : null,
                        'quantity' => $item['quantity'],
                        'money' => $item['money'],
//                    'unit_name' => $detailProduct != null ? $detailProduct['unit_name'] : null,
                        'status' => 'new',
                        'created_at' => Carbon::now(),
                        'created_by' => Auth::id(),
                        'updated_at' => Carbon::now(),
                        'updated_by' => Auth::id(),
                    ];
                }
            }

            if (count($arrIncurred) != 0) {
                $mTicketAcceptanceIncurred->createdIncurred($arrIncurred);
            }

            $note = 'Đã tạo biên bản nghiệm thu';
            $this->createHistory($data['ticket_id'], $note);

            $listCustomer = $this->listStaffs($data['ticket_id']);
            DB::commit();

            foreach ($listCustomer as $item) {
                $varNoti = [
                    'key' => 'acceptance_create',
                    'customer_id' => $item,
                    'object_id' => $data['ticket_id']

                ];
                $this->notiRepo->sendStaffNotification($varNoti);
            }

            $detail = $this->acceptanceRecord(['ticket_id' => $data['ticket_id']]);

            return $detail;
        } catch (\Exception|QueryException $exception) {
            DB::rollBack();
            throw new TicketRepoException(TicketRepoException::GET_ACCEPTANCE_CREATE_FAILED);
        }
    }

//    Chỉnh sửa biên bản nghiệm thu
    public function acceptanceRecordEdit($data)
    {
        try {

            DB::beginTransaction();
//            Kiểm tra danh sách vật tư

            if (!isset($data['proposed-materials']) || count($data['proposed-materials']) == 0) {
                throw new TicketRepoException(TicketRepoException::GET_MATERIAL_LIST_FAILED);
            }
            $mTicket = new TicketTable();
            $mTicketAcceptance = new TicketAcceptanceTable();
            $mTicketAcceptanceIncurred = new TicketAcceptanceIncurredTable();
            $mticketMaterialDetail = new TicketRequestMaterialDetailTable();

//            Tạo bản nghiệm thu

            $idAcceptance = $data['ticket_acceptance_id'];
            $dataAcceptance = [
                'title' => $data['title'],
                'status' => $data['status'],
                'updated_by' => Auth::id(),
                'updated_at' => Carbon::now()
            ];

            if (isset($data['sign_by'])) {
                $dataAcceptance['sign_by'] = $data['sign_by'];
            }

            if (isset($data['sign_date'])) {
                $dataAcceptance['sign_date'] = $data['sign_date'];
            }

            $mTicketAcceptance->editTicketAcceptance($dataAcceptance, $idAcceptance);

//            Tạo file đính kèm
            $ticketFile = new TicketFileTable();

//            Xoá file trước khi insert
            $ticketFile->deleteFile($data['ticket_id'], 'acceptance');

            if (count($data['attached']) != 0) {
                $listFile = [];

                foreach ($data['attached'] as $item) {
                    $listFile[] = [
                        'ticket_id' => $data['ticket_id'],
                        'type' => 'file',
                        'group' => 'acceptance',
                        'path' => $item['path'],
                        'created_at' => Carbon::now(),
                        'created_by' => Auth::id(),
                        'updated_at' => Carbon::now()
                    ];
                }
                $ticketFile->createFile($listFile);
            }

//            Cập nhật vật tư
            foreach ($data['proposed-materials'] as $item) {
//                lấy chi tiết vật tư
                $detailMaterialDetail = $mticketMaterialDetail->getDetail($item['ticket_request_material_detail_id']);

//                Số lượng hoàn ứng
                $quantity_return = $detailMaterialDetail['quantity_approve'] - $item['quantity_reality'];
                $mticketMaterialDetail->updateRequestFormDetail(['quantity_return' => $quantity_return > 0 ? $quantity_return : 0, 'quantity_reality' => $item['quantity_reality']], $item['ticket_request_material_detail_id']);
            }

            if (isset($data['incurred-materials']) && count($data['incurred-materials']) != 0) {
                $arrProduct = [];
//                Xoá các vậy tư bị dư
                $arrIdDetail = collect($data['incurred-materials'])->pluck('ticket_acceptance_incurred_id')->toArray();
                $arrIdDetail = array_filter($arrIdDetail, function ($var) {
                    return !is_null($var);
                });
                $mTicketAcceptanceIncurred->deleteRequestFormDetail($arrIdDetail, $idAcceptance);
                $productChild = new ProductChildTable();

                foreach ($data['incurred-materials'] as $item) {
//                    Cập nhật các vật tư
                    if (isset($item['ticket_acceptance_incurred_id'])) {
                        $dataUpdate['quantity'] = $item['quantity'];
                        if (isset($item['product_id'])) {
                            $dataUpdate['product_id'] = $item['product_id'];
                        }
                        if (isset($item['product_code'])) {
                            $dataUpdate['product_code'] = $item['product_code'];
                        }
                        if (isset($item['product_name'])) {
                            $dataUpdate['product_name'] = $item['product_name'];
                        }

                        if (isset($item['unit_name'])) {
                            $dataUpdate['unit_name'] = $item['unit_name'];
                        }

                        if (isset($item['quantity'])) {
                            $dataUpdate['quantity'] = $item['quantity'];
                        }

                        if (isset($item['money'])) {
                            $dataUpdate['money'] = $item['money'];
                        }
                        $mTicketAcceptanceIncurred->updateRequestFormDetail($dataUpdate, $item['ticket_acceptance_incurred_id']);
                    } else {
                        $detailProduct = null;
                        if (isset($item['product_id']) && $item['product_id'] != null) {
                            $detailProduct = $productChild->getInfo($item['product_id']);
                        }
//                        Thêm vật tư
                        $arrProduct[] = [
                            'ticket_acceptance_id' => $idAcceptance,
                            'product_id' => isset($item['product_id']) ? $item['product_id'] : null,
                            'product_name' => isset($item['product_name']) ? $item['product_name'] : null,
                            'product_code' => isset($item['product_code']) ? $item['product_code'] : null,
                            'unit_name' => isset($item['unit_name']) ? $item['unit_name'] : null,
                            'quantity' => $item['quantity'],
                            'money' => $item['money'],
                            'status' => 'new'
                        ];
                    }
                }
                if (count($arrProduct) != 0) {
                    $mTicketAcceptanceIncurred->createdIncurred($arrProduct);
                }
            }


            $note = 'Đã chỉnh sửa biên bản nghiệm thu';
            $this->createHistory($data['ticket_id'], $note);

            $listCustomer = $this->listStaffs($data['ticket_id']);
            DB::commit();

            foreach ($listCustomer as $item) {
                $varNoti = [
                    'key' => 'acceptance_edit',
                    'customer_id' => $item,
                    'object_id' => $data['ticket_id']

                ];
                $this->notiRepo->sendStaffNotification($varNoti);
            }

            $detail = $this->acceptanceRecord(['ticket_id' => $data['ticket_id']]);

            return $detail;

        } catch (\Exception|QueryException $exception) {
            throw new TicketRepoException(TicketRepoException::GET_ACCEPTANCE_EDIT_FAILED);
        }
    }

//    Tìm kiếm vật tư
    public function searchMaterials($data)
    {
        try {

            $mproductInventory = new ProductInventorysTable();

            $mProductImage = app()->get(ProductImageTable::class);

            //Ds sản phẩm theo
            $mProduct = app()->get(ProductChildTable::class);

            $list = $this->toPagingData($mProduct->getProductsUsing($data));

            foreach ($list['Items'] as $item) {
                //Lấy avatar product child
                $imageChild = $mProductImage->getAvatar($item['product_code']);

                if ($imageChild != null) {
                    $item['avatar'] = $imageChild['image'];
                }

                //Check khuyến mãi
                $getPromotion = $this->getPromotionDetail('product', $item['product_code'], null, 'app', null, $item['product_id']);

                $item['old_price'] = null;
                $item['new_price'] = floatval($item['new_price']);
                // Nếu không có promotion thì giá cũ là null, giá mới là giá chi nhánh
                // Nếu có promotion thì giá cũ là giá chi nhánh, giá mới là giá đã khuyến mãi
                $promotion = [];
                if (isset($getPromotion) && $getPromotion['price'] != null || $getPromotion['price'] != null) {
                    if (isset($getPromotion['price']) && $getPromotion['price'] != null) {
                        // Tinh phan tram
                        if ($getPromotion['price'] < $item['new_price']) {
                            $percent = $getPromotion['price'] / $item['new_price'] * 100;
                            $promotion['price'] = (100 - round($percent, 2)) . '%';
                            // Tính lại giá khi có khuyến mãi
                            $item['old_price'] = floatval($item['new_price']);
                            $item['new_price'] = ($item['new_price'] * $percent) / 100;
                        }
                    }
                    if ($getPromotion['gift'] != null) {
                        $promotion['gift'] = $getPromotion['gift'];
                    }
                }

                if (empty($promotion)) {
                    $promotion = null;
                }

                $item['promotion'] = $promotion;
            }

            foreach (collect($list)->toArray()['Items'] as $key => $item) {
                unset(collect($list)->toArray()['Items'][$key]['old_price']);
                unset(collect($list)->toArray()['Items'][$key]['promotion']);
                collect($list)->toArray()['Items'][$key]['quantity_warehouse'] = $mproductInventory->getCountInventory($item['product_id']);
            }
            return $list;
        } catch (\Exception|QueryException $exception) {
            throw new TicketRepoException(TicketRepoException::GET_SEARCH_MATERIALS_FAILED);
        }
    }

    public function getPromotionDetail($objectType, $objectCode, $customerId, $orderSource, $quantity = null, $objectId, $date = null)
    {
        $mPromotionDetail = new PromotionDetailTable();
        $mDaily = new PromotionDailyTimeTable();
        $mWeekly = new PromotionWeeklyTimeTable();
        $mMonthly = new PromotionMonthlyTimeTable();
        $mFromTo = new PromotionDateTimeTable();
        $mCustomer = new CustomerTable();
        $mPromotionApply = new PromotionObjectApplyTable();

        $currentDate = Carbon::now()->format('Y-m-d H:i:s');
        $currentTime = Carbon::now()->format('H:i');

        if ($date != null) {
            $currentDate = Carbon::createFromFormat('Y-m-d H:i', $date)->format('Y-m-d H:i:s');
            $currentTime = Carbon::createFromFormat('Y-m-d H:i', $date)->format('H:i');
        }

        $price = null;
        $gift = null;

        $promotionQuota = [];
        $promotionPrice = [];
        $promotionLog = [];

        $getDetail = $mPromotionDetail->getPromotionDetail($objectType, $objectCode, null, $currentDate);

        if (isset($getDetail) && count($getDetail) > 0) {
            foreach ($getDetail as $key => $item) {
                $promotionType = $item['promotion_type'];
                //Check thời gian diễn ra chương trình
                if ($currentDate < $item['start_date'] || $currentDate > $item['end_date']) {
                    //Kết thúc vòng for
                    continue;
                }
                //Check chi nhánh áp dụng
                if ($item['branch_apply'] != 'all' &&
                    !in_array(Auth()->user()->branch_id, explode(',', $item['branch_apply']))) {
                    //Kết thúc vòng for
                    continue;
                }
                //Check KM theo time đặc biệt
                if ($item['is_time_campaign'] == 1) {
                    switch ($item['time_type']) {
                        case 'D':
                            $daily = $mDaily->getDailyByPromotion($item['promotion_code']);

                            if ($daily != null) {
                                $startTime = Carbon::createFromFormat('H:i:s', $daily['start_time'])->format('H:i');
                                $endTime = Carbon::createFromFormat('H:i:s', $daily['end_time'])->format('H:i');
                                //Kiểm tra giờ bắt đầu, giờ kết thúc
                                if ($currentTime < $startTime || $currentTime > $endTime) {
                                    //Kết thúc vòng for
                                    continue 2;
                                }
                            }
                            break;
                        case 'W':
                            $weekly = $mWeekly->getWeeklyByPromotion($item['promotion_code']);
                            $startTime = Carbon::createFromFormat('H:i:s', $weekly['default_start_time'])->format('H:i');
                            $endTime = Carbon::createFromFormat('H:i:s', $weekly['default_end_time'])->format('H:i');

                            switch (Carbon::createFromFormat('Y-m-d H:i:s', $currentDate)->format('l')) {
                                case 'Monday':
                                    if ($weekly['is_monday'] == 1) {
                                        if ($weekly['is_other_monday'] == 1) {
                                            $startTime = Carbon::createFromFormat('H:i:s', $weekly['is_other_monday_start_time'])->format('H:i');
                                            $endTime = Carbon::createFromFormat('H:i:s', $weekly['is_other_monday_end_time'])->format('H:i');
                                        }
                                    } else {
                                        //Kết thúc vòng for
                                        continue 3;
                                    }
                                    break;
                                case 'Tuesday':
                                    if ($weekly['is_tuesday'] == 1) {
                                        if ($weekly['is_other_tuesday'] == 1) {
                                            $startTime = Carbon::createFromFormat('H:i:s', $weekly['is_other_tuesday_start_time'])->format('H:i');
                                            $endTime = Carbon::createFromFormat('H:i:s', $weekly['is_other_tuesday_end_time'])->format('H:i');
                                        }
                                    } else {
                                        //Kết thúc vòng for
                                        continue 3;
                                    }
                                    break;
                                case 'Wednesday':
                                    if ($weekly['is_wednesday'] == 1) {
                                        if ($weekly['is_other_wednesday'] == 1) {
                                            $startTime = Carbon::createFromFormat('H:i:s', $weekly['is_other_wednesday_start_time'])->format('H:i');
                                            $endTime = Carbon::createFromFormat('H:i:s', $weekly['is_other_wednesday_end_time'])->format('H:i');
                                        }
                                    } else {
                                        //Kết thúc vòng for
                                        continue 3;
                                    }
                                    break;
                                case 'Thursday':
                                    if ($weekly['is_thursday'] == 1) {
                                        if ($weekly['is_other_monday'] == 1) {
                                            $startTime = Carbon::createFromFormat('H:i:s', $weekly['is_other_thursday_start_time'])->format('H:i');
                                            $endTime = Carbon::createFromFormat('H:i:s', $weekly['is_other_thursday_end_time'])->format('H:i');
                                        }
                                    } else {
                                        //Kết thúc vòng for
                                        continue 3;
                                    }
                                    break;
                                case 'Friday':
                                    if ($weekly['is_friday'] == 1) {
                                        if ($weekly['is_other_friday'] == 1) {
                                            $startTime = Carbon::createFromFormat('H:i:s', $weekly['is_other_friday_start_time'])->format('H:i');
                                            $endTime = Carbon::createFromFormat('H:i:s', $weekly['is_other_friday_end_time'])->format('H:i');
                                        }
                                    } else {
                                        //Kết thúc vòng for
                                        continue 3;
                                    }
                                    break;
                                case 'Saturday':
                                    if ($weekly['is_saturday'] == 1) {
                                        if ($weekly['is_other_saturday'] == 1) {
                                            $startTime = Carbon::createFromFormat('H:i:s', $weekly['is_other_saturday_start_time'])->format('H:i');
                                            $endTime = Carbon::createFromFormat('H:i:s', $weekly['is_other_saturday_end_time'])->format('H:i');
                                        }
                                    } else {
                                        //Kết thúc vòng for
                                        continue 3;
                                    }
                                    break;
                                case 'Sunday':
                                    if ($weekly['is_sunday'] == 1) {
                                        if ($weekly['is_other_sunday'] == 1) {
                                            $startTime = Carbon::createFromFormat('H:i:s', $weekly['is_other_sunday_start_time'])->format('H:i');
                                            $endTime = Carbon::createFromFormat('H:i:s', $weekly['is_other_sunday_end_time'])->format('H:i');
                                        }
                                    } else {
                                        //Kết thúc vòng for
                                        continue 3;
                                    }
                                    break;
                            }
                            //Kiểm tra giờ bắt đầu, giờ kết thúc
                            if ($currentTime < $startTime || $currentTime > $endTime) {
                                //Kết thúc vòng for
                                continue 2;
                            }
                            break;
                        case 'M':
                            $monthly = $mMonthly->getMonthlyByPromotion($item['promotion_code']);

                            if (count($monthly) > 0) {
                                $next = false;

                                foreach ($monthly as $v) {
                                    $startDate = Carbon::createFromFormat('Y-m-d H:i:s', $v['run_date'] . ' ' . $v['start_time'])->format('Y-m-d H:i');
                                    $endDate = Carbon::createFromFormat('Y-m-d H:i:s', $v['run_date'] . ' ' . $v['end_time'])->format('Y-m-d H:i');

                                    if ($currentDate > $startDate && $currentDate < $endDate) {
                                        $next = true;
                                    }
                                }

                                if ($next == false) {
                                    //Kết thúc vòng for
                                    continue 2;
                                }
                            } else {
                                //Kết thúc vòng for
                                continue 2;
                            }
                            break;
                        case 'R':
                            $fromTo = $mFromTo->getDateTimeByPromotion($item['promotion_code']);

                            if ($fromTo != null) {
                                $startFrom = Carbon::createFromFormat('Y-m-d H:i:s', $fromTo['form_date'] . ' ' . $fromTo['start_time'])->format('Y-m-d H:i');
                                $endFrom = Carbon::createFromFormat('Y-m-d H:i:s', $fromTo['to_date'] . ' ' . $fromTo['end_time'])->format('Y-m-d H:i');

                                if ($currentDate < $startFrom || $currentDate > $endFrom) {
                                    //Kết thúc vòng for
                                    continue 2;
                                }
                            }
                            break;
                    }
                }

                //Check KM theo type = discount or gift
                if ($item['promotion_type'] != $promotionType) {
                    //Kết thúc vòng for
                    continue;
                }

                //Check nguồn đơn hàng
                if ($item['order_source'] != 'all' && $item['order_source'] != $orderSource) {
                    //Kết thúc vòng for
                    continue;
                }

                //Check đối tượng áp dụng
                if ($item['promotion_apply_to'] != 1 && $item['promotion_apply_to'] != null) {
                    //Lấy thông tin khách hàng
                    $getCustomer = $mCustomer->getItem($customerId);

                    if ($getCustomer == null || $getCustomer['customer_id'] == 1) {
                        //Kết thúc vòng for
                        continue;
                    }

                    if ($getCustomer['member_level_id'] == null) {
                        $getCustomer['member_level_id'] = 1;
                    }

                    $objectId = '';
                    if ($item['promotion_apply_to'] == 2) {
                        $objectId = $getCustomer['member_level_id'];
                    } else if ($item['promotion_apply_to'] == 3) {
                        $objectId = $getCustomer['customer_group_id'];
                    } else if ($item['promotion_apply_to'] == 4) {
                        $objectId = $item['customer_id'];
                    }

                    $getApply = $mPromotionApply->getApplyByObjectId($item['promotion_code'], $objectId);

                    if ($getApply == null) {
                        //Kết thúc vòng for
                        continue;
                    }
                }

                $item['object_type'] = $objectType;
                $item['object_id'] = $objectId;
                $item['object_code'] = $objectCode;
                $item['quantity'] = $quantity;
                //Check quota (số tiền)
                if ($promotionType == 1) {
                    $promotionPrice [] = $item;
                } else {
                    $item['quota'] = !empty($item['quota']) ? $item['quota'] : 0;
                    //Số quà được tặng
                    $totalGift = intval($item['quantity_gift']);
                    //Quota use sau khi áp dụng promotion
                    $quotaUse = $item['quota_use'] + $totalGift;

                    if ($item['quota'] == 0 || $item['quota'] == '' || $quotaUse <= floatval($item['quota'])) {
                        //Lấy giá trị quà tặng
                        $priceGift = $this->getPriceObject($item['gift_object_type'], $item['gift_object_code']);

                        $item['quantity_gift'] = $totalGift;
                        $item['quota'] = !empty($item['quota']) ? $item['quota'] : 0;
                        $item['quota_use'] = floatval($item['quota_use']);
                        $item['total_price_gift'] = $priceGift * $totalGift;

                        $promotionQuota [] = $item;
                    }
                }
            }
        }

        if (count($promotionPrice) > 0) {
            //Lấy CTKM có giá ưu đãi nhất
            $getPriceMostPreferential = $this->choosePriceMostPreferential($promotionPrice);
            $promotionLog [] = $getPriceMostPreferential;
            //Lấy giá KM
            $price = $getPriceMostPreferential['promotion_price'];
        }

        if (count($promotionQuota) > 0) {
            //Lấy CTKM có quà tặng ưu đãi nhất
            $getGiftMostPreferential = $this->getGiftMostPreferential($promotionQuota);
            $promotionLog [] = $getGiftMostPreferential;
            //Lấy quà tặng KM
            $gift = __('Mua ') . $getGiftMostPreferential['quantity_buy'] . __(' tặng ') . $getGiftMostPreferential['quantity_gift'];
        }

        return [
            'price' => $price,
            'gift' => $gift,
            'promotion_log' => $promotionLog,
        ];
    }

    /**
     * Chọn CTKM giảm giá ưu đãi nhất
     *
     * @param $arrPrice
     * @return array
     */
    private function choosePriceMostPreferential($arrPrice)
    {
        //Lấy giá trị quà tặng có giá trị cao nhất
        $minPrice = array_column($arrPrice, 'promotion_price');
        //Sắp xếp lại array có quà tặng giá trị cao nhất
        array_multisort($minPrice, SORT_ASC, $arrPrice);

        //Lấy CTKM có giá ưu đãi nhất
        return [
            'object_type' => $arrPrice[0]['object_type'],
            'object_id' => $arrPrice[0]['object_id'],
            'object_code' => $arrPrice[0]['object_code'],
            'quantity' => $arrPrice[0]['quantity'],
            'promotion_id' => $arrPrice[0]['promotion_id'],
            'promotion_code' => $arrPrice[0]['promotion_code'],
            'promotion_type' => $arrPrice[0]['promotion_type'],
            'start_date' => $arrPrice[0]['start_date'],
            'end_date' => $arrPrice[0]['end_date'],
            'base_price' => $arrPrice[0]['base_price'],
            'promotion_price' => $arrPrice[0]['promotion_price'],
            'gift_object_type' => $arrPrice[0]['gift_object_type'],
            'gift_object_id' => $arrPrice[0]['gift_object_id'],
            'gift_object_code' => $arrPrice[0]['gift_object_code'],
            'quantity_gift' => $arrPrice[0]['quantity_gift'],
        ];
    }

    /**
     * Lấy giá trị khuyến mãi sp, dv, thẻ dv
     *
     * @param $objectType
     * @param $objectCode
     * @return int
     */
    private function getPriceObject($objectType, $objectCode)
    {
        $price = 0;

        switch ($objectType) {
            case 'product':
                $mProduct = app()->get(\Modules\Home\Models\ProductChildTable::class);
                //Lấy thông tin sp khuyến mãi
                $getProduct = $mProduct->getProductPromotion($objectCode);
                $price = $getProduct['new_price'];

                break;
            case 'service':
                $mService = app()->get(ServiceTable::class);
                //Lấy thông tin dv khuyến mãi
                $getService = $mService->getServicePromotion($objectCode);
                $price = $getService['new_price'];

                break;
            case 'service_card':
                $mServiceCard = app()->get(ServiceCardTable::class);
                //Lấy thông tin thẻ dv khuyến mãi
                $getServiceCard = $mServiceCard->getServiceCardPromotion($objectCode);
                $price = $getServiceCard['new_price'];

                break;
        }

        return floatval($price);
    }

    /**
     * Lấy quà tặng ưu đãi nhất
     *
     * @param $arrGift
     * @return array
     */
    private function getGiftMostPreferential($arrGift)
    {
        $result = [];
        if (count($arrGift) == 1) {
            //Có 1 CTKM quà tặng thì lấy chính nó
            $result [] = [
                'object_type' => $arrGift[0]['object_type'],
                'object_id' => $arrGift[0]['object_id'],
                'object_code' => $arrGift[0]['object_code'],
                'quantity' => $arrGift[0]['quantity'],
                'promotion_type' => $arrGift[0]['promotion_type'],
                'promotion_id' => $arrGift[0]['promotion_id'],
                'promotion_code' => $arrGift[0]['promotion_code'],
                'start_date' => $arrGift[0]['start_date'],
                'end_date' => $arrGift[0]['end_date'],
                'base_price' => $arrGift[0]['base_price'],
                'promotion_price' => $arrGift[0]['promotion_price'],
                'gift_object_type' => $arrGift[0]['gift_object_type'],
                'gift_object_id' => $arrGift[0]['gift_object_id'],
                'gift_object_code' => $arrGift[0]['gift_object_code'],
                'quantity_gift' => $arrGift[0]['quantity_gift'],
                //mới update param thêm
                'quantity_buy' => $arrGift[0]['quantity_buy'],
                'quota' => $arrGift[0]['quota'],
                'quota_use' => $arrGift[0]['quota_use'],
                'total_price_gift' => $arrGift[0]['total_price_gift']
            ];
        } else if (count($arrGift) > 1) {

            //Có nhiều CTKM quà tặng
            //Lấy quà tặng có giá trị cao nhất
            $giftPreferential = $this->chooseGiftPreferential($arrGift);

            $result = $giftPreferential;

            if (count($result) > 1) {
                //Lấy quà tặng có số lượng mua thấp nhất
                $giftMinBuy = $this->chooseGiftMinBuy($result);

                $result = $giftMinBuy;
            }

            if (count($result) > 1) {
                //Lấy quà tặng có quota - quota_use còn nhiều nhất (ưu tiên quota != 0 ko giới hạn)
                $giftQuota = $this->chooseGiftQuota($result);

                $result = $giftQuota;
            }
        }

        return $result[0];
    }

    /**
     * Chọn quà tặng có quota còn lại cao nhất
     *
     * @param $arrGift
     * @return array
     */
    private function chooseGiftQuota($arrGift)
    {
        //Có nhiều promotion bằng giá trị + số lượng mua thì kiểm tra quota_use con lại (ưu tiên promotion có quota != 0)
        $result = [];

        $arrLimited = [];
        $arrUnLimited = [];

        foreach ($arrGift as $v) {
            if ($v['quota'] != 0) {
                $v['quota_balance'] = $v['quota'] - $v['quota_use'];
                $arrLimited [] = $v;
            } else {
                $arrUnLimited [] = $v;
            }
        }

        if (count($arrLimited) > 0) {
            //Ưu tiên lấy quà tặng có giới hạn quota

            //Lấy quà tặng có quota còn lại cao nhất
            $quantityQuota = array_column($arrLimited, 'quota_balance');
            //Sắp xếp lại array có số lượng cần mua thấp nhất
            array_multisort($quantityQuota, SORT_DESC, $arrLimited);

            $result [] = [
                'promotion_id' => $arrLimited[0]['promotion_id'],
                'promotion_code' => $arrLimited[0]['promotion_code'],
                'promotion_type' => $arrLimited[0]['promotion_type'],
                'start_date' => $arrLimited[0]['start_date'],
                'end_date' => $arrLimited[0]['end_date'],
                'base_price' => $arrLimited[0]['base_price'],
                'promotion_price' => $arrLimited[0]['promotion_price'],
                'gift_object_type' => $arrLimited[0]['gift_object_type'],
                'gift_object_id' => $arrLimited[0]['gift_object_id'],
                'gift_object_code' => $arrLimited[0]['gift_object_code'],
                'quantity_gift' => $arrLimited[0]['quantity_gift'],
                //mới update param thêm
                'quantity_buy' => $arrLimited[0]['quantity_buy'],
                'quota' => $arrLimited[0]['quota'],
                'quota_use' => $arrLimited[0]['quota_use'],
                'total_price_gift' => $arrLimited[0]['total_price_gift']
            ];

            unset($arrLimited[0]);

            foreach ($arrLimited as $v) {
                //Kiểm tra có promotion nào có giá trị = với promotion cao nhất
                if ($v['quota_balance'] == ($result[0]['quota'] - $result[0]['quota_use'])) {
                    $result [] = [
                        'promotion_id' => $v['promotion_id'],
                        'promotion_code' => $v['promotion_code'],
                        'promotion_type' => $v['promotion_type'],
                        'start_date' => $v['start_date'],
                        'end_date' => $v['end_date'],
                        'base_price' => $v['base_price'],
                        'promotion_price' => $v['promotion_price'],
                        'gift_object_type' => $v['gift_object_type'],
                        'gift_object_id' => $v['gift_object_id'],
                        'gift_object_code' => $v['gift_object_code'],
                        'quantity_gift' => $v['quantity_gift'],
                        //mới update param thêm
                        'quantity_buy' => $v['quantity_buy'],
                        'quota' => $v['quota'],
                        'quota_use' => $v['quota_use'],
                        'total_price_gift' => $v['total_price_gift']
                    ];
                }
            }
        }

        if (count($result) == 0 && count($arrUnLimited) > 0) {
            //Lấy quà tặng có quota_use thấp nhất
            $quantityQuotaUse = array_column($arrUnLimited, 'quota_use');
            //Sắp xếp lại array có số lượng cần mua thấp nhất
            array_multisort($quantityQuotaUse, SORT_ASC, $arrUnLimited);

            $result [] = [
                'promotion_id' => $arrUnLimited[0]['promotion_id'],
                'promotion_code' => $arrUnLimited[0]['promotion_code'],
                'promotion_type' => $arrUnLimited[0]['promotion_type'],
                'start_date' => $arrUnLimited[0]['start_date'],
                'end_date' => $arrUnLimited[0]['end_date'],
                'base_price' => $arrUnLimited[0]['base_price'],
                'promotion_price' => $arrUnLimited[0]['promotion_price'],
                'gift_object_type' => $arrUnLimited[0]['gift_object_type'],
                'gift_object_id' => $arrUnLimited[0]['gift_object_id'],
                'gift_object_code' => $arrUnLimited[0]['gift_object_code'],
                'quantity_gift' => $arrUnLimited[0]['quantity_gift'],
                //mới update param thêm
                'quantity_buy' => $arrUnLimited[0]['quantity_buy'],
                'quota' => $arrUnLimited[0]['quota'],
                'quota_use' => $arrUnLimited[0]['quota_use'],
                'total_price_gift' => $arrUnLimited[0]['total_price_gift']
            ];

            unset($arrUnLimited[0]);

            foreach ($arrUnLimited as $v) {
                //Kiểm tra có promotion nào có giá trị = với promotion cao nhất
                if ($v['quota_use'] <= $result[0]['quota_use']) {
                    $result [] = [
                        'promotion_id' => $v['promotion_id'],
                        'promotion_code' => $v['promotion_code'],
                        'promotion_type' => $v['promotion_type'],
                        'start_date' => $v['start_date'],
                        'end_date' => $v['end_date'],
                        'base_price' => $v['base_price'],
                        'promotion_price' => $v['promotion_price'],
                        'gift_object_type' => $v['gift_object_type'],
                        'gift_object_id' => $v['gift_object_id'],
                        'gift_object_code' => $v['gift_object_code'],
                        'quantity_gift' => $v['quantity_gift'],
                        //mới update param thêm
                        'quantity_buy' => $v['quantity_buy'],
                        'quota' => $v['quota'],
                        'quota_use' => $v['quota_use'],
                        'total_price_gift' => $v['total_price_gift']
                    ];
                }
            }
        }

//        if (count($result) > 1) {
//            $result = $result[0];
//        }

        return $result;
    }

    /**
     * Chọn quà tặng có lượng mua thấp nhất
     *
     * @param $arrGift
     * @return array
     */
    private function chooseGiftMinBuy($arrGift)
    {
        //Có nhiều promotion bằng giá trị thì check số lượng mua (lợi ích khách hàng)
        $result = [];
        //Lấy quà tặng có số lượng mua thấp nhất
        $quantityBuy = array_column($arrGift, 'quantity_buy');
        //Sắp xếp lại array có số lượng cần mua thấp nhất
        array_multisort($quantityBuy, SORT_ASC, $arrGift);

        $result [] = [
            'object_type' => $arrGift[0]['object_type'],
            'object_id' => $arrGift[0]['object_id'],
            'object_code' => $arrGift[0]['object_code'],
            'quantity' => $arrGift[0]['quantity'],
            'promotion_id' => $arrGift[0]['promotion_id'],
            'promotion_code' => $arrGift[0]['promotion_code'],
            'promotion_type' => $arrGift[0]['promotion_type'],
            'start_date' => $arrGift[0]['start_date'],
            'end_date' => $arrGift[0]['end_date'],
            'base_price' => $arrGift[0]['base_price'],
            'promotion_price' => $arrGift[0]['promotion_price'],
            'gift_object_type' => $arrGift[0]['gift_object_type'],
            'gift_object_id' => $arrGift[0]['gift_object_id'],
            'gift_object_code' => $arrGift[0]['gift_object_code'],
            'quantity_gift' => $arrGift[0]['quantity_gift'],
            //mới update param thêm
            'quantity_buy' => $arrGift[0]['quantity_buy'],
            'quota' => $arrGift[0]['quota'],
            'quota_use' => $arrGift[0]['quota_use'],
            'total_price_gift' => $arrGift[0]['total_price_gift']
        ];

        unset($arrGift[0]);

        foreach ($arrGift as $v) {
            //Kiểm tra có promotion nào có giá trị = với promotion cao nhất
            if ($v['quantity_buy'] == $result[0]['quantity_buy']) {
                $result [] = [
                    'object_type' => $v['object_type'],
                    'object_id' => $v['object_id'],
                    'object_code' => $v['object_code'],
                    'quantity' => $v['quantity'],
                    'promotion_id' => $v['promotion_id'],
                    'promotion_code' => $v['promotion_code'],
                    'promotion_type' => $v['promotion_type'],
                    'start_date' => $v['start_date'],
                    'end_date' => $v['end_date'],
                    'base_price' => $v['base_price'],
                    'promotion_price' => $v['promotion_price'],
                    'gift_object_type' => $v['gift_object_type'],
                    'gift_object_id' => $v['gift_object_id'],
                    'gift_object_code' => $v['gift_object_code'],
                    'quantity_gift' => $v['quantity_gift'],
                    //mới update param thêm
                    'quantity_buy' => $v['quantity_buy'],
                    'quota' => $v['quota'],
                    'quota_use' => $v['quota_use'],
                    'total_price_gift' => $v['total_price_gift']
                ];
            }
        }

        return $result;
    }

    /**
     * Chọn quà tặng có giá trị cao nhất
     *
     * @param $arrGift
     * @return array
     */
    private function chooseGiftPreferential($arrGift)
    {
        $result = [];
        //Lấy giá trị quà tặng có giá trị cao nhất
        $giftPrice = array_column($arrGift, 'total_price_gift');
        //Sắp xếp lại array có quà tặng giá trị cao nhất
        array_multisort($giftPrice, SORT_DESC, $arrGift);

        $result [] = [
            'promotion_id' => $arrGift[0]['promotion_id'],
            'promotion_code' => $arrGift[0]['promotion_code'],
            'promotion_type' => $arrGift[0]['promotion_type'],
            'start_date' => $arrGift[0]['start_date'],
            'end_date' => $arrGift[0]['end_date'],
            'base_price' => $arrGift[0]['base_price'],
            'promotion_price' => $arrGift[0]['promotion_price'],
            'gift_object_type' => $arrGift[0]['gift_object_type'],
            'gift_object_id' => $arrGift[0]['gift_object_id'],
            'gift_object_code' => $arrGift[0]['gift_object_code'],
            'quantity_gift' => $arrGift[0]['quantity_gift'],
            //mới update param thêm
            'quantity_buy' => $arrGift[0]['quantity_buy'],
            'quota' => $arrGift[0]['quota'],
            'quota_use' => $arrGift[0]['quota_use'],
            'total_price_gift' => $arrGift[0]['total_price_gift']
        ];

        unset($arrGift[0]);

        foreach ($arrGift as $v) {
            //Kiểm tra có promotion nào có giá trị = với promotion cao nhất
            if ($v['total_price_gift'] >= $result[0]['total_price_gift']) {
                $result [] = [
                    'promotion_id' => $v['promotion_id'],
                    'promotion_code' => $v['promotion_code'],
                    'promotion_type' => $v['promotion_type'],
                    'start_date' => $v['start_date'],
                    'end_date' => $v['end_date'],
                    'base_price' => $v['base_price'],
                    'promotion_price' => $v['promotion_price'],
                    'gift_object_type' => $v['gift_object_type'],
                    'gift_object_id' => $v['gift_object_id'],
                    'gift_object_code' => $v['gift_object_code'],
                    'quantity_gift' => $v['quantity_gift'],
                    //mới update param thêm
                    'quantity_buy' => $v['quantity_buy'],
                    'quota' => $v['quota'],
                    'quota_use' => $v['quota_use'],
                    'total_price_gift' => $v['total_price_gift']
                ];
            }
        }

        return $result;
    }

//    Danh sách nhóm yêu cầu
    public function issueGroup($param)
    {
        try {
            $issueGroup = new TicketIssueGroupTable();
            return $issueGroup->getListIssueGroup($param);

        } catch (\Exception|QueryException $exception) {
            throw new TicketRepoException(TicketRepoException::GET_ISSUEGROUP_FAILED);
        }
    }

//    Danh sách yêu cầu
    public function issue($param)
    {
        try {
            $issue = new TicketIssueTable();

            return $issue->getListIssue($param);

        } catch (\Exception|QueryException $exception) {
            throw new TicketRepoException(TicketRepoException::GET_ISSUE_FAILED);
        }
    }

    public function listStatus($ticketId = null)
    {
        try {
            $ticketStatus = new TicketStatusTable();
            $ticket = new TicketTable();
            $getRole = $this->getQueue();

            $listStatus = [];
            if ($ticketId != null) {
                $detailTicket = $ticket->getDetail($ticketId);
//                Lấy danh sách trạng thái có thể cập nhật
                if ($detailTicket['ticket_status_id'] == self::STATUS_NEW) {
                    $listStatus = [self::STATUS_NEW, self::STATUS_PROCESSING, self::STATUS_CANCEL];
                } else if ($detailTicket['ticket_status_id'] == self::STATUS_PROCESSING) {
                    $listStatus = [self::STATUS_PROCESSING, self::STATUS_COMPLETED, self::STATUS_CANCEL];
                } else if ($detailTicket['ticket_status_id'] == self::STATUS_COMPLETED) {
                    $listStatus = [self::STATUS_COMPLETED, self::STATUS_REOPEN, self::STATUS_CLOSE];
                } else if ($detailTicket['ticket_status_id'] == self::STATUS_REOPEN) {
                    $listStatus = [self::STATUS_REOPEN, self::STATUS_PROCESSING];
                } else if ($detailTicket['ticket_status_id'] == self::STATUS_CLOSE) {
                    $listStatus = [self::STATUS_CLOSE];
                } else if ($detailTicket['ticket_status_id'] == self::STATUS_CANCEL) {
                    $listStatus = [self::STATUS_CANCEL];
                }
            }

            return $ticketStatus->getListTicketStatus($getRole['roleStaff'], $listStatus);

        } catch (\Exception|QueryException $exception) {
            throw new TicketRepoException(TicketRepoException::GET_TICKET_STATUS_FAILED, $exception->getMessage());
        }
    }

    public function listQueue()
    {
        try {
//            Lấy danh sách queue được phân công cho nhân viên
            $mTicketQueue = new TicketStaffQueueTable();

            $list = $mTicketQueue->getListQueueStaff(Auth::id());

            return $list;

        } catch (\Exception|QueryException $exception) {
            throw new TicketRepoException(TicketRepoException::GET_TICKET_QUEUE_FAILED);
        }
    }

    public function uploadFile($input)
    {
        try {

            $imageFile = getimagesize($input['link']);

            if ($imageFile == false) {
                throw new TicketRepoException(TicketRepoException::FILE_NOT_TYPE);
            }

            $fileSize = number_format(filesize($input['link']) / 1048576, 2); //MB

            if ($fileSize > 20) {
                throw new TicketRepoException(TicketRepoException::MAX_FILE_SIZE);
            }

            $link = UploadImage::uploadImageS3($input['link'], '_ticket.');

            return ['path' => $link];

        } catch (\Exception|QueryException $exception) {
            throw new TicketRepoException(TicketRepoException::GET_UPLOAD_FILE_FAILED);
        }
    }

    public function createHistory($tickedId, $note)
    {
        $mTicketHistory = new TicketHistoryTable();
        $note_en = __($note);

        $data = [
            'ticket_id' => $tickedId,
            'note_vi' => $note,
            'note_en' => $note_en,
            'created_at' => Carbon::now(),
            'created_by' => Auth::id()
        ];

        $mTicketHistory->createHistory($data);
    }


    public function createdCode($type)
    {
        if ($type == 'request-form') {
            $mTicketRequestMaterial = new TicketRequestMaterialTable();
            $codeTicket = 'YCVT_' . Carbon::now()->format('Ymd') . '_';

//        Lấy phiếu thu mới nhất

            $getTicketDetailCode = $mTicketRequestMaterial->getRequestFormNew($codeTicket);
        } else if ($type == 'acceptance') {
            $mTicketAcceptance = new TicketAcceptanceTable();
            $codeTicket = 'BBNT' . Carbon::now()->format('Ymd') . '_';

//        Lấy phiếu thu mới nhất

            $getTicketDetailCode = $mTicketAcceptance->getAcceptanceNew($codeTicket);
        }
        if ($getTicketDetailCode == null) {
            return $codeTicket . '001';
        } else {
            $arr = explode($codeTicket, $getTicketDetailCode);
            $value = strval(intval($arr[1]) + 1);
            $zero_str = "";
            if (strlen($value) < 7) {
                for ($i = 0; $i < (3 - strlen($value)); $i++) {
                    $zero_str .= "0";
                }
            }
            return $codeTicket . $zero_str . $value;
        }

    }

//    Danh sách nhân viên được phân công
    public function listStaffs($ticketId)
    {
        $mOperater = new TicketOperaterTable();
        $mTicket = new TicketTable();
        $mProcessor = new TicketProcessorTable();

//        Lấy danh sách người chủ trì
        $getOperater = $mTicket->ticketDetailByTicket($ticketId);

        if ($getOperater != null && $getOperater['operate_by'] != null) {
            $listOperater = [$getOperater['operate_by']];
        } else {
            $listOperater = [];
        }
        $listProcessor = $mProcessor->getListProcessor($ticketId);
        if (count($listProcessor) != 0) {
            $listProcessor = collect($listProcessor)->pluck('staff_id');
        }

        $listArr = collect($listOperater)->merge($listProcessor)->toArray();
        $listArr = array_unique($listArr);

        $key = array_search(Auth::id(), $listArr);
        if ($key !== false) {
            unset($listArr[$key]);
        }

        return $listArr;
    }

//    Lấy danh sách nhân viên theo queue
    public function getListStaffByQueue($input)
    {
        try {

            if (isset($input['ticket_queue_id'])) {
                $mTicketStaffQueue = new TicketStaffQueueTable();

                $listStaff = $mTicketStaffQueue->getListStaffByQueue($input['ticket_queue_id']);
                $arrStaff = [];
                if (count($listStaff) != 0) {
                    $listStaff = collect($listStaff)->groupBy('ticket_role_queue_id');
                    $arrStaff['staff_host'] = isset($listStaff[2]) && isset($listStaff[2][0]) ? $listStaff[2][0] : [];
                    $arrStaff['staff_handler'] = isset($listStaff[1]) ? $listStaff[1] : [];
                }

                return $arrStaff;

            } else {
                throw new TicketRepoException(TicketRepoException::GET_LIST_STAFF_QUEUE_FAILED);
            }

        } catch (\Exception|QueryException $exception) {
            throw new TicketRepoException(TicketRepoException::GET_LIST_STAFF_QUEUE_FAILED);
        }
    }

    /**
     * Lấy ds công việc của ticket
     *
     * @param $input
     * @return mixed|void
     * @throws TicketRepoException
     */
    public function getListTaskOfTicket($input)
    {
        try {
            $mManageWork = app()->get(ManageWorkTable::class);

            //Lấy ds ticket của công việc
            return $mManageWork->getListOfTicket($input['ticket_id']);
        } catch (\Exception|QueryException $exception) {
            throw new TicketRepoException(TicketRepoException::GET_LIST_TASK_OF_TICKET_FAILED);
        }
    }


    public function addTikcetLocation($input)
    {
        try {
            $mLocation = app()->get(TicketLocationTable::class);
            $data = [
                'ticket_id' => $input['ticket_id'],
                'staff_id' => Auth()->id(),
                'lat' => $input['lat'],
                'lng' => $input['lng'],
                'description' => $input['description'],
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];

            //Thêm vị trí
            $locationId = $mLocation->add($data);
            return $mLocation->getInfo($locationId);
        } catch (\Exception $e) {
            throw new TicketRepoException(TicketRepoException::CREATE_LOCATION_FAILED);
        }
    }

    /**
     * Lấy vị trí làm việc của công việc
     *
     * @param $input
     * @return mixed
     * @throws ManageWorkRepoException
     */
    public function listLocation($input)
    {
        try {
            $mLocation = app()->get(TicketLocationTable::class);

            //Lấy vị trí 
            $data = $mLocation->getListLocation($input['ticket_id']);

            if (count($data) > 0) {
                foreach ($data as $v) {
                    $isRemove = 0;

                    //Chỉ người tạo dc phép xoá
                    if ($v['staff_id'] == Auth()->id()) {
                        $isRemove = 1;
                    }

                    $v['is_remove'] = $isRemove;
                }
            }

            return $data;
        } catch (\Exception $e) {
            throw new TicketRepoException(TicketRepoException::GET_LOCATION_FAILED);
        }
    }

    /**
     * Xoá toạ độ
     *
     * @param $input
     * @return mixed|void
     * @throws ManageWorkRepoException
     */
    public function removeLocation($input)
    {
        try {
            $mLocation = app()->get(TicketLocationTable::class);

            //Xoá vị trí làm việc
            $mLocation->edit([
                'is_deleted' => 1
            ], $input['ticket_location_id']);
        } catch (\Exception $e) {
            throw new TicketRepoException(TicketRepoException::REMOVE_LOCATION_FAILED);
        }
    }


    //    Chỉnh sửa ticket
    public function addTicket($data)
    {
        try {
            DB::beginTransaction();
            $mTicketIssue = new TicketIssueTable();
            $request_process_time = $mTicketIssue->getItem($data['ticket_issue_id'])->process_time;
            // thời gian bắt buộc hoàn thành lấy từ ngày tạo + thời gian xử lý của yêu cầu created_at
            $date_expected = Carbon::now()->addHour($request_process_time);

            // $date_request = $request->date_request;
            // if ($date_request != null) {
            //     $date_request = Carbon::createFromFormat("d/m/Y H:i", $request->date_request)->format("Y-m-d H:i:s");
            // }
            // if ($request->created_at) {
            //     $date_expected = Carbon::parse($request->created_at)->addHour($request_process_time);
            // }
            $ticket_code = $this->generateTicketCode();
            $dataInsert = [
                "ticket_code" => $ticket_code,
                "localtion_id" => $data['localtion_id'],
                "ticket_issue_id" => $data['ticket_issue_id'],
                "ticket_type" => $data['ticket_issue_group_id'],
                'ticket_issue_group_id' => $data['ticket_issue_group_id'],
                'title' => $data['title'],
                "description" => $data['description'],
                "customer_address" => $data['customer_address'],
                "customer_id" => $data['customer_id'],
                "issule_level" => $data['issule_level'],
                "date_estimated" => $data['date_estimated'] ? Carbon::createFromFormat("d/m/Y H:i", $data['date_estimated'])->format("Y-m-d H:i:s") : null,
                "priority" => $data['priority'],
                "date_issue" => Carbon::createFromFormat("d/m/Y H:i", $data['date_issue'])->format("Y-m-d H:i:s"),
                'date_expected' => $date_expected,
                "staff_notification_id" => $data['staff_notification_id'] != null ? $data['staff_notification_id'] : Auth::id(),
                "queue_process_id" => $data['queue_process_id'],
                "operate_by" => $data['operate_by'],
                "ticket_status_id" => 1,
                "updated_by" => Auth::id(),
                "created_by" => Auth::id(),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ];
            $idTicket = $this->mTicket->addTicket($dataInsert);
            $mContract = new ContractTable();

            if (isset($data['contract_id']) && $data['contract_id'] != '') {
                $mContract->edit([
                    'ticket_code' => $ticket_code
                ], $data['contract_id']);
            }
            if ($idTicket) {
                $link = '<a href="https://' . session()->get('brand_code') . env('DOMAIN_PIOSPA') . '/admin/staff/detail/"' . Auth::id() . '">';
                $note = $link . ' đã tạo ticket';
                $this->createHistory($note, $idTicket);
            }

            $mTicketFile = new TicketFileTable();
            if (isset($request['image']) && $request['image']) {
                $mTicketFile->remove($idTicket, 'image');
                foreach ($request->image as $v) {
                    $arrfileTicket = [
                        "ticket_id" => $idTicket,
                        "type" => "image",
                        "group" => "image",
                        "path" => $v,
                        "created_at" => Carbon::now()->format("Y-m-d H:i:s"),
                        "created_by" => Auth::id(),
                        "updated_at" => Carbon::now(),
                        "updated_by" => Auth::id()
                    ];
                    //Thêm image kèm theo
                    $mTicketFile->add($arrfileTicket);
                }
            }

            if (isset($data['file']) && count($data['file'])) {
                $mTicketFile->remove($idTicket, 'ticket');
                foreach ($data['file'] as $v) {
                    $arrfileTicket = [
                        "ticket_id" => $idTicket,
                        "type" => "file",
                        "group" => "ticket",
                        "path" => $v,
                        "updated_at" => Carbon::now()->format("Y-m-d H:i:s"),
                        "updated_by" => Auth::id(),
                    ];
                    //Thêm file kèm theo
                    $mTicketFile->add($arrfileTicket);
                }
            }

            if (isset($data['processor']) && ($data['processor'])) {
                $mTicketProcessor = new TicketProcessorTable();
                $mTicketProcessor->deleteListStaff($idTicket);
                foreach ($data['processor'] as $v) {
                    $arrProcessor = [
                        "ticket_id" => $idTicket,
                        "name" => "",
                        "process_by" => $v,
                        "updated_at" => Carbon::now()->format("Y-m-d H:i:s"),
                        "updated_by" => Auth::id(),
                    ];
                    //Thêm người xử lý
                    $mTicketProcessor->createdStaff($arrProcessor);
                }
            }
            $data['ticket_id'] = $idTicket;

            $detail = $this->getDetail($data);

            DB::commit();

            $mStaffNotify = app()->get(NotificationRepoInterface::class);
            //Lấy ds nhân viên nhận thông báo
            $listStaff = $this->getListStaff($idTicket);

            if (count($listStaff) > 0) {
                foreach ($listStaff as $item) {
                    $mStaffNotify->sendStaffNotification([
                        'key' => $data['operate_by'] == $item ? 'ticket_operater' : 'ticket_assign',
                        'customer_id' => $item,
                        'object_id' => $idTicket
                    ]);
                }
            }

            if (isset($data['operate_by']) && $data['operate_by'] != null) {
                //Bắn zns cho nhân viên chủ trì
                FunctionSendNotify::dispatch([
                    'type' => SEND_ZNS_CUSTOMER,
                    'key' => 'create_ticket_user_support',
                    'customer_id' => $data['customer_id'],
                    'object_id' => $idTicket,
                    'tenant_id' => session()->get('idTenant')
                ]);
            }

            return $detail;
        } catch (\Exception|QueryException $exception) {
            DB::rollBack();
            throw new TicketRepoException(TicketRepoException::GET_TICKET_EDIT_FAILED, $exception->getMessage() . $exception->getLine());
        }
    }

    public function generateTicketCode()
    {
        $type_ticket = 'TKTK';
        $time = date("Ymd");
        $last_id = DB::table('ticket')->latest('ticket_id')->first();
        if ($last_id) {
            $last_id = $last_id->ticket_id;
        } else {
            $last_id = 0;
        }
        $last_id = sprintf("%03d", ($last_id + 1));
        return $type_ticket . '_' . $time . '_' . $last_id;
    }

    public function createATag($link = '#', $text = "")
    {
        return $html = '<a href="' . $link . '">' . $text . '</a>';
    }

    /**
     * Lấy danh sách nhân viên
     * @param $ticketId
     * @return array
     */
    public function getListStaff($ticketId)
    {
        $mTicket = new TicketTable();
        $mProcessor = new TicketProcessorTable();

//        Lấy danh sách người chủ trì
        $getOperater = $mTicket->ticketDetailByTicket($ticketId);

        if ($getOperater != null && $getOperater['operate_by'] != null) {
            $listOperater = [$getOperater['operate_by']];
        } else {
            $listOperater = [];
        }

        $listProcessor = $mProcessor->getListProcessor($ticketId);
        if (count($listProcessor) != 0) {
            $listProcessor = collect($listProcessor)->pluck('staff_id');
        }

        $listArr = collect($listOperater)->merge($listProcessor)->toArray();
        $listArr = array_unique($listArr);
        $key = array_search(Auth::id(), $listArr);
        if ($key !== false) {
            unset($listArr[$key]);
        }
        return $listArr;
    }

    public function loadStaffByQueue($data)
    {
        $request_list['operate_by'] = '';
        $request_list['processor'] = '';
        $mTicketStaffQueue = new TicketStaffQueueTable();
        $operater_option = $mTicketStaffQueue->getListStaffByQueue($data['ticket_queue_id'], 2);
        $processor_option = $mTicketStaffQueue->getListStaffByQueue($data['ticket_queue_id'], 1);
        $arrData = [
            'staff_host' => $operater_option,
            'staff_handler' => $processor_option
        ];
        return $arrData;
    }


    public function loadQueue()
    {

        $mTicketQueue = new TicketQueueTable();
        $data = $mTicketQueue->getName();
        return $data;
    }

    /**
     * Danh sách bình luận
     * @param $data
     * @return mixed|void
     */
    public function listComment($data)
    {
        try {

            $mManageComment = new TicketCommentTable();

            $listComment = $mManageComment->getListComment($data['ticket_id']);
            if (count($listComment) != 0) {
                foreach ($listComment as $key => $item) {
                    $listComment[$key]['list_object'] = $mManageComment->getListComment($item['ticket_id'], $item['ticket_comment_id']);

                }
            }

            return $listComment;
        } catch (\Exception|QueryException $exception) {
            throw new TicketRepoException(TicketRepoException::GET_TICKET_EDIT_FAILED, $exception->getMessage() . $exception->getLine());
        }
    }

    /**
     * Tạo comment
     * @param $data
     * @return mixed|void
     */
    public function createdComment($data)
    {
        try {
            $mTicketComment = new TicketCommentTable();

            $createdComment = [
                'ticket_id' => $data['ticket_id'],
                'ticket_parent_comment_id' => isset($data['ticket_parent_comment_id']) ? $data['ticket_parent_comment_id'] : null,
                'staff_id' => Auth::id(),
                'message' => isset($data['message']) ? $data['message'] : null,
                'path' => isset($data['path']) ? $data['path'] : null,
                'created_at' => Carbon::now(),
                'created_by' => Auth::id(),
                'updated_at' => Carbon::now(),
                'updated_by' => Auth::id()
            ];

            //Thêm bình luận
            $idComment = $mTicketComment->createdComment($createdComment);

            $detailComment = $mTicketComment->getDetail($idComment);

            //Gửi notify bình luận ticket
            $listCustomer = $this->getListStaff($data['ticket_id']);

            $mStaffNotify = app()->get(NotificationRepoInterface::class);

            foreach ($listCustomer as $item) {
                if ($item != Auth()->id()) {
                    $mStaffNotify->sendStaffNotification([
                        'key' => 'ticket_comment_new',
                        'customer_id' => Auth()->id(),
                        'object_id' => $data['ticket_id']
                    ]);
                }
            }


            // $dataNoti = [
            //     'key' => 'comment_new',
            //     'object_id' => $data['manage_work_id'],
            // ];
            // $this->staffNotification($dataNoti);

            return $this->listComment($data);
        } catch (\Exception|QueryException $exception) {
            throw new TicketRepoException(TicketRepoException::GET_TICKET_EDIT_FAILED, $exception->getMessage() . $exception->getLine());
        }
    }

}