<?php
/**
 * Created by PhpStorm   .
 * User: Mr Son
 * Date: 2020-03-19
 * Time: 5:23 PM
 * @author SonDepTrai
 */

namespace Modules\Order\Models;


use Illuminate\Database\Eloquent\Model;

class ReceiptDetailTable extends Model
{
    protected $table = 'receipt_details';
    protected $primaryKey = 'receipt_detail_id';
    protected $fillable = [
        "receipt_detail_id",
        "receipt_id",
        "cashier_id",
        "receipt_type",
        "amount",
        "note",
        "card_code",
        "payment_method_code",
        "created_by",
        "updated_by",
        "created_at",
        "updated_at"
    ];

    const NOT_DELETED = 0;

    /**
     * Lấy chi tiết thanh toán bằng order_id
     *
     * @param $orderId
     * @return mixed
     */
    public function getDetailByOrder($orderId)
    {
        $lang = app()->getLocale();

        return $this
            ->select(
                "{$this->table}.amount",
                "{$this->table}.note",
                "{$this->table}.card_code",
                "receipts.status",
                "payment_method.payment_method_name_$lang as receipt_type"
            )
            ->join("receipts", "receipts.receipt_id", "=", "{$this->table}.receipt_id")
            ->join("payment_method", "payment_method.payment_method_code", "=", "{$this->table}.payment_method_code")
            ->where("receipts.order_id", $orderId)
            ->where("payment_method.is_delete", self::NOT_DELETED)
            ->get();
    }

    /**
     * Thêm chi tiết phiếu thu
     *
     * @param array $data
     * @return mixed
     */
    public function add(array $data)
    {
        return $this->create($data);
    }


    public function getItem($id)
    {
        $lang = \Config::get('app.locale');

        return $this
            ->select(
                "{$this->table}.receipt_detail_id",
                "{$this->table}.receipt_id",
                "{$this->table}.cashier_id",
                "{$this->table}.receipt_type",
                "{$this->table}.amount",
                "{$this->table}.card_code",
                "{$this->table}.created_at",
                "payment_method.payment_method_code",
                "payment_method.payment_method_name_$lang as payment_method_name"
            )
            ->leftJoin("payment_method", "payment_method.payment_method_code", "=", "{$this->table}.payment_method_code")
            ->where("{$this->table}.receipt_id", $id)
            ->get();
    }

}