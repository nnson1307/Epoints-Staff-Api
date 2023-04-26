<?php
/**
 * Created by PhpStorm
 * User: PhongDT
 */

namespace Modules\TimeOffDays\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
class TimeOffDaysTotalTable extends Model
{
    protected $table = "time_off_days_total";
    protected $primaryKey = "time_off_days_total_id";
    protected $fillable = [
        "time_off_days_total_id",
        "time_off_type_id",
        "time_off_days_number",
        "created_at",
        "updated_at",
        "created_by",
        "updated_by",
    ];

    /**
     * Get danh sách loại ngày phép
     *
     * @param array $data
     * @return mixed
     */

    public function getLists($id){
        $oSelect = $this
            ->select(
                $this->table.'.time_off_days_total_id',
                $this->table.'.time_off_type_id',
                $this->table.'.time_off_days_number',
                'tot.time_off_type_name',
            
            )
            ->leftJoin("time_off_type as tot", "tot.time_off_type_id", "=", "{$this->table}.time_off_type_id")
            ->where("{$this->table}.staff_id", "=", Auth()->id())    
            ->where("{$this->table}.time_off_type_id", "=",  $id)->get();

        return $oSelect;

    }

    /**
    * Get danh sách loại ngày phép
    *
    * @param array $data
    * @return mixed
    */

    public function checkValidTotal($staffId, $typeId){
        $oSelect = $this
            ->select(
                $this->table.'.time_off_days_total_id',
                $this->table.'.time_off_type_id',
                $this->table.'.time_off_days_number',
            )
            ->where("{$this->table}.staff_id", "=", $staffId)    
            ->where("{$this->table}.time_off_type_id", "=",  $typeId)->first();
        return $oSelect;

    }

    /**
     * Cập nhật
     *
     * @param $data
     * @param $id
     * @return mixed
     */
    public function edit($data, $staffId, $typeOffDaysId)
    {
        return $this->where("time_off_type_id", $typeOffDaysId)->where("staff_id", $staffId)->update($data);
    }

}