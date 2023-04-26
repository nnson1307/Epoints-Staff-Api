<?php
namespace Modules\Survey\Repositories\Info;

use MyCore\Repository\RepositoryExceptionAbstract;

/**
 * Class SurveyInfoException
 * @package Modules\Survey\Repositories\Info
 * @author DaiDP
 * @since Feb, 2022
 */
class SurveyInfoException extends RepositoryExceptionAbstract
{
    const SURVEY_NOT_FOUND = 1;

    public function __construct($errorType, $preg = [], $errorCode = 1, $errorData = null)
    {
        $error = $this->error[$errorType];
        $this->title     = $error['title'];
        $this->errorData = $errorData;

        // Lấy mã lỗi
        if (isset($error['error_code'])) {
            $errorCode = $error['error_code'];
        }

        parent::__construct(__($error['message'], $preg), $errorCode);
    }

    protected $error = [
        self::SURVEY_NOT_FOUND => [
            'message' => 'Không tìm thấy khảo sát',
            'title'   => 'Thông báo'
        ]
    ];
}