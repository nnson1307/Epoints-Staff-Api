<?php
/**
 * Created by PhpStorm
 * User: Mr Son
 * Date: 10/14/2020
 * Time: 2:41 PM
 */

namespace Modules\Order\Models;


use Illuminate\Database\Eloquent\Model;

class DeliveryCostTable extends Model
{
    protected $table = "delivery_costs";
    protected $primaryKey = "delivery_cost_id";

    const IS_DEFAULT = 1;
    const NOT_DELETE = 0;
    const IS_ACTIVE = 1;

    /**
     * Lấy phí vận chuyển mặc định
     *
     * @return mixed
     */
    public function getCostDefault()
    {
        return $this
            ->select(
                "delivery_cost_code",
                "delivery_cost_name",
                "delivery_cost"
            )
            ->where("is_system", self::IS_DEFAULT)
            ->where("is_actived", self::IS_ACTIVE)
            ->first();
    }
}