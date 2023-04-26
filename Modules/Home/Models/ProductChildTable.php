<?php
/**
 * Created by PhpStorm   .
 * User: Mr Son
 * Date: 2020-03-20
 * Time: 9:33 AM
 * @author SonDepTrai
 */

namespace Modules\Home\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ProductChildTable extends Model
{
    protected $table = "product_childs";
    protected $primaryKey = "product_child_id";

    protected $casts = [
        "cost" => 'float',
        "price" => 'float'
    ];

    const IS_ACTIVE = 1;
    const NOT_DELETE = 0;
    const IS_NEW = 'new';
    const IS_DISPLAY = 1;
    const IS_AVATAR = 1;
    const SURCHARGE = 0;

    /**
     * Lấy danh sách tất cả sản phẩm theo type không phân trang
     *
     * @param $type
     * @param array $filter
     * @return mixed
     */
    public function getAllProduct($type, array $filter = [])
    {
        $imageDefault = 'http://' . request()->getHttpHost() . '/static/images/product.png';

        $ds = $this
            ->select(
                "{$this->table}.product_child_id as product_id",
                "{$this->table}.product_code as product_code",
                "{$this->table}.product_child_name as product_name",
                DB::raw("(CASE
                    WHEN  {$this->table}.type_app = 'new,best_seller' THEN 1
                    WHEN  {$this->table}.type_app = 'best_seller,new' THEN 1
                    WHEN  {$this->table}.type_app = 'new' THEN 1
                    ELSE  0
                    END
                ) as is_new"),
                DB::raw("(CASE
                    WHEN  products.avatar = '' THEN '$imageDefault'
                    WHEN  products.avatar IS NULL THEN '$imageDefault'
                    ELSE  products.avatar 
                    END
                ) as avatar"),
                "{$this->table}.cost as old_price",
                "{$this->table}.price as new_price",
                "products.description",
                "products.description_detail",
                "products.type_app",
                "products.is_sales",
                "products.percent_sale",
                "product_categories.product_category_id",
                "product_categories.category_name",
                "units.name as unit_name"
            )
            ->join("products", "products.product_id", "=", "{$this->table}.product_id")
            ->join("product_categories", "product_categories.product_category_id", "=", "products.product_category_id")
            ->leftJoin("units", "units.unit_id", "=", "products.unit_id")
            ->where("{$this->table}.is_deleted", self::NOT_DELETE)
            ->where("{$this->table}.is_actived", self::IS_ACTIVE)
            ->where("{$this->table}.is_display", self::IS_DISPLAY)
            ->where("{$this->table}.is_surcharge", self::SURCHARGE)
            ->where("products.is_deleted", self::NOT_DELETE)
            ->where("products.is_actived", self::IS_ACTIVE)
            ->where("product_categories.is_actived", self::IS_ACTIVE)
            ->where("product_categories.is_deleted", self::NOT_DELETE)
            ->orderBy("products.product_id", "desc");

        // filter product name
        if (isset($filter["product_name"]) && $filter["product_name"] != null) {
            $ds->where("{$this->table}.product_child_name", "like", "%" . $filter["product_name"] . "%");
        }
        // filter product category
        if (isset($filter["product_category_id"]) && $filter["product_category_id"] != null) {
            $ds->where("products.product_category_id", "like", "%" . $filter["product_category_id"] . "%");
        }
        // Kiểm tra type_app
        if ($type == "other") {
            $ds->whereNull("products.type_app")->where("products.is_sales", 0);
        } else if ($type == "best_seller") {
            $ds->where("{$this->table}.type_app", "LIKE", "%" . $type . "%");
        } else if ($type == "new") {
            $ds->where("{$this->table}.type_app", "LIKE", "%" . $type . "%");
        } else if ($type == 'all') {
            return $ds->get();
        }

        return $ds->limit(6)->get();
    }

    /**
     * Danh sách sản phẩm theo type có phân trang
     *
     * @param $filter
     * @return mixed
     */
    public function getProducts($filter)
    {
        $imageDefault = 'http://' . request()->getHttpHost() . '/static/images/product.png';

        $ds = $this
            ->select(
                "{$this->table}.product_child_id as product_id",
                "{$this->table}.product_code as product_code",
                "{$this->table}.product_child_name as product_name",
                DB::raw("(CASE
                    WHEN  products.avatar = '' THEN '$imageDefault'
                    WHEN  products.avatar IS NULL THEN '$imageDefault'
                    ELSE  products.avatar 
                    END
                ) as avatar"),
                "{$this->table}.cost as old_price",
                "{$this->table}.price as new_price",
                "products.description",
                "products.description_detail",
                "products.type_app",
                "products.is_sales",
                "products.percent_sale",
                "product_categories.product_category_id",
                "product_categories.category_name",
                "units.name as unit_name"
            )
            ->join("products", "products.product_id", "=", "{$this->table}.product_id")
            ->join("product_categories", "product_categories.product_category_id", "=", "products.product_category_id")
            ->leftJoin("units", "units.unit_id", "=", "products.unit_id")
            ->where("{$this->table}.is_deleted", self::NOT_DELETE)
            ->where("{$this->table}.is_actived", self::IS_ACTIVE)
            ->where("{$this->table}.is_display", self::IS_DISPLAY)
            ->where("{$this->table}.is_surcharge", self::SURCHARGE)
            ->where("products.is_deleted", self::NOT_DELETE)
            ->where("products.is_actived", self::IS_ACTIVE)
            ->where("product_categories.is_actived", self::IS_ACTIVE)
            ->where("product_categories.is_deleted", self::NOT_DELETE);

        // get số trang
        $page = (int)($filter["page"] ?? 1);

        // filter product name
        if (isset($filter["product_name"]) && $filter["product_name"] != null) {
            $ds->where("{$this->table}.product_child_name", "like", "%" . $filter["product_name"] . "%");
        }

        // filter product category
        if (isset($filter["product_category_id"]) && $filter["product_category_id"] != null) {
            $ds->where("products.product_category_id", "like", "%" . $filter["product_category_id"] . "%");
        }

        // filter sort
        if (isset($filter["sort_type"]) && $filter["sort_type"] != null) {
            if ($filter["sort_type"] == "price") {
                $ds->orderBy("new_price", "asc");
            } else if ($filter["sort_type"] == "is_sale") {
                $ds->where("products.is_sales", 1);
            } else if ($filter["sort_type"] == "new") {
                $ds->where("products.type_app", "LIKE", "%" . "new" . "%");
            } else if ($filter["sort_type"] == "best_seller") {
                $ds->where("products.type_app", "LIKE", "%" . "best_seller" . "%");
            }
        } else {
            $ds->orderBy("products.product_id", "desc");
        }

        // Kiểm tra type_app
        if ($filter["type"] == "other") {
            $ds->whereNull("products.type_app")->where("products.is_sales", 0);
        } else if ($filter["type"] == "is_sale") {
            $ds->where("products.is_sales", 1);
        } else if ($filter["type"] == "new") {
            $ds->where("products.type_app", "LIKE", "%" . $filter["type"] . "%");
        } else if ($filter["type"] == "best_seller") {
            $ds->where("products.type_app", "LIKE", "%" . $filter["type"] . "%");
        }

        return $ds->paginate(PAGING_ITEM_PER_PAGE, $columns = ["*"], $pageName = "page", $page);
    }

    /**
     * Lấy chi tiết sản phẩm
     *
     * @param $productId
     * @return mixed
     */
    public function getInfo($productId)
    {
        $imageDefault = 'http://' . request()->getHttpHost() . '/static/images/product.png';

        return $this
            ->select(
                "{$this->table}.product_child_id as product_id",
                "{$this->table}.product_code as product_code",
                "{$this->table}.product_child_name as product_name",
                DB::raw("(CASE
                    WHEN  products.avatar = '' THEN '$imageDefault'
                    WHEN  products.avatar IS NULL THEN '$imageDefault'
                    ELSE  products.avatar 
                    END
                ) as avatar"),
                "{$this->table}.cost as old_price",
                "{$this->table}.price as new_price",
                "products.description",
                "products.description_detail",
                "products.type_app",
                "products.is_sales",
                "products.percent_sale",
                "product_categories.product_category_id",
                "product_categories.category_name",
                "units.name as unit_name",
                "products.product_id as product_parent_id"
            )
            ->join("products", "products.product_id", "=", "{$this->table}.product_id")
            ->join("product_categories", "product_categories.product_category_id", "=", "products.product_category_id")
            ->leftJoin("units", "units.unit_id", "=", "products.unit_id")
            ->where("{$this->table}.product_child_id", $productId)
            ->where("{$this->table}.is_deleted", self::NOT_DELETE)
            ->where("{$this->table}.is_actived", self::IS_ACTIVE)
            ->where("{$this->table}.is_display", self::IS_DISPLAY)
            ->where("{$this->table}.is_surcharge", self::SURCHARGE)
            ->where("products.is_deleted", self::NOT_DELETE)
            ->where("products.is_actived", self::IS_ACTIVE)
            ->first();
    }

    /**
     * Lấy danh sách sản phẩm home page
     *
     * @return mixed
     */
    public function getProductHome()
    {
        $imageDefault = 'http://' . request()->getHttpHost() . '/static/images/product.png';

        return $this
            ->select(
                "{$this->table}.product_child_id as product_id",
                "{$this->table}.product_code as product_code",
                "{$this->table}.product_child_name as product_name",
                DB::raw("(CASE
                    WHEN  products.avatar = '' THEN '$imageDefault'
                    WHEN  products.avatar IS NULL THEN '$imageDefault'
                    ELSE  products.avatar 
                    END
                ) as avatar"),
//                DB::raw("CONVERT({$this->table}.cost, INT) as old_price"),
//                DB::raw("CONVERT({$this->table}.price, INT) as new_price"),
                "{$this->table}.cost as old_price",
                "{$this->table}.price as new_price",
                "products.description",
                "products.description_detail",
                "products.type_app",
                "products.is_sales",
                "products.percent_sale",
                "product_categories.category_name",
                "units.name as unit_name",
                "product_categories.product_category_id"
            )
            ->join("products", "products.product_id", "=", "{$this->table}.product_id")
            ->join("product_categories", "product_categories.product_category_id", "=", "products.product_category_id")
            ->leftJoin("units", "units.unit_id", "=", "products.unit_id")
            ->where("{$this->table}.is_deleted", self::NOT_DELETE)
            ->where("{$this->table}.is_actived", self::IS_ACTIVE)
            ->where("{$this->table}.is_display", self::IS_DISPLAY)
            ->where("{$this->table}.is_surcharge", self::SURCHARGE)
            ->where("products.is_deleted", self::NOT_DELETE)
            ->where("products.is_actived", self::IS_ACTIVE)
            ->where("product_categories.is_actived", self::IS_ACTIVE)
            ->where("product_categories.is_deleted", self::NOT_DELETE)
            ->orderBy("products.product_id", "desc")
            ->get();
    }

    /**
     * Lấy sản phẩm kèm theo ăn theo category trừ chính sản phẩm đó
     *
     * @param $categoryId
     * @param $productId
     * @return mixed
     */
    public function getProductByCategory($categoryId, $productId)
    {
        $imageDefault = 'http://' . request()->getHttpHost() . '/static/images/product.png';

        return $this
            ->select(
                "{$this->table}.product_child_id as product_id",
                "{$this->table}.product_code as product_code",
                "{$this->table}.product_child_name as product_name",
                DB::raw("(CASE
                    WHEN  products.avatar = '' THEN '$imageDefault'
                    WHEN  products.avatar IS NULL THEN '$imageDefault'
                    ELSE  products.avatar 
                    END
                ) as avatar"),
//                DB::raw("CONVERT({$this->table}.cost, INT) as old_price"),
//                DB::raw("CONVERT({$this->table}.price, INT) as new_price"),
                "{$this->table}.cost as old_price",
                "{$this->table}.price as new_price",
                "products.description",
                "products.description_detail",
                "products.type_app",
                "products.is_sales",
                "products.percent_sale",
                "product_categories.category_name",
                "units.name as unit_name",
                "product_categories.product_category_id"
            )
            ->join("products", "products.product_id", "=", "{$this->table}.product_id")
            ->join("product_categories", "product_categories.product_category_id", "=", "products.product_category_id")
            ->leftJoin("units", "units.unit_id", "=", "products.unit_id")
            ->where("{$this->table}.is_deleted", self::NOT_DELETE)
            ->where("{$this->table}.is_actived", self::IS_ACTIVE)
            ->where("{$this->table}.is_display", self::IS_DISPLAY)
            ->where("{$this->table}.is_surcharge", self::SURCHARGE)
            ->where("products.is_deleted", self::NOT_DELETE)
            ->where("products.is_actived", self::IS_ACTIVE)
            ->where("product_categories.is_actived", self::IS_ACTIVE)
            ->where("product_categories.is_deleted", self::NOT_DELETE)
            ->where("product_categories.product_category_id", $categoryId)
            ->where("{$this->table}.product_child_id", "<>", $productId)
            ->orderBy("products.product_id", "desc")
            ->limit(3)
            ->get();
    }

    /**
     * Option thuộc tính sản phẩm theo product
     *
     * @param $productMasterId
     * @return mixed
     */
    public function getOptionAttribute($productMasterId)
    {
        return $this
            ->select(
                "product_child_id as product_id",
                "product_code",
                "product_child_name as product_name"
            )
            ->where("product_id", $productMasterId)
            ->where("is_actived", self::IS_ACTIVE)
            ->where("is_deleted", self::NOT_DELETE)
            ->where("is_display", self::IS_DISPLAY)
            ->where("{$this->table}.is_surcharge", self::SURCHARGE)
            ->get();
    }

    /**
     * Lấy thông tin sản phẩm ETL
     *
     * @param $productCode
     * @return mixed
     */
    public function getProductByCode($productCode)
    {
        $imageDefault = 'http://' . request()->getHttpHost() . '/static/images/product.png';

        return $this
            ->select(
                "{$this->table}.product_child_id as product_id",
                "{$this->table}.product_code as product_code",
                "{$this->table}.product_child_name as product_name",
//                DB::raw("(CASE
//                    WHEN  products.avatar = '' THEN '$imageDefault'
//                    WHEN  products.avatar IS NULL THEN '$imageDefault'
//                    ELSE  products.avatar
//                    END
//                ) as avatar"),
//                DB::raw("CONVERT({$this->table}.cost, INT) as old_price"),
//                DB::raw("CONVERT({$this->table}.price, INT) as price"),
                "{$this->table}.price as new_price"

//                "products.description",
//                "products.description_detail",
//                "products.type_app",
//                "products.is_sales",
//                "products.percent_sale",
//                "product_categories.product_category_id",
//                "product_categories.category_name",
//                "units.name as unit_name",
//                "products.product_id as product_parent_id"
            )
            ->join("products", "products.product_id", "=", "{$this->table}.product_id")
            ->join("product_categories", "product_categories.product_category_id", "=", "products.product_category_id")
            ->leftJoin("units", "units.unit_id", "=", "products.unit_id")
            ->where("{$this->table}.product_code", $productCode)
            ->where("{$this->table}.is_deleted", self::NOT_DELETE)
            ->where("{$this->table}.is_actived", self::IS_ACTIVE)
            ->where("{$this->table}.is_display", self::IS_DISPLAY)
            ->where("{$this->table}.is_surcharge", self::SURCHARGE)
            ->where("products.is_deleted", self::NOT_DELETE)
            ->where("products.is_actived", self::IS_ACTIVE)
            ->first();
    }

    /**
     * Lấy ds sản phẩm khi search home page
     *
     * @param $filter
     * @return mixed
     */
    public function getProductSearch($filter)
    {
        $imageDefault = 'http://' . request()->getHttpHost() . '/static/images/product.png';

        $ds = $this
            ->select(
                "{$this->table}.product_child_id as product_id",
                "{$this->table}.product_code as product_code",
                "{$this->table}.product_child_name as product_name",
                DB::raw("(CASE
                    WHEN  {$this->table}.type_app = 'new,best_seller' THEN 1
                    WHEN  {$this->table}.type_app = 'best_seller,new' THEN 1
                    WHEN  {$this->table}.type_app = 'new' THEN 1
                    ELSE  0
                    END
                ) as is_new"),
                DB::raw("(CASE
                    WHEN  product_images.name = '' THEN '$imageDefault'
                    WHEN  product_images.name IS NULL THEN '$imageDefault'
                    ELSE  product_images.name 
                    END
                ) as avatar"),
                "{$this->table}.cost as old_price",
                "{$this->table}.price as new_price",
                "products.description",
                "products.description_detail",
                "products.type_app",
                "products.is_sales",
                "products.percent_sale",
                "product_categories.product_category_id",
                "product_categories.category_name",
                "units.name as unit_name",
                DB::raw("(CASE
                    WHEN  product_favourite.id  IS NULL THEN 0
                    ELSE  1
                    END
                ) as is_like")
            )
            ->join("products", "products.product_id", "=", "{$this->table}.product_id")
            ->join("product_categories", "product_categories.product_category_id", "=", "products.product_category_id")
            ->leftJoin("units", "units.unit_id", "=", "products.unit_id")
            ->leftJoin('product_favourite', function ($join) {
                $join->on("{$this->table}.product_child_id", "=", "product_favourite.product_id")
                    ->where("product_favourite.user_id", Auth()->id());
            })
            ->leftJoin('product_images', function ($join) {
                $join->on("{$this->table}.product_code", "=", "product_images.product_child_code")
                    ->where("product_images.is_avatar", self::IS_AVATAR);
            })
            ->where("{$this->table}.is_deleted", self::NOT_DELETE)
            ->where("{$this->table}.is_actived", self::IS_ACTIVE)
            ->where("{$this->table}.is_display", self::IS_DISPLAY)
            ->where("{$this->table}.is_surcharge", self::SURCHARGE)
            ->where("products.is_deleted", self::NOT_DELETE)
            ->where("products.is_actived", self::IS_ACTIVE)
            ->where("product_categories.is_actived", self::IS_ACTIVE)
            ->where("product_categories.is_deleted", self::NOT_DELETE)
            ->orderBy("products.product_id", "desc");

        // filter product name
        if (isset($filter["keyword"]) && $filter["keyword"] != null) {
            $ds->where("{$this->table}.product_child_name", "like", "%" . $filter["keyword"] . "%");
        }

        return $ds->limit(3)->get();
    }

    /**
     * Lấy thông tin sản phẩm khuyến mãi
     *
     * @param $productCode
     * @return mixed
     */
    public function getProductPromotion($productCode)
    {
        return $this
            ->select(
                "product_child_id",
                "product_code",
                "product_child_name",
                "cost as old_price",
                "price as new_price"
            )
            ->where("product_code", $productCode)
            ->first();
    }
}