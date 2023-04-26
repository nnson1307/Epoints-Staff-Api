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

class TicketAlertTable extends Model
{
    protected $table = "ticket_alert";
    protected $primaryKey = "ticket_alert_id";

//    lấy thời gian được cấu hình
    public function checkTimeAlert($time){
        $oSelect = $this
            ->where('time','<=',$time)
            ->orderBy('time','DESC')
            ->first();
        return $oSelect;
    }
}