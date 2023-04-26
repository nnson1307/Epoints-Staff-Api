<?php
/**
 * Created by PhpStorm   .
 * User: Mr Son
 * Date: 2020-01-07
 * Time: 5:17 PM
 * @author SonDepTrai
 */

namespace Modules\Product\Models;


use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

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

    /**
     * Chi tiết đơn hàng
     *
     * @param $orderId
     * @param $customerId
     * @return mixed
     */
    public function orderDetail($orderId, $customerId)
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
                "{$this->table}.created_at as created_at"
            )
            ->join("orders", "orders.order_id", "=", "{$this->table}.order_id")
            ->leftJoin("staffs", "staffs.staff_id", "=", "{$this->table}.staff_id")
            ->leftJoin("customers", "customers.customer_id", "=", "{$this->table}.refer_id")
            ->where("{$this->table}.is_deleted", 0)
            ->where("orders.customer_id", $customerId)
            ->where("{$this->table}.order_id", $orderId)
            ->get();
    }

    /**
     * Danh sách sản phẩm, dịch vụ theo type
     *
     * @param $filter
     * @param $customerId
     * @param $objectType
     * @return mixed
     */
    public function getDetails($filter, $customerId, $objectType)
    {
        $ds = $this
            ->select(
                "branches.branch_name",
                "object_id as product_id",
                "object_name as product_name",
                "object_code as product_code",
                "{$this->table}.price",
                "{$this->table}.quantity",
                "{$this->table}.discount",
                "{$this->table}.amount",
                "{$this->table}.voucher_code",
                "{$this->table}.created_at as date_buy"
            )
            ->join("orders", "orders.order_id", "=", "{$this->table}.order_id")
            ->join("branches", "branches.branch_id", "=", "orders.branch_id")
            ->where("{$this->table}.object_type", $objectType)
            ->where("orders.process_status", "paysuccess")
            ->where("orders.customer_id", $customerId)
            ->where("{$this->table}.is_deleted", 0)
            ->where("branches.is_deleted", 0);
        // get số trang
        $page = (int)($filter['page'] ?? 1);

        // filter branch
        if (isset($filter['branch_id']) && $filter['branch_id'] > 0) {
            $ds->where("orders.branch_id", $filter['branch_id']);
        }

        // filter created at
        if (isset($filter['created_at']) && $filter['created_at'] != null) {
            $arr_filter = explode(" - ", $filter['created_at']);
            $startTime = Carbon::createFromFormat('d/m/Y', $arr_filter[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $arr_filter[1])->format('Y-m-d');
            $ds->whereBetween("{$this->table}.created_at", [$startTime . ' 00:00:00', $endTime . ' 23:59:59']);
        }

        return $ds->paginate(PAGING_ITEM_PER_PAGE, $columns = ["*"], $pageName = 'page', $page);
    }

    /**
     * Thêm chi tiết đơn hàng
     *
     * @param array $data
     * @return mixed
     */
    public function add(array $data)
    {
        return $this->create($data);
    }
}