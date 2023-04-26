<?php
/**
 * Created by PhpStorm
 * User: Mr Son
 * Date: 9/10/2020
 * Time: 4:00 PM
 */

namespace Modules\Order\Models;


use Illuminate\Database\Eloquent\Model;

class PaymentMethodTable extends Model
{
    protected $table = "payment_method";
    protected $primaryKey = "payment_method_id";

    const IS_ACTIVE = 1;
    const MEMBER_CARD = 'MEMBER_CARD';
    const MEMBER_MONEY = 'MEMBER_MONEY';
    const NOT_DELETED = 0;

    /**
     * Lấy hình thức thanh toán
     *
     * @param $lang
     * @return mixed
     */
    public function getPaymentMethod($lang)
    {
        return $this
            ->select(
                "payment_method_id",
                "payment_method_name_$lang as payment_method_name",
                "payment_method_code"
            )
            ->where("is_active", self::IS_ACTIVE)
            ->whereNotIn("payment_method_code", [self::MEMBER_CARD, self::MEMBER_MONEY])
            ->where("is_delete", self::NOT_DELETED)
            ->get();
    }

    /**
     * Lấy thông tin phương thức thanh toán bằng code
     *
     * @param $paymentMethodCode
     * @return mixed
     */
    public function getInfoByCode($paymentMethodCode)
    {
        $lang = app()->getLocale();

        return $this
            ->select(
                "payment_method_id",
                "payment_method_name_$lang as payment_method_name",
                "payment_method_code",
                "note",
                "payment_method_type"
            )
            ->where("is_active", self::IS_ACTIVE)
            ->where("payment_method_code", $paymentMethodCode)
            ->first();
    }
}