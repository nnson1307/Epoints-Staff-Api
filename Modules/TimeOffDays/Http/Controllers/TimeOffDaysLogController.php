<?php

namespace Modules\TimeOffDays\Http\Controllers;

use Illuminate\Http\Request;

use Modules\TimeOffDays\Repositories\TimeOffDaysLog\TimeOffDaysLogRepoException;
use Modules\TimeOffDays\Repositories\TimeOffDaysLog\TimeOffDaysLogRepoInterface;

class TimeOffDaysLogController extends Controller
{
    protected $repo;

    public function __construct(
        TimeOffDaysLogRepoInterface $repo ) {

        $this->repo = $repo;
    }
     
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function list(Request $request)
    {

        try {

            $result = $this->repo->getLists($request->all());

            return $this->responseJson(CODE_SUCCESS,  __('Xử lý thành công'), $result);
            
        } catch (\Exception $ex) {

            return $this->responseJson(CODE_ERROR, $ex->getMessage());

        }
    }

}
