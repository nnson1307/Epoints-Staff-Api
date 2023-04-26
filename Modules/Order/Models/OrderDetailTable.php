<?php
/**
 * Created by PhpStorm   .
 * User: Mr Son
 * Date: 2020-01-07
 * Time: 5:17 PM
 * @author SonDepTrai
 */

namespace Modules\Order\Models;


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
        'updated_by',
        'is_deleted',
        'quantity_type',
        'case_quantity',
        'saving',
        'is_change_price',
        'is_check_promotion',
        "order_detail_id_parent",
        "created_at_day",
        "created_at_month",
        "created_at_year",
        "delivery_date",
        "note"
    ];

    protected $casts = [
        'price' => 'float',
        'discount' => 'float',
        'amount' => 'float',
        'quantity' => 'float'
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

    /**
     * Chi tiết đơn hàng
     *
     * @param $orderId
     * @return mixed
     */
    public function getDetailOrderList($orderId)
    {
        $urlProduct = 'http://' . request()->getHttpHost() . '/static/images/product.png';
        $urlService = 'http://' . request()->getHttpHost() . '/static/images/service.png';
        $urlServiceCard = 'http://' . request()->getHttpHost() . '/static/images/service-card.png';

        return $this
            ->select(
                "{$this->table}.order_detail_id",
                "object_id",
                "object_name",
                "object_type",
                "object_code",
                "{$this->table}.price",
                "{$this->table}.quantity",
                "{$this->table}.discount",
                "{$this->table}.amount",
                "{$this->table}.created_at as created_at",
                DB::raw("(CASE
                    WHEN  {$this->table}.object_type = 'product' && products.avatar IS NOT NULL THEN products.avatar
                    WHEN  {$this->table}.object_type = 'service' && services.service_avatar IS NOT NULL THEN services.service_avatar
                    WHEN  {$this->table}.object_type = 'service_card' && service_cards.image IS NOT NULL THEN service_cards.image
                    
                    WHEN  {$this->table}.object_type = 'product' && products.avatar IS NULL THEN '{$urlProduct}'
                    WHEN  {$this->table}.object_type = 'service' && services.service_avatar IS NULL THEN '{$urlService}'
                    WHEN  {$this->table}.object_type = 'service_card' && service_cards.image IS NULL THEN '{$urlServiceCard}'
                       
                    WHEN  {$this->table}.object_type = 'product_gift' && products.avatar IS NOT NULL THEN products.avatar
                    WHEN  {$this->table}.object_type = 'service_gift' && services.service_avatar IS NOT NULL THEN services.service_avatar
                    WHEN  {$this->table}.object_type = 'service_card_gift' && service_cards.image IS NOT NULL THEN service_cards.image
                    
                    WHEN  {$this->table}.object_type = 'product_gift' && products.avatar IS NULL THEN '{$urlProduct}'
                    WHEN  {$this->table}.object_type = 'service_gift' && services.service_avatar IS NULL THEN '{$urlService}'
                    WHEN  {$this->table}.object_type = 'service_card_gift' && service_cards.image IS NULL THEN '{$urlServiceCard}'
                    
                    END
                ) as object_image"),
                "{$this->table}.is_check_promotion",
                "{$this->table}.is_change_price",
                "{$this->table}.refer_id",
                "{$this->table}.staff_id",
                "{$this->table}.note"
            )
            ->leftJoin("product_childs", "product_childs.product_code", "=", "{$this->table}.object_code")
            ->leftJoin("products", "products.product_id", "=", "product_childs.product_id")
            ->leftJoin("services", "services.service_code", "=", "{$this->table}.object_code")
            ->leftJoin("service_cards", "service_cards.code", "=", "{$this->table}.object_code")
            ->where("{$this->table}.is_deleted", 0)
            ->where("{$this->table}.order_id", $orderId)
            ->orderBy("{$this->table}.order_detail_id", "asc")
            ->get();
    }

    /**
     * Xoá chi tiết đơn hàng
     *
     * @param $orderId
     * @return mixed
     */
    public function removeByOrderId($orderId)
    {
        return $this->where("order_id", $orderId)->delete();
    }

    /**
     * @param $id
     */
    public function getItem($id)
    {
        $ds = $this
            ->leftJoin('orders', 'orders.order_id', '=', 'order_details.order_id')
            ->leftJoin('product_childs', 'product_childs.product_code', '=', 'order_details.object_code')
            ->select(
                'order_details.order_detail_id as order_detail_id',
                'order_details.object_id as object_id',
                'order_details.object_name as object_name',
                'order_details.object_type as object_type',
                'order_details.object_code as object_code',
                'order_details.price as price',
                'order_details.quantity as quantity',
                'order_details.discount as discount',
                'order_details.amount as amount',
                'order_details.voucher_code as voucher_code',
                'order_details.refer_id',
                'order_details.staff_id',
                "{$this->table}.is_change_price",
                "{$this->table}.is_check_promotion",
                "orders.order_code",
                "{$this->table}.tax",
                'product_childs.inventory_management'
            )->where('orders.order_id', $id)
            ->where('order_details.is_deleted', 0)
            ->get();
        return $ds;
    }

}