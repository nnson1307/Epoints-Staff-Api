<?php
/**
 * Created by PhpStorm
 * User: Mr Son
 * Date: 07-04-02020
 * Time: 11:18 PM
 */

namespace Modules\Service\Http\Controllers;


use Illuminate\Database\QueryException;
use Modules\Service\Http\Requests\ServiceCategory\ServiceCategoryListRequest;
use Modules\Service\Repositories\ServiceCategory\ServiceCategoryRepoInterface;

class ServiceCategoryController extends Controller
{
    protected $serviceCategory;

    public function __construct(
        ServiceCategoryRepoInterface $serviceCategory
    ) {
        $this->serviceCategory = $serviceCategory;
    }

    /**
     * Danh sách loại dịch vụ
     *
     * @param ServiceCategoryListRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getServiceCategories(ServiceCategoryListRequest $request)
    {
        try {
            $data = $this->serviceCategory->getServiceCategories($request->all());

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (\Exception | QueryException $exception) {
            return $this->responseJson(CODE_ERROR, $exception->getMessage());
        }
    }

    /**
     * Lấy option loại dịch vụ
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getOption()
    {
        try {
            $data = $this->serviceCategory->getOption();

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (\Exception | QueryException $exception) {
            return $this->responseJson(CODE_ERROR, $exception->getMessage());
        }
    }
}