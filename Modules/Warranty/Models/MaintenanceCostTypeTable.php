<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 27/09/2022
 * Time: 16:39
 */

namespace Modules\Warranty\Models;


use Illuminate\Database\Eloquent\Model;

class MaintenanceCostTypeTable extends Model
{
    protected $table = "maintenance_cost_type";
    protected $primaryKey = "maintenance_cost_type_id";

    const IS_ACTIVE = 1;
    const NOT_DELETED = 0;

    /**
     * Lấy chi phí phát sinh
     *
     * @return mixed
     */
    public function getCostType()
    {
        $lang = app()->getLocale();

        return $this
            ->select(
                "maintenance_cost_type_id",
                "maintenance_cost_type_name_$lang as maintenance_cost_type_name"
            )
            ->where("is_active", self::IS_ACTIVE)
            ->where("is_delete", self::NOT_DELETED)
            ->get();
    }
}