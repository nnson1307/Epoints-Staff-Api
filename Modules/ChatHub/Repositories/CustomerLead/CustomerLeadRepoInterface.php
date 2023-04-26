<?php

namespace Modules\ChatHub\Repositories\CustomerLead;

interface CustomerLeadRepoInterface
{
    
    /**
     * Lấy chi tiet KHTN
     * @param $input
     * @return mixed
     */
    public function getDetail($input);

     /**
     * Cập nhật hành trình khách hàng
     *
     * @param $input
     * @return array|mixed
     */
    public function updateJourney($input);

}



