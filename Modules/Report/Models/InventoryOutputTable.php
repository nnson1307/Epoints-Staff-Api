<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 14/06/2021
 * Time: 16:07
 */

namespace Modules\Report\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class InventoryOutputTable extends Model
{
    protected $table = "inventory_output_details";
    protected $primaryKey = "inventory_output_detail_id";

    const SUCCESS = "success";

    /**
     * Lấy số lượng xuất kho sản phẩm (từ ngày - đến ngày)
     *
     * @param $productCode
     * @param $warehouseId
     * @param $startTime
     * @param $endTime
     * @return mixed
     */
    public function getOutputToDate($productCode, $warehouseId, $startTime, $endTime)
    {
        $ds = $this
            ->select(
                "{$this->table}.product_code",
                DB::raw("SUM({$this->table}.quantity) as quantity"),
                DB::raw("SUM({$this->table}.total) as total")
            )
            ->join("inventory_outputs as op", "op.inventory_output_id", "=", "{$this->table}.inventory_output_id")
            ->where("{$this->table}.product_code", $productCode)
            ->whereBetween('op.created_at', [$startTime, $endTime])
            ->where("op.status", self::SUCCESS)
            ->groupBy("{$this->table}.product_code");

        if ($warehouseId != 0) {
            $ds->where("op.warehouse_id", $warehouseId);
        }

        return $ds->first();
    }

    /**
     * Lấy tổng xuất kho (từ ngày - đến ngày)
     *
     * @param $warehouseId
     * @param $startTime
     * @param $endTime
     * @return mixed
     */
    public function getTotalOutputToDate($warehouseId, $startTime, $endTime)
    {
        $ds = $this
            ->select(
                DB::raw("SUM({$this->table}.quantity) as quantity"),
                DB::raw("SUM({$this->table}.total) as total")
            )
            ->join("inventory_outputs as op", "op.inventory_output_id", "=", "{$this->table}.inventory_output_id")
            ->whereBetween('op.created_at', [$startTime, $endTime])
            ->where("op.status", self::SUCCESS);

        if ($warehouseId != 0) {
            $ds->where("op.warehouse_id", $warehouseId);
        }

        return $ds->first();
    }

}