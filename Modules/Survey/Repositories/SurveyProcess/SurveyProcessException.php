<?php
namespace Modules\Survey\Repositories\SurveyProcess;

use MyCore\Repository\RepositoryExceptionAbstract;

/**
 * Class SurveyProcessException
 * @package Modules\Survey\Repositories\SurveyProcess
 * @author DaiDP
 * @since Feb, 2022
 */
class SurveyProcessException extends RepositoryExceptionAbstract
{
    const SURVEY_NOT_FOUND = 1;
    const QUESTION_NOT_FOUND = 2;
    const QUESTION_TYPE_NOT_SUPPORT = 3;
    const ANSWER_REQUIRED = 4;
    const ANSWER_INVALID = 5;
    const QUESTION_NOT_END = 6;
    const USER_NOT_START = 7;
    const MISSING_POPUP_CONFIG = 8;
    const SURVEY_NOT_OPEN = 9;
    const SURVEY_CHANGED = 10;

    public function __construct($errorType, $preg = [], $errorCode = 1, $errorData = null)
    {
        $error = $this->transMessage($errorType);
        $this->title     = $error['title'];
        $this->errorData = $errorData;

        // Lấy mã lỗi
        if (isset($error['error_code'])) {
            $errorCode = $error['error_code'];
        }

        parent::__construct(__($error['message'], $preg), $errorCode);
    }

    protected function transMessage($code)
    {
        switch ($code) {
            case self::SURVEY_NOT_FOUND:
                return [
                    'message' => __('Không tìm thấy khảo sát'),
                    'title'   => __('Thông báo')
                ];
            case self::QUESTION_NOT_FOUND:
                return [
                    'message' => __('Không tìm thấy câu hỏi'),
                    'title'   => __('Thông báo')
                ];

            case self::QUESTION_TYPE_NOT_SUPPORT:
                return [
                    'message' => __('Loại câu hỏi không được hỗ trợ'),
                    'title'   => __('Thông báo')
                ];
            case self::ANSWER_REQUIRED:
                return [
                    'message' => __('Câu trả lời là bắt buộc'),
                    'title'   => __('Thông báo')
                ];
            case self::ANSWER_INVALID:
                return [
                    'message' => __('Câu trả lời không đúng định dạng'),
                    'title'   => __('Thông báo')
                ];
            case self::QUESTION_NOT_END:
                return [
                    'message' => __('Đây không phải là câu hỏi kết thúc khảo sát. Vui lòng kiểm tra lại'),
                    'title'   => __('Thông báo')
                ];
            case self::USER_NOT_START:
                return [
                    'message' => __('Bạn chưa bắt đầu khảo sát mà. Sao giờ lại kết thúc'),
                    'title'   => __('Thông báo')
                ];
            case self::MISSING_POPUP_CONFIG:
                return [
                    'message' => __('Chưa cấu hình trang hoàn thành'),
                    'title'   => __('Thông báo')
                ];
            case self::SURVEY_NOT_OPEN:
                return [
                    'message' => __('Khảo sát chưa bắt đầu. Vui lòng thử lại sau.'),
                    'title'   => __('Thông báo')
                ];
            case self::SURVEY_CHANGED:
                return [
                    'message' => __('Rất tiếc! Khảo sát hiện tại không còn khả dụng. Vui lòng chọn khảo sát khác để thực hiện.'),
                    'title'   => __('Thông báo'),
                    'error_code' => 302
                ];
        }
    }
}