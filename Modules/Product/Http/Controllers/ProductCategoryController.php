<?php
/**
 * Created by PhpStorm
 * User: Mr Son
 * Date: 08-04-02020
 * Time: 9:16 AM
 */

namespace Modules\Product\Http\Controllers;


use Illuminate\Database\Eloquent\Model;
use Modules\Product\Http\Requests\ProductCategory\GetCategoryETLRequest;
use Modules\Product\Http\Requests\ProductCategory\ProductCategoryListRequest;
use Modules\Product\Repositories\ProductCategory\ProductCategoryRepoException;
use Modules\Product\Repositories\ProductCategory\ProductCategoryRepoInterface;

class ProductCategoryController extends Controller
{
    protected $productCategory;

    public function __construct(
        ProductCategoryRepoInterface $productCategory
    ) {
        $this->productCategory = $productCategory;
    }

    /**
     * Danh sách loại dịch vụ
     *
     * @param ProductCategoryListRequest $request
     * @return mixed
     */
    public function getProductCategories(ProductCategoryListRequest $request)
    {
        try {
            $data = $this->productCategory->getProductCategories($request->all());

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (ProductCategoryRepoException $ex) {
            return $this->responseJson(CODE_ERROR, $ex->getMessage());
        }
    }

    /**
     * Lấy option loại sản phẩm
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getOption()
    {
        try {
            $data = $this->productCategory->getOption();

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (ProductCategoryRepoException $ex) {
            return $this->responseJson(CODE_ERROR, $ex->getMessage());
        }
    }

    /**
     * Lấy thông tin loại sản phẩm ETL
     *
     * @param GetCategoryETLRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getProductCategoryETL(GetCategoryETLRequest $request)
    {
        try {
            $data = $this->productCategory->getProductCategoryETL($request->all());

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (ProductCategoryRepoException $ex) {
            return $this->responseJson(CODE_ERROR, $ex->getMessage());
        }
    }
}