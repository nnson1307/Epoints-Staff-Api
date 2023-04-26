<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 26/05/2021
 * Time: 14:14
 */

namespace Modules\Report\Repositories\ReportRevenueOrder;


use Illuminate\Support\Carbon;
use Modules\Report\Models\BranchTable;
use Modules\Report\Models\OrderTable;

class ReportRevenueOrderRepo implements ReportRevenueOrderRepoInterface
{

    /**
     * Lấy tổng doanh thu
     *
     * @param $input
     * @return array
     * @throws ReportRevenueOrderRepoException
     */
    public function totalRevenue($input)
    {
        try {
            $startDate = null;
            $endDate = null;

            if (isset($input["range_date"]) && $input["range_date"] != "") {
                $arr_filter = explode(" - ", $input["range_date"]);
                $startDate = Carbon::createFromFormat('d/m/Y', $arr_filter[0])->format('Y-m-d');
                $endDate = Carbon::createFromFormat('d/m/Y', $arr_filter[1])->format('Y-m-d');
            }

            $mOrder = app()->get(OrderTable::class);
            //Lấy tổng đơn hàng trong khoảng thời gian
            $getOrder = $mOrder->getTotalOrder($input['branch_id'], $startDate, $endDate);

            $totalAmount = 0;
            $totalOrder = 0;

            if (count($getOrder) > 0) {
                foreach ($getOrder as $v) {
                    $v['amount'] = floatval($v['amount']);

                    $totalAmount += $v['amount'];
                    $totalOrder += $v['number_order'];
                }
            }

            return [
                'total_amount' => $totalAmount,
                'total_order' => $totalOrder,
                'detail' => $getOrder
            ];
        } catch (\Exception $e) {
            throw new ReportRevenueOrderRepoException(ReportRevenueOrderRepoException::GET_BRANCH_FAILED, $e->getMessage());
        }
    }

    /**
     * Lấy danh sách chi nhánh
     *
     * @return mixed|void
     * @throws ReportRevenueOrderRepoException
     */
    public function getBranch()
    {
        try {
            $mBranch = app()->get(BranchTable::class);
            //Lấy danh sách chi nhánh
            $data = $mBranch->getBranch();

            if (count($data) > 0) {
                foreach ($data as $v) {
                    $v['full_address'] = $v['address'] . ', ' . $v['ward_type'] . ' ' . $v['ward_name'] .', ' . $v['district_type'] . ' ' . $v['district_name'] . ', ' . $v['province_type'] . ' ' . $v['province_name'];;
                }
            }

            return $data;
        } catch (\Exception $exception) {
            throw new ReportRevenueOrderRepoException(ReportRevenueOrderRepoException::GET_BRANCH_FAILED, $exception->getMessage());
        }
    }

    /**
     * Chi tiết doanh thu bán hàng
     *
     * @param $input
     * @return mixed|void
     * @throws ReportRevenueOrderRepoException
     */
    public function detailRevenue($input)
    {
        try {
            $date = null;

            if (isset($input['date']) && $input['date'] != null) {
                $date = Carbon::createFromFormat('d/m/Y', $input['date'])->format('Y-m-d');
            }

            $mOrder = app()->get(OrderTable::class);
            //Lấy tổng đơn hàng trong khoảng thời gian
            return $mOrder->getDetailOrder($input['branch_id'], $date);
        } catch (\Exception $exception) {
            throw new ReportRevenueOrderRepoException(ReportRevenueOrderRepoException::GET_DETAIL_REVENUE_FAILED);
        }
    }
}