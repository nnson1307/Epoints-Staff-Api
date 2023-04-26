<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 25/05/2021
 * Time: 15:00
 */

namespace Modules\Order\Models;


use Illuminate\Database\Eloquent\Model;

class DeliveryHistoryTable extends Model
{
    protected $table = "delivery_history";
    protected $primaryKey = "delivery_history_id";
    protected $fillable = [
        "delivery_history_id",
        "delivery_id",
        "transport_id",
        "transport_code",
        "delivery_staff",
        "delivery_start",
        "delivery_end",
        "contact_phone",
        "contact_name",
        "contact_address",
        "created_by",
        "updated_by",
        "created_at",
        "updated_at",
        "amount",
        "verified_payment",
        "verified_by",
        "status",
        "note",
        "time_ship"
    ];

    /**
     * Xóa tất cả lịch sử giao hàng bằng delivery_id
     *
     * @param $deliveryId
     * @return mixed
     */
    public function removeAll($deliveryId)
    {
        return $this->where("delivery_id", $deliveryId)->update(['status' => 'cancel']);
    }

    /**
     * Xóa tất cả lịch sử giao hàng bằng order_id
     *
     * @param $orderId
     * @return mixed
     */
    public function removeAllByOrder($orderId)
    {
        return $this
            ->join("deliveries", "deliveries.delivery_id", "=", "delivery_history.delivery_id")
            ->where("deliveries.order_id", $orderId)
            ->update(['status' => 'cancel']);
    }

    /**
     * Chỉnh sửa phiếu giao hàng
     *
     * @param array $data
     * @param $delivery_history_id
     * @return mixed
     */
    public function edit(array $data, $delivery_history_id)
    {
        return $this->where("delivery_history_id", $delivery_history_id)->update($data);
    }

    /**
     * Lấy phiếu giao hàng bằng order_id
     *
     * @param $orderId
     * @return mixed
     */
    public function getHistoryByOrder($orderId)
    {
        return $this
            ->select(
                "{$this->table}.delivery_history_id",
                "{$this->table}.status"
            )
            ->join("deliveries", "deliveries.delivery_id", "=", "delivery_history.delivery_id")
            ->where("deliveries.order_id", $orderId)
            ->get();
    }
}