<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 27/05/2021
 * Time: 10:49
 */

namespace Modules\Report\Repositories\StaffCommission;

use MyCore\Repository\RepositoryExceptionAbstract;

class StaffCommissionRepoException extends RepositoryExceptionAbstract
{
    const GET_TOTAL_COMMISSION_FAILED = 0;
    const GET_DETAIL_COMMISSION_FAILED = 0;

    public function __construct(int $code = 0, string $message = "")
    {
        parent::__construct($message ? : $this->transMessage($code), $code);
    }

    protected function transMessage($code)
    {
        switch ($code)
        {
            case self::GET_TOTAL_COMMISSION_FAILED :
                return __('Lấy tổng hoa hồng thất bại.');

            case self::GET_DETAIL_COMMISSION_FAILED :
                return __('Lấy chi tiết hoa hồng thất bại.');

            default:
                return null;
        }
    }
}