<?php
/**
 * Created by PhpStorm
 * User: Mr Son
 * Date: 7/16/2020
 * Time: 2:42 PM
 */

namespace Modules\Order\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class PromotionLogTable extends Model
{
    protected $table = "promotion_logs";
    protected $primaryKey = "promotion_log_id";
    protected $fillable = [
        "promotion_log_id",
        "promotion_id",
        "promotion_code",
        "start_date",
        "end_date",
        "order_id",
        "order_code",
        "object_type",
        "object_id",
        "object_code",
        "quantity",
        "base_price",
        "promotion_price",
        "gift_object_type",
        "gift_object_id",
        "gift_object_code",
        "quantity_gift",
        "created_by",
        "updated_by",
        "created_at",
        "updated_at"
    ];

    /**
     * Thêm promotion log
     *
     * @param array $data
     * @return mixed
     */
    public function add(array $data)
    {
        return $this->create($data);
    }

    /**
     * Xóa promotion log by order_code
     *
     * @param $orderId
     * @return mixed
     */
    public function removeByOrder($orderId)
    {
        return $this->where("order_id", $orderId)->delete();
    }

    /**
     * Lấy quota promotion
     *
     * @param $orderId
     * @return mixed
     */
    public function getQuotaPromotion($orderId)
    {
        return $this
            ->select(
                "promotion_code",
                DB::raw("SUM(quantity_gift) as quantity_gift")
            )
            ->where("order_id", $orderId)
            ->whereNotNull("quantity_gift")
            ->groupBy("promotion_code")
            ->get();
    }
}