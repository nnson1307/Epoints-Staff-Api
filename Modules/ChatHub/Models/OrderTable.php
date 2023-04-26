<?php


namespace Modules\ChatHub\Models;


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
     * Lấy danh sách đơn hàng
     *
     * @param $filter
     * @param $staffId
     * @return mixed
     */
    public function getOrders($filter, $staffId)
    {
        $ds = $this
            ->select(
                "{$this->table}.order_id",
                "{$this->table}.order_code",
                "branches.branch_name",
                "refer.full_name as refer_name",
                "{$this->table}.total",
                "{$this->table}.discount",
                "{$this->table}.amount",
                "{$this->table}.process_status",
                "{$this->table}.voucher_code",
                "{$this->table}.discount_member",
                "{$this->table}.created_at as order_date",
                "deliveries.contact_address",
                "deliveries.delivery_status",
                "deliveries.is_actived as delivery_active",
                "customers.full_name",
                "customers.phone1 as phone",
                "{$this->table}.customer_id",
                "{$this->table}.created_at",
                'receipts.amount_paid as amount_paid',
            )
            ->join("branches", "branches.branch_id", "=", "{$this->table}.branch_id")
            ->join("customers", "customers.customer_id", "=", "{$this->table}.customer_id")
            ->leftJoin("customers as refer", "refer.customer_id", "=", "{$this->table}.refer_id")
            ->leftJoin("deliveries", "deliveries.order_id", "=", "{$this->table}.order_id")
            ->leftJoin('receipts', 'receipts.order_id', '=', "{$this->table}.order_id")

            ->where("{$this->table}.is_deleted", 0)
            ->where("branches.is_deleted", 0)
            ->orderBy("{$this->table}.created_at", "desc");
        // get số trang
        $page = (int)($filter["page"] ?? 1);

        // filter search
        if (isset($filter["search"]) && $filter["search"] != null) {
            $search = $filter["search"];

            $ds->where(function ($query) use ($search) {
                $query->where("{$this->table}.order_code",  'like', '%' . $search . '%')
                    ->orWhere("customers.full_name", 'like', '%' . $search . '%')
                    ->orWhere("customers.phone1", 'like', '%' . $search . '%');
            });
        }

        // filter branch
        if (isset($filter["branch_id"]) && $filter["branch_id"] > 0) {
            $ds->where("{$this->table}.branch_id", $filter["branch_id"]);
        }

        // filter created at
        if (isset($filter["created_at"]) && $filter["created_at"] != null) {
            $arr_filter = explode(" - ", $filter["created_at"]);
            $startTime = Carbon::createFromFormat("d/m/Y", $arr_filter[0])->format("Y-m-d");
            $endTime = Carbon::createFromFormat("d/m/Y", $arr_filter[1])->format("Y-m-d");
            $ds->whereBetween("{$this->table}.created_at", [$startTime . " 00:00:00", $endTime . " 23:59:59"]);
        }

        // filter status
        if (isset($filter["status"]) && $filter["status"] != null) {
            $ds->where("{$this->table}.process_status", $filter["status"]);
        }

        return $ds->paginate(PAGING_ITEM_PER_PAGE, $columns = ["*"], $pageName = "page", $page);
    }
}