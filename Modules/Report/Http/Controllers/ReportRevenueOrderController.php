<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 26/05/2021
 * Time: 14:12
 */

namespace Modules\Report\Http\Controllers;


use Modules\Report\Http\Requests\RevenueOrder\DetailRequest;
use Modules\Report\Http\Requests\RevenueOrder\TotalRequest;
use Modules\Report\Repositories\ReportRevenueOrder\ReportRevenueOrderRepoException;
use Modules\Report\Repositories\ReportRevenueOrder\ReportRevenueOrderRepoInterface;

class ReportRevenueOrderController extends Controller
{
    protected $revenueOrder;

    public function __construct(
        ReportRevenueOrderRepoInterface $revenueOrder
    ) {
        $this->revenueOrder = $revenueOrder;
    }

    /**
     * Lấy danh sách chi nhánh
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getBranch()
    {
        try {
            $data = $this->revenueOrder->getBranch();

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (ReportRevenueOrderRepoException $ex) {
            return $this->responseJson(CODE_ERROR, $ex->getMessage());
        }
    }

    /**
     * Lấy tổng doanh thu
     *
     * @param TotalRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function totalRevenue(TotalRequest $request)
    {
        try {
            $data = $this->revenueOrder->totalRevenue($request->all());

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (ReportRevenueOrderRepoException $ex) {
            return $this->responseJson(CODE_ERROR, $ex->getMessage());
        }
    }

    /**
     * Lấy chi tiết doanh thu
     *
     * @param DetailRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function detailRevenue(DetailRequest $request)
    {
        try {
            $data = $this->revenueOrder->detailRevenue($request->all());

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (ReportRevenueOrderRepoException $ex) {
            return $this->responseJson(CODE_ERROR, $ex->getMessage());
        }
    }
}