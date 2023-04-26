<?php
/**
 * Created by PhpStorm
 * User: Mr Son
 * Date: 6/15/2020
 * Time: 11:08 AM
 */

namespace Modules\Product\Models;


use Illuminate\Database\Eloquent\Model;

class ProductTable extends Model
{
    protected $table = "products";
    protected $primaryKey = "product_id";
    protected $fillable = [
        "product_id",
        "product_category_id",
        "product_model_id",
        "product_name",
        "product_short_name",
        "unit_id",
        "cost",
        "price_standard",
        "is_sales",
        "is_promo",
        "type",
        "type_manager",
        "count_version",
        "is_inventory_warning",
        "inventory_warning",
        "description",
        "staff_commission_value",
        "type_staff_commission",
        "refer_commission_value",
        "type_refer_commission",
        "supplier_id",
        "created_at",
        "updated_at",
        "created_by",
        "updated_by",
        "is_deleted",
        "is_actived",
        "product_code",
        "is_all_branch",
        "avatar",
        "slug",
        "description_detail",
        "type_app",
        "percent_sale"
    ];

    /**
     * Thêm sản phẩm
     *
     * @param array $data
     * @return mixed
     */
    public function add(array $data)
    {
        return $this->create($data)->product_id;
    }

    public function getByID($productId){
        $data =  $this
            ->select(
                "{$this->table}.product_id",
                "{$this->table}.supplier_id",
                "{$this->table}.product_model_id",
                "{$this->table}.supplier_id"
            )
            ->where("product_id", $productId)
            ->first();
        return $data;
    }
}