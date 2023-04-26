<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 27/09/2022
 * Time: 10:17
 */

namespace Modules\Warranty\Models;


use Illuminate\Database\Eloquent\Model;

class ServiceCardTable extends Model
{
    protected $table = "service_cards";
    protected $primaryKey = "service_card_id";

    /**
     * Lấy thông tin thẻ dịch vụ
     *
     * @param $code
     * @return mixed
     */
    public function getServiceCard($code)
    {
        return $this
            ->select(
                "service_card_id",
                "name",
                "price"
            )
            ->where("code", $code)
            ->first();
    }
}