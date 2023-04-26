<?php
namespace Modules\Product\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class PromotionDetailTable extends Model
{
    protected $table = "promotion_details";
    protected $primaryKey = "promotion_detail_id";
    protected $fillable = [
        "promotion_detail_id",
        "promotion_id",
        "promotion_code",
        "object_type",
        "object_id",
        "object_name",
        "object_code",
        "base_price",
        "promotion_price",
        "quantity_buy",
        "quantity_gift",
        "gift_object_type",
        "gift_object_id",
        "gift_object_name",
        "gift_object_code",
        "is_actived",
        "created_at",
        "updated_at"
    ];

    const IS_ACTIVE = 1;
    const NOT_DELETE = 0;
    const PRODUCT = 'product';
    const IS_DISPLAY = 1;

    /**
     * Lấy thông tin khuyến mãi của sp, dv, thẻ dv
     *
     * @param $objectType
     * @param $objectCode
     * @return mixed
     */
    public function getPromotionDetail($objectType, $objectCode)
    {
        $ds =  $this
            ->select(
                "promotion_master.promotion_id",
                "promotion_master.promotion_code",
                "promotion_master.start_date",
                "promotion_master.end_date",
                "promotion_master.is_time_campaign",
                "promotion_master.time_type",
                "promotion_master.branch_apply",
                "promotion_master.promotion_type",
                "promotion_master.promotion_type_discount",
                "promotion_master.promotion_type_discount_value",
                "promotion_master.order_source",
                "promotion_master.quota",
                "promotion_master.quota_use",
                "promotion_master.promotion_apply_to",
                "{$this->table}.object_type",
                "{$this->table}.object_id",
                "{$this->table}.object_name",
                "{$this->table}.object_code",
                "{$this->table}.base_price",
                "{$this->table}.promotion_price",
                "{$this->table}.quantity_buy",
                "{$this->table}.quantity_gift",
                "{$this->table}.gift_object_type",
                "{$this->table}.gift_object_id",
                "{$this->table}.gift_object_name",
                "{$this->table}.gift_object_code"
            )
            ->join("promotion_master", "promotion_master.promotion_code", "=", "{$this->table}.promotion_code")
            ->where("{$this->table}.object_type", $objectType)
            ->where("{$this->table}.object_code", $objectCode)
            ->where("promotion_master.is_actived", self::IS_ACTIVE)
            ->where("promotion_master.is_deleted", self::NOT_DELETE)
            ->where("{$this->table}.is_actived", self::IS_ACTIVE);

        return $ds->get();
    }

    /**
     * Lấy thông tin sản phẩm khuyến mãi
     *
     * @param array $filter
     * @return mixed
     */
    public function getProductPromotion(array $filter = [])
    {
        $imageDefault = 'http://' . request()->getHttpHost() . '/static/images/product.png';

        $ds = $this
            ->select(
                "product_childs.product_child_id as product_id",
                "product_childs.product_code as product_code",
                "product_childs.product_child_name as product_name",
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
            ->join("product_childs", "product_childs.product_code", "=", "{$this->table}.object_code")
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
            ->where("{$this->table}.object_type", self::PRODUCT)
            ->groupBy("{$this->table}.object_id");

        // filter product name
        if (isset($filter["product_name"]) && $filter["product_name"] != null) {
            $ds->where("product_childs.product_child_name", "like", "%" . $filter["product_name"] . "%");
        }

        // filter product category
        if (isset($filter["product_category_id"]) && $filter["product_category_id"] != null) {
            $ds->where("products.product_category_id", "like", "%" . $filter["product_category_id"] . "%");
        }

        return $ds->get();
    }
}