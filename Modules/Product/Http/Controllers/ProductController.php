<?php
/**
 * Created by PhpStorm   .
 * User: Mr Son
 * Date: 2020-01-09
 * Time: 9:34 AM
 * @author SonDepTrai
 */

namespace Modules\Product\Http\Controllers;


use Modules\Product\Http\Requests\Product\GetProductETLRequest;
use Modules\Product\Http\Requests\Product\LikeUnlikeRequest;
use Modules\Product\Http\Requests\Product\ProductDetailRequest;
use Modules\Product\Http\Requests\Product\ProductLikeListRequest;
use Modules\Product\Http\Requests\Product\ProductListRequest;
use Modules\Product\Http\Requests\Product\ProductAllListRequest;
use Modules\Product\Http\Requests\Product\HistoryProductListRequest;
use Modules\Product\Http\Requests\Product\ScanProductRequest;
use Modules\Product\Repositories\Product\ProductRepoException;
use Modules\Product\Repositories\Product\ProductRepoInterface;

class ProductController extends Controller
{
    protected $product;

    public function __construct(
        ProductRepoInterface $product
    ) {
        $this->product = $product;
    }

    /**
     * Danh sách lịch sử sản phẩm
     *
     * @param HistoryProductListRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getHistoryProducts(HistoryProductListRequest $request)
    {
        try {
            $data = $this->product->getHistoryProducts($request->all());

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
            $data = $this->product->getAllProducts($request->all());

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (ProductRepoException $ex) {
            return $this->responseJson(CODE_ERROR, $ex->getMessage());
        }
    }


    /**
     * Lấy danh sách sản phẩm theo type
     *
     * @param ProductListRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getProducts(ProductListRequest $request)
    {
        try {
            $data = $this->product->getProducts($request->all());

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (ProductRepoException $ex) {
            return $this->responseJson(CODE_ERROR, $ex->getMessage());
        }
    }

    /**
     * Lấy chi tiết sản phẩm
     *
     * @param ProductDetailRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getDetailProduct(ProductDetailRequest $request)
    {
        try {
            $lang = \request()->header('lang');

            $data = $this->product->getProductDetail($request->all(), $lang != null ? $lang : 'vi');

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (ProductRepoException $ex) {
            return $this->responseJson(CODE_ERROR, $ex->getMessage());
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
            $data = $this->product->getProductHome();

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (ProductRepoException $ex) {
            return $this->responseJson(CODE_ERROR, $ex->getMessage());
        }
    }

    /**
     * Từ khóa hot
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function hotKeywords()
    {
        try {
            $data = $this->product->hotKeyword();

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (ProductRepoException $ex) {
            return $this->responseJson(CODE_ERROR, $ex->getMessage());
        }
    }

    /**
     * Like hoặc unlike sản phẩm
     *
     * @param LikeUnlikeRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function likeUnlikeAction(LikeUnlikeRequest $request)
    {
        try {
            $data = $this->product->likeUnlike($request->all());

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (ProductRepoException $ex) {
            return $this->responseJson(CODE_ERROR, $ex->getMessage());
        }
    }

    /**
     * Lấy danh sách sản phẩm yêu thích
     *
     * @param ProductLikeListRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getListProductLikes(ProductLikeListRequest $request)
    {
        try {
            $data = $this->product->getListProductLike($request->all());

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (ProductRepoException $ex) {
            return $this->responseJson(CODE_ERROR, $ex->getMessage());
        }
    }

    /**
     * Lấy sản phẩm ETL
     *
     * @param GetProductETLRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getProductETL(GetProductETLRequest $request)
    {
        try {
            $data = $this->product->getProductETL($request->all());

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (ProductRepoException $ex) {
            return $this->responseJson(CODE_ERROR, $ex->getMessage());
        }
    }

    /**
     * Lay thong tin chung (banner + san pham noi bat + san pham khuyen mai)
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function generalInfo()
    {
        try {
            $data = $this->product->getGeneralInfo();

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (ProductRepoException $ex) {
            return $this->responseJson(CODE_ERROR, $ex->getMessage());
        }
    }

    /**
     * Un active sp không có image
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function unActiveProduct()
    {
        try {
            $data = $this->product->unActiveProduct();

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (ProductRepoException $ex) {
            return $this->responseJson(CODE_ERROR, $ex->getMessage());
        }
    }

    /**
     * Scan sản phẩm
     *
     * @param ScanProductRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function scanProduct(ScanProductRequest $request)
    {
        try {
            $lang = \request()->header('lang');

            $data = $this->product->scanProduct($request->all(), $lang != null ? $lang : 'vi');

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (ProductRepoException $ex) {
            return $this->responseJson(CODE_ERROR, $ex->getMessage());
        }
    }
}