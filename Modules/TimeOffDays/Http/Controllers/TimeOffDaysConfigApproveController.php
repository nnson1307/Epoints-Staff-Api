<?php

namespace Modules\TimeOffDays\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;

use Carbon\Carbon;

class TimeOffDaysConfigApproveController extends Controller
{


    public function __construct(){}
        
    
     
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function create(Request $request)
    {
        try {
            $timeOffType = DB::table('time_off_type')->get();
            $params = $request->all();
            
            foreach($timeOffType as $itemtimeOffType){
                if($params['staff_title_id']){
                    foreach($params['staff_title_id'] as $key => $item){ 
                        DB::table('time_off_days_config_approve')->insert([
                            'time_off_type_id' => $itemtimeOffType->time_off_type_id ?? null,
                            'staff_title_id' => $item ?? null,
                            'time_off_days_config_approve_level' => $params['time_off_days_config_approve_level'][$key] ?? null,
                            'created_at' => Carbon::now()
                        ]);
                        echo('Success!');
                    }
                }
                
            }
        } catch (\Exception $ex) {

            return $this->responseJson(CODE_ERROR, $ex->getMessage());

        }
    }

}
