<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 27/09/2022
 * Time: 21:11
 */

namespace Modules\Warranty\Models;


use Illuminate\Database\Eloquent\Model;

class MaintenanceCostTable extends Model
{
    protected $table = "maintenance_cost";
    protected $primaryKey = "maintenance_cost_id";

    protected $casts = [
        "cost" => 'float'
    ];

    /**
     * Lấy chi phí phát sinh của phiếu bảo trì
     *
     * @param $maintenanceId
     * @return mixed
     */
    public function getCost($maintenanceId)
    {
        $lang = app()->getLocale();

        return $this
            ->select(
                "{$this->table}.maintenance_cost_id",
                "{$this->table}.maintenance_id",
                "t.maintenance_cost_type_name_$lang as maintenance_cost_type_name",
                "{$this->table}.cost"
            )
            ->join("maintenance_cost_type as t", "t.maintenance_cost_type_id", "=", "{$this->table}.maintenance_cost_type")
            ->where("{$this->table}.maintenance_id", $maintenanceId)
            ->get();
    }

    /**
     * Xóa tất cả chi phí phát sinh của phiếu bảo trì
     *
     * @param $maintenanceId
     * @return mixed
     */
    public function removeCost($maintenanceId)
    {
        return $this->where("maintenance_id", $maintenanceId)->delete();
    }
}