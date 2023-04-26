<?php
/**
 * Created by PhpStorm   .
 * User: Mr Son
 * Date: 2020-03-25
 * Time: 10:19 AM
 * @author SonDepTrai
 */

namespace Modules\Order\Models;


use Illuminate\Database\Eloquent\Model;

class DeliveryTable extends Model
{
    protected $table = "deliveries";
    protected $primaryKey = "delivery_id";
    protected $fillable = [
        "delivery_id",
        "order_id",
        "customer_id",
        "created_by",
        "updated_by",
        "created_at",
        "updated_at",
        "contact_name",
        "contact_phone",
        "contact_address",
        "is_deleted",
        "is_actived",
        "delivery_status",
        "time_order"
    ];

    /**
     * Thêm đơn hàng cần giao
     *
     * @param array $data
     * @return mixed
     */
    public function add(array $data)
    {
        $add = $this->create($data);
        return $add->delivery_id;
    }

    /**
     * Chỉnh sửa đơn hàng cần giao
     *
     * @param array $data
     * @param $orderId
     * @return mixed
     */
    public function edit(array $data, $orderId)
    {
        return $this->where("order_id", $orderId)->update($data);
    }

    public function getDeliveryByOrderId($orderId)
    {
        return $this
            ->select(
                "delivery_id",
                "order_id",
                "customer_id",
                "created_by",
                "updated_by",
                "created_at",
                "updated_at",
                "contact_name",
                "contact_phone",
                "contact_address",
                "is_deleted",
                "is_actived",
                "delivery_status",
                "time_order"
            )
            ->where("is_deleted", 0)
            ->where("order_id", $orderId)
            ->first();
    }
}