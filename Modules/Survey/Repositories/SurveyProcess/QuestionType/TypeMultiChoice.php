<?php

namespace Modules\Survey\Repositories\SurveyProcess\QuestionType;


use Modules\Survey\Models\SurveyAnswerQuestionTable;
use Modules\Survey\Models\SurveyQuestionChoiceTable;
use Modules\Survey\Models\SurveyQuestionTable;
use Modules\Survey\Repositories\SurveyProcess\SurveyProcessException;

/**
 * Class TypeMultiChoice
 * RET-8632
 * @package Modules\Survey\Repositories\SurveyProcess\QuestionType
 * @author DaiDP
 * @since Feb, 2022
 */
class TypeMultiChoice extends TypeSingleChoice
{
    /**
     * Xử lý lưu trả lời của câu hỏi
     * RET-8632
     * @param $idAnswerSession
     * @param $questionDetail
     * @param $answer
     * @return mixed
     * @throws SurveyProcessException
     */
    public function saveAnswer($idAnswerSession, $questionDetail, $answer)
    {
        // Lấy các đáp án chọn
        $arrSubmitAnswer = $answer['submit_answer'] ?? [];
        $arrChoiceId = [];
        foreach ($arrSubmitAnswer as $item) {
            if (!isset($item['survey_question_choice_id'])) {
                continue;
            }

            $arrChoiceId[] = $item['survey_question_choice_id'];
        }

        // Không có đáp án hợp lệ thì báo lỗi
        if ($questionDetail->is_required && empty($arrChoiceId)) {
            throw new SurveyProcessException(SurveyProcessException::ANSWER_REQUIRED);
        }

        //        $idOutlet = $this->getOutletId();
        $idQuestion = $answer['survey_question_id'];
        $mAnswerQuestion = app()->get(SurveyAnswerQuestionTable::class);

        // Xóa các đáp án cũ
        $mAnswerQuestion->clearAnswer($questionDetail->survey_id, $idAnswerSession, $answer['branch_id'], $idQuestion);
        // Lưu đáp án mới
        $mAnswerQuestion->saveMultiChoice(
            $questionDetail->survey_id,
            $idAnswerSession,
            $answer['branch_id'],
            $idQuestion,
            $arrChoiceId
        );
    }

    /**
     * kiểm tra câu trả lời của user 
     * @param object $itemAnswerQuestion
     * @return boolean
     */

    public function checkAnswerUser($itemAnswerQuestion)
    {
        $isSuccess = 'wrong';
        // lấy câu hỏi dạng trắc nghiệm chọn một đáp án 
        $mSurveyQuestion = app()->get(SurveyQuestionChoiceTable::class);
        // lấy đán án đúng của câu hỏi trắc nghiệm //
        $answerQuestionSucces = $mSurveyQuestion->getAnswerSuccessMutipleChoice($itemAnswerQuestion->survey_question_id);
        // kiểm tra câu trả lời có đúng với đáp án câu hỏi //
        if (count($answerQuestionSucces) <= 0) {
            $isSuccess = 'wrong';
        }
        if (!is_array($itemAnswerQuestion->survey_question_choice_id)) {
            if ($itemAnswerQuestion->survey_question_choice_id == $answerQuestionSucces[0] && count($answerQuestionSucces) == 1) {
                $isSuccess = 'success';
            }
        } else {
            $diff = array_diff($answerQuestionSucces, $itemAnswerQuestion->survey_question_choice_id);
            if (count($answerQuestionSucces) == count($itemAnswerQuestion->survey_question_choice_id) && count($diff) == 0) {
                $isSuccess = 'success';
            }
        }
        // trả về kết quả trả lời và kèm theo id câu hỏi
        $rs = [
            'result_answer' => $isSuccess,
            'question_id' => $itemAnswerQuestion->survey_question_id
        ];

        return $rs;
    }
}
