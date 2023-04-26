<?php
namespace Modules\Survey\Repositories\ListData\Support;

use Modules\Survey\Models\SurveyAnswerTable;
use Modules\Survey\Repositories\ListData\ListDataRepoException;

/**
 * Trait CheckTimesPlay
 * @package Modules\Survey\Repositories\ListData\Support
 * @author DaiDP
 * @since Feb, 2022
 */
trait CheckTimesPlay
{
    /**
     * Kiểm tra lần chơi của chương trình
     *
     * @param $surveyInfo
     * @param $idBranch
     * @param $idUser
     * @throws ListDataRepoException
     */
    protected function checkTimesPlay(&$surveyInfo, $idBranch, $idUser)
    {
        $mLogs = app()->get(SurveyAnswerTable::class);
        $surveyInfo->num_completed_times = 0;
        // Kiểm tra giới hạn của outlet theo ngày
        if ($surveyInfo->branch_max_times_per_day > 0) {
            $times = $mLogs->countOutletInDateTimes($surveyInfo->survey_id, $idBranch);
            $surveyInfo->num_completed_times = $times;

            if ($times >= intval($surveyInfo->branch_max_times_per_day)) {
                throw new ListDataRepoException(ListDataRepoException::SURVEY_OUT_OF_QUOTA);
            }
        }

        // Kiểm tra giới hạn của outlet tối đa
        if ($surveyInfo->branch_max_times > 0) {
            $times = $mLogs->countOutletTimes($surveyInfo->survey_id, $idBranch);
            // Lưu số lần thực hiện
            if ($surveyInfo->branch_max_times_per_day == 0) {
                $surveyInfo->num_completed_times = $times;
            }
            
            if ($times >= intval($surveyInfo->branch_max_times)) {
                throw new ListDataRepoException(ListDataRepoException::SURVEY_OUT_OF_QUOTA);
            }
        }

        // Kiểm tra giới hạn số lần thực hiện của toàn survey
        $times = $mLogs->countSurveyTimes($surveyInfo->survey_id, $idUser);
        if ($surveyInfo->max_times > 0 && $times >= intval($surveyInfo->max_times)) {
            throw new ListDataRepoException(ListDataRepoException::SURVEY_OUT_OF_QUOTA);
        }

        // Số lần thực hiện
        if ($surveyInfo->num_completed_times == 0) {
            $surveyInfo->num_completed_times = $times;
        }
    }
}