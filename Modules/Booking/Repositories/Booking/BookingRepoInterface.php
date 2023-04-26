<?php
/**
 * Created by PhpStorm   .
 * User: Mr Son
 * Date: 2020-02-17
 * Time: 2:31 PM
 * @author SonDepTrai
 */

namespace Modules\Booking\Repositories\Booking;


interface BookingRepoInterface
{
    /**
     * Lấy rule setting other
     *
     * @return mixed
     */
    public function getSettingOther();

    /**
     * Lấy thời gian làm việc trong tuần
     *
     * @return mixed
     */
    public function getTimes();

    /**
     * Lấy danh sách kỹ thuật viên
     *
     * @param $input
     * @return mixed
     */
    public function getStaffs($input);

    /**
     * Kiểm tra số lần đặt lịch
     *
     * @param $input
     * @return mixed
     */
    public function checkAppointment($input);

    /**
     * Thêm lịch hẹn
     *
     * @param $input
     * @return mixed
     */
    public function store($input);

    /**
     * Cập nhật lịch hẹn
     *
     * @param $input
     * @return mixed
     */
    public function update($input);

    /**
     * Lấy thời gian đặt lịch
     *
     * @param $input
     * @return mixed
     */
    public function timeBooking($input);

    /**
     * Chi tiết lịch sử đặt lịch
     *
     * @param $appointmentId
     * @return mixed
     */
    public function getBookingHistoryDetail($appointmentId);

    /**
     * Lấy trạng thái lịch hẹn
     *
     * @return mixed
     */
    public function getStatusBooking();

    /**
     * Hủy lịch hẹn
     *
     * @param $input
     * @return mixed
     */
    public function cancel($input);

    /**
     * Đặt lịch lại
     *
     * @param $input
     * @return mixed
     */
    public function reBooking($input);

    /**
     * Lấy giá KM dịch vụ
     *
     * @param $input
     * @return mixed
     */
    public function getPriceService($input);

    /**
     * Lấy phòng phục vụ
     *
     * @return mixed
     */
    public function getRoom();

    /**
     * Lấy nguồn lịch hẹn
     *
     * @return mixed
     */
    public function getAppointmentSource();

    /**
     * DS lịch hẹn theo ngày/tuần/tháng
     *
     * @param $input
     * @return mixed
     */
    public function getListByDayWeekMonth($input);

    /**
     * DS lịch hẹn theo khung giờ
     *
     * @param $input
     * @return mixed
     */
    public function getListRangeTime($input);

    /**
     * Danh sách lịch hẹn của KH
     *
     * @param $input
     * @return mixed
     */
    public function getListCustomer($input);
}