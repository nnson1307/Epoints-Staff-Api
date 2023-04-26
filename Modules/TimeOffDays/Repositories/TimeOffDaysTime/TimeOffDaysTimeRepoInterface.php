<?php
/**
 * Created by PhpStorm
 * User: PhongDT
 */

namespace Modules\TimeOffDays\Repositories\TimeOffDaysTime;


interface TimeOffDaysTimeRepoInterface
{
    /**
     * Danh sách loại ngày phép
     *
     * @param $input
     * @return mixed
     */
    public function getLists();

     /**
     * Danh sách loại ngày phép
     *
     * @param $input
     * @return mixed
     */
    public function getOption();

    
     /**
     * Danh sách loại ngày phép
     *
     * @param $input
     * @return mixed
     */
    public function getDetail($id);
}