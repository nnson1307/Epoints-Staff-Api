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

class TicketProcessorTable extends Model
{
    protected $table = "ticket_processor";
    protected $primaryKey = "ticket_processor_id";

//    Lấy danh sách người xử lý theo ticket
    public function getListHandlerStaff($ticket_id){
        $oSelect = $this
            ->select(
                'staffs.staff_id',
                'staffs.full_name as user_name'
            )
            ->join('staffs','staffs.staff_id',$this->table.'.process_by')
            ->where($this->table.'.ticket_id',$ticket_id)
            ->orderBy($this->table.'.ticket_processor_id','DESC')
            ->get();
        return $oSelect;
    }

//    Kiểm tra ticket
    public function checkTicket($ticketId,$staffId){
        $oSelect = $this
            ->where('ticket_id',$ticketId)
            ->where('process_by',$staffId)
            ->first();
        return $oSelect;
    }

    public function getListProcessor($ticketId){
        $oSelect = $this
            ->select('process_by as staff_id')
            ->where('ticket_id',$ticketId)
            ->get();
        return $oSelect;
    }

//    Xoá nhân viên xử lý cũ
    public function deleteListStaff($ticketId){
        return $this
            ->where('ticket_id',$ticketId)
            ->delete();
    }

//    Thêm nhân viên xử lý mới
    public function createdStaff($data){
        return $this
            ->insert($data);
    }
}