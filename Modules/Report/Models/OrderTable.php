<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 26/05/2021
 * Time: 14:54
 */

namespace Modules\Report\Models;


use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class OrderTable extends Model
{
    protected $table = "orders";
    protected $primaryKey = "order_id";

    const PAY_SUCCESS = "paysuccess";
    const PAY_HALF = "pay-half";

    protected $casts = [
        "amount" => 'float',
    ];

    /**
     * Lấy tổng đơn hàng từ ngày -> ngày
     *
     * @param $branchId
     * @param $startDate
     * @param $endDate
     * @return mixed
     */
    public function getTotalOrder($branchId, $startDate, $endDate)
    {
        $ds = $this
            ->select(
                DB::raw("DATE_FORMAT(orders.created_at,'%d/%m/%Y') as date"),
                DB::raw('COUNT(orders.order_id) as number_order'),
                DB::raw('SUM(rc.amount_paid) as amount')
            )
            ->join("receipts as rc", function ($join) {
                $join->on("{$this->table}.order_id", "=", "rc.order_id")
                    ->where(function ($select) {
                        $select->where("rc.object_type", "order")
                            ->orWhere("rc.receipt_type_code", "RTC_ORDER");
                    });
            })
            ->whereBetween("{$this->table}.created_at", [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->whereIn("{$this->table}.process_status", [self::PAY_SUCCESS, self::PAY_HALF])
            ->groupBy(DB::raw("DATE_FORMAT(orders.created_at,'%d/%m/%Y')"));

        if (isset($branchId) && $branchId != null) {
            $ds->where("{$this->table}.branch_id", $branchId);
        }

        return $ds->get();
    }

    /**
     * Lấy chi tiết doanh thu theo ngày
     *
     * @param $branchId
     * @param $data
     * @return mixed
     */
    public function getDetailOrder($branchId, $data)
    {
        $ds = $this
            ->select(
                "{$this->table}.order_id",
                "{$this->table}.order_code",
                "cs.full_name",
                DB::raw("DATE_FORMAT(orders.created_at,'%d/%m/%Y %H:%i') as date"),
                "rc.amount_paid as amount"
            )
            ->join("receipts as rc", function ($join) {
                $join->on("{$this->table}.order_id", "=", "rc.order_id")
                    ->where(function ($select) {
                        $select->where("rc.object_type", "order")
                            ->orWhere("rc.receipt_type_code", "RTC_ORDER");
                    });
            })
            ->join("customers as cs", "cs.customer_id", "=", "{$this->table}.customer_id")
            ->whereBetween("{$this->table}.created_at", [$data . ' 00:00:00', $data . ' 23:59:59'])
            ->whereIn("{$this->table}.process_status", [self::PAY_SUCCESS, self::PAY_HALF]);

        if (isset($branchId) && $branchId != null) {
            $ds->where("{$this->table}.branch_id", $branchId);
        }

        return $ds->get();
    }
}