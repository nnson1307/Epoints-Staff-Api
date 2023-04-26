<?php
/**
 * Created by PhpStorm
 * User: Mr Son
 * Date: 10/2/2020
 * Time: 9:45 AM
 */

namespace Modules\Order\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ProductChildTable extends Model
{
    protected $table = "product_childs";
    protected $primaryKey = "product_child_id";

    const IS_ACTIVE = 1;
    const NOT_DELETE = 0;
    const IS_DISPLAY = 1;
    const SURCHARGE = 0;

    /**
     * Lấy thông tin sản phẩm
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
                "products.product_id as product_parent_id",
                DB::raw("(CASE
                    WHEN  {$this->table}.type_app = 'new,best_seller' THEN 1
                    WHEN  {$this->table}.type_app = 'best_seller,new' THEN 1
                    WHEN  {$this->table}.type_app = 'new' THEN 1
                    ELSE  0
                    END
                ) as is_new")
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

    /**
     * Lấy thông tin sản phẩm
     *
     * @param $productId
     * @return mixed
     */
    public function getInfo($productId)
    {
        return $this
            ->select(
                "{$this->table}.product_child_id",
                "{$this->table}.product_code",
                "{$this->table}.product_child_name",
                "{$this->table}.cost as old_price",
                "{$this->table}.price as new_price",
                "pd.type_refer_commission",
                "pd.refer_commission_value",
                "pd.type_staff_commission",
                "pd.staff_commission_value"
            )
            ->join("products as pd", "pd.product_id", "=", "{$this->table}.product_id")
            ->where("product_child_id", $productId)
            ->first();
    }
}