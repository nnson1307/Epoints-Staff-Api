<?php


namespace Modules\CustomerLead\Models;


use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderTable extends Model
{
    protected $table = "orders";
    protected $primaryKey = "order_id";
    protected $fillable = [
        "order_id",
        "order_code",
        "customer_id",
        "branch_id",
        "refer_id",
        "total",
        "discount",
        "amount",
        "tranport_charge",
        "created_by",
        "updated_by",
        "created_at",
        "updated_at",
        "process_status",
        "order_description",
        "customer_description",
        "payment_method_id",
        "order_source_id",
        "transport_id",
        "voucher_code",
        "discount_member",
        "is_deleted",
        "customer_contact_code",
        "receive_at_counter",
        "delivery_request_date",
        "blessing",
        "type_shipping",
        "delivery_cost_id"
    ];

    protected $casts = [
        'total' => 'float',
        'discount' => 'float',
        'amount' => 'float',
        'discount_member' => 'float',
        'tranport_charge' => 'float'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        "updated_at"
    ];


    /**
     * Lấy thông tin đơn hàng bằng order_code
     *
     * @param $orderCode
     * @return mixed
     */
    public function getListOrderByDealCode($input)
    {
        return $this
            ->select(
                "{$this->table}.order_id",
                "{$this->table}.order_code",
                "{$this->table}.total",
                "{$this->table}.discount",
                "{$this->table}.amount",
                "{$this->table}.branch_id",
                "branches.branch_name",
                "{$this->table}.order_source_id",
                "{$this->table}.receive_at_counter",
                "{$this->table}.process_status",
                "{$this->table}.created_at",
                "{$this->table}.delivery_request_date",
                "{$this->table}.shipping_address",
                "{$this->table}.contact_name",
                "{$this->table}.contact_phone",
                "{$this->table}.pickup_time",
                "{$this->table}.deal_code",
                "{$this->table}.cashier_date",
                "{$this->table}.time_address",
                "cs.customer_id",
                'cs.customer_code',
                "cs.full_name",
                "cs.phone1 as phone"
            )
            ->join("customers as cs", "cs.customer_id", "=", "{$this->table}.customer_id")
            ->leftJoin("branches", "branches.branch_id", "=", "{$this->table}.branch_id")
            ->where("{$this->table}.deal_code", $input['deal_code'])
            ->get();
    }


}