<?php
/**
 * Created by PhpStorm
 * User: PhongDT
 */

namespace Modules\TimeOffDays\Repositories\SFShifts;


interface SFShiftsRepoInterface
{
    /**
     * Danh sách người duyệt
     *
     * @param $input
     * @return mixed
     */
    public function getLists($input);

}