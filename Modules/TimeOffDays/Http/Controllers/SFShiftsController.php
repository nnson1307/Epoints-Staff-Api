<?php

namespace Modules\TimeOffDays\Http\Controllers;

use Illuminate\Http\Request;

use Modules\TimeOffDays\Repositories\SFShifts\SFShiftsRepoException;
use Modules\TimeOffDays\Repositories\SFShifts\SFShiftsRepoInterface;
use Carbon\Carbon;
use Illuminate\Support\Str;
class SFShiftsController extends Controller
{
    protected $repo;

    public function __construct(
        SFShiftsRepoInterface $repo ) {

        $this->repo = $repo;
    }
     
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function list(Request $request)
    {
        try {
           if(isset($request->working_day_start)){
                $start = Carbon::createFromFormat('Y-m-d',$request->working_day_start)->format('Y-m-d');
                $now =  Carbon::now()->format('Y-m-d');
                if($start < $now){
                    return $this->responseJson(CODE_ERROR, __('Ngày xin phép phải lớn hơn ngày hoặc bằng ngày hiện tại'));
                }
                $result = $this->repo->getLists($request->all());
                $data = [];
                if($result){
                    foreach($result as $key => $item){
                        $workingDay = Carbon::createFromFormat('Y-m-d',$item['working_day'])->format('d/m');
                        $data[$key]['time_working_staff_id'] = $item['time_working_staff_id'];
                        $data[$key]['shift_name'] = Str::replaceLast('Ca', 'Ca nghỉ', $item['shift_name']) .' - ' .$workingDay;
        
                    }
                }
                return $this->responseJson(CODE_SUCCESS, __('Xử lý thành công'), $data);
           }else {
                return $this->responseJson(CODE_ERROR, __('Lỗi dữ liệu'));
           }
           
            
        } catch (\Exception $ex) {
            return $this->responseJson(CODE_ERROR, $ex->getMessage());
        }
    }

}
