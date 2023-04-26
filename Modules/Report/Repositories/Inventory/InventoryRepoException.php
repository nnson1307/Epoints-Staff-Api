<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 26/05/2021
 * Time: 16:54
 */

namespace Modules\Report\Repositories\Inventory;


use MyCore\Repository\RepositoryExceptionAbstract;

class InventoryRepoException extends RepositoryExceptionAbstract
{
    const GET_TOTAL_INVENTORY_FAILED = 0;
    const GET_DETAIL_INVENTORY_FAILED = 1;

    public function __construct(int $code = 0, string $message = "")
    {
        parent::__construct($message ? : $this->transMessage($code), $code);
    }

    protected function transMessage($code)
    {
        switch ($code)
        {
            case self::GET_TOTAL_INVENTORY_FAILED :
                return __('Lấy tổng tồn kho thất bại.');

            case self::GET_DETAIL_INVENTORY_FAILED :
                return __('Lấy tổng tồn kho thất bại.');

            default:
                return null;
        }
    }
}