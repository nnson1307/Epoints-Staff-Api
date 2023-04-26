<?php

/**
 * Created by PhpStorm
 * User: PhongDT
 */

namespace Modules\TimeOffDays\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class TimeOffTypeOptionTable extends Model
{
    protected $table = "time_off_type_option";
    protected $primaryKey = "time_off_type_option_id";
    protected $fillable = [
        "time_off_type_option_id",
        "time_off_type_code",
        "time_off_type_option_key",
        "time_off_type_option_value",
        "time_off_type_option_position",
        "is_status",
        "updated_at",
        "created_at",
        "updated_by",
        "created_by",
    ];

    /**
     * Get danh sách người duyệt
     *
     * @param array $data
     * @return mixed
     */

    public function getLists($data = [])
    {
        $oSelect = $this
            ->select(
                "{$this->table}.time_off_type_option_id",
                "{$this->table}.time_off_type_code",
                "{$this->table}.time_off_type_option_key",
                "{$this->table}.time_off_type_option_value",
                "{$this->table}.time_off_type_option_position"
            )
            ->join('time_off_type', 'time_off_type.time_off_type_code', "{$this->table}.time_off_type_code")
            ->where("time_off_type.time_off_type_id", $data['time_off_type_id'])
            ->where(function ($oSelect) {
                $oSelect
                    ->where("$this->table.time_off_type_option_key", 'approve_level_1')
                    ->orWhere("$this->table.time_off_type_option_key", 'approve_level_2')
                    ->orWhere("$this->table.time_off_type_option_key", 'approve_level_3');
            });
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
