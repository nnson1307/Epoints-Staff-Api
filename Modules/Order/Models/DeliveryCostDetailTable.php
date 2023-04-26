<?php
/**
 * Created by PhpStorm
 * User: Mr Son
 * Date: 10/14/2020
 * Time: 2:41 PM
 */

namespace Modules\Order\Models;


use Illuminate\Database\Eloquent\Model;

class DeliveryCostDetailTable extends Model
{
    protected $table = "delivery_cost_detail";
    protected $primaryKey = "delivery_cost_detail_id";

    const IS_DEFAULT = 1;
    const NOT_DELETE = 0;
    const IS_ACTIVE = 1;

    /**
     * Lấy phí vận chuyển
     *
     * @param $provinceId
     * @param $districtId
     * @return mixed
     */
    public function getCostDetail($provinceId, $districtId)
    {
        return $this
            ->select(
                "{$this->table}.delivery_cost_code",
                "delivery_costs.delivery_cost_name",
                "delivery_costs.delivery_cost",
                "delivery_costs.is_delivery_fast",
                "delivery_costs.delivery_fast_cost",
                "delivery_costs.delivery_cost_id"
            )
            ->join("delivery_costs", "delivery_costs.delivery_cost_code", "=", "{$this->table}.delivery_cost_code")
            ->where("delivery_costs.is_actived", self::IS_ACTIVE)
            ->where("{$this->table}.province_id", $provinceId)
            ->where("{$this->table}.district_id", $districtId)
            ->first();
    }
}