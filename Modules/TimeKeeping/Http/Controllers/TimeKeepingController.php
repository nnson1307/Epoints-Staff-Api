<?php

namespace Modules\TimeKeeping\Http\Controllers;

use Carbon\Carbon;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Modules\TimeKeeping\Http\Requests\CheckInRequest;
use Modules\TimeKeeping\Http\Requests\CheckOutRequest;
use Modules\TimeKeeping\Http\Requests\GetCurrentShiftRequest;
use Modules\TimeKeeping\Http\Requests\GetDayHolidayRequest;
use Modules\TimeKeeping\Http\Requests\TimeKeepingHistoryRequest;
use Modules\TimeKeeping\Repositories\TimeKeepingInterface;

class TimeKeepingController extends Controller
{
    protected $timeKeepingRepo;

    public function __construct(TimeKeepingInterface $timeKeepingRepo)
    {
        $this->timeKeepingRepo = $timeKeepingRepo;
    }

    /**
     * Lấy ca làm việc hiện tại của nhân viên
     *
     * @param GetCurrentShiftRequest $request
     * @return JsonResponse
     */
    public function getShiftAction(GetCurrentShiftRequest $request)
    {
        try {
            $data = $this->timeKeepingRepo->getShift($request->all());

            $data['carbon_now'] = Carbon::now()->format('Y-m-d H:i:s');

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (Exception | QueryException $exception) {
            return $this->responseJson(CODE_ERROR, $exception->getMessage());
        }
    }

    /**
     * Check in
     *
     * @param CheckInRequest $request
     * @return JsonResponse
     */
    public function checkInAction(CheckInRequest $request)
    {
        try {

            $all = $request->all();
            $all['request_ip'] = $request->ip();
            $all['access_point_check_sum'] = $request->headers->get('access-point-check-sum');
            $data = $this->timeKeepingRepo->checkIn($all);

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (Exception | QueryException $exception) {
            return $this->responseJson(CODE_ERROR, $exception->getMessage());
        }
    }

    /**
     * Check out
     *
     * @param CheckOutRequest $request
     * @return JsonResponse
     */
    public function checkOutAction(CheckOutRequest $request)
    {
        try {
            $all = $request->all();
            $all['request_ip'] = $request->ip();
            $all['access_point_check_sum'] = $request->headers->get('access-point-check-sum');
            $data = $this->timeKeepingRepo->checkOut($all);

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (Exception | QueryException $exception) {
            return $this->responseJson(CODE_ERROR, $exception->getMessage());
        }
    }

    /**
     * Lấy lịch sử chấm công
     *
     * @param TimeKeepingHistoryRequest $request
     * @return JsonResponse
     */
    public function getHistoryAction(TimeKeepingHistoryRequest $request)
    {
        try {
            $data = $this->timeKeepingRepo->getHistories($request->all());

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (Exception | QueryException $exception) {
            return $this->responseJson(CODE_ERROR, $exception->getMessage());
        }
    }

    /**
     * Lấy lịch sử chấm công
     *
     * @param TimeKeepingHistoryRequest $request
     * @return JsonResponse
     */
    public function getPersonalHistoryAction(TimeKeepingHistoryRequest $request)
    {

        try {
            $data = $this->timeKeepingRepo->getPersonalHistories($request->all());

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (Exception | QueryException $exception) {
            return $this->responseJson(CODE_ERROR, $exception->getMessage());
        }
    }

    /**
     * Lấy ngày lễ
     *
     * @param GetDayHolidayRequest $request
     * @return JsonResponse
     */
    public function getDayHoliday(GetDayHolidayRequest $request)
    {
        try {
            $data = $this->timeKeepingRepo->getDayHoliday($request->all());

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (Exception | QueryException $exception) {
            return $this->responseJson(CODE_ERROR, $exception->getMessage());
        }
    }
}