<?php
namespace Modules\User\Repositories\Authen;

use MyCore\Repository\RepositoryExceptionAbstract;

/**
 * Class AuthenRepoException
 * @package Modules\User\Repositories\Authen
 * @author DaiDP
 * @since Aug, 2019
 */
class AuthenRepoException extends RepositoryExceptionAbstract
{

    const ERROR_TYPE = 0;
    const FILE_NOT_TYPE = 1;
    const MAX_FILE_SIZE = 2;
    const GET_UPLOAD_FILE_FAILED = 3;

    public function __construct(string $message = "", int $code = 0)
    {
        parent::__construct($message ?: $this->transMessage($code), $code);
    }

    protected function transMessage($code)
    {
        switch ($code) {
            case self::ERROR_TYPE :
                return __('Đã có lỗi, vui lòng thử lại sau');
            case self::GET_UPLOAD_FILE_FAILED :
                return __('Upload file thất bại.');
            case self::MAX_FILE_SIZE :
                return __('FIle có kích thước quá lớn, vui lòng upload file có kích thước tối đa 20MB.');
            case self::FILE_NOT_TYPE :
                return __('Ảnh/file không được trống.');

            default:
                return null;
        }
    }
}