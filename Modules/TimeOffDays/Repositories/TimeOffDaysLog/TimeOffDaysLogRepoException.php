<?php
/**
 * Created by PhpStorm
 * User: PhongDT
 */

namespace Modules\TimeOffDays\Repositories\TimeOffDaysLog;


use MyCore\Repository\RepositoryExceptionAbstract;

class TimeOffDaysLogRepoException extends RepositoryExceptionAbstract
{
    const GET_LIST_FAILED = 0;
    const GET_DETAIL_FAILED = 1;
    const CREATE_FAILED = 1;

    public function __construct(int $code = 0, string $message = "")
    {
        parent::__construct($message ? : $this->transMessage($code), $code);
    }

    protected function transMessage($code)
    {
        switch ($code)
        {
            case self::GET_LIST_FAILED :
                return __('Lấy danh sách thất bại.');

            case self::GET_DETAIL_FAILED :
                return __('Lấy chi tiết thất bại.');

            case self::CREATE_FAILED :
                return __('Thêm thất bại.');

            default:
                return null;
        }
    }
}