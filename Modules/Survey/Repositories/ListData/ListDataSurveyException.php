<?php

namespace Modules\Survey\Repositories\ListData;

use MyCore\Repository\RepositoryExceptionAbstract;

/**
 * Class ListDataRepoException
 * @package Modules\Survey\Repositories\ListData
 * @author DaiDP
 * @since Feb, 2022
 */
class ListDataSurveyException extends RepositoryExceptionAbstract
{

    const SURVEY_PAUSE = 1;

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
            case self::SURVEY_PAUSE:
                return [
                    'message' => __('Khảo sát đang tạm ngưng hoạt động.'),
                    'title'   => __('Thông báo'),
                    'error_code' => 302
                ];
        }
    }
}
