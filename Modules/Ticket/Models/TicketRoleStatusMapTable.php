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

class TicketRoleStatusMapTable extends Model
{
    protected $table = "ticket_role_status_map";
    protected $primaryKey = "ticket_role_status_map_id";

    /**
     * Kiểm tra cập nhật theo trạng thái
     */
    public function checkRoleStatus($staffId,$statusId){
        return $this
            ->join('ticket_role','ticket_role.ticket_role_id',$this->table.'.ticket_role_id')
            ->join('map_role_group_staff','map_role_group_staff.role_group_id','ticket_role.role_group_id')
            ->where('map_role_group_staff.staff_id',$staffId)
            ->where('map_role_group_staff.is_actived',1)
            ->where($this->table.'.ticket_status_id',$statusId)
            ->first();
    }

}