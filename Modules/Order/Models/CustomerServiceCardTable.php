<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 25/05/2021
 * Time: 16:01
 */

namespace Modules\Order\Models;


use Illuminate\Database\Eloquent\Model;

class CustomerServiceCardTable extends Model
{
    protected $table = 'customer_service_cards';
    protected $primaryKey = "customer_service_card_id";
    protected $fillable = [
        'customer_service_card_id',
        'customer_id',
        'card_code',
        'service_card_id',
        'actived_date',
        'expired_date',
        'number_using',
        'count_using',
        'money',
        'created_by',
        'updated_by',
        'created_at',
        'updated_at',
        'is_actived',
        'branch_id',
        'is_deleted',
        'note',
        'is_reserve',
        'date_reserve',
        'number_days_remain_reserve'
    ];

    /**
     * Lấy thông tin hoa hồng thẻ liệu trình
     *
     * @param $cardCode
     * @return mixed
     */
    public function getCommissionMemberCard($cardCode)
    {
        return $this
            ->select(
                "{$this->table}.card_code",
                "service_cards.name",
                "service_cards.type_refer_commission",
                "service_cards.refer_commission_value",
                "service_cards.type_staff_commission",
                "service_cards.staff_commission_value",
                "service_cards.price"
            )
            ->join("service_cards", "service_cards.service_card_id", "=", "{$this->table}.service_card_id")
            ->where("{$this->table}.card_code", $cardCode)
            ->where("service_cards.is_deleted", 0)
            ->first();
    }
}