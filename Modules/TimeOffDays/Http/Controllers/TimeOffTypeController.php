<?php

namespace Modules\TimeOffDays\Http\Controllers;

use Illuminate\Http\Request;

use Modules\TimeOffDays\Repositories\TimeOffType\TimeOffTypeRepoException;
use Modules\TimeOffDays\Repositories\TimeOffType\TimeOffTypeRepoInterface;

class TimeOffTypeController extends Controller
{
    protected $timeOffType;

    public function __construct(
        TimeOffTypeRepoInterface $timeOffType ) {

        $this->timeOffType = $timeOffType;
    }
     
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function list(Request $request)
    {

        try {

            $result = $this->timeOffType->getLists($request->all());
            $data = [];
            
            foreach($result as $key => $item){
                $data[$key]['parent'] = $item;
                $data[$key]['child'] = $this->timeOffType->getListsChild($item['time_off_type_id']);
            }

            return $this->responseJson(CODE_SUCCESS,  __('Xử lý thành công'), $data);
            
        } catch (\Exception $ex) {

            return $this->responseJson(CODE_ERROR, $ex->getMessage());

        }
    }

}
