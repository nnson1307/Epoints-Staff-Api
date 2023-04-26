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

class TicketRoleQueueTable extends Model
{
    protected $table = "ticket_staff_queue";
    protected $primaryKey = "ticket_staff_queue_id";

//    lấy role
    public function getTicketRoleQueue($staff){
        return $this
            ->select('ticket_role_queue_id','staff_id','ticket_queue_id as ticket_view_queue_id')->where('staff_id',$staff)->get();
    }
}