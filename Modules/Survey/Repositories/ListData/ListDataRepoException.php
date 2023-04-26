<?php

namespace Modules\Survey\Repositories\ListData;

use MyCore\Repository\RepositoryExceptionAbstract;

/**
 * Class ListDataRepoException
 * @package Modules\Survey\Repositories\ListData
 * @author DaiDP
 * @since Feb, 2022
 */
class ListDataRepoException extends RepositoryExceptionAbstract
{
    const CODE_RETURN_HOME = 2;

    const SURVEY_NOT_FOUND = 1;
    const SURVEY_EXPIRED = 2;
    const OUTLET_NOT_ALLOW = 3;
    const USER_NO_LOYALTY = 4;
    const SURVEY_OUT_OF_QUOTA = 5;
    const SURVEY_PAUSE = 6;

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
        ],
        self::SURVEY_EXPIRED => [
            'message' => 'Khảo sát đã kết thúc hoặc không còn tồn tại. Quý khách vui lòng quay lại trang chủ để tham gia khảo sát khác!',
            'title'   => 'Khảo sát đã tạm ngừng',
            'error_code' => self::CODE_RETURN_HOME
        ],
        self::OUTLET_NOT_ALLOW => [
            'message' => 'Bạn không đủ điều kiện tham gia khảo sát này. Vui lòng quay lại trang chủ để tham gia khảo sát khác!',
            'title'   => 'Thông báo',
            'error_code' => self::CODE_RETURN_HOME
        ],
        self::USER_NO_LOYALTY => [
            'message' => 'Bạn không đủ điều kiện tham gia chương trình.',
            'title'   => 'Thông báo',
            'error_code' => self::CODE_RETURN_HOME
        ],
        self::SURVEY_OUT_OF_QUOTA => [
            'message' => 'Số lần tham gia khảo sát đã hết. Vui lòng quay lại trang chủ để tham gia khảo sát khác!',
            'title'   => 'Thông báo'
        ],
        self::SURVEY_PAUSE => [
            'message' => 'Khảo sát đang tạm ngưng hoạt động.',
            'title'   => 'Thông báo'
        ]
    ];
}
