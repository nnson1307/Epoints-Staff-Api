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

class ManageHistoryTable extends Model
{
    protected $table = "manage_history";
    protected $primaryKey = "manage_history_id";

    /**
     * Danh sách lịch sử
     */
    public function getListHistory($manage_work_id){
        return $this
            ->select(
                $this->table.'.manage_history_id',
                $this->table.'.staff_id',
                'staffs.full_name as staff_name',
                'staffs.staff_avatar as staff_avatar',
                $this->table.'.message',
                $this->table.'.created_at'
            )
            ->join('staffs','staffs.staff_id',$this->table.'.staff_id')
            ->where($this->table.'.manage_work_id',$manage_work_id)
            ->orderBy($this->table.'.created_at','DESC')
            ->get();
    }

    /**
     * Tạo lịch sử
     * @param $data
     */
    public function createdHistory($data){
        return $this->insert($data);
    }
}