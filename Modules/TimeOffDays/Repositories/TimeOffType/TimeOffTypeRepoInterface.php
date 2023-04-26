<?php
/**
 * Created by PhpStorm
 * User: PhongDT
 */

namespace Modules\TimeOffDays\Repositories\TimeOffType;


interface TimeOffTypeRepoInterface
{
    /**
     * Danh sách loại ngày phép
     *
     * @param $input
     * @return mixed
     */
    public function getLists($input);
    /**
     * Danh sách loại ngày phép
     *
     * @param $input
     * @return mixed
     */
    public function getListsChild($id);
    /**
     * Chi tiết loại ngày phép
     *
     * @param $input
     * @return mixed
     */
    public function detail($id);
}