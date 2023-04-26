<?php
/**
 * Created by PhpStorm
 * User: Mr Son
 * Date: 1/19/2021
 * Time: 4:10 PM
 */

namespace Modules\Home\Models;


use Illuminate\Database\Eloquent\Model;

class ServiceTable extends Model
{
    protected $table = "services";
    protected $primaryKey = "service_id";

    /**
     * Lấy thông tin dịch vụ khuyến mãi
     *
     * @param $serviceCode
     * @return mixed
     */
    public function getServicePromotion($serviceCode)
    {
        return $this
            ->select(
                "service_id",
                "service_name",
                "service_code",
                "price_standard as new_price"
            )
            ->where("service_code", $serviceCode)
            ->first();
    }
}