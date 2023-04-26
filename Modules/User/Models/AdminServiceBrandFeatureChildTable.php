<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 17/06/2021
 * Time: 17:17
 */

namespace Modules\User\Models;


use Illuminate\Database\Eloquent\Model;

class AdminServiceBrandFeatureChildTable extends Model
{
    protected $table = "admin_service_brand_feature_child";
    protected $primaryKey = "service_brand_feature_child_id";

    const IS_ACTIVED = 1;
    const ID_APP_STAFF = 59;

    /**
     * Lấy service được cấp phép sử dụng
     *
     * @return mixed
     */
    public function getAllService()
    {
        return $this
            ->select(
                "{$this->table}.feature_code"
            )
            ->join("admin_service_brand_feature as ft", "ft.service_brand_feature_id", "=", "{$this->table}.service_brand_feature_id")
            ->where("ft.is_actived", self::IS_ACTIVED)
            ->where("{$this->table}.is_actived", self::IS_ACTIVED)
            ->where("{$this->table}.feature_group_id", self::ID_APP_STAFF)
            ->groupBy("{$this->table}.feature_code")
            ->get();
    }

    /**
     * Lấy tất cả dịch vụ của app
     *
     * @return mixed
     */
    public function getTotalService()
    {
        return $this
            ->select(
                "{$this->table}.feature_code"
            )
            ->join("admin_service_brand_feature as ft", "ft.service_brand_feature_id", "=", "{$this->table}.service_brand_feature_id")
            ->where("ft.is_actived", self::IS_ACTIVED)
            ->where("{$this->table}.is_actived", self::IS_ACTIVED)
            ->groupBy("{$this->table}.feature_code")
            ->get();
    }
}