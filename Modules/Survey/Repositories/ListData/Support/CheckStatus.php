<?php

namespace Modules\Survey\Repositories\ListData\Support;

use Modules\Survey\Models\SurveyTable;
use Modules\Survey\Repositories\ListData\ListDataRepoException;
use Modules\Survey\Repositories\ListData\ListDataSurveyException;

/**
 * Trait CheckAvailable
 * @package Modules\Survey\Repositories\ListData\Support
 * @author DaiDP
 * @since Feb, 2022
 */
trait CheckStatus
{
    /**
     * Check trạng thái khảo tạm ngưng 
     *
     * @param $surveyInfo
     * @param bool $throwException Return kết quả hoặc là tạo exception để dừng xử lý
     * @return bool
     * @throws ListDataSurveyException
     */

    public function checkStautsPause($surveyInfo)
    {
        // Kiểm tra trạng thái khảo sát 
        if ($surveyInfo->status !== SurveyTable::STATUS_PAUSE) return;
        throw new ListDataSurveyException(ListDataSurveyException::SURVEY_PAUSE);
    }
}
