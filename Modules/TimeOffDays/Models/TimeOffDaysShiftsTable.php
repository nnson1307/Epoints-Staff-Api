<?php
/**
 * Created by PhpStorm
 * User: PhongDT
 */

namespace Modules\TimeOffDays\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
class TimeOffDaysShiftsTable extends Model
{
    protected $table = "time_off_days_shift";
    protected $primaryKey = "time_off_days_shift_id";
    protected $fillable = [
        "time_off_days_shift_id",
        "time_off_days_id",
        "time_working_staff_id",
        "is_approve",
        "created_at",
        "updated_at",
        "created_by",
        "updated_by",
        "created_days",
        "created_months",
        "created_years",
        "time_off_type_id",
        "staff_id"
    ];

    /**
     * Get danh sách ngày phép
     *
     * @param array $data
     * @return mixed
     */

    public function getLists($data = []){
        $oSelect = $this
            ->select(
                $this->table.'.time_off_days_shift_id',
                $this->table.'.time_off_days_id',
                $this->table.'.time_working_staff_id',
                'sf.shift_name',
                'stws.working_time',
                'stws.working_day',
            )
            
            ->leftJoin("sf_time_working_staffs as stws", "stws.time_working_staff_id", "=", "{$this->table}.time_working_staff_id")
            ->leftJoin("sf_shifts as sf", "sf.shift_id", "=", "stws.shift_id");

            if (isset($data['time_off_days_id'])) {
                $id = $data['time_off_days_id'];
                $oSelect->where($this->table.".time_off_days_id", "=",  $id);
            }
        return $oSelect->get();
        // get số trang
        // $page = (int)($data['page'] ?? 1);
        // return $oSelect->paginate(PAGING_ITEM_PER_PAGE, $columns = ["*"], $pageName = 'page', $page);
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
        return $add->time_off_days_shift_id;
    }

/**
     * Cập nhật loại thông tin kèm theo
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
     * Xóa tất cả file
     *
     * @param $daysId
     * @return mixed
     */
    public function remove($daysId)
    {
        return $this->where("time_off_days_id", $daysId)->delete();
    }

    public function getNumberDaysOff($data){
        $staffIf = $data['staff_id']; 
        $timeOffTypeId = $data['time_off_type_id'];
        $month = $data['month'];
        $years = $data['years'];
        $monthReset = $data['month_reset'];
        $oSelect = $this
            ->select(
                DB::raw("COUNT({$this->table}.time_off_days_shift_id) as total")
            )->where("{$this->table}.is_approve", 1)
            ->where("{$this->table}.time_off_type_id", $timeOffTypeId)
            ->where("{$this->table}.staff_id", $staffIf)
            ->where(function ($oSelect) use ($month, $years, $monthReset){
                if($monthReset == 1){
                    $oSelect->where("{$this->table}.created_months",  $month)
                    ->where("{$this->table}.created_years",  $years);
                }else {
                    $oSelect->where("{$this->table}.created_years",  $years);
                }
            });
        return $oSelect->first();
    }

    public function getListsByDaysOff($daysOffId){
        $oSelect = $this
            ->select(
                $this->table.'.time_off_days_shift_id',
                $this->table.'.time_off_days_id',
                $this->table.'.time_working_staff_id',
                "tm.time_off_type_code",
                "tm.is_deducted"
            )->where($this->table.".time_off_days_id", "=",  $daysOffId)
            ->join("time_off_type as tm", "tm.time_off_type_id" , "=" , "{$this->table}.time_off_type_id");
        return $oSelect->get();
    }
}