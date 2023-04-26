<?php
namespace Modules\TimeKeeping\Repositories;


interface TimeKeepingInterface{

    /**
     * Lấy ca làm việc hiện tại của staff
     * @param array $all
     * @return mixed
     */
    public function getShift(array $all);

    /**
     * Check in
     * @param array $all
     * @return mixed
     */
    public function checkIn(array $all);

    /**
     * Check out
     * @param array $all
     * @return mixed
     */
    public function checkOut(array $all);

    /**
     * Lấy lịch sử chấm công
     *
     * @param array $all
     * @return mixed
     */
    public function getHistories(array $all);

    /**
     * Lấy lịch sử chấm công của cá nhân
     * @param array $all
     * @return mixed
     */
    public function getPersonalHistories(array $all);

    /**
     * Lấy ngày lễ
     *
     * @param $input
     * @return mixed
     */
    public function getDayHoliday($input);
}