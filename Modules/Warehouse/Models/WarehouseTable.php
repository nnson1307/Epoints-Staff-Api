<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 25/05/2021
 * Time: 16:55
 */

namespace Modules\Warehouse\Models;


use Illuminate\Database\Eloquent\Model;

class WarehouseTable extends Model
{
    protected $table = "warehouses";
    protected $primaryKey = "warehouse_id";

    const NOT_DELETED = 0;

    /**
     * Lấy danh sách kho
     *
     * @return mixed
     */
    public function getWarehouse()
    {
        return $this
            ->select(
                "warehouse_id",
                "name",
                "branch_id",
                "is_retail"
            )
            ->where("is_deleted", self::NOT_DELETED)
            ->get();
    }
}