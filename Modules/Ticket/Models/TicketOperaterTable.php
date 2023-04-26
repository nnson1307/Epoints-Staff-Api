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

class TicketOperaterTable extends Model
{
    protected $table = "ticket_operater";
    protected $primaryKey = "ticket_operater_id";

//    Lấy danh sách người chủ trì theo ticket
    public function getListHostStaff($ticket_id){
        $oSelect = $this
            ->select(
                'staffs.staff_id',
                'staffs.full_name as user_name'
            )
            ->join('staffs','staffs.staff_id',$this->table.'.operate_by')
            ->where($this->table.'.ticket_id',$ticket_id)
            ->orderBy($this->table.'.ticket_operater_id','DESC')
            ->first();
        return $oSelect;
    }

//    Kiểm tra ticket
    public function checkTicket($ticketId,$staffId){
        $oSelect = $this
            ->where('ticket_id',$ticketId)
            ->where('operate_by',$staffId)
            ->first();
        return $oSelect;
    }

//    lấy danh sách nhân viên chủ trì theo ticket
    public function getListOperater($ticketId){
        return $this
            ->select('operate_by as staff_id')
            ->where('ticket_id',$ticketId)
            ->get();
    }

//    Xoá danh sách nhân viên chủ trì cũ
    public function deleteListStaff($ticketId){
        return $this
            ->where('ticket_id',$ticketId)
            ->delete();
    }

//    Tạo danh sách nhân viên chủ trì mới
    public function createdStaff($data){
        return $this->insert($data);
    }
}