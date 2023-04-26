<?php
/**
 * Created by PhpStorm
 * User: Mr Son
 * Date: 10/12/2020
 * Time: 3:19 PM
 */

namespace Modules\Order\Models;


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
        "branch_id",
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
        "discount_member",
        "receipt_source",
        "receipt_type_code",
        "object_accounting_type_code",
        "object_accounting_id",
        "object_accounting_name",
        "type_insert",
        "document_code",
        "updated_by",
        "created_at",
        "updated_at",
        "created_by"
    ];

    /**
     * Tạo phiếu thu
     *
     * @param $data
     * @return mixed
     */
    public function add(array $data)
    {
        return $this->create($data)->receipt_id;
    }

    /**
     * Chỉnh sửa phiếu thu
     *
     * @param array $data
     * @param $receiptId
     * @return mixed
     */
    public function edit(array $data, $receiptId)
    {
        return $this->where("receipt_id", $receiptId)->update($data);
    }

    public function getItem($id)
    {
        $ds = $this->leftJoin("staffs", "staffs.staff_id", "=", "receipts.created_by")
            ->select(
                "receipts.receipt_id",
                "receipts.receipt_code",
                "receipts.customer_id",
                "receipts.order_id",
                "receipts.amount",
//                "receipts.amount_paid",
                \DB::raw("SUM(amount_paid) as amount_paid"),
                "receipts.created_at",
                "receipts.created_by",
                "receipts.amount_return",
                "staffs.full_name"
            )->where("receipts.order_id", $id)->first();
        return $ds;
    }

    public function editByOrder(array $data, $orderId)
    {
        return $this->where("order_id", $orderId)->update($data);
    }
}