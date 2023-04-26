<?php
/**
 * Created by PhpStorm
 * User: Mr Son
 * Date: 10/20/2020
 * Time: 3:01 PM
 */

namespace Modules\Order\Models;


use Illuminate\Database\Eloquent\Model;

class ServiceCardTable extends Model
{
    protected $table = "service_cards";
    protected $primaryKey = "service_card_id";

    const SURCHARGE = 0;

    /**
     * Lấy thông tin thẻ dịch vụ
     *
     * @param $serviceCardCode
     * @return mixed
     */
    public function getServiceCard($serviceCardCode)
    {
        return $this
            ->select(
                "service_card_id",
                "name",
                "price",
                "image"
            )
            ->where("code", $serviceCardCode)
            ->where("is_surcharge", self::SURCHARGE)
            ->firat();
    }

    /**
     * Lấy thông tin thẻ dịch vụ khuyến mãi
     *
     * @param $serviceCardCode
     * @return mixed
     */
    public function getServiceCardPromotion($serviceCardCode)
    {
        return $this
            ->select(
                "service_card_id",
                "name",
                "code",
                "price as new_price"
            )
            ->where("code", $serviceCardCode)
            ->where("is_surcharge", self::SURCHARGE)
            ->first();
    }

    /**
     * Lấy thông tin thẻ dịch vụ
     *
     * @param $serviceCardId
     * @return mixed
     */
    public function getInfo($serviceCardId)
    {
        return $this
            ->select(
                "service_card_id",
                "name",
                "code",
                "price as new_price",
                "type_refer_commission",
                "refer_commission_value",
                "type_staff_commission",
                "staff_commission_value"
            )
            ->where("service_card_id", $serviceCardId)
            ->where("is_surcharge", self::SURCHARGE)
            ->first();
    }
}