<?php
/**
 * Created by PhpStorm
 * User: PhongDT
 */

namespace Modules\TimeOffDays\Repositories\TimeOffDaysLog;


interface TimeOffDaysLogRepoInterface
{
    /**
     * Danh sách log
     *
     * @param $input
     * @return mixed
     */
    public function getLists($input);

    /**
     * Ghi log
     *
     * @param $input
     * @return mixed
     */
    public function add($data);

}