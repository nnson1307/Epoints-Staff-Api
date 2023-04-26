<?php
/**
 * Created by PhpStorm
 * User: PhongDT
 */

namespace Modules\TimeOffDays\Repositories\TimeOffDaysActivityApprove;


interface TimeOffDaysActivityApproveRepoInterface
{
    /**
     * Danh sách ngày phép
     *
     * @param $input
     * @return mixed
     */
    public function getLists($input);
    
    /**
     * Tạo ngày phép
     *
     * @param $input
     * @return mixed
     */
    public function add($data);
}