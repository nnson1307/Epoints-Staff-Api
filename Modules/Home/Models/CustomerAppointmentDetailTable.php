<?php
/**
 * Created by PhpStorm   .
 * User: Mr Son
 * Date: 2020-01-08
 * Time: 10:38 AM
 * @author SonDepTrai
 */

namespace Modules\Home\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class CustomerAppointmentDetailTable extends Model
{
    protected $table = 'customer_appointment_details';
    protected $primaryKey = 'customer_appointment_detail_id';
    protected $fillable = [
        'customer_appointment_detail_id',
        'customer_appointment_id',
        'service_id',
        'staff_id',
        'room_id',
        'created_at',
        'updated_at',
        'created_by',
        'updated_by',
        'is_deleted',
        'customer_order'
    ];

    /**
     * Chi tiết lịch hẹn
     *
     * @param $appointmentId
     * @param $customerId
     * @return mixed
     */
    public function getDetailAppointment($appointmentId, $customerId)
    {
        return $this
            ->select(
                "services.service_name",
                "staffs.full_name as staff_name",
                "rooms.name as room_name",
                "{$this->table}.customer_order as customer_number"
            )
            ->join("customer_appointments", "customer_appointments.customer_appointment_id", "=", "{$this->table}.customer_appointment_id")
            ->leftJoin("services", "services.service_id", "=", "{$this->table}.service_id")
            ->leftJoin("staffs", "staffs.staff_id", "=", "{$this->table}.staff_id")
            ->leftJoin("rooms", "rooms.room_id", "=", "{$this->table}.room_id")
            ->where("customer_appointments.customer_id", $customerId)
            ->where("{$this->table}.customer_appointment_id", $appointmentId)
            ->get();

    }

    /**
     * Thêm chi tiết lịch hẹn
     *
     * @param array $data
     * @return mixed
     */
    public function add(array $data)
    {
        $add = $this->create($data);
        return $add->customer_appointment_detail_id;
    }

    /**
     * Xóa tất cả chi tiết của lịch hẹn
     *
     * @param $appointmentId
     * @return mixed
     */
    public function remove($appointmentId)
    {
        return $this->where("customer_appointment_id", $appointmentId)->delete();
    }
}