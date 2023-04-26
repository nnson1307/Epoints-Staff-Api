<?php

namespace Modules\Survey\Repositories\SurveyProcess\QuestionType;


use Modules\Survey\Models\SurveyTable;
use Modules\Survey\Models\SurveyQuestionTable;
use Modules\Survey\Models\SurveyConfigPointTable;
use Modules\Survey\Models\SurveyAnswerQuestionTable;
use Modules\Survey\Models\SurveyQuestionChoiceTable;
use Modules\Survey\Repositories\SurveyProcess\SurveyProcessException;
use Modules\Survey\Repositories\SurveyProcess\TextValid\TextValidFactory;

/**
 * Class TypeText (Text Entry)
 * RET-8819
 * @package Modules\Survey\Repositories\SurveyProcess\QuestionType
 * @author DaiDP
 * @since Feb, 2022
 */
class TypeText extends QuestionTypeAbstract
{
    /**
     * Lấy nội dung câu hỏi khảo sát
     * RET-8819
     * @param SurveyQuestionTable $questionInfo
     * @param null $idAnswer ID của session trả lời
     * @return mixed
     */
    public function getQuestionDetail(SurveyQuestionTable $questionInfo, $idAnswer = null)
    {
        // Lấy thông tin trả lời nếu có
        $questionInfo->answer_value = $this->getAnswer($questionInfo->survey_question_id, $idAnswer);
        $questionInfo->count_point = intval($questionInfo->count_point);
        // đán áp của câu trả hỏi tính tiểm 
        if ($questionInfo->count_point == SurveyTable::IS_COUNT_POINT) {
            $questionInfo->result_answer =  $this->correctAnswer($questionInfo->answer_value);
            $questionInfo->list_answer_success = null;
            // kiểm tra cấu hình tính điểm có cho tính điểm 
            $mSurveyConfigPoint = app()->get(SurveyConfigPointTable::class);
            $configPoint = $mSurveyConfigPoint->getConfigPoint($questionInfo->survey_id);
            if (!$configPoint || $configPoint->count_point_text != SurveyConfigPointTable::IS_COUNT_POINT_TEXT || $configPoint->show_point != $mSurveyConfigPoint::SHOW_POINT) {
                $questionInfo->value_point = 0;
            }
        }
        return $questionInfo;
    }

    /**
     * Kiểm tra đán đung,sai
     * @param $answer_value
     * @return array
     */

    public function correctAnswer($answer_value)
    {
        $result_answer = 'wrong';
        if ($answer_value) $result_answer = 'success';
        return $result_answer;
    }

    /**
     * Xử lý lưu trả lời của câu hỏi
     * RET-8819
     * @param $idAnswerSession
     * @param $questionDetail
     * @param $answer
     * @return mixed
     * @throws SurveyProcessException
     */
    public function saveAnswer($idAnswerSession, $questionDetail, $answer)
    {
        // Lấy câu trả lời
        $text = $answer['submit_answer'][0]['text'] ?? null;

        // Validate input
        if (!$this->isValid($questionDetail, $text)) {
            throw new SurveyProcessException(SurveyProcessException::ANSWER_INVALID);
        }

        // Lưu đáp án
        $mAnswerQuestion = app()->get(SurveyAnswerQuestionTable::class);
        $mAnswerQuestion->saveSingleChoice(
            $questionDetail->survey_id,
            $idAnswerSession,
            $answer['branch_id'],
            $answer['survey_question_id'],
            null,
            $text
        );
    }

    /**
     * Lấy giá trị trả lời của câu hỏi
     * RET-8819
     * @param $idQuestion
     * @param $idAnswer
     * @return null|string
     */
    protected function getAnswer($idQuestion, $idAnswer)
    {
        if (!$idAnswer) {
            return null;
        }

        // Lấy câu trả lời của outlet (nếu có), Detail có check outlet rồi, nên query câu trả lời không cần check lại
        $mAnswerQuestion = app()->get(SurveyAnswerQuestionTable::class);
        return $mAnswerQuestion->getTextAnswer($idQuestion, $idAnswer);
    }

    /**
     * Xử lý validate giá trị submit lên
     * RET-8819
     * @param $questionDetail
     * @param $text
     * @return bool
     */
    protected function isValid($questionDetail, $text)
    {
        // Kiểm tra require
        if ($questionDetail->is_required && empty($text)) {
            throw new SurveyProcessException(SurveyProcessException::ANSWER_REQUIRED);
        }

        $type = $questionDetail->survey_question_config->valid_type ?? null;
        $oProcessor = TextValidFactory::getInstance($type);
        return $oProcessor->isValid($text, (array) ($questionDetail->survey_question_config->valid_option ?? []));
    }

    /**
     * kiểm tra câu trả lời của user 
     * @param object $itemAnswerQuestion
     * @return boolean
     */

    public function checkAnswerUser($itemAnswerQuestion)
    {
        $isSuccess = 'wrong';
        // lấy câu hỏi và kiểm tra bên cấu hình câu hỏi dạng text có đc tính điểm hay không 
        $mSurveyQuestion = app()->get(SurveyQuestionTable::class);
        $isCountPointText = $mSurveyQuestion->getConfigQuestionText($itemAnswerQuestion->survey_question_id);
        if ($isCountPointText && $itemAnswerQuestion->answer_value) $isSuccess = 'success';
        // trả về kết quả trả lời và kèm theo id câu hỏi
        $rs = [
            'result_answer' => $isSuccess,
            'question_id' => $itemAnswerQuestion->survey_question_id
        ];
        return $rs;
    }
}
