<?php

namespace Modules\ChatHub\Http\Controllers;

use Illuminate\Http\Request;
use Modules\ChatHub\Repositories\Product\ProductRepoInterface;
use Modules\ChatHub\Repositories\Product\ProductRepoException;
use Modules\ChatHub\Http\Requests\Product\ProductListRequest;

class ProductController extends Controller
{
    protected $product;

    public function __construct(
        ProductRepoInterface $product
    ) {
        $this->product = $product;
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
}