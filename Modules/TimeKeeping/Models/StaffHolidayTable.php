<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 18/10/2022
 * Time: 11:59
 */

namespace Modules\TimeKeeping\Models;


use Illuminate\Database\Eloquent\Model;

class StaffHolidayTable extends Model
{
    protected $table = "staff_holiday";
    protected $primaryKey = "staff_holiday_id";

    /**
     * Lấy thông tin ngày lễ
     *
     * @return mixed
     */
    public function getHoliday()
    {
        return $this
            ->select(
                "staff_holiday_id",
                "staff_holiday_title",
                "staff_holiday_start_date",
                "staff_holiday_end_date",
                "staff_holiday_number"
            )
            ->get();
    }

    /**
     * Lấy thông tin ngày lễ
     *
     * @param $day
     * @return mixed
     */
    public function checkDayInHoliday($day)
    {
        return $this
            ->select(
                "staff_holiday_id",
                "staff_holiday_title",
                "staff_holiday_start_date",
                "staff_holiday_end_date",
                "staff_holiday_number"
            )
            ->where("staff_holiday_start_date", "<=", $day)
            ->where("staff_holiday_end_date", ">=", $day)
            ->get();
    }
}