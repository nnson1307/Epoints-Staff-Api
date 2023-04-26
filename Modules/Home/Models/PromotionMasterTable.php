<?php
/**
 * Created by PhpStorm
 * User: Mr Son
 * Date: 9/14/2020
 * Time: 10:03 AM
 */

namespace Modules\Home\Models;


use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class PromotionMasterTable extends Model
{
    protected $table = "promotion_master";
    protected $primaryKey = "promotion_id";

    const IS_ACTIVE = 1;
    const IS_DISPLAY = 1;
    const NOT_DELETED = 0;

    /**
     * Lấy ds CTKM khi search home page
     *
     * @param $filter
     * @return mixed
     */
    public function getPromotionSearch($filter)
    {
        $now = Carbon::now()->format('Y-m-d H:i:s');

        $ds = $this
            ->select(
                "promotion_code",
                "promotion_name",
                "start_date",
                "end_date",
                "description",
                "image"
            )
            ->where("is_actived", self::IS_ACTIVE)
            ->where("is_display", self::IS_DISPLAY)
            ->where("is_deleted", self::NOT_DELETED)
            ->where("start_date", "<=", $now)
            ->where("end_date", ">=", $now)
            ->orderBy("promotion_id", "desc");


        //Filter từ khóa
        if (isset($filter['keyword']) && $filter['keyword'] != '') {
            $ds->where("promotion_name", "like", "%" . $filter['keyword'] . "%");
        }

        return $ds->limit(3)->get();
    }

}