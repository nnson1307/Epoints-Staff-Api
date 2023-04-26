<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 25/05/2021
 * Time: 16:55
 */

namespace Modules\Warehouse\Repositories\Warehouse;


use Modules\Warehouse\Models\ProductInventoryTable;
use Modules\Warehouse\Models\WarehouseTable;
use MyCore\Repository\PagingTrait;

class WarehouseRepo implements WarehouseRepoInterface
{
    use PagingTrait;

    /**
     * Lấy danh sách kho
     *
     * @return mixed|void
     * @throws WarehouseRepoException
     */
    public function getWarehouse()
    {
        try {
            $mWarehouse = app()->get(WarehouseTable::class);
            //Lấy danh sách kho
            return $mWarehouse->getWarehouse();
        } catch (\Exception $exception) {
            throw new WarehouseRepoException(WarehouseRepoException::GET_LIST_WAREHOUSE_FAILED);
        }
    }

    /**
     * Lấy danh sách sản phẩm tồn kho
     *
     * @param $input
     * @return mixed|void
     * @throws WarehouseRepoException
     */
    public function getProductInventory($input)
    {
        try {
            $mProductInventory = app()->get(ProductInventoryTable::class);
            //Lấy danh sách sản phẩm tồn kho
            $data = $mProductInventory->getInventory($input);

            return $this->toPagingData($data);
        } catch (\Exception $e) {
            throw new WarehouseRepoException(WarehouseRepoException::GET_INVENTORY_FAILED, $e->getMessage());
        }
    }
}