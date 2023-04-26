<?php
/**
 * Created by PhpStorm   .
 * User: Mr Son
 * Date: 2020-02-25
 * Time: 2:13 PM
 * @author SonDepTrai
 */

namespace Modules\Home\Models;


use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class VoucherTable extends Model
{
    protected $table = 'vouchers';
    protected $primaryKey = 'voucher_id';

    protected $casts = [
        'cash' => 'float',
        'max_price' => 'float',
        'required_price' => 'float',
        'percent' => 'float'
    ];

    /**
     * Lấy thông tin voucher
     *
     * @param $voucherCode
     * @param $memberLevelId
     * @return mixed
     */
    public function getVoucher($voucherCode, $memberLevelId)
    {
        return $this
            ->select(
                "voucher_id",
                "code",
                "is_all",
                "type",
                "percent",
                "cash",
                "max_price",
                "required_price",
                "object_type",
                "object_type_id",
                "expire_date",
                "branch_id",
                "quota",
                "total_use",
                "voucher_img as image"
            )
            ->where("code", $voucherCode)
            ->where(function ($query) use ($memberLevelId) {
                $query->where("member_level_apply", "all")
                    ->orWhereNull("member_level_apply")
                    ->orWhereIn("member_level_apply", $memberLevelId);
            })
            ->where("is_actived", 1)
            ->where("is_deleted", 0)
            ->where("type_using", "public")
            ->first();
    }

    /**
     * Lấy danh sách tất cả voucher
     *
     * @return mixed
     */
    public function getAllVoucher()
    {
        $imageDefault = 'http://' . request()->getHttpHost() . '/static/images/voucher.png';

        return $this
            ->select(
                "voucher_id",
                "code",
                "is_all",
                "type",
                "percent",
                "cash",
                "max_price",
                "required_price",
                "object_type",
                "object_type_id",
                "expire_date",
                "branch_id",
                "quota",
                "total_use",
                "sale_special",
                DB::raw("(CASE
                    WHEN  voucher_img = '' THEN '$imageDefault'
                    WHEN  voucher_img IS NULL THEN '$imageDefault'
                    ELSE  voucher_img 
                    END
                ) as image"),
                "description",
                "detail_description",
                "member_level_apply"
            )
            ->where("type_using", "public")
            ->where("is_actived", 1)
            ->where("is_deleted", 0)
            ->where("expire_date", ">", Carbon::now()->format('Y-m-d'))
            ->whereRaw("quota > total_use")
            ->orderBy('voucher_id', 'desc')
            ->get();
    }

    /**
     * Danh sách voucher của bạn
     *
     * @param $filter
     * @param $memberLevelId
     * @return mixed
     */
    public function getYourVoucher($filter, $memberLevelId)
    {
        $ds = $this
            ->select(
                "voucher_id",
                "code",
                "is_all",
                "type",
                "percent",
                "cash",
                "max_price",
                "required_price",
                "object_type",
                "object_type_id",
                "expire_date",
                "branch_id",
                "quota",
                "total_use",
                "sale_special",
                "voucher_img as image",
                "description",
                "detail_description",
                "member_level_apply"
            )
            ->where(function ($query) use ($memberLevelId) {
                $query->where("member_level_apply", "all")
                    ->orWhereNull("member_level_apply")
                    ->orWhereIn("member_level_apply", $memberLevelId);
            })
            ->where("type_using", "public")
            ->where("is_actived", 1)
            ->where("is_deleted", 0);

        //Get data theo 2 type
        if ($filter['type'] == 'current') {
            //Set điều kiện
            $ds->where("expire_date", ">=", Carbon::now()->format('Y-m-d'))
                ->whereRaw('quota > total_use');

            return $ds->orderBy("voucher_id", "desc")->get();
        } else if ($filter['type'] == 'expired') {
            // get số trang
            $page = (int)($filter['page'] ?? 1);
            //Set điều kiện
            $ds->where("expire_date", "<", Carbon::now()->format('Y-m-d'))
                ->orWhereRaw('total_use >= quota');

            return $ds->orderBy("voucher_id", "desc")->paginate(PAGING_ITEM_PER_PAGE, $columns = ["*"], $pageName = 'page', $page);
        }
    }

    /**
     * Lấy chi tiết voucher
     *
     * @param $code
     * @return mixed
     */
    public function getInfo($code)
    {
        return $this
            ->select(
                "voucher_id",
                "code",
                "is_all",
                "type",
                "percent",
                "cash",
                "max_price",
                "required_price",
                "object_type",
                "object_type_id",
                "expire_date",
                "branch_id",
                "quota",
                "total_use",
                "sale_special",
                "voucher_img as image",
                "description",
                "detail_description",
                "member_level_apply"
            )
            ->where("code", $code)
            ->where("type_using", "public")
            ->where("is_actived", 1)
            ->where("is_deleted", 0)
            ->first();
    }

    /**
     * Lấy ds voucher khi search home page
     *
     * @param $filter
     * @return mixed
     */
    public function getVoucherSearch($filter)
    {
        $imageDefault = 'http://' . request()->getHttpHost() . '/static/images/voucher.png';

        $ds = $this
            ->select(
                "voucher_id",
                "code",
                "is_all",
                "type",
                "percent",
                "cash",
                "max_price",
                "required_price",
                "object_type",
                "object_type_id",
                "expire_date",
                "branch_id",
                "quota",
                "total_use",
                "sale_special",
                DB::raw("(CASE
                    WHEN  voucher_img = '' THEN '$imageDefault'
                    WHEN  voucher_img IS NULL THEN '$imageDefault'
                    ELSE  voucher_img 
                    END
                ) as image"),
                "description",
                "detail_description",
                "member_level_apply",
                "voucher_title",
                "created_at as start_date",
                "point"
            )
            ->where("type_using", "public")
            ->where("is_actived", 1)
            ->where("is_deleted", 0)
            ->where("expire_date", ">", Carbon::now()->format('Y-m-d'))
            ->whereRaw("quota > total_use")
            ->orderBy('voucher_id', 'desc');

        // filter voucher name
        if (isset($filter["keyword"]) && $filter["keyword"] != null) {
            $ds->where("{$this->table}.voucher_title", "like", "%" . $filter["keyword"] . "%");
        }

        return $ds->limit(3)->get();
    }
}