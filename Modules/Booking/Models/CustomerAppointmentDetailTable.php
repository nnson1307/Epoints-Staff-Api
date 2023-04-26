<?php

/**
 * Created by PhpStorm   .
 * User: Mr Son
 * Date: 2020-01-08
 * Time: 10:38 AM
 * @author SonDepTrai
 */

namespace Modules\Booking\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
        'customer_order',
        'price',
        'object_type',
        'object_id',
        'object_code',
        'object_name'
    ];

    protected $casts = [
        "price" => 'float'
    ];

    const NOT_DELETE = 0;
    const IS_ACTIVE = 1;
    /**
     * Chi tiết lịch hẹn
     *
     * @param $appointmentId
     * @param $customerId
     * @return mixed
     */
    public function getDetailAppointment($appointmentId, $customerId)
    {
        $imageDefault = 'http://' . request()->getHttpHost() . '/static/images/service.png';
        $imageMemberCard = 'http://' . request()->getHttpHost() . '/static/images/service-card.png';

        return $this
            ->select(
                DB::raw("(CASE
                    WHEN  {$this->table}.object_type = 'service' && {$this->table}.object_name IS NOT NULL THEN {$this->table}.object_name
                    WHEN  {$this->table}.object_type = 'member_card' && {$this->table}.object_name IS NOT NULL THEN {$this->table}.object_name
                    
                    WHEN  {$this->table}.object_type = 'service' && {$this->table}.object_name IS NULL THEN services.service_name
                    WHEN  {$this->table}.object_type = 'member_card' && {$this->table}.object_name IS NULL THEN service_cards.name
                    
                    END
                ) as service_name"),
                "staffs.full_name as staff_name",
                "rooms.name as room_name",
                "{$this->table}.customer_order as customer_number",
                "services.time",
                DB::raw("(CASE
                    WHEN  {$this->table}.object_type = 'service' && services.service_avatar IS NOT NULL THEN services.service_avatar
                    WHEN  {$this->table}.object_type = 'member_card' && service_cards.image IS NOT NULL THEN service_cards.image
                    
                    WHEN  {$this->table}.object_type = 'service' && services.service_avatar IS NULL THEN '{$imageDefault}'
                    WHEN  {$this->table}.object_type = 'member_card' && service_cards.image IS NULL THEN '{$imageMemberCard}'
                         
                    END
                ) as service_avatar"),
                "{$this->table}.price",
                "{$this->table}.service_id",
                "{$this->table}.object_type",
                "{$this->table}.object_id",
                "{$this->table}.object_code",
                "staffs.staff_avatar",
                "{$this->table}.staff_id",
                "{$this->table}.room_id"
            )
            ->join("customer_appointments", "customer_appointments.customer_appointment_id", "=", "{$this->table}.customer_appointment_id")
            ->leftJoin("services", "services.service_id", "=", "{$this->table}.service_id")
            ->leftJoin("customer_service_cards", "customer_service_cards.customer_service_card_id", "=", "{$this->table}.object_id")
            ->leftJoin("service_cards", "service_cards.service_card_id", "=", "customer_service_cards.service_card_id")
            ->leftJoin("staffs", "staffs.staff_id", "=", "{$this->table}.staff_id")
            ->leftJoin("rooms", function ($join) {
                $join->on("rooms.room_id", "=", "{$this->table}.room_id")
                    ->where("rooms.is_actived", self::IS_ACTIVE)
                    ->where("rooms.is_deleted", self::NOT_DELETE);
            })
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

    /**
     * Lấy thông tin chi tiết lịch hẹn gửi sms
     *
     * @param $appointmentId
     * @return mixed
     */
    public function getDetail($appointmentId)
    {
        return $this
            ->select(
                "{$this->table}.customer_appointment_detail_id",
                "{$this->table}.service_id",
                "{$this->table}.staff_id",
                "{$this->table}.room_id",
                "{$this->table}.price",
                "{$this->table}.object_type",
                "{$this->table}.object_id",
                "{$this->table}.object_code",
                "{$this->table}.is_check_promotion",
                DB::raw("(CASE
                    WHEN  {$this->table}.object_type = 'service' && {$this->table}.object_name IS NOT NULL THEN {$this->table}.object_name
                    WHEN  {$this->table}.object_type = 'member_card' && {$this->table}.object_name IS NOT NULL THEN {$this->table}.object_name
                    
                    WHEN  {$this->table}.object_type = 'service' && {$this->table}.object_name IS NULL THEN services.service_name
                    WHEN  {$this->table}.object_type = 'member_card' && {$this->table}.object_name IS NULL THEN service_cards.name
                   
                    END
                ) as object_name"),
                "staffs.full_name as staff_name",
                "rooms.name as room_name"
            )
            ->leftJoin("services", "services.service_id", "=", "{$this->table}.service_id")

            ->leftJoin("customer_service_cards", "customer_service_cards.customer_service_card_id", "=", "{$this->table}.object_id")
            ->leftJoin("service_cards", "service_cards.service_card_id", "=", "customer_service_cards.service_card_id")

            ->leftJoin("staffs", "staffs.staff_id", "=", "{$this->table}.staff_id")
            ->leftJoin("rooms", "rooms.room_id", "=", "{$this->table}.room_id")
            ->where("{$this->table}.customer_appointment_id", $appointmentId)
            ->where("{$this->table}.is_deleted", self::NOT_DELETE)
            ->get();
    }
}
