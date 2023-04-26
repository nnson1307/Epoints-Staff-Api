<?php

namespace Modules\Survey\Repositories\ListData;

use Modules\Survey\Models\SurveyAnswerTable;
use Modules\Survey\Models\SurveyTable;
use Modules\Survey\Repositories\ListData\Support\CheckAvailable;
use Modules\Survey\Repositories\ListData\Support\CheckTimesPlay;
use Modules\Survey\Repositories\SurveyProcess\SurveyProcessInterface;
use MyCore\Repository\PagingTrait;

/**
 * Class ListDataRepo
 * @package Modules\Survey\Repositories\ListData
 * @author DaiDP
 * @since Feb, 2022
 */
class ListDataRepo implements ListDataInterface
{
    use CheckAvailable, CheckTimesPlay, PagingTrait;


    protected $survey;


    /**
     * ListDataRepo constructor
     * @param SurveyTable $mSurvey
     */
    public function __construct(SurveyTable $mSurvey)
    {
        $this->survey = $mSurvey;
    }

    /**
     * Danh sách khảo sát outlet có thể tham gia
     * RET-1762
     * @param $filters
     * @return mixed
     */
    public function mission($filters = [])
    {

        $filters['user_id'] = auth()->id();
        $surveyList = $this->survey->getMission($filters);
        //        $rLoyalty = app()->get(LoyaltyAccumInterface::class);
        $arrData = [];
        foreach ($surveyList as $item) {
            // Kiểm tra điều kiện hiển thị
            if (!$this->isValid($item, $filters['branch_id'], $filters['user_id'])) {
                continue;
            }

            $i = $item->only([
                'survey_id', 'survey_name', 'survey_code', 'count_point', 'status', 'survey_description', 'survey_banner',
                'is_exec_time', 'start_date', 'end_date', 'max_times', 'num_completed_times', 'total_questions', 'total_point', 'created_at'
            ]);
            $i['count_point'] = $this->getAvailableCountPointName($i['count_point']);
            $i['is_start'] = $this->getStatusOpenOrPause($i['status']);
            $i['survey_status'] = $this->getAvailableStatus($item);
            $i['total_point'] = intval($i['total_point']);
            $i['survey_status_name'] = $this->getAvailableStatusName($i['survey_status']);
            // Tính điểm tích lũy
            //            $i['accumulation_point'] = $rLoyalty->getSurveyPoint($i['survey_id']);

            $arrData[] = $i;
        }

        $this->fillInProcess($arrData, $filters['branch_id'], $filters['user_id']);

        return $arrData;
    }

    /**
     * Khảo sát ở banner game trang chủ
     * RET-2048
     * @param array $listBanner \Modules\Loyalty\Repositories\Game\GameRepo@banner
     * @return mixed
     */
    public function banner(array &$listBanner)
    {
        // TODO: Lọc ra những banner link tới khảo sát
        $arrSurveyId = [];
        foreach ($listBanner as $key => $item) {
            if ($item['action'] == 'survey_detail') {
                $arrSurveyId[$item['action_params']->survey_id][] = $key;
            }
        }

        // TODO: Không có khảo sát thì thoát
        if (empty($arrSurveyId)) {
            return;
        }

        // TODO: Check điều kiện hiển thị
        $surveyList = $this->survey->getSurveyListBanner(array_keys($arrSurveyId), $this->getOutletId());

        // Điền khảo sát đang thực hiện 1 phần
        $this->fillInProcess($surveyList);
        $rLoyalty = app()->get(LoyaltyAccumInterface::class);

        foreach ($surveyList as $item) {
            if (!$this->isValid($item)) {
                // Khảo sát không đủ điều kiện hiển thị không xử lý gì. Xuống dưới sẽ xóa
                continue;
            }

            // Tính điểm tích lũy của khảo sát
            foreach ($arrSurveyId[$item->survey_id] as $kg) {
                $listBanner[$kg]['accumulation_point'] = $rLoyalty->getSurveyPoint($item->survey_id);
                $listBanner[$kg]['is_in_process'] = $item->is_in_process;
                $listBanner[$kg]['total_questions'] = $item->total_questions;
                $listBanner[$kg]['num_questions_completed'] = $item->num_questions_completed;
            }

            // Xóa khỏi list xử lý, để phần dưới không phải xóa nhầm
            unset($arrSurveyId[$item->survey_id]);
        }

        // Xóa khảo sát còn lại trong list arrSurveyId, vì còn lại là không tồn tại
        foreach ($arrSurveyId as $pos) {
            foreach ($pos as $kg) {
                unset($listBanner[$kg]);
            }
        }
    }

    /**
     * Lịch sử khảo sát
     * RET-1765
     * @param $filters
     * @return mixed
     */
    public function history($filters)
    {
        $idBranch = $filters['branch_id'];
        $filters['user_id'] = auth()->id();

        // Lấy danh sách lịch sử khảo sát
        $mSurveyAnswer = app()->get(SurveyAnswerTable::class);
        $data = $mSurveyAnswer->listHistory($idBranch, $filters);

        return $this->toPagingData($data);
    }

    /**
     * Xem câu hỏi đã làm ở khảo sát
     * RET-1765
     * @params $idSurveyAnswer
     * @params $questionNo
     * @return mixed
     */
    public function historyPreview($idSurveyAnswer, $questionNo, $idBranch)
    {
        // Lấy thông tin khảo sát
        $mSurveyAnswer = app()->get(SurveyAnswerTable::class);
        $detail = $mSurveyAnswer->getDetail($idSurveyAnswer, $idBranch, auth()->id());
        if (!$detail) {
            throw new ListDataRepoException(ListDataRepoException::SURVEY_NOT_FOUND);
        }

        $rSurveyProcess = app()->get(SurveyProcessInterface::class);
        return $rSurveyProcess->getQuestionHistory($detail->survey_id, $idSurveyAnswer, $questionNo);
    }


    /**
     * Bổ sung thêm thông tin in-process của survey
     * @param $surveyList
     * @param $idBranch
     * @param $idUser
     */
    protected function fillInProcess(&$surveyList, $idBranch, $idUser)
    {
        // Lấy ra id survey
        $arrSurveyId = [];
        foreach ($surveyList as $item) {
            $arrSurveyId[] = $item['survey_id'];
        }
        // Lấy danh sách câu trả lời của survey còn dang dở
        $mSurAns = app()->get(SurveyAnswerTable::class);
        $arrInprocess = $mSurAns->getInprocessList($arrSurveyId, $idBranch, $idUser);
        // Bổ sung thêm thông tin survey chưa hoàn thành
        foreach ($surveyList as &$item) {
            // Có tồn tại trong in-process là chưa hoàn thành
            $idSurvey = $item['survey_id'];
            $isInProcess = isset($arrInprocess[$idSurvey]);

            // Thêm field
            $item['is_in_process'] = intval($isInProcess);
            //            $item['total_questions'] = $isInProcess ? $arrInprocess[$idSurvey]['total_questions'] : null;
            $item['num_questions_completed'] = $isInProcess ? $arrInprocess[$idSurvey]['num_questions_completed'] : null;
        }
    }

    /**
     * Kiểm tra hiệu lực khảo sát
     *
     * @param $surveyInfo
     * @param $idBranch
     * @param $idUser
     * @return bool
     */
    protected function isValid($surveyInfo, $idBranch, $idUser)
    {
        try {
            // Kiểm tra thời gian diễn ra
            $this->checkAvailable($surveyInfo);

            // Kiểm tra số lần chơi
            $this->checkTimesPlay($surveyInfo, $idBranch, $idUser);
        } catch (ListDataRepoException $ex) {
            return false;
        }

        return true;
    }

    /**
     * Lấy trạng thái sẵn sàn thực hiện của khảo sát
     *
     * @param $surveyInfo
     * @return string
     */
    protected function getAvailableStatus($surveyInfo)
    {
        try {
            $this->_checkFrequency($surveyInfo);

            $this->_checkTimePeriod($surveyInfo);
        } catch (ListDataRepoException $ex) {
            return 'coming';
        }

        return 'open';
    }

    /**
     * Kiểm tra trạng thái khảo sát dừng hay duyệt
     * @param $status
     * @return string
     */

    public function getStatusOpenOrPause($status)
    {

        switch ($status) {
            case SurveyTable::STATUS_RELEASE:
                $status =  SurveyTable::START;
                break;
            case SurveyTable::STATUS_PAUSE:
                $status = SurveyTable::STOP;
                break;
            default:
                $status = SurveyTable::STOP;
        }
        return $status;
    }

    /**
     * Author Hoàng vũ
     * Lấy tên hiển thị khảo sát có tính điểm hay không 
     * @param int $countPoint 
     * @return string
     */

    public function getAvailableCountPointName($countPoint)
    {
        switch ($countPoint) {
            case SurveyTable::IS_COUNT_POINT:
                return 'point';
            default:
                return 'not_point';
        }
    }


    /**
     * Tên trạng thái
     *
     * @param $status
     * @return string
     */
    protected function getAvailableStatusName($status)
    {
        switch ($status) {
            case 'open':
                return __('Có thể thực hiện');

            case 'coming':
                return __('Chưa thể thực hiện');

            default:
                return '';
        }
    }

    /**
     * Số lượng khảo sát
     *
     * @return mixed
     */
    public function count($filters = [])
    {
        $filters['user_id'] = auth()->id();
        $surveyList = $this->survey->getNewSurvey($filters);
        $arrData = [];
        foreach ($surveyList as $item) {
            // Kiểm tra điều kiện hiển thị
            if (!$this->isValid($item, $filters['branch_id'], $filters['user_id'])) {
                continue;
            }
            $arrData[] = $item;
        }

        return [
            "total" => sizeof($arrData)
        ];
    }
}
