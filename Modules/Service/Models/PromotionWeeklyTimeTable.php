<?php
namespace Modules\Service\Models;
use Illuminate\Database\Eloquent\Model;

class PromotionWeeklyTimeTable extends Model
{
    protected $table = "promotion_weekly_time";
    protected $primaryKey = "promotion_week_id";
    protected $fillable = [
        "promotion_week_id",
        "promotion_id",
        "promotion_code",
        "default_start_time",
        "default_end_time",
        "is_monday",
        "is_tuesday",
        "is_wednesday",
        "is_thursday",
        "is_friday",
        "is_saturday",
        "is_sunday",
        "is_other_monday",
        "is_other_monday_start_time",
        "is_other_monday_end_time",
        "is_other_tuesday",
        "is_other_tuesday_start_time",
        "is_other_tuesday_end_time",
        "is_other_wednesday",
        "is_other_wednesday_start_time",
        "is_other_wednesday_end_time",
        "is_other_thursday",
        "is_other_thursday_start_time",
        "is_other_thursday_end_time",
        "is_other_friday",
        "is_other_friday_start_time",
        "is_other_friday_end_time",
        "is_other_saturday",
        "is_other_saturday_start_time",
        "is_other_saturday_end_time",
        "is_other_sunday",
        "is_other_sunday_start_time",
        "is_other_sunday_end_time",
        "created_by",
        "updated_by",
        "created_at",
        "updated_at"
    ];

    /**
     * Thêm promotion weekly time
     *
     * @param array $data
     * @return mixed
     */
    public function add(array $data)
    {
        return $this->create($data);
    }

    /**
     * Get promotion weekly
     *
     * @param $promotionCode
     * @return mixed
     */
    public function getWeeklyByPromotion($promotionCode)
    {
        return $this->where("promotion_code", $promotionCode)->first();
    }

    /**
     * Remove promotion weekly
     *
     * @param $promotionCode
     * @return mixed
     */
    public function removeWeekly($promotionCode)
    {
        return $this->where("promotion_code", $promotionCode)->delete();
    }
}