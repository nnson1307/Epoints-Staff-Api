<?php
/**
 * Created by PhpStorm
 * User: PhongDT
 */

namespace Modules\TimeOffDays\Repositories\TimeOffDaysShifts;


interface TimeOffDaysShiftsRepoInterface
{
    /**
     * Danh sách loại ngày phép
     *
     * @param $input
     * @return mixed
     */
    public function getLists($input);

    /**
    * Thêm mới
    *
    * @param $input
    * @return mixed
    */
    public function add($data);

    /**
    * Thêm mới
    *
    * @param $input
    * @return mixed
    */
    public function remove($id);

      /**
     * Lấy tổng số ngày phép đã nghĩ
     * @data filter: 
     *  staff_id'
     *  time_off_type_id']
     *  month
     *  years
     *  month_reset
     */
    public function getNumberDaysOff($data);

    public function getListsByDaysOff($daysOffId);

     /**
     * Cập nhật loại thông tin kèm theo
     *
     * @param $data
     * @param $id
     * @return mixed
     */
    public function edit($data, $id);

}