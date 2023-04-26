<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 27/09/2022
 * Time: 14:22
 */

namespace Modules\Warranty\Repositories\Maintenance;


use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Modules\Warranty\Models\MaintenanceCostTable;
use Modules\Warranty\Models\MaintenanceCostTypeTable;
use Modules\Warranty\Models\MaintenanceImageTable;
use Modules\Warranty\Models\MaintenanceTable;
use Modules\Warranty\Models\ProductChildTable;
use Modules\Warranty\Models\ReceiptDetailTable;
use Modules\Warranty\Models\ReceiptTable;
use Modules\Warranty\Models\ServiceCardTable;
use Modules\Warranty\Models\ServiceTable;
use Modules\Warranty\Models\WarrantyCardTable;
use MyCore\Repository\PagingTrait;

class MaintenanceRepo implements MaintenanceRepoInterface
{
    use PagingTrait;

    const FINISH = "finish";
    CONST CANCEL = "cancel";
    const MAINTENANCE = "maintenance";
    const ACTIVE_CARD = "actived";
    const FINISH_CARD = "finish";

    /**
     * DS phiếu bảo trì
     *
     * @param $input
     * @return mixed|void
     * @throws MaintenanceRepoException
     */
    public function getMaintenance($input)
    {
        try {
            $mMaintenance = app()->get(MaintenanceTable::class);

            //Lấy ds phiếu bảo trì
            $getMaintenance = $mMaintenance->getList($input);

            if (count($getMaintenance->items()) > 0) {
                $mProductChild = app()->get(ProductChildTable::class);
                $mService = app()->get(ServiceTable::class);
                $mServiceCard = app()->get(ServiceCardTable::class);
                $mReceiptDetail = app()->get(ReceiptDetailTable::class);

                foreach ($getMaintenance->items() as $v) {
                    $v['status_name'] = $this->_setStatusName($v['status']);
                    $v['status_color'] = $this->_setStatusColor($v['status']);

                    if ($v['object_type'] == 'product') {
                        $obj = $mProductChild->getProduct($v['object_code']);
                        $v['object_name'] = $obj['product_child_name'];
                    } elseif ($v['object_type'] == 'service') {
                        $obj = $mService->getService($v['object_code']);
                        $v['object_name'] = $obj['service_name'];
                    } elseif ($v['object_type'] == 'service_card') {
                        $obj = $mServiceCard->getServiceCard($v['object_code']);
                        $v['object_name'] = $obj['name'];
                    }

                    $isUpdate = 0;

                    if (!in_array($v['status'], [self::CANCEL, self::FINISH])) {
                        $isUpdate = 1;
                    }
                    //Cờ cập nhật
                    $v['is_update'] = $isUpdate;

                    //Lấy thông tin thanh toán
                    $getReceiptDetail = $mReceiptDetail->getDetailMaintenance($v['maintenance_id']);

                    $totalReceipt = 0;

                    if (count($getReceiptDetail) > 0) {
                        foreach ($getReceiptDetail as $v1) {
                            $totalReceipt += $v1['amount'];
                        }
                    }

                    $isPayment = 0;

                    if ($v['total_amount_pay'] > 0 && $v['total_amount_pay'] > $totalReceipt && $v['status'] != self::CANCEL) {
                        $isPayment = 1;
                    }

                    //Cờ thanh toán
                    $v['is_payment'] = $isPayment;
                    $v['total_amount_receipt'] = $totalReceipt;
                }
            }

            return $this->toPagingData($getMaintenance);
        } catch (\Exception $e) {
            throw new MaintenanceRepoException(MaintenanceRepoException::GET_LIST_MAINTENANCE_FAILED, $e->getMessage());
        }
    }

    /**
     * Lấy DS phiếu bảo hành của khách hàng
     *
     * @param $input
     * @return mixed|void
     * @throws MaintenanceRepoException
     */
    public function getWarrantyCardCustomer($input)
    {
        try {
            $mWarrantyCard = app()->get(WarrantyCardTable::class);

            //DS phiếu bảo hành của KH
            $getCard = $mWarrantyCard->getWarrantyCardCustomer($input);

            if (count($getCard->items()) > 0) {
                $mProductChild = app()->get(ProductChildTable::class);
                $mService = app()->get(ServiceTable::class);
                $mServiceCard = app()->get(ServiceCardTable::class);

                foreach ($getCard->items() as $v) {
                    if ($v['object_type'] == 'product') {
                        $obj = $mProductChild->getProduct($v['object_code']);
                        $v['object_name'] = $obj['product_child_name'];
                    } elseif ($v['object_type'] == 'service') {
                        $obj = $mService->getService($v['object_code']);
                        $v['object_name'] = $obj['service_name'];
                    } elseif ($v['object_type'] == 'service_card') {
                        $obj = $mServiceCard->getServiceCard($v['object_code']);
                        $v['object_name'] = $obj['name'];
                    }
                    //Giá trị tối đa được bảo hành
                    $maxPrice = floatval($v['warranty_value']);
                    //Tính giá trị dc bảo hành
                    $warrantyValueApply = floatval(($v['object_price'] / 100) * $v['warranty_percent']);

                    if ($warrantyValueApply > $maxPrice) {
                        $warrantyValueApply = $maxPrice;
                    }

                    $v['warranty_value_apply'] = $warrantyValueApply;

                    //Lấy tên date_expired
                    $dateExpiredName = null;

                    if (in_array($v['status'], [self::ACTIVE_CARD, self::FINISH_CARD])) {
                        if ($v['date_expired'] == null) {
                            $dateExpiredName = __('Không giới hạn');
                        } else {
                            $dateExpiredName = Carbon::parse($v['date_expired'])->format('d/m/Y');
                        }
                    }

                    $v['date_expired_name'] = $dateExpiredName;
                }
            }

            return $this->toPagingData($getCard);
        } catch (\Exception $e) {
            throw new MaintenanceRepoException(MaintenanceRepoException::GET_WARRANTY_CARD_CUSTOMER_FAILED, $e->getMessage());
        }
    }

    /**
     * Lấy chi phí phát sinh
     *
     * @return mixed|void
     * @throws MaintenanceRepoException
     */
    public function getCostType()
    {
        try {
            $mCostType = app()->get(MaintenanceCostTypeTable::class);

            //Lấy option chi phí phát sinh
            return $mCostType->getCostType();
        } catch (\Exception $e) {
            throw new MaintenanceRepoException(MaintenanceRepoException::GET_COST_TYPE_FAILED);
        }
    }

    /**
     * Thêm phiếu bảo trì
     *
     * @param $input
     * @return mixed|void
     * @throws MaintenanceRepoException
     */
    public function store($input)
    {
        DB::beginTransaction();
        try {
            $dateEstimateDelivery = Carbon::parse($input['date_estimate_delivery'])->format('Y-m-d H:i');
            $now = Carbon::now()->format('Y-m-d H:i');

            //Check start time > end time
            if ($now >= $dateEstimateDelivery) {
                throw new MaintenanceRepoException(MaintenanceRepoException::CREATE_MAINTENANCE_FAILED, __('Ngày trả hàng dự kiến phải lớn hơn thời gian hiện tại'));
            }

            $mMaintenance = app()->get(MaintenanceTable::class);

            $status = isset($input['status']) ? $input['status'] : 'new';

            //Thêm phiếu bảo trì
            $maintenanceId = $mMaintenance->add([
                "customer_code" => $input['customer_code'],
                "warranty_code" => $input['warranty_code'],
                "maintenance_cost" => $input['maintenance_cost'],
                "warranty_value" => $input['warranty_value'],
                "insurance_pay" => $input['insurance_pay'],
                "amount_pay" => $input['amount_pay'],
                "total_amount_pay" => $input['total_amount_pay'],
                "staff_id" => $input['staff_id'],
                "object_type" => $input['object_type'],
                "object_type_id" => $input['object_type_id'],
                "object_code" => $input['object_code'],
                "object_serial" => $input['object_serial'],
                "object_status" => $input['object_status'],
                "maintenance_content" => $input['maintenance_content'],
                "date_estimate_delivery" => $dateEstimateDelivery,
                "status" => $status,
                "created_by" => Auth()->id(),
                "updated_by" => Auth()->id()
            ]);

            $maintenanceCode = 'PBT_' . date('dmY') . sprintf("%02d", $maintenanceId);
            //Cập nhật mã phiếu bảo trì
            $mMaintenance->edit([
                'maintenance_code' => $maintenanceCode
            ], $maintenanceId);

            $arrInsertImage = [];
            $arrInsertCost = [];

            if (isset($input['list_image']) && count($input['list_image'])) {
                foreach ($input['list_image'] as $v) {
                    $arrInsertImage [] = [
                        "maintenance_code" => $maintenanceCode,
                        "type" => $v['type'],
                        "link" => $v['link'],
                        "created_at" => Carbon::now()->format("Y-m-d H:i:s"),
                        "updated_at" => Carbon::now()->format("Y-m-d H:i:s")
                    ];
                }
            }

            if (isset($input['cost_type']) && count($input['cost_type']) > 0) {
                foreach ($input['cost_type'] as $v) {
                    $arrInsertCost [] = [
                        "maintenance_id" => $maintenanceId,
                        "maintenance_cost_type" => $v['maintenance_cost_type_id'],
                        "cost" => $v['cost'],
                        "created_by" => Auth()->id(),
                        "updated_by" => Auth()->id(),
                        "created_at" => Carbon::now()->format("Y-m-d H:i:s"),
                        "updated_at" => Carbon::now()->format("Y-m-d H:i:s")
                    ];
                }
            }

            //Insert hình ảnh trước, sau khi bảo trì
            $mMaintenanceImage = app()->get(MaintenanceImageTable::class);
            $mMaintenanceImage->insert($arrInsertImage);

            //Insert chi phí phát sinh
            $mMaintenanceCost = app()->get(MaintenanceCostTable::class);
            $mMaintenanceCost->insert($arrInsertCost);

            if ($input['status'] == self::FINISH && $input['warranty_code'] != null) {
                //Xử lý cập nhật hoàn tất phiếu bảo hành
                $updateFinish = $this->_updateFinishWarrantyCard($input['warranty_code'], $maintenanceCode);

                if ($updateFinish['error'] == true) {
                    throw new MaintenanceRepoException(MaintenanceRepoException::CREATE_MAINTENANCE_FAILED, $updateFinish['message']);

                }
            }

            DB::commit();

            return [
                "maintenance_code" => $maintenanceCode
            ];
        } catch (\Exception $e) {
            DB::rollback();
            throw new MaintenanceRepoException(MaintenanceRepoException::CREATE_MAINTENANCE_FAILED, $e->getMessage());
        }
    }

    /**
     * Xử lý cập nhật hoàn thành phiếu bảo hành
     *
     * @param $warrantyCardCode
     * @param $maintenanceCode
     * @return array
     */
    private function _updateFinishWarrantyCard($warrantyCardCode, $maintenanceCode)
    {
        $mWarrantyCard = app()->get(WarrantyCardTable::class);
        $mMaintenance = app()->get(MaintenanceTable::class);

        //Lấy thông tin của phiếu bảo hành
        $getWarranty = $mWarrantyCard->getInfo($warrantyCardCode);
        //Lấy số phiếu bảo trì đã hoàn tất của phiếu bảo hành
        $getFinish = $mMaintenance->getMaintenanceFinish($warrantyCardCode, $maintenanceCode);

        if ($getWarranty['quota'] != 0 && $getWarranty['quota'] <= count($getFinish)) {
            return [
                'error' => true,
                'message' => __('Đã vượt quá số lần sử dụng của phiếu bảo hành')
            ];
        } else if ($getWarranty['quota'] != 0 && $getWarranty['quota'] == count($getFinish) + 1) {
            //Hoàn thành phiếu bảo trì thì check quota phiếu bảo hành để update status
            $mWarrantyCard->editByCode([
                'status' => self::FINISH
            ], $warrantyCardCode);
        }

        return [
            'error' => false
        ];
    }

    /**
     * Chi tiết phiếu bảo trì
     *
     * @param $input
     * @return mixed|void
     * @throws MaintenanceRepoException
     */
    public function show($input)
    {
        try {
            $mMaintenance = app()->get(MaintenanceTable::class);
            $mMaintenanceCost = app()->get(MaintenanceCostTable::class);
            $mMaintenanceImage = app()->get(MaintenanceImageTable::class);
            $mProductChild = app()->get(ProductChildTable::class);
            $mService = app()->get(ServiceTable::class);
            $mServiceCard = app()->get(ServiceCardTable::class);
            $mReceiptDetail = app()->get(ReceiptDetailTable::class);

            //Lấy thông tin phiếu bảo trì
            $getInfo = $mMaintenance->getInfo($input['maintenance_code']);

            $getInfo['status_name'] = $this->_setStatusName($getInfo['status']);
            $getInfo['status_color'] = $this->_setStatusColor($getInfo['status']);
            $getInfo['status_update'] = $this->_setStatusUpdate($getInfo['status']);

            if ($getInfo['object_type'] == 'product') {
                $obj = $mProductChild->getProduct($getInfo['object_code']);
                $getInfo['object_name'] = $obj['product_child_name'];
            } elseif ($getInfo['object_type'] == 'service') {
                $obj = $mService->getService($getInfo['object_code']);
                $getInfo['object_name'] = $obj['service_name'];
            } elseif ($getInfo['object_type'] == 'service_card') {
                $obj = $mServiceCard->getServiceCard($getInfo['object_code']);
                $getInfo['object_name'] = $obj['name'];
            }

            $getInfo['full_address'] = $getInfo['address'];

            if ($getInfo['ward_name'] != null) {
                $getInfo['full_address'] .=  ', ' . $getInfo['ward_name'];
            }

            if ($getInfo['district_name'] != null) {
                $getInfo['full_address'] .=  ', ' . $getInfo['district_name'];
            }

            if ($getInfo['province_name'] != null) {
                $getInfo['full_address'] .=  ', ' . $getInfo['province_name'];
            }

            //Lấy chi phí phát sinh
            $getInfo['list_cost'] = $mMaintenanceCost->getCost($getInfo['maintenance_id']);
            //Lấy hình ảnh bảo trì
            $getInfo['list_image'] = $mMaintenanceImage->getImage($input['maintenance_code']);
            //Lấy thông tin thanh toán
            $getReceiptDetail = $mReceiptDetail->getDetailMaintenance($getInfo['maintenance_id']);

            $totalReceipt = 0;

            if (count($getReceiptDetail) > 0) {
                foreach ($getReceiptDetail as $v) {
                    $totalReceipt += $v['amount'];
                }
            }

            $getInfo['receipt_detail'] = $getReceiptDetail;
            $getInfo['total_amount_receipt'] = $totalReceipt;

            $isUpdate = 0;

            if (!in_array($getInfo['status'], [self::CANCEL, self::FINISH])) {
                $isUpdate = 1;
            }
            //Cờ cập nhật
            $getInfo['is_update'] = $isUpdate;

            $isPayment = 0;

            if ($getInfo['total_amount_pay'] > 0 && $getInfo['total_amount_pay'] > $totalReceipt && $getInfo['status'] != self::CANCEL) {
                $isPayment = 1;
            }

            //Cờ thanh toán
            $getInfo['is_payment'] = $isPayment;

            return $getInfo;
        } catch (\Exception $e) {
            throw new MaintenanceRepoException(MaintenanceRepoException::GET_DETAIL_FAILED, $e->getMessage() . $e->getLine());
        }
    }

    /**
     * Lấy trạng thái được cập nhật
     *
     * @param $status
     * @return array
     */
    private function _setStatusUpdate($status)
    {
        $data = [
            [
                'status' => 'new',
                'status_name' => $this->_setStatusName('new'),
                'status_color' => $this->_setStatusColor('new')
            ],
            [
                'status' => 'received',
                'status_name' => $this->_setStatusName('received'),
                'status_color' => $this->_setStatusColor('received')
            ],
            [
                'status' => 'processing',
                'status_name' => $this->_setStatusName('processing'),
                'status_color' => $this->_setStatusColor('processing')
            ],
            [
                'status' => 'ready_delivery',
                'status_name' => $this->_setStatusName('ready_delivery'),
                'status_color' => $this->_setStatusColor('ready_delivery')
            ],
            [
                'status' => 'finish',
                'status_name' => $this->_setStatusName('finish'),
                'status_color' => $this->_setStatusColor('finish')
            ],
            [
                'status' => 'cancel',
                'status_name' => $this->_setStatusName('cancel'),
                'status_color' => $this->_setStatusColor('cancel')
            ],
        ];

        switch ($status) {
            case 'new':
                break;
            case 'received':
                unset($data[0], $data[1]);
                break;
            case 'processing':
                unset($data[0], $data[1], $data[2]);
                break;
            case 'ready_delivery':
                unset($data[0], $data[1], $data[2], $data[3]);
                break;
            case 'finish':
                unset($data[0], $data[1], $data[2], $data[3], $data[5]);
                break;
            case 'cancel':
                unset($data[0], $data[1], $data[2], $data[3], $data[4]);
                break;
        }

        return array_values($data);
    }

    /**
     * Lấy tên trạng thái phiếu bảo trì
     *
     * @param $status
     * @return array|null|string
     */
    private function _setStatusName($status)
    {
        $statusName = '';

        switch ($status) {
            case 'new':
                $statusName = __('Mới');
                break;
            case 'received':
                $statusName = __('Đã nhận hàng');
                break;
            case 'processing':
                $statusName = __('Đang xử lý');
                break;
            case 'ready_delivery':
                $statusName = __('Sẵn sàng trả hàng');
                break;
            case 'finish':
                $statusName = __('Hoàn tất');
                break;
            case 'cancel':
                $statusName = __('Đã huỷ');
                break;
        }

        return $statusName;
    }

    /**
     * Lấy màu trạng thái phiếu bảo trì
     *
     * @param $status
     * @return array|null|string
     */
    private function _setStatusColor($status)
    {
        $statusColor = '';

        switch ($status) {
            case 'new':
                $statusColor = "#00A650";
                break;
            case 'received':
                $statusColor = "#00A650";
                break;
            case 'processing':
                $statusColor = "#36A3F7";
                break;
            case 'ready_delivery':
                $statusColor = "#36A3F7";
                break;
            case 'finish':
                $statusColor = "#5867DD";
                break;
            case 'cancel':
                $statusColor = "#ED1B24";
                break;
        }

        return $statusColor;
    }

    /**
     * Chỉnh sửa phiếu bảo trì
     *
     * @param $input
     * @return array|mixed
     * @throws MaintenanceRepoException
     */
    public function update($input)
    {
        DB::beginTransaction();
        try {
            $mMaintenance = app()->get(MaintenanceTable::class);

            $maintenanceCode = $input['maintenance_code'];
            $maintenanceId = $input['maintenance_id'];

            //Chỉnh sửa phiếu bảo trì
            $mMaintenance->editByCode([
                "customer_code" => $input['customer_code'],
                "warranty_code" => $input['warranty_code'],
                "maintenance_cost" => $input['maintenance_cost'],
                "warranty_value" => $input['warranty_value'],
                "insurance_pay" => $input['insurance_pay'],
                "amount_pay" => $input['amount_pay'],
                "total_amount_pay" => $input['total_amount_pay'],
                "staff_id" => $input['staff_id'],
                "object_type" => $input['object_type'],
                "object_type_id" => $input['object_type_id'],
                "object_code" => $input['object_code'],
                "object_serial" => $input['object_serial'],
                "object_status" => $input['object_status'],
                "maintenance_content" => $input['maintenance_content'],
                "date_estimate_delivery" => $input['date_estimate_delivery'],
                "status" => $input['status'],
                "updated_by" => Auth()->id()
            ], $maintenanceCode);

            $arrInsertImage = [];
            $arrInsertCost = [];

            if (isset($input['list_image']) && count($input['list_image'])) {
                foreach ($input['list_image'] as $v) {
                    $arrInsertImage [] = [
                        "maintenance_code" => $maintenanceCode,
                        "type" => $v['type'],
                        "link" => $v['link'],
                        "created_at" => Carbon::now()->format("Y-m-d H:i:s"),
                        "updated_at" => Carbon::now()->format("Y-m-d H:i:s")
                    ];
                }
            }

            if (isset($input['cost_type']) && count($input['cost_type']) > 0) {
                foreach ($input['cost_type'] as $v) {
                    $arrInsertCost [] = [
                        "maintenance_id" => $maintenanceId,
                        "maintenance_cost_type" => $v['maintenance_cost_type_id'],
                        "cost" => $v['cost'],
                        "created_by" => Auth()->id(),
                        "updated_by" => Auth()->id(),
                        "created_at" => Carbon::now()->format("Y-m-d H:i:s"),
                        "updated_at" => Carbon::now()->format("Y-m-d H:i:s")
                    ];
                }
            }

            $mMaintenanceImage = app()->get(MaintenanceImageTable::class);
            //Xóa tất cả hình ảnh
            $mMaintenanceImage->removeImage($maintenanceCode);
            //Insert hình ảnh trước, sau khi bảo trì
            $mMaintenanceImage->insert($arrInsertImage);


            $mMaintenanceCost = app()->get(MaintenanceCostTable::class);
            //Xóa tất cả chi phí phát sinh
            $mMaintenanceCost->removeCost($maintenanceId);
            //Insert chi phí phát sinh
            $mMaintenanceCost->insert($arrInsertCost);

            if ($input['status'] == self::FINISH && $input['warranty_code'] != null) {
                //Xử lý cập nhật hoàn tất phiếu bảo hành
                $updateFinish = $this->_updateFinishWarrantyCard($input['warranty_code'], $maintenanceCode);

                if ($updateFinish['error'] == true) {
                    throw new MaintenanceRepoException(MaintenanceRepoException::CREATE_MAINTENANCE_FAILED, $updateFinish['message']);

                }
            }

            if ($input['status'] == self::CANCEL) {
                $mReceipt = app()->get(ReceiptTable::class);
                //Hủy phiếu bảo trì hủy phiếu thanh toán
                $mReceipt->cancelReceipt(self::MAINTENANCE, $maintenanceId);
            }

            DB::commit();

            return [
                "maintenance_code" => $input['maintenance_code']
            ];
        } catch (\Exception $e) {
            DB::rollback();
            throw new MaintenanceRepoException(MaintenanceRepoException::UPDATE_MAINTENANCE_FAILED, $e->getMessage());
        }
    }

    /**
     * Thanh toán phiếu bảo trì
     *
     * @param $input
     * @return mixed|void
     * @throws MaintenanceRepoException
     */
    public function receiptMaintenance($input)
    {
        DB::beginTransaction();
        try {
            $amountReceipt = $input['amount_paid'];

            if ($input['amount_paid'] > $input['total_money']) {
                $amountReceipt = $input['total_money'];
            }

            $mReceipt = app()->get(ReceiptTable::class);
            $mReceiptDetail = app()->get(ReceiptDetailTable::class);

            //Thêm phiếu thu
            $receiptId = $mReceipt->add([
                'customer_id' => $input['customer_id'],
                'staff_id' => Auth()->id(),
                'object_type' => self::MAINTENANCE,
                'object_id' => $input['maintenance_id'],
                'total_money' => $input['total_money'],
                'status' => 'paid',
                'amount' => $input['total_money'],
                'amount_paid' => $amountReceipt,
                'amount_return' => $input['amount_return'],
                'note' => $input['note'],
                'receipt_type_code' => 'RTC_MAINTENANCE',
                'object_accounting_id' => $input['maintenance_id'], // maintenance id
                'created_by' => Auth()->id(),
                'updated_by' => Auth()->id(),
            ]);

            $receiptCode = 'TT_' . date('dmY') . sprintf("%02d", $receiptId);
            //Update receipt_code
            $mReceipt->edit([
                'receipt_code' => $receiptCode
            ], $receiptId);

            $arrReceiptDetail = [];

            if (isset($input['payment_method']) && count($input['payment_method']) > 0) {
                foreach ($input['payment_method'] as $v) {
                    $arrReceiptDetail [] = [
                        'receipt_id' => $receiptId,
                        'cashier_id' => Auth()->id(),
                        'amount' => $v['money'],
                        'payment_method_code' => $v['payment_method_code'],
                        'created_by' => Auth()->id(),
                        'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                        'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
                    ];
                }
            }

            //Insert chi tiết phiếu thu
            $mReceiptDetail->insert($arrReceiptDetail);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            throw new MaintenanceRepoException(MaintenanceRepoException::RECEIPT_MAINTENANCE_FAILED, $e->getMessage());
        }
    }

    /**
     * Lấy ds phiếu bảo trì
     *
     * @return mixed|void
     * @throws MaintenanceRepoException
     */
    public function getListStatus()
    {
        try {
            return [
                [
                    'status' => 'new',
                    'status_name' => $this->_setStatusName('new'),
                    'status_color' => $this->_setStatusColor('new')
                ],
                [
                    'status' => 'received',
                    'status_name' => $this->_setStatusName('received'),
                    'status_color' => $this->_setStatusColor('received')
                ],
                [
                    'status' => 'processing',
                    'status_name' => $this->_setStatusName('processing'),
                    'status_color' => $this->_setStatusColor('processing')
                ],
                [
                    'status' => 'ready_delivery',
                    'status_name' => $this->_setStatusName('ready_delivery'),
                    'status_color' => $this->_setStatusColor('ready_delivery')
                ],
                [
                    'status' => 'finish',
                    'status_name' => $this->_setStatusName('finish'),
                    'status_color' => $this->_setStatusColor('finish')
                ],
                [
                    'status' => 'cancel',
                    'status_name' => $this->_setStatusName('cancel'),
                    'status_color' => $this->_setStatusColor('cancel')
                ],
            ];
        } catch (\Exception $e) {
            throw new MaintenanceRepoException(MaintenanceRepoException::GET_LIST_STATUS_FAILED);
        }
    }
}