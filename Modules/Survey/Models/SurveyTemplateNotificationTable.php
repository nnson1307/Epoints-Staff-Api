<?php
namespace Modules\Survey\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class SurveyTemplateNotificationTable
 * @package Modules\Survey\Models
 * @author DaiDP
 * @since Mar, 2022
 */
class SurveyTemplateNotificationTable extends Model
{

    protected $table = 'survey_template_notification';
    protected $primaryKey = 'id';
    const SHOW_POINT = 1;
    /**
     * Lấy nội dung popup
     * @param $idSurvey
     * @return mixed
     */
    public function getTemplate($idSurvey)
    {
        return $this->select(
                        'title',
                        'message',
                        'detail_background',
                        'show_point'
                    )
                    ->where('survey_id', $idSurvey)
                    ->first();
    }
}