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

class TicketHistoryTable extends Model
{
    protected $table = "ticket_history";
    protected $primaryKey = "ticket_process_history_id";

//    Lấy danh sách lịch sử
    public function getListHistory($ticketId){
        $lang = app()->getLocale();
        return $this
            ->select(
                'staffs.full_name as created_name',
                $this->table.'.created_at',
                "ticket_history.note_{$lang} as note"
            )
            ->join('staffs','staffs.staff_id',$this->table.'.created_by')
            ->where($this->table.'.ticket_id',$ticketId)
            ->orderBy($this->table.'.ticket_process_history_id','DESC')
            ->get();
    }

    public function createHistory($data){
        $oSelect = $this
            ->insertGetId($data);
        return $oSelect;
    }
}