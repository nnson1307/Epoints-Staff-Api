<?php

namespace Modules\ChatHub\Repositories\Order;

interface OrderRepoInterface
{
    
      /**
     * Lấy danh sách đơn hàng
     *
     * @param $input
     * @return mixed
     */
    public function getOrders($input);

}



