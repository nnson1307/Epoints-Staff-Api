<?php
/**
 * Created by PhpStorm
 * User: Mr Son
 * Date: 1/6/2021
 * Time: 3:07 PM
 */

namespace Modules\Order\Models;


use Illuminate\Database\Eloquent\Model;

class ProductInventoryTable extends Model
{
    protected $table = "product_inventorys";
    protected $primaryKey = "product_inventory_id";

    const IS_RETAIL = 1;

    /**
     * Lấy thông tin tồn kho của sp
     *
     * @param $productCode
     * @param $branchCode
     * @return mixed
     */
    public function getInventory($productCode, $branchCode)
    {
        return $this
            ->select(
                "{$this->table}.product_code",
                "{$this->table}.quantity"
            )
            ->join("warehouses", "warehouses.warehouse_id", "=", "{$this->table}.warehouse_id")
            ->join("branches", "branches.branch_id", "=", "warehouses.branch_id")
            ->where("{$this->table}.product_code", $productCode)
            ->where("branches.branch_code", $branchCode)
            ->where("warehouses.is_retail", self::IS_RETAIL)
            ->first();
    }
}