<?php

namespace Modules\TimeOffDays\Http\Controllers;

use App;
use App\Jobs\FunctionSendNotify;
use Illuminate\Http\Request;
use Modules\TimeOffDays\Http\Requests\RequestForm\TimeOffDaysRequest;
use Modules\TimeOffDays\Http\Requests\RequestForm\TimeOffDaysActivityRequest;
use Modules\TimeOffDays\Http\Requests\RequestForm\TimeOffDaysDetailRequest;
use Modules\TimeOffDays\Http\Requests\RequestForm\TimeOffDaysEditRequest;
use Modules\TimeOffDays\Repositories\TimeOffDays\TimeOffDaysRepoInterface;
use Modules\TimeOffDays\Repositories\TimeOffDaysActivityApprove\TimeOffDaysActivityApproveRepoInterface;
use Modules\TimeOffDays\Repositories\TimeOffDaysFiles\TimeOffDaysFilesRepoInterface;
use Modules\TimeOffDays\Repositories\TimeOffDaysShifts\TimeOffDaysShiftsRepoInterface;
use Modules\TimeOffDays\Repositories\TimeOffDaysLog\TimeOffDaysLogRepoInterface;
use Illuminate\Support\Facades\Auth;
use Modules\TimeOffDays\Repositories\Staffs\StaffRepoInterface;
use Modules\TimeOffDays\Repositories\TimeOffDaysTotal\TimeOffDaysTotalRepoInterface;
use Modules\TimeOffDays\Repositories\TimeOffType\TimeOffTypeRepoInterface;
use Modules\TimeOffDays\Repositories\TimeOffDaysConfigApprove\TimeOffDaysConfigApproveRepoInterface;
use Modules\TimeOffDays\Repositories\TimeWorkingStaffs\TimeWorkingStaffsRepoInterface;
use Modules\TimeOffDays\Repositories\TimeOffDaysTime\TimeOffDaysTimeRepoInterface;
use Carbon\Carbon;
use Illuminate\Support\Str;

class TimeOffDaysController extends Controller
{
    protected $timeOffDays;
    protected $timeOffDaysActivity;
    protected $timeOffDaysFiles;
    protected $timeOffDaysShifts;
    protected $timeOffDaysLog;
    protected $staffs;
    protected $timeOffDaysTotal;
    protected $timeOffType;
    protected $timeOffDaysConfig;
    protected $timeWorkingStaff;
    protected $timeOffDaysTime;

    public function __construct(
        TimeOffDaysRepoInterface $timeOffDays,
        TimeOffDaysActivityApproveRepoInterface $timeOffDaysActivity,
        TimeOffDaysShiftsRepoInterface $timeOffDaysShifts,
        TimeOffDaysFilesRepoInterface $timeOffDaysFiles,
        StaffRepoInterface $staffs,
        TimeOffDaysTotalRepoInterface $timeOffDaysTotal,
        TimeOffTypeRepoInterface $timeOffType,
        TimeOffDaysLogRepoInterface $timeOffDaysLog,
        TimeOffDaysConfigApproveRepoInterface $timeOffDaysConfig,
        TimeWorkingStaffsRepoInterface $timeWorkingStaff,
        TimeOffDaysTimeRepoInterface $timeOffDaysTime
    ) {

        $this->timeOffDays = $timeOffDays;
        $this->timeOffDaysActivity = $timeOffDaysActivity;
        $this->timeOffDaysFiles = $timeOffDaysFiles;
        $this->timeOffDaysShifts = $timeOffDaysShifts;
        $this->timeOffDaysLog = $timeOffDaysLog;
        $this->staffs = $staffs;
        $this->timeOffDaysTotal = $timeOffDaysTotal;
        $this->timeOffType = $timeOffType;
        $this->timeOffDaysConfig = $timeOffDaysConfig;
        $this->timeWorkingStaff = $timeWorkingStaff;
        $this->timeOffDaysTime = $timeOffDaysTime;
    }
    /**
     * Danh sách ngày phép
     * @return Response
     */
    public function list(Request $request)
    {
        try {

            $data = $this->timeOffDays->getLists($request->all());

            foreach ($data as $key => $item) {
                $data[$key] = $item;
                $data[$key]['is_update'] = 0;
             
                $arrStaff = [];
                if(isset($data['staff_id_level1'])){
                    $staffInfo = $this->staffs->getDetailStaffApproveInfo($item['staff_id_level1']);
                    if(isset($staffInfo)){
                        $item['staff_id_approve_level1'] = $staffInfo['staff_id'];
                        $staffInfo['is_approvce'] = $item['is_approve_level1'];
                        array_push($arrStaff, $staffInfo);
                    }
                }
                if(isset($data['staff_id_level2'])){
                    $staffInfo = $this->staffs->getDetailStaffApproveInfo($item['staff_id_level2']);
                    if(isset($staffInfo)){
                        $item['staff_id_approve_level2'] = $staffInfo['staff_id'];
                        $staffInfo['is_approvce'] = $item['is_approve_level2'];
                        array_push($arrStaff, $staffInfo);
                    }
                }
                if(isset($data['staff_id_level3'])){
                    $staffInfo = $this->staffs->getDetailStaffApproveInfo($item['staff_id_level3']);
                   
                    if(isset($staffInfo)){
                        $item['staff_id_approve_level3'] = $staffInfo['staff_id'];
                        $staffInfo['is_approvce'] = $item['is_approve_level3'];
                        array_push($arrStaff, $staffInfo);
                    }
                }
            //     if($item['direct_management_approve'] == 1){ 
            //         $staffInfo = $this->staffs->getDetailApproveLevel1($item['department_id']);
            //         if(isset($staffInfo)){
            //             $item['staff_id_approve_level1'] = $staffInfo['staff_id'];
            //             $staffInfo['is_approvce'] = $item['is_approve_level1'];
            //             array_push($arrStaff, $staffInfo);
            //         }
            //    }
            //    if(isset($data['staff_id_approve_level2'])){
            //         $staffInfo = $this->staffs->getDetailStaffApproveInfo($data['staff_id_approve_level2']);
            //         if(isset($staffInfo)){
            //             $arrStaff[] =
            //                 [
            //                     'staff_id' => $staffInfo['staff_id'],
            //                     'full_name' => $staffInfo['full_name'],
            //                     'staff_avatar' => $staffInfo['staff_avatar'],
            //                     'staff_title' => $staffInfo['staff_title'],
            //                     'staff_title_id' => $staffInfo['staff_title_id'],
            //                     'department_name' => $staffInfo['department_name'],
            //                     'is_approvce' => isset($data['staff_id_level2']) && $data['staff_id_level2'] > 0 ? 1 : 0
            //                 ];
            //         }
            //     }
            //     if(isset($data['staff_id_approve_level3'])){
            //         $staffInfo = $this->staffs->getDetailStaffApproveInfo($data['staff_id_approve_level3']);
            //         if(isset($staffInfo)){
            //             $arrStaff[] =
            //             [
            //                 'staff_id' => $staffInfo['staff_id'],
            //                 'full_name' => $staffInfo['full_name'],
            //                 'staff_avatar' => $staffInfo['staff_avatar'],
            //                 'staff_title' => $staffInfo['staff_title'],
            //                 'staff_title_id' => $staffInfo['staff_title_id'],
            //                 'department_name' => $staffInfo['department_name'],
            //                 'is_approvce' => isset($data['staff_id_level3']) && $data['staff_id_level3'] > 0 ? 1 : 0
            //             ];
            //         }
            //     }
            if((Auth()->id() == $item['staff_id_approve_level1'] && $item['is_approve_level1'] === null) || (in_array(Auth()->id(), json_decode($item['staff_id_approve_level2'])) && $item['is_approve_level2'] === null) || (in_array(Auth()->id(), json_decode($item['staff_id_approve_level3'])))){
                $data[$key]['is_update'] = 1;
            }
            //    if ((Auth()->id() == $item['staff_id_approve_level1'] && $item['is_approve_level1'] === null) || (Auth()->id() == $item['staff_id_approve_level2'] && $item['is_approve_level2'] === null) || ( Auth()->id() == $item['staff_id_approve_level3'])){
            //         $data[$key]['is_update'] = 1;
            //     }
                // //Check người duyệt cấp 1
                
                $data[$key]['staffs'] = $arrStaff;
            }


            return $this->responseJson(CODE_SUCCESS,  __('Xử lý thành công'), $data);
        } catch (\Exception $ex) {

            return $this->responseJson(CODE_ERROR, $ex->getMessage());
        }
    }

    /**
     * Tạo ngày phép
     * @return Response
     */
    public function create(TimeOffDaysRequest $request)
    {
        try {

            $params = $request->all();

            $paramsInsert['time_off_type_id']       = $params['time_off_type_id'] ?? 0;
            $paramsInsert['time_off_days_start']    = $params['time_off_days_start'] ?? '';
            $paramsInsert['time_off_days_end']      = $params['time_off_days_end'] ?? '';
            $paramsInsert['time_off_days_time']     = $params['time_off_days_time'] ?? '';
            $paramsInsert['time_off_note']          = $params['time_off_note'] ?? '';
            $paramsInsert['date_type_select']       = $params['date_type_select'] ?? 'one-day';
            $paramsInsert['staff_id']            = Auth()->id();
            $paramsInsert['created_by']          = Auth()->id();
            $paramsInsert['created_at']          = Carbon::now()->format("Y-m-d H:i:s");
            $paramsInsert['updated_at']          = Carbon::now()->format("Y-m-d H:i:s");

            //check có chọn ca chưa
            if(!isset($request->time_off_days_shift) || count($request->time_off_days_shift) == 0){
                return $this->responseJson(CODE_ERROR,  __('Chưa chọn ca nghĩ'), null);
            }
            //Check giới hạn ngày phép
            $infoDaysTotal = $this->timeOffDaysTotal->checkValidTotal(Auth()->id(), $request->time_off_type_id);
            if(isset($infoDaysTotal)){
                if($infoDaysTotal['time_off_days_number'] != -1){
                    if($infoDaysTotal['time_off_days_number'] < count($request->time_off_days_shift)){
                        return $this->responseJson(CODE_ERROR,  __('Số ngày phép vượt quá giới hạn cho phép'), null);
                    }
                }
            }

            $id = $this->timeOffDays->add($paramsInsert);

            if ($id) {

                if (isset($params['time_off_days_files']) && count($params['time_off_days_files'])) {
                    foreach ($request->input('time_off_days_files') as $item) {
                        $input['time_off_days_id'] = $id;
                        $input['time_off_days_files_name'] = $item;
                        $result = $this->timeOffDaysFiles->add($input);
                    }
                }
                if (isset($params['time_off_days_shift']) && count($params['time_off_days_shift'])) {
                    foreach ($request->input('time_off_days_shift') as $item) {
                      
                        $this->timeWorkingStaff->edit(['time_of_days_id' => $id], (int)$item);
                            
                        //insert ca xin nghĩ
                        $dataShift = [
                            "time_off_days_id" => $id,
                            "time_working_staff_id" => (int)$item,
                            "is_approve" => null,
                            "created_at" => Carbon::now()->format("Y-m-d H:i:s"),
                            "updated_at" => Carbon::now()->format("Y-m-d H:i:s"),
                            "created_by" => Auth()->id(),
                            "updated_by" => Auth()->id(),
                            "created_days" => Carbon::now()->day,
                            "created_months" => Carbon::now()->month,
                            "created_years" => Carbon::now()->year,
                            "time_off_type_id" => $request->time_off_type_id,
                            "staff_id" => Auth()->id()
                        ];
                        $this->timeOffDaysShifts->add($dataShift);
                    }
                }
                $param['time_off_days_action'] = 'create';
                $param['time_off_days_id'] = $id;
                $param['time_off_days_title'] = __('Tạo đơn nghỉ phép');
                $param['time_off_days_content'] = __('Tạo đơn nghỉ phép');
                $param['created_by'] = Auth()->id();
                $param['created_at'] = Carbon::now();
                $this->timeOffDaysLog->add($param);

                //Cập nhật lại số lượng ngày phép
                if(isset($infoDaysTotal)){
                    if($infoDaysTotal['time_off_days_number'] != -1){
                        $daysTotal = $infoDaysTotal['time_off_days_number'] - count($request->time_off_days_shift);
                        $this->timeOffDaysTotal->edit(['time_off_days_number' => $daysTotal], $infoDaysTotal['time_off_days_total_id'], Auth()->id());
                    }
                }
                
                $data = $this->detailAction($id);
                //Lấy thông tin loại đơn
                if(isset($data)){
                     $arrStaff = [];
                     if($data['direct_management_approve'] == 1){
                         $infoApproveLevel1 = $this->staffs->getDetailApproveLevel1(Auth()->user()->department_id);
                         if(isset($infoApproveLevel1)){
                             $arrStaff[] = $infoApproveLevel1['staff_id'];
                         }
                     }
                     if(isset($data['staff_id_approve_level2'])){
                         foreach (json_decode($data['staff_id_approve_level2']) as $value) {
                             $arrStaff[] = $value;
                         }
                     }
                     if(isset($data['staff_id_approve_level3'])){
                         foreach (json_decode($data['staff_id_approve_level3']) as $value) {
                             $arrStaff[] = $value;
                         }
                     }
                     if(count($arrStaff) > 0){
                         foreach ($arrStaff as $value) {
                             App\Jobs\FunctionSendNotify::dispatch([
                                 'type' => SEND_NOTIFY_STAFF,
                                 'key' => 'time_off_days_waiting', //Key nào mình muốn gửi thì config
                                 'customer_id' => null, //Này ko có thì để rỗng
                                 'object_id' => $id, //Đối tượng ăn theo key
                                 'branch_id' => Auth()->user()->branch_id,
                                 'tenant_id' => session()->get('idTenant'),
                                 'staff_id' => $value,
                                 'model' => json_encode(["time_off_days_id" => $request->time_off_days_id, "is_personal" => 0]),
                                 'content' => 'Xin ' . $data['time_off_type_name']. 'từ ngày: ' . $data['time_off_days_start']. ' đến ngày: ' . $data['time_off_days_end']
                             ]);
                         }
                     }
                 
                }
            }
            return $this->responseJson(CODE_SUCCESS,  __('Tạo đơn nghỉ phép thành công'), $data);
        } catch (\Exception $ex) {

            return $this->responseJson(CODE_ERROR, $ex->getMessage(), $ex->getLine());
        }
    }

    /**
     * Duyệt ngày phép
     * @return Response
     */
    public function activity(TimeOffDaysActivityRequest $request)
    {
        try {
            $id = $request->input('time_off_days_id');
            $idReturn = 0;
            if($request->is_approvce == 1){
                $idReturn = $this->approveAction($id);
            }else {
                $idReturn = $this->unApproveAction($id, $request->input('time_off_days_activity_approve_note'));
            }
            if($idReturn > 0){
                $data = $this->detailAction($id);
                return $this->responseJson(CODE_SUCCESS,  __('Phê duyệt đơn phép thành công'), $data);
            }
            
        } catch (\Exception $ex) {
            return $this->responseJson(CODE_ERROR, $ex->getMessage());
        }
    }

    /**
     * Chi tiết ngày phép
     * @return Response
     */
    public function detail(TimeOffDaysDetailRequest $request)
    {
        try {
            $id = $request->input('time_off_days_id') ?? 0;

            $data = $this->detailAction($id);

            return $this->responseJson(CODE_SUCCESS, __('Xử lý thành công'), $data);
        } catch (\Exception $ex) {

            return $this->responseJson(CODE_ERROR, $ex->getMessage());
        }
    }

    /**
     * Cancel ngày phép
     * @return Response
     */
    public function cancel(TimeOffDaysActivityRequest $request)
    {
        try {
            $id = $this->timeOffDaysActivity->add($request->all());
            if ($id) {

                $input['time_off_days_id'] = $request->input('time_off_days_id');
                $input['is_approve'] = $request->input('is_approve') ?? 0;
                $this->timeOffDaysShifts->add($input);

                $daysTd = $request->input('time_off_days_id');

                $param['time_off_days_action'] = "cancel";
                $param['time_off_days_id'] = $daysTd;
                $param['time_off_days_title'] = __('Huỷ đơn nghỉ phép');
                $param['time_off_days_content'] = __('Huỷ đơn nghỉ phép');
                $param['created_by'] = Auth()->id();
                $param['created_at'] = Carbon::now();
                $this->timeOffDaysLog->add($param);
            }

            return $this->responseJson(CODE_SUCCESS, __('Cancel đơn phép thành công'), $id);
        } catch (\Exception $ex) {

            return $this->responseJson(CODE_ERROR, $ex->getMessage());
        }
    }

    /**
     * Danh sách ngày phép - tìm kiếm
     * @return Response
     */
    public function search(Request $request)
    {
        try {

            $data = $this->timeOffDays->getLists($request->all());

            foreach ($data as $key => $item) {
                $data[$key] = $item;
                $data[$key]['is_update'] = 0;
                if($item['direct_management_approve'] == 1){ 
                    $staffInfo = $this->staffs->getDetailApproveLevel1($item['department_id']);
                    if(isset($staffInfo)){
                        $item['staff_id_approve_level1'] = $staffInfo['staff_id'];
                    }
               }
                $arrStaff = [];
                if(isset($data['staff_id_level1'])){
                    $staffInfo = $this->staffs->getDetailStaffApproveInfo($item['staff_id_level1']);
                    if(isset($staffInfo)){
                        $arrStaff[] =
                            [
                                'staff_id' => $staffInfo['staff_id'],
                                'full_name' => $staffInfo['full_name'],
                                'staff_avatar' => $staffInfo['staff_avatar'],
                                'staff_title' => $staffInfo['staff_title'],
                                'staff_title_id' => $staffInfo['staff_title_id'],
                                'department_name' => $staffInfo['department_name'],
                                'is_approvce' => isset($data['staff_id_level1']) && $data['staff_id_level1'] > 0 ? 1 : 0
                            ];
                    }
                }
                if(isset($data['staff_id_level2'])){
                    $staffInfo = $this->staffs->getDetailStaffApproveInfo($item['staff_id_level2']);
                    if(isset($staffInfo)){
                        $arrStaff[] =
                            [
                                'staff_id' => $staffInfo['staff_id'],
                                'full_name' => $staffInfo['full_name'],
                                'staff_avatar' => $staffInfo['staff_avatar'],
                                'staff_title' => $staffInfo['staff_title'],
                                'staff_title_id' => $staffInfo['staff_title_id'],
                                'department_name' => $staffInfo['department_name'],
                                'is_approvce' => isset($data['staff_id_level2']) && $data['staff_id_level2'] > 0 ? 1 : 0
                            ];
                    }
                }
                if(isset($data['staff_id_level3'])){
                    $staffInfo = $this->staffs->getDetailStaffApproveInfo($item['staff_id_level3']);
                   
                    if(isset($staffInfo)){
                        if(isset($staffInfo)){
                            $arrStaff[] =
                                [
                                    'staff_id' => $staffInfo['staff_id'],
                                    'full_name' => $staffInfo['full_name'],
                                    'staff_avatar' => $staffInfo['staff_avatar'],
                                    'staff_title' => $staffInfo['staff_title'],
                                    'staff_title_id' => $staffInfo['staff_title_id'],
                                    'department_name' => $staffInfo['department_name'],
                                    'is_approvce' => isset($data['staff_id_level3']) && $data['staff_id_level3'] > 0 ? 1 : 0
                                ];
                        }
                    }
                }
           
            //    if(isset($data['staff_id_approve_level2'])){
            //         $staffInfo = $this->staffs->getDetailStaffApproveInfo($data['staff_id_approve_level2']);
            //         if(isset($staffInfo)){
            //             $arrStaff[] =
            //                 [
            //                     'staff_id' => $staffInfo['staff_id'],
            //                     'full_name' => $staffInfo['full_name'],
            //                     'staff_avatar' => $staffInfo['staff_avatar'],
            //                     'staff_title' => $staffInfo['staff_title'],
            //                     'staff_title_id' => $staffInfo['staff_title_id'],
            //                     'department_name' => $staffInfo['department_name'],
            //                     'is_approvce' => isset($data['staff_id_level2']) && $data['staff_id_level2'] > 0 ? 1 : 0
            //                 ];
            //         }
            //     }
            //     if(isset($data['staff_id_approve_level3'])){
            //         $staffInfo = $this->staffs->getDetailStaffApproveInfo($data['staff_id_approve_level3']);
            //         if(isset($staffInfo)){
            //             $arrStaff[] =
            //             [
            //                 'staff_id' => $staffInfo['staff_id'],
            //                 'full_name' => $staffInfo['full_name'],
            //                 'staff_avatar' => $staffInfo['staff_avatar'],
            //                 'staff_title' => $staffInfo['staff_title'],
            //                 'staff_title_id' => $staffInfo['staff_title_id'],
            //                 'department_name' => $staffInfo['department_name'],
            //                 'is_approvce' => isset($data['staff_id_level3']) && $data['staff_id_level3'] > 0 ? 1 : 0
            //             ];
            //         }
            //     }
            //    if ((Auth()->id() == $item['staff_id_approve_level1'] && $item['is_approve_level1'] === null) || (Auth()->id() == $item['staff_id_approve_level2'] && $item['is_approve_level2'] === null) || ( Auth()->id() == $item['staff_id_approve_level3'])){
            //         $data[$key]['is_update'] = 1;
            //     }
        
                // //Check người duyệt cấp 1
                if(Auth()->id() == $item['staff_id_approve_level1'] && $item['is_approve_level1'] === null){
                    $data[$key]['is_update'] = 1;
                }
                if(isset($item['staff_id_approve_level2'])){
                    if((in_array(Auth()->id(), json_decode($item['staff_id_approve_level2'])) && $item['is_approve_level2'] === null)){
                        $data[$key]['is_update'] = 1;
                    }
                }
                if(isset($item['staff_id_approve_level3'])){
                    if((in_array(Auth()->id(), json_decode($item['staff_id_approve_level3'])))){
                        $data[$key]['is_update'] = 1;
                    }
                }
                $data[$key]['staffs'] = $arrStaff;
            }
            return $this->responseJson(CODE_SUCCESS,  __('Xử lý thành công'), $data);
        } catch (\Exception $ex) {

            return $this->responseJson(CODE_ERROR, $ex->getMessage());
        }
    }

    /**
     * xóa ngày phép
     * @return Response
     */
    public function remove(TimeOffDaysDetailRequest $request)
    {
        try {
            $id = $request->input('time_off_days_id') ?? 0;
            $data = $this->timeOffDays->remove($id);

            $param['time_off_days_action'] = "delete";
            $param['time_off_days_id'] = $id;
            $param['time_off_days_title'] = __('Xóa đơn nghỉ phép');
            $param['time_off_days_content'] = __('Xóa đơn nghỉ phép');
            $param['created_by'] = Auth()->id();
            $param['created_at'] = Carbon::now();
            $this->timeOffDaysLog->add($param);


            return $this->responseJson(CODE_SUCCESS,  __('Xóa đơn phép thành công'), $data);
        } catch (\Exception $ex) {

            return $this->responseJson(CODE_ERROR, $ex->getMessage());
        }
    }

    /**
     * xóa ngày phép
     * @return Response
     */
    public function edit(TimeOffDaysEditRequest $request)
    {
        try {

            $id = $request->input('time_off_days_id') ?? 0;
            $input = $request->all();

            $paramsInsert['time_off_type_id'] = $input['time_off_type_id'] ?? 0;
            $paramsInsert['time_off_days_start'] = $input['time_off_days_start'] ?? '';
            $paramsInsert['time_off_days_end'] = $input['time_off_days_end'] ?? '';
            $paramsInsert['time_off_days_time'] = $input['time_off_days_time'] ?? '';
            $paramsInsert['time_off_note'] = $input['time_off_note'] ?? '';
            $paramsInsert['time_off_days_files'] = $input['time_off_days_files'] ?? [];
            $paramsInsert['time_off_days_shift'] = $input['time_off_days_shift'] ?? [];
            $paramsInsert['date_type_select']    = $input['date_type_select'] ?? 'one-day';
            //hieupc
            $resultId = $this->timeOffDays->edit($paramsInsert, $id);

            if ($resultId) {

                $this->timeOffDaysFiles->remove($id);
                $this->timeOffDaysShifts->remove($id);
                $this->timeWorkingStaff->removeTimeOffDay($id);
                if (isset($paramsInsert['time_off_days_files'])  && count($paramsInsert['time_off_days_files'])) {

                    foreach ($paramsInsert['time_off_days_files'] as $item) {
                        $input['time_off_days_id'] = $id;
                        $input['time_off_days_files_name'] = $item;
                        $result = $this->timeOffDaysFiles->add($input);
                    }
                }
                if (isset($params['time_off_days_shift']) && count($params['time_off_days_shift'])) {
                    foreach ($request->input('time_off_days_shift') as $item) {
                      
                        $this->timeWorkingStaff->edit(['time_of_days_id' => $id], (int)$item);
                            
                        //insert ca xin nghĩ
                        $dataShift = [
                            "time_off_days_id" => $id,
                            "time_working_staff_id" => (int)$item,
                            "is_approve" => null,
                            "created_at" => Carbon::now()->format("Y-m-d H:i:s"),
                            "updated_at" => Carbon::now()->format("Y-m-d H:i:s"),
                            "created_by" => Auth()->id(),
                            "updated_by" => Auth()->id(),
                            "created_days" => Carbon::now()->day,
                            "created_months" => Carbon::now()->month,
                            "created_years" => Carbon::now()->year,
                            "time_off_type_id" => $request->time_off_type_id,
                            "staff_id" => Auth()->id()
                        ];
                        $this->timeOffDaysShifts->add($dataShift);
                    }
                }

                $param['time_off_days_action'] = "update";
                $param['time_off_days_id'] = $id;
                $param['time_off_days_title'] = __('Cập nhật đơn nghỉ phép');
                $param['time_off_days_content'] = __('Cập nhật đơn nghỉ phép');
                $param['created_by'] = Auth()->id();
                $param['created_at'] = Carbon::now();
                $this->timeOffDaysLog->add($param);
            }

            $data = $this->detailAction($id);
            return $this->responseJson(CODE_SUCCESS,  __('Chỉnh sửa đơn phép thành công'), $data);
        } catch (\Exception $ex) {

            return $this->responseJson(CODE_ERROR, $ex->getMessage());
        }
    }

    /**
     * tổng ngày phép
     * @return Response
     */
    public function total(Request $request)
    {
        try {
            $now = Carbon::now();
            $month = $now->month;
            $id = $request->input('time_off_type_id') ?? 1;
            $detailType = $this->timeOffType->detail($id);
            $isDay = 1;
            $authId = Auth()->id();
            $totalDaysUsed = -1;
            if($detailType['total_number'] != -1){
                $infoDaysUsed = $this->timeOffDaysShifts->getNumberDaysOff(
                    [
                        'staff_id' => $authId,
                        'time_off_type_id' => $id,
                        'month' => Carbon::now()->month,
                        'years' => Carbon::now()->year,
                        'month_reset' => $detailType['month_reset']
                    ]
                );
              
                $totalDaysUsed = $infoDaysUsed->total;
            }
            if(isset($detailType) && $detailType['time_off_type_code'] == '017'){
                $data = array(
                    [
                        'key' => 'Tổng số lần xin đi trễ', 
                        'value' => $totalDaysUsed == -1 ? 'Không giới hạn' : $totalDaysUsed.' lần', 
                    ],
                    [
                        'key' => 'Giới hạn đi trễ', 
                        'value' => $detailType['total_number'] == -1 ? 'Không giới hạn' : $detailType['total_number'].' lần', 
                    ]
                );
            }elseif($detailType['time_off_type_code'] == '018'){
                $data = array(
                    [
                        'key' => 'Đã dùng', 
                        'value' => $totalDaysUsed == -1 ? 'Không giới hạn' : $totalDaysUsed.' lần', 
                    ],
                    [
                        'key' => 'Giới hạn về sớm', 
                        'value' => $detailType['total_number'] == -1 ? 'Không giới hạn' : $detailType['total_number'].' lần', 
                    ]
                );
            }else{
                $data = array(
                    [
                        'key' => 'Quỹ phép khả dụng', 
                        'value' => $totalDaysUsed == -1 ? 'Không giới hạn' :$totalDaysUsed.' ngày', 
                    ],
                    [
                        'key' => 'Quỹ phép năm ('. Carbon::now()->year . ')', 
                        'value' => $detailType['total_number'] == -1 ? 'Không giới hạn' : $detailType['total_number'].' ngày', 
                    ]
                );
            }
            return $this->responseJson(CODE_SUCCESS,  __('Xử lý thành công'), $data);

        } catch (\Exception $ex) {

            return response()->json($ex->getMessage());

        }
    }

    /**
     * đếm số đơn nghĩ phép ngày phép
     * @return Response
     */
    public function count(Request $request)
    {
        try {
            $authId = Auth()->id();

            $number = $this->timeOffDays->countById($authId);

            $count = ['count' => $number];

            return $this->responseJson(CODE_SUCCESS,  __('Xử lý thành công'), $count);
        } catch (\Exception $ex) {

            return $this->responseJson(CODE_ERROR, $ex->getMessage());
        }
    }


    /**
     * Chi tiết ngày phép
     * @return Response
     */
    protected function detailAction($id)
    {

        $data = $this->timeOffDays->detail($id);
        if(isset($data)){
            $paramsId['time_off_days_id'] = $id;
            $data['is_update'] = 0;
            $data['time_off_days_files'] = $this->timeOffDaysFiles->getLists($paramsId) ?? [];
    
            $data['time_off_days_shifts'] = $this->timeOffDaysShifts->getLists($paramsId) ?? [];
    
            if ($data['time_off_days_shifts']) {
                foreach ($data['time_off_days_shifts'] as $key => $item) {
                    $workingDay = '';
                    if ($item['working_day']) {
                        $workingDay = Carbon::createFromFormat('Y-m-d', $item['working_day'])->format('d/m');
                    }
    
                    $data['time_off_days_shifts'][$key]['time_working_staff_id'] = $item['time_working_staff_id'];
                    $data['time_off_days_shifts'][$key]['shift_name'] = Str::replaceLast('Ca', 'Ca nghỉ', $item['shift_name']) . ' - ' . $workingDay;
                }
            }
    
            $data['time_off_days_time_text'] = '';
            if (isset($data['time_off_days_time'])) {
    
                $objTime = $this->timeOffDaysTime->getDetail($data['time_off_days_time']);
                if (isset($objTime)) {
                    $data['time_off_days_time_text'] = $objTime['time_name'];
                }
            }
    
            $data['is_update'] = 0;
            if (Auth()->id() == $data['staff_id']){
                $data['is_update'] = 1;
            }
            
            $arrStaff = [];
            if($data['direct_management_approve'] != 0){
                $staffInfo = $this->staffs->getDetailApproveLevel1(Auth::user()->department_id);
               
                if(isset($staffInfo)){
                    $arrStaff[] =
                        [
                            'staff_id' => $staffInfo['staff_id'],
                            'full_name' => $staffInfo['full_name'],
                            'staff_avatar' => $staffInfo['staff_avatar'],
                            'staff_title' => $staffInfo['staff_title'],
                            'staff_title_id' => $staffInfo['staff_title_id'],
                            'department_name' => $staffInfo['department_name'],
                            'is_approvce' => isset($data['staff_id_level1']) && $data['staff_id_level1'] > 0 ? 1 : 0
                        ];
                }
            }

            if(isset($data['staff_id_approve_level2'])){
                $staffInfo = $this->staffs->getDetailStaffApproveInfo($data['staff_id_approve_level2']);
                if(isset($staffInfo)){
                    $arrStaff[] =
                        [
                            'staff_id' => $staffInfo['staff_id'],
                            'full_name' => $staffInfo['full_name'],
                            'staff_avatar' => $staffInfo['staff_avatar'],
                            'staff_title' => $staffInfo['staff_title'],
                            'staff_title_id' => $staffInfo['staff_title_id'],
                            'department_name' => $staffInfo['department_name'],
                            'is_approvce' => isset($data['staff_id_level2']) && $data['staff_id_level2'] > 0 ? 1 : 0
                        ];
                }
            }
            if(isset($data['staff_id_approve_level3'])){
                $staffInfo = $this->staffs->getDetailStaffApproveInfo($data['staff_id_approve_level3']);
                if(isset($staffInfo)){
                    $arrStaff[] =
                    [
                        'staff_id' => $staffInfo['staff_id'],
                        'full_name' => $staffInfo['full_name'],
                        'staff_avatar' => $staffInfo['staff_avatar'],
                        'staff_title' => $staffInfo['staff_title'],
                        'staff_title_id' => $staffInfo['staff_title_id'],
                        'department_name' => $staffInfo['department_name'],
                        'is_approvce' => isset($data['staff_id_level3']) && $data['staff_id_level3'] > 0 ? 1 : 0
                    ];
                }
            }
           
            $data['staffs'] = $arrStaff;
        }
        return  $data;
    }

    /**
     * Duyệt ngày phép
     * @return Response
     */
    protected function approveAction($id)
    {
        try {
            $data = $this->timeOffDays->detail($id);
            $authId = Auth()->id();
            $data['staff_id_approve_level1'] = null;
            $dataUpdate['is_approve'] = null;
          
            if(isset($data) && $data['direct_management_approve'] == 1){
              
                $infoApproveLevel1 = $this->staffs->getDetailApproveLevel1($data['department_id']);
             
                if(isset($infoApproveLevel1)){
                    $data['staff_id_approve_level1'] = $infoApproveLevel1['staff_id'];
                    if($authId == $infoApproveLevel1['staff_id'] ){
                        $dataUpdate['staff_id_level1'] = $authId;
                        $dataUpdate['is_approve_level1'] = 1;
                        $data['is_approve_level1'] = 1;
                        $data['staff_id_level1'] = $authId;
                        
                    }
                }
            }
            if(isset($data['staff_id_approve_level2']) && in_array($authId, json_decode($data['staff_id_approve_level2']))){
                $dataUpdate['staff_id_level2'] = $authId;
                $dataUpdate['is_approve_level2'] = 1;
                $data['is_approve_level2'] = 1;
                $data['staff_id_level2'] = $authId;
            }
            if(isset($data['staff_id_approve_level3']) && in_array($authId, json_decode($data['staff_id_approve_level3']))){
                $dataUpdate['staff_id_level3'] = $authId;
                $dataUpdate['is_approve_level3'] = 1;
                $data['is_approve_level3'] = 1;
                $data['staff_id_level3'] = $authId;
            }
            if($data['staff_id_approve_level1'] == $data['staff_id_level1'] && in_array($data['staff_id_level2'], json_decode($data['staff_id_approve_level2'])) && in_array($data['staff_id_level3'], json_decode($data['staff_id_approve_level3']))){
                $dataUpdate['is_approve'] = 1;
            }
            $result = $this->timeOffDays->edit($dataUpdate, $id);
            if ($result) {
                $param['time_off_days_id'] = $id;
                $param['time_off_days_action'] = 'update';
                $param['time_off_days_title'] = 'Duyệt đơn phép';
                $param['time_off_days_content'] = 'Chấp nhận đơn phép';
                $param['created_by'] = Auth()->id();
                $param['created_at'] = Carbon::now();
                $this->timeOffDaysLog->add($param);
                
                if($dataUpdate['is_approve'] == 1){
                    $lstShiftDaysOff = $this->timeOffDaysShifts->getListsByDaysOff($id);
                    if(count($lstShiftDaysOff) > 0){
                        foreach ($lstShiftDaysOff as $obj) {
                            $this->timeOffDaysShifts->edit(['is_approve' => 1], $obj['time_off_days_shift_id']);
                            switch ($obj['time_off_type_code']) {
                                case '017':
                                    //Xin đi trễ
                                    $this->timeWorkingStaff->edit(
                                        ['is_approve_late' => 1, 'approve_late_by' =>  $authId], $obj['time_working_staff_id']
                                    );
                                    break;
                                case '018':
                                    //Xin về sớm
                                    $this->timeWorkingStaff->edit(
                                        ['is_approve_soon' => 1 , 'approve_soon_by' => $authId], $obj['time_working_staff_id']
                                    );
                                    break;
                                default:
                                    $this->timeWorkingStaff->edit(
                                        ['is_deducted' => $obj['is_deducted']], $obj['time_working_staff_id']
                                    );
                                    break;
                            }
                        }
                    }
                    App\Jobs\FunctionSendNotify::dispatch([
                        'type' => SEND_NOTIFY_STAFF,
                        'key' => 'time_off_days_approved', //Key nào mình muốn gửi thì config
                        'customer_id' => null, //Này ko có thì để rỗng
                        'object_id' => $id, //Đối tượng ăn theo key
                        'branch_id' => Auth()->user()->branch_id,
                        'tenant_id' => session()->get('idTenant'),
                        'staff_id' => $data['staff_id'],
                        'model' => json_encode(["time_off_days_id" => $id, "is_personal" => 1]),
                        'content' => 'Đơn xin ' . $data['time_off_type_name']. ' từ ngày: ' . $data['time_off_days_start']. ' đến ngày: ' . $data['time_off_days_end'] . ' đã được phê duyệt'
                    ]);
                }
            }
            return  $result ?? [];
        } catch (\Exception $ex) {
            return [];
        }
    }

    protected function unApproveAction($id, $note)
    {
        try {
            $data = $this->timeOffDays->detail($id);
            $authId = Auth()->id();
            $data['staff_id_approve_level1'] = null;
            $dataUpdate['is_approve'] = null;
           
            if(isset($data) && $data['direct_management_approve'] == 1){
              
                $infoApproveLevel1 = $this->staffs->getDetailApproveLevel1($data['department_id']);
                if(isset($infoApproveLevel1)){
                    $data['staff_id_approve_level1'] = $infoApproveLevel1['staff_id'];
                    if($authId == $infoApproveLevel1['staff_id'] ){
                        $dataUpdate['staff_id_level1'] = $authId;
                        $dataUpdate['is_approve_level1'] = 0;
                        $data['is_approve_level1'] = 0;
                        $data['staff_id_level1'] = $authId;
                        
                    }
                }
            }
            if(isset($data['staff_id_approve_level2']) && in_array($authId, json_decode($data['staff_id_approve_level2']))){
                $dataUpdate['staff_id_level2'] = $authId;
                $dataUpdate['is_approve_level2'] = 0;
                $data['is_approve_level2'] = 0;
                $data['staff_id_level2'] = $authId;
            }
            if(isset($data['staff_id_approve_level3']) && in_array($authId, json_decode($data['staff_id_approve_level3']))){
                $dataUpdate['staff_id_level3'] = $authId;
                $dataUpdate['is_approve_level3'] = 0;
                $data['is_approve_level3'] = 0;
                $data['staff_id_level3'] = $authId;
            }
          
            $dataUpdate['is_approve'] = 0;
            $result = $this->timeOffDays->edit($dataUpdate, $id);
            if ($result) {
                $param['time_off_days_id'] = $id;
                $param['time_off_days_action'] = 'update';
                $param['time_off_days_title'] = 'Duyệt đơn phép';
                $param['time_off_days_content'] = 'Không chấp nhận đơn phép' . $note != null ? '. Lý do: ' . $note : '';
                $param['created_by'] = Auth()->id();
                $param['created_at'] = Carbon::now();
                $this->timeOffDaysLog->add($param);
                
                if($dataUpdate['is_approve'] == 0){
                    $lstShiftDaysOff = $this->timeOffDaysShifts->getListsByDaysOff($id);
                    if(count($lstShiftDaysOff) > 0){
                        foreach ($lstShiftDaysOff as $obj) {
                            $this->timeOffDaysShifts->edit(['is_approve' => 0], $obj['time_off_days_shift_id']);
                            switch ($obj['time_off_type_code']) {
                                case '017':
                                    //Xin đi trễ
                                    $this->timeWorkingStaff->edit(
                                        ['is_approve_late' => 0, 'approve_late_by' =>  $authId], $obj['time_working_staff_id']
                                    );
                                    break;
                                case '018':
                                    //Xin về sớm
                                    $this->timeWorkingStaff->edit(
                                        ['is_approve_soon' => 0 , 'approve_soon_by' => $authId], $obj['time_working_staff_id']
                                    );
                                    break;
                                default:
                                    // $this->timeWorkingStaff->edit(
                                    //     ['is_deducted' => 1], $obj['time_working_staff_id']
                                    // );
                                    break;
                            }
                        }
                    }
                    App\Jobs\FunctionSendNotify::dispatch([
                        'type' => SEND_NOTIFY_STAFF,
                        'key' => 'time_off_days_not_approved', //Key nào mình muốn gửi thì config
                        'customer_id' => null, //Này ko có thì để rỗng
                        'object_id' => $id, //Đối tượng ăn theo key
                        'branch_id' => Auth()->user()->branch_id,
                        'tenant_id' => session()->get('idTenant'),
                        'staff_id' => $data['staff_id'],
                        'model' => json_encode(["time_off_days_id" => $id, "is_personal" => 0]),
                        'content' => 'Đơn xin ' . $data['time_off_type_name']. ' từ ngày: ' . $data['time_off_days_start']. ' đến ngày: ' . $data['time_off_days_end'] . ' đã không được chấp nhận'
                    ]);
                }
            }
            
            return  $result ?? [];
        } catch (\Exception $ex) {
            return  [];
        }
    }

    /**
     * Duyệt ngày phép
     * @return Response
     */
    protected function isApproveAction($data)
    {

        if ($data['is_approve_level1'] == 1 && $data['staff_id_level2'] == null && $data['staff_id_level3'] == null) {
            return 1;
        }
        if ($data['is_approve_level2'] == 1 && $data['staff_id_level3'] == null) {
            return 1;
        }
        if ($data['is_approve_level3'] == 1) {
            return 1;
        }
        return null;
    }

    /**
     * Check ngày phép hợp lệ
     * @return Response
     */
    protected function checkDateApproveAction($params)
    {
        $timeOffTypeDetail = $this->timeOffType->detail($params['time_off_type_id']);
        $timeOffHolidaysNumber = $timeOffTypeDetail['time_off_holidays_number'];

        if ($timeOffHolidaysNumber != 0) {
            $dayStart = Carbon::createFromFormat("d/m/Y", $params['time_off_days_start'])->format("Y-m-d");
            $dayEnd = Carbon::createFromFormat("d/m/Y", $params['time_off_days_end'])->format("Y-m-d");

            if ($dayEnd - $dayStart >= $timeOffHolidaysNumber) {
                return false;
            } else {
                return true;
            }
        } else {
            return true;
        }
    }

    public function updateTimeWorkingStaff($data)
    {
        switch ($data['time_off_type_code']) {
            case '017':
                # Xin đi trễ
                $objTime = $this->timeOffDaysTime->getDetail($data['time_off_days_time']);
                $timeLate = 0;
                if (isset($objTime)) {
                    $timeLate = $objTime['time_off_days_time_value'];
                }
                $id = $this->timeWorkingStaff->edit($data['time_working_staff_id'], ['time_late_apply_work_late' => $timeLate]);
                break;
            case '018':
                # Xin về sớm
                $objTime = $this->timeOffDaysTime->getDetail($data['time_off_days_time']);
                $timeSoon = 0;
                if (isset($objTime)) {
                    $timeSoon = $objTime['time_off_days_time_value'];
                }
                $id = $this->timeWorkingStaff->edit($data['time_working_staff_id'], ['time_late_apply_work_soon' => $timeSoon]);
                break;
            case '010':
                # Xin nghĩ không lương
                $id = $this->timeWorkingStaff->edit($data['time_working_staff_id'], ['is_time_off_days' => 1, 'is_deducted' => 1]);
                break;
            default:
                # Xin nghĩ có lương
                $id = $this->timeWorkingStaff->edit($data['time_working_staff_id'], ['is_time_off_days' => 1, 'is_deducted' => 0]);

                break;
        }
    }
}
