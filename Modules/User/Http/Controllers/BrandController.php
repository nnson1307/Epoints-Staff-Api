<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 06/05/2021
 * Time: 14:36
 */

namespace Modules\User\Http\Controllers;


use Modules\User\Http\Requests\Brand\GetBrandRequest;
use Modules\User\Repositories\Brand\BrandRepoException;
use Modules\User\Repositories\Brand\BrandRepoInterface;

class BrandController extends Controller
{
    protected $brand;

    public function __construct(
        BrandRepoInterface $brand
    ) {
        $this->brand = $brand;
    }

    /**
     * Lấy ds brand
     *
     * @param GetBrandRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getBrand(GetBrandRequest $request)
    {
        try {
            $data = $this->brand->getBrand($request->all());

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (BrandRepoException $ex) {
            return $this->responseJson(CODE_ERROR, $ex->getMessage());
        }
    }
}