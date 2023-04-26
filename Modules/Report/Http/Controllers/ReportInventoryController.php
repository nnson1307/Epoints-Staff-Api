<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 26/05/2021
 * Time: 14:13
 */

namespace Modules\Report\Http\Controllers;


use Modules\Report\Http\Requests\Inventory\DetailRequest;
use Modules\Report\Http\Requests\Inventory\TotalRequest;
use Modules\Report\Repositories\Inventory\InventoryRepoException;
use Modules\Report\Repositories\Inventory\InventoryRepoInterface;

class ReportInventoryController extends Controller
{
    protected $inventory;

    public function __construct(
        InventoryRepoInterface $inventory
    ) {
        $this->inventory = $inventory;
    }

    /**
     * Lấy tổng tồn kho
     *
     * @param TotalRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function totalInventory(TotalRequest $request)
    {
        try {
            $data = $this->inventory->totalInventory($request->all());

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (InventoryRepoException $ex) {
            return $this->responseJson(CODE_ERROR, $ex->getMessage());
        }
    }

    /**
     * DS sản phẩm tồn kho
     *
     * @param DetailRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function listDetailInventory(DetailRequest $request)
    {
        try {
            $data = $this->inventory->detailInventory($request->all());

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (InventoryRepoException $ex) {
            return $this->responseJson(CODE_ERROR, $ex->getMessage());
        }
    }

    /**
     * Lấy tổng tồn kho (new)
     *
     * @param TotalRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function totalInventoryNew(TotalRequest $request)
    {
        try {
            $data = $this->inventory->totalNewInventory($request->all());

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (InventoryRepoException $ex) {
            return $this->responseJson(CODE_ERROR, $ex->getMessage());
        }
    }

    /**
     * DS sản phẩm tồn kho (new)
     *
     * @param DetailRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function listDetailInventoryNew(DetailRequest $request)
    {
        try {
            $data = $this->inventory->detailInventoryNew($request->all());

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (InventoryRepoException $ex) {
            return $this->responseJson(CODE_ERROR, $ex->getMessage());
        }
    }
}