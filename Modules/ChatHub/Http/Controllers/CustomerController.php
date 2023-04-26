<?php
/**
 * Created by PhpStorm   .
 * User: HIEUPC
 * Date: 2022-10-17
 * Time: 5:48 PM
 * @author HIEUPC
 */

namespace Modules\ChatHub\Http\Controllers;


use Modules\ChatHub\Http\Requests\Customer\CustomerInfoRequest;
use Modules\ChatHub\Repositories\Customer\CustomerRepoException;
use Modules\ChatHub\Repositories\Customer\CustomerRepoInterface;

class CustomerController extends Controller
{
    protected $customer;

    public function __construct(
        CustomerRepoInterface $customer
    ) {
        $this->customer = $customer;
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

}