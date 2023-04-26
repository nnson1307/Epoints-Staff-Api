<?php
namespace Modules\Survey\Repositories\SurveyProcess\QuestionType;


use Modules\Survey\Models\SurveyAnswerQuestionTable;
use Modules\Survey\Models\SurveyQuestionChoiceTable;
use Modules\Survey\Models\SurveyQuestionTable;
use Modules\Survey\Repositories\SurveyProcess\SurveyProcessException;

/**
 * Class TypePageText (Văn bản tĩnh)
 * RET-8820
 * @package Modules\Survey\Repositories\SurveyProcess\QuestionType
 * @author DaiDP
 * @since Feb, 2022
 */
class TypePageText extends QuestionTypeAbstract
{
    /**
     * Lấy nội dung câu hỏi khảo sát
     * RET-8820
     * @param SurveyQuestionTable $questionInfo
     * @param null $idAnswer ID của session trả lời
     * @return mixed
     */
    public function getQuestionDetail(SurveyQuestionTable $questionInfo, $idAnswer = null)
    {
        return $questionInfo;
    }

    /**
     * Xử lý lưu trả lời của câu hỏi
     * RET-8820
     * @param $idAnswerSession
     * @param $questionDetail
     * @param $answer
     * @return mixed
     */
    public function saveAnswer($idAnswerSession, $questionDetail, $answer)
    {
        $mAnswerQuestion = app()->get(SurveyAnswerQuestionTable::class);
        $mAnswerQuestion->saveSingleChoice(
            $questionDetail->survey_id,
            $idAnswerSession,
            $answer['branch_id'],
            $answer['survey_question_id'],
            null,
            null
        );
    }
}