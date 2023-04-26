<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 27/05/2021
 * Time: 11:06
 */

namespace Modules\Report\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class OrderCommissionTable extends Model
{
    protected $table = 'order_commission';
    protected $primaryKey = 'id';

    const NOT_DELETE = 0;

    protected $casts = [
        "total_staff_money" => 'float',
        "staff_money" => 'float',
        "staff_commission_rate" => 'float',
    ];

    /**
     * Lấy staff id, name, tổng tiền hoa hồng của mỗi nhân viên
     *
     * @param $startTime
     * @param $endTime
     * @return mixed
     */
    public function getInfoCommissionGroupByStaff($startTime, $endTime)
    {
        $select = $this
            ->select(
                "{$this->table}.staff_id",
                DB::raw("SUM({$this->table}.staff_money) as total_staff_money"),
                "staffs.full_name as staff_name"
            )
            ->join("staffs", "staffs.staff_id", "=", "{$this->table}.staff_id")
            ->whereNotNull("{$this->table}.staff_id")
            ->where("{$this->table}.status", "approve")
            ->whereBetween("{$this->table}.created_at", [$startTime . ' 00:00:00', $endTime . ' 23:59:59'])
            ->where("staffs.is_deleted", self::NOT_DELETE)
            ->orderBy("total_staff_money")
            ->groupBy("{$this->table}.staff_id");

        return $select->get();
    }

    /**
     * Lấy thông tin chi tiết hoa hồng nv
     *
     * @param array $filter
     * @return mixed
     */
    public function getCommissionStaff($filter = [])
    {
        $ds = $this
            ->select(
                "staffs.full_name as staff_name",
                "{$this->table}.staff_money",
                "{$this->table}.staff_commission_rate",
                "branches.branch_name",
                "{$this->table}.created_at"
            )
            ->join("staffs", "staffs.staff_id", "=", "{$this->table}.staff_id")
            ->leftJoin("order_details", "order_details.order_detail_id", "=", "{$this->table}.order_detail_id")
            ->leftJoin("orders", "orders.order_id", "=", "order_details.order_id")
            ->leftJoin("branches", "branches.branch_id", "=", "orders.branch_id")
            ->where("staffs.is_deleted", self::NOT_DELETE)
//            ->where("branches.is_deleted", self::NOT_DELETE)
            ->whereBetween("{$this->table}.created_at", [$filter['start_time'] . ' 00:00:00', $filter['end_time'] . ' 23:59:59'])
            ->where("{$this->table}.staff_id", $filter['staff_id']);

        // get số trang
        $page = (int)($filter["page"] ?? 1);

        return $ds->paginate(PAGING_ITEM_PER_PAGE, $columns = ["*"], $pageName = "page", $page);
    }
}