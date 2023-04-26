<?php
namespace Modules\TimeKeeping\Models;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class CheckOutLogTable extends Model
{
    const ADMIN_CREATED_TYPE = "admin";
    const STAFF_CREATED_TYPE = "staff";

    const OK_STATUS = "ok";
    const NOT_OK_STATUS = "not_ok";

    protected $table = 'sf_check_out_log';
    protected $primaryKey = 'check_out_log_id';
    protected $fillable = [
        'check_out_log_id', 'time_working_staff_id', 'staff_id', 'branch_id', 'shift_id', 'check_out_day', 'check_out_time',
        'status', 'reason', 'created_type', 'created_at', 'updated_at', 'created_by', 'wifi_name', 'wifi_ip', 'request_ip',
        'timekeeping_type', 'latitude', 'longitude', 'radius'
    ];

    /**
     * Ghi log checkin
     * @param int|null $id
     * @param $timeWorking
     * @param Carbon $currentDate
     * @param $status
     * @return mixed
     */
    public function checkOut($data)
    {
        return $this->create($data);
    }


}