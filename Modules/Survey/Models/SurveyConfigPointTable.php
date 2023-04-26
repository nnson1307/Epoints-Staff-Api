<?php

namespace Modules\Survey\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class SurveyOutletTable
 * @package Modules\Survey\Models
 * @author DaiDP
 * @since Feb, 2022
 */
class SurveyConfigPointTable extends Model
{

    protected $table = 'survey_config_point';
    protected $primaryKey = 'id_config_point';

    const IS_COUNT_POINT_TEXT = 1;
    const SHOW_ANSWER_WRONG = 1;
    const SHOW_ANSWER_SUCCESS = 1;
    const SHOW_POINT = 1;
    const SHOW_FINSHNED = 'N';

    /**
     * Lấy cấu hình tính điểm của khảo sát
     * @param $idSurvey
     * @return mixed
     */

    public function getConfigPoint($idSurvey)
    {
        return $this->where("survey_id", $idSurvey)->first();
    }
}
