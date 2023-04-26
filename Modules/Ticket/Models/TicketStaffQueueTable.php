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
use Illuminate\Support\Facades\DB;

class TicketStaffQueueTable extends Model
{
    protected $table = "ticket_staff_queue";
    protected $primaryKey = "ticket_staff_queue_id";

//    Lấy danh sách file theo loại
    public function getListQueueViewByStaff($staffId){
        $oSelect = $this
            ->select(
                'staff_id',
                'ticket_queue_id'
            )
            ->where($this->table.'.staff_id',$staffId)
            ->orderBy($this->table.'.ticket_staff_queue_id','DESC')
            ->get();
        return $oSelect;
    }

    public function getListQueueStaff($staffId){
        return $this
            ->select(
                $this->table.'.ticket_queue_id',
                'ticket_queue.queue_name'
            )
            ->join('ticket_queue','ticket_queue.ticket_queue_id',$this->table.'.ticket_queue_id')
            ->where($this->table.'.staff_id',$staffId)
            ->where('ticket_queue.is_actived',1)
            ->get();
    }

//    Lấy danh sách nhân viên theo queue
    public function getListStaffByQueue($ticketQueueId, $ticketRoleRueueId = null){
        $ds = $this
        ->select(
            $this->table.'.staff_id',
            'staffs.full_name as user_name',
            'staffs.staff_avatar',
            $this->table.'.ticket_role_queue_id',
            'ticket_role_queue.name as ticket_role_queue_name'
        )
        ->join('ticket_role_queue','ticket_role_queue.ticket_role_queue_id',$this->table.'.ticket_role_queue_id')
        ->join('staffs','staffs.staff_id',$this->table.'.staff_id')
        ->where($this->table.'.ticket_queue_id',$ticketQueueId);
        if($ticketRoleRueueId != null){
            $ds = $ds->where($this->table.'.ticket_role_queue_id',$ticketRoleRueueId);
        }
        return $ds->get();
    }

//    Lấy quyền nhân viên
    public function getQueueStaff($staffId){
        $oSelect = $this
            ->select(
                $this->table.'.ticket_queue_id',
                'ticket_queue.queue_name as ticket_queue_name',
                'ticket_role_queue.name as ticket_role_queue_name'
            )
            ->join('ticket_queue','ticket_queue.ticket_queue_id',$this->table.'.ticket_queue_id')
            ->join('ticket_role_queue','ticket_role_queue.ticket_role_queue_id',$this->table.'.ticket_role_queue_id')
            ->where($this->table.'.staff_id',$staffId)
            ->orderBy($this->table.'.ticket_staff_queue_id','DESC')
            ->first();
        return $oSelect;
    }
}