<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 25/05/2021
 * Time: 16:55
 */

namespace Modules\Warehouse\Repositories\Warehouse;


interface WarehouseRepoInterface
{
    /**
     * Lấy danh sách kho
     *
     * @return mixed
     */
    public function getWarehouse();

    /**
     * Lấy danh sách sản phẩm tồn kho
     *
     * @param $input
     * @return mixed
     */
    public function getProductInventory($input);
}