<?php
/**
 * Created by PhpStorm
 * User: PhongDT
 */

namespace Modules\TimeOffDays\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class TimeOffDaysConfigApproveTable extends Model
{
    protected $table = "time_off_days_config_approve";
    protected $primaryKey = "time_off_days_config_approve_id";
    protected $fillable = [
        "time_off_days_config_approve_id",
        "time_off_type_id",
        "staff_title_id",
        "time_off_days_config_approve_level",
        "created_at",
        "updated_at",
        "created_by",
        "updated_by",
    ];

    /**
     * Get danh sách người duyệt
     *
     * @param array $data
     * @return mixed
     */

    public function getLists($data = []){
        $oSelect = $this
            ->select(
                's.staff_id',
                's.full_name',
                's.staff_avatar', 
                'st.staff_title_name as staff_title', 
            )
            ->leftJoin("staffs as s", "s.staff_title_id", "=", "{$this->table}.staff_title_id")
            ->leftJoin("staff_title as st", "st.staff_title_id", "=", "s.staff_title_id");
        if (isset($data['time_off_type_id'])) {
            $id = $data['time_off_type_id'];
            $oSelect->where($this->table.".time_off_type_id", "=",  $id);
        }
        $oSelect->where("s.department_id", "=",  Auth()->user()->department_id ?? 1);

        return $oSelect->get();
    }

    /**
     * Thêm đơn hàng
     *
     * @param array $data
     * @return mixed
     */
    public function add(array $data)
    {
        $add = $this->create($data);
        return $add->time_off_days_config_approve_id;
    }

}