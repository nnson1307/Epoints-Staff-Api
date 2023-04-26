<?php

namespace Modules\Service\Models;

use Illuminate\Database\Eloquent\Model;

class FeedbackQuestionTable extends Model
{
    protected $table = "feedback_question";
    protected $primaryKey = "feedback_question_id";

    const IS_ACTIVE = 1;

    /**
     * Danh sách câu hỏi
     *
     * @return mixed
     */
    public function getQuestion()
    {
        return $this
            ->select(
                "{$this->table}.feedback_question_id",
//                "feedback_question_type",
                "{$this->table}.feedback_question_title",
//                "feedback_answer.feedback_answer_id",
                "feedback_answer.feedback_answer_value"
            )
            ->leftJoin("feedback_answer","{$this->table}.feedback_question_id", "=", "feedback_answer.feedback_question_id")
            ->where("{$this->table}.feedback_question_active", self::IS_ACTIVE)
            ->get();
    }
}