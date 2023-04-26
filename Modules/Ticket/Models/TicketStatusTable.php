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

class TicketStatusTable extends Model
{
    protected $table = "ticket_status";
    protected $primaryKey = "ticket_status_id";

//    Lấy danh sách file theo loại
    public function getListTicketStatus($roleStaff = null,$listStatus = []){
        $oSelect = $this
            ->select(
                'ticket_status_id',
                'ticket_status_value',
                'status_name'
            );

//        Nhân viên xử lý
//        if ($roleStaff == 1) {
//            $oSelect = $oSelect->whereNotIn('ticket_status_id',['4','5']);
////            Nhân viên chủ trì
//        } else if ($roleStaff == 2){
//            $oSelect = $oSelect->whereNotIn('ticket_status_id',['5']);
//        }

        $oSelect = $oSelect->whereIn('ticket_status_id',$listStatus);
        return $oSelect->get();
    }

    public function getListTicketForNotCompleted($arrStatus,$new_total,$processing_total,$completed_total){
        return $this
            ->select(
                'ticket_status_id as status_id',
                'status_name',
                DB::raw("IF(ticket_status.ticket_status_id = 1,{$new_total},(IF(ticket_status.ticket_status_id = 2,{$processing_total},{$completed_total}))) as total")

            )
            ->whereIn('ticket_status_id',$arrStatus)
            ->get()
            ->toArray();
    }


}