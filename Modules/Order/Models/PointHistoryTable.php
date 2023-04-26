<?php
/**
 * Created by PhpStorm
 * User: Mr Son
 * Date: 4/27/2020
 * Time: 10:08 AM
 */

namespace Modules\Order\Models;


use Illuminate\Database\Eloquent\Model;

class PointHistoryTable extends Model
{
    protected $table = "point_history";
    protected $primaryKey = "point_history_id";
    protected $fillable = [
        "point_history_id",
        "customer_id",
        "order_id",
        "point",
        "type",
        "point_description",
        "object_id",
        "is_deleted",
        "accepted_ranking",
        "created_at",
        "updated_at",
        "created_by"
    ];

    const PLUS = 'plus';

    /**
     * Lấy ds cộng điểm từ đơn hàng
     *
     * @param $customerId
     * @return mixed
     */
    public function getPointOrder($customerId)
    {
        return $this
            ->select(
                "point",
                "point_description",
                "object_id"
            )
            ->where("customer_id", $customerId)
            ->where("type", self::PLUS)
            ->whereIn("point_description", ["order_app", "order_direct"])
            ->get();
    }

    /**
     * Lấy lịch sử tích điểm của đơn hàng
     *
     * @param $customerId
     * @param $objectId
     * @return mixed
     */
    public function getPointBookingByAppointment($customerId, $objectId)
    {
        return $this
            ->select(
                "point",
                "point_description",
                "object_id",
                "point_history_id"
            )
            ->where("customer_id", $customerId)
            ->where("type", self::PLUS)
            ->where("object_id", $objectId)
            ->whereIn("point_description", ["order_app", "order_direct"])
            ->first();
    }

    /**
     * Thêm lịch sử tích điểm
     *
     * @param array $data
     * @return mixed
     */
    public function add(array $data)
    {
        return $this->create($data)->point_history_id;
    }

    /**
     * Chỉnh sửa lịch sử tích điểm
     *
     * @param array $data
     * @param $pointHistoryId
     * @return mixed
     */
    public function edit(array $data, $pointHistoryId)
    {
        return $this->where("point_history_id", $pointHistoryId)->update($data);
    }
}