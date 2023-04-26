<?php
/**
 * Created by PhpStorm
 * User: PhongDT
 */

namespace Modules\TimeOffDays\Repositories\TimeOffDaysFiles;


interface TimeOffDaysFilesRepoInterface
{
    /**
     * Danh sách
     *
     * @param $input
     * @return mixed
     */
    public function getLists($input);
    
    /**
     * Tạo
     *
     * @param $input
     * @return mixed
     */
    public function add($data);

    /**
     * Xóa
     *
     * @param $input
     * @return mixed
     */
    public function remove($id);

}