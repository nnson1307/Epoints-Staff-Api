<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 27/09/2022
 * Time: 21:11
 */

namespace Modules\Warranty\Models;


use Illuminate\Database\Eloquent\Model;

class MaintenanceImageTable extends Model
{
    protected $table = "maintenance_images";
    protected $primaryKey = "maintenance_image_id";

    const NOT_DELETED = 0;

    /**
     * Lấy hình ảnh của phiếu bảo trì
     *
     * @param $maintenanceCode
     * @return mixed
     */
    public function getImage($maintenanceCode)
    {
        return $this
            ->select(
                "maintenance_image_id",
                "maintenance_code",
                "type",
                "link"
            )
            ->where("is_deleted", self::NOT_DELETED)
            ->where("maintenance_code", $maintenanceCode)
            ->get();
    }

    /**
     * Xóa tất cả hình ảnh của phiếu bảo trì
     *
     * @param $maintenanceCode
     * @return mixed
     */
    public function removeImage($maintenanceCode)
    {
        return $this->where("maintenance_code", $maintenanceCode)->delete();
    }
}