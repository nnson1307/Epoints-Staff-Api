<?php
/**
 * Created by PhpStorm   .
 * User: Mr Son
 * Date: 2020-01-03
 * Time: 5:48 PM
 * @author SonDepTrai
 */

namespace Modules\Customer\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Customer\Http\Requests\Customer\CustomerInfoRequest;
use Modules\Customer\Http\Requests\Customer\GetCustomerGroupRequest;
use Modules\Customer\Http\Requests\Customer\GetListRequest;
use Modules\Customer\Http\Requests\Customer\HistoryOrderRequest;
use Modules\Customer\Http\Requests\Customer\StoreRequest;
use Modules\Customer\Http\Requests\Customer\UpdateRequest;
use Modules\Customer\Repositories\Customer\CustomerRepoException;
use Modules\Customer\Repositories\Customer\CustomerRepoInterface;
use Modules\Customer\Http\Requests\Customer\CreateCommentRequest;

class CustomerController extends Controller
{
    protected $customer;

    public function __construct(
        CustomerRepoInterface $customer
    ) {
        $this->customer = $customer;
    }

    /**
     * Lấy ds khách hàng
     *
     * @param GetListRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCustomer(GetListRequest $request)
    {
        try {
            $data = $this->customer->getCustomer($request->all());

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (CustomerRepoException $ex) {
            return $this->responseJson(CODE_ERROR, $ex->getMessage());
        }
    }

    /**
     * Lấy thông tin khách hàng
     *
     * @param CustomerInfoRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getDetail(CustomerInfoRequest $request)
    {
        try {
            $data = $this->customer->getDetail($request->all());

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (CustomerRepoException $ex) {
            return $this->responseJson(CODE_ERROR, $ex->getMessage());
        }
    }

    public function updateCustomer(UpdateRequest $request)
    {
        try {
            $this->customer->updateCustomer($request->all());

            return $this->responseJson(CODE_SUCCESS, __('Cập nhật thông tin khách hàng thành công.'), null);
        } catch (CustomerRepoException $ex) {
            return $this->responseJson(CODE_ERROR, $ex->getMessage());
        }
    }

    /**
     * Lấy nhóm khách hàng
     *
     * @param GetCustomerGroupRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCustomerGroup(GetCustomerGroupRequest $request)
    {
        try {
            $data = $this->customer->getCustomerGroup();

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (CustomerRepoException $ex) {
            return $this->responseJson(CODE_ERROR, $ex->getMessage());
        }
    }

    /**
     * Thêm khách hàng
     *
     * @param StoreRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreRequest $request)
    {
        try {
            $data = $this->customer->store($request->all());

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (CustomerRepoException $ex) {
            return $this->responseJson(CODE_ERROR, $ex->getMessage());
        }
    }

    /**
     * Lấy lịch sử mua hàng
     *
     * @param HistoryOrderRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function historyOrder(HistoryOrderRequest $request)
    {
        try {
            $data = $this->customer->historyOrder($request->all());

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (CustomerRepoException $ex) {
            return $this->responseJson(CODE_ERROR, $ex->getMessage());
        }
    }

    /**
     * Cập nhật khách hàng
     *
     * @param UpdateRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateRequest $request)
    {
        try {
            $data = $this->customer->update($request->all());

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (CustomerRepoException $ex) {
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
            $data = $this->customer->listComment($param);

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
            $data = $this->customer->createdComment($param);

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (\Exception $exception) {
            return $this->responseJson(CODE_ERROR, $exception->getMessage());
        }
    }
}