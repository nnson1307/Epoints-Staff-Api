<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 03/10/2022
 * Time: 09:11
 */

namespace Modules\Ticket\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ManageWorkTable extends Model
{
    protected $table = "manage_work";
    protected $primaryKey = "manage_work_id";

    const VIEW_TICKET = "ticket";

    /**
     * Lấy ds công việc của ticket
     *
     * @param $ticketId
     * @return mixed
     */
    public function getListOfTicket($ticketId)
    {
        $user = Auth()->id();

        $high = __('Cao');
        $normal = __('Bình thường');
        $low = __('Thấp');

        return $this
            ->select(
                $this->table.'.manage_work_id',
                $this->table.'.manage_work_title',
                $this->table.'.progress',
                $this->table.'.parent_id',
                'parent.manage_work_title as parent_name',
                $this->table.'.processor_id',
                'staffs.full_name as processor_name',
                'staffs.staff_avatar as processor_avatar',
                $this->table.'.assignor_id',
                'assignor.full_name as assignor_name',
                'assignor.staff_avatar as assignor_avatar',
                $this->table.'.manage_status_id',
                'manage_status.manage_status_name',
                'manage_status.manage_status_color',
                'manage_project.manage_project_name',
                $this->table.'.manage_project_id',
                $this->table.'.manage_type_work_id',
                $this->table.'.date_end',
                $this->table.'.priority',
                $this->table.'.is_approve_id',
                $this->table.'.approve_id',
                $this->table.'.branch_id',
                DB::raw("IF((manage_work.processor_id = {$user} OR manage_work.assignor_id = {$user}) AND manage_status_config.is_edit = 1, 1,0) as is_edit"),
                DB::raw("IF((manage_work.processor_id = {$user} OR manage_work.assignor_id = {$user}) AND manage_status_config.is_deleted = 1, 1,0) as is_deleted"),
                DB::raw("IF(manage_work.priority = 1 , '{$high}' , IF(manage_work.priority = 2 , '{$normal}','{$low}') ) as priority_name"),
                DB::raw("IF(manage_work.approve_id = {$user} AND manage_work.manage_status_id = 3 , 1,0) as is_approve"),
                DB::raw("(SELECT COUNT(manage_work_parent.manage_work_id) FROM manage_work as manage_work_parent where manage_work.manage_work_id = manage_work_parent.parent_id ) as total_child_job")
            )
            ->leftJoin('staffs','staffs.staff_id',$this->table.'.processor_id')
            ->leftJoin('staffs as assignor','assignor.staff_id',$this->table.'.assignor_id')
//            ->leftJoin('manage_work_support',function ($sql) use ($user){
//                $sql->on('manage_work_support.manage_work_id',$this->table.'.manage_work_id')
//                    ->where('manage_work_support.staff_id',$user);
//            })
            ->join('manage_status','manage_status.manage_status_id',$this->table.'.manage_status_id')
            ->leftJoin('manage_project','manage_project.manage_project_id',$this->table.'.manage_project_id')
            ->leftJoin('manage_status_config', 'manage_status_config.manage_status_id', '=', "{$this->table}.manage_status_id")
            ->leftJoin('manage_work as parent','parent.manage_work_id',$this->table.'.parent_id')
            ->where("{$this->table}.create_object_type", self::VIEW_TICKET)
            ->where("{$this->table}.create_object_id", $ticketId)
            ->orderBy($this->table.'.manage_status_id','ASC')
            ->orderBy($this->table.'.date_end', 'DESC')
            ->groupBy($this->table.'.manage_work_id')
            ->get();
    }
}