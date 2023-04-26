<?php

namespace Modules\Survey\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/**
 * Class SurveyTable
 * @package Modules\Survey\Models
 * @author DaiDP
 * @since Feb, 2022
 */
class SurveyTable extends Model
{
    const SURVEY_TARGET = "staff";
    const PERIOD_TYPE_UNLIMITED = 'unlimited';
    const PERIOD_TYPE_LIMITED = 'limited';

    const STATUS_RELEASE = 'R';
    const STATUS_PAUSE = 'P';
    const FREQUENCY_DAILY = 'daily';
    const FREQUENCY_WEEKLY = 'weekly';
    const FREQUENCY_MONTHLY = 'monthly';
    const START = 'success';
    const STOP = 'forbidden';

    const FREQUENCY_MONTH_TYPE_MONTH = 'day_in_month';
    const FREQUENCY_MONTH_TYPE_WEEK = 'day_in_week';
    const LAST_DATE_OF_MONTH = -1;
    const IS_COUNT_POINT = 1;

    protected $table = 'survey';
    protected $primaryKey = 'survey_id';

    protected $hidden = [
        'allow_all_outlet', 'is_active', 'status', 'is_delete', 'created_at', 'created_by', 'updated_at', 'updated_by',
        'period_in_date_type', 'period_in_date_start', 'period_in_date_end', 'max_times', 'outlet_max_times_per_day',
        'outlet_max_times', 'frequency', 'frequency_value', 'is_limit_exec_time', 'exec_time_from', 'exec_time_to',
        'frequency_monthly_type', 'day_in_monthly', 'day_in_week', 'day_in_week_repeat', 'survey_answer_id'
    ];

    /**
     * Danh sách khảo sát của outlet
     * RET-1762
     * 
     * select m.*
     * from (
     *  select *
     *  from dmspro_mys_survey as sv
     *  where sv.is_active = 1
     *  and sv.status = 'R'
     *  and sv.is_delete = 0
     *  and (is_exec_time = 0 or (start_date <= now() and end_date >= now()))
     * ) as m
     * left join dmspro_mys_survey_outlet so on so.outlet_id = 1 and m.survey_id = m.survey_id
     * and (allow_all_outlet = 1 or so.survey_outlet_id is not null)
     * 
     * @param $idOutlet
     * @param $filters
     * @return mixed
     */
    public function getMission($filters = [])
    {
        $idUser = $filters['user_id'] ?? null;
        $target = SurveyTable::SURVEY_TARGET;
        // Query chính, lấy danh sách khảo sát release
        $select = $this->buildMissionQuery($filters);
        $selectQuery = $select->toSql();
        // Lấy khảo sát outlet có thể tham gia
        $mainSelect = $this->select('sub.*')
            ->from(\DB::raw("({$selectQuery}) as sub"))
            ->mergeBindings($select->getQuery())
            ->join('survey_apply_user as sau', function ($join) use ($idUser, $target) {
                $join->on('sau.survey_id', 'sub.survey_id')
                    ->whereRaw("sub.type_user = '{$target}'")
                    ->whereRaw("sau.user_id = {$idUser}");
            });

        if (!empty($filters['date_start']) || !empty($filters['date_end'])) {
            $mainSelect->where(function ($filterCond) use ($filters) {
                $start = $filters['date_start'] ?? null;
                $end = $filters['date_end'] ?? null;
                if (!empty($start) && !empty($end)) { // is_exec_time
                    $filterCond->where(function ($filterCond) use ($filters, $start, $end) {
                        $filterCond->where("sub.is_exec_time", 0)
                            ->whereRaw('(date(sub.created_at) >= ? and date(sub.created_at) <= ?)', [$start, $end]);
                    })
                        ->orWhere(function ($filterCond) use ($filters, $start, $end) {
                            $filterCond->where("sub.is_exec_time", 1)
                                ->whereRaw("(date(sub.start_date) < ? and date(sub.end_date) < ?) or (date(sub.start_date) > ? and date(sub.end_date) > ?) <> true", [$start, $start, $end, $end]);;
                        });
                } else if (empty($end)) {
                    $filterCond->orWhereDate('sub.created_at', '>=', $start);
                } else // empty date_start
                {
                    $filterCond->orWhereDate('sub.created_at', '<=', $end);
                }
            });
        }
        return $mainSelect->orderBy("sub.created_at", "DESC")->get();
    }

    /**
     * Query chính, lấy danh sách khảo sát release
     * RET-1762
     * @param $filters
     * @return mixed
     */
    protected function buildMissionQuery($filters = [])
    {
        return  $this->select(
            "{$this->table}.*",
            DB::raw("count(*) as total_questions"),
            DB::raw("sum(survey_question.value_point) as total_point")
        )
            ->leftJoin("survey_question", function ($join) {
                $join->on("survey_question.survey_id", "=", "{$this->table}.survey_id");
            })
            ->where(function($q) {
                $q->where("{$this->table}.status", self::STATUS_RELEASE);
//                  ->orWhere("{$this->table}.status", self::STATUS_PAUSE);
            })
            ->where("{$this->table}.is_active", 1)
            ->where("{$this->table}.is_delete", 0)
            ->where(function ($cond1) {
                $cond1->where("{$this->table}.is_exec_time", 0) // Không giới hạn thời gian khảo sát
                    ->orWhere(function ($cond2) {
                        // Giới hạn thời gian khảo sát
                        $now = Carbon::now()->format('Y-m-d');
                        $cond2->whereRaw("(date({$this->table}.start_date) <= ? and date({$this->table}.end_date) >= ?)", [$now, $now]);
                    });
            })
            ->groupBy("{$this->table}.survey_id");
    }

    /**
     * Chi tiết khảo sát
     * RET-1766
     * @param $idSurvey
     * @param $idBranch
     * @return mixed
     */
    public function detail($idSurvey, $idBranch, $idUser)
    {
        $select = $this->select(
            "{$this->table}.*",
            'sa.total_questions',
            'survey_answer_id',
            \DB::raw('IF(survey_answer_id IS NULL, 0, 1) as is_in_process'),
            'sa.num_questions_completed',
            \DB::raw('null as accumulation_point'),
            \DB::raw("sum(sq.value_point) as total_point")
        )
            ->where("{$this->table}.{$this->primaryKey}", $idSurvey)
            ->where(function ($q) {
                $q->where("{$this->table}.status", self::STATUS_RELEASE)
                    ->orWhere("{$this->table}.status", self::STATUS_PAUSE);
            })
            ->where("{$this->table}.is_active", 1)
            ->where("{$this->table}.is_delete", 0)
            ->leftJoin('survey_answer as sa', function ($join) use ($idSurvey, $idBranch, $idUser) {
                $join->on("sa.{$this->primaryKey}", "{$this->table}.{$this->primaryKey}")
                    ->where("sa.{$this->primaryKey}", $idSurvey)
                    ->where('sa.branch_id', $idBranch)
                    ->where('sa.user_id', $idUser)
                    ->where('survey_answer_status', SurveyAnswerTable::STATUS_IN_PROCESS);
            })
            // lấy tổng điểm tất cả câu hỏi của khảo sát nếu khảo sát có tính điểm //
            ->leftJoin('survey_question as sq', function ($join) use ($idSurvey) {
                $join->on("sq.{$this->primaryKey}", "{$this->table}.{$this->primaryKey}")
                    ->where("{$this->table}.count_point", self::IS_COUNT_POINT);
            });


        return $select->first();
    }

    /**
     * Lấy khảo sát đang ở trạng thái tạm ngưng
     * @param $idSurvey
     * @return mixed
     */

    public function getSurveyPause($idSurvey)
    {
        $select = $this
            ->where("{$this->table}.{$this->primaryKey}", $idSurvey)
            ->where("{$this->table}.status", self::STATUS_PAUSE)
            ->where("{$this->table}.is_active", 1)
            ->where("{$this->table}.is_delete", 0);
        return $select->first();
    }



    /**
     * Lấy thông tin khảo sát trong list
     *
     * @param array $arrSurveyId
     * @param $idOutlet
     * @return mixed
     */
    public function getSurveyListBanner(array $arrSurveyId, $idBranch)
    {
        // Query chính, lấy danh sách khảo sát release
        $select = $this->buildMissionQuery([]);
        $select->whereIn('survey_id', $arrSurveyId);

        return $this->select('sub.*')
            ->from($select, 'sub')
            ->leftJoin('survey_outlet as so', function ($join) use ($idBranch) {
                $join->on('so.survey_id', 'sub.survey_id')
                    ->where('so.branch_id', $idBranch);
            })
            ->where('sub.allow_all_branch', 1)
            ->orWhereNotNull('so.survey_branch_id')
            ->get();
    }

    /**
     * Số lượng khảo sát
     * @param array $filters
     * @return mixed
     */
    public function getNewSurvey($filters = [])
    {
        //        $target = SurveyTable::SURVEY_TARGET;
        //        $select = $this->select("{$this->table}.*")
        //            ->join('survey_apply_user as sau', function ($join) use ($filters, $target) {
        //                $join->on('sau.survey_id', "{$this->table}.survey_id")
        //                     ->whereRaw("{$this->table}.type_user = '{$target}'")
        //                     ->whereRaw("sau.user_id = {$filters['user_id']}");
        //            })
        //            ->where("{$this->table}.status", self::STATUS_RELEASE)
        //            ->where("{$this->table}.is_active", 1)
        //            ->where("{$this->table}.is_delete", 0)
        //            ->where(function ($cond1) {
        //                $cond1->where("{$this->table}.is_exec_time", 0) // Không giới hạn thời gian khảo sát
        //                ->orWhere(function ($cond2) {
        //                    // Giới hạn thời gian khảo sát
        //                    $now = Carbon::now()->format('Y-m-d');
        //                    $cond2->whereRaw("(date({$this->table}.start_date) <= ? and date({$this->table}.end_date) >= ?)", [$now, $now]);
        //                });
        //            });

        return $this->getMission($filters);
    }
}
