<?php

/**
 * Created by PhpStorm   .
 * User: Mr Son
 * Date: 2020-02-17
 * Time: 2:28 PM
 * @author SonDepTrai
 */

namespace Modules\Booking\Http\Controllers;

use Illuminate\Database\QueryException;


use Illuminate\Http\Request;
use Modules\Booking\Http\Requests\Booking\BookingHistoryDetailRequest;
use Modules\Booking\Http\Requests\Booking\BookingHistoryRequest;
use Modules\Booking\Http\Requests\Booking\CancelRequest;
use Modules\Booking\Http\Requests\Booking\CheckAppointmentRequest;
use Modules\Booking\Http\Requests\Booking\GetListCustomerRequest;
use Modules\Booking\Http\Requests\Booking\GetPriceServiceRequest;
use Modules\Booking\Http\Requests\Booking\ListDayWeekMonthRequest;
use Modules\Booking\Http\Requests\Booking\ListRangeTimeRequest;
use Modules\Booking\Http\Requests\Booking\ReBookingRequest;
use Modules\Booking\Http\Requests\Booking\StaffListRequest;
use Modules\Booking\Http\Requests\Booking\StoreRequest;
use Modules\Booking\Http\Requests\Booking\TimeBookingRequest;
use Modules\Booking\Http\Requests\Booking\UpdateRequest;
use Modules\Booking\Repositories\Booking\BookingRepoException;
use Modules\Booking\Repositories\Booking\BookingRepoInterface;

class BookingController extends Controller
{
    protected $booking;

    public function __construct(
        BookingRepoInterface $booking
    ) {
        $this->booking = $booking;
    }

    /**
     * Chi tiết lịch sử đặt lịch
     *
     * @param BookingHistoryDetailRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function historyAppointmentDetail(BookingHistoryDetailRequest $request)
    {
        try {
            $data = $this->booking->getBookingHistoryDetail($request->customer_appointment_id);

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (BookingRepoException $ex) {
            return $this->responseJson(CODE_ERROR, $ex->getMessage());
        }
    }

    /**
     * Lấy rule setting other
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws BookingRepoException
     */
    public function getSettingOther()
    {
        try {
            $data = $this->booking->getSettingOther();

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (\Exception | QueryException $exception) {
            return $this->responseJson(CODE_ERROR, $exception->getMessage());
        }
    }

    /**
     * Lấy thời gian làm việc trong tuần
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws BookingRepoException
     */
    public function getTimes()
    {
        try {
            $data = $this->booking->getTimes();

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (\Exception | QueryException $exception) {
            return $this->responseJson(CODE_ERROR, $exception->getMessage());
        }
    }

    /**
     * Lấy danh sách kỹ thuật viên
     *
     * @param StaffListRequest $request
     * @return \Illuminate\Http\JsonResponse
     * @throws BookingRepoException
     */
    public function getStaffs(StaffListRequest $request)
    {
        try {
            $data = $this->booking->getStaffs($request->all());

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (\Exception | QueryException $exception) {
            return $this->responseJson(CODE_ERROR, $exception->getMessage());
        }
    }


    /**
     * Kiểm tra số lần đặt lịch
     *
     * @param CheckAppointmentRequest $request
     * @return \Illuminate\Http\JsonResponse
     * @throws BookingRepoException
     */
    public function checkAppointment(CheckAppointmentRequest $request)
    {
        try {
            $data = $this->booking->checkAppointment($request->all());

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (\Exception | QueryException $exception) {
            return $this->responseJson(CODE_ERROR, $exception->getMessage());
        }
    }

    /**
     * Thêm lịch hẹn
     *
     * @param StoreRequest $request
     * @return \Illuminate\Http\JsonResponse
     * @throws BookingRepoException
     */
    public function store(StoreRequest $request)
    {
        try {
            $data = $this->booking->store($request->all());

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (\Exception | QueryException $exception) {
            return $this->responseJson(CODE_ERROR, $exception->getMessage());
        }
    }

    /**
     * Cập nhật lịch hẹn
     *
     * @param UpdateRequest $request
     * @return \Illuminate\Http\JsonResponse
     * @throws BookingRepoException
     */
    public function update(UpdateRequest $request)
    {
        try {
            $data = $this->booking->update($request->all());

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (\Exception | QueryException $exception) {
            return $this->responseJson(CODE_ERROR, $exception->getMessage());
        }
    }

    /**
     * Thời gian đặt lịch
     *
     * @param TimeBookingRequest $request
     * @return \Illuminate\Http\JsonResponse
     * @throws BookingRepoException
     */
    public function timeBooking(TimeBookingRequest $request)
    {
        try {
            $data = $this->booking->timeBooking($request->all());

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (\Exception | QueryException $exception) {
            return $this->responseJson(CODE_ERROR, $exception->getMessage());
        }
    }

    /**
     * Trạng thái lịch hẹn
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getStatusBooking()
    {
        try {
            $data = $this->booking->getStatusBooking();

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (\Exception | QueryException $exception) {
            return $this->responseJson(CODE_ERROR, $exception->getMessage());
        }
    }

    /**
     * Hủy lịch hẹn
     *
     * @param CancelRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function cancelAction(CancelRequest $request)
    {
        try {
            $data = $this->booking->cancel($request->all());

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (\Exception | QueryException $exception) {
            return $this->responseJson(CODE_ERROR, $exception->getMessage());
        }
    }

    /**
     * Đặt lịch lại
     *
     * @param ReBookingRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function reBooking(ReBookingRequest $request)
    {
        try {
            $data = $this->booking->reBooking($request->all());

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (\Exception | QueryException $exception) {
            return $this->responseJson(CODE_ERROR, $exception->getMessage());
        }
    }

    /**
     * Lấy giá KM dịch vụ
     *
     * @param GetPriceServiceRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getPriceService(GetPriceServiceRequest $request)
    {
        try {
            $data = $this->booking->getPriceService($request->all());

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (\Exception | QueryException $exception) {
            return $this->responseJson(CODE_ERROR, $exception->getMessage());
        }
    }

    /**
     * Lấy phòng phục vụ
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getRoom()
    {
        try {
            $data = $this->booking->getRoom();

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (\Exception | QueryException $exception) {
            return $this->responseJson(CODE_ERROR, $exception->getMessage());
        }
    }

    /**
     * Lấy nguồn lịch hẹn
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAppointmentSource()
    {
        try {
            $data = $this->booking->getAppointmentSource();

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (\Exception | QueryException $exception) {
            return $this->responseJson(CODE_ERROR, $exception->getMessage());
        }
    }

    /**
     * DS lịch hẹn theo ngày/tuần/tháng
     *
     * @param ListDayWeekMonthRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getListByDayWeekMonth(ListDayWeekMonthRequest $request)
    {
        try {
            $data = $this->booking->getListByDayWeekMonth($request->all());

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (\Exception | QueryException $exception) {
            return $this->responseJson(CODE_ERROR, $exception->getMessage());
        }
    }

    /**
     * DS lịch hẹn theo khung giờ
     *
     * @param ListRangeTimeRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getListRangeTime(ListRangeTimeRequest $request)
    {
        try {
            $data = $this->booking->getListRangeTime($request->all());

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (\Exception | QueryException $exception) {
            return $this->responseJson(CODE_ERROR, $exception->getMessage());
        }
    }

    /**
     * Danh sách lịch hẹn của KH
     *
     * @param GetListCustomerRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getListCustomer(GetListCustomerRequest $request)
    {
        try {
            $data = $this->booking->getListCustomer($request->all());

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (\Exception | QueryException $exception) {
            return $this->responseJson(CODE_ERROR, $exception->getMessage());
        }
    }
}