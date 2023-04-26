<?php
/**
 * Created by PhpStorm
 * User: Mr Son
 * Date: 10/2/2020
 * Time: 12:00 PM
 */

namespace Modules\Order\Models;


use Illuminate\Database\Eloquent\Model;

class ServiceCardListTable extends Model
{
    protected $table = "service_card_list";
    protected $primaryKey = "service_card_list_id";

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
                "{$this->table}.service_card_list_id",
                "service_cards.name",
                "service_cards.code",
                "service_cards.price"
            )
            ->join("service_cards", "service_cards.service_card_id", "=", "{$this->table}.service_card_id")
            ->where("{$this->table}.code", $serviceCardCode)
            ->first();
    }
}