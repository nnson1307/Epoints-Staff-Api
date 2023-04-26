<?php

/**
 * Created by PhpStorm
 * User: PhongDT
 */

namespace Modules\TimeOffDays\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class TimeOffDayTimeTable extends Model
{
    protected $table = "time_off_days_time";
    protected $primaryKey = "time_off_days_time_id";
    protected $fillable = [
        "time_off_days_time_id",
        "time_off_days_time_value",
        "time_off_days_time_unit",
    ];

    /**
     * Get danh sách người duyệt
     *
     * @param array $data
     * @return mixed
     */

    public function getLists($data = [])
    {
        $minute = __('Phút');
        $hours = __('Giờ');
        $oSelect = $this
            ->select(
                $this->table . '.time_off_days_time_id',
                $this->table . '.time_off_days_time_value',
                $this->table . '.time_off_days_time_unit',
                DB::raw("CONCAT(time_off_days_time_value, ' ', CASE WHEN time_off_days_time_unit = 'minute' THEN '{$minute}' ELSE '{$hours}' END) AS time_off_days_time_text")
            );
        return $oSelect->get();
    }

     /**
     * Get danh sách người duyệt
     *
     * @param array $data
     * @return mixed
     */

     public function getOption()
     {
         $minute = __('Phút');
         $hours = __('Giờ');
         $oSelect = $this
             ->select(
                 $this->table . '.time_off_days_time_id as time_id',
                 DB::raw("CONCAT(time_off_days_time_value, ' ', CASE WHEN time_off_days_time_unit = 'minute' THEN '{$minute}' ELSE '{$hours}' END) AS time_name")
             );
         return $oSelect->get();
     }

      /**
     * Get detail
     *
     * @param array $data
     * @return mixed
     */

     public function getDetail($id)
     {
         $minute = __('Phút');
         $hours = __('Giờ');
         $oSelect = $this
             ->select(
                 $this->table . '.time_off_days_time_id as time_id',
                 $this->table . '.time_off_days_time_value',
                $this->table . '.time_off_days_time_unit',
                 DB::raw("CONCAT(time_off_days_time_value, ' ', CASE WHEN time_off_days_time_unit = 'minute' THEN '{$minute}' ELSE '{$hours}' END) AS time_name")
             )
             ->where("{$this->table}.time_off_days_time_id", $id );
         return $oSelect->first();
     }

}
