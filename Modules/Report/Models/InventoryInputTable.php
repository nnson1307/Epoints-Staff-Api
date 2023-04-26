<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 14/06/2021
 * Time: 15:55
 */

namespace Modules\Report\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class InventoryInputTable extends Model
{
    protected $table = "inventory_input_details";
    protected $primaryKey = "inventory_input_detail_id";

    const SUCCESS = "success";

    /**
     * Lấy số lượng nhập kho sản phẩm (từ ngày - đến ngày)
     *
     * @param $productCode
     * @param $warehouseId
     * @param $startTime
     * @param $endTime
     * @return mixed
     */
    public function getInputToDate($productCode, $warehouseId, $startTime, $endTime)
    {
        $ds =  $this
            ->select(
                "{$this->table}.product_code",
                DB::raw("SUM({$this->table}.quantity) as quantity"),
                DB::raw("SUM({$this->table}.total) as total")
            )
            ->join("inventory_inputs as ip", "ip.inventory_input_id", "=", "{$this->table}.inventory_input_id")
            ->where("{$this->table}.product_code", $productCode)
            ->whereBetween('ip.created_at', [$startTime, $endTime])
            ->where("ip.status", self::SUCCESS)
            ->groupBy("{$this->table}.product_code");

        if ($warehouseId != 0) {
            $ds->where("ip.warehouse_id", $warehouseId);
        }

        return $ds->first();
    }

    /**
     * Lấy tổng nhập kho (từ ngày - đến ngày)
     *
     * @param $warehouseId
     * @param $startTime
     * @param $endTime
     * @return mixed
     */
    public function getTotalInputToDate($warehouseId, $startTime, $endTime)
    {
        $ds = $this
            ->select(
                DB::raw("SUM({$this->table}.quantity) as quantity"),
                DB::raw("SUM({$this->table}.total) as total")
            )
            ->join("inventory_inputs as ip", "ip.inventory_input_id", "=", "{$this->table}.inventory_input_id")
            ->whereBetween('ip.created_at', [$startTime, $endTime])
            ->where("ip.status", self::SUCCESS);

        if ($warehouseId != 0) {
            $ds->where("ip.warehouse_id", $warehouseId);
        }

        return $ds->first();
    }
}