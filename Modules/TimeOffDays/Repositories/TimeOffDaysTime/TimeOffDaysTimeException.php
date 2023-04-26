<?php
/**
 * Created by PhpStorm
 * User: PhongDT
 */

namespace Modules\TimeOffDays\Repositories\TimeOffDaysTime;


use MyCore\Repository\RepositoryExceptionAbstract;

class TimeOffDaysTimeException extends RepositoryExceptionAbstract
{
    const GET_LIST_FAILED = 0;
    const GET_DETAIL_FAILED = 1;

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

            default:
                return null;
        }
    }
}