<?php

namespace Modules\Home\Http\Controllers;

use Illuminate\Database\QueryException;
use Modules\Home\Http\Requests\Home\HomeRequest;
use Modules\Home\Http\Requests\Home\SearchRequest;
use Modules\Home\Repositories\Home\HomeRepoInterface;
use Modules\Home\Repositories\Home\HomeRepoException;
use Modules\Product\Http\Requests\Product\ProductAllListRequest;
use Modules\Product\Repositories\Product\ProductRepoException;

class HomeController extends Controller
{
    protected $home;

    public function __construct(
        HomeRepoInterface $home
    ) {
        $this->home = $home;
    }
    /**
     * Danh sách banner product
     *
     * @param HomeRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getHome(){
        try {
            $lang = \request()->header('lang');

            $data = $this->home->getHome($lang != null ? $lang : 'vi');

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (\Exception | QueryException $exception) {
            return $this->responseJson(CODE_ERROR, $exception->getMessage());
        }
    }
    /**
     * Lấy danh sách sản phẩm home page
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getProductHome()
    {
        try {
            $data = $this->home->getProductHome();

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (ProductRepoException $ex) {
            return $this->responseJson(CODE_ERROR, $ex->getMessage());
        }
    }


    /**
     * Lấy danh sách dịch vụ home page
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getService()
    {
        try {
            $data = $this->home->getService();

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (ProductRepoException $ex) {
            return $this->responseJson(CODE_ERROR, $ex->getMessage());
        }
    }

    /**
     * Lấy danh sách tất cả sản phẩm
     *
     * @param ProductAllListRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAllProducts(ProductAllListRequest $request)
    {
        try {
            $data = $this->home->getAllProducts($request->all());

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (ProductRepoException $ex) {
            return $this->responseJson(CODE_ERROR, $ex->getMessage());
        }
    }

    /**
     * Tìm kiếm tất cả home page
     *
     * @param SearchRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function searchAll(SearchRequest $request)
    {
        try {
            $lang = \request()->header('lang');

            $data = $this->home->searchAll($request->all(), $lang != null ? $lang : 'vi');

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (ProductRepoException $ex) {
            return $this->responseJson(CODE_ERROR, $ex->getMessage());
        }
    }

}
