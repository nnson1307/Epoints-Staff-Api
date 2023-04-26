<?php

namespace Modules\Survey\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Modules\Survey\Models\SurveyTemplateNotificationTable;

/**
 * Class SurveyAnswerTable
 * @package Modules\Survey\Models
 * @author DaiDP
 * @since Feb, 2022
 */
class SurveyAnswerTable extends Model
{

    const STATUS_FINISH = 'done';
    const STATUS_IN_PROCESS = 'in-process';
    
    protected $table = 'survey_answer';
    protected $primaryKey = 'survey_answer_id';

    protected $fillable = [
        'branch_id',
        'user_id',
        'survey_id',
        'survey_answer_status',
        'total_questions',
        'num_questions_completed',
        'total_answer_success',
        'total_answer_wrong',
        'total_point',
        'finished_at'
    ];


    /**
     * Đếm số lượng lượt chơi của game
     * => Chỉ tính khảo sát có trạng thái hoàn thành thôi
     * @param $idSurvey
     * @param $idUser
     * @return number
     */
    public function countSurveyTimes($idSurvey, $idUser)
    {
        return $this->where('survey_id', $idSurvey)
            ->where('user_id', $idUser)
            ->where('survey_answer_status', self::STATUS_FINISH)
            ->count();
    }

    /**
     * Đếm số lượng lượt thực hiện của outlet theo ngày
     * => Ngày của khảo sát tính ngày hoàn thành
     * @param $idSurvey
     * @param $idBranch
     * @param $idUser
     * @return number
     */
    public function countOutletInDateTimes($idSurvey, $idBranch)
    {
        return $this->where('survey_id', $idSurvey)
            ->where('branch_id', $idBranch)
            ->whereRaw('finished_at IS NOT NULL and date(finished_at) = curdate()')
            ->where('survey_answer_status', self::STATUS_FINISH)
            ->count();
    }

    /**
     * Đếm số lượng lượt thực hiện của outlet
     * @param $idSurvey
     * @param $idBranch
     * @param $idUser
     * @return number
     */
    public function countOutletTimes($idSurvey, $idBranch)
    {
        return $this->where('survey_id', $idSurvey)
            ->where('branch_id', $idBranch)
            ->where('survey_answer_status', self::STATUS_FINISH)
            ->count();
    }

    /**
     * Lấy danh sách khảo sát chưa hoàn thành. Group theo khảo sát
     *
     * @param $arrSurveyId
     * @param $idBranch
     * @param $idUser
     * @return array
     */
    public function getInprocessList($arrSurveyId, $idBranch, $idUser)
    {
        $rs = $this->select(
            $this->primaryKey,
            'survey_id',
            'total_questions',
            'num_questions_completed'
        )
            ->whereIn('survey_id', $arrSurveyId)
            ->where('branch_id', $idBranch)
            ->where('user_id', $idUser)
            ->where('survey_answer_status', self::STATUS_IN_PROCESS)
            ->get();

        $data = [];
        foreach ($rs as $item) {
            $data[$item->survey_id] = $item;
        }
        return $data;
    }

    /**
     * Cập nhật trạng thái trả lời khảo sát hoàn thành
     * RET-1831
     * @param $idSurvey
     * @param $idAnswerSession
     * @param $accumPoint
     * @param array $listTotalPoint 
     * @return mixed
     */
    public function updateFinish($idSurvey, $idAnswerSession, $accumPoint = null, $listTotalPoint)
    {
        return $this->where($this->primaryKey, $idAnswerSession)
            ->where('survey_id', $idSurvey)
            ->update([
                'survey_answer_status' => self::STATUS_FINISH,
                'accumulation_point' => $accumPoint,
                'finished_at' => Carbon::now(),
                'total_answer_success' => $listTotalPoint['total_answer_success'] ?? null,
                'total_answer_wrong' => $listTotalPoint['total_answer_wrong'] ?? null,
                'total_point' => $listTotalPoint['total_point'] ?? null,
            ]);
    }

    /**
     * Tạo phiên trả lời khảo sát
     * @param $idSurvey
     * @param $idBranch
     * @param $idUser
     * @param $totalQuestion
     * @return mixed
     */
    public function initSession($idSurvey, $idBranch, $idUser, $totalQuestion)
    {
        return self::create([
            'survey_id' => $idSurvey,
            'branch_id' => $idBranch,
            'user_id' => $idUser,
            'total_questions' => $totalQuestion,
            'survey_answer_status' => self::STATUS_IN_PROCESS
        ]);
    }

    /**
     * Cập nhật số lượng câu hỏi đã hoàn thành
     * RET-1833
     * @param $idAnswerSession
     * @return mixed
     */
    public function updateCompleteQuestions($idAnswerSession)
    {
        $oSub = $this->select(\DB::raw('count(DISTINCT(survey_question_id)) as num'))
            ->from('survey_answer_question')
            ->where($this->primaryKey, $idAnswerSession);

        $sql = str_replace_array('?', $oSub->getBindings(), $oSub->toSql());
        return $this->where($this->primaryKey, $idAnswerSession)
            ->update([
                'num_questions_completed' => \DB::raw("({$sql})")
            ]);
    }


    /**
     * Lịch sử khảo sát
     * RET-1765
     * @param $idBranch
     * @param array $filters
     * @return mixed
     */
    public function listHistory($idBranch, array $filters = [])
    {
        $oSelect = $this->select(
            "{$this->table}.{$this->primaryKey}",
            'sv.survey_id',
            'sv.survey_name',
            'sv.survey_code',
            'sv.survey_description',
            'sv.survey_banner',
            \DB::raw("IF(stn.show_point = 1 AND sv.count_point = 1 ,{$this->table}.total_point, null) as total_point"),
            "{$this->table}.accumulation_point",
            "{$this->table}.finished_at"
        )
            ->join('survey as sv', function ($join) use ($idBranch, $filters) {
                $join->on('sv.survey_id', "{$this->table}.survey_id")
                    ->where("{$this->table}.branch_id", $idBranch)
                    ->where("{$this->table}.user_id", $filters['user_id']);
            })
            ->leftJoin("survey_template_notification as stn", function ($join) {
                $join->on("stn.survey_id", "{$this->table}.survey_id")
                    ->where("stn.show_point", SurveyTemplateNotificationTable::SHOW_POINT);
            })
            ->where('survey_answer_status', self::STATUS_FINISH)
            ->orderBy("{$this->primaryKey}", 'DESC');

        // Filter từ ngày
        if (!empty($filters['date_start'])) {
            $oSelect->whereDate("{$this->table}.finished_at", '>=', $filters['date_start']);
        }

        // Filter đến ngày
        if (!empty($filters['date_end'])) {
            $oSelect->whereDate("{$this->table}.finished_at", '<=', $filters['date_end']);
        }

        $page = (int) ($filters['page'] ?? 1);
        return $oSelect->paginate(PAGING_ITEM_PER_PAGE, $columns = ['*'], $pageName = 'page', $page);
    }

    /**
     * Chi tiết phiên trả lời
     * RET-1765
     * @param $idSurveyAnswer
     * @param $idBranch
     * @return mixed
     */
    public function getDetail($idSurveyAnswer, $idBranch, $idUser)
    {
        return $this->where($this->primaryKey, $idSurveyAnswer)
            ->where('branch_id', $idBranch)
            ->where('user_id', $idUser)
            ->first();
    }
}
