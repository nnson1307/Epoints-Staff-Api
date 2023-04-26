<?php


namespace Modules\Chat\Repositories;


use MyCore\Repository\RepositoryExceptionAbstract;

class ChatRepoException extends RepositoryExceptionAbstract
{
    const FILE_NOT_TYPE = 0;
    const MAX_FILE_SIZE = 1;
    const GET_UPLOAD_FILE_FAILED = 2;
    const GET_PROFILE_FAILED = 3;
    const GET_STAFF_CHAT_FAILED = 4;

    public function __construct(int $code = 0, string $message = "")
    {
        parent::__construct($message ?: $this->transMessage($code), $code);
    }

    protected function transMessage($code)
    {
        switch ($code) {

            case self::GET_UPLOAD_FILE_FAILED :
                return __('Upload file thất bại.');
            case self::MAX_FILE_SIZE :
                return __('FIle có kích thước quá lớn, vui lòng upload file có kích thước tối đa 20MB.');
            case self::FILE_NOT_TYPE :
                return __('Ảnh/file không được trống.');
            case self::GET_PROFILE_FAILED :
                return __('Lấy thông tin hồ sơ thất bại.');

            case self::GET_STAFF_CHAT_FAILED :
                return __('Lấy danh sách nhân viên có quyền chat thất bại.');

            default:
                return null;
        }
    }
}