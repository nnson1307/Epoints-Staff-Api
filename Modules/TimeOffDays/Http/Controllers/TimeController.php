<?php

namespace Modules\TimeOffDays\Http\Controllers;

use Illuminate\Http\Request;

use Carbon\Carbon;
use Modules\TimeOffDays\Repositories\TimeOffDaysTime\TimeOffDaysTimeRepoInterface;
class TimeController extends Controller
{
 
    protected $timeOffDaysTime;
    public function __construct(TimeOffDaysTimeRepoInterface $timeOffDaysTime) {

        $this->timeOffDaysTime = $timeOffDaysTime;
    }
     
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function list(Request $request)
    {

        try {
            
            // $data = array(
            //     [
            //         'time_id' => 1, 
            //         'time_name' => '30 Phút', 
            //     ],
            //     [
            //         'time_id' => 2, 
            //         'time_name' => '60 Phút', 

            //     ],
            //     [
            //         'time_id' => 3, 
            //         'time_name' => '90 Phút', 
            //     ]
                
            // );
            $data = $this->timeOffDaysTime->getOption();
     
            return $this->responseJson(CODE_SUCCESS,  __('Xử lý thành công'), $data);
            
        } catch (\Exception $ex) {

            return $this->responseJson(CODE_ERROR, $ex->getMessage());

        }
    }

}
