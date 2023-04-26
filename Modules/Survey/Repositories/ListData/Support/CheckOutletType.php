<?php
namespace Modules\Survey\Repositories\ListData\Support;

use Modules\Survey\Models\SurveyOutletTable;
use Modules\Survey\Repositories\ListData\ListDataRepoException;

/**
 * Trait CheckOutletType
 * @package Modules\Survey\Repositories\ListData\Support
 * @author DaiDP
 * @since Feb, 2022
 */
trait CheckOutletType
{
    /**
     * Kiểm tra outlet áp dụng
     *
     * @param $surveyInfo
     * @return bool
     * @throws ListDataRepoException
     */
    protected function checkOutletType($surveyInfo, $idBranch)
    {
        if ($surveyInfo->allow_all_outlet == 1) {
            return;
        }

//        $user   = auth()->user();
        $mSvOut = app()->get(SurveyOutletTable::class);
        $rs = $mSvOut->checkOutlet($surveyInfo->survey_id, $idBranch);

        if ($rs) {
            return;
        }

        throw new ListDataRepoException(ListDataRepoException::OUTLET_NOT_ALLOW);
    }
}