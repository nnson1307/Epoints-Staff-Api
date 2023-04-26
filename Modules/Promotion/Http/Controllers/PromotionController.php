<?php

namespace Modules\Promotion\Http\Controllers;

use Modules\Promotion\Http\Requests\Promotion\DetailRequest;
use Modules\Promotion\Http\Requests\Promotion\ListRequest;
use Modules\Promotion\Repositories\Promotion\PromotionRepoException;
use Modules\Promotion\Repositories\Promotion\PromotionRepoInterface;

class PromotionController extends Controller
{
    protected $promotion;

    public function __construct(
        PromotionRepoInterface $promotion
    ) {
        $this->promotion = $promotion;
    }

    /**
     * Danh sách CTKM
     *
     * @param ListRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getLists(ListRequest $request)
    {
        try {
            $data = $this->promotion->getLists($request->all());

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (PromotionRepoException $ex) {
            return $this->responseJson(CODE_ERROR, $ex->getMessage());
        }
    }

    /**
     * Chi tiết CTKM
     *
     * @param DetailRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getDetail(DetailRequest $request)
    {
        try {
            $data = $this->promotion->getDetail($request->all());

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (PromotionRepoException $ex) {
            return $this->responseJson(CODE_ERROR, $ex->getMessage());
        }
    }
}
