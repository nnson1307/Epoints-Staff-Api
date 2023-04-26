<?php
/**
 * Created by PhpStorm   .
 * User: Mr Son
 * Date: 2020-01-04
 * Time: 10:56 AM
 * @author SonDepTrai
 */

namespace Modules\ChatHub\Repositories\Customer;


interface CustomerRepoInterface
{

    /**
     * Chi tiết khách hàng
     *
     * @param $input
     * @return mixed
     */
    public function getDetail($input);

}