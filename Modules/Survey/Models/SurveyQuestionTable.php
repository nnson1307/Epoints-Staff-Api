<?php

namespace Modules\Survey\Models;

use Modules\Survey\Models\SurveyTable;
use Illuminate\Database\Eloquent\Model;
use Modules\Survey\Models\SurveyConfigPointTable;
use Modules\Survey\Models\SurveyQuestionChoiceTable;

/**
 * Class SurveyQuestionTable
 * @package Modules\Survey\Models
 * @author DaiDP
 * @since Feb, 2022
 */
class SurveyQuestionTable extends Model
{
    protected $table = 'survey_question';
    protected $primaryKey = 'survey_question_id';

    protected $hidden = [
        'parent_id', 'survey_block_id', 'survey_question_position', 'created_at', 'created_by', 'updated_at', 'updated_by'
    ];

    /**
     * Lấy câu hỏi để thực hiện khảo sát
     * RET-8632
     * @param $idSurvey
     * @param int $questionNo
     * @return mixed
     */
    public function getQuestion($idSurvey, $questionNo = 1)
    {
        return $this->select(
            "{$this->table}.*",
            \DB::RAW("IF(sv.count_point = 1 ,'1' ,'0') as count_point")
        )
            ->where("{$this->table}.survey_id", $idSurvey)
            ->join('survey_block as sb', 'sb.survey_block_id', "{$this->table}.survey_block_id")
            ->join('survey as sv', "sv.survey_id", "{$this->table}.survey_id")
            ->orderBy('survey_block_position', 'ASC')
            ->orderBy('survey_question_position', 'ASC')
            ->paginate(1, $columns = ['*'], $pageName = 'page', $questionNo);
    }

    /**
     * Lấy lịch sử câu hỏi đã trả lời theo cấu hình
     * @param int $idSurvey
     * @param int $questionNo
     * @param array $listQuestionConfig
     * @return mixed
     */

    public function getQuestionAnswerHistory($idSurvey, $questionNo, $listQuestionConfig = []) {
        return $this->select(
            "{$this->table}.*",
            \DB::RAW("IF(sv.count_point = 1 ,'1' ,'0') as count_point")
        )
            ->where("{$this->table}.survey_id", $idSurvey)
            ->whereNotIn("{$this->table}.{$this->primaryKey}", $listQuestionConfig)
            ->join('survey_block as sb', 'sb.survey_block_id', "{$this->table}.survey_block_id")
            ->join('survey as sv', "sv.survey_id", "{$this->table}.survey_id")
            ->orderBy('survey_block_position', 'ASC')
            ->orderBy('survey_question_position', 'ASC')
            ->paginate(1, $columns = ['*'], $pageName = 'page', $questionNo);
    }

    /**
     * Xử lý decode cột cấu hình
     * @param $value
     * @return mixed|null
     */
    public function getSurveyQuestionConfigAttribute($value)
    {
        return $value ? json_decode($value) : new \stdClass();
    }

    /**
     * Lấy vị trí của câu hỏi trong danh sách khảo sát
     * @param $idSurvey
     * @param $idQuestion
     * @return int
     */
    public function getPosQuestion($idSurvey, $idQuestion)
    {
        // Lấy list câu hỏi và sort theo thứ tự
        $oSelect = $this->select(
            "{$this->table}.{$this->primaryKey}"
        )
            ->join('survey_block as sb', 'sb.survey_block_id', "{$this->table}.survey_block_id")
            ->where("{$this->table}.survey_id", $idSurvey)
            ->orderBy('survey_block_position', 'ASC')
            ->orderBy('survey_question_position', 'ASC');

        // Đánh dấu số thứ tự record của list câu hỏi phía trên
        $oSub2 = $this->select(
            "{$this->primaryKey}",
            \DB::raw('@i:=@i+1 as pos')
        )
            ->from(\DB::raw("({$oSelect->toSql()}) as sub1"))
            ->mergeBindings($oSelect->getQuery())
            ->join(\DB::raw('(SELECT @i:=0) as no'), \DB::raw(1), \DB::raw(1));

        // Lấy ra vị trí của câu hiện tại
        $rs = $this->from(\DB::raw("({$oSub2->toSql()}) as sub2"))
            ->mergeBindings($oSub2->getQuery())
            ->whereRaw("{$this->primaryKey} = {$idQuestion}", $idQuestion)
            //                   ->where("{$this->primaryKey}", $idQuestion)
            ->first();

        return $rs ? $rs->pos : 1;
    }

    /**
     * Lấy chi tiết câu hỏi. Dùng để khởi tạo factory
     * @param $idSurvey
     * @param $idQuestion
     * @return mixed
     */
    public function getDetail($idSurvey, $idQuestion)
    {
        return $this->select(
            $this->primaryKey,
            'survey_question_type',
            "{$this->table}.survey_id",
            'is_required',
            'survey_question_config'
        )
            ->where($this->primaryKey, $idQuestion)
            ->where("{$this->table}.survey_id", $idSurvey)
            ->first();
    }

    /**
     * Lấy câu hỏi cuối của khảo sát
     * @param $idSurvey
     * @return mixed
     */
    public function getLastQuestion($idSurvey)
    {
        return $this->select(
            "{$this->table}.{$this->primaryKey}"
        )
            ->join('survey_block as sb', 'sb.survey_block_id', "{$this->table}.survey_block_id")
            ->where("{$this->table}.survey_id", $idSurvey)
            ->orderBy('survey_block_position', 'DESC')
            ->orderBy('survey_question_position', 'DESC')
            ->first();
    }

    /**
     * Lấy vị trí của câu hỏi đầu tiên chưa trả lời
     * RET-1833
     * @param $idSurvey
     * @param $idBranch
     * @param $idUser
     * @param $idAnswerSession
     * @return int
     */
    public function getResumeQuestionPos($idSurvey, $idBranch, $idUser, $idAnswerSession)
    {
        // Lấy list câu hỏi và sort theo thứ tự
        $oSelect = $this->select(
            "{$this->table}.{$this->primaryKey}",
            "{$this->table}.is_combine_question",
            "{$this->table}.survey_id"
        )
            ->join('survey_block as sb', 'sb.survey_block_id', "{$this->table}.survey_block_id")
            ->where("{$this->table}.survey_id", $idSurvey)
            ->orderBy('survey_block_position', 'ASC')
            ->orderBy('survey_question_position', 'ASC');

        // Đánh dấu số thứ tự record của list câu hỏi phía trên
        $oSubPos = $this->select(
            "{$this->primaryKey}",
            'is_combine_question',
            'survey_id',
            \DB::raw('@i:=@i+1 as pos')
        )

            ->from(\DB::raw("({$oSelect->toSql()}) as sub1"))
            ->mergeBindings($oSelect->getQuery())
            ->join(\DB::raw('(SELECT @i:=0) as no'), \DB::raw(1), \DB::raw(1));

        // Lấy ra vị trí của câu hiện tại
        $rs = $this->select('pos')
            ->from(\DB::raw("({$oSubPos->toSql()}) as sub2"))
            ->mergeBindings($oSubPos->getQuery())
            ->join('survey_answer as sa', function ($join) use ($idBranch, $idUser, $idAnswerSession) {
                $join->on('sa.survey_id', 'sub2.survey_id')
                    ->whereRaw("sa.branch_id = {$idBranch}")
                    ->whereRaw("sa.user_id = {$idUser}")
                    ->whereRaw("sa.survey_answer_id = " . ($idAnswerSession ?? "null"));
            })
            ->leftJoin('survey_answer_question as a', function ($join) {
                $join->on('a.survey_answer_id', 'sa.survey_answer_id')
                    ->on("a.{$this->primaryKey}", "sub2.{$this->primaryKey}");
            })
            ->whereRaw('a.answer_question_id is null')
            ->whereRaw('is_combine_question = 0')
            ->orderBy('pos')
            ->first();
        return $rs ? $rs->pos : 1;
    }

    /**
     * Đếm số lượng câu hỏi của survey
     * RET-1833
     * @param $idSurvey
     * @return mixed
     */
    public function countNumberQuestions($idSurvey)
    {
        return $this->where('survey_id', $idSurvey)
            ->count();
    }

    /**
     * Đếm số lượng loại câu hỏi cần câu trả lời của user
     * @param $idSurvey
     * @return mixed
     */
    public function countAnswerQuestionsType($idSurvey)
    {
        return $this->where('survey_id', $idSurvey)
            //                    ->where('is_combine_question', 0)
            ->count();
    }

    /**
     * Lấy vị trí của câu hỏi (Chỉ tính câu hỏi phải subit đáp án) trong danh sách khảo sát
     * @param $idSurvey
     * @param $idQuestion
     * @return int
     */
    public function getPosQuestionAnswer($idSurvey, $idQuestion)
    {
        // Lấy list câu hỏi và sort theo thứ tự
        $oSelect = $this->select(
            "{$this->table}.{$this->primaryKey}"
        )
            ->join('survey_block as sb', 'sb.survey_block_id', "{$this->table}.survey_block_id")
            ->where("{$this->table}.survey_id", $idSurvey)
            //                        ->where("{$this->table}.is_combine_question", 0)
            ->orderBy('survey_block_position', 'ASC')
            ->orderBy('survey_question_position', 'ASC');

        // Đánh dấu số thứ tự record của list câu hỏi phía trên
        $oSub2 = $this->select(
            "{$this->primaryKey}",
            \DB::raw('@i:=@i+1 as pos')
        )
            ->from(\DB::raw("({$oSelect->toSql()}) as sub1"))
            ->mergeBindings($oSelect->getQuery())
            ->join(\DB::raw('(SELECT @i:=0) as no'), \DB::raw(1), \DB::raw(1));

        // Lấy ra vị trí của câu hiện tại
        $rs = $this->from(\DB::raw("({$oSub2->toSql()}) as sub2"))
            ->mergeBindings($oSub2->getQuery())
            //                    ->where("{$this->primaryKey}", $idQuestion)
            ->whereRaw("{$this->primaryKey} = {$idQuestion}")
            ->first();

        return $rs ? $rs->pos : null;
    }

    /**
     * Lấy câu hỏi dạng text và xem cấu hình có cho tính điểm
     * @param $idQuestion
     * @return mixed
     */

    public function getConfigQuestionText($idQuestion)
    {

        $oSelect = $this->where("survey_question_id", $idQuestion)
            ->join("survey_config_point as scp", function ($join) {
                $join->on("scp.survey_id", "{$this->table}.survey_id")
                    ->where("scp.count_point_text", SurveyConfigPointTable::IS_COUNT_POINT_TEXT);
            })
            ->first();
        return $oSelect;
    }

    /**
     * lấy tổng số câu trả lời và tổng điểm khảo sát có tính điểm 
     * @param $idSurvey
     * @return mixed
     */

    public function caclTotalPointAndQuestion($idSurvey)
    {
        return $this->select(
            \DB::raw('COUNT(survey_question_id) as total_question'),
            \DB::raw('SUM(value_point) as total_point'),
        )
            ->where("survey_id", $idSurvey)
            ->first();
    }

    /**
     * lấy danh sách câu hỏi theo điều kiện 
     * @param $listIdQuestion
     * @return mixed
     */

    public function getListIdQuestion($listIdQuestion = [])
    {
        return $this->whereNotIn("{$this->table}.{$this->primaryKey}", $listIdQuestion)
            ->pluck("{$this->primaryKey}")
            ->toArray();
    }
}
