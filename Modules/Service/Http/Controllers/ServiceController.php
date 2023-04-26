<?php

namespace Modules\Service\Http\Controllers;

use Illuminate\Database\QueryException;


use Modules\Service\Http\Requests\Service\LikeUnLikeRequest;
use Modules\Service\Http\Requests\Service\ServiceDetailRequest;
use Modules\Service\Http\Requests\Service\ServiceLikeListRequest;
use Modules\Service\Http\Requests\Service\ServiceRepresentativeListRequest;
use Modules\Service\Repositories\Service\ServiceRepoException;
use Modules\Service\Repositories\Service\ServiceRepoInterface;
use Modules\Service\Http\Requests\Service\ServiceListRequest;

class ServiceController extends Controller
{
    protected $service;

    public function __construct(
        ServiceRepoInterface $service
    ) {
        $this->service = $service;
    }

    /**
     * Lấy danh sách dịch vụ
     *
     * @param ServiceListRequest $request
     * @return mixed
     */
    public function getServices(ServiceListRequest $request)
    {
        try {
            $data = $this->service->getServices($request->all());

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (\Exception | QueryException $exception) {
            return $this->responseJson(CODE_ERROR, $exception->getMessage());
        }
    }

    /**
     * Danh sách dịch vụ đã sử dụng
     *
     * @param ServiceListRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getHistoryServices(ServiceListRequest $request)
    {
        try {
            $data = $this->service->getHistoryServices($request->all());

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (ServiceRepoException $ex) {
            return $this->responseJson(CODE_ERROR, $ex->getMessage());
        }
    }

    /**
     * Danh sách dịch vụ theo chi nhánh chính
     *
     * @param ServiceRepresentativeListRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getServiceRepresentative(ServiceRepresentativeListRequest $request)
    {
        try {
            $data = $this->service->getServiceRepresentative($request->all());

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (ServiceRepoException $ex) {
            return $this->responseJson(CODE_ERROR, $ex->getMessage());
        }
    }

    /**
     * Chi tiết dịch vụ
     *
     * @param ServiceDetailRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getDetail(ServiceDetailRequest $request)
    {
        try {
            $lang = \request()->header('lang');

            $data = $this->service->getDetail($request->service_id, $lang != null ? $lang : 'vi');

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (ServiceRepoException $ex) {
            return $this->responseJson(CODE_ERROR, $ex->getMessage());
        }
    }

    /**
     * Lay thong tin chung (banner + dich vu noi bat + dich vu khuyen mai)
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function generalInfo()
    {
        try {
            $data = $this->service->getGeneralInfo();

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (ServiceRepoException $ex) {
            return $this->responseJson(CODE_ERROR, $ex->getMessage());
        }
    }

    /**
     * Like / Un like dịch vụ
     *
     * @param LikeUnLikeRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function likeUnlikeService(LikeUnLikeRequest $request)
    {
        try {
            $data = $this->service->likeUnlikeService($request->all());

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (ServiceRepoException $ex) {
            return $this->responseJson(CODE_ERROR, $ex->getMessage());
        }
    }

    /**
     * Danh sách dịch vụ yêu thích
     *
     * @param ServiceLikeListRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getListServiceLikes(ServiceLikeListRequest $request)
    {
        try {
            $data = $this->service->getListServiceLikes($request->all());

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (ServiceRepoException $ex) {
            return $this->responseJson(CODE_ERROR, $ex->getMessage());
        }
    }
}
