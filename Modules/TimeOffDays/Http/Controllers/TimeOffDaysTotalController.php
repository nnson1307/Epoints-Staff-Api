<?php

namespace Modules\TimeOffDays\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;

use Carbon\Carbon;

class TimeOffDaysTotalController extends Controller
{


    public function __construct(){}
        
    
     
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function create(Request $request)
    {
        try {
            
            $staffs = DB::table('staffs')->get();
            $timeOffType = DB::table('time_off_type')->get();

            foreach($staffs as $itemStaffs){
                foreach($timeOffType as $itemtimeOffType){
                
                    if($itemtimeOffType->time_off_type_code == '001' 
                    || $itemtimeOffType->time_off_type_code == '018'
                    || $itemtimeOffType->time_off_type_code == '017' )
                    {
                        $timeOffDaysNumber = 0;
                    }
                    DB::table('time_off_days_total')->insert([
                        'time_off_type_id'  => $itemtimeOffType->time_off_type_id,
                        'staff_id'          => $itemStaffs->staff_id,
                        'time_off_days_number' => $timeOffDaysNumber ?? 12,
                        'created_at' => Carbon::now()
                    ]);
    
                    echo('Success!');

                }
            }      
            
        } catch (\Exception $ex) {

            return $this->responseJson(CODE_ERROR, $ex->getMessage());

        }
    }

}
