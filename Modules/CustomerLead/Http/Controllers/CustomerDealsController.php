<?php

namespace Modules\CustomerLead\Http\Controllers;

use Illuminate\Http\Request;

use Modules\CustomerLead\Http\Requests\CustomerDeals\AssignRevokeRequest;
use Modules\CustomerLead\Http\Requests\CustomerDeals\CommentIdRequest;
use Modules\CustomerLead\Repositories\CustomerDeals\CustomerDealsRepoException;
use Modules\CustomerLead\Repositories\CustomerDeals\CustomerDealsRepoInterface;
use Modules\CustomerLead\Http\Requests\CustomerDeals\AddDealsRequest;
use Modules\CustomerLead\Http\Requests\CustomerDeals\DetailDealRequest;
use Modules\CustomerLead\Http\Requests\CustomerDeals\UpdateDealRequest;
use Modules\CustomerLead\Http\Requests\CustomerDeals\DealIdRequest;
use Modules\CustomerLead\Http\Requests\CustomerDeals\CreateCommentRequest;

class CustomerDealsController extends Controller
{
    protected $deal;

    public function __construct(
        CustomerDealsRepoInterface $deal
    ) {
        $this->deal = $deal;
    }

    //tao co hoi ban hang
    public function addDeals(AddDealsRequest $request)
    {

        try {
            // thuc hien tao Deal moi
            $data = $this->deal->createdDeal($request->all());
            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (CustomerDealsRepoException $ex) {
            return $this->responseJson(CODE_ERROR, $ex->getMessage());
        }
    }

    //danh sach deal
    public function getListDeal(Request $request)
    {
        try {
            $data = $this->deal->getDataDeal($request->all());

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (CustomerDealsRepoException $ex) {
            return $this->responseJson(CODE_ERROR, $ex->getMessage());
        }
    }

    //chi tiet deal
    public function getDetailDeal(DetailDealRequest $request)
    {
        try {
            $data = $this->deal->getDetail($request->all());

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (CustomerLeadRepoException $ex) {
            return $this->responseJson(CODE_ERROR, $ex->getMessage());
        }
    }
    ///tạo comment Deal
    public function createMessageDeal(DealIdRequest $request)
    {
        try {
            $data = $this->deal->createMessageDeal($request->all());

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (CustomerLeadRepoException $ex) {
            return $this->responseJson(CODE_ERROR, $ex->getMessage());
        }
    }
    ///list comment Deal
    public function getListMessageDeal(DealIdRequest $request)
    {
        try {
            $data = $this->deal->getListMessageDeal($request->all());

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (CustomerLeadRepoException $ex) {
            return $this->responseJson(CODE_ERROR, $ex->getMessage());
        }
    }
    ///list comment Deal
    public function deleteMessageDeal(CommentIdRequest $request)
    {
        try {
            $data = $this->deal->deleteMessageDeal($request->all());

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (CustomerLeadRepoException $ex) {
            return $this->responseJson(CODE_ERROR, $ex->getMessage());
        }
    }
    ///lịch sử đơn hàng
    public function getOrderHistory(DetailDealRequest $request)
    {
        try {
            $data = $this->deal->getOrderHistory($request->all());

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (CustomerLeadRepoException $ex) {
            return $this->responseJson(CODE_ERROR, $ex->getMessage());
        }
    }
    public function getCareDeal(DealIdRequest $request)
    {
        try {
            $data = $this->deal->getCareDeal($request->all());

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (CustomerLeadRepoException $ex) {
            return $this->responseJson(CODE_ERROR, $ex->getMessage());
        }
    }
    //update deal
    public function updateDeal(UpdateDealRequest $request)
    {
        try {
            $data = $this->deal->actionUpdate($request->all());

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (CustomerLeadRepoException $ex) {
            return $this->responseJson(CODE_ERROR, $ex->getMessage());
        }
    }
    //delete deal
    public function deleteDeal(Request $request)
    {
        try {
            $data = $this->deal->actionDelete($request->all());

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (CustomerLeadRepoException $ex) {
            return $this->responseJson(CODE_ERROR, $ex->getMessage());
        }
    }

    /**
     * Phân bổ hoặc thu hồi deal
     *
     * @param AssignRevokeRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function assignRevokeDeal(AssignRevokeRequest $request)
    {
        try {
            $data = $this->deal->assignRevoke($request->all());

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (CustomerLeadRepoException $ex) {
            return $this->responseJson(CODE_ERROR, $ex->getMessage());
        }
    }

    /**
     * Danh sách comment
     * @return \Illuminate\Http\JsonResponse
     */
    public function listComment(Request $request)
    {
        try {
            $param = $request->all();
            $data = $this->deal->listComment($param);

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (\Exception $exception) {
            return $this->responseJson(CODE_ERROR, $exception->getMessage());
        }
    }

    /**
     * Tạo comment
     * @param CreateCommentRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function createdComment(CreateCommentRequest $request)
    {
        try {
            $param = $request->all();
            $data = $this->deal->createdComment($param);

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (\Exception $exception) {
            return $this->responseJson(CODE_ERROR, $exception->getMessage());
        }
    }
}
