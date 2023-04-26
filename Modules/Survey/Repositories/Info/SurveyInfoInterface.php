<?php
namespace Modules\Survey\Repositories\Info;

/**
 * Interface SurveyInfoInterface
 * @package Modules\Survey\Repositories\Info
 * @author DaiDP
 * @since Feb, 2022
 */
interface SurveyInfoInterface
{
    /**
     * Chi tiết khảo sát
     * RET-1766
     * @param $idSurvey
     * @param $idBranch
     * @param bool $calcPoint
     * @return mixed
     * @throws SurveyInfoException
     */
    public function detail($idSurvey, $idBranch, $calcPoint = true);

}