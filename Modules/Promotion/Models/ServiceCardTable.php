<?php


namespace Modules\Promotion\Models;


use Illuminate\Database\Eloquent\Model;

class ServiceCardTable extends Model
{
    protected $table = "service_cards";
    protected $primaryKey = "service_card_id";

    /**
     * Lấy chi tiết thẻ dịch vụ
     *
     * @param $serviceCardCode
     * @return mixed
     */
    public function getDetail($serviceCardCode)
    {
        return $this
            ->select(
                "service_card_id",
                "service_card_group_id",
                "name",
                "service_is_all",
                "service_id",
                "service_card_type",
                "date_using",
                "number_using",
                "price",
                "money",
                "image",
                "code",
                "description"
            )
            ->where("code", $serviceCardCode)
            ->first();
    }
}