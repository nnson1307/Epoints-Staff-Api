<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 26/05/2021
 * Time: 17:03
 */

namespace Modules\Report\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ProductInventoryLogTable extends Model
{
    protected $table = "product_inventory_logs";
    protected $primaryKey = "product_inventory_log_id";

    protected $casts = [
        "total_inventory" => 'int',
        "total_inventory_value" => 'float',
        "inventory" => 'int',
        "inventory_value" => 'float',
    ];

    /**
     * Lấy tổng tồn kho
     *
     * @param $warehouseId
     * @param $date
     * @return mixed
     */
    public function getTotalInventory($warehouseId, $date)
    {
        $ds = $this
            ->select(
                DB::raw('SUM(inventory) as total_inventory'),
                DB::raw('SUM(inventory_value) as total_inventory_value')
            )
            ->whereDate("{$this->table}.created_at", $date)
            ->groupBy("{$this->table}.created_at");

        if ($warehouseId != 0) {
            $ds->where("warehouse_id", $warehouseId);
        }

        return $ds->first();
    }

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
                "pdc.product_child_name as product_name",
                "pdc.product_code",
//                "{$this->table}.inventory",
//                "{$this->table}.inventory_value",
                DB::raw('SUM(product_inventory_logs.inventory) as inventory'),
                DB::raw('SUM(product_inventory_logs.inventory_value) as inventory_value')
            )
            ->join("product_childs as pdc", "pdc.product_child_id", "=", "{$this->table}.product_id")
            ->whereDate("{$this->table}.created_at", $filter['date'])
            ->groupBy("{$this->table}.product_id");

        if ($filter['warehouse_id'] != 0) {
            $ds->where("{$this->table}.warehouse_id", $filter['warehouse_id']);
        }

        // get số trang
        $page = (int)($filter["page"] ?? 1);

        return $ds->paginate(PAGING_ITEM_PER_PAGE, $columns = ["*"], $pageName = "page", $page);
    }

    /**
     * Lấy tổng tồn ko đầu kỳ
     *
     * @param $warehouseId
     * @param $date
     * @return mixed
     */
    public function getTotalInventoryBegin($warehouseId, $date)
    {
        $ds = $this
            ->select(
                DB::raw('SUM(inventory) as total_inventory'),
                DB::raw('SUM(inventory_value) as total_inventory_value')
            )
            ->whereDate("{$this->table}.created_at", $date)
            ->groupBy("{$this->table}.created_at");

        if ($warehouseId != 0) {
            $ds->where("warehouse_id", $warehouseId);
        }

        return $ds->first();
    }

    /**
     * Lấy tồn đầu kỳ của sản phẩm theo kho
     *
     * @param $productCode
     * @param $warehouseId
     * @param $date
     * @return mixed
     */
    public function getInventoryLog($productCode, $warehouseId, $date)
    {
        $ds = $this
            ->select(
                DB::raw('SUM(inventory) as total_inventory'),
                DB::raw('SUM(inventory_value) as total_inventory_value')
            )
            ->where("product_code", $productCode)
            ->whereDate("{$this->table}.created_at", $date)
            ->groupBy("{$this->table}.created_at");

        if ($warehouseId != 0) {
            $ds->where("warehouse_id", $warehouseId);
        }

        return $ds->first();
    }
}