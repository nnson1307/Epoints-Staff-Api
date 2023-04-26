<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 06/05/2021
 * Time: 15:10
 */

namespace Modules\User\Http\Api;

use MyCore\Api\ApiAbstract;

class BrandApi extends ApiAbstract
{
    /**
     * Lấy ds brand bằng client key
     *
     * @param array $filter
     * @return mixed
     * @throws \MyCore\Api\ApiException
     */
    public function getBrandByClient($filter = [])
    {
        return $this->baseClientPushNotification('admin/brand/get-brand-by-client', $filter, false);
    }
}