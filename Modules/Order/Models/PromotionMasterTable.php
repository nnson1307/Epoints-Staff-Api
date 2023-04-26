<?php
/**
 * Created by PhpStorm
 * User: Mr Son
 * Date: 1/12/2021
 * Time: 4:48 PM
 */

namespace Modules\Order\Models;


use Illuminate\Database\Eloquent\Model;

class PromotionMasterTable extends Model
{
    protected $table = "promotion_master";
    protected $primaryKey = "promotion_id";
    protected $fillable = [
        "promotion_id",
        "promotion_code",
        "promotion_name",
        "start_date",
        "end_date",
        "is_actived",
        "is_display",
        "is_time_campaign",
        "time_type",
        "image",
        "branch_apply",
        "is_feature",
        "position_feature",
        "promotion_type",
        "promotion_type_discount",
        "promotion_type_discount_value",
        "order_source",
        "quota",
        "promotion_apply_to",
        "description",
        "description_detail",
        "is_deleted",
        "created_by",
        "updated_by",
        "created_at",
        "updated_at",
        "site_id",
        "quota_use"
    ];

    const IS_ACTIVE = 1;
    const NOT_DELETE = 0;

    /**
     * Lấy thông tin promotion_master
     *
     * @param $promotionCode
     * @return mixed
     */
    public function getInfo($promotionCode)
    {
        return $this
            ->select(
                "promotion_id",
                "promotion_code",
                "start_date",
                "end_date",
                "is_time_campaign",
                "time_type",
                "branch_apply",
                "promotion_type",
                "promotion_type_discount",
                "promotion_type_discount_value",
                "order_source",
                "quota",
                "quota_use",
                "promotion_apply_to"
            )
            ->where("promotion_code", $promotionCode)
            ->where("is_actived", self::IS_ACTIVE)
            ->where("is_deleted", self::NOT_DELETE)
            ->first();
    }

    /**
     * Cập nhật thông tin promotion_master
     *
     * @param array $data
     * @param $promotionCode
     * @return mixed
     */
    public function edit(array $data, $promotionCode)
    {
        return $this->where("promotion_code", $promotionCode)->update($data);
    }
}