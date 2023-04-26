<?php


namespace Modules\Order\Models;


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
            ->where(function ($query) use ($staffId) {
//                $query->where("{$this->table}.created_by", $staffId);
//                    ->orWhere('customers.phone1', 'like', '%' . $search . '%');
            })
            ->where("{$this->table}.is_deleted", 0)
            ->where("branches.is_deleted", 0)
            ->orderBy("{$this->table}.created_at", "desc");
        // get số trang
        $page = (int)($filter["page"] ?? 1);

        // filter type
//        if ($filter["type"] == "current") {
//            $ds->where(function ($query) {
//                $query->whereNotNull("deliveries.delivery_status")
//                    ->whereIn("deliveries.delivery_status", ["packing", "preparing", "delivering"]);
//            });
//        } else if ($filter["type"] == "older") {
//            $ds->where(function ($query) {
//                $query->whereNull("deliveries.delivery_status")
//                    ->orWhere("deliveries.delivery_status", "delivered");
//            });
//        }

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

    /**
     * Thông tin đơn hàng
     *
     * @param $orderId
     * @return mixed
     */
    public function orderInfo($orderId)
    {
        $textTransportSave = __('Tiết kiệm');
        $textTransportSpeed = __('Hoả tốc');

        return $this
            ->select(
                "{$this->table}.order_id",
                "{$this->table}.order_code",
                "{$this->table}.branch_id",
                "branches.branch_name",
                "refer.full_name as refer_name",
                "{$this->table}.total",
                "{$this->table}.discount",
                "{$this->table}.tranport_charge",
                "{$this->table}.amount",
                "{$this->table}.process_status",
                "{$this->table}.voucher_code",
                "{$this->table}.discount_member",
                "{$this->table}.created_at as order_date",
                "deliveries.contact_address",
                "deliveries.delivery_status",
                "deliveries.is_actived as delivery_active",
                "{$this->table}.customer_contact_code",
                "customer_contacts.contact_name",
                "customer_contacts.contact_phone",
                "customer_contacts.contact_email",
                "customer_contacts.full_address",
                "payment_method.payment_method_name_vi as payment_method_name",
                "{$this->table}.order_description",
                "{$this->table}.payment_method_id",
                "customer_contacts.postcode",
                "province.name as province_name",
                "district.name as district_name",
                "{$this->table}.created_at",
                "customers.full_name",
                "customers.phone1 as phone",
                "{$this->table}.customer_id",
                "customers.customer_code",
                "{$this->table}.order_source_id",
                "{$this->table}.receive_at_counter",
                "{$this->table}.type_shipping",
                "{$this->table}.delivery_cost_id",
                DB::raw("(CASE
                    WHEN  orders.type_shipping = 1 THEN '$textTransportSpeed'
                    ELSE  '$textTransportSave' 
                    END
                ) as type_shipping_text")
            )
            ->join("branches", function ($join) {
                $join->on("branches.branch_id", "=", "{$this->table}.branch_id")
                    ->where("branches.is_deleted", 0);
            })
            ->join("customers", "customers.customer_id", "=", "{$this->table}.customer_id")
            ->leftJoin("customers as refer", "refer.customer_id", "=", "{$this->table}.refer_id")
            ->leftJoin("deliveries", "deliveries.order_id", "=", "{$this->table}.order_id")
            ->leftJoin("customer_contacts", "customer_contacts.customer_contact_code", "=", "{$this->table}.customer_contact_code")
            ->leftJoin("payment_method", "payment_method.payment_method_id", "=", "{$this->table}.payment_method_id")
            ->leftJoin("province", "province.provinceid", "=", "customer_contacts.province_id")
            ->leftJoin("district", "district.districtid", "=", "customer_contacts.district_id")
            ->where("{$this->table}.is_deleted", 0)
            ->where("{$this->table}.order_id", $orderId)
            ->first();
    }

    /**
     * Thêm đơn hàng
     *
     * @param array $data
     * @return mixed
     */
    public function add(array $data)
    {
        $add = $this->create($data);
        return $add->order_id;
    }

    /**
     * Chỉnh sửa đơn hàng
     *
     * @param array $data
     * @param $orderId
     * @return mixed
     */
    public function edit(array $data, $orderId)
    {
        return $this->where("order_id", $orderId)->update($data);
    }

    /**
     * Đêm số đơn hàng đã xóa trong ngày
     *
     * @param $date
     * @return mixed
     */
    public function numberOrderCancel($date)
    {
        return $this
            ->select(
                "order_id",
                "order_code",
                "process_status"
            )
            ->whereDate("created_at", $date)
            ->where("process_status", "ordercancle")
            ->get()
            ->count();
    }

    /**
     * Lấy thông tin đơn hàng bằng order_code
     *
     * @param $orderCode
     * @return mixed
     */
    public function getOrderByCode($orderCode)
    {
        return $this
            ->select(
                "{$this->table}.order_id",
                "{$this->table}.order_code",
                "{$this->table}.total",
                "{$this->table}.discount",
                "{$this->table}.amount",
                "{$this->table}.order_source_id",
                "{$this->table}.receive_at_counter",
                "{$this->table}.process_status",
                "cs.customer_id",
                'cs.customer_code',
                "cs.full_name",
                "cs.phone1 as phone"
            )
            ->join("customers as cs", "cs.customer_id", "=", "{$this->table}.customer_id")
            ->where("{$this->table}.order_code", $orderCode)
            ->first();
    }

    /**
     * Thông tin đơn hàng ngoài middleware
     *
     * @param $orderId
     * @param $customerId
     * @return mixed
     */
    public function orderItem($orderId)
    {
        return $this
            ->select(
                "{$this->table}.order_id",
                "{$this->table}.order_code",
                "{$this->table}.customer_id",
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
                "customer_contacts.contact_name",
                "customer_contacts.contact_phone",
                "customer_contacts.contact_email",
                "customer_contacts.full_address",
                "payment_method.payment_method_name_vi as payment_method_name",
                "{$this->table}.order_description",
                "{$this->table}.receive_at_counter"
            )
            ->join("branches", function ($join) {
                $join->on("branches.branch_id", "=", "{$this->table}.branch_id")
                    ->where("branches.is_deleted", 0);
            })
            ->leftJoin("customers as refer", "refer.customer_id", "=", "{$this->table}.refer_id")
            ->leftJoin("deliveries", "deliveries.order_id", "=", "{$this->table}.order_id")
            ->leftJoin("customer_contacts", "customer_contacts.customer_contact_code", "=", "{$this->table}.customer_contact_code")
            ->leftJoin("payment_method", "payment_method.payment_method_id", "=", "{$this->table}.payment_method_id")
            ->where("{$this->table}.is_deleted", 0)
            ->where("{$this->table}.order_id", $orderId)
            ->first();
    }

    public function getItemDetail($id)
    {
        $ds = $this
            ->select(
                'customers.full_name as full_name',
                'customers.phone1 as phone',
                'customers.address as address',
                'customers.customer_avatar as customer_avatar',
                'customers.customer_id as customer_id',
                'customers.phone1 as phone1',
                'orders.order_code as order_code',
                'orders.total as total',
                'orders.discount as discount',
                'orders.tranport_charge as tranport_charge',
                'orders.voucher_code as voucher_code',
                'orders.amount as amount',
                'orders.process_status as process_status',
                'orders.order_id as order_id',
                'receipts.amount_paid as amount_paid',
                'customers.gender as gender',
                'orders.order_id as order_id',
                'receipts.note as note',
                'receipts.receipt_id',
                'orders.refer_id',
                'customers.member_level_id',
                'member_levels.name as member_level_name',
                'member_levels.discount as member_level_discount',
                'orders.discount_member',
                'orders.branch_id',
                'orders.order_source_id',
                'deliveries.is_actived as delivery_active',
                'deliveries.delivery_id',
                'orders.tranport_charge',
                'orders.shipping_address',
                'customer_groups.group_name as group_name_cus',
                'orders.customer_contact_code',
                'customer_contacts.postcode',
                'customer_contacts.full_address',
                'province.name as province_name',
                'district.name as district_name',
                'orders.receive_at_counter',
                'orders.created_at',
                'staffs.full_name as staff_name',
                'customers.profile_code',
                'customers.customer_code',
                "{$this->table}.receive_at_counter",
                "{$this->table}.delivery_request_date",
                "{$this->table}.order_description",
                "{$this->table}.blessing",
                "{$this->table}.customer_contact_id",
                "{$this->table}.receipt_info_check",
                "{$this->table}.type_time",
                "{$this->table}.time_address",
                "{$this->table}.type_shipping"
            )
            ->leftJoin('customers', 'customers.customer_id', '=', 'orders.customer_id')
            ->leftJoin('customer_groups', 'customer_groups.customer_group_id', '=', 'customers.customer_group_id')
            ->leftJoin('receipts', 'receipts.order_id', '=', 'orders.order_id')
            ->leftJoin('member_levels', 'member_levels.member_level_id', '=', 'customers.member_level_id')
            ->leftJoin('deliveries', 'deliveries.order_id', '=', 'orders.order_id')
            ->leftJoin('customer_contacts', 'customer_contacts.customer_contact_code', '=', 'orders.customer_contact_code')
            ->leftJoin('province', 'customer_contacts.province_id', '=', 'province.provinceid')
//            ->leftJoin('province',DB::raw("CONVERT(province.provinceid, INT)"), 'customer_contacts.province_id')
            ->leftJoin('district', 'customer_contacts.district_id', '=', 'district.districtid')
//            ->leftJoin('district', DB::raw("CONVERT(district.districtid, INT)"),'customer_contacts.district_id')
            ->leftJoin('staffs', 'staffs.staff_id', '=', 'orders.created_by')
            ->where('orders.order_id', $id);
        if (Auth::user()->is_admin != 1) {
            $ds->where('orders.branch_id', Auth::user()->branch_id);
        }
        return $ds->first();
    }
}