<?php
/**
 * Created by PhpStorm
 * User: Mr Son
 * Date: 6/12/2020
 * Time: 2:50 PM
 */

namespace Modules\ChatHub\Repositories\ChatHub;


interface ChatHubRepoInterface
{
    /**
     * Lấy thông tin chi nhánh ETL
     *
     * @param $input
     * @return mixed
     */
    public function getCustomer($input);
}