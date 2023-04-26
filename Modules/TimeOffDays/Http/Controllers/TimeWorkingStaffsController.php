<?php

namespace Modules\TimeOffDays\Http\Controllers;

use Illuminate\Http\Request;

use Modules\TimeOffDays\Repositories\TimeWorkingStaffs\TimeWorkingStaffsRepoException;
use Modules\TimeOffDays\Repositories\TimeWorkingStaffs\TimeWorkingStaffsRepoInterface;

class TimeWorkingStaffsController extends Controller
{
    protected $table;

    public function __construct(
        TimeWorkingStaffsRepoInterface $table ) {

        $this->table = $table;
    }
     
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function list(Request $request)
    {

        try {
          
            $data = $this->table->getLists($request->all());
            
            return $this->responseJson(CODE_SUCCESS, __('Xử lý thành công'), $data);
            
        } catch (\Exception $ex) {
            
            return $this->responseJson(CODE_ERROR, $ex->getMessage());

        }
    }

}
