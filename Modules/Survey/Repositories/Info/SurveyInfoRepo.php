<?php

namespace Modules\Survey\Repositories\Info;

use Modules\Survey\Models\SurveyTable;
use Modules\Survey\Repositories\ListData\ListDataRepo;

/**
 * Class SurveyInfoRepo
 * @package Modules\Survey\Repositories\Info
 * @author DaiDP
 * @since Feb, 2022
 */
class SurveyInfoRepo implements SurveyInfoInterface
{

    protected $survey;


    /**
     * SurveyInfoRepo constructor
     */
    public function __construct(SurveyTable $mSurvey)
    {
        $this->survey = $mSurvey;
    }

    /**
     * Chi tiết khảo sát
     * RET-1766
     * @param $idSurvey
     * @param $idBranch
     * @param bool $calcPoint
     * @return mixed
     * @throws SurveyInfoException
     */
    public function detail($idSurvey, $idBranch, $calcPoint = true)
    {
        $idUser = auth()->id();
        $detail = $this->survey->detail($idSurvey, $idBranch, $idUser);
        if (!$detail) {
            throw new SurveyInfoException(SurveyInfoException::SURVEY_NOT_FOUND);
        }
        // kiểm tra trạng thái của khảo sát (duyệt và ngưng hoạt động)
        $detail->is_start =  SurveyTable::STOP;
        if ($detail->status != SurveyTable::STATUS_PAUSE) {
            $detail->is_start =  SurveyTable::START;
        }
        // Điểm tích lũy
        //        if ($calcPoint) {
        //            $rLoyalty = app()->get(LoyaltyAccumInterface::class);
        //            $detail->accumulation_point = $rLoyalty->getSurveyPoint($idSurvey);
        //        }

        return $detail;
    }
}
