<?php

namespace Modules\Survey\Repositories\SurveyProcess;

use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\DB;
use MyCore\Repository\PagingTrait;
use Illuminate\Support\Facades\Log;
use Modules\Survey\Models\SurveyTable;
use Modules\Survey\Models\SurveyAnswerTable;
use Modules\Survey\Models\SurveyQuestionTable;
use Modules\Survey\Models\SurveyConfigPointTable;
use Modules\Survey\Models\SurveyAnswerQuestionTable;
use Modules\Survey\Models\SurveyQuestionChoiceTable;
use Modules\Survey\Models\TemplateNotificationTable;
use Modules\Survey\Repositories\Info\SurveyInfoException;
use Modules\Survey\Repositories\Info\SurveyInfoInterface;
use Modules\Survey\Models\SurveyTemplateNotificationTable;
use Modules\Survey\Repositories\ListData\Support\CheckStatus;
use Modules\Survey\Repositories\ListData\ListDataRepoException;
use Modules\Survey\Repositories\ListData\Support\CheckAvailable;
use Modules\Survey\Repositories\ListData\ListDataSurveyException;
use Modules\Survey\Repositories\ListData\Support\CheckOutletType;
use Modules\Survey\Repositories\SurveyProcess\QuestionType\QuestionTypeFactory;

/**
 * Class SurveyProcessRepo
 * @package Modules\Survey\Repositories\SurveyProcess
 * @author DaiDP
 * @since Feb, 2022
 */
class SurveyProcessRepo implements SurveyProcessInterface
{
    use CheckAvailable, CheckOutletType, CheckStatus, PagingTrait;

    /**
     * Bắt đầu khảo sát
     * RET-8632
     * @param $idSurvey
     * @param $idBranch
     * @param null $questionNo
     * @return mixed
     * @throws SurveyProcessException
     */
    public function start($idSurvey, $idBranch, $questionNo = null)
    {
        $idUser = auth()->id();
        // lấy thông tin survey & Kiểm tra hiệu lực
        $detail = $this->getDetail($idSurvey, $idBranch);
        try {
            $this->_checkFrequency($detail);
            $this->_checkTimePeriod($detail);
        } catch (ListDataRepoException $ex) {
            throw new SurveyProcessException(SurveyProcessException::SURVEY_NOT_OPEN);
        }
        // Resume khảo sát
        if (!$questionNo && $detail->survey_answer_id) {
            $mQuestion = app()->get(SurveyQuestionTable::class);
            $questionNo = $mQuestion->getResumeQuestionPos($idSurvey, $idBranch, $idUser, $detail->survey_answer_id);
        }

        // Trả về danh sách câu hỏi
        return $this->getQuestion($idSurvey, $detail->survey_answer_id, $questionNo);
    }

    /**
     * Gửi câu hỏi khảo sát
     * RET-8632
     * @param $answer
     * @return mixed
     * @throws ListDataRepoException
     * @throws SurveyProcessException
     */
    public function submit($answer)
    {
        $idSurvey = $answer['survey_id'];
        $idBranch = $answer['branch_id'];

        // lấy thông tin survey & Kiểm tra hiệu lực
        $detail = $this->getDetail($idSurvey, $idBranch);
        if (!$detail->survey_answer_id) {
            $answerSession = $this->createAnswerSession($idSurvey, $idBranch);
            $detail->survey_answer_id = $answerSession->survey_answer_id;
        }
        // Lưu khảo sát
        $this->saveAnswer($detail, $answer);

        // Lấy thứ tự câu hỏi hiện tại
        $mQuestion = app()->get(SurveyQuestionTable::class);
        $no = $mQuestion->getPosQuestion($idSurvey, $answer['survey_question_id']) + 1;
        // Trả về danh sách câu hỏi
        return $this->getQuestion($idSurvey, $detail->survey_answer_id, $no);
    }

    /**
     * Hoàn thành gửi khảo sát
     * RET-1831
     * @param $answer
     * @return array
     * @throws ListDataRepoException
     * @throws SurveyProcessException
     */
    public function finish($answer)
    {

        $idSurvey = $answer['survey_id'];
        $idBranch = $answer['branch_id'];

        // lấy thông tin survey & Kiểm tra hiệu lực
        $detail = $this->getDetail($idSurvey, $idBranch, true);
        // Trả lời khảo sát đã hoàn thành
        if (!$detail->survey_answer_id) {
            # Khảo sát có 1 câu hoặc chưa start
            $mQuestion = app()->get(SurveyQuestionTable::class);

            // Số lượng câu hỏi nhiều hơn 1
            if ($mQuestion->countAnswerQuestionsType($idSurvey) > 1) {
                throw new SurveyProcessException(SurveyProcessException::USER_NOT_START);
            }

            // Số lượng câu hỏi là 1 câu
            $answerSession = $this->createAnswerSession($idSurvey, $idBranch);
            $detail->survey_answer_id = $answerSession->survey_answer_id;
        } else {
            # Khảo sát có nhiều câu
            // Lấy câu hỏi cuối cùng của khảo sát
            $lastQuestion = $this->getLastSurveyQuestion($idSurvey);

            // Kiểm tra câu hỏi hiện tại phải câu cuối không
            $idSubmitQuestion = $answer['survey_question_id'];
            if ($lastQuestion->survey_question_id != $idSubmitQuestion) {
                throw new SurveyProcessException(SurveyProcessException::QUESTION_NOT_END);
            }
        }
        // Lưu khảo sát
        $this->saveAnswer($detail, $answer);
        // RET-1832: Gọi api tích điểm
        $accumPoint = 0;
        //        $rLoyalty = app()->get(LoyaltyAccumInterface::class);
        //        $rLoyalty->Survey($idSurvey, $detail->survey_answer_id, $accumPoint);

        // Cập nhật hoàn thành khảo sát và trả về kết quả tổng điểm //
        $this->updateFinish($idSurvey, $detail->survey_answer_id, $accumPoint);

        $popup = $this->getPopupContent($idSurvey, $detail->survey_answer_id);
        $popup->survey_answer_id = $detail->survey_answer_id;
        return $popup;
    }

    /**
     * Chi tiết survey
     * @param $idSurvey
     * @param $idBranch
     * @param $status
     * @return mixed
     * @throws SurveyProcessException
     */
    protected function getDetail($idSurvey, $idBranch, $checkStatus = null)
    {
        try {
            // Lấy thông tin khảo sát
            $rInfo = app()->get(SurveyInfoInterface::class);
            // Kiểm tra khảo sát tam dừng
            $detail = $rInfo->detail($idSurvey, $idBranch, false);
            if ($checkStatus) {
                $this->_pauseSurvey($detail);
            }
            // Kiểm tra thời gian hiện tại còn hiệu lực không
            $this->checkAvailable($detail);

            // Outlet có còn trong chương trình
            $this->checkOutletType($detail, $idBranch);
        } catch (ListDataRepoException | SurveyInfoException $ex) {
            throw new SurveyProcessException(SurveyProcessException::SURVEY_CHANGED);
        } catch (ListDataSurveyException $ex) {
            throw new ListDataSurveyException(ListDataSurveyException::SURVEY_PAUSE);
        }

        return $detail;
    }

    /**
     * Lấy nội dung câu hỏi để thực hiện khảo sát
     * @param $idSurvey
     * @param $idAnswer
     * @param $questionNo
     * @return mixed
     * @throws SurveyProcessException
     */
    public function getQuestion($idSurvey, $idAnswer, $questionNo = 1)
    {
        // Lấy header của câu hỏi
        $mQuestion = app()->get(SurveyQuestionTable::class);
        $questionPage = $mQuestion->getQuestion($idSurvey, $questionNo);
        if (!$questionPage->count()) {
            throw new SurveyProcessException(SurveyProcessException::QUESTION_NOT_FOUND);
        }
        $posDisplay = $mQuestion->getPosQuestionAnswer($idSurvey, $questionPage->first()->survey_question_id ?? 0);

        return $this->toPagingData($questionPage, function ($paging) use ($idAnswer, $posDisplay) {
            $curQuest = $paging->first();
            $curQuest->display_pos = $posDisplay;

            // Lấy chi tiết nội dung câu hỏi
            $oProcessor = QuestionTypeFactory::getInstance($curQuest->survey_question_type);
            return $oProcessor->getQuestionDetail($curQuest, $idAnswer);
        });
    }

    /**
     * Lấy lịch sử câu hỏi đã khảo sát
     * @param $idSurvey
     * @param $idAnswer
     * @param $questionNo
     * @return mixed
     * @throws SurveyProcessException
     */
    public function getQuestionHistory($idSurvey, $idAnswer, $questionNo = 1)
    {
        // Lấy header của câu hỏi
        $mQuestion = app()->get(SurveyQuestionTable::class);
        // kiểm tra khảo sát có tính điểm và lấy cấu hình để hiển thị câu hỏi //
        $listQuestionConfig = [];
        $isCountPoint = $this->checkIsCountPoint($idSurvey);
        if ($isCountPoint) {
            $listQuestionConfig = $this->hanldeToggleQuestionPoint($idSurvey, $idAnswer);
        }
        $questionPage = $mQuestion->getQuestionAnswerHistory($idSurvey, $questionNo, $listQuestionConfig);
        if (!$questionPage->count()) {
            throw new SurveyProcessException(SurveyProcessException::QUESTION_NOT_FOUND);
        }
        $posDisplay = $mQuestion->getPosQuestionAnswer($idSurvey, $questionPage->first()->survey_question_id ?? 0);

        return $this->toPagingData($questionPage, function ($paging) use ($idAnswer, $posDisplay) {
            $curQuest = $paging->first();
            $curQuest->display_pos = $posDisplay;

            // Lấy chi tiết nội dung câu hỏi
            $oProcessor = QuestionTypeFactory::getInstance($curQuest->survey_question_type);
            return $oProcessor->getQuestionDetail($curQuest, $idAnswer);
        });
    }

    /**
     * Lấy cấu hình hiển thị các hỏi sau khi đã khảo sát tính điểm
     * @param $idSurvey
     * @return mixed
     */

    public function getConfigPoint($idSurvey)
    {
        $mSurveyConfigPoint = app()->get(SurveyConfigPointTable::class);
        $configPoint = $mSurveyConfigPoint->getConfigPoint($idSurvey);
        return $configPoint;
    }

    /**
     * Xử lý cấu hình tính điểm ẩn hiện các câu hỏi tính điểm
     * @param $idSurvey
     * @param $answerId
     * @return array
     */

    public function hanldeToggleQuestionPoint($idSurvey, $answerId)
    {
        $listQuestionId = [];
        // lấy cấu hình tích điểm
        $configPoint = $this->getConfigPoint($idSurvey);
        // xử lý các trường hợp cấu hình, hiên thị câu đúng, sai ẩn
        if ($configPoint) {
            // danh sách câu hỏi user đã lời 
            $listQuestionAnswerUser = $this->getQuestionOfUserAnswer($answerId);
            // trường hợp ẩn câu hỏi bị lỡ và sai
            if ($configPoint->show_answer_wrong != SurveyConfigPointTable::SHOW_ANSWER_WRONG) {
                $listArrayWrong = array_merge($listQuestionAnswerUser['question_wrong'], $listQuestionAnswerUser['question_skip']);
                $listQuestionId = array_merge($listQuestionId, $listArrayWrong);
            }
            // trường hợp ẩn câu trả lời đúng
            if ($configPoint->show_answer_success != SurveyConfigPointTable::SHOW_ANSWER_SUCCESS) {
                $listQuestionId = array_merge($listQuestionId, $listQuestionAnswerUser['question_success']);
            }
        }
        // trả về danh sách id câu hỏi
        return $listQuestionId;
    }

    /**
     * Lấy các câu hỏi của user đã trả lời
     * @param $answerId
     * @return mixed
     */

    public function getQuestionOfUserAnswer($answerId)
    {
        // list câu hỏi user trả lời đúng 
        $listQuestionAnswerSuccess = [];
        // list câu hỏi user trả lời sai 
        $listQuestionAnswerWrong = [];
        // list câu hỏi user đã bỏ lỡ 
        $listQuestionAnswerSkip = $this->getQuestionUserSkip($answerId);
        $mSurveyAnswerQuestion = app()->get(SurveyAnswerQuestionTable::class);
        $listQuestionOfAnswerQuestion = $mSurveyAnswerQuestion->getQuestions($answerId);
        // xử lý data và trả về danh sách câu hỏi và trả lời mới 
        $listQuestionOfAnswerQuestionNew = $this->hanldeDataAnswerQuestion($listQuestionOfAnswerQuestion, $answerId);
        // bắt đầu tính điểm số câu trả lời đúng sai và tổng điểm
        foreach ($listQuestionOfAnswerQuestionNew as $item) {
            $instanceQuestion = QuestionTypeFactory::getInstance($item->survey_question_type);
            // kiểm tra câu trả lời của user ( đúng, sai)
            $checkAnswerUser = $instanceQuestion->checkAnswerUser($item);
            if ($checkAnswerUser['result_answer'] == 'success') {
                $listQuestionAnswerSuccess[] = $checkAnswerUser['question_id'];
            } else {

                $listQuestionAnswerWrong[] = $checkAnswerUser['question_id'];
            }
        }
        $rs = [
            'question_success' => $listQuestionAnswerSuccess,
            'question_wrong' => $listQuestionAnswerWrong,
            'question_skip' => $listQuestionAnswerSkip
        ];
        return $rs;
    }

    /**
     * Lấy các câu hỏi mà user đã bỏ lờ 
     * @param $answerId
     */

    public function getQuestionUserSkip($answerId)
    {
        $mSurveyQuestion = app()->get(SurveyQuestionTable::class);
        $mSurveyAnswerQuestion = app()->get(SurveyAnswerQuestionTable::class);
        $getIdQuestions = $mSurveyAnswerQuestion->getListIdQuestion($answerId);
        $listIdQuestionSkip = $mSurveyQuestion->getListIdQuestion($getIdQuestions);
        return $listIdQuestionSkip;
    }

    /**
     * Lưu câu trả lời khảo sát
     * @param $detail
     * @param $answer
     * @throws SurveyProcessException
     */
    protected function saveAnswer($detail, $answer)
    {
        // Lấy thông tin câu hỏi
        $mQuestion = app()->get(SurveyQuestionTable::class);
        $questionDetail = $mQuestion->getDetail($detail['survey_id'], $answer['survey_question_id']);
        // Câu hỏi có tính điểm //
        $mSurvey = app()->get(SurveyTable::class);
        // $survey = $mSurvey->find($de)
        if (!$questionDetail) {
            throw new SurveyProcessException(SurveyProcessException::QUESTION_NOT_FOUND);
        }

        // Xử lý lưu
        $oProcessor = QuestionTypeFactory::getInstance($questionDetail->survey_question_type);
        $oProcessor->saveAnswer($detail->survey_answer_id, $questionDetail, $answer, $detail['count_point']);

        // RET-1833: Cập nhật số lượng câu hỏi đã hoàn thành
        $mAnswer = app()->get(SurveyAnswerTable::class);
        $mAnswer->updateCompleteQuestions($detail->survey_answer_id);
    }

    /**
     * Cập nhật khảo sát hoàn thành
     * RET-1831
     * @param $idSurvey
     * @param $idAnswerSession
     * @param $accumPoint
     */
    protected function updateFinish($idSurvey, $idAnswerSession, $accumPoint = null)
    {
        $mAnswer = app()->get(SurveyAnswerTable::class);
        // Tổng số câu trả lời đúng, sai, điểm 
        $listTotalCountPoint =  $this->cacTotalPointFinish($idSurvey, $idAnswerSession);
        $mAnswer->updateFinish($idSurvey, $idAnswerSession, $accumPoint, $listTotalCountPoint);
    }

    /**
     * Tính tổng số câu trả lời đúng sai và tổng điểm khi hoàn thành khảo sát 
     * @param int $idSurvey
     * @param int $idAnswerSession
     * @return array
     */

    public function cacTotalPointFinish($idSurvey, $idAnswerSession)
    {
        // dữ liệu mặc định //
        $dataDefault = [
            'total_answer_success' => null,
            'total_answer_wrong' => null,
            'total_point' => null
        ];
        // kiểm tra khảo sát có tính điểm //
        $isCountPoint = $this->checkIsCountPoint($idSurvey);
        if ($isCountPoint) {
            $totalSuccess = 0;
            $totalWrong = 0;
            $totalPoint = 0;
            // lấy tổng số câu hỏi và tổng điểm mặc định 
            $totalCountPointAndQuestion = $this->getTotalPointAndQuestion($idSurvey);
            // danh sách câu hỏi của phiên trả lời
            $mSurveyAnswerQuestion = app()->get(SurveyAnswerQuestionTable::class);
            $listQuestionOfAnswerQuestion = $mSurveyAnswerQuestion->getQuestions($idAnswerSession);
            // xử lý data và trả về danh sách câu hỏi và trả lời mới 
            $listQuestionOfAnswerQuestionNew = $this->hanldeDataAnswerQuestion($listQuestionOfAnswerQuestion, $idAnswerSession);
            // bắt đầu tính điểm số câu trả lời đúng sai và tổng điểm
            foreach ($listQuestionOfAnswerQuestionNew as $item) {
                // // khởi tạo các class  tương ứng với loại từng loại câu hỏi
                $instanceQuestion = QuestionTypeFactory::getInstance($item->survey_question_type);
                // kiểm tra câu trả lời của user ( đúng, sai)
                $checkAnswerUser = $instanceQuestion->checkAnswerUser($item);
                if ($checkAnswerUser['result_answer'] == 'success') {
                    $totalPoint += $item->value_point;
                    $totalSuccess += 1;
                } else {
                    $totalWrong += 1;
                }
            }
            // tổng điểm cuối cùng sau khi đã tính toán
            $totalPoint = $totalPoint . '/' . $totalCountPointAndQuestion['total_point'];
            $totalSuccess = $totalSuccess . '/' . $totalCountPointAndQuestion['total_question'];
            $totalWrong = $totalWrong . '/' . $totalCountPointAndQuestion['total_question'];
            $dataDefault['total_point'] = $totalPoint;
            $dataDefault['total_answer_wrong'] = $totalWrong;
            $dataDefault['total_answer_success'] = $totalSuccess;
        }
        return $dataDefault;
    }

    /**
     * Xử lý data các câu trả lời của user ở các loại câu hỏi
     * @param $listAnswerQuestion
     * @return mixed
     */

    public function hanldeDataAnswerQuestion($listAnswerQuestion, $idAnswerSession)
    {
        // kiểm tra ở phiên trả lời của user có trả lời câu hỏi chọn nhiều đáp án
        $filter['multi_choice'] = true;
        $mSurveyAnswerQuestion = app()->get(SurveyAnswerQuestionTable::class);
        $listQuestionOfAnswerQuestion = $mSurveyAnswerQuestion->getQuestions($idAnswerSession, $filter);
        if ($listQuestionOfAnswerQuestion->count() > 0) {
            // nhóm câu hỏi có id giống nhau 
            $convertDataQuestionAnswer = [];
            // danh sách câu trả lời cho câu hỏi chon nhiều đáp án 
            $listAnswerQuestionMutipleChoice = [];
            $groupListQuestion =  $listQuestionOfAnswerQuestion->groupBy('survey_question_id')->values();
            foreach ($groupListQuestion as $questions) {
                // câu hỏi  chọn nhiều đáp án 
                if (count($questions) > 1) {
                    foreach ($questions as $item) {
                        $listAnswerQuestionMutipleChoice[] = $item->survey_question_choice_id;
                    }
                    $questions[0]['survey_question_choice_id'] = $listAnswerQuestionMutipleChoice;
                    $convertDataQuestionAnswer[] = $questions[0];
                } else {
                    $convertDataQuestionAnswer[] = $questions[0];
                }
            }
            $listQuestionOfAnswerQuestion = $convertDataQuestionAnswer;
        }

        return $listQuestionOfAnswerQuestion;
    }

    /**
     * lấy tổng điểm và tổng số câu hỏi của khảo sát nếu có tính điểm
     * @param $idSurvey
     * @return array
     */

    public function getTotalPointAndQuestion($idSurvey)
    {
        $mSurveyQuestion = app()->get(SurveyQuestionTable::class);
        $data = $mSurveyQuestion->caclTotalPointAndQuestion($idSurvey);
        $rs = [
            'total_point' =>  $data->total_point ? intval($data->total_point) : 0,
            'total_question' => $data->total_question ? intval($data->total_question) : 0,
        ];
        return $rs;
    }



    /**
     * Kiểm tra khảo sát có tính điểm
     * @param int $idSurvey
     * @return boolean
     */

    public function checkIsCountPoint($idSurvey)
    {
        $flag = false;
        $mSurvey = app()->get(SurveyTable::class);
        $itemSurvey = $mSurvey->find($idSurvey);
        if ($itemSurvey->count_point) $flag = true;
        return $flag;
    }



    /**
     * Lấy câu hỏi cuối
     * RET-1831
     * @param $idSurvey
     * @return mixed
     */
    protected function getLastSurveyQuestion($idSurvey)
    {
        $mQuestion = app()->get(SurveyQuestionTable::class);
        return $mQuestion->getLastQuestion($idSurvey);
    }

    /**
     * Lấy nội dung popup
     * RET-1831
     * @param $idSurvey
     * @param $answerId
     * @return mixed
     */
    protected function getPopupContent($idSurvey, $answerId)
    {
        $mTemplate = app()->get(SurveyTemplateNotificationTable::class);
        $mAnswer = app()->get(SurveyAnswerTable::class);
        $mConfigPoint = app()->get(SurveyConfigPointTable::class);
        $popup = $mTemplate->getTemplate($idSurvey);
        $configPoint = $mConfigPoint->getConfigPoint($idSurvey);
        $answer = $mAnswer->find($answerId);
        if (!$popup) {
            $popup = new \stdClass();
            $popup->title = __('Đã hoàn thành nhiệm vụ');
            $popup->message = __('Cám ơn bạn đã hoàn thành nhiệm vụ. Câu trả lời của bạn đã được ghi nhận.');
            $popup->detail_background = null;
        }

        if ($popup && $configPoint && $configPoint->show_answer == $mConfigPoint::SHOW_FINSHNED && $popup->show_point == $mTemplate::SHOW_POINT) {
            $popup->total_point = $answer ? $answer->total_point : null;
        } else {
            $popup->total_point =  null;
        }

        return $popup;
    }

    /**
     * Tạo phiên trả lời khảo sát
     * @param $idSurvey
     * @return mixed
     */
    protected function createAnswerSession($idSurvey, $idBranch)
    {
        // RET-1833: Đếm số lượng câu hỏi của khảo sát
        $mSurveyQuestion = app()->get(SurveyQuestionTable::class);
        $totalQuestion = $mSurveyQuestion->countAnswerQuestionsType($idSurvey);

        $mAnswer = app()->get(SurveyAnswerTable::class);
        return $mAnswer->initSession($idSurvey, $idBranch, auth()->id(), $totalQuestion);
    }

    /**
     * Kiểm tra trạng thái tam ngưng khảo sát
     * @param $surveyInfo
     * @return mixed
     */

    public function _pauseSurvey($surveyInfo)
    {

        return $this->checkStautsPause($surveyInfo);
    }
}
