<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 26/05/2021
 * Time: 16:54
 */

namespace Modules\Report\Repositories\Inventory;


use Illuminate\Support\Carbon;
use Modules\Report\Models\InventoryInputTable;
use Modules\Report\Models\InventoryOutputTable;
use Modules\Report\Models\ProductInventoryLogTable;
use Modules\Report\Models\ProductInventoryTable;
use MyCore\Repository\PagingTrait;

class InventoryRepo implements InventoryRepoInterface
{
    use PagingTrait;

    /**
     * Lấy tổng tồn kho
     *
     * @param $input
     * @return mixed|void
     * @throws InventoryRepoException
     */
    public function totalInventory($input)
    {
        try {
            $date = null;

            if (isset($input['date']) && $input['date'] != null) {
                $date = Carbon::createFromFormat('d/m/Y', $input['date'])->format('Y-m-d');
            }

            $mInventoryLog = app()->get(ProductInventoryLogTable::class);
            //Lấy tổng tồn kho
            return $mInventoryLog->getTotalInventory($input['warehouse_id'], $date);
        } catch (\Exception $exception) {
            throw new InventoryRepoException(InventoryRepoException::GET_TOTAL_INVENTORY_FAILED);
        }
    }

    /**
     * DS sản phẩm tồn kho
     *
     * @param $input
     * @return mixed|void
     * @throws InventoryRepoException
     */
    public function detailInventory($input)
    {
        try {
            if (isset($input['date']) && $input['date'] != null) {
                $input['date'] = Carbon::createFromFormat('d/m/Y', $input['date'])->format('Y-m-d');
            }

            $mInventoryLog = app()->get(ProductInventoryLogTable::class);
            //Lấy sản phẩm tồn kho
            $data = $mInventoryLog->getProductInventory($input);

            return $this->toPagingData($data);
        } catch (\Exception $e) {
            throw new InventoryRepoException(InventoryRepoException::GET_DETAIL_INVENTORY_FAILED, $e->getMessage());
        }
    }

    /**
     * Tổng tồn kho (new)
     *
     * @param $input
     * @return mixed
     * @throws InventoryRepoException
     */
    public function totalNewInventory($input)
    {
        try {
            $startDate = null;
            $endDate = null;

            if (isset($input['date']) && $input['date'] != null) {
                $arrInputDate = explode(" - ", $input['date']);
                $startDate = Carbon::createFromFormat('d/m/Y', $arrInputDate[0])->format('Y-m-d');
                $endDate = Carbon::createFromFormat('d/m/Y', $arrInputDate[1])->format('Y-m-d');
            }
            $mInventoryLog = app()->get(ProductInventoryLogTable::class);
            $mInventoryInput = app()->get(InventoryInputTable::class);
            $mInventoryOutput = app()->get(InventoryOutputTable::class);

            $totalBegin = 0;
            $totalBeginValue = 0;
            $totalInput = 0;
            $totalInputValue = 0;
            $totalOutput = 0;
            $totalOutputValue = 0;

            //Lấy tổng tồn kho đầu kỳ
            $dataTotalBegin = $mInventoryLog->getTotalInventoryBegin(
                $input['warehouse_id'],
                $startDate
            );

            if ($dataTotalBegin != null) {
                $totalBegin = $dataTotalBegin['total_inventory'];
                $totalBeginValue = $dataTotalBegin['total_inventory_value'];
            }
            //Lấy tổng nhập kho từ ngày -> ngày
            $dataTotalInput = $mInventoryInput->getTotalInputToDate(
                $input['warehouse_id'],
                $startDate .' '. '00:00:00',
                $endDate .' '. '23:59:59'
            );

            if ($dataTotalInput != null) {
                $totalInput = $dataTotalInput['quantity'];
                $totalInputValue = $dataTotalInput['total'];
            }
            //Lấy tổng xuất kho từ ngày -> ngày
            $dataTotalOutput = $mInventoryOutput->getTotalOutputToDate(
                $input['warehouse_id'],
                $startDate .' '. '00:00:00',
                $endDate .' '. '23:59:59'
            );

            if ($dataTotalOutput != null) {
                $totalOutput = $dataTotalOutput['quantity'];
                $totalOutputValue = $dataTotalOutput['total'];
            }

            return [
                'total_inventory' => intval($totalBegin + $totalInput - $totalOutput),
                'total_inventory_value' => floatval($totalBeginValue + $totalInputValue - $totalOutputValue)
            ];
        } catch (\Exception $e) {
            throw new InventoryRepoException(InventoryRepoException::GET_TOTAL_INVENTORY_FAILED, $e->getMessage());
        }
    }

    /**
     * DS sản phẩm tồn kho (new)
     *
     * @param $input
     * @return mixed|void
     * @throws InventoryRepoException
     */
    public function detailInventoryNew($input)
    {
        try {
            $startDate = null;
            $endDate = null;

            if (isset($input['date']) && $input['date'] != null) {
                $arrInputDate = explode(" - ", $input['date']);
                $startDate = Carbon::createFromFormat('d/m/Y', $arrInputDate[0])->format('Y-m-d');
                $endDate = Carbon::createFromFormat('d/m/Y', $arrInputDate[1])->format('Y-m-d');
            }

            $mProductInventory = app()->get(ProductInventoryTable::class);
            //Lấy ds sản phẩm tồn kho
            $getProduct = $mProductInventory->getProductInventory([
                'page' => isset($input['page']) ? $input['page'] : 1,
            ]);

            if (count($getProduct->items()) > 0) {
                $mInventoryLog = app()->get(ProductInventoryLogTable::class);
                $mInventoryInput = app()->get(InventoryInputTable::class);
                $mInventoryOutput = app()->get(InventoryOutputTable::class);

                foreach ($getProduct->items() as $v) {
                    $totalBegin = 0;
                    $totalBeginValue = 0;
                    $totalInput = 0;
                    $totalInputValue = 0;
                    $totalOutput = 0;
                    $totalOutputValue = 0;

                    //Lấy tồn đầu kỳ theo ngày bắt đầu
                    $dataTotalBegin = $mInventoryLog->getInventoryLog(
                        $v['product_code'],
                        $input['warehouse_id'],
                        $startDate
                    );

                    if ($dataTotalBegin != null) {
                        $totalBegin = $dataTotalBegin['total_inventory'];
                        $totalBeginValue = $dataTotalBegin['total_inventory_value'];
                    }
                    //Lấy tổng nhập kho từ ngày -> ngày
                    $dataTotalInput = $mInventoryInput->getInputToDate(
                        $v['product_code'],
                        $input['warehouse_id'],
                        $startDate. ' '. '00:00:00',
                        $endDate. ' '. '23:59:59'
                    );

                    if ($dataTotalInput != null) {
                        $totalInput = $dataTotalInput['quantity'];
                        $totalInputValue = $dataTotalInput['total'];
                    }
                    //Lấy tổng xuất kho từ ngày -> ngày
                    $dataTotalOutput = $mInventoryOutput->getOutputToDate(
                        $v['product_code'],
                        $input['warehouse_id'],
                        $startDate. ' '. '00:00:00',
                        $endDate. ' '. '23:59:59'
                    );

                    if ($dataTotalOutput != null) {
                        $totalOutput = $dataTotalOutput['quantity'];
                        $totalOutputValue = $dataTotalOutput['total'];
                    }

                    $v['inventory'] = intval($totalBegin + $totalInput - $totalOutput);
                    $v['inventory_value'] = floatval($totalBeginValue + $totalInputValue - $totalOutputValue);
                }
            }

            return $this->toPagingData($getProduct);
        } catch (\Exception $e) {
            throw new InventoryRepoException(InventoryRepoException::GET_DETAIL_INVENTORY_FAILED, $e->getMessage());
        }
    }
}