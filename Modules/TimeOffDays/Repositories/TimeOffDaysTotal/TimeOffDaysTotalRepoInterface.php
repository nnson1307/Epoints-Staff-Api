<?php
/**
 * Created by PhpStorm
 * User: PhongDT
 */

namespace Modules\TimeOffDays\Repositories\TimeOffDaysTotal;


interface TimeOffDaysTotalRepoInterface
{
    /**
     * Danh sách
     *
     * @param $input
     * @return mixed
     */
    public function getLists($id);

    public function checkValidTotal($staffId, $typeId);

    public function edit($data, $staffId, $typeOffDaysId);

}