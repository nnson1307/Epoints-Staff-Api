<?php
/**
 * Created by PhpStorm   .
 * User: Mr Son
 * Date: 2020-03-20
 * Time: 9:33 AM
 * @author SonDepTrai
 */

namespace Modules\Product\Models;


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
    const IS_DISPLAY = 1;
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
                DB::raw("(CASE
                    WHEN  {$this->table}.type_app = 'new,best_seller' THEN 1
                    WHEN  {$this->table}.type_app = 'best_seller,new' THEN 1
                    WHEN  {$this->table}.type_app = 'new' THEN 1
                    ELSE  0
                    END
                ) as is_new"),
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
                "{$this->table}.cost as old_price",
                "{$this->table}.price as new_price"
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

    public function getProductHighLight($highlight = null, $limit = null)
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
            ->where("product_categories.is_deleted", self::NOT_DELETE);
        // filter sort
        if (isset($highlight) && $highlight != 'highlight' && $highlight != null) {
            $ds->orderBy("{$this->table}.created_at", "asc");
        }
        // filter limit
        if (isset($limit) && $limit != null) {
            $ds->limit($limit);
        }

        return $ds->get();
    }

    /**
     * Lấy sp không có image
     *
     * @return mixed
     */
    public function getProductNotImage()
    {
        return $this
            ->select(
                "{$this->table}.product_code"
            )
            ->leftJoin("product_images", "product_images.product_child_code", "=", "{$this->table}.product_code")
            ->whereNull("product_images.name")
            ->get();
    }

    /**
     * Cập nhật sản phẩm
     *
     * @param array $data
     * @param $productCode
     * @return mixed
     */
    public function edit(array $data, $productCode)
    {
        return $this->where("product_code", $productCode)->update($data);
    }

    /**
     * Scan sản phẩm
     *
     * @param $productCode
     * @return mixed
     */
    public function scanProduct($productCode)
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
            ->where("{$this->table}.product_code", $productCode)
            ->where("{$this->table}.is_deleted", self::NOT_DELETE)
            ->where("{$this->table}.is_actived", self::IS_ACTIVE)
            ->where("{$this->table}.is_display", self::IS_DISPLAY)
            ->where("{$this->table}.is_surcharge", self::SURCHARGE)
            ->where("products.is_deleted", self::NOT_DELETE)
            ->where("products.is_actived", self::IS_ACTIVE)
            ->first();
    }
}