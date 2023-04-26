<?php
namespace Modules\Survey\Repositories\SurveyProcess\QuestionType;

use Modules\Survey\Models\SurveyQuestionTable;

/**
 * Class QuestionTypeAbstract
 * @package Modules\Survey\Repositories\SurveyProcess\QuestionType
 * @author DaiDP
 * @since Feb, 2022
 */
abstract class QuestionTypeAbstract
{

    /**
     * Lấy nội dung câu hỏi khảo sát
     * @param SurveyQuestionTable $questionInfo
     * @param null $idAnswer ID của session trả lời
     * @return mixed
     */
    abstract public function getQuestionDetail(SurveyQuestionTable $questionInfo, $idAnswer = null);

    /**
     * Xử lý lưu trả lời của câu hỏi
     * RET-8819
     * @param $idAnswerSession
     * @param $questionDetail
     * @param $answer
     * @return mixed
     */
    abstract public function saveAnswer($idAnswerSession, $questionDetail, $answer);

}