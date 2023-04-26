<?php


namespace Modules\ManageWork\Repositories;


use Aws\S3\S3Client;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\ManageWork\Models\FileMinioConfigTable;
use Modules\ManageWork\Libs\UploadImage;
use Modules\ManageWork\Models\BranchsTable;
use Modules\ManageWork\Models\CustomerDealTable;
use Modules\ManageWork\Models\CustomerLeadTable;
use Modules\ManageWork\Models\CustomersTable;
use Modules\ManageWork\Models\DepartmentTable;
use Modules\ManageWork\Models\ManageCommentTable;
use Modules\ManageWork\Models\ManageConfigNotificationTable;
use Modules\ManageWork\Models\ManageDocumentFileTable;
use Modules\ManageWork\Models\ManageDocumentTable;
use Modules\ManageWork\Models\ManageHistoryTable;
use Modules\ManageWork\Models\ManageProjectTable;
use Modules\ManageWork\Models\ManageRemindTable;
use Modules\ManageWork\Models\ManageRepeatTimeTable;
use Modules\ManageWork\Models\ManageStatusConfigMapTable;
use Modules\ManageWork\Models\ManageStatusTable;
use Modules\ManageWork\Models\ManageTagsTable;
use Modules\ManageWork\Models\ManageTypeWorkTable;
use Modules\ManageWork\Models\ManageWorkLocationTable;
use Modules\ManageWork\Models\ManageWorkSupportTable;
use Modules\ManageWork\Models\ManageWorkTable;
use Modules\ManageWork\Models\ManageWorkTagTable;
use Modules\ManageWork\Models\StaffTable;
use Modules\ManageWork\Models\TicketTable;
use Modules\ManageWork\Models\ProjectPhaseTable;
use Modules\Notification\Models\NotificationDetailTable;
use Modules\Notification\Models\StaffEmailLogTable;
use Modules\Notification\Models\StaffNotificationDetailTable;
use Modules\Notification\Models\StaffNotificationTable;
use Modules\Notification\Repositories\Notification\NotificationRepo;
use MyCore\Repository\PagingTrait;

class ManageWorkRepository implements ManageWorkRepositoryInterface
{
    use PagingTrait;

    const not_started_yet = 1;
    const is_starting = 2;
    const started = 3;
    const pause = 4;
    const incomplete = 5;
    const complete = 6;
    const cancel = 7;

    /**
     * Lấy tổng công việc ở màn hình home
     */
    public function getTotalWork($data)
    {
        try {

            $mManageWork = new ManageWorkTable();
            $mManageWorkStatus = app()->get(ManageStatusTable::class);

            $data['from_date'] = $data['from_date'] ?? Carbon::now()->startOfMonth()->format('Y/m/d');
            $data['to_date'] = $data['to_date'] ?? Carbon::now()->endOfMonth()->format('Y/m/d');

            $total = $mManageWork->getTotalHome($data);
            $totalUpdate = $mManageWork->getTotalHomeUpdate($data);

            if (count($totalUpdate) != 0) {
                $totalUpdate = collect($totalUpdate)->keyBy('manage_status_id');
            }

            $listStatus = $mManageWorkStatus->getListStatus();

            if ($total != null) {
                //                $info = [
                //                    'total_work_day' => (int)$total['total_work_day'],
                ////                    'total_work' => (int)($total['total_not_started_yet'] + $total['total_started'] + $total['total_complete'] + $total['total_unfinished'] + $total['total_overdue']),
                //                    'total_work' => (int)$total['total_work'],
                //                    'total_not_started_yet' => (int)$total['total_not_started_yet'],
                //                    'total_started' => (int)$total['total_started'],
                //                    'total_complete' => (int)$total['total_complete'],
                //                    'total_unfinished' => (int)$total['total_unfinished'],
                //                    'total_overdue' => (int)$total['total_overdue'],
                //                ];

                $info = [
                    'total_work_day' => (int)$total['total_work_day'],
                    'total_work' => (int)$total['total_work'],
                    //                    'total_overdue' => (int)$total['total_overdue'],

                ];
                foreach ($listStatus as $keyupdate => $itemUpdate) {
                    $info['total'][] = [
                        'total' => isset($totalUpdate[$itemUpdate['manage_status_id']]) ? (int)$totalUpdate[$itemUpdate['manage_status_id']]['total_work'] : 0,
                        "is_chart" => 1,
                        "title" => $itemUpdate['manage_status_name'],
                        "color" => str_replace('#', '', $itemUpdate['manage_status_color']),
                        "id" => $itemUpdate['manage_status_id']
                    ];
                }
                $info['total'][] = [
                    'total' => 0,
                    "is_chart" => 0,
                    "title" => $total['total_overdue'] . '/' . $total['total_work'] . __(' công việc quá hạn'),
                    "color" => 'FFF'
                ];
            } else {
                $info = [
                    'total_work_day' => 0,
                    'total_work' => 0,
                    //                    'total_not_started_yet' => 0,
                    //                    'total_started' => 0,
                    //                    'total_complete' => 0,
                    //                    'total_unfinished' => 0,
                    //                    'total_overdue' => 0,
                ];
                foreach ($listStatus as $keyupdate => $itemUpdate) {
                    $info['total'][] = [
                        'total' => 0,
                        "is_chart" => 1,
                        "title" => $itemUpdate['manage_status_name'],
                        "color" => str_replace('#', '', $itemUpdate['manage_status_color']),
                        "id" => $itemUpdate['manage_status_id']
                    ];
                }

                $info['total'][] = [
                    'total' => 0,
                    "is_chart" => 0,
                    "title" => $total['total_overdue'] . '/' . $total['total_work'] . __(' công việc quá hạn'),
                    "color" => 'FFF'
                ];
            }

            return $info;
        } catch (\Exception|QueryException $exception) {
            throw new ManageWorkRepoException(ManageWorkRepoException::GET_MANAGE_WORK_TOTAL_FAILED);
        }
    }

    public function getTotalWorkSupport($data)
    {
        try {

            $mManageWork = new ManageWorkTable();
            $mManageWorkStatus = app()->get(ManageStatusTable::class);

            $data['from_date'] = Carbon::now()->startOfMonth()->format('Y/m/d');
            $data['to_date'] = Carbon::now()->endOfMonth()->format('Y/m/d');
            unset($data['branch_id']);
            $total = $mManageWork->getTotalHomeSupport($data);
            $totalUpdate = $mManageWork->getTotalHomeUpdateSupport($data);

            if (count($totalUpdate) != 0) {
                $totalUpdate = collect($totalUpdate)->keyBy('manage_status_id');
            }

            $listStatus = $mManageWorkStatus->getListStatus();

            if ($total != null) {
                //                $info = [
                //                    'total_work_day' => (int)$total['total_work_day'],
                ////                    'total_work' => (int)($total['total_not_started_yet'] + $total['total_started'] + $total['total_complete'] + $total['total_unfinished'] + $total['total_overdue']),
                //                    'total_work' => (int)$total['total_work'],
                //                    'total_not_started_yet' => (int)$total['total_not_started_yet'],
                //                    'total_started' => (int)$total['total_started'],
                //                    'total_complete' => (int)$total['total_complete'],
                //                    'total_unfinished' => (int)$total['total_unfinished'],
                //                    'total_overdue' => (int)$total['total_overdue'],
                //                ];

                $info = [
                    'total_work_day' => (int)$total['total_work_day'],
                    'total_work' => (int)$total['total_work'],
                    //                    'total_overdue' => (int)$total['total_overdue'],

                ];
                foreach ($listStatus as $keyupdate => $itemUpdate) {
                    $info['total'][] = [
                        'total' => isset($totalUpdate[$itemUpdate['manage_status_id']]) ? (int)$totalUpdate[$itemUpdate['manage_status_id']]['total_work'] : 0,
                        "is_chart" => 1,
                        "title" => $itemUpdate['manage_status_name'],
                        "color" => str_replace('#', '', $itemUpdate['manage_status_color']),
                        "id" => $itemUpdate['manage_status_id']
                    ];
                }
                $info['total'][] = [
                    'total' => 0,
                    "is_chart" => 0,
                    "title" => $total['total_overdue'] . '/' . $total['total_work'] . __(' công việc quá hạn'),
                    "color" => 'FFF'
                ];
            } else {
                $info = [
                    'total_work_day' => 0,
                    'total_work' => 0,
                    //                    'total_not_started_yet' => 0,
                    //                    'total_started' => 0,
                    //                    'total_complete' => 0,
                    //                    'total_unfinished' => 0,
                    //                    'total_overdue' => 0,
                ];
                foreach ($listStatus as $keyupdate => $itemUpdate) {
                    $info['total'][] = [
                        'total' => 0,
                        "is_chart" => 1,
                        "title" => $itemUpdate['manage_status_name'],
                        "color" => str_replace('#', '', $itemUpdate['manage_status_color']),
                        "id" => $itemUpdate['manage_status_id']
                    ];
                }

                $info['total'][] = [
                    'total' => 0,
                    "is_chart" => 0,
                    "title" => $total['total_overdue'] . '/' . $total['total_work'] . __(' công việc quá hạn'),
                    "color" => 'FFF'
                ];
            }
            Log::info($info);
            return $info;
        } catch (\Exception|QueryException $exception) {
            throw new ManageWorkRepoException(ManageWorkRepoException::GET_MANAGE_WORK_TOTAL_FAILED);
        }
    }


    /**
     * Tổng ticket , danh sách nhân viên chưa có công việc trong ngày
     * @param $data
     * @return array|mixed
     * @throws ManageWorkRepoException
     */
    public function jobOverview($data)
    {
        try {
            $data['job_overview'] = 1;
            $data['status_overdue'] = [1, 2, 5];
            $data['status_overdue_fix'] = [6, 7];
            $info = $this->getTotalWork($data);

            $mManageWork = new ManageWorkTable();
            $mManageWorkTag = new ManageWorkTagTable();
            $mManageWorkSupport = new ManageWorkSupportTable();
            $mManageComment = new ManageCommentTable();
            $mManageStatus = new ManageStatusTable();

            $staffs = new StaffTable();

            //            lấy danh sách công việc trễ hạn

            $info['list_overdue'] = $mManageWork->getListOverdue($data);

            foreach ($info['list_overdue'] as $key => $item) {
                $info['list_overdue'][$key]['total_message'] = $mManageComment->getTotalCommentByWork($item['manage_work_id']);
                $info['list_overdue'][$key]['tags'] = $mManageWorkTag->getListTagByWork($item['manage_work_id']);
                $info['list_overdue'][$key]['list_staff'] = $mManageWorkSupport->getListStaffByWork($item['manage_work_id']);
                $info['list_overdue'][$key]['list_status'] = $this->getListStatus($item);
            }
            //            Danh sách nhân viên chưa có công việc

            $listStaffJob = $staffs->staffNoJob($data);

            if (count($listStaffJob) != 0) {
                $listStaffJob = collect($listStaffJob)->pluck('staff_id');
            }

            //            Danh sách nhân viên chưa có việc làm
            $info['list_staff_no_job'] = $staffs->getListStaffNoJob($listStaffJob);

            //            Danh sách nhân viên chưa bắt đầu công việc
            $data['list_staff_no_started_work'] = 1;
            $info['list_staff_no_started_work'] = $staffs->staffNoJob($data);
            $data['is_overdue_hide'] = 0;
            unset($data['list_staff_no_started_work']);

            $info['list_job'] = $this->myWork($data);

            return $info;
        } catch (\Exception|QueryException $exception) {
            throw new ManageWorkRepoException(ManageWorkRepoException::GET_MANAGE_WORK_OVERVIEW_FAILED);
        }
    }

    /**
     * Danh sách chi nhánh
     * @return mixed|void
     */
    public function listBranch($data)
    {
        try {
            $mBranch = new BranchsTable();
            $list = $mBranch->getListBranch($data);

            return $list;
        } catch (\Exception|QueryException $exception) {
            throw new ManageWorkRepoException(ManageWorkRepoException::GET_MANAGE_WORK_BRANCH_FAILED);
        }
    }

    /**
     * Lấy danh sách phòng ban
     * @return mixed|void
     */
    public function listDepartment($data)
    {
        try {
            $mDepartment = new DepartmentTable();

            $list = $mDepartment->getListDepartment($data);

            return $list;
        } catch (\Exception|QueryException $exception) {
            throw new ManageWorkRepoException(ManageWorkRepoException::GET_MANAGE_WORK_DEPARTMENT_FAILED);
        }
    }


    /**
     * Tạo nhắc nhở theo công việc hoặc theo nhân viên
     * @param $data
     * @return mixed|void
     * @throws ManageWorkRepoException
     */
    public function createReminder($data)
    {
        try {
            $mRemind = new ManageRemindTable();
            $mManageHistory = new ManageHistoryTable();
            $mManageWork = app()->get(ManageWorkTable::class);

            $mStaff = new StaffTable();

            if (!isset($data['list_staff']) || count($data['list_staff']) == 0) {
                throw new ManageWorkRepoException(ManageWorkRepoException::GET_MANAGE_WORK_CREATED_REMINDER_FAILED);
            }

//            if ($data['date_remind'] < Carbon::now()) {
//                throw new ManageWorkRepoException(ManageWorkRepoException::GET_MANAGE_WORK_CREATED_REMINDER_FAILED);
//            }

            $data['list_staff'] = collect($data['list_staff'])->keyBy('staff_id')->toArray();

            if (isset($data['manage_work_id'])) {
                $detailWork = $mManageWork->detailWork($data['manage_work_id']);
                $mManageWork->editWork([
                    'updated_at' => Carbon::now(),
                    'updated_by' => Auth::id()
                ], $data['manage_work_id']);
            }

            $dataRemind = [];
            foreach (array_values($data['list_staff']) as $item) {
                if (isset($data['date_remind'])) {

                    $created_by = $mStaff->getStaffId(Auth::id());
                    $staff_id = $mStaff->getStaffId($item['staff_id']);
                    if (isset($data['manage_work_id'])) {
                        $title = $created_by['staff_name'] . ' ' . __('tạo nhắc nhở về công việc :manage_work_title cho', ['manage_work_title' => $detailWork['manage_work_title']]) . ' ' . $staff_id['staff_name'];
                    } else {
                        $title = $created_by['staff_name'] . ' ' . __('tạo nhắc nhở cho ') . $staff_id['staff_name'];
                    }

                    //                    if ($item['staff_id'] != Auth::id()){
                    $dataRemind[] = [
                        'title' => $title,
                        'staff_id' => $item['staff_id'],
                        'date_remind' => $data['date_remind'],
                        'time' => isset($data['time']) && $data['time'] != null ? $data['time'] : null,
                        'time_type' => isset($data['time_type']) && $data['time_type'] != null ? $data['time_type'] : null,
                        'description' => isset($data['description']) ? $data['description'] : null,
                        'manage_work_id' => isset($data['manage_work_id']) ? $data['manage_work_id'] : '',
                        'created_at' => Carbon::now(),
                        'created_by' => Auth::id(),
                        'updated_at' => Carbon::now(),
                        'updated_by' => Auth::id()
                    ];
                    //                    }
                }
            }

            if (count($dataRemind) != 0) {

                $arrId = array_unique(collect($data['list_staff'])->pluck('staff_id')->toArray());
                $listStaff = $mStaff->getListStaffByArrId($arrId);
                $arrHistory = [];
                foreach ($listStaff as $item) {
                    $arrHistory[] = [
                        'manage_work_id' => isset($data['manage_work_id']) ? $data['manage_work_id'] : '',
                        'staff_id' => Auth::id(),
                        'message' => __(' đã tạo nhắc nhở cho ') . $item['staff_name'],
                        'created_at' => Carbon::now(),
                        'created_by' => Auth::id(),
                        'updated_at' => Carbon::now(),
                        'updated_by' => Auth::id()
                    ];
                }

                if (count($arrHistory) != 0) {
                    $mManageHistory->createdHistory($arrHistory);
                }

                $id = $mRemind->createdRemindGetId($dataRemind[0]);
                unset($dataRemind[0]);
                if (count($dataRemind) != 0) {
                    $mRemind->createdRemind($dataRemind);
                }

                $dataNoti = [
                    'key' => 'work_remind',
                    'object_id' => $id,
                ];
                $this->staffNotification($dataNoti);
            }
            return true;
        } catch (\Exception|QueryException $exception) {
            throw new ManageWorkRepoException(ManageWorkRepoException::GET_MANAGE_WORK_CREATED_REMINDER_FAILED);
        }
    }

    /**
     * Danh sách công việc
     * @param $data
     * @return mixed|void
     */
    public function listWork($data)
    {
        try {

            $mManageWork = new ManageWorkTable();

            $list = $mManageWork->getListWork($data);

            return $list;
        } catch (\Exception|QueryException $exception) {
            throw new ManageWorkRepoException(ManageWorkRepoException::GET_MANAGE_LIST_WORK_FAILED);
        }
    }

    /**
     * Chi tiết công việc
     * @param $data
     * @return mixed|void
     */
    public function workDetail($data)
    {
        try {
            $mManageWork = new ManageWorkTable();
            $mManageWorkSupport = new ManageWorkSupportTable();
            $mManageRepeatTime = new ManageRepeatTimeTable();
            $mManageWorkTag = new ManageWorkTagTable();

            //Lấy thông tin công việc
            $workDetail = $mManageWork->detailWork($data['manage_work_id']);
            if ($workDetail == null) {
                return null;
            }

            $createObjectCode = null;
            $createObjectName = null;
            if ($workDetail['create_object_type'] != null) {
                switch ($workDetail['create_object_type']) {
                    case 'ticket':
                        $mTicket = app()->get(TicketTable::class);
                        //Lấy thông tin ticket
                        $info = $mTicket->getInfo($workDetail['create_object_id']);

                        $createObjectCode = $info['ticket_code'];
                        $createObjectName = $info['title'];
                        break;
                }
            }

            $workDetail['create_object_code'] = $createObjectCode;
            $workDetail['create_object_name'] = $createObjectName;

            $workDetail['staff_support'] = $mManageWorkSupport->getListStaffByWork($data['manage_work_id']);
            $workDetail['list_repeat'] = $mManageRepeatTime->getListRepeatTime($data['manage_work_id']);
            $workDetail['tags'] = $mManageWorkTag->getListTagByWork($data['manage_work_id']);
            $workDetail['list_status'] = $this->getListStatus($workDetail);

            if ($workDetail['parent_id'] == null) {
                $workDetail['is_parent'] = $mManageWork->getTotalChild($data['manage_work_id']) != 0 ? 1 : 0;
            } else {
                $workDetail['is_parent'] = 0;
            }

            return $workDetail;
        } catch (\Exception|QueryException $exception) {
            throw new ManageWorkRepoException(ManageWorkRepoException::GET_MANAGE_WORK_DETAIL_FAILED, $exception->getMessage());
        }
    }

    /**
     * Danh sách công việc
     * @param $data
     * @return mixed|void
     */
    public function listWorkParent($data)
    {
        try {

            $mManageWork = new ManageWorkTable();

//            Nếu có truyền thêm id dự án thì sẽ lấy danh sách công việc cha theo id dự án
            $list = $data = $this->toPagingData($mManageWork->getListWorkParent($data));

            return $list;
        } catch (\Exception|QueryException $exception) {
            throw new ManageWorkRepoException(ManageWorkRepoException::GET_MANAGE_LIST_WORK_FAILED);
        }
    }

    /**
     * Duyệt công việc
     * @param $data
     * @return mixed|void
     * @throws ManageWorkRepoException
     */
    public function workApprove($data)
    {
        try {
            $mManageWork = new ManageWorkTable();
            $mManageHistory = new ManageHistoryTable();
            $status = null;

            if ($data['type'] == 'approve') {
                $status = self::complete;
            } else if ($data['type'] == 'reject') {
                $status = self::incomplete;
            }

            $detailOLd = $this->workDetail(['manage_work_id' => $data['manage_work_id']]);

            if ($status != null) {
                if ($status == 6) {
                    $mManageWork->editWork([
                        'manage_status_id' => $status,
                        'progress' => 100,
                        'updated_at' => Carbon::now(),
                        'updated_by' => Auth::id()
                    ], $data['manage_work_id']);
                } else {
                    $mManageWork->editWork([
                        'manage_status_id' => $status,
                        'updated_at' => Carbon::now(),
                        'updated_by' => Auth::id()
                    ], $data['manage_work_id']);
                }
            }

            $dataHistory = [
                'manage_work_id' => $data['manage_work_id'],
                'staff_id' => Auth::id(),
                'created_at' => Carbon::now(),
                'created_by' => Auth::id(),
                'updated_at' => Carbon::now(),
                'updated_by' => Auth::id()
            ];

            if ($status == self::complete) {
                $dataHistory['message'] = __(' đã duyệt công việc');
            } else {
                $dataHistory['message'] = __(' đã từ chối công việc');
            }

            $mManageHistory->createdHistory($dataHistory);

            $detail = $this->workDetail(['manage_work_id' => $data['manage_work_id']]);

            $dataNoti = [
                'key' => 'work_update_status',
                'object_id' => $data['manage_work_id'],
            ];

            $this->staffNotification($dataNoti);


            $detail = $this->workDetail($data);

            return $detail;
        } catch (\Exception|QueryException $exception) {
            throw new ManageWorkRepoException(ManageWorkRepoException::GET_MANAGE_WORK_APPROVE_FAILED);
        }
    }

    /**
     * Danh sách bình luận
     * @param $data
     * @return mixed|void
     */
    public function listComment($data)
    {
        try {

            $mManageComment = new ManageCommentTable();

            $listComment = $mManageComment->getListComment($data['manage_work_id']);
            if (count($listComment) != 0) {
                foreach ($listComment as $key => $item) {
                    $listComment[$key]['list_object'] = $mManageComment->getListComment($data['manage_work_id'], $item['manage_comment_id']);
                }
            }

            return $listComment;
        } catch (\Exception|QueryException $exception) {
            throw new ManageWorkRepoException(ManageWorkRepoException::GET_MANAGE_LIST_COMMENT_FAILED);
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

            $mManageComment = new ManageCommentTable();
            $mManageWork = app()->get(ManageWorkTable::class);

            $createdComment = [
                'manage_work_id' => $data['manage_work_id'],
                'manage_parent_comment_id' => isset($data['manage_parent_comment_id']) ? $data['manage_parent_comment_id'] : null,
                'staff_id' => Auth::id(),
                'message' => isset($data['message']) ? $data['message'] : null,
                'path' => isset($data['path']) ? $data['path'] : null,
                'created_at' => Carbon::now(),
                'created_by' => Auth::id(),
                'updated_at' => Carbon::now(),
                'updated_by' => Auth::id()
            ];

            $mManageComment->createdComment($createdComment);

            $mManageWork->editWork([
                'updated_at' => Carbon::now(),
                'updated_by' => Auth::id()
            ], $data['manage_work_id']);

            $mManageHistory = new ManageHistoryTable();
            $dataHistory = [
                'manage_work_id' => $data['manage_work_id'],
                'message' => __(' đã tạo bình luận mới'),
                'staff_id' => Auth::id(),
                'created_at' => Carbon::now(),
                'created_by' => Auth::id(),
                'updated_at' => Carbon::now(),
                'updated_by' => Auth::id()
            ];

            $mManageHistory->createdHistory($dataHistory);

            $dataNoti = [
                'key' => 'comment_new',
                'object_id' => $data['manage_work_id'],
            ];
            $this->staffNotification($dataNoti);

            return $this->listComment($data);
        } catch (\Exception|QueryException $exception) {
            throw new ManageWorkRepoException(ManageWorkRepoException::GET_MANAGE_CREATED_COMMENT_FAILED);
        }
    }

    /**
     * Danh sách nhắc nhở
     * @param $data
     * @return array|mixed
     * @throws ManageWorkRepoException
     */
    public function listRemind($data)
    {
        try {

            $mManageRemind = new ManageRemindTable();

            $list = $mManageRemind->getListRemind($data['manage_work_id']);

            return $list;
        } catch (\Exception|QueryException $exception) {
            throw new ManageWorkRepoException(ManageWorkRepoException::GET_MANAGE_LIST_REMIND_FAILED);
        }
    }

    /**
     * Danh sách file
     * @param $data
     * @return mixed|void
     */
    public function listFile($data)
    {
        try {

            $mManageDocumentFile = new ManageDocumentFileTable();

            $list = $mManageDocumentFile->getListFileDoc($data['manage_work_id']);

            return $list;
        } catch (\Exception|QueryException $exception) {
            throw new ManageWorkRepoException(ManageWorkRepoException::GET_MANAGE_LIST_FILE_FAILED);
        }
    }

    /**
     * Danh sách lịch sử
     * @param $data
     * @return mixed|void
     */
    public function listHistory($data)
    {
        try {

            $mManageHistory = new ManageHistoryTable();

            $list = $mManageHistory->getListHistory($data['manage_work_id']);

            return $list;
        } catch (\Exception|QueryException $exception) {
            throw new ManageWorkRepoException(ManageWorkRepoException::GET_MANAGE_LIST_HISTORY_FAILED);
        }
    }

    /**
     * Thêm công việc
     * @param $data
     * @return mixed|void
     */
    public function addWork($data)
    {
        try {

            DB::beginTransaction();

            $mManageWork = new ManageWorkTable();
            $mManageWorkSupport = new ManageWorkSupportTable();
            $mManageWorkTag = new ManageWorkTagTable();
            $mProjectPhase = new ProjectPhaseTable();

//            if (isset($data['type_card_work'])) {
//                if ($data['type_card_work'] == 'kpi') {
//                    if (isset($data['is_approve_id']) && $data['is_approve_id'] != 1)
//                        throw new ManageWorkRepoException(ManageWorkRepoException::WORK_NEEDED_ACTIVE);
//                    if (isset($data['approve_id']) && $data['approve_id'] == null) {
//                        throw new ManageWorkRepoException(ManageWorkRepoException::INDISPENSABLE_APPROVER);
//                    }
//                }
//            }

            if (isset($data['staff_support']) && count($data['staff_support']) != 0) {
                $listStaffSupport = collect($data['staff_support'])->pluck('staff_id')->toArray();
                if (in_array($data['processor_id'], $listStaffSupport)) {
                    throw new ManageWorkRepoException(ManageWorkRepoException::GET_USING_STAFF_SUPPORT_FAILED);
                }
                if (isset($data['processor_id']) && in_array($data['processor_id'], $listStaffSupport)) {
                    throw new ManageWorkRepoException(ManageWorkRepoException::GET_USING_STAFF_SUPPORT_FAILED);
                }
            }

            $fromTime = '00:00';
            $endTime = '23:00';
            if (isset($data['from_time'])) {
                $fromTime = $data['from_time'];
            }

            if (isset($data['to_time'])) {
                $endTime = $data['to_time'];
            }

            //            if (isset($data['from_date'])){
            //                if (Carbon::parse($data['from_date'])->format('Y-m-d '.$fromTime.':00') < Carbon::now()){
            //                    throw new ManageWorkRepoException(ManageWorkRepoException::GET_MANAGE_EDIT_WORK_FAILED);
            //                }
            //            }
            if (isset($data['from_date'])) {
                if (Carbon::parse($data['from_date'])->format('Y-m-d ' . $fromTime . ':00') > Carbon::parse($data['to_date'])->format('Y-m-d ' . $endTime . ':00')) {
                    throw new ManageWorkRepoException(ManageWorkRepoException::GET_MANAGE_EDIT_WORK_FAILED);
                }
            }
            //them giai doan cho cong viecj neu cong viec thuoc du an
            $idPhase = isset($data['manage_project_phase_id']) ? $data['manage_project_phase_id'] : null;
            if (isset($data['manage_project_id']) && $data['manage_project_id'] != null && $data['manage_project_id'] != '') {
                $filter = [
                    'manage_project_id' => $data['manage_project_id'],
                ];
                $defaultPhase = $mProjectPhase->getDefaultPhase($filter);
                if ($idPhase == null || $idPhase == '') {
                    $idPhase = $defaultPhase['manage_project_phase_id'];
                }

            }

            $dataWork = [
                'manage_work_title' => $data['manage_work_title'],
                'manage_work_customer_type' => isset($data['manage_work_customer_type']) ? $data['manage_work_customer_type'] : null,
                'manage_work_code' => $this->codeWork($data),
                'manage_project_id' => isset($data['manage_project_id']) ? $data['manage_project_id'] : null,
                'manage_project_phase_id' => $idPhase,
                'manage_type_work_id' => $data['manage_type_work_id'],
                //                'date_end' => Carbon::parse($data['to_date'])->format('Y-m-d ' . $endTime . ':59'),
                'processor_id' => $data['processor_id'],
                'assignor_id' => Auth::id(),
                'time' => isset($data['time']) ? $data['time'] : 0,
                'branch_id' => isset($data['branch_id']) ? $data['branch_id'] : null,
                'time_type' => isset($data['time_type']) ? $data['time_type'] : null,
                'progress' => isset($data['progress']) ? $data['progress'] : 0,
                'customer_id' => isset($data['customer_id']) ? $data['customer_id'] : null,
                'description' => isset($data['description']) ? $data['description'] : null,
                'approve_id' => isset($data['approve_id']) ? $data['approve_id'] : null,
                'parent_id' => isset($data['parent_id']) ? $data['parent_id'] : null,
//                'type_card_work' => isset($data['type_card_work']) ? $data['type_card_work'] : null,
                'priority' => isset($data['priority']) ? $data['priority'] : null,
                'manage_status_id' => isset($data['manage_status_id']) ? $data['manage_status_id'] : 1,
                'is_approve_id' => isset($data['is_approve_id']) ? $data['is_approve_id'] : 1,
                'created_at' => Carbon::now(),
                'created_by' => Auth::id(),
                'updated_at' => Carbon::now(),
                'updated_by' => Auth::id(),
                "create_object_type" => isset($data['create_object_type']) ? $data['create_object_type'] : null,
                "create_object_id" => isset($data['create_object_id']) ? $data['create_object_id'] : null,
            ];

            if (isset($data['manage_work_id'])) {
                $dataWork['progress'] = 0;
                $dataWork['manage_status_id'] = 1;
            }

            if (isset($data['from_date']) && $data['from_date'] != Carbon::parse($data['from_date'])->format('Y-m-d H:i')) {
                $dataWork['date_start'] = isset($data['from_date']) ? Carbon::parse($data['from_date'])->format('Y-m-d ' . $fromTime . ':00') : null;
            } else {
                $dataWork['date_start'] = Carbon::now()->format('Y-m-d H:i:00');
            }
//            if (isset($data['from_date']) && $data['from_date'] == Carbon::parse($data['from_date'])->format('Y-m-d H:i')) {
//                $dataWork['date_start'] = $data['from_date'];
//            } else {
//                //láy giờ để gộp
//                if (isset($data['from_date']) && $data['from_date'] != Carbon::parse($data['from_date'])->format('Y-m-d H:i')) {
//                    $dataWork['date_start'] = isset($data['from_date']) ? Carbon::parse($data['from_date'])->format('Y-m-d ' . $fromTime . ':00') : null;
//                } else {
//                    $dataWork['date_start'] = Carbon::now()->format('Y-m-d H:i:00');
//                }
//            }

            if (isset($data['to_date']) && $data['to_date'] != Carbon::parse($data['to_date'])->format('Y-m-d H:i')) {
                $dataWork['date_end'] = isset($data['to_date']) ? Carbon::parse($data['to_date'])->format('Y-m-d ' . $endTime . ':00') : null;
            } else {
                $dataWork['date_end'] = Carbon::now()->format('Y-m-d H:i:00');
            }
//            if (isset($data['to_date']) && $data['to_date'] = Carbon::parse($data['to_date'])->format('Y-m-d H:i')) {
//                $dataWork['date_end'] = $data['to_date'];
//            } else {
//                //láy giờ để gộp
//                if (isset($data['to_date']) && $data['to_date'] != Carbon::parse($data['to_date'])->format('Y-m-d H:i')) {
//                    $dataWork['date_end'] = isset($data['to_date']) ? Carbon::parse($data['to_date'])->format('Y-m-d ' . $fromTime . ':00') : null;
//                } else {
//                    $dataWork['date_end'] = Carbon::now()->format('Y-m-d H:i:00');
//                }
//            }
            //thời gian công việc phải nằm trong thời gian dự án
//            if (isset($data['from_date']) && $data['from_date'] != null) {
//                $DateProject = new ManageProjectTable();
//                $date = $DateProject->getDate($data['manage_project_id']);
//
//                if (
//                    $data['from_date'] < $date['date_start']
//                    || $data['from_date'] > $date['date_end']
//                    || $data['to_date'] < $date['date_start']
//                    || $data['to_date'] > $date['date_end']
//                ) {
//                    throw new ManageWorkRepoException(ManageWorkRepoException::OUTSIDE_THE_PROJECT_TIME);
//                } else {
//                    if ($data['to_date'] > $date['from_date']) {
//                        throw new ManageWorkRepoException(ManageWorkRepoException::WRONG_CHRONOLOGICAL_ORDER);
//                    }
//                }
//            }

            $idWork = $mManageWork->createdWork($dataWork);
            if (isset($dataWork['manage_work_customer_type']) && $dataWork['manage_work_customer_type'] == 'lead') {
                if (isset($dataWork['customer_id'])) {
                    $care = app()->get(CustomerLeadTable::class);
                    $dateCare = [
                        'date_last_care' => Carbon::now()
                    ];
                    $updateCare = $care->updateCare($dateCare, $dataWork['customer_id']);
                }
            }
            if (isset($data['staff_support']) && count($data['staff_support']) != 0) {
                $dataStaff = [];
                foreach ($data['staff_support'] as $item) {
                    $dataStaff[] = [
                        'manage_work_id' => $idWork,
                        'staff_id' => $item['staff_id'],
                        'created_at' => Carbon::now(),
                        'created_by' => Auth::id(),
                        'updated_at' => Carbon::now(),
                        'updated_by' => Auth::id()
                    ];
                }

                $mManageWorkSupport->addStaffSupport($dataStaff);
            }

            if (isset($data['remind_work']) && isset($data['remind_work']['date_remind'])) {
                $dataRemind = $data['remind_work'];
                $dataRemind['list_staff'][0]['staff_id'] = $data['processor_id'];
                $dataRemind['manage_work_id'] = $idWork;
                $this->createReminder($dataRemind);
            }

            if (isset($data['repeat_work']) && isset($data['remind_work']['date_remind'])) {
                $dataRepeatWork = $data['repeat_work'];
                $dataRepeatWork['manage_work_id'] = $idWork;
                $this->editRepeatWork($dataRepeatWork);
            }

            if (isset($data['list_tag']) && count($data['list_tag']) != 0) {
                $dataTag = [];
                foreach ($data['list_tag'] as $item) {
                    $dataTag[] = [
                        'manage_work_id' => $idWork,
                        'manage_tag_id' => $item['manage_tag_id'],
                        'created_at' => Carbon::now(),
                        'created_by' => Auth::id(),
                        'updated_at' => Carbon::now(),
                        'updated_by' => Auth::id()
                    ];
                }

                $mManageWorkTag->createdWorkTag($dataTag);
            }

            $detail = $this->workDetail(['manage_work_id' => $idWork]);

            //            Xử lý tiến độ task cha
            $detailNew = $mManageWork->detailWork($idWork);
            if ($detailNew != null && $detailNew['parent_id'] != null) {
                $listWorkChild = $mManageWork->getListChildWorkByParent($detailNew['parent_id']);
                if ($listWorkChild != null && $listWorkChild['total_child'] != 0) {
                    $detailParentOLd = $mManageWork->detailWork($detailNew['parent_id']);

                    $avg = round($listWorkChild['total_process'] / $listWorkChild['total_child']);

                    $mManageWork->editWork(['progress' => $avg], $detailNew['parent_id']);

                    //                    Ghi log cập nhật tiến độ task cha
                    if ($detailParentOLd != null && $detailParentOLd['progress'] != $avg) {

                        $message = __(' đã cập nhật tiến độ công việc ') . $detailParentOLd['manage_work_title'] . __(' từ ') . $detailParentOLd['progress'] . '%' . __(' sang ') . $avg . '%';
                        $this->createHistory($detailNew['parent_id'], $message);
                    }
                }
            }

            //            lưu lịch sử
            $dataHistory = [
                'manage_work_id' => $idWork,
                'staff_id' => Auth::id(),
                'message' => __(' đã tạo công việc và phân công ') . $detail['processor_name'] . __(' xử lý'),
                'created_at' => Carbon::now(),
                'created_by' => Auth::id(),
                'updated_at' => Carbon::now(),
                'updated_by' => Auth::id()
            ];

            $mManageHistory = new ManageHistoryTable();

            $mManageHistory->createdHistory($dataHistory);

            if (isset($data['manage_work_id'])) {
                $listWorkChild = $mManageWork->getListWorkChildInsert($data['manage_work_id']);

                foreach ($listWorkChild as $keyChild => $itemChild) {
                    unset(
                        $itemChild['manage_work_id'],
                        $itemChild['manage_work_code'],
                        $itemChild['manage_color_code'],
                        $itemChild['repeat_type'],
                        $itemChild['repeat_end'],
                        $itemChild['repeat_end_time'],
                        $itemChild['repeat_end_type'],
                        $itemChild['repeat_end_full_time'],
                        $itemChild['repeat_time'],
                        $itemChild['customer_name'],
                        $itemChild['manage_type_work_name'],
                        $itemChild['manage_project_name'],
                        $itemChild['parent_manage_work_code'],
                        $itemChild['parent_manage_work_title'],
                        $itemChild['createdStaff_name'],
                        $itemChild['approve_name'],
                        $itemChild['staff_name'],
                        $itemChild['manage_status_name'],
                        $itemChild['list_support'],
                        $itemChild['list_tag'],
                        $itemChild['is_edit'],
                        $itemChild['is_deleted'],
                        $itemChild['lead_name'],
                        $itemChild['deal_name'],
                        $itemChild['total_child_job'],
                    );
                    $itemChild['manage_work_code'] = $this->codeWork($itemChild);
                    $itemChild['parent_id'] = $idWork;
                    $itemChild['progress'] = 0;
                    $itemChild['manage_status_id'] = 1;
                    $itemChild['created_by'] = Auth::id();
                    $itemChild['updated_by'] = Auth::id();
                    $itemChild['created_at'] = Carbon::now();
                    $itemChild['updated_at'] = Carbon::now();

                    $idChildWork = $mManageWork->createdWork(collect($itemChild)->toArray());

                    $staffSupport = $mManageWorkSupport->getListSupport($data['manage_work_id']);

                    if (count($staffSupport) != 0) {
                        $dataStaff = [];
                        foreach ($data['staff_support'] as $item) {
                            $dataStaff[] = [
                                'manage_work_id' => $idChildWork,
                                'staff_id' => $item['staff_id'],
                                'created_at' => Carbon::now(),
                                'created_by' => Auth::id(),
                                'updated_at' => Carbon::now(),
                                'updated_by' => Auth::id()
                            ];
                        }

                        $mManageWorkSupport->addStaffSupport($dataStaff);
                    }

                    $listTag = $mManageWorkTag->getListTagByWork($data['manage_work_id']);

                    if (count($listTag) != 0) {
                        $dataTag = [];
                        foreach ($data['list_tag'] as $item) {
                            $dataTag[] = [
                                'manage_work_id' => $idChildWork,
                                'manage_tag_id' => $item['manage_tag_id'],
                                'created_at' => Carbon::now(),
                                'created_by' => Auth::id(),
                                'updated_at' => Carbon::now(),
                                'updated_by' => Auth::id()
                            ];
                        }

                        $mManageWorkTag->createdWorkTag($dataTag);
                    }
                }
            }
            $mManageDocumentFile = new ManageDocumentFileTable();

            if (isset($data['list_document'])) {
                foreach ($data['list_document'] as $itemDocument) {
                    $cutLink = [];
                    $cutLink = explode('/', $itemDocument);
                    $nameFile = count($cutLink) != 0 ? end($cutLink) : '';

                    $dataFile = [
                        'manage_work_id' => $idWork,
                        'file_name' => $nameFile,
                        'path' => $itemDocument,
                        'created_at' => Carbon::now(),
                        'created_by' => Auth::id(),
                        'updated_at' => Carbon::now(),
                        'updated_by' => Auth::id()
                    ];

                    $mManageDocumentFile->createFileByDocument($dataFile);

                    $dataHistory = [
                        'manage_work_id' => $idWork,
                        'staff_id' => Auth::id(),
                        'message' => __(' đã thêm tài liệu ') . $nameFile,
                        'created_at' => Carbon::now(),
                        'created_by' => Auth::id(),
                        'updated_at' => Carbon::now(),
                        'updated_by' => Auth::id()
                    ];

                    $mManageHistory = new ManageHistoryTable();
                    $mManageHistory->createdHistory($dataHistory);
                }
            }

            DB::commit();

            $dataNoti = [
                'key' => 'work_assign',
                'object_id' => $idWork,
            ];
            $this->staffNotification($dataNoti);

            return $detail;
        } catch (\Exception|QueryException $exception) {
            DB::rollBack();
            throw new ManageWorkRepoException(ManageWorkRepoException::GET_MANAGE_ADD_WORK_FAILED, $exception->getMessage());
        }
    }

    /**
     * Chỉnh sửa công việc
     * @param $data
     * @return mixed|void
     */
    public function editWork($data)
    {

        try {

            $mManageWork = new ManageWorkTable();
            $mManageWorkSupport = new ManageWorkSupportTable();
            $mManageWorkTag = new ManageWorkTagTable();

            $idWork = $data['manage_work_id'];

            if (isset($data['staff_support']) && count($data['staff_support']) != 0) {
                $listStaffSupport = collect($data['staff_support'])->pluck('staff_id')->toArray();
                if (in_array($data['processor_id'], $listStaffSupport)) {
                    throw new ManageWorkRepoException(ManageWorkRepoException::GET_USING_STAFF_SUPPORT_FAILED);
                }
                if (isset($data['processor_id']) && in_array($data['processor_id'], $listStaffSupport)) {
                    throw new ManageWorkRepoException(ManageWorkRepoException::GET_USING_STAFF_SUPPORT_FAILED);
                }
            }


            $detailOLd = $this->workDetail(['manage_work_id' => $idWork]);

            //            kiểm tra tác vụ có tác vụ con hay k
            if ($detailOLd['is_parent'] == 1 && isset($data['parent_id']) && $data['parent_id'] != null) {
                throw new ManageWorkRepoException(ManageWorkRepoException::GET_USING_STAFF_SUPPORT_FAILED);
            }

            $fromTime = '00:00';
            $endTime = '23:59';
            if (isset($data['from_time'])) {
                $fromTime = $data['from_time'];
            }

            if (isset($data['to_time'])) {
                $endTime = $data['to_time'];
            }

            //            if (isset($data['from_date'])){
            //                if (Carbon::parse($data['from_date'])->format('Y-m-d '.$fromTime.':00') < Carbon::now()){
            //                    throw new ManageWorkRepoException(ManageWorkRepoException::GET_MANAGE_EDIT_WORK_FAILED);
            //                }
            //            }

            if (isset($data['from_date'])) {
                if (Carbon::parse($data['from_date'])->format('Y-m-d ' . $fromTime . ':00') > Carbon::parse($data['to_date'])->format('Y-m-d ' . $endTime . ':00')) {
                    throw new ManageWorkRepoException(ManageWorkRepoException::GET_MANAGE_EDIT_WORK_FAILED);
                }
            }

            $dataWork = [
                'manage_work_title' => $data['manage_work_title'],
                'manage_work_customer_type' => isset($data['manage_work_customer_type']) ? $data['manage_work_customer_type'] : null,
                'manage_project_id' => isset($data['manage_project_id']) ? $data['manage_project_id'] : null,
                'manage_type_work_id' => $data['manage_type_work_id'],
                'date_end' => Carbon::parse($data['to_date'])->format('Y-m-d ' . $endTime . ':59'),
                'processor_id' => $data['processor_id'],
                'time' => isset($data['time']) ? $data['time'] : 0,
                'branch_id' => isset($data['branch_id']) ? $data['branch_id'] : null,
                'time_type' => isset($data['time_type']) ? $data['time_type'] : null,
                'progress' => isset($data['manage_status_id']) && $data['manage_status_id'] == 6 ? 100 : (isset($data['progress']) ? $data['progress'] : 0),
                'customer_id' => isset($data['customer_id']) ? $data['customer_id'] : null,
                'description' => isset($data['description']) ? $data['description'] : null,
                'approve_id' => isset($data['approve_id']) ? $data['approve_id'] : null,
                'parent_id' => isset($data['parent_id']) ? $data['parent_id'] : null,
                'type_card_work' => isset($data['type_card_work']) ? $data['type_card_work'] : null,
                'priority' => isset($data['priority']) ? $data['priority'] : null,
                'manage_status_id' => isset($data['manage_status_id']) ? $data['manage_status_id'] : 1,
                'updated_at' => Carbon::now(),
                'updated_by' => Auth::id()
            ];

            //            Nếu là task cha thì sẽ k cập nhật tiến độ , tiến độ ăn théo công việc của task con
            if ($detailOLd['is_parent'] == 1) {
                unset($dataWork['progress']);
            }
            if (isset($data['from_date'])) {
                $dataWork['date_start'] = isset($data['from_date']) ? Carbon::parse($data['from_date'])->format('Y-m-d ' . $fromTime . ':00') : null;
            }

            if (isset($data['date_finish']) && $detailOLd['date_finish'] == null) {
                $dataWork['date_finish'] = Carbon::now();
            }

            $mManageWork->editWork($dataWork, $idWork);

            $detailNew = $mManageWork->detailWork($idWork);

            if ($detailNew != null && $detailNew['parent_id'] != null) {
                $listWorkChild = $mManageWork->getListChildWorkByParent($detailNew['parent_id']);
                if ($listWorkChild != null && $listWorkChild['total_child'] != 0) {
                    $detailParentOLd = $mManageWork->detailWork($detailNew['parent_id']);
                    $avg = round($listWorkChild['total_process'] / $listWorkChild['total_child']);
                    $mManageWork->editWork(['progress' => $avg], $detailNew['parent_id']);

                    //                    Ghi log cập nhật tiến độ task cha
                    if ($detailParentOLd['progress'] != $avg) {
                        $message = __(' đã cập nhật tiến độ công việc ') . $detailParentOLd['manage_work_title'] . __(' từ ') . $detailParentOLd['progress'] . '%' . __(' sang ') . $avg . '%';
                        $this->createHistory($detailNew['parent_id'], $message);
                    }
                }
            }

            if ($detailNew['manage_status_id'] == 7 && $detailNew['parent_id'] == null) {
                $listTaskChildOld = $mManageWork->getListTaskOfParent($detailNew['manage_work_id']);
                $mManageWork->updateByParentId([
                    'manage_status_id' => $detailNew['manage_status_id']
                ], $detailNew['manage_work_id']);

                $listTaskChildNew = $mManageWork->getListTaskOfParent($detailNew['manage_work_id']);

                if (count($listTaskChildOld) != 0) {
                    $listTaskChildOld = collect($listTaskChildOld)->keyBy('manage_work_id');
                    $listTaskChildNew = collect($listTaskChildNew)->keyBy('manage_work_id');

                    foreach ($listTaskChildOld as $keyTask => $itemTask) {
                        $message = __(' đã cập nhật trạng thái công việc con ') . $detailNew['manage_work_title'] . __(' từ ') . $itemTask['manage_status_name'] . __(' sang ') . $listTaskChildNew[$keyTask]['manage_status_name'];
                        $this->createHistory($itemTask['manage_work_id'], $message);
                    }
                }
            }

            $mManageWorkSupport->deleteSupportByWork($idWork);
            if (isset($data['staff_support']) && count($data['staff_support']) != 0) {
                $dataStaff = [];
                foreach ($data['staff_support'] as $item) {
                    $dataStaff[] = [
                        'manage_work_id' => $idWork,
                        'staff_id' => $item['staff_id'],
                        'created_at' => Carbon::now(),
                        'created_by' => Auth::id(),
                        'updated_at' => Carbon::now(),
                        'updated_by' => Auth::id()
                    ];
                }

                $mManageWorkSupport->addStaffSupport($dataStaff);
            }

            if (isset($data['repeat_work'])) {
                $dataRepeatWork = $data['repeat_work'];
                $dataRepeatWork['manage_work_id'] = $idWork;
                $this->editRepeatWork($dataRepeatWork);
            }

            $mManageWorkTag->deleteWorkTag($idWork);
            if (isset($data['list_tag']) && count($data['list_tag']) != 0) {
                $dataTag = [];
                foreach ($data['list_tag'] as $item) {
                    $dataTag[] = [
                        'manage_work_id' => $idWork,
                        'manage_tag_id' => $item['manage_tag_id'],
                        'created_at' => Carbon::now(),
                        'created_by' => Auth::id(),
                        'updated_at' => Carbon::now(),
                        'updated_by' => Auth::id()
                    ];
                }

                $mManageWorkTag->createdWorkTag($dataTag);
            }

            $detail = $this->workDetail(['manage_work_id' => $idWork]);

            if (isset($data['manage_status_id']) && $detailOLd['manage_status_id'] != $data['manage_status_id']) {
                $dataNoti = [
                    'key' => 'work_update_status',
                    'object_id' => $idWork,
                ];

                $this->staffNotification($dataNoti);

                if ($data['manage_status_id'] == 3) {

                    $dataNoti = [
                        'key' => 'work_finish',
                        'object_id' => $idWork,
                    ];

                    $this->staffNotification($dataNoti);
                }
            }

            if (isset($data['processor_id']) && $detailOLd['processor_id'] != $data['processor_id']) {
                $dataNoti = [
                    'key' => 'work_assign',
                    'object_id' => $idWork,
                ];
                $this->staffNotification($dataNoti);
            }

            if (isset($data['description']) && $detailOLd['description'] != $data['description']) {
                $dataNoti = [
                    'key' => 'work_update_description',
                    'object_id' => $idWork,
                ];
                $this->staffNotification($dataNoti);
            }

            $checkUpdate = 0;
            if ($detailOLd['manage_status_id'] != $dataWork['manage_status_id']) {
                $mManageStatus = app()->get(ManageStatusTable::class);
                $oldStatus = $mManageStatus->getItem($detailOLd['manage_status_id']);
                $newStatus = $mManageStatus->getItem($dataWork['manage_status_id']);
                $message = __(' đã cập nhật trạng thái công việc ') . $dataWork['manage_work_title'] . __(' từ ') . $oldStatus['manage_status_name'] . __(' sang ') . $newStatus['manage_status_name'];
                $this->createHistory($idWork, $message);
                $checkUpdate = 1;
                if ($detailOLd['is_parent'] == 0 && $dataWork['manage_status_id'] == 6) {
                    $message = __(' đã cập nhật tiến độ công việc ') . $dataWork['manage_work_title'] . __(' từ ') . $detailOLd['progress'] . '%' . __(' sang ') . '100%';
                    $this->createHistory($idWork, $message);
                }
            }

            if (isset($dataWork['progress'])) {
                if ($detailOLd['progress'] != $dataWork['progress']) {
                    $message = __(' đã cập nhật tiến độ công việc ') . $dataWork['manage_work_title'] . __(' từ ') . $detailOLd['progress'] . '%' . __(' sang ') . $dataWork['progress'] . '%';
                    $this->createHistory($idWork, $message);
                    $checkUpdate = 1;
                }
            }

            if ($detailOLd['processor_id'] != $dataWork['processor_id']) {
                $mStaff = app()->get(StaffTable::class);
                $oldProcessor = $mStaff->getStaffId($detailOLd['processor_id']);
                $newProcessor = $mStaff->getStaffId($dataWork['processor_id']);
                $message = __(' đã cập nhật người thực hiện công việc ') . $dataWork['manage_work_title'] . __(' từ ') . $oldProcessor['staff_name'] . __(' sang ') . $newProcessor['staff_name'];
                $this->createHistory($idWork, $message);
                $checkUpdate = 1;
            }

            if ($detailOLd['date_end'] != $dataWork['date_end']) {
                $message = __(' đã cập nhật ngày hết hạn công việc ') . $dataWork['manage_work_title'] . __(' từ ') . Carbon::parse($detailOLd['date_end'])->format('H:i:s d/m/Y') . __(' sang ') . Carbon::parse($dataWork['date_end'])->format('H:i:s d/m/Y');
                $this->createHistory($idWork, $message);
                $checkUpdate = 1;
            }

            if ($checkUpdate == 0) {
                $message = __(' đã cập nhật công việc');
                $this->createHistory($idWork, $message);
            }

            return $detail;
        } catch (\Exception|QueryException $exception) {
            Log::info($exception->getLine());
            throw new ManageWorkRepoException(ManageWorkRepoException::GET_MANAGE_EDIT_WORK_FAILED, $exception->getMessage());
        }
    }

    public function createHistory($manage_work_id, $message)
    {
        $dataHistory = [
            'manage_work_id' => $manage_work_id,
            'staff_id' => Auth::id(),
            'message' => $message,
            'created_at' => Carbon::now(),
            'created_by' => Auth::id(),
            'updated_at' => Carbon::now(),
            'updated_by' => Auth::id()
        ];

        $mManageHistory = new ManageHistoryTable();
        $mManageHistory->createdHistory($dataHistory);
    }

    /**
     * Gửi noti
     * @param $data
     */
    public function staffNotification($data)
    {

        $mManageConfigNotification = new ManageConfigNotificationTable();
        $mManageWork = new ManageWorkTable();
        $mManageDocumentFile = new ManageDocumentFileTable();
        $mManageRemind = new ManageRemindTable();

        $oClient = new Client();
        $configNoti = $mManageConfigNotification->getConfigByKey($data['key']);
        $dataGroupNoti = [];
        if ($configNoti != null) {
            switch ($configNoti['manage_config_notification_key']) {
                case 'work_finish':

                    $info = $mManageWork->detailWorkNoti($data['object_id']);

                    $message = str_replace(['[manage_work_title]', '[updated_name]', '[date_end]'], [$info['manage_work_title'], $info['updated_name'], Carbon::parse($info['date_end'])->format('d/m/Y H:i')], $configNoti['manage_config_notification_message']);
                    $content = str_replace(['[manage_work_title]', '[updated_name]', '[date_end]'], [$info['manage_work_title'], $info['updated_name'], Carbon::parse($info['date_end'])->format('d/m/Y H:i')], $configNoti['manage_config_notification_message']);

                    $params = str_replace(
                        [
                            '[:manage_work_id]'
                        ],
                        [
                            $info['manage_work_id']
                        ],
                        $configNoti['detail_action_params']
                    );

                    //Data insert
                    $dataNotificationDetail = [
                        'background' => '',
                        'content' => $content,
                        'action_name' => $configNoti['detail_action_name'],
                        'action' => $configNoti['detail_action'],
                        'action_params' => $params
                    ];
                    $dataNotification = [
                        'user_id' => $configNoti['customer_id'],
                        'notification_avatar' => $configNoti['avatar'],
                        'notification_title' => $configNoti['manage_config_notification_title'],
                        'notification_message' => $message
                    ];

                    if ($info['is_approve_id'] == 1) {
                        $this->addEmailLog($dataNotificationDetail, $dataNotification, $configNoti, $info);
                        $this->insertNotificationLog($dataNotificationDetail, $dataNotification, $configNoti, $info);
                    }

                    break;

                case 'work_assign':

                    $info = $mManageWork->detailWorkNoti($data['object_id']);

                    $message = str_replace(['[created_name]', '[processor_name]', '[manage_work_title]', '[date_end]'], [$info['updated_name'], $info['processor_name'], $info['manage_work_title'], Carbon::parse($info['date_end'])->format('d/m/Y H:i')], $configNoti['manage_config_notification_message']);
                    $content = str_replace(['[created_name]', '[processor_name]', '[manage_work_title]', '[date_end]'], [$info['updated_name'], $info['processor_name'], $info['manage_work_title'], Carbon::parse($info['date_end'])->format('d/m/Y H:i')], $configNoti['manage_config_notification_message']);

                    $params = str_replace(
                        [
                            '[:manage_work_id]'
                        ],
                        [
                            $info['manage_work_id']
                        ],
                        $configNoti['detail_action_params']
                    );

                    //Data insert
                    $dataNotificationDetail = [
                        'background' => '',
                        'content' => $content,
                        'action_name' => $configNoti['detail_action_name'],
                        'action' => $configNoti['detail_action'],
                        'action_params' => $params
                    ];
                    $dataNotification = [
                        'user_id' => $configNoti['customer_id'],
                        'notification_avatar' => $configNoti['avatar'],
                        'notification_title' => $configNoti['manage_config_notification_title'],
                        'notification_message' => $message
                    ];

                    $this->addEmailLog($dataNotificationDetail, $dataNotification, $configNoti, $info);
                    $this->insertNotificationLog($dataNotificationDetail, $dataNotification, $configNoti, $info);
                    break;
                case 'work_update_status':

                    $info = $mManageWork->detailWorkNoti($data['object_id']);

                    $message = str_replace(['[manage_work_title]', '[updated_name]', '[manage_status_name]'], [$info['manage_work_title'], $info['updated_name'], $info['manage_status_name']], $configNoti['manage_config_notification_message']);
                    $content = str_replace(['[manage_work_title]', '[updated_name]', '[manage_status_name]'], [$info['manage_work_title'], $info['updated_name'], $info['manage_status_name']], $configNoti['manage_config_notification_message']);

                    $params = str_replace(
                        [
                            '[:manage_work_id]'
                        ],
                        [
                            $info['manage_work_id']
                        ],
                        $configNoti['detail_action_params']
                    );

                    //Data insert
                    $dataNotificationDetail = [
                        'background' => '',
                        'content' => $content,
                        'action_name' => $configNoti['detail_action_name'],
                        'action' => $configNoti['detail_action'],
                        'action_params' => $params
                    ];
                    $dataNotification = [
                        'user_id' => $configNoti['customer_id'],
                        'notification_avatar' => $configNoti['avatar'],
                        'notification_title' => $configNoti['manage_config_notification_title'],
                        'notification_message' => $message
                    ];

                    $this->addEmailLog($dataNotificationDetail, $dataNotification, $configNoti, $info);
                    $this->insertNotificationLog($dataNotificationDetail, $dataNotification, $configNoti, $info);
                    break;
                case 'comment_new':

                    $info = $mManageWork->detailWorkNoti($data['object_id']);

                    $mManageComment = app()->get(ManageCommentTable::class);

                    $lastComment = $mManageComment->getCommentLast($data['object_id']);

                    if ($lastComment != null) {
                        $info['update_comment'] = $lastComment['updated_by'];
                        $info['update_comment_parent'] = $lastComment['updated_by_parent'];
                    }

                    $message = str_replace(['[manage_work_title]'], [$info['manage_work_title']], $configNoti['manage_config_notification_message']);
                    $content = str_replace(['[manage_work_title]'], [$info['manage_work_title']], $configNoti['manage_config_notification_message']);

                    $params = str_replace(
                        [
                            '[:manage_work_id]'
                        ],
                        [
                            $info['manage_work_id']
                        ],
                        $configNoti['detail_action_params']
                    );

                    //Data insert
                    $dataNotificationDetail = [
                        'background' => '',
                        'content' => $content,
                        'action_name' => $configNoti['detail_action_name'],
                        'action' => $configNoti['detail_action'],
                        'action_params' => $params
                    ];
                    $dataNotification = [
                        'user_id' => $configNoti['customer_id'],
                        'notification_avatar' => $configNoti['avatar'],
                        'notification_title' => $configNoti['manage_config_notification_title'],
                        'notification_message' => $message
                    ];

                    $this->addEmailLog($dataNotificationDetail, $dataNotification, $configNoti, $info);
                    $this->insertNotificationLog($dataNotificationDetail, $dataNotification, $configNoti, $info);
                    break;
                case 'file_new':

                    $info = $mManageDocumentFile->getDetailFileNoti($data['object_id']);
                    $infoWork = null;
                    if ($info != null) {
                        $infoWork = $mManageWork->detailWorkNoti($info['manage_work_id']);
                    }

                    $message = str_replace(['[manage_work_title]', '[updated_name]', '[file_name]'], [$info['manage_work_title'], $info['staff_name'], $info['file_name']], $configNoti['manage_config_notification_message']);
                    $content = str_replace(['[manage_work_title]', '[updated_name]', '[file_name]'], [$info['manage_work_title'], $info['staff_name'], $info['file_name']], $configNoti['manage_config_notification_message']);

                    $params = str_replace(
                        [
                            '[:manage_work_id]'
                        ],
                        [
                            $info['manage_work_id']
                        ],
                        $configNoti['detail_action_params']
                    );

                    //Data insert
                    $dataNotificationDetail = [
                        'background' => '',
                        'content' => $content,
                        'action_name' => $configNoti['detail_action_name'],
                        'action' => $configNoti['detail_action'],
                        'action_params' => $params
                    ];
                    $dataNotification = [
                        'user_id' => $configNoti['customer_id'],
                        'notification_avatar' => $configNoti['avatar'],
                        'notification_title' => $configNoti['manage_config_notification_title'],
                        'notification_message' => $message
                    ];

                    $this->addEmailLog($dataNotificationDetail, $dataNotification, $configNoti, $infoWork);
                    $this->insertNotificationLog($dataNotificationDetail, $dataNotification, $configNoti, $infoWork);
                    break;
                case 'work_update_description':

                    $info = $mManageWork->detailWorkNoti($data['object_id']);

                    $message = str_replace(['[manage_work_title]', '[updated_name]', '[description]'], [$info['manage_work_title'], $info['updated_name'], strip_tags($info['description'])], $configNoti['manage_config_notification_message']);
                    $content = str_replace(['[manage_work_title]', '[updated_name]', '[description]'], [$info['manage_work_title'], $info['updated_name'], strip_tags($info['description'])], $configNoti['manage_config_notification_message']);

                    $params = str_replace(
                        [
                            '[:manage_work_id]'
                        ],
                        [
                            $info['manage_work_id']
                        ],
                        $configNoti['detail_action_params']
                    );

                    //Data insert
                    $dataNotificationDetail = [
                        'background' => '',
                        'content' => $content,
                        'action_name' => $configNoti['detail_action_name'],
                        'action' => $configNoti['detail_action'],
                        'action_params' => $params
                    ];
                    $dataNotification = [
                        'user_id' => $configNoti['customer_id'],
                        'notification_avatar' => $configNoti['avatar'],
                        'notification_title' => $configNoti['manage_config_notification_title'],
                        'notification_message' => $message
                    ];

                    $this->addEmailLog($dataNotificationDetail, $dataNotification, $configNoti, $info);
                    $this->insertNotificationLog($dataNotificationDetail, $dataNotification, $configNoti, $info);
                    break;
                case 'work_remind':

                    $info = $mManageRemind->getDetailRemindNoti($data['object_id']);
                    $infoWork = null;
                    if ($info != null) {
                        $infoWork = $mManageWork->detailWorkNoti($info['manage_work_id']);
                    }

                    $message = str_replace(['[created_name]', '[description]'], [$info['staff_name'], $info['description']], $configNoti['manage_config_notification_message']);
                    $content = str_replace(['[created_name]', '[description]'], [$info['staff_name'], $info['description']], $configNoti['manage_config_notification_message']);

                    $params = str_replace(
                        [
                            '[:manage_work_id]'
                        ],
                        [
                            $info['manage_work_id']
                        ],
                        $configNoti['detail_action_params']
                    );

                    //Data insert
                    $dataNotificationDetail = [
                        'background' => '',
                        'content' => $content,
                        'action_name' => $configNoti['detail_action_name'],
                        'action' => $configNoti['detail_action'],
                        'action_params' => $params
                    ];
                    $dataNotification = [
                        'user_id' => $configNoti['customer_id'],
                        'notification_avatar' => $configNoti['avatar'],
                        'notification_title' => $configNoti['manage_config_notification_title'],
                        'notification_message' => $message
                    ];
                    $this->addEmailLog($dataNotificationDetail, $dataNotification, $configNoti, $infoWork);
                    $this->insertNotificationLog($dataNotificationDetail, $dataNotification, $configNoti, $infoWork);
                    break;
                default:
                    break;
            }
        }
    }

    private function addEmailLog($dataNotificationDetail, $dataNotification, $configNoti, $info)
    {
        try {
            if ($configNoti['is_mail'] == 1) {
                $dataEmail = [];
                $staffEmailLog = new StaffEmailLogTable();
                $mManageWorkSupport = new ManageWorkSupportTable();
                $listStaff = [];
                if ($configNoti['is_created'] == 1) {
                    $listStaff[$info['created_by']] = $info['created_by'];
                }

                if ($configNoti['is_processor'] == 1) {
                    $listStaff[$info['processor_id']] = $info['processor_id'];
                }

                if ($configNoti['is_approve'] == 1) {
                    $listStaff[$info['approve_id']] = $info['approve_id'];
                }

                if ($configNoti['is_support'] == 1) {
                    $listSupport = $mManageWorkSupport->getListStaffByWork($info['manage_work_id']);
                    if (count($listSupport) != 0) {
                        $listSupport = collect($listSupport)->pluck('staff_id')->toArray();
                        $listStaff = array_merge($listStaff, $listSupport);
                    }
                }

                $key = array_search($info['updated_by'], $listStaff);
                if ($key !== false) {
                    unset($listStaff[$key]);
                }

                $listStaff = array_unique($listStaff);

                $staff = new StaffTable();

                $listEmail = $staff->getListStaffByArrId($listStaff);

                if (count($listEmail) != 0) {
                    $listEmail = collect($listEmail)->pluck('email');
                }

                if (count($listEmail)) {
                    $var = [
                        'content' => $dataNotification['notification_message'],
                        'title' => $dataNotification['notification_title']
                    ];
                    foreach ($listEmail as $v) {
                        $dataEmail[] = [
                            'email_type' => $configNoti['manage_config_notification_key'],
                            'email_subject' => $dataNotification['notification_title'],
                            'email_from' => env('MAIL_USERNAME'),
                            'email_to' => $v,
                            'email_params' => json_encode($var),
                            'is_run' => 0,
                            'created_at' => Carbon::now()
                        ];
                    }
                }

                if (count($dataEmail) != 0) {

                    $staffEmailLog->addEmail($dataEmail);
                }
            }
        } catch (\Exception $exception) {
            return '';
        }
    }


    private function insertNotificationLog($dataNotificationDetail, $dataNotification, $configNoti, $info)
    {
        try {
            if ($configNoti['is_noti'] == 1) {
                $mManageWorkSupport = new ManageWorkSupportTable();
                $listStaff = [];
                if ($configNoti['is_created'] == 1) {
                    $listStaff[$info['created_by']] = $info['created_by'];
                }

                if ($configNoti['is_processor'] == 1) {
                    $listStaff[$info['processor_id']] = $info['processor_id'];
                }

                if ($configNoti['is_approve'] == 1) {
                    $listStaff[$info['approve_id']] = $info['approve_id'];
                }

                if ($configNoti['is_support'] == 1) {
                    $listSupport = $mManageWorkSupport->getListStaffByWork($info['manage_work_id']);
                    if (count($listSupport) != 0) {
                        $listSupport = collect($listSupport)->pluck('staff_id')->toArray();

                        $listStaff = array_merge($listStaff, $listSupport);
                    }
                }

                if ($configNoti['manage_config_notification_key'] == 'comment_new') {
                    if ($info['update_comment_parent'] != null) {
                        $listStaff = [];
                        if (isset($info['update_comment']) && $info['update_comment'] != $info['update_comment_parent']) {
                            $listStaff[$info['update_comment_parent']] = $info['update_comment_parent'];
                        }
                    } else {
                        $key = array_search($info['updated_by'], $listStaff);
                        if ($key !== false) {
                            unset($listStaff[$key]);
                        }
                    }
                } else {
                    $key = array_search($info['updated_by'], $listStaff);
                    if ($key !== false) {
                        unset($listStaff[$key]);
                    }
                }


                $mNotificationDetail = app()->get(StaffNotificationDetailTable::class);
                $mStaff = app()->get(\Modules\Notification\Models\StaffTable::class);
                $oClient = new Client();
                //Insert notification detail
                $idNotificationDetail = $mNotificationDetail->add($dataNotificationDetail);
                $listStaff = array_unique($listStaff);
                if (count($listStaff)) {

                    foreach ($listStaff as $v) {

                        $response = $oClient->request('POST', NAE_SERVICE_URL . '/notification/push', [
                            'json' => [
                                'tenant_id' => session()->get('idTenant'),
                                'staff_id' => $v,
                                'title' => $dataNotification['notification_title'],
                                'message' => $dataNotification['notification_message'],
                                'detail_id' => $idNotificationDetail,
                                'avatar' => $dataNotification['notification_avatar']
                            ]
                        ]);
                    }
                }
            }
        } catch (\Exception $exception) {
            return '';
        }
    }

    /**
     * Thêm dự án
     * @param $data
     * @return array|mixed
     * @throws ManageWorkRepoException
     */
    public function addProject($data)
    {
        try {
            $mManageProject = new ManageProjectTable();

            $data = [
                'manage_project_name' => $data['manage_project_name'],
                'is_active' => 1,
                'created_at' => Carbon::now(),
                'created_by' => Auth::id(),
                'updated_at' => Carbon::now(),
                'updated_by' => Auth::id()
            ];
            $idProject = $mManageProject->createdProject($data);

            $detailProject = $mManageProject->getDetailProject($idProject);

            return $detailProject;
        } catch (\Exception|QueryException $exception) {
            throw new ManageWorkRepoException(ManageWorkRepoException::GET_MANAGE_ADD_PROJECT_FAILED);
        }
    }

    /**
     * Thêm loại công việc
     * @param $data
     * @return mixed|void
     */
    public function addTypeWork($data)
    {
        try {
            $mManageTypeWork = new ManageTypeWorkTable();

            $data = [
                'manage_type_work_name' => $data['manage_type_work_name'],
                'manage_type_work_icon' => isset($data['manage_type_work_icon']) ? $data['manage_type_work_icon'] : null,
                'is_active' => 1,
                'created_at' => Carbon::now(),
                'created_by' => Auth::id(),
                'updated_at' => Carbon::now(),
                'updated_by' => Auth::id()
            ];
            $mManageTypeWork->createdTypeWork($data);

            return true;
        } catch (\Exception|QueryException $exception) {
            throw new ManageWorkRepoException(ManageWorkRepoException::GET_MANAGE_ADD_TYPE_WORK_FAILED);
        }
    }

    /**
     * Danh sách tags
     * @param $data
     * @return mixed|void
     */
    public function listTags($data)
    {
        try {
            $mManageTags = new ManageTagsTable();

            $list = $mManageTags->getListTags($data);
            return $list;
        } catch (\Exception|QueryException $exception) {
            throw new ManageWorkRepoException(ManageWorkRepoException::GET_MANAGE_LIST_TAGS_FAILED);
        }
    }

    /**
     * Danh sách nhân viên
     * @param $data
     * @return mixed|void
     */
    public function listStaff($data)
    {
        try {

            $mStaff = new StaffTable();

//            Nếu có id dự án thì sẽ lấy danh sách nhân viên có trong dự án
            if (isset($data['manage_project_id']) || isset($data['project_id'])) {
                $list = $mStaff->getListStaffNew($data);
            } else {
                $list = $mStaff->getListStaff($data);
            }


            return $list;
        } catch (\Exception|QueryException $exception) {
            throw new ManageWorkRepoException(ManageWorkRepoException::GET_MANAGE_LIST_STAFF_FAILED);
        }
    }

    /**
     * Upload ảnh
     * @param $input
     * @return array|mixed
     * @throws ManageWorkRepoException
     */
    public function uploadFile($input)
    {
        try {

            $imageFile = getimagesize($input['link']);

            //            if($imageFile == false) {
            //                throw new ManageWorkRepoException(ManageWorkRepoException::FILE_NOT_TYPE);
            //            }

//            $fileSize = number_format(filesize($input['link']) / 1048576, 2); //MB
//
//            if ($fileSize > 20) {
//                throw new ManageWorkRepoException(ManageWorkRepoException::MAX_FILE_SIZE);
//            }
//
//            $file = $input['link'];
//            $ext = $file->getClientOriginalExtension();
//            $mineType = $file->getMimeType();
//            $config = config('filesystems.disks.minio');
//            $mConfig = app()->get(FileMinioConfigTable::class);
//
//            $detailConfig = $mConfig->getLastConfig();
//
//            $s3 = new S3Client([
//                'credentials' => [
//                    'key'    => $detailConfig["minio_root_user"],
//                    'secret' => $detailConfig["minio_root_password"]
//                ],
//                'region'      => $detailConfig["minio_region"],
//                'version'     => "latest",
//                'use_path_style_endpoint' => true,
//                'endpoint'    => $detailConfig["minio_endpoint"],
//                'bucket_endpoint' => false
//            ]);
//
//            $fileName = $file->getClientOriginalName();
//
//            $folder = env('FOLDER');
//
//            $s3->putObject([
//                'Bucket' => $folder,
//                'SourceFile' => $file->getRealPath(),
//                'Key' => $fileName,
//                'ContentType' => $mineType
//            ]);
//
//            $fullPath = $folder . "/" . $fileName;
//            $link = $detailConfig["minio_endpoint"] . '/' . $fullPath;

            $link = UploadImage::uploadImageS3($input['link'], '_manage_work.');

            return ['path' => $link];
        } catch (\Exception|QueryException $exception) {
            throw new ManageWorkRepoException(ManageWorkRepoException::GET_UPLOAD_FILE_FAILED);
        }
    }

    /**
     * Danh sách hồ sơ
     * @param $data
     * @return mixed|void
     */
    public function listDocument($data)
    {
        try {

            $mManageDocument = new ManageDocumentTable();

            $list = $mManageDocument->getListDocument($data['manage_work_id']);

            return $list;
        } catch (\Exception|QueryException $exception) {
            throw new ManageWorkRepoException(ManageWorkRepoException::GET_LIST_DOCUMENT_FAILED);
        }
    }

    /**
     * Cập nhật file hồ sơ
     * @param $data
     * @return mixed|void
     */
    public function uploadFileDocument($data)
    {
        try {

            $mManageDocumentFile = new ManageDocumentFileTable();
            $mManageWork = app()->get(ManageWorkTable::class);
            $iWork = $data['manage_work_id'];

            $mManageWork->editWork([
                'updated_at' => Carbon::now(),
                'updated_by' => Auth::id()
            ], $iWork);
            //            Xoá tất cả file theo hồ sơ

            //            $mManageDocumentFile->deleteFileByDocument($iWork);
            $cutLink = explode('/', $data['path']);
            $nameFile = count($cutLink) != 0 ? end($cutLink) : '';

            $dataFile = [
                'manage_work_id' => $iWork,
                'file_name' => $nameFile,
                'path' => $data['path'],
                'created_at' => Carbon::now(),
                'created_by' => Auth::id(),
                'updated_at' => Carbon::now(),
                'updated_by' => Auth::id()
            ];

            $idFile = $mManageDocumentFile->createFileByDocument($dataFile);

            $dataNoti = [
                'key' => 'file_new',
                'object_id' => $idFile,
            ];
            $this->staffNotification($dataNoti);

            $detailFile = $mManageDocumentFile->getDetailFile($idFile);

            $dataHistory = [
                'manage_work_id' => $iWork,
                'staff_id' => Auth::id(),
                'message' => __(' đã thêm tài liệu ') . $nameFile,
                'created_at' => Carbon::now(),
                'created_by' => Auth::id(),
                'updated_at' => Carbon::now(),
                'updated_by' => Auth::id()
            ];

            $mManageHistory = new ManageHistoryTable();
            $mManageHistory->createdHistory($dataHistory);

            return $detailFile;
        } catch (\Exception|QueryException $exception) {
            throw new ManageWorkRepoException(ManageWorkRepoException::GET_UPLOAD_FILE_DOCUMENT_FAILED);
        }
    }

    /**
     * Cập nhật tag cho công việc
     * @param $data
     * @return bool|mixed
     * @throws ManageWorkRepoException
     */
    public function updateWorkTag($data)
    {
        try {

            $mManageWorkTag = new ManageWorkTagTable();

            //            Xoá tag trước khi insert mới

            $mManageWorkTag->deleteWorkTag($data['manage_work_id']);

            $dataTag = [];
            if (isset($data['list_tag'])) {
                foreach ($data['list_tag'] as $item) {
                    $dataTag[] = [
                        'manage_work_id' => $data['manage_work_id'],
                        'manage_tag_id' => $item['manage_tag_id'],
                        'created_at' => Carbon::now(),
                        'created_by' => Auth::id(),
                        'updated_at' => Carbon::now(),
                        'updated_by' => Auth::id()
                    ];
                }

                if (count($dataTag) != 0) {
                    $mManageWorkTag->createdWorkTag($dataTag);
                }
            }

            $dataHistory = [
                'manage_work_id' => $data['manage_work_id'],
                'staff_id' => Auth::id(),
                'message' => __(' đã cập nhật tag công việc'),
                'created_at' => Carbon::now(),
                'created_by' => Auth::id(),
                'updated_at' => Carbon::now(),
                'updated_by' => Auth::id()
            ];

            $mManageHistory = new ManageHistoryTable();
            $mManageHistory->createdHistory($dataHistory);

            return true;
        } catch (\Exception|QueryException $exception) {
            throw new ManageWorkRepoException(ManageWorkRepoException::GET_UPDATE_WORK_TAG_FAILED);
        }
    }

    /**
     * Danh sách tác vụ con
     * @param $data
     * @return mixed|void
     */
    public function listChildWork($data)
    {
        try {

            $mManageWork = new ManageWorkTable();
            $mManageWorkTag = new ManageWorkTagTable();
            $mManageWorkSupport = new ManageWorkSupportTable();
            $mManageComment = new ManageCommentTable();
            $mManageStatus = new ManageStatusTable();

            $staffs = new StaffTable();

            //            lấy danh sách công việc trễ hạn

            $list = $mManageWork->getChildWork($data);

            foreach ($list as $key => $item) {
                $list[$key]['total_message'] = $mManageComment->getTotalCommentByWork($item['manage_work_id']);
                $list[$key]['tags'] = $mManageWorkTag->getListTagByWork($item['manage_work_id']);
                $list[$key]['list_staff'] = $mManageWorkSupport->getListStaffByWork($item['manage_work_id']);
                $list[$key]['list_status'] = $this->getListStatus($item);
            }

            return $list;
        } catch (\Exception|QueryException $exception) {
            throw new ManageWorkRepoException(ManageWorkRepoException::GET_LIST_CHILD_WORK_FAILED);
        }
    }

    /**
     * Chỉnh sửa lặp lại công việc
     * @param $data
     * @return mixed
     * @throws ManageWorkRepoException
     */
    public function editRepeatWork($data)
    {
        try {
            if (!isset($data['repeat_time'])) {
                throw new ManageWorkRepoException(ManageWorkRepoException::GET_REPEAT_TIME_FAILED);
            }
            $mManageWork = new ManageWorkTable();
            $mManageRepeatTime = new ManageRepeatTimeTable();

            $idWork = $data['manage_work_id'];

            $updateWork = [
                'repeat_type' => $data['repeat_type'],
                'repeat_end' => $data['repeat_end'],
                'repeat_end_full_time' => $data['repeat_end_full_time'],
                'repeat_time' => $data['repeat_time'],
                'updated_at' => Carbon::now(),
                'updated_by' => Auth::id()
            ];

            $mManageWork->editWork($updateWork, $idWork);

            //            Cập nhật ngày
            $mManageRepeatTime->deleteRepeatTime($idWork);
            if (isset($data['list_date'])) {
                $dataDate = [];
                foreach ($data['list_date'] as $item) {
                    $dataDate[] = [
                        'manage_work_id' => $idWork,
                        'time' => $item['date'],
                        'created_at' => Carbon::now(),
                        'created_by' => Auth::id(),
                        'updated_at' => Carbon::now(),
                        'updated_by' => Auth::id()
                    ];
                }

                if (count($dataDate) != 0) {
                    $mManageRepeatTime->createRepeatTime($dataDate);
                }
            }

            $dataHistory = [
                'manage_work_id' => $data['manage_work_id'],
                'staff_id' => Auth::id(),
                'message' => __(' đã cập nhật thời gian lặp lại công việc'),
                'created_at' => Carbon::now(),
                'created_by' => Auth::id(),
                'updated_at' => Carbon::now(),
                'updated_by' => Auth::id()
            ];

            $mManageHistory = new ManageHistoryTable();
            $mManageHistory->createdHistory($dataHistory);

            return true;
        } catch (\Exception|QueryException $exception) {
            throw new ManageWorkRepoException(ManageWorkRepoException::GET_EDIT_REPEAT_WORK_FAILED);
        }
    }

    /**
     * Danh sách dự án
     * @return mixed|void
     */
    public function listProject($data)
    {
        try {

            $mManageProject = new ManageProjectTable();
            Log::info($data);
            if (isset($data['show_program']) && in_array($data['show_program'], ['show_all', 'not_show_all'])) {
                $list = $mManageProject->getListAllNew($data);
            } else {
                $list = $mManageProject->getListAll($data);
            }

            return $list;
        } catch (\Exception|QueryException $exception) {
            throw new ManageWorkRepoException(ManageWorkRepoException::GET_LIST_PROJECT_FAILED);
        }
    }

    /**
     * Công việc của tôi
     * @param $data
     * @return mixed|void
     */
    public function myWorkSearchOverdue($data)
    {
        try {

            $mManageWork = new ManageWorkTable();

            $mManageWorkTag = new ManageWorkTagTable();
            $mManageWorkSupport = new ManageWorkSupportTable();
            $mManageComment = new ManageCommentTable();

            $staffs = new StaffTable();

            $data['staff_id'] = Auth::id();
            $data['status_overdue'] = [1, 2, 5];
            $list = $mManageWork->getMyWorkOrverDueDate($data);

            return $list;
        } catch (\Exception|QueryException $exception) {
            throw new ManageWorkRepoException(ManageWorkRepoException::GET_MY_WORK_FAILED);
        }
    }

    /**
     * Công việc của tôi
     * @param $data
     * @return mixed|void
     */
    public function myWorkSearch($data)
    {
        try {

            $mManageWork = new ManageWorkTable();

            $mManageWorkTag = new ManageWorkTagTable();
            $mManageWorkSupport = new ManageWorkSupportTable();
            $mManageComment = new ManageCommentTable();
            $mManageStatus = new ManageStatusTable();

            $staffs = new StaffTable();

            $data['staff_id'] = Auth::id();
            $data['status_overdue'] = [1, 2, 5];
            $list = [];
            //            Hiển thị danh sách dạng lịch
            // if (!isset($data['end_date']) && !isset($data['start_date']) && !isset($data['manage_status_id']) && !isset($data['manage_project_id']) && !isset($data['manage_type_work_id']) && !isset($data['department_id']) && !isset($data['manage_work_title']) && !isset($data['date_overdue'])) {
            //     $data['start_date'] = Carbon::now()->format('Y/m/d') . '-' . Carbon::now()->format('Y/m/d');
            //     $data['end_date'] = Carbon::now()->format('Y/m/d') . '-' . Carbon::now()->format('Y/m/d');
            // }

            $listWork = $mManageWork->getMyWorkByDateSearch($data);


            $listWorkSearch = [];

            if (count($listWork) != 0) {
                //                    Thêm các trường còn thiếu
                foreach ($listWork as $key => $item) {
                    $listWork[$key]['total_message'] = $mManageComment->getTotalCommentByWork($item['manage_work_id']);
                    $listWork[$key]['tags'] = $mManageWorkTag->getListTagByWork($item['manage_work_id']);
                    $listWork[$key]['list_staff'] = $mManageWorkSupport->getListStaffByWork($item['manage_work_id']);
                    $listWork[$key]['list_status'] = $this->getListStatus($item);

                    $listWork[$key]['total_child_work'] = $mManageWork->getTotalWorkChild($item['manage_work_id']);
                }
            }

            return $this->toPagingData($listWork);
        } catch (\Exception|QueryException $exception) {
            throw new ManageWorkRepoException(ManageWorkRepoException::GET_MY_WORK_FAILED, $exception->getMessage());
        }
    }

    /**
     * Công viêc của tôi tab của tôi
     * @return mixed|void
     */
    public function myWork($data)
    {
        try {

            $startOfMonth = Carbon::now()->startOfMonth()->format('Y-m-d 00:00:00');
            $endOfMonth = Carbon::now()->endOfMonth()->format('Y-m-d 23:59:59');

            $start = null;
            $end = null;

            $n = 0;
            $check = 0; // Kiểm tra cuối tháng
            $mManageWork = new ManageWorkTable();
            $data['staff_id'] = Auth::id();
            //            $data['status_overdue'] = [1,2,5];
            $data['status_overdue'] = [6, 7];
            //            Công việc quá hạn
            if (isset($data['tab_my_work'])) {
                $list[$n] = [
                    'text_block' => __('Quá hạn'),
                    'list' => $mManageWork->getMyWorkOrverDue($data)
                ];

                $list[$n]['list'] = $this->checkList($list[$n]['list']);
                $n++;
            }

            //            Việc hôm nay

            $data['from_date'] = Carbon::now()->format('Y-m-d 00:00:00');
            $data['to_date'] = Carbon::now()->format('Y-m-d 23:59:59');

            $list[$n] = [
                'text_block' => __('Hôm nay'),
                'list' => $mManageWork->getMyWorkByDate($data)
            ];

            $list[$n]['list'] = $this->checkList($list[$n]['list']);

            //            Việc tuần này
            $n++;
            $data['from_date'] = Carbon::now()->startOfWeek()->format('Y-m-d 00:00:00') >= $startOfMonth ? Carbon::now()->startOfWeek()->format('Y-m-d 00:00:00') : $startOfMonth;
            $data['to_date'] = Carbon::now()->endOfWeek()->format('Y-m-d 23:59:59') <= $endOfMonth ? Carbon::now()->endOfWeek()->format('Y-m-d 23:59:59') : $endOfMonth;

            if (Carbon::now()->startOfWeek()->format('Y-m-d 00:00:00') >= $startOfMonth) {
                $start = $startOfMonth;
                $end = Carbon::now()->startOfWeek()->subDays(1)->format('Y-m-d 23:59:59');
            }

            if (Carbon::now()->endOfWeek()->format('Y-m-d 23:59:59') > $endOfMonth) {
                $check = 1;
            }

            $list[$n] = [
                'text_block' => __('Tuần này'),
                'list' => $mManageWork->getMyWorkByDate($data)
            ];


            $list[$n]['list'] = $this->checkList($list[$n]['list']);

            if ($check == 0) {
                //Tuần sau
                $n++;

                $data['from_date'] = Carbon::now()->addWeeks(1)->startOfWeek()->format('Y-m-d 00:00:00');
                $data['to_date'] = Carbon::now()->addWeeks(1)->endOfWeek()->format('Y-m-d 23:59:59') <= $endOfMonth ? Carbon::now()->addWeeks(1)->endOfWeek()->format('Y-m-d 23:59:59') : $endOfMonth;

                if (Carbon::now()->addWeeks(1)->endOfWeek()->format('Y-m-d 23:59:59') > $endOfMonth) {
                    $check = 1;
                } else {
                    $start = Carbon::now()->addWeeks(1)->addDays(1)->startOfWeek()->format('Y-m-d 00:00:00');
                    $end = $endOfMonth;
                }

                $list[$n] = [
                    'text_block' => __('Tuần sau'),
                    'list' => $mManageWork->getMyWorkByDate($data)
                ];

                $list[$n]['list'] = $this->checkList($list[$n]['list']);
            }

            //            if ($check == 0){
            //                //Tuần kế tiếp
            //                $n++;
            //
            //                $data['from_date'] = Carbon::now()->addWeeks(2)->startOfWeek()->format('Y-m-d 00:00:00');
            //                $data['to_date'] = Carbon::now()->addWeeks(2)->endOfWeek()->format('Y-m-d 23:59:59') <= $endOfMonth ? Carbon::now()->addWeeks(2)->endOfWeek()->format('Y-m-d 23:59:59') : $endOfMonth;
            //
            //                if (Carbon::now()->addWeeks(1)->endOfWeek()->format('Y-m-d 23:59:59') > $endOfMonth){
            //                    $check = 1;
            //                } else {
            ////                    $start = Carbon::now()->addWeeks(2)->addDays(1)->startOfWeek()->format('Y-m-d 00:00:00');
            //                    $start = Carbon::now()->addWeeks(2)->addDays(1)->endOfWeek()->format('Y-m-d 00:00:00');
            //                    $end = $endOfMonth;
            //                }
            //
            //                $list[$n] = [
            //                    'text_block' => __('Tuần kế tiếp'),
            //                    'list' =>  $mManageWork->getMyWorkByDate($data)
            //                ];
            //
            //                $list[$n]['list'] = $this->checkList($list[$n]['list']);
            //            }

            //Khác
            //            $n++;
            //            $data['from_date'] = $start;
            //            $data['to_date'] = $end;
            //
            //            $list[$n] = [
            //                'text_block' => __('Khác'),
            //                'list' =>  $mManageWork->getMyWorkByDate($data)
            //            ];
            //
            //            $list[$n]['list'] = $this->checkList($list[$n]['list']);

            return $list;
        } catch (\Exception|QueryException $exception) {
            throw new ManageWorkRepoException(ManageWorkRepoException::GET_MY_WORK_FAILED);
        }
    }

    /**
     * Công việc của tôi chỉnh sửa danh sách công việc
     */
    public function checkList($list)
    {
        try {
            $mManageWorkTag = new ManageWorkTagTable();
            $mManageWorkSupport = new ManageWorkSupportTable();
            $mManageComment = new ManageCommentTable();
            $mManageStatus = new ManageStatusTable();
            $mManageWork = app()->get(ManageWorkTable::class);

            foreach ($list as $key => $item) {
                $list[$key]['total_message'] = $mManageComment->getTotalCommentByWork($item['manage_work_id']);
                $list[$key]['tags'] = $mManageWorkTag->getListTagByWork($item['manage_work_id']);
                $list[$key]['list_staff'] = $mManageWorkSupport->getListStaffByWork($item['manage_work_id']);
                $list[$key]['list_status'] = $this->getListStatus($item);
            }

            return $list;
        } catch (\Exception|QueryException $exception) {
            throw new ManageWorkRepoException(ManageWorkRepoException::GET_MY_WORK_FAILED);
        }
    }

    public function myAssignWork()
    {
        try {

            $mManageWork = new ManageWorkTable();
            $mManageWorkTag = new ManageWorkTagTable();
            $mManageWorkSupport = new ManageWorkSupportTable();
            $mManageComment = new ManageCommentTable();
            $mManageStatus = new ManageStatusTable();


            $data['assignor_id'] = Auth::id();
            $listAll = $mManageWork->getListWorkAllStatus($data);
            $listByGroup = [];
            if (count($listAll) != 0) {

                foreach ($listAll as $key => $item) {
                    $listAll[$key]['total_message'] = $mManageComment->getTotalCommentByWork($item['manage_work_id']);
                    $listAll[$key]['tags'] = $mManageWorkTag->getListTagByWork($item['manage_work_id']);
                    $listAll[$key]['list_staff'] = $mManageWorkSupport->getListStaffByWork($item['manage_work_id']);
                    $listAll[$key]['list_status'] = $this->getListStatus($item);
                }

                $listGroup = collect($listAll)->groupBy('manage_status_name');
                foreach ($listGroup as $key => $item) {
                    $listByGroup[$key]['text_block'] = $key;
                    $listByGroup[$key]['list'] = $item;
                }
                $listByGroup = array_values($listByGroup);
            }

            return $listByGroup;
        } catch (\Exception|QueryException $exception) {
            throw new ManageWorkRepoException(ManageWorkRepoException::GET_MY_ASSIGN_WORK_FAILED);
        }
    }

    /**
     * Danh sách nhắc nhở của tôi
     */
    public function myRemindWork($data)
    {
        try {

            $mManageRemin = new ManageRemindTable();

            $list = $mManageRemin->getListMyRemind(Auth::id());

            return $list;
        } catch (\Exception|QueryException $exception) {
            throw new ManageWorkRepoException(ManageWorkRepoException::GET_MY_REMIND_WORK_FAILED);
        }
    }

    /**
     * Xoá nhắc nhớ
     * @param $data
     * @return array|mixed
     * @throws ManageWorkRepoException
     */
    public function deleteRemind($data)
    {
        try {
            $mManageRemind = new ManageRemindTable();
            foreach ($data['manage_remind_id'] as $item) {
                $detail = $mManageRemind->getDetailRemindNoti($item);

                $dataHistory = [
                    'manage_work_id' => isset($data['manage_work_id']) ? $data['manage_work_id'] : '',
                    'staff_id' => Auth::id(),
                    'message' => __(' đã xoá nhắc nhở ') . $detail['description'],
                    'created_at' => Carbon::now(),
                    'created_by' => Auth::id(),
                    'updated_at' => Carbon::now(),
                    'updated_by' => Auth::id()
                ];

                $mManageHistory = new ManageHistoryTable();
                $mManageHistory->createdHistory($dataHistory);
            }

            return $mManageRemind->deleteRemindArray($data['manage_remind_id']);
        } catch (\Exception|QueryException $exception) {
            throw new ManageWorkRepoException(ManageWorkRepoException::GET_DELETE_REMIND_FAILED);
        }
    }

    /**
     * Danh sách trạng thái
     * @return mixed|void
     */
    public function listStatus()
    {
        try {

            $mMaganeStatus = new ManageStatusTable();

            $list = $mMaganeStatus->getListStatus();

            return $list;
        } catch (\Exception|QueryException $exception) {
            throw new ManageWorkRepoException(ManageWorkRepoException::GET_LIST_STATUS_FAILED);
        }
    }

    /**
     * Xoá bình luận
     * @param $data
     * @return mixed
     * @throws ManageWorkRepoException
     */
    public function deleteComment($data)
    {
        try {

            $mMaganeComment = new ManageCommentTable();

            return $mMaganeComment->deleteComment($data['manage_comment_id']);
        } catch (\Exception|QueryException $exception) {
            throw new ManageWorkRepoException(ManageWorkRepoException::GET_DELETE_COMMENT_FAILED);
        }
    }

    /**
     * Cập nhật nhân viên liên quan
     * @param $data
     * @return mixed|void
     */
    public function updateStaffSupport($data)
    {
        try {

            $mManageWorkSupport = new ManageWorkSupportTable();

            $idWork = $data['manage_work_id'];
            $mManageWorkSupport->deleteSupportByWork($idWork);

            if (isset($data['list_staff']) && count($data['list_staff']) != 0) {
                $dataStaff = [];
                foreach ($data['list_staff'] as $item) {
                    $dataStaff[] = [
                        'manage_work_id' => $idWork,
                        'staff_id' => $item['staff_id'],
                        'created_at' => Carbon::now(),
                        'created_by' => Auth::id(),
                        'updated_at' => Carbon::now(),
                        'updated_by' => Auth::id()
                    ];
                }

                if (count($dataStaff) != 0) {
                    $mManageWorkSupport->addStaffSupport($dataStaff);
                }
            }

            return true;
        } catch (\Exception|QueryException $exception) {
            throw new ManageWorkRepoException(ManageWorkRepoException::GET_UPDATE_STAFF_SUPPORT_FAILED);
        }
    }

    /**
     * Xoá công việc
     * @param $data
     * @return bool|mixed
     * @throws ManageWorkRepoException
     */
    public function deleteWork($data)
    {
        try {

            $idWork = $data['manage_work_id'];

            $mManageWork = new ManageWorkTable();
            $mManageRemind = new ManageRemindTable();
            $mManageRepeatTime = new ManageRepeatTimeTable();
            $mManageComment = new ManageCommentTable();
            $mManageSupport = new ManageWorkSupportTable();
            $mManageWorkTag = new ManageWorkTagTable();

            $detailWork = $this->workDetail(['manage_work_id' => $idWork]);

            if (in_array(Auth::id(), [$detailWork['processor_id'], $detailWork['assignor_id']])) {
                $mManageWork->deleteWork($idWork);
                $mManageRemind->deleteRemindByWork($idWork);
                $mManageRepeatTime->deleteRepeatTime($idWork);
                $mManageComment->deleteCommentByWork($idWork);
                $mManageSupport->deleteSupportByWork($idWork);
                $mManageWorkTag->deleteWorkTag($idWork);

                //                Xóa task con
                $mManageRemind->deleteRemindByParentTask($idWork);
                $mManageWork->deleteWorkChild($idWork);

            } else {
                throw new ManageWorkRepoException(ManageWorkRepoException::GET_DELETE_WORK_FAILED);
            }


            //            $mManageWork->editParentWork(['parent_id' => null],$idWork);

            return true;
        } catch (\Exception|QueryException $exception) {
            throw new ManageWorkRepoException(ManageWorkRepoException::GET_DELETE_WORK_FAILED);
        }
    }

    /**
     * Danh sách loại công việc
     * @return mixed|void
     */
    public function listTypeWork()
    {
        try {

            $mManageTypeWork = new ManageTypeWorkTable();

            $list = $mManageTypeWork->getAll();

            return $list;
        } catch (\Exception|QueryException $exception) {
            throw new ManageWorkRepoException(ManageWorkRepoException::GET_LIST_TYPE_WORK_FAILED);
        }
    }

    /**
     * Cập nhật nhanh công việc
     * @param $data
     * @return mixed|void
     */
    public function quickUpdateWork($data)
    {
        try {

            $mManageWork = new ManageWorkTable();
            $idWork = $data['manage_work_id'];
            unset($data['manage_work_id']);
            unset($data['brand_code']);

            $dataWork = [];
            if (isset($data['progress'])) {
                $dataWork['progress'] = $data['progress'];
                $detailWork = $this->workDetail(['manage_work_id' => $idWork]);
                if ($detailWork['is_parent'] == 1) {
                    throw new ManageWorkRepoException(ManageWorkRepoException::PARENT_TASK_CANT_UPDATE);
                }
            }

            if (isset($data['manage_status_id'])) {
                $dataWork['manage_status_id'] = $data['manage_status_id'];
                if ($data['manage_status_id'] == 6) {
                    $dataWork['date_finish'] = Carbon::now();
                    $detailWork = $this->workDetail(['manage_work_id' => $idWork]);
                    if ($detailWork['is_parent'] == 0) {
                        $dataWork['progress'] = 100;
                    }
                }
            }

            $dataWork['updated_at'] = Carbon::now();
            $dataWork['updated_by'] = Auth::id();

            $mManageWork->editWork($dataWork, $idWork);

            $message = isset($data['progress']) ? __(' tiến độ ') : (isset($data['manage_status_id']) ? __(' trạng thái ') : '');

            $dataHistory = [
                'manage_work_id' => $idWork,
                'staff_id' => Auth::id(),
                'message' => __(' đã cập nhật nhanh') . $message . __(' công việc'),
                'created_at' => Carbon::now(),
                'created_by' => Auth::id(),
                'updated_at' => Carbon::now(),
                'updated_by' => Auth::id()
            ];

            $mManageHistory = new ManageHistoryTable();
            $mManageHistory->createdHistory($dataHistory);

            //            Cập nhật tiến độ task cha
            $detailNew = $mManageWork->detailWork($idWork);

            if ($detailNew != null && $detailNew['parent_id'] != null) {
                $listWorkChild = $mManageWork->getListChildWorkByParent($detailNew['parent_id']);
                if ($listWorkChild != null && $listWorkChild['total_child'] != 0) {
                    $detailParentOLd = $mManageWork->detailWork($detailNew['parent_id']);
                    $avg = round($listWorkChild['total_process'] / $listWorkChild['total_child']);
                    $mManageWork->editWork(['progress' => $avg], $detailNew['parent_id']);

                    //                    Ghi log cập nhật tiến độ task cha
                    if ($detailParentOLd['progress'] != $avg) {
                        $message = __(' đã cập nhật tiến độ công việc ') . $detailParentOLd['manage_work_title'] . __(' từ ') . $detailParentOLd['progress'] . '%' . __(' sang ') . $avg . '%';
                        $this->createHistory($detailNew['parent_id'], $message);
                    }
                }
            }

            if (!isset($data['progress'])) {
                $dataNoti = [
                    'key' => 'work_update_status',
                    'object_id' => $idWork,
                ];
                $this->staffNotification($dataNoti);

                if ($data['manage_status_id'] == 3) {
                    $dataNoti = [
                        'key' => 'work_finish',
                        'object_id' => $idWork,
                    ];
                    $this->staffNotification($dataNoti);
                }
            }

            $detailWork = $this->workDetail(['manage_work_id' => $idWork]);

            return $detailWork;
        } catch (\Exception|QueryException $exception) {
            throw new ManageWorkRepoException(ManageWorkRepoException::GET_QUICK_UPDATE_WORK_FAILED);
        }
    }

    /**
     * Danh sách khách hàng
     * @return mixed|void
     */
    public function listCustomer($data)
    {
        try {
            $customer = new CustomersTable();
            $customerLead = new CustomerLeadTable();
            $mDeal = new CustomerDealTable();

            $list = [];
            if (isset($data['manage_work_customer_type'])) {
                if ($data['manage_work_customer_type'] == 'customer') {
                    $list = $customer->getListCustomer($data);
                } else if ($data['manage_work_customer_type'] == 'lead') {
                    $list = $customerLead->getAllCustomerLead($data);
                } else if ($data['manage_work_customer_type'] == 'deal') {
                    $list = $mDeal->getAll($data);
                }
            }

            return $list;
        } catch (\Exception|QueryException $exception) {
            throw new ManageWorkRepoException(ManageWorkRepoException::GET_LIST_CUSTOMER_FAILED);
        }
    }

    /**
     * Tạo tag mới
     * @param $data
     * @return mixed|void
     */
    public function addTag($data)
    {
        try {
            $mManageTag = new ManageTagsTable();

            $value['manage_tag_name'] = $data['manage_tag_name'];
            $idTag = $mManageTag->addTag($value);

            return $mManageTag->getTags($idTag);
        } catch (\Exception|QueryException $exception) {
            throw new ManageWorkRepoException(ManageWorkRepoException::GET_ADD_TAG_FAILED);
        }
    }

    /**
     * Xoá hình ảnh
     * @param $data
     * @return mixed|void
     */
    public function deleteDocumentFile($data)
    {
        try {
            $mManageDocumentFile = new ManageDocumentFileTable();

            $detail = $mManageDocumentFile->getDetailFileNoti($data['manage_document_file_id']);

            $dataHistory = [
                'manage_work_id' => $detail['manage_work_id'],
                'staff_id' => Auth::id(),
                'message' => __(' đã xoá thành công hồ sơ ') . $detail['file_name'],
                'created_at' => Carbon::now(),
                'created_by' => Auth::id(),
                'updated_at' => Carbon::now(),
                'updated_by' => Auth::id()
            ];

            $mManageDocumentFile->deleteFileByWork($data['manage_document_file_id']);

            $mManageHistory = new ManageHistoryTable();
            $mManageHistory->createdHistory($dataHistory);
        } catch (\Exception|QueryException $exception) {
            throw new ManageWorkRepoException(ManageWorkRepoException::DELETE_IMAGE_FAILED);
        }
    }

    /**
     * Danh sách công việc cần duyệt
     * @param $data
     * @return mixed|void
     */
    public function getListWorkApprove($data)
    {
        try {
            $mManageWork = new ManageWorkTable();
            $mManageStatus = new ManageStatusTable();
            $list = $mManageWork->getListWorkApprove($data);

            foreach ($list as $key => $item) {
                $list[$key]['list_status'] = $this->getListStatus($item);
            }


            return $list;
        } catch (\Exception|QueryException $exception) {
            throw new ManageWorkRepoException(ManageWorkRepoException::GET_LIST_WORK_APPROVE_FAILED);
        }
    }

    public function codeWork($data = [])
    {
        if (isset($data['manage_project_id']) && $data['manage_project_id'] != null) {
//            $getProjectPrefix = new ManageWorkTable();
            $getProjectPrefix = new ManageProjectTable();
            $mWork = new ManageWorkTable();
            $projectPrefix = $getProjectPrefix->getDetailProject($data['manage_project_id']);
            $totalWork = $mWork->getTotalWork($data['manage_project_id']);

            return $projectPrefix['prefix_code'] . "_" . (($totalWork != null ? $totalWork['total'] : 0) + 1);
        } else {

            $mManageWork = new ManageWorkTable();
            $codeWork = 'CV_' . Carbon::now()->format('Ymd') . '_';
            $workCodeDetail = $mManageWork->getCodeWork($codeWork);

            if ($workCodeDetail == null) {
                return $codeWork . '001';
            } else {
                $arr = explode($codeWork, $workCodeDetail);
                $value = strval(intval($arr[1]) + 1);
                $zero_str = "";
                if (strlen($value) < 7) {
                    for ($i = 0; $i < (3 - strlen($value)); $i++) {
                        $zero_str .= "0";
                    }
                }
                return $codeWork . $zero_str . $value;
            }
        }
    }

    /**
     * Lấy danh sách trạng thái
     */
    public function getListStatus($data)
    {
        $mManageStatusConfigMap = new ManageStatusConfigMapTable();

        $listStatusConfig = $mManageStatusConfigMap->getListStatusByConfig($data['manage_status_id']);

        $mManageStatus = new ManageStatusTable();
        $listStatus = [];
        if (count($listStatusConfig) != 0) {
            $listStatusConfig = collect($listStatusConfig)->pluck('manage_status_id')->toArray();
            $listStatusConfig = array_merge($listStatusConfig, [$data['manage_status_id']]);
            if (Auth::id() != $data['approve_id'] && $data['is_approve_id'] == 1) {
                if (($key = array_search(6, $listStatusConfig)) !== false) {
                    unset($listStatusConfig[$key]);
                }
            }
            $listStatus = $mManageStatus->getListStatus($listStatusConfig);
        }

        return $listStatus;
    }

    /**
     * Loại khách hàng
     * @param $data
     * @return mixed|void
     */
    public function typeCustomer()
    {
        try {
            $data = [
                [
                    'manage_work_customer_type' => 'customer',
                    'manage_work_customer_type_text' => __('Khách hàng')
                ],
                [
                    'manage_work_customer_type' => 'lead',
                    'manage_work_customer_type_text' => __('Khách hàng tiềm năng')
                ],
                [
                    'manage_work_customer_type' => 'deal',
                    'manage_work_customer_type_text' => __('Danh sách deal')
                ]
            ];
            return $data;
        } catch (\Exception $e) {
        }
    }

    /**
     * Lấy danh sách phòng ban
     *
     * @return mixed
     * @throws ManageWorkRepoException
     */
    public function getDepartment()
    {
        try {
            $mDepartment = app()->get(DepartmentTable::class);

            //Lấy option phòng ban
            return $mDepartment->getOptionDepartment();
        } catch (\Exception $e) {
            throw new ManageWorkRepoException(ManageWorkRepoException::GET_DEPARTMENT_FAILED);
        }
    }

    /**
     * Thêm vị trí công việc
     *
     * @param $input
     * @return mixed|void
     * @throws ManageWorkRepoException
     */
    public function createLocation($input)
    {
        try {
            $mLocation = app()->get(ManageWorkLocationTable::class);

            //Thêm vị trí công việc
            $locationId = $mLocation->add([
                'manage_work_id' => $input['manage_work_id'],
                'staff_id' => Auth()->id(),
                'lat' => $input['lat'],
                'lng' => $input['lng'],
                'description' => $input['description']
            ]);

            return $mLocation->getInfo($locationId);
        } catch (\Exception $e) {
            throw new ManageWorkRepoException(ManageWorkRepoException::CREATE_LOCATION_FAILED);
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
            $mLocation = app()->get(ManageWorkLocationTable::class);

            //Lấy vị trí công việc
            $data = $mLocation->getLocation($input['manage_work_id']);

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
            throw new ManageWorkRepoException(ManageWorkRepoException::GET_LOCATION_FAILED);
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
            $mLocation = app()->get(ManageWorkLocationTable::class);

            //Xoá vị trí làm việc
            $mLocation->edit([
                'is_deleted' => 1
            ], $input['manage_work_location_id']);
        } catch (\Exception $e) {
            throw new ManageWorkRepoException(ManageWorkRepoException::REMOVE_LOCATION_FAILED);
        }
    }

    public function jobOverViewV2($input)
    {
        try {
            $input['job_overview'] = 1;
            $input['status_overdue'] = [1, 2, 5];
            $input['status_overdue_fix'] = [6, 7];

            //Lấy thông tin trạng thái công việc
            $info = $this->getTotalWork($input);

            $staffs = new StaffTable();

            //Danh sách nhân viên chưa bắt đầu công việc
            $input['list_staff_no_started_work'] = 1;

            $listStaffNoStart = $staffs->staffNoJob($input);

            $info['list_staff_no_started_work'] = [
                'total' => count($listStaffNoStart),
                'list' => $listStaffNoStart
            ];

            $input['is_overdue_hide'] = 0;
            unset($input['list_staff_no_started_work']);
            $info['list_job'] = $this->myWorkV2($input);

            return $info;
        } catch (\Exception|QueryException $exception) {
            throw new ManageWorkRepoException(ManageWorkRepoException::GET_MANAGE_WORK_OVERVIEW_FAILED);
        }
    }

    /**
     * Công viêc của tôi tab của tôi (v2)
     * @return mixed|void
     */
    public function myWorkV2($data)
    {
        try {
            $startOfMonth = Carbon::now()->startOfMonth()->format('Y-m-d 00:00:00');
            $endOfMonth = Carbon::now()->endOfMonth()->format('Y-m-d 23:59:59');

            $start = null;
            $end = null;

            $n = 0;
            $check = 0; // Kiểm tra cuối tháng
            $mManageWork = new ManageWorkTable();
            $data['staff_id'] = Auth::id();
            $data['status_overdue'] = [6, 7];

            //Việc quá hạn
            $list[$n] = [
                'text_block' => __('Quá hạn'),
                'code' => 'overdue',
                'total' => count($mManageWork->getListOverdue($data))
            ];

            //Việc hôm nay
            $n++;
            $data['from_date'] = Carbon::now()->format('Y-m-d 00:00:00');
            $data['to_date'] = Carbon::now()->format('Y-m-d 23:59:59');

            $list[$n] = [
                'text_block' => __('Hôm nay'),
                'code' => 'today',
                'total' => count($mManageWork->getMyWorkByDate($data))
            ];

            //Việc tuần này
            $n++;
            $data['from_date'] = Carbon::now()->startOfWeek()->format('Y-m-d 00:00:00') >= $startOfMonth ? Carbon::now()->startOfWeek()->format('Y-m-d 00:00:00') : $startOfMonth;
            $data['to_date'] = Carbon::now()->endOfWeek()->format('Y-m-d 23:59:59') <= $endOfMonth ? Carbon::now()->endOfWeek()->format('Y-m-d 23:59:59') : $endOfMonth;

            if (Carbon::now()->startOfWeek()->format('Y-m-d 00:00:00') >= $startOfMonth) {
                $start = $startOfMonth;
                $end = Carbon::now()->startOfWeek()->subDays(1)->format('Y-m-d 23:59:59');
            }

            if (Carbon::now()->endOfWeek()->format('Y-m-d 23:59:59') > $endOfMonth) {
                $check = 1;
            }

            $list[$n] = [
                'text_block' => __('Tuần này'),
                'code' => 'this_week',
                'total' => count($mManageWork->getMyWorkByDate($data))
            ];


            if ($check == 0) {
                //Tuần sau
                $n++;

                $data['from_date'] = Carbon::now()->addWeeks(1)->startOfWeek()->format('Y-m-d 00:00:00');
                $data['to_date'] = Carbon::now()->addWeeks(1)->endOfWeek()->format('Y-m-d 23:59:59') <= $endOfMonth ? Carbon::now()->addWeeks(1)->endOfWeek()->format('Y-m-d 23:59:59') : $endOfMonth;

                if (Carbon::now()->addWeeks(1)->endOfWeek()->format('Y-m-d 23:59:59') > $endOfMonth) {
                    $check = 1;
                } else {
                    $start = Carbon::now()->addWeeks(1)->addDays(1)->startOfWeek()->format('Y-m-d 00:00:00');
                    $end = $endOfMonth;
                }

                $list[$n] = [
                    'text_block' => __('Tuần sau'),
                    'code' => 'next_week',
                    'total' => count($mManageWork->getMyWorkByDate($data))
                ];
            }

            return $list;
        } catch (\Exception|QueryException $exception) {
            throw new ManageWorkRepoException(ManageWorkRepoException::GET_MY_WORK_FAILED);
        }
    }

    /**
     * Danh sách trạng thái (màn hình bộ lọc)
     *
     * @return mixed
     * @throws ManageWorkRepoException
     */
    public function listStatusV2()
    {
        try {

            $mMaganeStatus = new ManageStatusTable();

            $list = $mMaganeStatus->getListStatus();

            //Chèn thêm cái quá hạn vào
            $list [] = [
                "manage_status_id" => -1,
                "manage_status_name" => __("Quá hạn"),
                "manage_status_color" => "#159cd5",
                "is_cancel" => 0
            ];

            return $list;
        } catch (\Exception|QueryException $exception) {
            throw new ManageWorkRepoException(ManageWorkRepoException::GET_LIST_STATUS_FAILED);
        }
    }
}
