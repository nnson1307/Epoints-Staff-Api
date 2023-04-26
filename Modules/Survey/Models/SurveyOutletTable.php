<?php
namespace Modules\Survey\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class SurveyOutletTable
 * @package Modules\Survey\Models
 * @author DaiDP
 * @since Feb, 2022
 */
class SurveyOutletTable extends Model
{

    protected $table = 'survey_branch';
    protected $primaryKey = 'survey_branch_id';

    /**
     * Kiểm tra outlet có tham gia
     *
     * @param $idSurvey
     * @param $idOutlet
     * @return mixed
     */
    public function checkOutlet($idSurvey, $idOutlet)
    {
        return $this->where('survey_id', $idSurvey)
                    ->where('branch_id', $idOutlet)
                    ->first();
    }
}