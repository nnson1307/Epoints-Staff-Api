<?php
namespace Modules\Survey\Repositories\ListData;

/**
 * Interface ListDataInterface
 * @package Modules\Survey\Repositories\ListData
 * @author DaiDP
 * @since Feb, 2022
 */
interface ListDataInterface
{
    /**
     * Danh sách khảo sát outlet có thể tham gia
     * RET-1762
     * @param $filters
     * @return mixed
     */
    public function mission($filters = []);

    /**
     * Khảo sát ở banner game trang chủ
     * RET-2048
     * @param array $listBanner \Modules\Loyalty\Repositories\Game\GameRepo@banner
     * @return mixed
     */
    public function banner(array &$listBanner);

    /**
     * Lịch sử khảo sát
     * RET-1765
     * @param $filters
     * @return mixed
     */
    public function history($filters);

    /**
     * Xem câu hỏi đã làm ở khảo sát
     * RET-1765
     * @params $idSurveyAnswer
     * @params $questionNo
     * @param $idSurveyAnswer
     * @param $questionNo
     * @param $idBranch
     * @return mixed
     */
    public function historyPreview($idSurveyAnswer, $questionNo, $idBranch);

    /**
     * Số lượng khảo sát
     *
     * @param array $filters
     * @return mixed
     */
    public function count($filters = []);
}