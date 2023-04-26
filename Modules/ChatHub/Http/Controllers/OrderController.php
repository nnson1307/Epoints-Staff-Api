<?php

namespace Modules\ChatHub\Http\Controllers;

use Illuminate\Http\Request;
use Modules\ChatHub\Repositories\Order\OrderRepoInterface;
use Modules\ChatHub\Repositories\Order\OrderRepoException;
use Modules\ChatHub\Http\Requests\Order\OrderListRequest;

class OrderController extends Controller
{
    protected $order;

    public function __construct(
        OrderRepoInterface $order
    ) {
        $this->order = $order;
    }

  /**
     * Lấy danh sách đơn hàng
     *
     * @param OrderListRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getOrders(OrderListRequest $request)
    {
        try {
            $data = $this->order->getOrders($request->all());

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (OrderRepoException $ex) {
            return $this->responseJson(CODE_ERROR, $ex->getMessage());
        }
    }
}