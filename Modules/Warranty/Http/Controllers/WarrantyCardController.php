<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 26/09/2022
 * Time: 16:53
 */

namespace Modules\Warranty\Http\Controllers;


use Illuminate\Http\Request;
use Modules\Warranty\Http\Requests\WarrantyCard\GetListRequest;
use Modules\Warranty\Http\Requests\WarrantyCard\QuickUpdateRequest;
use Modules\Warranty\Http\Requests\WarrantyCard\UpdateRequest;
use Modules\Warranty\Repositories\WarrantyCard\WarrantyCardRepoException;
use Modules\Warranty\Repositories\WarrantyCard\WarrantyCardRepoInterface;

class WarrantyCardController extends Controller
{
    protected $warrantyCard;

    public function __construct(
        WarrantyCardRepoInterface $warrantyCard
    ) {
        $this->warrantyCard = $warrantyCard;
    }

    /**
     * Lấy DS gói bảo hành
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getPackage()
    {
        try {
            $data = $this->warrantyCard->getPackage();

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (WarrantyCardRepoException $ex) {
            return $this->responseJson(CODE_ERROR, $ex->getMessage());
        }
    }

    /**
     * Lấy DS phiếu bảo hành
     *
     * @param GetListRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getWarrantyCard(GetListRequest $request)
    {
        try {
            $data = $this->warrantyCard->getWarrantyCard($request->all());

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (WarrantyCardRepoException $ex) {
            return $this->responseJson(CODE_ERROR, $ex->getMessage());
        }
    }

    /**
     * Chi tiết phiếu bảo hành
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request)
    {
        try {
            $data = $this->warrantyCard->show($request->all());

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (WarrantyCardRepoException $ex) {
            return $this->responseJson(CODE_ERROR, $ex->getMessage());
        }
    }

    /**
     * Chỉnh sửa phiếu bảo hành
     *
     * @param UpdateRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateRequest $request)
    {
        try {
            $data = $this->warrantyCard->update($request->all());

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (WarrantyCardRepoException $ex) {
            return $this->responseJson(CODE_ERROR, $ex->getMessage());
        }
    }

    /**
     * Cập nhật nhanh phiếu bảo hành
     *
     * @param QuickUpdateRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function quickUpdate(QuickUpdateRequest $request)
    {
        try {
            $data = $this->warrantyCard->quickUpdate($request->all());

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (WarrantyCardRepoException $ex) {
            return $this->responseJson(CODE_ERROR, $ex->getMessage());
        }
    }

    /**
     * Lấy ds trạng thái phiếu bảo hành
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function listStatus()
    {
        try {
            $data = $this->warrantyCard->getListStatus();

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (WarrantyCardRepoException $ex) {
            return $this->responseJson(CODE_ERROR, $ex->getMessage());
        }
    }
}