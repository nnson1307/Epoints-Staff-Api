<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 26/05/2021
 * Time: 14:13
 */

namespace Modules\Report\Http\Controllers;


use Modules\Report\Http\Requests\StaffCommission\DetailRequest;
use Modules\Report\Http\Requests\StaffCommission\TotalRequest;
use Modules\Report\Repositories\StaffCommission\StaffCommissionRepoException;
use Modules\Report\Repositories\StaffCommission\StaffCommissionRepoInterface;

class ReportStaffCommissionController extends Controller
{
    protected $staffCommission;

    public function __construct(
        StaffCommissionRepoInterface $staffCommission
    ) {
        $this->staffCommission = $staffCommission;
    }

    /**
     * Tổng hoa hồng nhân viên
     *
     * @param TotalRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function totalCommission(TotalRequest $request)
    {
        try {
            $data = $this->staffCommission->totalCommission($request->all());

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (StaffCommissionRepoException $ex) {
            return $this->responseJson(CODE_ERROR, $ex->getMessage());
        }
    }

    /**
     * Chi tiết hoa hồng nhân viên
     *
     * @param DetailRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function detailCommission(DetailRequest $request)
    {
        try {
            $data = $this->staffCommission->detailCommission($request->all());

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (StaffCommissionRepoException $ex) {
            return $this->responseJson(CODE_ERROR, $ex->getMessage());
        }
    }
}