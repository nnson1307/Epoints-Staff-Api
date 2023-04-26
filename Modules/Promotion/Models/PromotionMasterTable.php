<?php
/**
 * Created by PhpStorm
 * User: Mr Son
 * Date: 9/14/2020
 * Time: 10:03 AM
 */

namespace Modules\Promotion\Models;


use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class PromotionMasterTable extends Model
{
    protected $table = "promotion_master";
    protected $primaryKey = "promotion_id";

    const IS_ACTIVE = 1;
    const IS_DISPLAY = 1;
    const NOT_DELETED = 0;
    const IS_FEATURE = 1;

    /**
     * Danh sách CTKM
     *
     * @param $filter
     * @return mixed
     */
    public function getLists($filter)
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
            ->orderBy("promotion_id", "desc");

        // get số trang
        $page = (int)($filter['page'] ?? 1);

        //Filter từ khóa
        if (isset($filter['keyword']) && $filter['keyword'] != '') {
            $ds->where("promotion_name", "like", "%" . $filter['keyword'] . "%");
        }

        //Filter tình trạng CTKM đang diễn ra or kết thúc
        if (isset($filter['type']) && $filter['type'] != '') {
            if ($filter['type'] == 'current') {
                $ds->where("start_date", "<=", $now)
                    ->where("end_date", ">=", $now);
            } else if ($filter['type'] == 'old') {
                $ds->where("end_date", "<", $now);
            }
        }

        if (isset($filter['view']) && $filter['view'] == 'home') {
            $ds->where("is_feature", self::IS_FEATURE);
        }

        return $ds->paginate(PAGING_ITEM_PER_PAGE, $columns = ["*"], $pageName = 'page', $page);
    }

    /**
     * Chi tiết CTKM
     *
     * @param $promotionCode
     * @return mixed
     */
    public function getDetail($promotionCode)
    {
        return $this
            ->select(
                "promotion_code",
                "promotion_name",
                "start_date",
                "end_date",
                "description",
                "image",
                "description_detail",
                "promotion_type",
                "promotion_type_discount",
                "promotion_type_discount_value"
            )
            ->where("promotion_code", $promotionCode)
            ->where("is_actived", self::IS_ACTIVE)
            ->where("is_display", self::IS_DISPLAY)
            ->where("is_deleted", self::NOT_DELETED)
            ->first();
    }
}