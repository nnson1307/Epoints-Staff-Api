<?php
namespace Modules\Service\Models;
use Illuminate\Database\Eloquent\Model;

class PromotionObjectApplyTable extends Model
{
    protected $table = "promotion_object_apply";
    protected $primaryKey = "promotion_object_apply_id";
    protected $fillable = [
        "promotion_object_apply_id",
        "promotion_id",
        "promotion_code",
        "object_type",
        "object_id",
        "object_code",
        "created_at",
        "updated_at"
    ];

    /**
     * Thêm đối tượng áp dụng
     *
     * @param array $data
     * @return mixed
     */
    public function add(array $data)
    {
        return $this->create($data);
    }

    /**
     * Get promotion object apply
     *
     * @param $promotionCode
     * @return mixed
     */
    public function getObjectApplyByPromotion($promotionCode)
    {
        return $this
            ->select(
                "promotion_id",
                "promotion_code",
                "object_type",
                "object_id",
                "object_code"
            )
            ->where("promotion_code", $promotionCode)
            ->get();
    }

    /**
     * Remove object apply
     *
     * @param $promotionCode
     * @return mixed
     */
    public function removeObjectApply($promotionCode)
    {
        return $this->where("promotion_code", $promotionCode)->delete();
    }

    /**
     * Lấy đối tượng áp dụng by promotion_code, object_id
     *
     * @param $promotionCode
     * @param $objectId
     * @return mixed
     */
    public function getApplyByObjectId($promotionCode, $objectId)
    {
        return $this
            ->select(
                "promotion_id",
                "promotion_code",
                "object_type",
                "object_id",
                "object_code"
            )
            ->where("promotion_code", $promotionCode)
            ->where("object_id", $objectId)
            ->first();
    }
}