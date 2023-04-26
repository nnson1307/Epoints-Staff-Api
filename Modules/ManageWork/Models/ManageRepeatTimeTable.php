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

class ManageRepeatTimeTable extends Model
{
    protected $table = "manage_repeat_time";
    protected $primaryKey = "manage_repeat_time_id";

    /**
     * Xoá thời gian cấu hình nhắc nhở theo công việc
     * @param $manage_work_id
     * @return mixed
     */
    public function deleteRepeatTime($manage_work_id){
        return $this
            ->where('manage_work_id',$manage_work_id)
            ->delete();
    }

    /**
     * Lấy danh sách ngày theo công việc
     * @param $manage_work_id
     * @return mixed
     */
    public function getListRepeatTime($manage_work_id){
        return $this
            ->select('time')
            ->where('manage_work_id',$manage_work_id)
            ->get();
    }

    /**
     * Tạo ngày lặp lại cho công việc
     * @param $data
     */
    public function createRepeatTime($data){
        return $this->insert($data);
    }
}