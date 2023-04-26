<?php

namespace Modules\Survey\Http\Controllers;

use Modules\Survey\Http\Requests\SurveyProcess\StartRequest;
use Modules\Survey\Http\Requests\SurveyProcess\SubmitRequest;
use Modules\Survey\Repositories\ListData\ListDataSurveyException;
use Modules\Survey\Repositories\SurveyProcess\SurveyProcessException;
use Modules\Survey\Repositories\SurveyProcess\SurveyProcessInterface;

/**
 * Class SurveyProcessController
 * RET-8632
 * @package Modules\Survey\Http\Controllers
 * @author DaiDP
 * @since Feb, 2022
 */
class SurveyProcessController extends Controller
{
    protected $survey;


    /**
     * SurveyProcessController constructor
     * @param SurveyProcessInterface $survey
     */
    public function __construct(SurveyProcessInterface $survey)
    {
        $this->survey = $survey;
    }

    /**
     * Bắt đầu khảo sát
     * RET-8632
     * @param StartRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function startAction(StartRequest $request)
    {
        try {
            $branchId = $request->header('branch-id');
            if(!isset($request->all()["branch_id"]) && isset($branchId)){
                $request->merge(["branch_id" => $branchId]);
            }
            $request->branch_id = null;
            $data = $this->survey->start($request->survey_id, $request->branch_id, $request->question_no);

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (SurveyProcessException $ex) {
            return $this->responseJson($ex->getCode(), $ex->getMessage(), $ex->getErrorData());
        } catch (ListDataSurveyException $ex) {
            return $this->responseJson($ex->getCode(), $ex->getMessage(), $ex->getErrorData());
        }
    }

    /**
     * Submit khảo sát
     * RET-8632
     * @param SubmitRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function submitAction(SubmitRequest $request)
    {
        try {
            $branchId = $request->header('branch-id');
            if(!isset($request->all()["branch_id"]) && isset($branchId)){
                $request->merge(["branch_id" => $branchId]);
            }
            $request->branch_id = null;
            $data = $this->survey->submit($request->all());

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (SurveyProcessException $ex) {
            return $this->responseJson($ex->getCode(), $ex->getMessage(), $ex->getErrorData());
        }
    }

    /**
     * Hoàn thành khảo sát
     * RET-1831
     * @param SubmitRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function finishAction(SubmitRequest $request)
    {
        try {
            $branchId = $request->header('branch-id');
            if(!isset($request->all()["branch_id"]) && isset($branchId)){
                $request->merge(["branch_id" => $branchId]);
            }

            if (!isset($request->branch_id)){
                $request->branch_id = null;
            }
            $data = $this->survey->finish($request->all());
            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (SurveyProcessException $ex) {
            return $this->responseJson($ex->getCode(), $ex->getMessage(), $ex->getErrorData());
        } catch (ListDataSurveyException $ex) {
            return $this->responseJson($ex->getCode(), $ex->getMessage(), $ex->getErrorData());
        }
    }
}
