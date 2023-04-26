<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 26/05/2021
 * Time: 14:37
 */

namespace Modules\ProjectManagement\Models;


use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ManageRemindTable extends Model
{
    protected $table = "manage_remind";
    protected $primaryKey = "manage_remind_id";

    /**
     * Tạo nhắc nhở
     */
    public function createdRemindGetId($data){
        return $this->insertGetId($data);
    }

    /**
     * Tạo nhắc nhở
     */
    public function createdRemind($data){
        return $this->insert($data);
    }

    /**
     * Xoá nhắc nhở
     * @param $manage_remind_id
     */
    public function deleteRemind($manage_remind_id){
        return $this->where('manage_remind_id',$manage_remind_id)->delete();
    }

    /**
     * Xoá nhắc nhở
     * @param $manage_remind_id
     */
    public function deleteRemindArray($manage_remind_id){
        return $this->whereIn('manage_remind_id',$manage_remind_id)->delete();
    }

    /**
     * Xoá nhắc nhở theo công việc
     * @param $manage_remind_id
     */
    public function deleteRemindByWork($manage_work_id){
        return $this->where('manage_work_id',$manage_work_id)->delete();
    }

    /**
     * Lấy danh sách nhắc nhở theo công việc
     */
    public function getListRemind($manage_work_id){
        return $this
            ->select(
                $this->table.'.manage_remind_id',
                $this->table.'.date_remind',
                $this->table.'.title',
                $this->table.'.description',
                DB::raw("IF({$this->table}.manage_work_id = 0 , null , {$this->table}.manage_work_id) as manage_work_id"),
                $this->table.'.is_sent',
                'staffs.staff_avatar',
                'staffs.full_name as staff_name'
            )
            ->join('staffs','staffs.staff_id',$this->table.'.staff_id')
            ->where($this->table.'.manage_work_id',$manage_work_id)
            ->orderBy($this->table.'.date_remind','DESC')
            ->get();
    }

    /**
     * Lấy danh sách nhắc nhở của tôi
     */
    public function getListMyRemind($staffId){
        return $this
            ->select(
                $this->table.'.manage_remind_id',
                $this->table.'.title',
                $this->table.'.date_remind',
                $this->table.'.description',
                DB::raw("IF({$this->table}.manage_work_id = 0 , null , {$this->table}.manage_work_id) as manage_work_id"),
                $this->table.'.is_sent',
                'staffs.staff_avatar',
                'staffs.full_name as staff_name'
            )
            ->join('staffs','staffs.staff_id',$this->table.'.staff_id')
            ->where($this->table.'.staff_id',$staffId)
            ->orderBy($this->table.'.date_remind','DESC')
            ->get();
    }

    /**
     * Gửi noti cho nhân viên
     * @param $manage_remind_id
     * @return mixed
     */
    public function getDetailRemindNoti($manage_remind_id){
        return $this
            ->select(
                $this->table.'.manage_remind_id',
                $this->table.'.title',
                $this->table.'.date_remind',
                $this->table.'.description',
                $this->table.'.is_sent',
                $this->table.'.created_by',
                DB::raw("IF({$this->table}.manage_work_id = 0 , null , {$this->table}.manage_work_id) as manage_work_id"),
                'staffs.staff_avatar',
                'staffs.full_name as staff_name'
            )
            ->join('staffs','staffs.staff_id',$this->table.'.created_by')
            ->where($this->table.'.manage_remind_id',$manage_remind_id)
            ->first();
    }

    /**
     * Xóa nhắc nhở theo task cha
     * @param $parentTask
     */
    public function deleteRemindByParentTask($parentTask){
        return $this
            ->join('manage_work','manage_work.manage_work_id',$this->table.'.manage_work_id')
            ->where('manage_work.parent_id',$parentTask)
            ->delete();
    }
    /**
     * Lấy danh sách nhắc nhở theo dự án
     */
    public function getListRemindProject($input){
        $oSelect = $this
            ->select(
                $this->table.'.manage_remind_id',
                $this->table.'.manage_project_id',
                $this->table.'.date_remind',
                $this->table.'.title',
                $this->table.'.description',
                DB::raw("IF({$this->table}.manage_work_id = 0 , null , {$this->table}.manage_work_id) as manage_work_id"),
                $this->table.'.is_sent',
                'staffs.staff_avatar',
                'staffs.full_name as staff_name'
            )
            ->join('staffs','staffs.staff_id',$this->table.'.staff_id')
            ->orderBy($this->table.'.date_remind','DESC');
            if(isset($input['manage_project_id']) && $input['manage_project_id'] != null){
                $oSelect ->  where($this->table.'.manage_project_id',$input['manage_project_id']);
            }
        if (isset($input["date_remind"]) && $input["date_remind"] != null) {
            $arr_filter_remind = explode(" - ", $input["date_remind"]);
                $startCreateTime = Carbon::createFromFormat("d/m/Y", $arr_filter_remind[0])->format("Y-m-d");
                $endCreateTime = Carbon::createFromFormat("d/m/Y", $arr_filter_remind[1])->format("Y-m-d");
                $oSelect->whereBetween("{$this->table}.date_remind", [$startCreateTime . " 00:00:00", $endCreateTime . " 23:59:59"]);

        }
        if (isset($input["created_at"]) && $input["created_at"] != null) {
            $arr_filter_create = explode(" - ", $input["created_at"]);
            $startCreateTime = Carbon::createFromFormat("d/m/Y", $arr_filter_create[0])->format("Y-m-d");
            $endCreateTime = Carbon::createFromFormat("d/m/Y", $arr_filter_create[1])->format("Y-m-d");
            $oSelect->whereBetween("{$this->table}.created_at", [$startCreateTime . " 00:00:00", $endCreateTime . " 23:59:59"]);

        }
        $page = (int)($input["page"] ?? 1);
        return $oSelect->paginate(PAGING_ITEM_PER_PAGE, $columns = ["*"], $pageName = "page", $page);
    }
}