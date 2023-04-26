<?php
/**
 * Created by PhpStorm   .
 * User: Mr Son
 * Date: 2020-01-07
 * Time: 5:17 PM
 * @author SonDepTrai
 */

namespace Modules\CustomerLead\Models;


use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderDetailTable extends Model
{
    protected $table = 'order_details';
    protected $primaryKey = 'order_detail_id';
    protected $fillable = [
        'order_detail_id',
        'order_id',
        'object_id',
        'object_name',
        'object_type',
        'object_code',
        'staff_id',
        'refer_id',
        'price',
        'quantity',
        'discount',
        'amount',
        'voucher_code',
        'updated_at',
        'created_at',
        'is_deleted',
        'created_by',
        'updated_by'
    ];

    protected $casts = [
        'price' => 'float',
        'discount' => 'float',
        'amount' => 'float',
    ];

    /**
     * Chi tiết đơn hàng
     *
     * @param $orderId
     * @param $customerId
     * @return mixed
     */
    public function getListOrder($orderId)
    {
        return $this
            ->select(
                "object_name",
                "object_type",
                "object_code",
                "staffs.full_name as staff_name",
                "customers.full_name as refer_name",
                "{$this->table}.price",
                "{$this->table}.quantity",
                "{$this->table}.discount",
                "{$this->table}.amount",
                "{$this->table}.voucher_code",
                "{$this->table}.created_at"

            )
            ->join("orders", "orders.order_id", "=", "{$this->table}.order_id")
            ->leftJoin("staffs", "staffs.staff_id", "=", "{$this->table}.staff_id")
            ->leftJoin("customers", "customers.customer_id", "=", "{$this->table}.refer_id")
            ->where("{$this->table}.is_deleted", 0)
            ->where("{$this->table}.order_id", $orderId)
            ->get();
    }



}