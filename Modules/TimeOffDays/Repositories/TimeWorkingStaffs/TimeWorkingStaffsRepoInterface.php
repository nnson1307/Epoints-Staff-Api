<?php
/**
 * Created by PhpStorm
 * User: PhongDT
 */

namespace Modules\TimeOffDays\Repositories\TimeWorkingStaffs;


interface TimeWorkingStaffsRepoInterface
{
    /**
     * Danh sách loại ngày phép
     *
     * @param $input
     * @return mixed
     */
    public function getLists($input);

    
    public function edit($data, $id);

    public function removeTimeOffDay($id);

}