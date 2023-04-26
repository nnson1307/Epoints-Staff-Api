<?php
/**
 * Created by PhpStorm
 * User: Mr Son
 * Date: 7/14/2020
 * Time: 3:58 PM
 */

namespace Modules\Home\Models;


use Illuminate\Database\Eloquent\Model;

class PromotionDetailTable extends Model
{
    protected $table = "promotion_details";
    protected $primaryKey = "promotion_detail_id";
    protected $fillable = [
        "promotion_detail_id",
        "promotion_id",
        "promotion_code",
        "object_type",
        "object_id",
        "object_name",
        "object_code",
        "base_price",
        "promotion_price",
        "quantity_buy",
        "quantity_gift",
        "gift_object_type",
        "gift_object_id",
        "gift_object_name",
        "gift_object_code",
        "is_actived",
        "created_at",
        "updated_at"
    ];

    const IS_ACTIVE = 1;
    const NOT_DELETE = 0;

    /**
     * Lấy thông tin khuyến mãi của sp, dv, thẻ dv
     *
     * @param $objectType
     * @param $objectCode
     * @param $promotionType
     * @param $currentDate
     * @return mixed
     */
    public function getPromotionDetail($objectType, $objectCode, $promotionType = null, $currentDate = null)
    {
        $ds =  $this
            ->select(
                "promotion_master.promotion_id",
                "promotion_master.promotion_code",
                "promotion_master.start_date",
                "promotion_master.end_date",
                "promotion_master.is_time_campaign",
                "promotion_master.time_type",
                "promotion_master.branch_apply",
                "promotion_master.promotion_type",
                "promotion_master.promotion_type_discount",
                "promotion_master.promotion_type_discount_value",
                "promotion_master.order_source",
                "promotion_master.quota",
                "promotion_master.quota_use",
                "promotion_master.promotion_apply_to",
                "{$this->table}.object_type",
                "{$this->table}.object_id",
                "{$this->table}.object_name",
                "{$this->table}.object_code",
                "{$this->table}.base_price",
                "{$this->table}.promotion_price",
                "{$this->table}.quantity_buy",
                "{$this->table}.quantity_gift",
                "{$this->table}.gift_object_type",
                "{$this->table}.gift_object_id",
                "{$this->table}.gift_object_name",
                "{$this->table}.gift_object_code"
            )
            ->join("promotion_master", "promotion_master.promotion_code", "=", "{$this->table}.promotion_code")
            ->where("{$this->table}.object_type", $objectType)
            ->where("{$this->table}.object_code", $objectCode)
            ->where(function ($query) use ($currentDate) {
                $query->where("promotion_master.start_date", "<=", $currentDate)
                    ->where("promotion_master.end_date", ">=", $currentDate);
            })
            ->where("promotion_master.is_actived", self::IS_ACTIVE)
            ->where("promotion_master.is_deleted", self::NOT_DELETE)
            ->where("{$this->table}.is_actived", self::IS_ACTIVE);

        return $ds->get();
    }
}