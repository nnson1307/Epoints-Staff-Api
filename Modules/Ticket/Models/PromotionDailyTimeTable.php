<?php
namespace Modules\Ticket\Models;
use Illuminate\Database\Eloquent\Model;

class PromotionDailyTimeTable extends Model
{
    protected $table = "promotion_daily_time";
    protected $primaryKey = "promotion_daily_id";
    protected $fillable = [
        "promotion_daily_id",
        "promotion_id",
        "promotion_code",
        "start_time",
        "end_time",
        "created_by",
        "updated_by",
        "created_at",
        "updated_at"
    ];

    /**
     * Thêm promotion daily
     *
     * @param array $data
     * @return mixed
     */
    public function add(array $data)
    {
        return $this->create($data);
    }

    /**
     * Get promotion daily
     *
     * @param $promotionCode
     * @return mixed
     */
    public function getDailyByPromotion($promotionCode)
    {
        return $this
            ->select(
                "promotion_id",
                "promotion_code",
                "start_time",
                "end_time"
            )
            ->where("promotion_code", $promotionCode)
            ->first();
    }

    /**
     * Remove promotion daily
     *
     * @param $promotionCode
     * @return mixed
     */
    public function removeDaily($promotionCode)
    {
        return $this->where("promotion_code", $promotionCode)->delete();
    }
}