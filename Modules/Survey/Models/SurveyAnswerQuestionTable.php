<?php

namespace Modules\Survey\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Modules\Survey\Repositories\SurveyProcess\QuestionType\QuestionTypeFactory;

/**
 * Class SurveyAnswerQuestionTable
 * @package Modules\Survey\Models
 * @author DaiDP
 * @since Feb, 2022
 */
class SurveyAnswerQuestionTable extends Model
{

    protected $table = 'survey_answer_question';
    protected $primaryKey = 'answer_question_id';

    protected $fillable = [
        'survey_id', 'survey_answer_id', 'branch_id', 'survey_question_id', 'survey_question_choice_id', 'answer_value'
    ];

    /**
     * Lấy câu trả lời của câu hỏi
     * RET-8632
     * @param $idQuestion
     * @param $idAnswer
     * @return mixed
     */
    public function getMultiChoiceAnswer($idQuestion, $idAnswer)
    {
        return $this->select(
            'survey_question_choice_id'
        )
            ->where('survey_answer_id', $idAnswer)
            ->where('survey_question_id', $idQuestion)
            ->get()
            ->pluck('survey_question_choice_id')
            ->toArray();
    }

    /**
     * Lấy câu trả lời của loại text entry
     * RET-8819
     * @param $idQuestion
     * @param $idAnswer
     * @return null|string
     */
    public function getTextAnswer($idQuestion, $idAnswer)
    {
        $rs = $this->select(
            'answer_value'
        )
            ->where('survey_answer_id', $idAnswer)
            ->where('survey_question_id', $idQuestion)
            ->first();

        return $rs ? $rs->answer_value : null;
    }

    /**
     * Lưu kết quả trắc nghiệm
     * RET-8632
     * @param $idSurvey
     * @param $idAnswerSession
     * @param $idBranch
     * @param $idQuestion
     * @param $idChoice
     * @param null $value
     * @param $isSuccess
     * @return mixed
     */
    public function saveSingleChoice($idSurvey, $idAnswerSession, $idBranch, $idQuestion, $idChoice, $value = null)
    {
        return self::updateOrCreate(
            [
                'survey_id' => $idSurvey,
                'survey_answer_id' => $idAnswerSession,
                'branch_id' => $idBranch,
                'survey_question_id' => $idQuestion
            ],
            ['survey_question_choice_id' => $idChoice, 'answer_value' => $value]
        );
    }

    /**
     * Xóa đáp án cũ. Dùng cho multi choice
     * RET-8632
     * @param $idSurvey
     * @param $idAnswerSession
     * @param $idOutlet
     * @param $idQuestion
     * @return mixed
     */
    public function clearAnswer($idSurvey, $idAnswerSession, $idBranch, $idQuestion)
    {
        return $this->where('survey_id', $idSurvey)
            ->where('survey_answer_id', $idAnswerSession)
            ->where('branch_id', $idBranch)
            ->where('survey_question_id', $idQuestion)
            ->delete();
    }

    /**
     * Lưu kết quả trắc nghiệm cho multi choice
     * RET-8632
     * @param $idSurvey
     * @param $idAnswerSession
     * @param $idOutlet
     * @param $idQuestion
     * @param $arrChoiceId
     * @return mixed
     */
    public function saveMultiChoice($idSurvey, $idAnswerSession, $idBranch, $idQuestion, array $arrChoiceId)
    {
        // Không có kết quả nào thì khỏi lưu
        if (empty($arrChoiceId)) {
            return;
        }

        $data = [];
        $now = Carbon::now();

        foreach ($arrChoiceId as $idChoice) {
            $data[] = [
                'survey_id' => $idSurvey,
                'survey_answer_id' => $idAnswerSession,
                'branch_id' => $idBranch,
                'survey_question_id' => $idQuestion,
                'survey_question_choice_id' => $idChoice,
                'created_at' => $now
            ];
        }

        return self::insert($data);
    }

    /**
     * Lấy tất cả câu hỏi của phiên trả lời khi update
     * @param int $idAnswerSession
     * @return mixed
     */

    public function getQuestions($idAnswerSession, $filter = [])
    {

        $oSelect = $this->select(
            "{$this->table}.answer_value",
            "{$this->table}.survey_question_choice_id",
            "{$this->table}.survey_question_id",
            "sq.value_point",
            "sq.survey_question_type"
        )
            ->where("{$this->table}.survey_answer_id", $idAnswerSession)
            ->join("survey_question as sq", function ($join) use ($filter) {
                $join->on("sq.survey_question_id", "{$this->table}.survey_question_id");
                if (isset($filler['mutiple_choice'])) {
                    $join->where("sq.survey_question_type", QuestionTypeFactory::MULTI_CHOICE);
                }
            })
            ->get();

        return $oSelect;
    }

    /**
     * Lấy danh sách id câu hỏi mà user đã trả lời 
     * @param $answerId
     * @return array
     */

    public function getListIdQuestion($answerId)
    {

        return $this->where("survey_answer_id", $answerId)
            ->groupBy("survey_question_id")
            ->pluck("survey_question_id")
            ->toArray();
    }
}
