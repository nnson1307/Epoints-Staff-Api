<?php
namespace Modules\Survey\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class TemplateNotificationTable
 * @package Modules\Survey\Models
 * @author DaiDP
 * @since Feb, 2022
 */
class TemplateNotificationTable extends Model
{
    use CustomSerializeDateTime;

    const SURVEY = 'survey_success';

    protected $table = 'template_notification';
    protected $primaryKey = 'id';

    /**
     * Lấy nội dung popup
     * @param $key
     * @return mixed
     */
    public function getTemplate($key)
    {
        return $this->select(
                        'title',
                        'message',
                        'detail_background'
                    )
                    ->where('key', $key)
                    ->first();
    }
}