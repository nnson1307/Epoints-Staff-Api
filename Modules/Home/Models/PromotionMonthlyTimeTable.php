<?php
/**
 * Created by PhpStorm
 * User: Mr Son
 * Date: 7/8/2020
 * Time: 3:02 PM
 */

namespace Modules\Home\Models;


use Illuminate\Database\Eloquent\Model;

class PromotionMonthlyTimeTable extends Model
{
    protected $table = "promotion_monthly_time";
    protected $primaryKey = "promotion_monthly_id";
    protected $fillable = [
        "promotion_monthly_id",
        "promotion_id",
        "promotion_code",
        "run_date",
        "start_time",
        "end_time",
        "created_by",
        "updated_by",
        "created_at",
        "updated_at"
    ];

    /**
     * Thêm promotion monthly time
     *
     * @param array $data
     * @return mixed
     */
    public function add(array $data)
    {
        return $this->create($data);
    }

    /**
     * Get monthly
     *
     * @param $promotionCode
     * @return mixed
     */
    public function getMonthlyByPromotion($promotionCode)
    {
        return $this->where("promotion_code", $promotionCode)->get();
    }

    /**
     * Remove monthly
     *
     * @param $promotionCode
     * @return mixed
     */
    public function removeMonthly($promotionCode)
    {
        return $this->where("promotion_code", $promotionCode)->delete();
    }
}