<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 27/09/2022
 * Time: 14:21
 */

namespace Modules\Warranty\Http\Controllers;


use Illuminate\Http\Request;
use Modules\Warranty\Http\Requests\Maintenance\DetailRequest;
use Modules\Warranty\Http\Requests\Maintenance\GetListRequest;
use Modules\Warranty\Http\Requests\Maintenance\ReceiptRequest;
use Modules\Warranty\Http\Requests\Maintenance\StoreRequest;
use Modules\Warranty\Http\Requests\Maintenance\UpdateRequest;
use Modules\Warranty\Http\Requests\Maintenance\WarrantyCardCustomerRequest;
use Modules\Warranty\Repositories\Maintenance\MaintenanceRepoException;
use Modules\Warranty\Repositories\Maintenance\MaintenanceRepoInterface;

class MaintenanceController extends Controller
{
    protected $maintenance;

    public function __construct(
        MaintenanceRepoInterface $maintenance
    ) {
        $this->maintenance = $maintenance;
    }

    /**
     * DS phiếu bảo trì
     *
     * @param GetListRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getMaintenance(GetListRequest $request)
    {
        try {
            $data = $this->maintenance->getMaintenance($request->all());

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (MaintenanceRepoException $ex) {
            return $this->responseJson(CODE_ERROR, $ex->getMessage());
        }
    }

    /**
     * DS phiếu bảo hành của khách hàng
     *
     * @param WarrantyCardCustomerRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getWarrantyCardCustomer(WarrantyCardCustomerRequest $request)
    {
        try {
            $data = $this->maintenance->getWarrantyCardCustomer($request->all());

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (MaintenanceRepoException $ex) {
            return $this->responseJson(CODE_ERROR, $ex->getMessage());
        }
    }

    /**
     * Lấy chi phí phát sinh
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCostType(Request $request)
    {
        try {
            $data = $this->maintenance->getCostType();

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (MaintenanceRepoException $ex) {
            return $this->responseJson(CODE_ERROR, $ex->getMessage());
        }
    }

    /**
     * Thêm phiếu bảo trì
     *
     * @param StoreRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreRequest $request)
    {
        try {
            $data = $this->maintenance->store($request->all());

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (MaintenanceRepoException $ex) {
            return $this->responseJson(CODE_ERROR, $ex->getMessage());
        }
    }

    /**
     * Chi tiết phiếu bảo trì
     *
     * @param DetailRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(DetailRequest $request)
    {
        try {
            $data = $this->maintenance->show($request->all());

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (MaintenanceRepoException $ex) {
            return $this->responseJson(CODE_ERROR, $ex->getMessage());
        }
    }

    /**
     * Cập nhật phiếu bảo trì
     *
     * @param UpdateRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateRequest $request)
    {
        try {
            $data = $this->maintenance->update($request->all());

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (MaintenanceRepoException $ex) {
            return $this->responseJson(CODE_ERROR, $ex->getMessage());
        }
    }

    /**
     * Thanh toán phiếu bảo trì
     *
     * @param ReceiptRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function receiptMaintenance(ReceiptRequest $request)
    {
        try {
            $data = $this->maintenance->receiptMaintenance($request->all());

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (MaintenanceRepoException $ex) {
            return $this->responseJson(CODE_ERROR, $ex->getMessage());
        }
    }

    /**
     * Lấy ds trạng thái phiếu bảo trì
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function listStatus()
    {
        try {
            $data = $this->maintenance->getListStatus();

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (MaintenanceRepoException $ex) {
            return $this->responseJson(CODE_ERROR, $ex->getMessage());
        }
    }
}