<?php
namespace Modules\Survey\Repositories\ListData\Support;

use Carbon\Carbon;
use Modules\Survey\Models\SurveyTable;
use Modules\Survey\Repositories\ListData\ListDataRepoException;

/**
 * Trait CheckAvailable
 * @package Modules\Survey\Repositories\ListData\Support
 * @author DaiDP
 * @since Feb, 2022
 */
trait CheckAvailable
{
    /**
     * Check điều kiện thời gian
     *
     * @param $surveyInfo
     * @param bool $throwException Return kết quả hoặc là tạo exception để dừng xử lý
     * @return bool
     * @throws ListDataRepoException
     */
    protected function checkAvailable($surveyInfo, $throwException = true)
    {
        try {
            // Check thời gian diễn ra
            $this->_checkPeriod($surveyInfo);

            // Kiểm tra tần suất thực hiện
            //$this->_checkFrequency($surveyInfo);

            // Kiểm tra thời gian thực hiện trong ngày
            //$this->_checkTimePeriod($surveyInfo);
        }
        catch (ListDataRepoException $ex) {
            if ($throwException) {
                throw $ex;
            }

            return false;
        }

        return true;
    }

    /**
     * Kiểm tra ngày hiệu lực của chương trình
     *
     * @param $surveyInfo
     * @throws ListDataRepoException
     */
    protected function _checkPeriod($surveyInfo)
    {
        // Không giới hạn ngày
        if ($surveyInfo->is_exec_time == 0) {
            return;
        }

        // Ngày không được cấu hình
        if (empty($surveyInfo->start_date) || empty($surveyInfo->end_date)) {
            throw new ListDataRepoException(ListDataRepoException::SURVEY_EXPIRED);
        }

        // Kiểm tra theo ngày hieu luc
        $curTime   = Carbon::now();
        $startTime = Carbon::createFromTimeString($surveyInfo->start_date);
        $endTime   = Carbon::createFromTimeString($surveyInfo->end_date);

        if ($curTime->diffInSeconds($startTime, false) > 0 || $curTime->diffInSeconds($endTime, false) < 0) {
            throw new ListDataRepoException(ListDataRepoException::SURVEY_EXPIRED);
        }
    }

    /**
     * Kiểm tra tần suất thực hiện
     *
     * @param $surveyInfo
     * @return mixed
     * @throws ListDataRepoException
     */
    protected function _checkFrequency($surveyInfo)
    {
        switch ($surveyInfo->frequency)
        {
            case SurveyTable::FREQUENCY_DAILY:
                return $this->__frequencyDaily();

            case SurveyTable::FREQUENCY_WEEKLY:
                return $this->__frequencyWeekly($surveyInfo);

            case SurveyTable::FREQUENCY_MONTHLY:
                return $this->__frequencyMonthly($surveyInfo);

            default:
                throw new ListDataRepoException(ListDataRepoException::SURVEY_EXPIRED);
        }
    }

    /**
     * Kiểm tra thời gian thực hiện trong ngày
     *
     * @param $surveyInfo
     * @throws ListDataRepoException
     */
    protected function _checkTimePeriod($surveyInfo)
    {
        // Không giới hạn thời gian
        if (! $surveyInfo->is_limit_exec_time) {
            return;
        }

        // Thời gian không được cài đặt
        if (empty($surveyInfo->exec_time_from) || empty($surveyInfo->exec_time_to)) {
            throw new ListDataRepoException(ListDataRepoException::SURVEY_EXPIRED);
        }

        // So sánh thời gian
        $curTime   = Carbon::now();
        $startTime = (clone $curTime)->setTimeFromTimeString($surveyInfo->exec_time_from);
        $endTime   = (clone $curTime)->setTimeFromTimeString($surveyInfo->exec_time_to);

        if ($curTime->diffInSeconds($startTime, false) > 0 || $curTime->diffInSeconds($endTime, false) < 0) {
            throw new ListDataRepoException(ListDataRepoException::SURVEY_EXPIRED);
        }
    }

    /**
     * Kiểm tra tần suất theo hàng ngày
     *
     * @return mixed
     */
    protected function __frequencyDaily()
    {
        return;
    }

    /**
     * Kiểm tra tần suất theo tuần
     *
     * @param $surveyInfo
     * @return mixed
     * @throws ListDataRepoException
     */
    protected function __frequencyWeekly($surveyInfo)
    {
        $curTime = Carbon::now();
        $allow   = explode(',', $surveyInfo->frequency_value);

        if (in_array($curTime->dayOfWeek, $allow)) {
            return;
        }

        throw new ListDataRepoException(ListDataRepoException::SURVEY_EXPIRED);
    }

    /**
     * Kiểm tra tần suất theo tháng
     *
     * @param $surveyInfo
     * @return mixed
     * @throws ListDataRepoException
     */
    protected function __frequencyMonthly($surveyInfo)
    {
        $curTime = Carbon::now();

        // Kiểm tra tháng lặp lại
        $month = explode(',', $surveyInfo->frequency_value);
        if (! in_array($curTime->month, $month)) {
            throw new ListDataRepoException(ListDataRepoException::SURVEY_EXPIRED);
        }

        // TH chọn Kiểm tra ngày trong tháng
        $allowMonth = explode(',', $surveyInfo->day_in_monthly);
        if ($surveyInfo->frequency_monthly_type == SurveyTable::FREQUENCY_MONTH_TYPE_MONTH)
        {
            if (! in_array($curTime->day, $allowMonth)) {
                // Kiểm tra có phải là ngày cuối của tháng không
                if (!$curTime->isLastOfMonth() || !in_array(SurveyTable::LAST_DATE_OF_MONTH, $allowMonth) ) {
                    throw new ListDataRepoException(ListDataRepoException::SURVEY_EXPIRED);
                }
            }

            return;
        }

        // TH chọn Kiểm tra ngày trong tuần
        $allowWeek = explode(',', $surveyInfo->day_in_week);
        if (! in_array($curTime->weekOfMonth, $allowWeek)) {
            // Lấy tuần cuối của tháng
            $endMonth = clone $curTime;
            $weeksInMonth = $endMonth->endOfMonth()->weekOfMonth;

            // Kiểm tra có phải là tuần cuối của tháng không
            if (! ($curTime->weekOfMonth == $weeksInMonth && in_array(SurveyTable::LAST_DATE_OF_MONTH, $allowWeek)) ) {
                throw new ListDataRepoException(ListDataRepoException::SURVEY_EXPIRED);
            }
        }

        // Kiểm tra ngày lặp lại trong tuần
        $allowWeekDay = explode(',', $surveyInfo->day_in_week_repeat);
        if (in_array($curTime->dayOfWeek, $allowWeekDay)) {
            return;
        }

        throw new ListDataRepoException(ListDataRepoException::SURVEY_EXPIRED);
    }
}