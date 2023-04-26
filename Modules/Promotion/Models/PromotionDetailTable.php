<?php


namespace Modules\Promotion\Models;


use Illuminate\Database\Eloquent\Model;

class PromotionDetailTable extends Model
{
    protected $table = "promotion_details";
    protected $primaryKey = "promotion_detail_id";

    const IS_ACTIVE = 1;

    public function getListByPromotionCode($promotionCode)
    {
        return $this
            ->select(
                'object_type',
                'object_id',
                'object_name',
                'object_code',
                'base_price',
                'promotion_price',
                'quantity_buy',
                'quantity_gift'
            )
            ->where("is_actived", self::IS_ACTIVE)
            ->where("promotion_code", $promotionCode)
            ->orderBy("promotion_detail_id", "desc")
            ->get();
    }
}