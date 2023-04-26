<?php
namespace Modules\User\Repositories\UploadAvatar;

use MyCore\Repository\RepositoryExceptionAbstract;

/**
 * Class UploadAvatarRepoException
 * @package Modules\User\Repositories\UploadAvatar
 * @author todh
 * @since April, 2023
 */
class UploadAvatarRepoException extends RepositoryExceptionAbstract
{

    const ERROR_TYPE = 0;
    const GET_UPLOAD_FILE_FAILED = 1;

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
                return __('Upload ảnh thất bại.');
            default:
                return null;
        }
    }
}