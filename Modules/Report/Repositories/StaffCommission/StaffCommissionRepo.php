<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 27/05/2021
 * Time: 10:48
 */

namespace Modules\Report\Repositories\StaffCommission;


use Illuminate\Support\Carbon;
use Modules\Report\Models\OrderCommissionTable;
use MyCore\Repository\PagingTrait;

class StaffCommissionRepo implements StaffCommissionRepoInterface
{
    use PagingTrait;

    /**
     * Tổng hoa hồng nhân viên
     *
     * @param $input
     * @return mixed|void
     * @throws StaffCommissionRepoException
     */
    public function totalCommission($input)
    {
        try {
            $startDate = null;
            $endDate = null;

            if (isset($input["range_date"]) && $input["range_date"] != "") {
                $arr_filter = explode(" - ", $input["range_date"]);
                $startDate = Carbon::createFromFormat('d/m/Y', $arr_filter[0])->format('Y-m-d');
                $endDate = Carbon::createFromFormat('d/m/Y', $arr_filter[1])->format('Y-m-d');
            }

            $mOrderCommission = app()->get(OrderCommissionTable::class);
            //Lấy tổng hoa hồng nhân viên
            $getCommission = $mOrderCommission->getInfoCommissionGroupByStaff($startDate, $endDate);

            $totalCommission = 0;

            if (count($getCommission) > 0) {
                foreach ($getCommission as $v) {
                    $totalCommission += $v['total_staff_money'];
                }
            }

            return [
                'totalCommission' => $totalCommission,
                'detail' => $getCommission
            ];
        } catch (\Exception $e) {
            throw new StaffCommissionRepoException(StaffCommissionRepoException::GET_TOTAL_COMMISSION_FAILED);
        }
    }

    /**
     * Chi tiết hoa hồng nhân viên
     *
     * @param $input
     * @return mixed|void
     * @throws StaffCommissionRepoException
     */
    public function detailCommission($input)
    {
        try {
            $input['start_time'] = null;
            $input['end_time'] = null;

            if (isset($input["range_date"]) && $input["range_date"] != "") {
                $arr_filter = explode(" - ", $input["range_date"]);
                $input['start_time'] = Carbon::createFromFormat('d/m/Y', $arr_filter[0])->format('Y-m-d');
                $input['end_time'] = Carbon::createFromFormat('d/m/Y', $arr_filter[1])->format('Y-m-d');
            }
            $mOrderCommission = app()->get(OrderCommissionTable::class);
            //Lấy chi tiết hoa hồng nhân viên
            $data = $mOrderCommission->getCommissionStaff($input);

            return $this->toPagingData($data);
        } catch (\Exception $e) {
            throw new StaffCommissionRepoException(StaffCommissionRepoException::GET_DETAIL_COMMISSION_FAILED, $e->getMessage());
        }
    }
}