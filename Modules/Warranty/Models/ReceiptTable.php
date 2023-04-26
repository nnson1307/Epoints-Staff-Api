<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 27/09/2022
 * Time: 22:18
 */

namespace Modules\Warranty\Models;


use Illuminate\Database\Eloquent\Model;

class ReceiptTable extends Model
{
    protected $table = "receipts";
    protected $primaryKey = "receipt_id";
    protected $fillable = [
        "receipt_id",
        "receipt_code",
        "customer_id",
        "staff_id",
        "object_type",
        "object_id",
        "order_id",
        "total_money",
        "voucher_code",
        "status",
        "discount",
        "custom_discount",
        "is_discount",
        "amount",
        "amount_paid",
        "amount_return",
        "note",
        "updated_by",
        "created_at",
        "updated_at",
        "created_by",
        "discount_member",
        "receipt_source",
        "receipt_type_code",
        "object_accounting_type_code",
        "object_accounting_id",
        "object_accounting_name",
        "type_insert",
        "document_code"
    ];

    const PAID = "paid";
    const CANCEL = "cancel";
    const TYPE_MAINTENANCE = "RTC_MAINTENANCE";

    /**
     * Thêm phiếu thanh toán
     *
     * @param array $data
     * @return mixed
     */
    public function add(array $data)
    {
        return $this->create($data)->receipt_id;
    }

    /**
     * Chỉnh sửa phiếu thanh toán
     *
     * @param array $data
     * @param $receiptId
     * @return mixed
     */
    public function edit(array $data, $receiptId)
    {
        return $this->where("receipt_id", $receiptId)->update($data);
    }

    /**
     * Lấy thông tin thanh toán của phiếu bảo trì
     *
     * @param $objectType
     * @param $objectId
     * @return mixed
     */
    public function getReceipt($objectType, $objectId)
    {
        return $this
            ->select(
                "receipt_id",
                "receipt_code",
                "customer_id",
                "total_money",
                "amount",
                "amount_paid",
                "amount_return",
                "status"
            )
            ->where(function ($query) use ($objectType, $objectId) {
                $query->whereRaw("object_type = 'maintenance' and object_id = $objectId ")
                    ->orWhereRaw("receipt_type_code = 'RTC_MAINTENANCE' and object_accounting_id = $objectId ");
            })
            ->where("status", self::PAID)
            ->get();
    }

    /**
     * Hủy tất cả thanh toán của phiếu bảo trì
     *
     * @param $objectType
     * @param $objectId
     * @return mixed
     */
    public function cancelReceipt($objectType, $objectId)
    {
        return $this
            ->where("object_type", $objectType)
            ->where("object_id", $objectId)
            ->update([
                "status" => self::CANCEL
            ]);
    }
}