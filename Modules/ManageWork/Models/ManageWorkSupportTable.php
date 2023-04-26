<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 26/05/2021
 * Time: 14:37
 */

namespace Modules\ManageWork\Models;


use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ManageWorkSupportTable extends Model
{
    protected $table = "manage_work_support";
    protected $primaryKey = "manage_work_support_id";

    /**
     * Xoá danh sách nhân viên liên quan theo công việc
     * @param $manage_work_id
     * @return mixed
     */
    public function deleteSupportByWork($manage_work_id){
        return $this
            ->where('manage_work_id',$manage_work_id)
            ->delete();
    }

    /**
     * Thêm nhân viên liên quan
     * @param $data
     */
    public function addStaffSupport($data){
        return $this->insert($data);
    }

    /**
     * lấy danh sách nhân viên liên quan theo công việc
     * @param $manage_work_id
     */
    public function getListStaffByWork($manage_work_id){
        return $this
            ->select(
                'staffs.staff_id',
                'staffs.full_name as staff_name',
                'staffs.email',
                'staffs.staff_avatar'
            )
            ->join('staffs','staffs.staff_id',$this->table.'.staff_id')
            ->where('manage_work_id',$manage_work_id)
            ->get();
    }

    /**
     * Lấy danh sách nhân viên hỗ trợ
     * @param $manageWorkId
     */
    public function getListSupport($manageWorkId){
        $oSelect = $this
            ->select($this->table.'.staff_id','staffs.full_name as staff_name', 'manage_work_id')
            ->join('staffs','staffs.staff_id',$this->table.'.staff_id');
        if(is_array($manageWorkId)){
            $oSelect->whereIn($this->table.'.manage_work_id',$manageWorkId);
        } else {
            $oSelect->where($this->table.'.manage_work_id',$manageWorkId);
        }

        return$oSelect->get();
    }
}