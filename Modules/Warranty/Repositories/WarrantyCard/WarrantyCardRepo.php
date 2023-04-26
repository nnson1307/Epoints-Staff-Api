<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 27/09/2022
 * Time: 09:31
 */

namespace Modules\Warranty\Repositories\WarrantyCard;


use App\Jobs\FunctionSendNotify;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Modules\Warranty\Models\ProductChildTable;
use Modules\Warranty\Models\ServiceCardTable;
use Modules\Warranty\Models\ServiceTable;
use Modules\Warranty\Models\WarrantyCardImageTable;
use Modules\Warranty\Models\WarrantyCardTable;
use Modules\Warranty\Models\WarrantyPackedTable;
use MyCore\Repository\PagingTrait;

class WarrantyCardRepo implements WarrantyCardRepoInterface
{
    use PagingTrait;

    const ACTIVE_CARD = "actived";
    const FINISH_CARD = "finish";

    /**
     * Lấy DS gói bảo hành
     *
     * @return mixed|void
     * @throws WarrantyCardRepoException
     */
    public function getPackage()
    {
        try {
            $mPacked = app()->get(WarrantyPackedTable::class);

            //Lấy option gói bào hành
            return $mPacked->getOptionPacked();
        } catch (\Exception $e) {
            throw new WarrantyCardRepoException(WarrantyCardRepoException::GET_LIST_PACKAGE_FAILED);
        }
    }

    /**
     * Lấy DS thẻ bảo hành
     *
     * @param $input
     * @return mixed|void
     * @throws WarrantyCardRepoException
     */
    public function getWarrantyCard($input)
    {
        try {
            $mWarrantyCard = app()->get(WarrantyCardTable::class);

            //Lấy DS thẻ bảo hảnh
            $getCard = $mWarrantyCard->getList($input);

            if (count($getCard->items()) > 0) {
                $mProductChild = app()->get(ProductChildTable::class);
                $mService = app()->get(ServiceTable::class);
                $mServiceCard = app()->get(ServiceCardTable::class);

                foreach ($getCard->items() as $v) {
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

                    $isUpdate = 0;

                    if ($v['status'] == 'new') {
                        $isUpdate = 1;
                    }

                    $v['is_update'] = $isUpdate;
                }
            }

            return $this->toPagingData($getCard);
        } catch (\Exception $e) {
            throw new WarrantyCardRepoException(WarrantyCardRepoException::GET_LIST_WARRANTY_CARD_FAILED);
        }
    }

    /**
     * Chi tiết phiếu bảo hành
     *
     * @param $input
     * @return mixed|void
     * @throws WarrantyCardRepoException
     */
    public function show($input)
    {
        try {
            $mWarrantyCard = app()->get(WarrantyCardTable::class);
            $mWarrantyImage = app()->get(WarrantyCardImageTable::class);
            $mProductChild = app()->get(ProductChildTable::class);
            $mService = app()->get(ServiceTable::class);
            $mServiceCard = app()->get(ServiceCardTable::class);

            //Lấy thông tin phiếu bảo hành
            $getInfo = $mWarrantyCard->getInfo($input['warranty_card_code']);

            if ($getInfo == null) {
                throw new WarrantyCardRepoException(WarrantyCardRepoException::GET_DETAIL_WARRANTY_CARD_FAILED, __('Mã thẻ không hợp lệ. Vui lòng kiểm tra lại'));
            }

            $getInfo['status_name'] = $this->_setStatusName($getInfo['status']);
            $getInfo['status_color'] = $this->_setStatusColor($getInfo['status']);

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

            //Lấy thông tin ảnh hình ảnh của thẻ bảo hành
            $getInfo['warranty_image'] = $mWarrantyImage->getImage($input['warranty_card_code']);

            $isUpdate = 0;

            if ($getInfo['status'] == 'new') {
                $isUpdate = 1;
            }

            $getInfo['is_update'] = $isUpdate;

            $isCreateMaintenance = 1;

            if ($getInfo['status'] != 'actived') {
                $isCreateMaintenance = 0;
            } else {
                $dateNow = Carbon::now()->format('Y-m-d');

                if ($getInfo['quota'] != 0 && $getInfo['quota'] <= $getInfo['count_using'] && $getInfo['date_expired'] != null && $getInfo['date_expired'] < $dateNow) {
                    $isCreateMaintenance = 0;
                }
            }

            $getInfo['is_create_maintenance'] = $isCreateMaintenance;

            //Giá trị tối đa được bảo hành
            $maxPrice = floatval($getInfo['warranty_value']);
            //Tính giá trị dc bảo hành
            $warrantyValueApply = floatval(($getInfo['object_price'] / 100) * $getInfo['warranty_percent']);

            if ($warrantyValueApply > $maxPrice) {
                $warrantyValueApply = $maxPrice;
            }

            $getInfo['warranty_value_apply'] = $warrantyValueApply;

            //Lấy tên date_expired
            $dateExpiredName = null;

            if (in_array($getInfo['status'], [self::ACTIVE_CARD, self::FINISH_CARD])) {
                if ($getInfo['date_expired'] == null) {
                    $dateExpiredName = __('Không giới hạn');
                } else {
                    $dateExpiredName = Carbon::parse($getInfo['date_expired'])->format('d/m/Y');
                }
            }

            $getInfo['date_expired_name'] = $dateExpiredName;

            return $getInfo;
        } catch (\Exception $e) {
            throw new WarrantyCardRepoException(WarrantyCardRepoException::GET_DETAIL_WARRANTY_CARD_FAILED, $e->getMessage());
        }
    }

    /**
     * Cập nhật thẻ bảo hành
     *
     * @param $input
     * @return mixed|void
     * @throws WarrantyCardRepoException
     */
    public function update($input)
    {
        DB::beginTransaction();
        try {
            $mWarrantyCard = app()->get(WarrantyCardTable::class);
            $mWarrantyImage = app()->get(WarrantyCardImageTable::class);

            //Lấy thông tin thẻ bảo hành
            $getInfo = $mWarrantyCard->getInfo($input['warranty_card_code']);

            $dataUpdate = [
                'status' => $input['status'],
                'object_serial' => $input['object_serial'],
                'object_note' => $input['object_note'],
            ];

            if ($input['status'] == self::ACTIVE_CARD) {
                $mWarrantyPacked = app()->get(WarrantyPackedTable::class);

                //Lấy thông tin gói bảo hành -> lấy thời gian bảo hành
                $getWarranty = $mWarrantyPacked->getInfoPacked($getInfo['warranty_packed_code']);

                $dataUpdate['date_actived'] = Carbon::now()->format('Y-m-d H:i:s');
                $dataUpdate['date_expired'] = ($getWarranty['time'] != 0) ? Carbon::now()->addDays($getWarranty['time']) : null;

                //Lưu log ZNS
                FunctionSendNotify::dispatch([
                    'type' => SEND_ZNS_CUSTOMER,
                    'key' => 'active_warranty_card',
                    'customer_id' => $getInfo['customer_id'],
                    'object_id' => $getInfo['warranty_card_id'],
                    'tenant_id' => session()->get('idTenant')
                ]);
            }

            //Cập nhật thẻ bảo hành
            $mWarrantyCard->editByCode($dataUpdate, $input['warranty_card_code']);

            //Xoá ảnh bảo hành
            $mWarrantyImage->removeImage($input['warranty_card_code']);

            $dataImage = [];

            if (isset($input['warranty_image']) && count($input['warranty_image']) > 0) {
                foreach ($input['warranty_image'] as $v) {
                    $dataImage [] = [
                        "warranty_card_code" => $input['warranty_card_code'],
                        "link" => $v['link']
                    ];
                }
            }

            //Insert ảnh bảo hành
            $mWarrantyImage->insert($dataImage);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            throw new WarrantyCardRepoException(WarrantyCardRepoException::UPDATE_WARRANTY_CARD_FAILED, $e->getMessage());
        }
    }

    /**
     * Cập nhật nhanh phiếu bảo hành
     *
     * @param $input
     * @return mixed|void
     * @throws WarrantyCardRepoException
     */
    public function quickUpdate($input)
    {
        try {
            $mWarrantyCard = app()->get(WarrantyCardTable::class);

            //Lấy thông tin thẻ bảo hành
            $getInfo = $mWarrantyCard->getInfo($input['warranty_card_code']);

            $dataUpdate = [
                'status' => $input['status']
            ];

            if ($input['status'] == self::ACTIVE_CARD) {
                $mWarrantyPacked = app()->get(WarrantyPackedTable::class);

                //Lấy thông tin gói bảo hành -> lấy thời gian bảo hành
                $getWarranty = $mWarrantyPacked->getInfoPacked($getInfo['warranty_packed_code']);

                $dataUpdate['date_actived'] = Carbon::now()->format('Y-m-d H:i:s');
                $dataUpdate['date_expired'] = ($getWarranty['time'] != 0) ? Carbon::now()->addDays($getWarranty['time']) : null;

                //Lưu log ZNS
                FunctionSendNotify::dispatch([
                    'type' => SEND_ZNS_CUSTOMER,
                    'key' => 'active_warranty_card',
                    'customer_id' => $getInfo['customer_id'],
                    'object_id' => $getInfo['warranty_card_id'],
                    'tenant_id' => session()->get('idTenant')
                ]);
            }
            //Cập nhật thẻ bảo hành
            $mWarrantyCard->editByCode($dataUpdate, $input['warranty_card_code']);
        } catch (\Exception $e) {
            throw new WarrantyCardRepoException(WarrantyCardRepoException::QUICK_UPDATE_CARD_FAILED, $e->getMessage());
        }
    }

    /**
     * Lấy ds trạng thái phiếu bảo hành
     *
     * @return mixed|void
     * @throws WarrantyCardRepoException
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
                    'status' => 'actived',
                    'status_name' => $this->_setStatusName('actived'),
                    'status_color' => $this->_setStatusColor('actived')
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
                ]
            ];
        } catch (\Exception $e) {
            throw new WarrantyCardRepoException(WarrantyCardRepoException::QUICK_UPDATE_CARD_FAILED);
        }
    }

    /**
     * Lấy tên trạng thái phiếu bảo hành
     *
     * @param $status
     * @return array|null|string
     */
    private function _setStatusName($status)
    {
        $statusName = "";

        switch ($status) {
            case 'new':
                $statusName = __('Mới');
                break;
            case 'actived':
                $statusName = __('Kích hoạt');
                break;
            case 'finish':
                $statusName = __('Hoàn thành');
                break;
            case 'cancel':
                $statusName = __('Đã huỷ');
                break;
        }

        return $statusName;
    }

    /**
     * Lấy màu trạng thái phiếu bảo hành
     *
     * @param $status
     * @return string
     */
    private function _setStatusColor($status)
    {
        $statusColor = "";

        switch ($status) {
            case 'new':
                $statusColor = "#00A650";
                break;
            case 'actived':
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
}