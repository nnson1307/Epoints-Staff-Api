<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 24/05/2021
 * Time: 14:26
 */

namespace Modules\Customer\Http\Controllers;


use Modules\Customer\Http\Requests\CustomerContact\RemoveRequest;
use Modules\Customer\Http\Requests\CustomerContact\SetDefaultRequest;
use Modules\Customer\Http\Requests\CustomerContact\StoreRequest;
use Modules\Customer\Http\Requests\CustomerContact\UpdateRequest;
use Modules\Customer\Repositories\CustomerContact\CustomerContactRepoException;
use Modules\Customer\Repositories\CustomerContact\CustomerContactRepoInterface;

class CustomerContactController extends Controller
{
    protected $customerContact;

    public function __construct(
        CustomerContactRepoInterface $customerContact
    ) {
        $this->customerContact = $customerContact;
    }

    /**
     * Thêm địa chỉ giao hàng
     *
     * @param StoreRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreRequest $request)
    {
        try {
            $data = $this->customerContact->store($request->all());

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (CustomerContactRepoException $ex) {
            return $this->responseJson(CODE_ERROR, $ex->getMessage());
        }
    }

    /**
     * Xoá địa chỉ giao hàng
     *
     * @param RemoveRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function remove(RemoveRequest $request)
    {
        try {
            $data = $this->customerContact->remove($request->all());

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (CustomerContactRepoException $ex) {
            return $this->responseJson(CODE_ERROR, $ex->getMessage());
        }
    }

    /**
     * Cập nhật địa chỉ giao hàng mặc định
     *
     * @param SetDefaultRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function setDefault(SetDefaultRequest $request)
    {
        try {
            $data = $this->customerContact->setDefault($request->all());

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (CustomerContactRepoException $ex) {
            return $this->responseJson(CODE_ERROR, $ex->getMessage());
        }
    }

    /**
     * Chỉnh sửa địa chỉ giao hàng
     *
     * @param UpdateRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateRequest $request)
    {
        try {
            $data = $this->customerContact->update($request->all());

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (CustomerContactRepoException $ex) {
            return $this->responseJson(CODE_ERROR, $ex->getMessage());
        }
    }
}