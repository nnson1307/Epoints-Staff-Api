<?php

/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 24/08/2022
 * Time: 16:11
 */

namespace Modules\Booking\Models;

use Illuminate\Support\Facades\DB;

use Illuminate\Database\Eloquent\Model;

class AppointmentSourceTable extends Model
{
    protected $table = "appointment_source";
    protected $primaryKey = "appointment_source_id";

    const NOT_DELETED = 0;

    /**
     * Lấy option nguồn lịch hẹn
     *
     * @return mixed
     */
    public function optionSource()
    {
        $ds = $this
            ->select(
                "appointment_source_id",
                "appointment_source_name",
                DB::raw("(CASE
                    WHEN  appointment_source_id = 4 THEN 1
                    ELSE  0 
                    END
                ) as 'default'"),
            )
            ->where("is_deleted", self::NOT_DELETED);
        return  $ds->get();
    }
}