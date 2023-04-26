<?php
/**
 * Created by PhpStorm   .
 * User: Mr Son
 * Date: 2020-03-20
 * Time: 9:33 AM
 * @author SonDepTrai
 */

namespace Modules\ChatHub\Models;


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
}