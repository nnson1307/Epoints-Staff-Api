<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 25/05/2021
 * Time: 16:55
 */

namespace Modules\Warehouse\Repositories\Warehouse;


use MyCore\Repository\RepositoryExceptionAbstract;

class WarehouseRepoException extends RepositoryExceptionAbstract
{
    const GET_LIST_WAREHOUSE_FAILED = 0;
    const GET_INVENTORY_FAILED = 1;

    public function __construct(int $code = 0, string $message = "")
    {
        parent::__construct($message ? : $this->transMessage($code), $code);
    }

    protected function transMessage($code)
    {
        switch ($code)
        {
            case self::GET_LIST_WAREHOUSE_FAILED :
                return __('Lấy danh sách kho thất bại.');

            case self::GET_INVENTORY_FAILED :
                return __('Lấy danh sách sản phẩm tồn kho thất bại.');

            default:
                return null;
        }
    }
}