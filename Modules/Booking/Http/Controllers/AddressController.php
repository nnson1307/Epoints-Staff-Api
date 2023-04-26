<?php
/**
 * Created by PhpStorm   .
 * User: Mr Son
 * Date: 2020-01-06
 * Time: 2:06 PM
 * @author SonDepTrai
 */

namespace Modules\Booking\Http\Controllers;


use Illuminate\Database\QueryException;
use Modules\Booking\Http\Requests\Address\DistrictRequest;
use Modules\Booking\Http\Requests\Address\WardRequest;
use Modules\Booking\Http\Requests\Booking\GetPriceServiceRequest;
use Modules\Booking\Http\Requests\Booking\GetProvinceFullRequest;
use Modules\Booking\Repositories\Address\AddressRepoException;
use Modules\Booking\Repositories\Address\AddressRepoInterface;

class AddressController extends Controller
{
    protected $address;

    public function __construct(
        AddressRepoInterface $address
    ) {
        $this->address = $address;
    }

    /**
     * Lấy Option Tỉnh Thành của chi nhánh
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws AddressRepoException
     */
    public function getProvinces()
    {
        try {
            $data = $this->address->getProvinces();

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (\Exception | QueryException $exception) {
            return $this->responseJson(CODE_ERROR, $exception->getMessage());
        }
    }

    /**
     * Lấy Option Quận Huyện
     *
     * @param DistrictRequest $request
     * @return \Illuminate\Http\JsonResponse
     * @throws AddressRepoException
     */
    public function getDistricts(DistrictRequest $request)
    {
        try {
            $data = $this->address->getDistricts($request->provinceid);

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (\Exception | QueryException $exception) {
            return $this->responseJson(CODE_ERROR, $exception->getMessage());
        }
    }

    /**
     * Lấy option tỉnh thành full
     *
     * @param GetProvinceFullRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getProvinceFull(GetProvinceFullRequest $request)
    {
        try {
            $data = $this->address->getProvinceFull();

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (\Exception | QueryException $exception) {
            return $this->responseJson(CODE_ERROR, $exception->getMessage());
        }
    }

    /**
     * Lấy option phường xã
     *
     * @param WardRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getWard(WardRequest $request)
    {
        try {
            $data = $this->address->getWard($request->all());

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (\Exception | QueryException $exception) {
            return $this->responseJson(CODE_ERROR, $exception->getMessage());
        }
    }
}