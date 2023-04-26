<?php


namespace Modules\Promotion\Models;


use Illuminate\Database\Eloquent\Model;

class ServiceTable extends Model
{
    protected $table = "services";
    protected $primaryKey = "service_id";

    /**
     * Lấy chi tiết dịch vụ
     *
     * @param $serviceCode
     * @return mixed
     */
    public function getDetail($serviceCode)
    {
        return $this
            ->select(
                "service_id",
                "service_name",
                "service_code",
                "service_category_id",
                "service_avatar"
            )
            ->where("service_code", $serviceCode)
            ->first();
    }
}