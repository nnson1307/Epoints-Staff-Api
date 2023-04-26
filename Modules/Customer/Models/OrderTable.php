<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 11/05/2021
 * Time: 14:02
 */

namespace Modules\Customer\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class OrderTable extends Model
{
    protected $table = "orders";
    protected $primaryKey = "order_id";

    protected $casts = [
        'total' => 'float',
        'discount' => 'float',
        'amount' => 'float',
        'discount_member' => 'float'
    ];

    /**
     * Lấy danh sách lịch sử mua hàng
     *
     * @param $filter
     * @param $customerId
     * @return mixed
     */
    public function getOrders($filter, $customerId)
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
                "{$this->table}.created_at"
            )
            ->join("branches", "branches.branch_id", "=", "{$this->table}.branch_id")
            ->leftJoin("customers as refer", "refer.customer_id", "=", "{$this->table}.refer_id")
            ->leftJoin("deliveries", "deliveries.order_id", "=", "{$this->table}.order_id")
            ->where("{$this->table}.customer_id", $customerId)
            ->where("{$this->table}.is_deleted", 0)
            ->where("branches.is_deleted", 0)
            ->orderBy("{$this->table}.created_at", "desc");

        // get số trang
        $page = (int)($filter["page"] ?? 1);

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
            switch ($filter["status"]) {
                case 'new':
                    $ds->where("{$this->table}.process_status", "new");
                    break;
                case 'packing':
                    $ds->where(function ($query) {
                        $query->whereRaw("deliveries.delivery_status IS NULL and {$this->table}.process_status = 'confirmed' and {$this->table}.process_status <> 'new' ")
                            ->orWhereRaw("deliveries.delivery_status IS NOT NULL and deliveries.delivery_status = 'packing' and {$this->table}.process_status <> 'new' ");
                    });
                    break;
                case 'delivering':
                    $ds->whereRaw("deliveries.delivery_status IS NOT NULL and deliveries.delivery_status = 'delivering' ");
                    break;
                case 'ordercomplete':
                    $ds->where(function ($query) {
                        $query->whereRaw("deliveries.delivery_status IS NULL and {$this->table}.process_status = 'pay-half' ")
                            ->orWhereRaw("deliveries.delivery_status IS NULL and {$this->table}.process_status = 'paysuccess' ")
                            ->orWhereRaw("deliveries.delivery_status IS NOT NULL and deliveries.delivery_status = 'delivered' ");
                    });
                    break;
                case 'ordercancle':
                    $ds->where("{$this->table}.process_status", 'ordercancle');
                    break;
            }
        }

        return $ds->paginate(PAGING_ITEM_PER_PAGE, $columns = ["*"], $pageName = "page", $page);
    }

}