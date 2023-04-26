<?php
/**
 * Created by PhpStorm
 * User: Mr Son
 * Date: 9/14/2020
 * Time: 10:31 AM
 */

namespace Modules\Promotion\Repositories\Promotion;


use MyCore\Repository\RepositoryExceptionAbstract;

class PromotionRepoException extends RepositoryExceptionAbstract
{
    const GET_PROMOTION_LIST_FAILED = 0;
    const GET_PROMOTION_DETAIL_FAILED = 1;

    public function __construct(int $code = 0, string $message = "")
    {
        parent::__construct($message ? : $this->transMessage($code), $code);
    }

    protected function transMessage($code)
    {
        switch ($code)
        {
            case self::GET_PROMOTION_LIST_FAILED :
                return __('Lấy danh sách CTKM thất bại.');

            case self::GET_PROMOTION_DETAIL_FAILED :
                return __('Lấy chi tiết CTKM thất bại.');

            default:
                return null;
        }
    }
}