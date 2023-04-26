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

class TicketStaffQueueMapTable extends Model
{
    protected $table = "ticket_staff_queue_map";
    protected $primaryKey = "ticket_staff_queue_map_id";

//    Lấy danh sách queue có thể xem ticket
    public function getListQueueView($staffId){
        $oSelect = $this
            ->select(
                'ticket_staff_queue_map.ticket_queue_id',
                'ticket_staff_queue.ticket_role_queue_id',
                $this->table.'.ticket_queue_id as ticket_view_queue_id'
            )
            ->leftJoin('ticket_staff_queue','ticket_staff_queue.ticket_staff_queue_id',$this->table.'.ticket_staff_queue_id')
            ->where('ticket_staff_queue.staff_id',$staffId)
            ->get();
        return $oSelect;
    }
}