<?php
/**
 * Created by PhpStorm   .
 * User: Mr Son
 * Date: 2020-03-19
 * Time: 4:09 PM
 * @author SonDepTrai
 */

namespace Modules\Home\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class NewTable extends Model
{
    protected $table = "news";
    protected $primaryKey = "new_id";

    const IS_ACTIVE = 1;
    const NOT_DELETE = 0;
    const ALL_SERVICE = 0;
    const ALL_PRODUCT = 0;

    /**
     * Danh sách bài viết
     *
     * @param $filter
     * @param $lang
     * @return mixed
     */
    public function getNews($filter, $lang)
    {
        $ds = $this
            ->select(
                "new_id",
                "title_$lang as title",
                "description_$lang as description",
                "image",
                "created_at"
            )
            ->where("is_deleted", 0)
            ->orderBy("new_id", "desc");

        // get số trang
        $page = (int)($filter['page'] ?? 1);

        // filter title vi
        if (isset($filter['title_vi']) && $filter['title_vi'] != null) {
            $ds->where("title_$lang", 'like', '%' . $filter['title_vi'] . '%');
        }

        if ($filter['type'] == 'home') {
            return $ds->limit(5)->get();
        } else if ($filter['type'] == 'list') {
            return $ds->paginate(PAGING_ITEM_PER_PAGE, $columns = ["*"], $pageName = 'page', $page);
        }
    }

    /**
     * Lấy thông tin bài viết
     *
     * @param $newId
     * @return mixed
     */
    public function getInfo($newId)
    {
        return $this
            ->select(
                "new_id",
                "title_vi as title",
                "description_vi as description",
                "description_detail_vi as description_detail",
                "image"
            )
            ->where("new_id", $newId)
            ->where("is_deleted", 0)
            ->first();
    }

    /**
     * Lấy 5 bài viết theo mãng dịch vụ
     *
     * Đang viết chuối trước tìm giải pháp tối ứu sau
     * @param $arrService
     * @return mixed
     */
    public function getListByService($arrService)
    {
        return $this
            ->select(
                "new_id",
                "title_vi",
                "title_en",
                "image",
                "description_vi",
                "description_en",
                "description_detail_vi",
                "description_detail_en"
            )
            ->where(function ($query) use ($arrService) {
                $query->where("service", self::ALL_SERVICE);

                if (count($arrService) > 0) {
                    foreach ($arrService as $v) {
                        $query->orWhereRaw("FIND_IN_SET($v,service)");
                    }
                }
            })
            ->where("is_actived", self::IS_ACTIVE)
            ->where("is_deleted", self::NOT_DELETE)
            ->orderBy("new_id", "desc")
            ->limit(5)
            ->get();
    }

    /**
     * Lấy 5 bài viết theo mãng dịch vụ
     *
     * Đang viết chuối trước tìm giải pháp tối ứu sau
     * @param $arrProduct
     * @return mixed
     */
    public function getListByProduct($arrProduct)
    {
        return $this
            ->select(
                "new_id",
                "title_vi",
                "title_en",
                "image",
                "description_vi",
                "description_en",
                "description_detail_vi",
                "description_detail_en"
            )
            ->where(function ($query) use ($arrProduct) {
                $query->where("product", self::ALL_PRODUCT);

                if (count($arrProduct) > 0) {
                    foreach ($arrProduct as $v) {
                        $query->orWhereRaw("FIND_IN_SET($v,product)");
                    }
                }
            })
            ->where("is_actived", self::IS_ACTIVE)
            ->where("is_deleted", self::NOT_DELETE)
            ->orderBy("new_id", "desc")
            ->limit(5)
            ->get();
    }

    /**
     * Lấy ds bài viết khi search home page
     *
     * @param $filter
     * @param $lang
     * @return mixed
     */
    public function getNewsSearch($filter, $lang)
    {
        $ds = $this
            ->select(
                "new_id",
                "title_$lang as title",
                "description_$lang as description",
                "image",
                "created_at"
            )
            ->where("is_deleted", 0)
            ->orderBy("new_id", "desc");

        // filter title vi
        if (isset($filter['keyword']) && $filter['keyword'] != null) {
            $ds->where("title_$lang", 'like', '%' . $filter['keyword'] . '%');
        }

        return $ds->limit(3)->get();
    }
}