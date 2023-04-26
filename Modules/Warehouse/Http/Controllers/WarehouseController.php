<?php

namespace Modules\Warehouse\Http\Controllers;


use Modules\Warehouse\Http\Requests\Warehouse\InventoryRequest;
use Modules\Warehouse\Repositories\Warehouse\WarehouseRepoException;
use Modules\Warehouse\Repositories\Warehouse\WarehouseRepoInterface;

class WarehouseController extends Controller
{
    protected $wareHouse;

    public function __construct(
        WarehouseRepoInterface $wareHouse
    ) {
        $this->wareHouse = $wareHouse;
    }

    /**
     * Lấy danh sách kho
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getWarehouse()
    {
        try {
            $data = $this->wareHouse->getWarehouse();

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (WarehouseRepoException $ex) {
            return $this->responseJson(CODE_ERROR, $ex->getMessage());
        }
    }

    /**
     * Lấy danh sách sản phẩm tồn kho
     *
     * @param InventoryRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getProductInventory(InventoryRequest $request)
    {
        try {
            $data = $this->wareHouse->getProductInventory($request->all());

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (WarehouseRepoException $ex) {
            return $this->responseJson(CODE_ERROR, $ex->getMessage());
        }
    }
}
