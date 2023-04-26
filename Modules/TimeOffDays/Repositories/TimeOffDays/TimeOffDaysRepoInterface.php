<?php
/**
 * Created by PhpStorm
 * User: PhongDT
 */

namespace Modules\TimeOffDays\Repositories\TimeOffDays;


interface TimeOffDaysRepoInterface
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

        
    /**
     * Chi tiết ngày phép
     *
     * @param $id
     * @return mixed
     */
    public function detail($id);

    /**
     * Xóa ngày phép
     *
     * @param $id
     * @return mixed
     */
    public function remove($id);


    /**
     * Chỉnh sửa ngày phép
     *
     * @param $id
     * @return mixed
     */
    public function edit($input, $id);


    /**
     * Tổng ngày phép
     *
     * @param $id
     * @return mixed
     */
    public function total($id);


    /**
     * Tổng ngày phép
     *
     * @param $id
     * @return mixed
     */
    public function countById($id);
}