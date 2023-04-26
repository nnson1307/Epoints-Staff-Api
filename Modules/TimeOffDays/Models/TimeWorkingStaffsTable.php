<?php
/**
 * Created by PhpStorm
 * User: PhongDT
 */

namespace Modules\TimeOffDays\Models;


use Illuminate\Database\Eloquent\Model;

class TimeWorkingStaffsTable extends Model
{
    protected $table = "sf_time_working_staffs";
    protected $primaryKey = "time_working_staff_id";
    protected $fillable = [
        "time_working_staff_id",
        "work_schedule_id",
        "shift_id",
        "branch_id",
        "staff_id",
        "working_day",
        "working_time",
        "start_working_format_day",
        "start_working_format_week",
        "start_working_format_month",
        "start_working_format_year",
        "working_end_day",
        "working_end_time",
        "timekeeping_coefficient",
        "is_check_in",
        "is_check_out",
        "is_deducted",
        "is_close",
        "is_ot",
        "is_deleted",
        "note",
        "created_at",
        "updated_at",
        "updated_by",
        "is_approve_late",
        "is_approve_soon",
        "approve_late_by",
        "approve_soon_by",
        "check_in_by",
        "check_out_by",
        "time_work",
        "min_time_work",
        "overtime_type",
        "actual_time_work",
        "time_of_days_id"
    ];

    /**
     * Get danh sách ca làm
     *
     * @param array $data
     * @return mixed
     */

    public function getLists($data = []){
        $oSelect = $this
            ->select(
                $this->table.'.time_working_staff_id',
                $this->table.'.working_day',
                'sf_shifts.shift_name',
                
            )
            ->join("sf_shifts", "sf_shifts.shift_id", "=", "{$this->table}.shift_id")
            ->get();
        
        return $oSelect;
    }

     /**
     * Cập nhật 
     *
     * @param $data
     * @param $id
     * @return mixed
     */
    public function edit($data, $id)
    {
        return $this->where("{$this->primaryKey}", $id)->update($data);
    }

    /**
     * Cập nhật 
     *
     * @param $data
     * @param $id
     * @return mixed
     */
    public function removeTimeOffDay($id)
    {
        return $this->where("time_of_days_id", $id)->update(['time_of_days_id' => 0]);
    }

}