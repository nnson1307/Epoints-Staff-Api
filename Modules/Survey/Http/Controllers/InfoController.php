<?php

namespace Modules\Survey\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Survey\Models\SurveyQuestionTable;
use Modules\Survey\Http\Requests\Info\DetailRequest;
use Modules\Survey\Repositories\ListData\ListDataRepo;
use Modules\Survey\Repositories\Info\SurveyInfoException;
use Modules\Survey\Repositories\Info\SurveyInfoInterface;

/**
 * Class InfoController
 * RET-1766
 * @package Modules\Survey\Http\Controllers
 * @author DaiDP
 * @since Feb, 2022
 */
class InfoController extends Controller
{

    protected $survey;


    /**
     * InfoController constructor
     * @param SurveyInfoInterface $listData
     */
    public function __construct(SurveyInfoInterface $survey)
    {
        $this->survey = $survey;
    }

    /**
     * Chi tiết khảo sát
     * RET-1766
     * @param DetailRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function detailAction(DetailRequest $request, SurveyQuestionTable $mQuestion)
    {
        try {
            $branchId = $request->header('branch-id');
            if(!isset($request->all()["branch_id"]) && isset($branchId)){
                $request->merge(["branch_id" => $branchId]);
            }
            $idUser = auth()->id();
            if (!isset($request->branch_id)){
                $request->branch_id = null;
            }
            $data = $this->survey->detail($request->survey_id, $request->branch_id);
            $data->total_questions = $mQuestion->countAnswerQuestionsType($request->survey_id);
            $data->resume_question_no = $mQuestion->getResumeQuestionPos($request->survey_id, $request->branch_id, $idUser, $data->survey_answer_id);
            $data->total_point = intval($data->total_point);
            // lấy tên trạng thái tính điểm của khảo sát //
            $listDataRepo = app()->get(ListDataRepo::class);
            $data->count_point = $listDataRepo->getAvailableCountPointName($data->count_point);
            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (SurveyInfoException $ex) {
            return $this->responseJson(CODE_ERROR, $ex->getMessage(), $ex->getErrorData());
        }
    }
}
