<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 14/06/2021
 * Time: 16:32
 */

namespace Modules\Report\Models;


use Illuminate\Database\Eloquent\Model;

class ProductInventoryTable extends Model
{
    protected $table = "product_inventorys";
    protected $primaryKey = "product_inventory_id";

    /**
     * Lấy ds sản phẩm tồn kho
     *
     * @param array $filter
     * @return mixed
     */
    public function getProductInventory($filter = [])
    {
        $ds = $this
            ->select(
                "{$this->table}.product_id",
                "pr.product_child_name as product_name",
                "{$this->table}.product_code"
            )
            ->join("product_childs as pr", "pr.product_child_id", "=", "{$this->table}.product_id")
            ->where("{$this->table}.warehouse_id", "<>", 0)
            ->groupBy("{$this->table}.product_id");

        // get số trang
        $page = (int)($filter["page"] ?? 1);

        return $ds->paginate(PAGING_ITEM_PER_PAGE, $columns = ["*"], $pageName = "page", $page);
    }
}