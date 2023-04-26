<?php
/**
 * Created by PhpStorm   .
 * User: Mr Son
 * Date: 2020-02-17
 * Time: 2:46 PM
 * @author SonDepTrai
 */

namespace Modules\Booking\Models;


use Illuminate\Database\Eloquent\Model;

class TimeWorkingTable extends Model
{
    protected $table = 'time_working';
    protected $primaryKey = 'id';

    /**
     * Lấy thời gian làm việc trong tuần
     *
     * @return mixed
     */
    public function getTimes()
    {
        return $this
            ->select(
                "id",
                "eng_name",
                "vi_name",
                "is_actived",
                "start_time",
                "end_time"
            )
            ->get();
    }

    /**
     * Lấy thời gian làm việc theo eng name
     *
     * @param $engName
     * @return mixed
     */
    public function getTimeByEngName($engName)
    {
        return $this
            ->select(
                "id",
                "eng_name",
                "vi_name",
                "is_actived",
                "start_time",
                "end_time"
            )
            ->where("eng_name", $engName)
            ->first();
    }
}