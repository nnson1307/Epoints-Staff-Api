<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 26/05/2021
 * Time: 16:54
 */

namespace Modules\Report\Repositories\Inventory;


interface InventoryRepoInterface
{
    /**
     * Lấy tổng tồn kho
     *
     * @param $input
     * @return mixed
     */
    public function totalInventory($input);

    /**
     * DS sản phẩm tồn kho
     *
     * @param $input
     * @return mixed
     */
    public function detailInventory($input);

    /**
     * Lấy tổng tồn kho (new)
     *
     * @param $input
     * @return mixed
     */
    public function totalNewInventory($input);

    /**
     * DS sản phẩm tồn kho (new)
     *
     * @param $input
     * @return mixed
     */
    public function detailInventoryNew($input);
}