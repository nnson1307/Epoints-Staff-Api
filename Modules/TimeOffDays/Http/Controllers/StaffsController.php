<?php

namespace Modules\TimeOffDays\Http\Controllers;

use Illuminate\Http\Request;

use Modules\TimeOffDays\Repositories\Staffs\StaffRepoException;
use Modules\TimeOffDays\Repositories\Staffs\StaffRepoInterface;

use Modules\TimeOffDays\Repositories\TimeOffDaysConfigApprove\TimeOffDaysConfigApproveRepoException;
use Modules\TimeOffDays\Repositories\TimeOffDaysConfigApprove\TimeOffDaysConfigApproveRepoInterface;



class StaffsController extends Controller
{

    protected $repo;
    protected $timeOffDaysConfig;

    public function __construct(
        StaffRepoInterface $repo,
        TimeOffDaysConfigApproveRepoInterface $timeOffDaysConfig
    ) {
        $this->repo = $repo;
        $this->timeOffDaysConfig = $timeOffDaysConfig;
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function list(Request $request)
    {

        try {
            $params = $request->all();

            // $data = $this->timeOffDaysConfig->getLists($params);
            // return $this->responseJson(CODE_SUCCESS, __('Xử lý thành công'), $data);
            $data = $this->repo->getListStaffApprove($params['time_off_type_id']);
            return $this->responseJson(CODE_SUCCESS,  __('Xử lý thành công'), $data);
        } catch (\Exception $ex) {

            return $this->responseJson(CODE_ERROR, $ex->getMessage());
        }
    }
}
