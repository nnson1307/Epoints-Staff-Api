<?php

namespace Modules\Survey\Repositories\SurveyProcess;

/**
 * Interface SurveyProcessInterface
 * @package Modules\Survey\Repositories\SurveyProcess
 * @author DaiDP
 * @since Feb, 2022
 */
interface SurveyProcessInterface
{
    /**
     * Bắt đầu khảo sát
     * RET-8632
     * @param $idSurvey
     * @param $idBranch
     * @param null $questionNo
     * @return mixed
     */
    public function start($idSurvey, $idBranch, $questionNo = null);

    /**
     * Gửi câu hỏi khảo sát
     * RET-8632
     * @param $answer
     */
    public function submit($answer);

    /**
     * Hoàn thành gửi khảo sát
     * RET-1831
     * @param $answer
     */
    public function finish($answer);

    /**
     * Lấy nội dung câu hỏi để thực hiện khảo sát
     * @param $idSurvey
     * @param $idAnswer
     * @param $questionNo
     * @return mixed
     * @throws SurveyProcessException
     */
    public function getQuestion($idSurvey, $idAnswer, $questionNo = 1);

    /**
     * Lấy nội dung câu hỏi lịch sử khảo sát
     * @param $idSurvey
     * @param $idAnswer
     * @param $questionNo
     * @return mixed
     * @throws SurveyProcessException
     */

    public function getQuestionHistory($idSurvey, $idAnswer, $questionNo = 1);
}
