<?php

namespace Modules\Survey\Repositories\SurveyProcess\QuestionType;


use Modules\Survey\Models\SurveyTable;
use Modules\Survey\Models\SurveyQuestionTable;
use Modules\Survey\Models\SurveyConfigPointTable;
use Modules\Survey\Models\SurveyAnswerQuestionTable;
use Modules\Survey\Models\SurveyQuestionChoiceTable;
use Modules\Survey\Repositories\SurveyProcess\SurveyProcessException;

/**
 * Class TypeSingleChoice
 * RET-8632
 * @package Modules\Survey\Repositories\SurveyProcess\QuestionType
 * @author DaiDP
 * @since Feb, 2022
 */
class TypeSingleChoice extends QuestionTypeAbstract
{
    /**
     * Lấy nội dung câu hỏi khảo sát
     * @param SurveyQuestionTable $questionInfo
     * @param null $idAnswer ID của session trả lời
     * @return mixed
     */
    public function getQuestionDetail(SurveyQuestionTable $questionInfo, $idAnswer = null)
    {
        // TODO: Lấy danh sách lựa chọn
        $mChoice = app()->get(SurveyQuestionChoiceTable::class);
        $listChoice = $mChoice->getQuestionChoice($questionInfo->survey_question_id);
        // cấu hình tính điểm //
        $mSurveyConfigPoint = app()->get(SurveyConfigPointTable::class);
        // lấy cấu hình tính điểm
        // Lấy thông tin trả lời nếu có
        $this->fillAnswer($listChoice, $questionInfo->survey_question_id, $idAnswer);
        $questionInfo->result_answer = null;
        // ráp thông tin câu lựa chọn vào
        $questionInfo->list_choice = $listChoice;
        $questionInfo->list_answer_success = null;
        $questionInfo->count_point = intval($questionInfo->count_point);
        // lấy thông tin danh sách câu trả lời và lọc ra đáp án của câu trả lời có tính điểm
        if ($questionInfo->count_point == SurveyTable::IS_COUNT_POINT) {
            // kiểm tra câu hỏi có bỏ lỡ
            $checkQuestionSkip = $listChoice->every(function ($item, $key) {
                return  $item->is_selected != SurveyQuestionChoiceTable::ANSWER_SUCCESS_QUESTION;
            });
            if (!$checkQuestionSkip) {
                $resultAnswer = $this->correctAnswer($listChoice);
                $questionInfo->result_answer = $resultAnswer['result_answer'];
                $questionInfo->list_answer_success = $resultAnswer['list_answer_success'];
            }
            // lấy cấu hình và xem có hiển thị điểm 
            $configPoint = $mSurveyConfigPoint->getConfigPoint($questionInfo->survey_id);
            if ($configPoint->show_point != $mSurveyConfigPoint::SHOW_POINT) {
                $questionInfo->value_point = 0;
            }
        }
        return $questionInfo;
    }

    /**
     * Xử lý đáp án đúng sai khi khảo sát có tính điểm
     * @param $listChoice
     * @return mixed
     */

    public function correctAnswer($listChoice)
    {
        // kết quả câu trả lời của user
        $isSuccess = true;
        // danh sách câu trả lời đúng 
        $listAnswerSuccess = [];
        // kiểm tra không có đáp án đúng câu hỏi 
        $checkEmptyAnswer = $listChoice->contains(function ($value, $key) {
            return $value->survey_question_choice_value == SurveyQuestionChoiceTable::ANSWER_SUCCESS_QUESTION;
        });
        if (!$checkEmptyAnswer) return [
            'result_answer' => 'wrong',
            'list_answer_success' => null
        ];
        foreach ($listChoice as $item) {
            if (
                $item->survey_question_choice_value == SurveyQuestionChoiceTable::ANSWER_SUCCESS_QUESTION
                &&  $item->is_selected != SurveyQuestionChoiceTable::ANSWER_SUCCESS_QUESTION
            ) {
                $isSuccess = false;
            }
            if ($item->survey_question_choice_value == SurveyQuestionChoiceTable::ANSWER_SUCCESS_QUESTION) {
                $listAnswerSuccess[] = $item;
            }
        }
        if ($isSuccess) $listAnswerSuccess = null;
        $rs = [
            'result_answer' => $isSuccess ? 'success' : 'wrong',
            'list_answer_success' => $listAnswerSuccess,
        ];
        return $rs;
    }

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
        // Lấy câu trả lời
        $idChoice = $answer['submit_answer'][0]['survey_question_choice_id'] ?? null;

        // Kiểm tra bắt buộc hay không
        if (is_null($idChoice)) {
            if ($questionDetail->is_required) {
                throw new SurveyProcessException(SurveyProcessException::ANSWER_REQUIRED);
            } else {
                return;
            }
        }

        // Lưu đáp án
        $mAnswerQuestion = app()->get(SurveyAnswerQuestionTable::class);

        $mAnswerQuestion->saveSingleChoice(
            $questionDetail->survey_id,
            $idAnswerSession,
            $answer['branch_id'],
            $answer['survey_question_id'],
            $idChoice
        );
    }


    /**
     * Bổ sung thêm câu trả lời của user
     * @param $listChoice
     * @param $idQuestion
     * @param $idAnswer
     */
    protected function fillAnswer(&$listChoice, $idQuestion, $idAnswer)
    {
        // Lấy câu trả lời của outlet (nếu có), Detail có check outlet rồi, nên query câu trả lời không cần check lại
        $mAnswerQuestion = app()->get(SurveyAnswerQuestionTable::class);
        $answer = $mAnswerQuestion->getMultiChoiceAnswer($idQuestion, $idAnswer);

        // Đánh dấu lựa chọn của user
        foreach ($listChoice as &$item) {
            $item->is_selected = intval(in_array($item->survey_question_choice_id, $answer));
            // đanh dấu đáp án đúng của câu hỏi 
            $item->survey_question_choice_value =  $item->survey_question_choice_value == SurveyQuestionChoiceTable::ANSWER_SUCCESS_QUESTION ? intval($item->survey_question_choice_value) : 0;
        }
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
        $answerQuestionSucces = $mSurveyQuestion->getAnswerSuccessSigleChoice($itemAnswerQuestion->survey_question_id);

        if (!$answerQuestionSucces) {
            $isSuccess = 'wrong';
        }
        if (
            $answerQuestionSucces &&
            $answerQuestionSucces->survey_question_choice_id == $itemAnswerQuestion->survey_question_choice_id
        ) $isSuccess = 'success';
        // trả về kết quả trả lời và kèm theo id câu hỏi 
        $rs = [
            'result_answer' => $isSuccess,
            'question_id' => $itemAnswerQuestion->survey_question_id
        ];
        return $rs;
    }
}
