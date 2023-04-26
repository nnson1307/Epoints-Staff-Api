<?php
/**
 * Created by PhpStorm
 * User: Mr Son
 * Date: 4/23/2020
 * Time: 2:24 PM
 */

namespace Modules\Product\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ProductFavouriteTable extends Model
{
    protected $table = "product_favourite";
    protected $primaryKey = "id";
    protected $fillable = [
        "id",
        "product_id",
        "user_id",
        "created_at",
        "updated_at"
    ];

    protected $casts = [
        "cost" => 'float',
        "price" => 'float'
    ];

    const IS_ACTIVE = 1;
    const NOT_DELETE = 0;
    const IS_DISPLAY = 1;

    /**
     * Like sản phẩm
     *
     * @param array $data
     * @return mixed
     */
    public function like(array $data)
    {
        return $this->create($data);
    }

    /**
     * Unlike sản phẩm
     *
     * @param $productId
     * @param $customerId
     * @return mixed
     */
    public function unlike($productId, $customerId)
    {
        return $this->where("product_id", $productId)->where('user_id', $customerId)->delete();
    }

    /**
     * Lấy thông tin đã thích sản phẩm chưa
     *
     * @param $productId
     * @param $userId
     * @return mixed
     */
    public function getLike($productId, $userId)
    {
        return $this
            ->select(
                "id",
                "product_id",
                "user_id"
            )
            ->where("product_id", $productId)
            ->where("user_id", $userId)
            ->first();
    }

    /**
     * Lấy thông tin tất cã sản phẩm đã like của user
     *
     * @param $userId
     * @return mixed
     */
    public function getLikeAll($userId)
    {
        return $this
            ->select(
                "id",
                "product_id",
                "user_id"
            )
            ->where("user_id", $userId)
            ->get();
    }

    /**
     * lấy ds sản phẩm yêu thích
     *
     * @param $filter
     * @param $customerId
     * @return mixed
     */
    public function getListProductLike($filter, $customerId)
    {
        $imageDefault = 'http://' . request()->getHttpHost() . '/static/images/product.png';

        $ds = $this
            ->select(
                "product_childs.product_child_name as product_name",
                "product_childs.product_code as product_code",
                "{$this->table}.product_id",
                "product_childs.product_child_name as product_name",
//                "products.avatar as avatar",
                DB::raw("(CASE
                    WHEN  products.avatar = '' THEN '$imageDefault'
                    WHEN  products.avatar IS NULL THEN '$imageDefault'
                    ELSE  products.avatar 
                    END
                ) as avatar"),
                "product_childs.cost as old_price",
                "product_childs.price as new_price",
                "products.description",
                "products.description_detail",
                "products.type_app",
                "products.is_sales",
                "products.percent_sale",
                "product_categories.product_category_id",
                "product_categories.category_name",
                "units.name as unit_name"
            )
            ->join("product_childs", "product_childs.product_child_id", "=", "{$this->table}.product_id")
            ->join("products", "products.product_id", "=", "product_childs.product_id")
            ->join("product_categories", "product_categories.product_category_id", "=", "products.product_category_id")
            ->leftJoin("units", "units.unit_id", "=", "products.unit_id")
            ->where("product_childs.is_deleted", self::NOT_DELETE)
            ->where("product_childs.is_actived", self::IS_ACTIVE)
            ->where("product_childs.is_display", self::IS_DISPLAY)
            ->where("products.is_deleted", self::NOT_DELETE)
            ->where("products.is_actived", self::IS_ACTIVE)
            ->where("product_categories.is_actived", self::IS_ACTIVE)
            ->where("product_categories.is_deleted", self::NOT_DELETE)
            ->where("{$this->table}.user_id", $customerId)
            ->orderBy("{$this->table}.created_at", "desc");

        // get số trang
        $page = (int)($filter["page"] ?? 1);

        return $ds->paginate(PAGING_ITEM_PER_PAGE, $columns = ["*"], $pageName = "page", $page);
    }

    /**
     * Kiểm tra sản phẩm đã like chưa
     *
     * @param $productId
     * @param $userId
     * @return mixed
     */
    public function checkFavourite($productId, $userId)
    {
        return $this
            ->where("product_id", $productId)
            ->where("user_id", $userId)
            ->first();
    }
}