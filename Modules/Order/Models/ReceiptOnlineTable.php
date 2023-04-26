<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 23/12/2021
 * Time: 11:41
 */

namespace Modules\Order\Models;


use Illuminate\Database\Eloquent\Model;

class ReceiptOnlineTable extends Model
{
    protected $table = "receipt_online";
    protected $primaryKey = "receipt_online_id";
    protected $fillable = [
        "receipt_online_id",
        "receipt_id",
        "object_type",
        "object_id",
        "object_code",
        "payment_method_code",
        "amount_paid",
        "payment_transaction_code",
        "payment_transaction_uuid",
        "payment_time",
        "status",
        "performer_name",
        "performer_phone",
        "type",
        "note",
        "platform",
        "created_at",
        "updated_at"
    ];

    /**
     * Thêm đợt thanh toán online
     *
     * @param array $data
     * @return mixed
     */
    public function add(array $data)
    {
        return $this->create($data)->receipt_online_id;
    }

    /**
     * Chỉnh sửa phương thức thanh toán
     *
     * @param array $data
     * @param $transactionCode
     * @return mixed
     */
    public function editByCode(array $data, $transactionCode)
    {
        return $this->where("payment_transaction_code", $transactionCode)->update($data);
    }

    /**
     * Lấy thông tin giao dịch
     *
     * @param $transactionCode
     * @return mixed
     */
    public function getInfoByCode($transactionCode)
    {
        return $this
            ->select(
                "receipt_online_id",
                "receipt_id",
                "object_type",
                "object_id",
                "object_code",
                "payment_method_code",
                "amount_paid",
                "payment_transaction_code",
                "payment_transaction_uuid",
                "payment_time",
                "status",
                "performer_name",
                "performer_phone",
                "type",
                "note",
                "platform"
            )
            ->where("payment_transaction_code", $transactionCode)
            ->first();
    }
}