<?php
/**
 * Created by PhpStorm
 * User: Mr Son
 * Date: 1/19/2021
 * Time: 4:10 PM
 */

namespace Modules\Home\Models;


use Illuminate\Database\Eloquent\Model;

class ServiceCardTable extends Model
{
    protected $table = "service_cards";
    protected $primaryKey = "service_card_id";

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
            ->first();
    }
}