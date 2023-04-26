<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 26/05/2021
 * Time: 14:37
 */

namespace Modules\Ticket\Models;


use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TicketTable extends Model
{
    protected $table = "ticket";
    protected $primaryKey = "ticket_id";

    const STATUS_NEW = 1; //Mới
    const STATUS_PROCESSING = 2; // Đang xử lý
    const STATUS_COMPLETED = 3; //Hoàn thành
    const STATUS_CLOSE = 4; //Đóng
    const STATUS_CANCEL = 5; //Huỷ
    protected $fillable = ['ticket_id', 'ticket_code', 'localtion_id', 'ticket_type', 'ticket_issue_group_id',
        'ticket_issue_id', 'issule_level', 'priority', 'title', 'description', 'date_issue', 'date_estimated',
        'date_expected', 'date_finished', 'found_by', 'customer_id', 'customer_address', 'queue_process_id',
        'ticket_status_id', 'staff_notification_id', 'image', 'date_request',
        'operate_by', 'alert_time', 'platform', 'created_by', 'updated_by', 'count_opened', 'created_at', 'updated_at'];


    public function getMyTicket($data = [])
    {
        $staffId = $data['staff_id'];
        $checkStype = isset($data['type_ticket']) ? $data['type_ticket'] : '';

        $oSelect = $this
            ->select(
                $this->table . '.ticket_id',
                $this->table . '.ticket_code',
                $this->table . '.title',
                'ticket_issue.name as issue_name',
                $this->table . '.date_issue',
                $this->table . '.date_expected',
                $this->table . '.issule_level',
                $this->table . '.priority',
                $this->table . '.queue_process_id',
                'ticket_queue..queue_name',
                $this->table . '.ticket_status_id',
                'ticket_status.status_name',
                'ticket_processor.process_by'
            )
            ->join('ticket_issue', 'ticket_issue.ticket_issue_id', $this->table . '.ticket_issue_id')
            ->join('ticket_queue', 'ticket_queue.ticket_queue_id', $this->table . '.queue_process_id')
            ->join('ticket_status', 'ticket_status.ticket_status_id', $this->table . '.ticket_status_id')
            ->where('ticket_queue.is_actived', 1);

        $oSelect = $oSelect->leftJoin('ticket_processor', 'ticket_processor.ticket_id', $this->table . '.ticket_id');

        if (isset($data['type_ticket']) && $data['type_ticket'] == 'undivided') {
            $oSelect = $oSelect->where(function ($join) use ($staffId) {
                $join->whereNull('ticket_processor.process_by');
            });
        } else {
//            $oSelect = $oSelect->where(function ($sql) use ($staffId) {
//                $sql->where($this->table . '.operate_by', $staffId)
//                    ->orWhere('ticket_processor.process_by', $staffId);
//            });
        }

        if (isset($data['ticket_status_id']) && $data['ticket_status_id'] != null) {
            $oSelect = $oSelect->where($this->table . '.ticket_status_id', $data['ticket_status_id']);
        }

        if (isset($data['ticket_type'])) {
            $oSelect = $oSelect->where($this->table . '.ticket_type', $data['ticket_type']);
        }

        if (isset($data['issule_level'])) {
            $oSelect = $oSelect->where($this->table . '.issule_level', $data['issule_level']);
        }

        if (isset($data['priority'])) {
            $oSelect = $oSelect->where($this->table . '.priority', $data['priority']);
        }

        if (isset($data['created_from']) && isset($data['created_to'])) {
            $oSelect = $oSelect->whereBetween($this->table . '.created_at', [$data['created_from'], $data['created_to']]);
        }

        $oSelect = $this->permissionView($oSelect);


        if (isset($data['type_ticket'])) {
//            Ticket mới
            if ($data['type_ticket'] == 'new') {
//                $oSelect = $oSelect
//                    ->where($this->table . '.date_expected', '>=', Carbon::now());
//                Ticket quá hạn
            } else if ($data['type_ticket'] == 'expired') {
                $list_status = [1, 2, 3, 6, 7];

//                $oSelect = $oSelect
//                    ->where($this->table . '.date_expected', '<', Carbon::now())
//                    ->whereIn($this->table . '.ticket_status_id', [self::STATUS_NEW, self::STATUS_COMPLETED, self::STATUS_PROCESSING]);

                $oSelect = $oSelect
                    ->where(function ($query) {
                        $query->where(function ($query) {
                            $query->whereIn("{$this->table}.ticket_status_id", [self::STATUS_NEW, self::STATUS_PROCESSING])
                                ->whereDate("{$this->table}.date_expected", '<', Carbon::today());
                        })->orWhere(function ($query) {
                            $query->whereIn("{$this->table}.ticket_status_id", [self::STATUS_COMPLETED, self::STATUS_CLOSE])
                                ->where("{$this->table}.date_finished", '>', "{$this->table}.date_expected");
                        });
                    })
                    ->whereIn("{$this->table}.ticket_status_id", $list_status);
            }
        }

        $oSelect = $oSelect
            ->orderBy($this->table . ".created_at", "DESC")
            ->orderBy($this->table . ".priority", "ASC")
            ->orderBy($this->table . ".ticket_status_id", "ASC");
        $oSelect = $oSelect->groupBy($this->table . '.ticket_id')
            ->orderBy($this->table . ".ticket_id", "DESC");

        // get số trang
        $page = (int)($data['page'] ?? 1);


        return $oSelect->paginate(PAGING_ITEM_PER_PAGE, $columns = ["*"], $pageName = 'page', $page);
    }

    public function getListTicket($data = [])
    {
        $staffId = $data['staff_id'];
        $oSelect = $this
            ->select(
                $this->table . '.ticket_id',
                $this->table . '.ticket_code',
                $this->table . '.title',
                'ticket_issue.name as issue_name',
                $this->table . '.date_issue',
                $this->table . '.date_expected',
                $this->table . '.issule_level',
                $this->table . '.priority',
                $this->table . '.queue_process_id',
                'ticket_queue..queue_name',
                $this->table . '.ticket_status_id',
                'ticket_status.status_name'
            )
            ->join('ticket_issue', 'ticket_issue.ticket_issue_id', $this->table . '.ticket_issue_id')
            ->join('ticket_queue', 'ticket_queue.ticket_queue_id', $this->table . '.queue_process_id')
            ->join('ticket_status', 'ticket_status.ticket_status_id', $this->table . '.ticket_status_id')
            ->where('ticket_queue.is_actived', 1);

        // dd($data['arr_queue']);
        // if (isset($data['arr_queue'])){
        //     $oSelect = $oSelect->whereIn($this->table.'.queue_process_id', $data['arr_queue']);
        // }

        if (isset($data['ticket_status_id'])) {
            $oSelect = $oSelect->where($this->table . '.ticket_status_id', $data['ticket_status_id']);
        }

        if (isset($data['ticket_type'])) {
            $oSelect = $oSelect->where($this->table . '.ticket_type', $data['ticket_type']);
        }

        if (isset($data['issule_level'])) {
            $oSelect = $oSelect->where($this->table . '.issule_level', $data['issule_level']);
        }

        if (isset($data['priority'])) {
            $oSelect = $oSelect->where($this->table . '.priority', $data['priority']);
        }

        if (isset($data['created_from']) && isset($data['created_to'])) {
            $oSelect = $oSelect->whereBetween($this->table . '.created_at', [$data['created_from'], $data['created_to']]);
        }

        if (isset($data['overdue_ticket_check']) && $data['overdue_ticket_check'] == 1) {
            $oSelect = $oSelect
                ->where($this->table . '.ticket_status_id', '<>', 3)
                ->where($this->table . '.date_expected', '<', Carbon::now());
        }
        /* 
        - filter theo 
            + quyền tạo , 
            + xử lý ,
            + chủ trì ,
            lấy danh sách ticket được assign 
            admin lấy full
        */
        $oSelect = $this->permissionView($oSelect);
        $oSelect = $oSelect
            ->orderBy($this->table . ".created_at", "DESC")
            ->orderBy($this->table . ".priority", "ASC")
            ->orderBy($this->table . ".ticket_status_id", "ASC");

        // get số trang
        $page = (int)($data['page'] ?? 1);


        return $oSelect->paginate(PAGING_ITEM_PER_PAGE, $columns = ["*"], $pageName = 'page', $page);
    }

//    Lấy tổng số ticket theo trạng thái cho nhân viên chưa hết hạn
    public function getTotalForStatusUnexpired($staffId, $status = [], $roleId, $listQueue)
    {
        $oSelect = $this;

//        if ($roleId == 1) {
//            $oSelect = $oSelect
//                ->join('ticket_processor',function ($join) use ($staffId){
//                    $join->on('ticket_processor.ticket_id',$this->table.'.ticket_id')
//                        ->where('ticket_processor.process_by',$staffId);
//                });
//        }

        $oSelect = $oSelect
            ->leftJoin('ticket_processor', 'ticket_processor.ticket_id', $this->table . '.ticket_id')
            ->where(function ($sql) use ($staffId) {
                $sql
                    ->where($this->table . '.operate_by', $staffId)
                    ->orWhere('ticket_processor.process_by', $staffId);
            });

        $oSelect = $oSelect
            ->join('ticket_queue', 'ticket_queue.ticket_queue_id', $this->table . '.queue_process_id')
            ->where('ticket_queue.is_actived', 1)
            ->where($this->table . '.date_expected', '>=', Carbon::now());

        if (count($status) != 0) {
            $oSelect = $oSelect->whereIn($this->table . '.ticket_status_id', $status);
        }

        if (count($listQueue) != 0) {
            $oSelect = $oSelect->whereIn($this->table . '.queue_process_id', $listQueue);
        }
        $oSelect = $oSelect->groupBy($this->table . '.ticket_id')->get()->count();
        return $oSelect;
        if ($roleId != null) {
            return $oSelect;
        } else {
            return 0;
        }

    }

//    Lấy tổng số ticket theo trạng thái cho nhân viên bao gồm hết hạn
    public function getTotalForStatus($staffId, $status = [], $roleId, $listQueue, $type = '')
    {
        $oSelect = $this
            ->join('ticket_queue', 'ticket_queue.ticket_queue_id', $this->table . '.queue_process_id')
            ->where('ticket_queue.is_actived', 1);

//        if ($roleId == 1) {
////            if ($type == 'my-ticket'){
//                $oSelect = $oSelect
//                    ->join('ticket_processor',function ($join) use ($staffId){
//                        $join->on('ticket_processor.ticket_id',$this->table.'.ticket_id')
//                            ->where('ticket_processor.process_by',$staffId);
//                    });
//        } else {
//            if ($type == 'my-ticket'){
//                $oSelect = $oSelect->where($this->table.'.process_by',$staffId);
//            }
//        }

        $oSelect = $oSelect
//            ->leftJoin('ticket_processor',function ($join) use ($staffId){
//                $join->on('ticket_processor.ticket_id',$this->table.'.ticket_id')
//                    ->where('ticket_processor.process_by',$staffId);
//            })
            ->leftJoin('ticket_processor', 'ticket_processor.ticket_id', $this->table . '.ticket_id')
            ->where(function ($sql) use ($staffId) {
                $sql
                    ->where($this->table . '.operate_by', $staffId)
                    ->orWhere('ticket_processor.process_by', $staffId);
            });

        if (count($status) != 0) {
            $oSelect = $oSelect->whereIn($this->table . '.ticket_status_id', $status);
        }

        if (count($listQueue) != 0) {
            $oSelect = $oSelect->whereIn($this->table . '.queue_process_id', $listQueue);
        }

        $oSelect = $oSelect->groupBy($this->table . '.ticket_id')->get()->count();
        return $oSelect;
        if ($roleId != null) {
            return $oSelect;
        } else {
            return 0;
        }
    }

//    Lấy tổng số ticket theo trạng thái cho nhân viên đã quá hạn
    public function getTotalForStatusExpired($staffId, $status = [], $roleId, $listQueue)
    {
        $oSelect = $this
            ->join('ticket_queue', 'ticket_queue.ticket_queue_id', $this->table . '.queue_process_id')
            ->where('ticket_queue.is_actived', 1);
//        if ($roleId == 1) {
////            $oSelect = $oSelect->leftJoin('ticket_processor','ticket_processor.ticket_id',$this->table.'.ticket_id');
//            $oSelect = $oSelect
//                ->join('ticket_processor',function ($join) use ($staffId){
//                    $join->on('ticket_processor.ticket_id',$this->table.'.ticket_id')
//                        ->where('ticket_processor.process_by',$staffId);
//                });
////        } else {
////            $oSelect = $oSelect->leftJoin('ticket_operater','ticket_operater.ticket_id',$this->table.'.ticket_id');
//        }

        $oSelect = $oSelect
//            ->leftJoin('ticket_processor',function ($join) use ($staffId){
//                $join->on('ticket_processor.ticket_id',$this->table.'.ticket_id')
//                    ->where('ticket_processor.process_by',$staffId);
//            })
            ->leftJoin('ticket_processor', 'ticket_processor.ticket_id', $this->table . '.ticket_id')
            ->where(function ($sql) use ($staffId) {
                $sql
                    ->where($this->table . '.operate_by', $staffId)
                    ->orWhere('ticket_processor.process_by', $staffId);
            });

//            ->leftJoin('ticket_processor',function ($join) use ($staffId){
//                $join->on('ticket_processor.ticket_id',$this->table.'.ticket_id');
//            })
//            ->leftJoin('ticket_operater',function ($join) use ($staffId){
//                $join->on('ticket_operater.ticket_id',$this->table.'.ticket_id');
//            })
//            ->where(function ($join) use ($staffId){
//                $join->where('ticket_processor.process_by',$staffId)
//                    ->orWhere('ticket_operater.operate_by',$staffId);
//
//            })
        $oSelect = $oSelect->where($this->table . '.date_expected', '<', Carbon::now());

        if (count($status) != 0) {
            $oSelect = $oSelect->whereIn($this->table . '.ticket_status_id', $status);
        }

        if (count($listQueue) != 0) {
            $oSelect = $oSelect->whereIn($this->table . '.queue_process_id', $listQueue);
        }

        $oSelect = $oSelect->groupBy($this->table . '.ticket_id')->get()->count();
        return $oSelect;
    }

//    Lấy danh sách ticket cho chart
    public function getChart($staffId, $status = [], $roleId, $listQueue)
    {
        $oSelect = $this
            ->select(
                DB::raw('DATE_FORMAT(ticket.date_finished,"%d/%m") as fulltime'),
                DB::raw('COUNT(ticket.ticket_status_id) as totalTicket')
            )
            ->join('ticket_queue', 'ticket_queue.ticket_queue_id', $this->table . '.queue_process_id')
            ->where('ticket_queue.is_actived', 1)
            ->where($this->table . '.date_finished', '>=', Carbon::now()->startOfWeek()->format('Y-m-d 00:00:00'))
            ->where($this->table . '.date_finished', '<=', Carbon::now()->endOfWeek()->subDays(2)->format('Y-m-d 23:59:59'));

//        if ($roleId == 1) {
//            $oSelect = $oSelect
//                ->join('ticket_processor',function ($join) use ($staffId){
//                    $join->on('ticket_processor.ticket_id',$this->table.'.ticket_id')
//                        ->where('ticket_processor.process_by',$staffId);
//                });
////        } else {
////            $oSelect = $oSelect->leftJoin('ticket_operater','ticket_operater.ticket_id',$this->table.'.ticket_id');
//        }

        $oSelect = $oSelect
//            ->leftJoin('ticket_processor',function ($join) use ($staffId){
//                $join->on('ticket_processor.ticket_id',$this->table.'.ticket_id')
//                    ->where('ticket_processor.process_by',$staffId);
//            })
            ->leftJoin('ticket_processor', 'ticket_processor.ticket_id', $this->table . '.ticket_id')
            ->where(function ($sql) use ($staffId) {
                $sql
                    ->where($this->table . '.operate_by', $staffId)
                    ->orWhere('ticket_processor.process_by', $staffId);
            });


//            ->leftJoin('ticket_processor',function ($join) use ($staffId){
//                $join->on('ticket_processor.ticket_id',$this->table.'.ticket_id');
//            })
//            ->leftJoin('ticket_operater',function ($join) use ($staffId){
//                $join->on('ticket_operater.ticket_id',$this->table.'.ticket_id');
//            })
//            ->where(function ($join) use ($staffId){
//                $join->where('ticket_processor.process_by',$staffId)
//                    ->orWhere('ticket_operater.operate_by',$staffId);
//
//            });

        if (count($status) != 0) {
            $oSelect = $oSelect->whereIn($this->table . '.ticket_status_id', $status);
        }

        if (count($listQueue) != 0) {
            $oSelect = $oSelect->whereIn($this->table . '.queue_process_id', $listQueue);
        }

        return $oSelect
            ->orderBy('ticket.date_finished', 'ASC')
            ->groupBy(DB::raw('DATE_FORMAT(ticket.date_finished,"%d-%m-%Y")'))
            ->get();
    }

//    Danh sách ticket chưa hoàn thành
    public function getListNotCompleted($data)
    {
        $staffId = $data['staff_id'];
        $oSelect = $this
            ->select(
                $this->table . '.date_expected',
                'ticket_queue..ticket_queue_id',
                'ticket_queue..queue_name',
                $this->table . '.ticket_status_id',
                'ticket_status.status_name'
            )
            ->join('ticket_issue', 'ticket_issue.ticket_issue_id', $this->table . '.ticket_issue_id')
            ->join('ticket_queue', 'ticket_queue.ticket_queue_id', $this->table . '.queue_process_id')
            ->join('ticket_status', 'ticket_status.ticket_status_id', $this->table . '.ticket_status_id')
            ->where('ticket_queue.is_actived', 1);
//            ->leftJoin('ticket_processor',function ($join) use ($staffId){
//                $join->on('ticket_processor.ticket_id',$this->table.'.ticket_id');
//            })
//            ->leftJoin('ticket_operater',function ($join) use ($staffId){
//                $join->on('ticket_operater.ticket_id',$this->table.'.ticket_id');
//            })
//            ->where('ticket_processor.process_by',$staffId)
//            ->orWhere('ticket_operater.operate_by',$staffId);

//        if ($data['roleStaff'] == 1){
//            $oSelect = $oSelect
//                ->join('ticket_processor','ticket_processor.ticket_id',$this->table.'.ticket_id')
//                ->where('ticket_processor.process_by',$staffId);
//        } else {
//            $oSelect = $oSelect->leftJoin('ticket_processor','ticket_processor.ticket_id',$this->table.'.ticket_id');
//        }

        $oSelect = $this->permissionView($oSelect);

        $oSelect = $oSelect
            ->where("{$this->table}.ticket_status_id", self::STATUS_PROCESSING)
            ->groupBy($this->table . '.ticket_id')->orderBy($this->table . ".ticket_id", "DESC");

        return $oSelect->get();
    }

    public function getDetail($ticket_id)
    {
        $oSelect = $this
            ->select(
                $this->table . '.ticket_id',
                $this->table . '.ticket_code',
                'province.name as location_name',
//                $this->table.'.ticket_type',
                'ticket_issue_group.type as ticket_type',
                'ticket_issue_group.name as ticket_issue_group_name',
                'ticket_issue.name as ticket_issue_name',
                $this->table . '.issule_level',
                $this->table . '.priority',
                $this->table . '.title',
                $this->table . '.description',
                "customers.customer_id",
                'customers.full_name as customer_name',
                "customers.phone1 as customer_phone",
                $this->table . '.customer_address',
                $this->table . '.date_issue',
                $this->table . '.date_expected',
                $this->table . '.date_estimated',
                $this->table . '.date_finished',
                $this->table . '.date_request',
                $this->table . '.queue_process_id',
                $this->table . '.ticket_issue_id',
                'ticket_queue.queue_name',
                $this->table . '.ticket_status_id',
                'ticket_status.status_name',
                'staffs.full_name as staff_noti_name',
                $this->table . '.operate_by as staff_host_id',
                'staffs_host.full_name as staff_host_name',
                'ticket_queue.is_actived'
            )
            ->join('province', 'province.provinceid', $this->table . '.localtion_id')
// //            ->leftJoin('ticket_issue_group','ticket_issue_group.ticket_issue_group_id',$this->table.'.ticket_issue_group_id')
            ->leftJoin('ticket_issue_group', 'ticket_issue_group.ticket_issue_group_id', $this->table . '.ticket_type')
            ->leftJoin('ticket_issue', 'ticket_issue.ticket_issue_id', $this->table . '.ticket_issue_id')
            ->leftJoin('customers', 'customers.customer_id', $this->table . '.customer_id')
            ->leftJoin('ticket_queue', 'ticket_queue.ticket_queue_id', $this->table . '.queue_process_id')
            ->leftJoin('ticket_status', 'ticket_status.ticket_status_id', $this->table . '.ticket_status_id')
            ->leftJoin('staffs', 'staffs.staff_id', $this->table . '.staff_notification_id')
            ->leftJoin('staffs as staffs_host', 'staffs_host.staff_id', $this->table . '.operate_by')
            ->where('ticket_queue.is_actived', 1)
            ->where($this->table . '.ticket_id', $ticket_id)
            ->first();
        return $oSelect;
    }

//    Cập nhật ticket
    public function updateTicket($ticketID, $data)
    {
        $oSelect = $this
            ->where('ticket_id', $ticketID)
            ->update($data);

        return $oSelect;
    }

    public function ticketDetailByTicket($ticketId)
    {
        return $this
            ->where('ticket_id', $ticketId)
            ->first();
    }

//    Thông tin ticket cho noti
    public function getInfoNoti($ticketId)
    {
        $oSelect = $this
            ->select(
                $this->table . '.ticket_id',
                $this->table . '.ticket_code',
                'staff_updated.full_name as full_name_updated',
                'staff_created.full_name as full_name_created',
                "{$this->table}.title"
            )
            ->join('staffs as staff_updated', 'staff_updated.staff_id', $this->table . '.updated_by')
            ->join('staffs as staff_created', 'staff_created.staff_id', $this->table . '.created_by')
            ->where($this->table . '.ticket_id', $ticketId)
            ->first();

        return $oSelect;
    }

    public function checkTicket($ticketId, $staffId)
    {
        return $this
            ->where('operate_by', $staffId)
            ->where('ticket_id', $ticketId)
            ->first();
    }

    public function getPermission($oSelect)
    {
        $user = Auth::user();

        $userId = $user->staff_id;

        $dataRole = DB::table('map_role_group_staff')
            ->select('manage_role.role_group_id', 'is_all', 'is_branch', 'is_department', 'is_own')
            ->join('manage_role', 'manage_role.role_group_id', 'map_role_group_staff.role_group_id')
            ->where('staff_id', $userId)
            ->get()->toArray();

        $isAll = $isBranch = $isDepartment = $isOwn = 0;
        foreach ($dataRole as $role) {
            $role = (array)$role;
            if ($role['is_all']) {
                $isAll = 1;
            }

            if ($role['is_branch']) {
                $isBranch = 1;
            }

            if ($role['is_department']) {
                $isDepartment = 1;
            }

            if ($role['is_own']) {
                $isOwn = 1;
            }
        }
        $listManageSupport = DB::table('manage_work_support')
            ->where('staff_id', $userId)
            ->get()->pluck('manage_work_id')->toArray();
        if ($isAll) {

        } else if ($isBranch) {
            $myBrand = $user->branch_id;

            $oSelect = $oSelect->join('staffs as per_staff', 'per_staff.staff_id', '=', $this->table . '.processor_id')
                ->where('per_staff.branch_id', $myBrand);
        } else if ($isDepartment) {
            $myDep = $user->department_id;

            $oSelect = $oSelect->join('staffs as per_staff', 'per_staff.staff_id', '=', $this->table . '.processor_id')
                ->where('per_staff.department_id', $myDep);
        } else {
            $listManageSupport = DB::table('manage_work_support')
                ->where('staff_id', $userId)
                ->get()->pluck('manage_work_id')->toArray();

            $oSelect = $oSelect->where(function ($query) use ($userId, $listManageSupport) {
                $query->where($this->table . '.processor_id', $userId)->orWhere($this->table . '.assignor_id', $userId)
                    ->orWhere($this->table . '.approve_id', $userId)->orWhereIn($this->table . '.manage_work_id', $listManageSupport);
            });
        }

        return $oSelect;
    }

    /*
    *Lấy tổng số ticket mới
    */
    public function getTotalTicketNew()
    {
        $user_id = \Auth::id();
        $query = $this
            ->select('*')
            ->join('ticket_issue', 'ticket_issue.ticket_issue_id', $this->table . '.ticket_issue_id')
            ->join('ticket_queue', 'ticket_queue.ticket_queue_id', $this->table . '.queue_process_id')
            ->join('ticket_status', 'ticket_status.ticket_status_id', $this->table . '.ticket_status_id')
            ->where('ticket_queue.is_actived', 1);
        $query = $this->permissionView($query);
        // (Mới)
        return $query->where("{$this->table}.ticket_status_id", self::STATUS_NEW)->get()->count();
    }

    /*
   *Lấy tổng số ticket đang xử lý
   */
    public function getTotalTicketProccessing()
    {
        $user_id = \Auth::id();
        $query = $this
            ->select('*')
            ->join('ticket_issue', 'ticket_issue.ticket_issue_id', $this->table . '.ticket_issue_id')
            ->join('ticket_queue', 'ticket_queue.ticket_queue_id', $this->table . '.queue_process_id')
            ->join('ticket_status', 'ticket_status.ticket_status_id', $this->table . '.ticket_status_id')
            ->where('ticket_queue.is_actived', 1);
        $query = $this->permissionView($query);
        // (Đang xử lý)
        return $query->where("{$this->table}.ticket_status_id", self::STATUS_PROCESSING)->get()->count();
    }

    /*
   *Lấy tổng số ticket của tôi
   */
    public function getTotalTicketForMe()
    {
        $query = $this->select('*')
            ->join('ticket_issue', 'ticket_issue.ticket_issue_id', $this->table . '.ticket_issue_id')
            ->join('ticket_queue', 'ticket_queue.ticket_queue_id', $this->table . '.queue_process_id')
            ->join('ticket_status', 'ticket_status.ticket_status_id', $this->table . '.ticket_status_id')
            ->where('ticket_queue.is_actived', 1);
        /*
        - filter theo
            + quyền tạo ,
            + xử lý ,
            + chủ trì ,
            lấy danh sách ticket được assign
            admin lấy full
        */
//        $query = $this->permission($query);
        $list_processor = \DB::table('ticket_processor')->where('process_by', \Auth::id())->groupBy('ticket_id')->pluck('ticket_id')->toArray();
        // // lấy danh sách ticket có nhân viên xử lý là user
        $user_id = \Auth::id();
        $query = $this->where(function ($query) use ($list_processor, $user_id) {
            $query->whereIn("{$this->table}.ticket_id", $list_processor)
                ->orWhere("{$this->table}.operate_by", $user_id);
        });
        return $query->get()->count();
    }

    /*
   *Lấy tổng số ticket quá hạn
   */
    public function getTotalTicketExpire()
    {
        $user_id = \Auth::id();
        $query = $this
            ->select('*')
            ->join('ticket_issue', 'ticket_issue.ticket_issue_id', $this->table . '.ticket_issue_id')
            ->join('ticket_queue', 'ticket_queue.ticket_queue_id', $this->table . '.queue_process_id')
            ->join('ticket_status', 'ticket_status.ticket_status_id', $this->table . '.ticket_status_id')
            ->where('ticket_queue.is_actived', 1);
        $query = $this->permissionView($query);

        $list_status = [1, 2, 3, 6, 7];

        $query = $query->where(function ($query) {
            $query->where(function ($query) {
                $query->whereIn("{$this->table}.ticket_status_id", [self::STATUS_NEW, self::STATUS_PROCESSING])
                    ->whereDate("{$this->table}.date_expected", '<', Carbon::today());
            })->orWhere(function ($query) {
                $query->whereIn("{$this->table}.ticket_status_id", [self::STATUS_COMPLETED, self::STATUS_CLOSE])
                    ->where("{$this->table}.date_finished", '>', "{$this->table}.date_expected");
            });
        });
        // (Mới, đang xử lý, hoàn tất, quá hạn)
        return $query->whereIn("{$this->table}.ticket_status_id", $list_status)->get()->count();
    }

    //  phân quyền admin & user thường
    private function permission($query, $check_permission = true)
    {
        /*
        - filter theo
            + quyền tạo ,
            + xử lý ,
            + chủ trì ,
            lấy danh sách ticket được assign
            admin lấy full
        */
        $list_processor = \DB::table('ticket_processor')->where('process_by', \Auth::id())->groupBy('ticket_id')->pluck('ticket_id')->toArray();
        // lấy danh sách ticket có nhân viên xử lý là user
        $user_id = \Auth::id();
        if ($check_permission) {
            if (!\Auth::user()->is_admin) {
                $query = $query->where(function ($query) use ($list_processor, $user_id) {
                    $query->orWhere("{$this->table}.created_by", $user_id)
                        ->orWhereIn("{$this->table}.ticket_id", $list_processor)
                        ->orWhere("{$this->table}.operate_by", $user_id)
                        ->orWhere("{$this->table}.staff_notification_id", $user_id);
                });
            }
        } else {
            $query = $this->where(function ($query) use ($list_processor, $user_id) {
                $query->whereIn("{$this->table}.ticket_id", $list_processor)
                    ->orWhere("{$this->table}.operate_by", $user_id)
                    ->orWhere("{$this->table}.staff_notification_id", $user_id);
            });
        }
        /*
        - Phân quyền theo
            + Cá nhân ,
            + Theo queue ,
            + Theo phòng ,
            + Theo chi nhánh ,
            + tất cả ,
        */
        return $query;
    }

    //tạo ticket
    public function addTicket($data)
    {
        return $this->create($data)->ticket_id;
    }

    function permissionView($oSelect)
    {

        if (!\Auth::user()->is_admin) {
            $list_processor = \DB::table('ticket_processor')->where('process_by', \Auth::id())->groupBy('ticket_id')->pluck('ticket_id')->toArray();
            $tiketQueue = \DB::table('ticket_staff_queue')
                ->join('ticket_staff_queue_map', 'ticket_staff_queue_map.ticket_staff_queue_id', 'ticket_staff_queue.ticket_staff_queue_id')
                ->where('ticket_staff_queue.staff_id', \Auth::id())->get();
            $arrQueue = [];

            foreach ($tiketQueue as $objQueue) {

                $arrQueue[] = $objQueue->ticket_queue_id;
            }
            $oSelect = $oSelect->where(function ($oSelect) use ($list_processor, $arrQueue) {
                $oSelect->orWhere("{$this->table}.created_by", \Auth::id())
                    ->orWhere("{$this->table}.operate_by", \Auth::id())
                    ->orWhereIn("{$this->table}.ticket_id", $list_processor)
                    ->orWhere("{$this->table}.staff_notification_id", \Auth::id());
                if (count($arrQueue) > 0) {
                    $oSelect->orWhereIn("{$this->table}.queue_process_id", $arrQueue);
                }
            });
        }
        return $oSelect;
    }
}