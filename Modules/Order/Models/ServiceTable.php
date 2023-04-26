<?php
/**
 * Created by PhpStorm
 * User: Mr Son
 * Date: 1/20/2021
 * Time: 2:02 PM
 */

namespace Modules\Order\Models;


use Illuminate\Database\Eloquent\Model;

class ServiceTable extends Model
{
    protected $table = 'services';
    protected $primaryKey = 'service_id';

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

    /**
     * Lấy thông tin dịch vụ
     *
     * @param $serviceId
     * @return mixed
     */
    public function getInfo($serviceId)
    {
        return $this
            ->select(
                "service_id",
                "service_name",
                "service_code",
                "price_standard as new_price",
                "type_refer_commission",
                "refer_commission_value",
                "type_staff_commission",
                "staff_commission_value",
                "type_deal_commission",
                "deal_commission_value"
            )
            ->where("service_id", $serviceId)
            ->first();
    }
}