<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 25/05/2021
 * Time: 17:10
 */

namespace Modules\Warehouse\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ProductInventoryTable extends Model
{
    protected $table = "product_inventorys";
    protected $primaryKey = "product_inventory_id";

    /**
     * Lấy danh sách sản phẩm tồn kho
     *
     * @param array $filter
     * @return mixed
     */
    public function getInventory($filter = [])
    {
        $imageDefault = 'http://' . request()->getHttpHost() . '/static/images/product.png';

        $ds = $this
            ->select(
                "{$this->table}.product_inventory_id",
                "prc.product_child_name as product_name",
                "{$this->table}.product_code",
                "quantity",
                DB::raw("(CASE
                    WHEN  pr.avatar = '' THEN '$imageDefault'
                    WHEN  pr.avatar IS NULL THEN '$imageDefault'
                    ELSE  pr.avatar 
                    END
                ) as avatar")
            )
            ->join("product_childs as prc", "prc.product_child_id", "=", "{$this->table}.product_id")
            ->join("products as pr", "pr.product_id", "=", "prc.product_id")
            ->where("{$this->table}.warehouse_id", $filter['warehouse_id']);

        // filter product name
        if (isset($filter["product_name"]) && $filter["product_name"] != null) {
            $ds->where("prc.product_child_name", "like", "%" . $filter["product_name"] . "%");
        }

        // get số trang
        $page = (int)($filter["page"] ?? 1);

        return $ds->paginate(PAGING_ITEM_PER_PAGE, $columns = ["*"], $pageName = "page", $page);
    }
}