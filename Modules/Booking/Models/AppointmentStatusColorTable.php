<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 23/09/2022
 * Time: 13:56
 */

namespace Modules\Booking\Models;


use Illuminate\Database\Eloquent\Model;

class AppointmentStatusColorTable extends Model
{
    protected $table = "appointment_status_color";
    protected $primaryKey = "appointment_status_color_id";

    /**
     * Lấy màu trạng thái LH
     *
     * @return mixed
     */
    public function getStatusColor()
    {
        return $this->get();
    }
}