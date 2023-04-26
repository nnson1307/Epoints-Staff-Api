<?php

namespace Modules\Survey\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Survey\Http\Requests\ListData\HistoryRequest;
use Modules\Survey\Http\Requests\ListData\MissionRequest;
use Modules\Survey\Repositories\Info\SurveyInfoException;
use Modules\Survey\Repositories\ListData\ListDataInterface;
use Modules\Survey\Repositories\ListData\ListDataRepoException;
use Modules\Survey\Http\Requests\ListData\HistoryPreviewRequest;
use Modules\Survey\Repositories\SurveyProcess\SurveyProcessException;

/**
 * Class ListController
 * RET-1762
 * @package Modules\Survey\Http\Controllers
 * @author DaiDP
 * @since Feb, 2022
 */
class ListController extends Controller
{
    protected $listData;


    /**
     * ListController constructor
     * @param ListDataInterface $listData
     */
    public function __construct(ListDataInterface $listData)
    {
        $this->listData = $listData;
    }

    /**
     * Danh sách nhiệm vụ khảo sát
     * RET-1762
     * @param MissionRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function missionAction(MissionRequest $request)
    {
        $branchId = $request->header('branch-id');
        if(!isset($request->all()["branch_id"]) && isset($branchId)){
            $request->merge(["branch_id" => $branchId]);
        }
        $filters = $request->all();
        if (!isset($filters['branch_id'])){
            $filters['branch_id'] = null;
        }
        $data = $this->listData->mission($filters);

        return $this->responseJson(CODE_SUCCESS, null, $data);
    }

    /**
     * Lịch sử khảo sát
     * RET-1765
     * @param HistoryRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function historyAction(HistoryRequest $request)
    {
        $branchId = $request->header('branch-id');
        if(!isset($request->all()["branch_id"]) && isset($branchId)){
            $request->merge(["branch_id" => $branchId]);
        }
        $filters = $request->all();
        if (!isset($filters['branch_id'])){
            $filters['branch_id'] = null;
        }
        $data = $this->listData->history($filters);

        return $this->responseJson(CODE_SUCCESS, null, $data);
    }

    /**
     * Xem lại trả lời khảo sát
     * RET-1765
     * @param HistoryPreviewRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function historyPreviewAction(HistoryPreviewRequest $request)
    {
        try {
            $branchId = $request->header('branch-id');
            if(!isset($request->all()["branch_id"]) && isset($branchId)){
                $request->merge(["branch_id" => $branchId]);
            }

            if (!isset($request->branch_id)){
                $request->branch_id = null;
            }
            $data = $this->listData->historyPreview($request->survey_answer_id, $request->question_no, $request->branch_id);
//            $data = $this->listData->historyPreview($request->survey_answer_id, $request->question_no, null);
            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (SurveyProcessException $ex) {
            return $this->responseJson($ex->getCode(), $ex->getMessage(), $ex->getErrorData());
        } catch (ListDataRepoException $ex) {
            return $this->responseJson($ex->getCode(), $ex->getMessage(), $ex->getErrorData());
        }
    }

    /**
     * Chi tiết khảo sát
     * RET-1766
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function countAction(Request $request)
    {
        try {
            $branchId = $request->header('branch-id');
            if(!isset($request->all()["branch_id"]) && isset($branchId)){
                $request->merge(["branch_id" => $branchId]);
            }
            $filter = $request->all();
            if (!isset($filter['branch_id'])){
                $filter['branch_id'] = null;
            }
            $data = $this->listData->count($filter);
            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (SurveyInfoException $ex) {
            return $this->responseJson(CODE_ERROR, $ex->getMessage(), $ex->getErrorData());
        }
    }
}
