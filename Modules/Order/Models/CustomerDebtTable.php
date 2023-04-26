<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 25/05/2021
 * Time: 10:35
 */

namespace Modules\Order\Models;


use Illuminate\Database\Eloquent\Model;

class CustomerDebtTable extends Model
{
    protected $table = "customer_debt";
    protected $primaryKey = "customer_debt_id";
    protected $fillable = [
        "customer_debt_id",
        "debt_code",
        "customer_id",
        "staff_id",
        "branch_id",
        "debt_type",
        "order_id",
        "status",
        "amount",
        "amount_paid",
        "note",
        "created_by",
        "updated_by",
        "created_at",
        "updated_at"
    ];

    /**
     * Thêm công nợ
     *
     * @param array $data
     * @return mixed
     */
    public function add(array $data)
    {
        return $this->create($data)->customer_debt_id;
    }

    /**
     * Chỉnh sửa công nợ
     *
     * @param array $data
     * @param $debtId
     * @return mixed
     */
    public function edit(array $data, $debtId)
    {
        return $this->where("customer_debt_id", $debtId)->update($data);
    }

       /**
     * Chỉnh sửa công nợ
     *
     * @param array $data
     * @param $debtId
     * @return mixed
     */
    public function editByOrder(array $data, $orderId)
    {
        return $this->where("order_id", $orderId)->update($data);
    }

}