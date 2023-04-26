<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 15/02/2022
 * Time: 14:47
 */

namespace Modules\Order\Http\Api;

use MyCore\Api\ApiAbstract;

class ZnsApi extends ApiAbstract
{
    /**
     * Lưu log trigger event Zns
     *
     * @param array $data
     * @return mixed
     * @throws \MyCore\Api\ApiException
     */
    public function saveLogTriggerEvent(array $data = [])
    {
        return $this->baseClientShareService('/zns/trigger-event', $data, false);
    }
}