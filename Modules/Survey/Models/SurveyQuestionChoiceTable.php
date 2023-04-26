<?php

namespace Modules\Survey\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class SurveyQuestionChoiceTable
 * @package Modules\Survey\Models
 * @author DaiDP
 * @since Feb, 2022
 */
class SurveyQuestionChoiceTable extends Model
{

    protected $table = 'survey_question_choice';
    protected $primaryKey = 'survey_question_choice_id';
    // đáp án đúng của câu hỏi //
    const ANSWER_SUCCESS_QUESTION = 1;

    /**
     * Lấy các tùy chọn của câu hỏi
     * RET-8632
     * @param $idQuestion
     * @return mixed
     */
    public function getQuestionChoice($idQuestion)
    {
        return $this->select(
            $this->primaryKey,
            'survey_question_choice_title',
            'survey_question_choice_value',
            'survey_question_choice_config'
        )
            ->where('survey_question_id', $idQuestion)
            ->orderBy('survey_question_choice_position', 'ASC')
            ->get();
    }

    /**
     * Xử lý decode cột cấu hình
     * @param $value
     * @return mixed
     */
    public function getSurveyQuestionChoiceConfigAttribute($value)
    {
        return $value ? json_decode($value) : new \stdClass();
    }

    /**
     * Lấy tất cả danh sách câu trả lời đúng của hỏi tính điểm
     * @param $idQuestion
     * @return mixed
     */
    public function getAnswerSuccess($idQuestion)
    {
        return $this->select(
            $this->primaryKey,
            'survey_question_choice_title',
            'survey_question_choice_value',
            'survey_question_choice_config'
        )
            ->where("")
            ->where('survey_question_id', $idQuestion)
            ->orderBy('survey_question_choice_position', 'ASC')
            ->get();
    }

    /**
     * Lấy đán án đúng của câu hỏi trắc nghiệm chọn một đáp án
     * @param $idQuestion
     * @return int
     */

    public function getAnswerSuccessSigleChoice($idQuestion)
    {

        $rs = $this->select("survey_question_choice_id")
            ->where("survey_question_id", $idQuestion)
            ->where("survey_question_choice_value", self::ANSWER_SUCCESS_QUESTION)
            ->first();
        return $rs;
    }

    /**
     * Lấy đán án đúng của câu hỏi trắc nghiệm chọn nhiều đáp án 
     * @param $idQuestion
     * @return int
     */

    public function getAnswerSuccessMutipleChoice($idQuestion)
    {

        $rs = $this->where("survey_question_id", $idQuestion)
            ->where("survey_question_choice_value", self::ANSWER_SUCCESS_QUESTION)
            ->pluck('survey_question_choice_id')
            ->toArray();
        return $rs;
    }
}
