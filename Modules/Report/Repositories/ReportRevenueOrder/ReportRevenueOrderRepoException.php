<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 26/05/2021
 * Time: 14:14
 */

namespace Modules\Report\Repositories\ReportRevenueOrder;


use MyCore\Repository\RepositoryExceptionAbstract;

class ReportRevenueOrderRepoException extends RepositoryExceptionAbstract
{
    const GET_TOTAL_REVENUE_FAILED = 0;
    const GET_BRANCH_FAILED = 1;
    const GET_DETAIL_REVENUE_FAILED = 2;

    public function __construct(int $code = 0, string $message = "")
    {
        parent::__construct($message ? : $this->transMessage($code), $code);
    }

    protected function transMessage($code)
    {
        switch ($code)
        {
            case self::GET_TOTAL_REVENUE_FAILED :
                return __('Lấy tổng doanh thu thất bại.');

            case self::GET_BRANCH_FAILED :
                return __('Lấy danh sách chi nhánh thất bại.');

            case self::GET_DETAIL_REVENUE_FAILED :
                return __('Lấy chi tiết doanh thu thất bại.');

            default:
                return null;
        }
    }
}