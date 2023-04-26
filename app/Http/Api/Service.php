<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 06/05/2021
 * Time: 13:52
 */

namespace App\Http\Api;


use MyCore\Api\ApiAbstract;

class Service extends ApiAbstract
{
    /**
     * Lấy tất cả brand của hệ thống
     *
     * @param array $filter
     * @return mixed
     * @throws \MyCore\Api\ApiException
     */
    public function getAllBrand($filter = [])
    {
        return $this->baseClientPushNotification('admin/brand/get-all', $filter, false);
    }
}