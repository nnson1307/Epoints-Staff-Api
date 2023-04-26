<?php


namespace Modules\Order\Repositories\Order;


use App\Jobs\FunctionSendNotify;
use App\Jobs\SaveLogZns;
use App\Jobs\SendNotification;
use App\Jobs\SendStaffNotification;
use Carbon\Carbon;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use Modules\Home\Repositories\Home\HomeRepoInterface;
use Modules\Order\Http\Api\PaymentOnline;
use Modules\Order\Models\ConfigPrintBillTable;
use Modules\Order\Models\CustomerContactTable;
use Modules\Order\Models\CustomerDebtTable;
use Modules\Order\Models\CustomerServiceCardTable;
use Modules\Order\Models\DeliveryCostDetailTable;
use Modules\Order\Models\DeliveryCostTable;
use Modules\Order\Models\DeliveryHistoryLogTable;
use Modules\Order\Models\DeliveryHistoryTable;
use Modules\Order\Models\EmailConfigTable;
use Modules\Order\Models\EmailLogTable;
use Modules\Order\Models\EmailProviderTable;
use Modules\Order\Models\MaintenanceTable;
use Modules\Order\Models\OrderCommissionTable;
use Modules\Order\Models\OrderImageTable;
use Modules\Order\Models\PaymentMethodTable;
use Modules\Order\Models\PrintBillDeviceTable;
use Modules\Order\Models\PrintBillLogTable;
use Modules\Order\Models\ProductChildTable;
use Modules\Order\Models\ProductImageTable;
use Modules\Order\Models\ProductInventoryTable;
use Modules\Order\Models\ProductTable;
use Modules\Order\Models\PromotionLogTable;
use Modules\Order\Models\PromotionDailyTimeTable;
use Modules\Order\Models\PromotionDateTimeTable;
use Modules\Order\Models\PromotionDetailTable;
use Modules\Order\Models\PromotionMasterTable;
use Modules\Order\Models\PromotionMonthlyTimeTable;
use Modules\Order\Models\PromotionObjectApplyTable;
use Modules\Order\Models\PromotionWeeklyTimeTable;

use Modules\Order\Models\BranchTable;
use Modules\Order\Models\ConfigDetailTable;
use Modules\Order\Models\ConfigTable;
use Modules\Order\Models\CustomerTable;
use Modules\Order\Models\DeliveryTable;
use Modules\Order\Models\MemberLevelTable;
use Modules\Order\Models\NotificationAutoConfigTable;
use Modules\Order\Models\NotificationDetailTable;
use Modules\Order\Models\NotificationLogTable;
use Modules\Order\Models\OrderDetailTable;
use Modules\Order\Models\OrderLogTable;
use Modules\Order\Models\OrderTable;
use Modules\Order\Models\PointHistoryDetailTable;
use Modules\Order\Models\PointHistoryTable;
use Modules\Order\Models\PointRewardRuleTable;
use Modules\Order\Models\ReceiptDetailTable;
use Modules\Order\Models\ReceiptOnlineTable;
use Modules\Order\Models\ReceiptTable;
use Modules\Order\Models\ServiceBranchPriceTable;
use Modules\Order\Models\ServiceCardListTable;
use Modules\Order\Models\ServiceCardTable;
use Modules\Order\Models\ServiceTable;
use Modules\Order\Models\SmsConfigTable;
use Modules\Order\Models\SmsLogTable;
use Modules\Order\Models\SmsProviderTable;
use Modules\Order\Models\SpaInfoTable;

use Modules\Order\Models\StaffTable;
use Modules\Order\Models\VoucherTable;
use Modules\Order\Models\WarrantyCardTable;
use Modules\Order\Models\WarrantyPackageDetailTable;
use Modules\Order\Models\WarrantyPackageTable;
use Modules\Payment\Repositories\PaymentFactory;
use Modules\Product\Repositories\Product\ProductRepoInterface;
use Modules\User\Libs\help\Help;
use MyCore\Repository\PagingTrait;
use Modules\Order\Libs\UploadImage;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class OrderRepo implements OrderRepoInterface
{
    use PagingTrait;
    protected $order;
    protected $orderDetail;
    protected $product;
    protected $receipt;
    protected $receiptDetail;
    protected $configPrintBill;
    protected $branch;
    protected $printBillLog;
    protected $help;
    protected $spaInfo;
    protected $printBillDeviceTable;

    public function __construct(
        OrderTable $order,
        OrderDetailTable $orderDetail,
        ProductTable $product,
        ReceiptTable $receipt,
        ReceiptDetailTable $receiptDetail,
        ConfigPrintBillTable $configPrintBill,
        BranchTable $branch,
        PrintBillLogTable $printBillLog,
        Help $help,
        SpaInfoTable $spaInfo,
        PrintBillDeviceTable $printBillDeviceTable
    )
    {
        $this->order = $order;
        $this->orderDetail = $orderDetail;
        $this->product = $product;
        $this->receipt = $receipt;
        $this->receiptDetail = $receiptDetail;
        $this->configPrintBill = $configPrintBill;
        $this->branch = $branch;
        $this->printBillLog = $printBillLog;
        $this->help = $help;
        $this->spaInfo = $spaInfo;
        $this->printBillDeviceTable = $printBillDeviceTable;
    }

    const LIVE = 1;
    const RECEIPT_ONLINE_SUCCESS = "success";

    /**
     * Lấy Option chi nhánh
     *
     * @param $input
     * @return mixed
     */
    public function getOptionBranch($input)
    {
        $mBranch = app()->get(BranchTable::class);

        return $mBranch->getOptionBranch($input);
    }

    /**
     * Lấy danh sách đơn hàng
     *
     * @param $input
     * @return mixed|void
     * @throws OrderRepoException
     */
    public function getOrders($input)
    {
        try {
            $customerId = Auth()->id();

            $data = $this->toPagingData($this->order->getOrders($input, $customerId));

            $dataItem = $data['Items'];

            if (count($dataItem) > 0) {
                foreach ($dataItem as $item) {
                    //Lấy status name của đơn hàng
                    $item['process_status_name'] = $this->setStatusName($item['process_status']);

                    $isRemove = 0;
                    $isCancel = 0;
                    $isEdit = 0;

                    if ($item['process_status'] == 'new') {
                        $isRemove = 1;
                    }

                    if (in_array($item['process_status'], ['new', 'confirmed'])) {
                        $isEdit = 1;
                    }

                    $dateNow = Carbon::now()->format('Y-m-d');
                    $dateCreated = Carbon::createFromFormat('Y-m-d H:i:s', $item['created_at'])->format('Y-m-d');

                    if (in_array($item['process_status'], ['paysuccess', 'pay-half']) && $dateNow == $dateCreated) {
                        $isCancel = 1;
                    }

                    $item['is_remove'] = $isRemove;
                    $item['is_cancel'] = $isCancel;
                    $item['is_edit'] = $isEdit;

                    
                }
            }

            return $data;
        } catch (\Exception $exception) {
            throw new OrderRepoException(OrderRepoException::GET_ORDER_LIST_FAILED, $exception->getMessage());
        }
    }

    /**
     * Lấy tên trạng thái đơn hàng
     *
     * @param $status
     * @return array|null|string
     */
    protected function setStatusName($status)
    {
        $statusName = null;

        switch ($status) {
            case "new":
                $statusName = __('Mới');
                break;
            case "confirmed":
                $statusName = __('Đã xác nhận');
                break;
            case "paysuccess":
                $statusName = __('Đã thanh toán');
                break;
            case "pay-half":
                $statusName = __('Thanh toán còn thiếu');
                break;
            case "ordercancle":
                $statusName = __('Đã huỷ');
                break;
        }

        return $statusName;
    }

    /**
     * Chi tiết đơn hàng
     *
     * @param $orderId
     * @param $lang
     * @return array|mixed
     * @throws OrderRepoException
     */
    public function getOrderDetail($orderId, $lang)
    {
        try {
            $mOrderDetail = app()->get(OrderDetailTable::class);
            $mReceiptDetail = app()->get(ReceiptDetailTable::class);
            $mProductChild = app()->get(ProductChildTable::class);
            $mCustomerContact = app()->get(CustomerContactTable::class);

            //Thông tin đơn hàng
            $data = $this->order->orderInfo($orderId);
            $data['owed'] = 0;

            //Lấy status name của đơn hàng
            $data['process_status_name'] = $this->setStatusName($data['process_status']);
            //Lấy cờ xoá or huỷ
            $isRemove = 0;
            $isCancel = 0;
            $isEdit = 0;

            if ($data['process_status'] == 'new') {
                $isRemove = 1;
            }

            if (in_array($data['process_status'], ['new', 'confirmed'])) {
                $isEdit = 1;
            }

            $dateNow = Carbon::now()->format('Y-m-d');
            $dateCreated = Carbon::createFromFormat('Y-m-d H:i:s', $data['order_date'])->format('Y-m-d');

            if (in_array($data['process_status'], ['paysuccess', 'pay-half']) && $dateNow == $dateCreated) {
                $isCancel = 1;
            }

            $data['is_remove'] = $isRemove;
            $data['is_cancel'] = $isCancel;
            $data['is_edit'] = $isEdit;
            //Chi tiết đơn hàng
            $getDetail = $mOrderDetail->getDetailOrderList($data['order_id']);

            foreach ($getDetail as $v) {
                $unitName = null;

                if ($v['object_type'] == 'product' || $v['object_type'] == 'product_gift') {
                    //Lấy đơn vị tính
                    $getChild = $mProductChild->getProductByCode($v['object_code']);

                    if ($getChild != null) {
                        $unitName = $getChild['unit_name'];
                        //Lấy avatar product child
                        $v['object_image'] = $getChild['avatar'];
                    }
                }
                $v['unit_name'] = $unitName;
            }
            $data['order_detail'] = $getDetail;
            //Chi tiết thanh toán
            $dataReceipt = $mReceiptDetail->getDetailByOrder($orderId);

            if (count($dataReceipt) > 0) {
                $data['payment_status'] = $dataReceipt[0]['status'];
            } else {
                $data['payment_status'] = null;
            }

            if ($dataReceipt != null) {
                $data['receipt_detail'] = $dataReceipt;
                $totalReceipt = 0;
                if (count($dataReceipt) > 0) {
                    foreach ($dataReceipt as $item) {
                        $totalReceipt += $item['amount'];
                    }
                }
                $data['payed'] = $totalReceipt;
                $data['owed'] = $data['amount'] - $totalReceipt;
            } else {
                $data['receipt_detail'] = [];
            }
            //Log cập nhật đơn hàng
            $mOrderLog = app()->get(OrderLogTable::class);
            $data['log'] = $mOrderLog->getLog($orderId, $lang);
            //Log theo dõi đơn hàng
            $data['order_log'] = $mOrderLog->getLog($orderId, $lang);
            //Lấy hình ảnh trước/sau khi sử dụng
            $mOrderImage = app()->get(OrderImageTable::class);

            $data['image_before'] = $mOrderImage->getOrderImage($data['order_code'], 'before');
            $data['image_after'] = $mOrderImage->getOrderImage($data['order_code'], 'after');

            //Lấy địa chỉ giao hàng mặc định của khách hàng (khi thay đổi hình thức giao hàng thì dùng để bind vào)
            $deliveryAddress = $mCustomerContact->getContactDefault($data['customer_id']);

            $addressCustomer = null;

            if ($deliveryAddress != null) {
                $addressCustomer = $deliveryAddress['address'] . ', ' . $deliveryAddress['district_type'] . ' ' . $deliveryAddress['district_name'] . ', ' . $deliveryAddress['province_type'] . ' ' . $deliveryAddress['province_name'];
            }

            $data['address_customer'] = $addressCustomer;

            return $data;
        } catch (\Exception $e) {
            throw new OrderRepoException(OrderRepoException::GET_ORDER_DETAIL_FAILED, $e->getMessage() . $e->getLine());
        }
    }


    /**
     * Thêm đơn hàng
     *
     * @param $input
     * @return array|mixed
     * @throws OrderRepoException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function store($input)
    {
        DB::beginTransaction();
        try {
            $mOrderDetail = app()->get(OrderDetailTable::class);
            $mDelivery = app()->get(DeliveryTable::class);
            $mPromotionLog = app()->get(PromotionLogTable::class);
            $mOrderLog = app()->get(OrderLogTable::class);
            $mCustomer = app()->get(CustomerTable::class);

            $branchId = '';
            $inventory['inventory'] = [];
            $inventory['change_price'] = [];
            $inventory['is_delete'] = [];
            $giftNew = [];

            //Check quà tặng đẩy lên khi thêm đơn hàng
            $checkStoreGift = $this->checkGiftStoreOrder($input['detail'], $input['customer_id']);

            if ($checkStoreGift['error'] == 1) {
                return [
                    'promotion_update' => $checkStoreGift['gift'],
                    'message' => $checkStoreGift['message']
                ];
            }

            //Lấy chi nhánh mặc định nếu ko có chi nhánh
            $mBranch = app()->get(BranchTable::class);
            $branchDefault = $mBranch->getBranchById(Auth()->user()->branch_id);

            $input['branch_code'] = $input['branch_code'] != null ? $input['branch_code'] : $branchDefault['branch_code'];

            //Lấy branchId insert đơn hàng
            $getBranch = $mBranch->getBranchByCode($input['branch_code']);
            $branchId = null;
            if( $getBranch != null){
                $branchId = $getBranch['branch_id'];
            }

//            if ($input['branch_code'] != null) {
//            if (isset($input['detail']) || count($input['detail']) > 0) {
//                foreach ($input['detail'] as $v) {
//                    //Check tồn kho - thay đổi giá - đã xóa sản phẩm
//                    if ($v['object_type'] == 'product') {
//                        $getCheck = $this->checkProduct($input['branch_code'], $v['object_id'], $v['object_code'], $v['quantity'], $v['price']);
//
//                        if (count($getCheck['inventory']) > 0) {
//                            $inventory['inventory'] [] = $getCheck['inventory'];
//                        }
//
//                        if (count($getCheck['change_price']) > 0) {
//                            $inventory['change_price'] [] = $getCheck['change_price'];
//                        }
//                        if (count($getCheck['is_delete']) > 0) {
//                            $inventory['is_delete'] [] = $getCheck['is_delete'];
//                        }
//                    }
//                }
//            }
//
//            if (count($inventory['inventory']) > 0 || count($inventory['change_price']) > 0 || count($inventory['is_delete']) > 0) {
//                return [
//                    'product_update' => $inventory
//                ];
//            }
//            }

            $dataOrder = [
                'branch_id' => $branchId,
                'customer_id' => $input['customer_id'],
                'total' => $input['total'],
                'discount_member' => isset($input['discount_member']) ? $input['discount_member'] : 0,
                'discount' => $input['discount'],
                'amount' => $input['amount'],
                'voucher_code' => $input['voucher_code'],
                'tranport_charge' => $input['transport_charge'],
                'order_source_id' => 1,
                'customer_contact_code' => $input['customer_contact_code'],
                'payment_method_id' => $input['payment_method_id'],
                'order_description' => $input['order_description'],
                'created_by' => Auth()->id(),
                'updated_by' => Auth()->id(),
                "type_shipping" => isset($input['type_shipping']) ? $input['type_shipping'] : 0,
                "delivery_cost_id" => isset($input['delivery_cost_id']) ? $input['delivery_cost_id'] : null
            ];

            //Thanh toán tại quầy
            if (isset($input['branch_code']) && $input['branch_code'] != null && $input['customer_contact_code'] == null) {
                $dataOrder['receive_at_counter'] = 1;
            }

            //Insert đơn hàng
            $idOrder = $this->order->add($dataOrder);
            //Update order code
            $orderCode = 'DH_' . date('dmY') . sprintf("%02d", $idOrder);
            $this->order->edit([
                'order_code' => $orderCode
            ], $idOrder);

            //Promotion log
            $arrPromotionLog = [];
            $arrQuota = [];
            $arrObjectBuy = [];

            if (isset($input['detail']) && count($input['detail']) > 0) {
                foreach ($input['detail'] as $key => $value) {
                    if (in_array($value['object_type'], ['product', 'service', 'service_card'])) {
                        $arrObjectBuy [] = [
                            'object_type' => $value['object_type'],
                            'object_code' => $value['object_code'],
                            'object_id' => $value['object_id'],
                            'price' => $value['price'],
                            'quantity' => $value['quantity'],
                            'customer_id' => $input['customer_id'],
                            'order_source' => self::LIVE,
                            'order_id' => $idOrder,
                            'order_code' => $orderCode
                        ];
                    }
                }
            }

            //Lấy thông tin CTKM dc áp dụng cho đơn hàng
            $getPromotionLog = $this->groupQuantityObjectBuy($arrObjectBuy);
            //Insert promotion log
            $arrPromotionLog = $getPromotionLog['promotion_log'];
            $mPromotionLog->insert($arrPromotionLog);
            //Cộng quota_use promotion quà tặng
            $arrQuota = $getPromotionLog['promotion_quota'];
            $this->plusQuotaUsePromotion($arrQuota);

            //Insert chi tiết đơn hàng
            if (!isset($input['detail']) || count($input['detail']) == 0) {
                throw new OrderRepoException(OrderRepoException::STORE_ORDER_FAILED);
            }
            foreach ($input['detail'] as $item) {
                $dataDetail = [
                    'order_id' => $idOrder,
                    'object_id' => $item['object_id'],
                    'object_name' => $item['object_name'],
                    'object_type' => $item['object_type'],
                    'object_code' => $item['object_code'],
                    'price' => $item['price'],
                    'quantity' => $item['quantity'],
                    'discount' => $item['discount'],
                    'amount' => $item['amount']
                ];
                $mOrderDetail->add($dataDetail);
            }
//            if ($input['branch_code'] == null) {
            //Insert thông tin giao hàng
            $dataDelivery = [
                'order_id' => $idOrder,
                'customer_id' => $input['customer_id'],
                'contact_name' => $input['contact_name'],
                'contact_phone' => $input['contact_phone'],
                'contact_address' => $input['full_address'],
                'is_actived' => 0,
                'time_order' => Carbon::now()->format('Y-m-d H:i')
            ];
            $mDelivery->add($dataDelivery);
//            }
            //Insert sms log khi đặt hàng thành công
            $this->saveSmsLog($input['customer_id'], 'order_success', $idOrder);
            //Insert email log khi đặt hàng thành công
            $this->saveEmailLog($input['customer_id'], 'order_success', $idOrder);

            //Insert order log
            $mOrderLog->add([
                'order_id' => $idOrder,
                'created_type' => 'backend',
                'status' => 'new',
//                'note' => __('Đặt hàng thành công'),
                'created_by' => Auth()->id(),
                'note_vi' => 'Đặt hàng thành công',
                'note_en' => 'Order success',
            ]);

            DB::commit();

            //Send Notification
            FunctionSendNotify::dispatch([
                'type' => SEND_NOTIFY_CUSTOMER,
                'key' => 'order_status_W',
                'customer_id' => $input['customer_id'],
                'object_id' => $idOrder,
                'tenant_id' => session()->get('idTenant')
            ]);
            //Gửi thông báo nhân viên
            FunctionSendNotify::dispatch([
                'type' => SEND_NOTIFY_STAFF,
                'key' => 'order_status_W',
                'customer_id' => $input['customer_id'],
                'object_id' => $idOrder,
                'branch_id' => $branchId,
                'tenant_id' => session()->get('idTenant')
            ]);
            //Lưu log ZNS
            FunctionSendNotify::dispatch([
                'type' => SEND_ZNS_CUSTOMER,
                'key' => 'order_success',
                'customer_id' => $input['customer_id'],
                'object_id' => $idOrder,
                'tenant_id' => session()->get('idTenant')
            ]);
            //Cộng điểm khi đặt hàng
            $this->plusPoint([
                'customer_id' => $input['customer_id'],
                'rule_code' => 'order_app',
                'object_id' => $idOrder
            ]);
            $order_create_ticket = DB::table('config')->select('value')->where('key', 'save_order_create_ticket')->first()->value;
            return [
                'order_id' => $idOrder,
                'order_create_ticket' => (int)$order_create_ticket
            ];
        } catch (\Exception $e) {
            DB::rollback();
            throw new OrderRepoException(OrderRepoException::STORE_ORDER_FAILED, $e->getMessage() . $e->getFile() . $e->getLine());
        }
    }

    /**
     * Thêm đơn hàng (V2)
     *
     * @param $input
     * @return array
     * @throws OrderRepoException
     * @throws GuzzleException
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function storeV2($input)
    {
        DB::beginTransaction();
        try {
            $mOrderDetail = app()->get(OrderDetailTable::class);
            $mDelivery = app()->get(DeliveryTable::class);
            $mPromotionLog = app()->get(PromotionLogTable::class);
            $mOrderLog = app()->get(OrderLogTable::class);
            $mCustomer = app()->get(CustomerTable::class);

            $branchId = '';
            $inventory['inventory'] = [];
            $inventory['change_price'] = [];
            $inventory['is_delete'] = [];
            $giftNew = [];

            //Check quà tặng đẩy lên khi thêm đơn hàng
            $checkStoreGift = $this->checkGiftStoreOrder($input['detail'], $input['customer_id']);

            if ($checkStoreGift['error'] == 1) {
                return [
                    'promotion_update' => $checkStoreGift['gift'],
                    'message' => $checkStoreGift['message']
                ];
            }

            //Lấy chi nhánh mặc định nếu ko có chi nhánh
            $mBranch = app()->get(BranchTable::class);
            $branchDefault = $mBranch->getBranchById(Auth()->user()->branch_id);

            $input['branch_code'] = $input['branch_code'] != null ? $input['branch_code'] : $branchDefault['branch_code'];

            //Lấy branchId insert đơn hàng
            $getBranch = $mBranch->getBranchByCode($input['branch_code']);

            $branchId = null;
            if( $getBranch != null){
                $branchId = $getBranch['branch_id'];
            }

            $dataOrder = [
                'branch_id' => $branchId,
                'customer_id' => $input['customer_id'],
                'total' => $input['total'],
                'discount_member' => isset($input['discount_member']) ? $input['discount_member'] : 0,
                'discount' => $input['discount'],
                'amount' => $input['amount'],
                'voucher_code' => $input['voucher_code'],
                'tranport_charge' => $input['transport_charge'],
                'order_source_id' => 1,
                'customer_contact_code' => $input['customer_contact_code'],
                'payment_method_id' => $input['payment_method_id'],
                'order_description' => $input['order_description'],
                "type_shipping" => isset($input['type_shipping']) ? $input['type_shipping'] : 0,
                "delivery_cost_id" => isset($input['delivery_cost_id']) ? $input['delivery_cost_id'] : null,
                'created_by' => Auth()->id(),
                'updated_by' => Auth()->id()
            ];

            //Thanh toán tại quầy
            if (isset($input['branch_code']) && $input['branch_code'] != null && $input['customer_contact_code'] == null) {
                $dataOrder['receive_at_counter'] = 1;
            }

            //Insert đơn hàng
            $idOrder = $this->order->add($dataOrder);
            //Update order code
            $orderCode = 'DH_' . date('dmY') . sprintf("%02d", $idOrder);
            $this->order->edit([
                'order_code' => $orderCode
            ], $idOrder);

            //Promotion log
            $arrPromotionLog = [];
            $arrQuota = [];
            $arrObjectBuy = [];

            if (isset($input['detail']) && count($input['detail']) > 0) {
                foreach ($input['detail'] as $key => $value) {
                    if (in_array($value['object_type'], ['product', 'service', 'service_card'])) {
                        $arrObjectBuy [] = [
                            'object_type' => $value['object_type'],
                            'object_code' => $value['object_code'],
                            'object_id' => $value['object_id'],
                            'price' => $value['price'],
                            'quantity' => $value['quantity'],
                            'customer_id' => $input['customer_id'],
                            'order_source' => self::LIVE,
                            'order_id' => $idOrder,
                            'order_code' => $orderCode
                        ];
                    }
                }
            }

            //Lấy thông tin CTKM dc áp dụng cho đơn hàng
            $getPromotionLog = $this->groupQuantityObjectBuy($arrObjectBuy);
            //Insert promotion log
            $arrPromotionLog = $getPromotionLog['promotion_log'];
            $mPromotionLog->insert($arrPromotionLog);
            //Cộng quota_use promotion quà tặng
            $arrQuota = $getPromotionLog['promotion_quota'];
            $this->plusQuotaUsePromotion($arrQuota);

            //Insert chi tiết đơn hàng
            if (!isset($input['detail']) || count($input['detail']) == 0) {
                throw new OrderRepoException(OrderRepoException::STORE_ORDER_FAILED);
            }
            foreach ($input['detail'] as $item) {
                $dataDetail = [
                    'order_id' => $idOrder,
                    'object_id' => $item['object_id'],
                    'object_name' => $item['object_name'],
                    'object_type' => $item['object_type'],
                    'object_code' => $item['object_code'],
                    'price' => $item['price'],
                    'quantity' => $item['quantity'],
                    'discount' => $item['discount'],
                    'amount' => $item['amount'],
                    'note' => $item['note'],
                    'created_at_day' => Carbon::now()->format('d'),
                    'created_at_month' => Carbon::now()->format('m'),
                    'created_at_year' => Carbon::now()->format('Y'),
                    'created_by' => Auth::id(),
                    'updated_by' => Auth::id()
                ];
                $mOrderDetail->add($dataDetail);
            }
//            if ($input['branch_code'] == null) {
            //Insert thông tin giao hàng
            $dataDelivery = [
                'order_id' => $idOrder,
                'customer_id' => $input['customer_id'],
                'contact_name' => $input['contact_name'],
                'contact_phone' => $input['contact_phone'],
                'contact_address' => $input['full_address'],
                'is_actived' => 0,
                'time_order' => Carbon::now()->format('Y-m-d H:i')
            ];
            $mDelivery->add($dataDelivery);
//            }
            //Insert sms log khi đặt hàng thành công
            $this->saveSmsLog($input['customer_id'], 'order_success', $idOrder);
            //Insert email log khi đặt hàng thành công
            $this->saveEmailLog($input['customer_id'], 'order_success', $idOrder);

            //Insert order log
            $mOrderLog->add([
                'order_id' => $idOrder,
                'created_type' => 'backend',
                'status' => 'new',
//                'note' => __('Đặt hàng thành công'),
                'created_by' => Auth()->id(),
                'note_vi' => 'Đặt hàng thành công',
                'note_en' => 'Order success',
            ]);

            DB::commit();

            //Send Notification
            FunctionSendNotify::dispatch([
                'type' => SEND_NOTIFY_CUSTOMER,
                'key' => 'order_status_W',
                'customer_id' => $input['customer_id'],
                'object_id' => $idOrder,
                'tenant_id' => session()->get('idTenant')
            ]);
            //Gửi thông báo nhân viên
            FunctionSendNotify::dispatch([
                'type' => SEND_NOTIFY_STAFF,
                'key' => 'order_status_W',
                'customer_id' => $input['customer_id'],
                'object_id' => $idOrder,
                'branch_id' => $branchId,
                'tenant_id' => session()->get('idTenant')
            ]);
            //Lưu log ZNS
            FunctionSendNotify::dispatch([
                'type' => SEND_ZNS_CUSTOMER,
                'key' => 'order_success',
                'customer_id' => $input['customer_id'],
                'object_id' => $idOrder,
                'tenant_id' => session()->get('idTenant')
            ]);
            //Cộng điểm khi đặt hàng
            $this->plusPoint([
                'customer_id' => $input['customer_id'],
                'rule_code' => 'order_app',
                'object_id' => $idOrder
            ]);
            $order_create_ticket = DB::table('config')->select('value')->where('key', 'save_order_create_ticket')->first()->value;
            return [
                'order_id' => $idOrder,
                'order_create_ticket' => (int)$order_create_ticket
            ];
        } catch (\Exception $e) {
            DB::rollback();
            throw new OrderRepoException(OrderRepoException::STORE_ORDER_FAILED, $e->getMessage() . $e->getFile() . $e->getLine());
        }
    }

    /**
     * Group số lượng mua của các object, lấy ra CTKM áp dụng cho đơn hàng
     *
     * @param $arrObjectBuy
     * @return mixed|void
     */
    public function groupQuantityObjectBuy($arrObjectBuy)
    {
        $promotionLog = [];
        $arrQuota = [];

        $arrBuy = [];

        //Group số lượng mua của những sp trùng nhau
        if (count($arrObjectBuy) > 0) {
            foreach ($arrObjectBuy as $v) {
                $objectCode = $v['object_code'];
                if (!array_key_exists($objectCode, $arrBuy)) {
                    $arrBuy[$objectCode] = $v;
                } else {
                    $arrBuy[$objectCode]['quantity'] = $arrBuy[$objectCode]['quantity'] + $v['quantity'];
                }
            }
        }


        if (count($arrBuy) > 0) {
            foreach ($arrBuy as $v) {
                //Lấy thông tin CTKM áp dụng cho đơn hàng
                $getLog = $this->getPromotionLog(
                    $v['object_type'],
                    $v['object_code'],
                    $v['price'],
                    $v['quantity'],
                    $v['customer_id'],
                    $v['order_source'],
                    $v['object_id'],
                    $v['order_id'],
                    $v['order_code']
                );

                foreach ($getLog['promotion_log'] as $vLog) {
                    $promotionLog [] = $vLog;
                }

                if (count($getLog['promotion_quota']) > 0) {
                    $arrQuota [] = $getLog['promotion_quota'];
                }
            }
        }

        return [
            'promotion_log' => $promotionLog,
            'promotion_quota' => $arrQuota
        ];
    }

    /**
     * Kiểm tra quà tặng khi thêm đơn hàng
     *
     * @param array $listProduct
     * @param $customerId
     * @return array
     */
    protected function checkGiftStoreOrder($listProduct = [], $customerId)
    {
        $arrProduct = [];
        $arrGift = [];

        if (count($listProduct) > 0) {
            foreach ($listProduct as $v) {
                if (empty($v['quantity']) || $v['quantity'] == null) {
                    return [
                        'error' => 1,
                        'message' => __('Số lượng không hợp lệ'),
                        'gift' => null
                    ];
                }

                if (in_array($v['object_type'], ['product', 'service', 'service_card'])) {
                    $arrProduct [] = $v;
                } else {
                    unset($v['object_image']);
                    $v['price'] = 0;
                    $v['discount'] = 0;
                    $v['amount'] = 0;
                    $arrGift [] = $v;
                }
            }
        }

        //Check gift product
        $checkGift = $this->checkPromotionGift([
            'list_param' => $arrProduct,
            'customer_id' => $customerId
        ]);
        $arrGiftNew = [];

        //Có sản phẩm khuyến mãi
        if ($checkGift > 0) {
            foreach ($checkGift as $v) {
                unset($v['object_image']);
                $arrGiftNew [] = $v;
            }

            array_multisort($arrGift);
            array_multisort($arrGiftNew);

            if (serialize($arrGift) !== serialize($arrGiftNew)) {
                //Qua tặng có thay đổi
                return [
                    'error' => 1,
                    'message' => __('Quà tặng có thay đổi'),
                    'gift' => $checkGift
                ];
            }
        } else if ($checkGift == 0 && count($arrGift) > 0) {
            //Không có sản phẩm khuyến mãi, nhưng đẩy lên có quà tặng thì báo lỗi clear hết quà tặng đó
            return [
                'error' => 1,
                'message' => __('List sản phẩm không có quà tặng'),
                'gift' => null
            ];
        }

        return [
            'error' => 0
        ];
    }

    /**
     * Lấy giảm giá thành viên
     *
     * @param $input
     * @return mixed|void
     * @throws OrderRepoException
     */
    public function getDiscountMember($input)
    {
        try {
            $data['discount_member'] = 0;

            $mCustomer = app()->get(CustomerTable::class);
            //Lấy thông tin khách hàng
            $info = $mCustomer->getInfoById($input['customer_id']);

            $mMemberLevel = app()->get(MemberLevelTable::class);
            //Lấy hạng thành viên
            $memberLevel = $mMemberLevel->getInfo($info['member_level_id']);

            if ($memberLevel != null) {
                $discountMember = number_format(($input['amount'] / 100) * $memberLevel['discount'], 0);
                $data['discount_member'] = ($discountMember);
            }

            return $data;
        } catch (\Exception | QueryException $exception) {
            throw new OrderRepoException(OrderRepoException::GET_DISCOUNT_MEMBER_FAILED);
        }
    }

    /**
     * Cộng điểm khi có event
     *
     * @param $param
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function plusPoint($param)
    {
        $domain = request()->getHost();
        $brandCode = session()->get('brand_code');

        $endpoint = sprintf(BASE_URL_API, $brandCode) . '/loyalty/plus-point-event';

        $client = new \GuzzleHttp\Client();
        $response = $client->request('POST', $endpoint, ['query' => $param]);

        $statusCode = $response->getStatusCode();
        $content = $response->getBody();

        return json_decode($response->getBody(), true);
    }

    /**
     * Hủy đơn hàng
     *
     * @param $input
     * @return mixed|void
     * @throws OrderRepoException
     */
    public function cancel($input)
    {
        try {
            $mConfig = app()->get(ConfigTable::class);
            $mConfigDetail = app()->get(ConfigDetailTable::class);

            //Lấy thông tin đơn hàng
            $info = $this->order->orderInfo($input['order_id']);

            if ($input['type'] == 'remove') {
                //Cập nhật is_deleted đơn hàng
                $this->order->edit([
                    'is_deleted' => 1
                ], $input['order_id']);
            } else if ($input['type'] == 'cancel') {
                //Cập nhật trạng thái đơn hàng
                $this->order->edit([
                    'process_status' => 'ordercancle'
                ], $input['order_id']);

                //update receipt
                $this->receipt->editByOrder(['status' => 'cancel'], $input['order_id']);
                //check customer debt
                $mCustomerDebt = app()->get(CustomerDebtTable::class);
                $mCustomerDebt->editByOrder(['status' => 'cancel'], $input['order_id']);

                //Lưu order_log
                $mOrderLog = app()->get(OrderLogTable::class);
                $mOrderLog->add([
                    'order_id' => $input['order_id'],
                    'created_type' => 'app',
                    'type' => 'status',
                    'status' => 'ordercancel',
//                'note' => __('Hủy đơn hàng'),
                    'created_by' => Auth()->id(),
                    'note_vi' => 'Hủy đơn hàng',
                    'note_en' => 'Order cancel',
                ]);
                //Send notification
                FunctionSendNotify::dispatch([
                    'type' => SEND_NOTIFY_CUSTOMER,
                    'key' => 'order_status_C',
                    'customer_id' => $info['customer_id'],
                    'object_id' => $input['order_id'],
                    'tenant_id' => session()->get('idTenant')
                ]);
                //Lưu log ZNS
                FunctionSendNotify::dispatch([
                    'type' => SEND_ZNS_CUSTOMER,
                    'key' => 'order_cancle',
                    'customer_id' => $info['customer_id'],
                    'object_id' => $input['order_id'],
                    'tenant_id' => session()->get('idTenant')
                ]);
            }

            //Cập nhật trang thái đơn hàng cần giao
            $mDelivery = app()->get(DeliveryTable::class);
            $mDelivery->edit([
                'delivery_status' => 'cancel'
            ], $input['order_id']);
            //Xóa lịch sử giao hàng
            $mDeliveryHistory = app()->get(DeliveryHistoryTable::class);
            $mDeliveryHistory->removeAllByOrder($input['order_id']);
            //Trừ quota_user khi đơn hàng có promotion quà tặng
            $this->subtractQuotaUsePromotion($input['order_id']);
            //Kiểm tra cấu hình giữ điểm khi hủy lịch hẹn
            $config = $mConfig->getConfig('save_point_order_cancel');

            if ($config['value'] == 1) {
                $configDetail = $mConfigDetail->getDetail($config['config_id']);
//                //Đếm số lịch hẹn đã hủy
                $numberCancel = $this->order->numberOrderCancel(Carbon::now()->format('Y-m-d'));

                if ($numberCancel > $configDetail['value']) {
                    //Trừ điểm khi vượt quá số đơn cho phép trong ngày
                    $this->subtractPoint($input['order_id'], $info['customer_id']);
                }
            } else {
                //Trừ điểm khi không cấu hình
                $this->subtractPoint($input['order_id'], $info['customer_id']);
            }
        } catch (\Exception | QueryException $e) {
            throw new OrderRepoException(OrderRepoException::CANCEL_ORDER_FAILED, $e->getMessage() . $e->getLine() . $e->getFile());
        }
    }

    /**
     * Trừ điểm thành viên khi hủy đơn hàng
     *
     * @param $objectId
     * @param $customerId
     */
    private function subtractPoint($objectId, $customerId)
    {
        $mCustomer = app()->get(CustomerTable::class);
        $mRule = app()->get(PointRewardRuleTable::class);
        $mPointHistory = app()->get(PointHistoryTable::class);
        $mPointHistoryDetail = app()->get(PointHistoryDetailTable::class);

        $point = $mPointHistory->getPointBookingByAppointment($customerId, $objectId);
        
        if ($point != null) {
            //Xóa lịch sử tích điểm của lịch bị xóa
            $mPointHistory->edit([
                'is_deleted' => 1
            ], $point['point_history_id']);
            //Lấy thông tin khách hàng
            $customer = $mCustomer->getInfoById($customerId);
            //Cập nhật điểm user
            $mCustomer->editUser([
                'point' => $customer['point'] - $point['point']
            ], $customerId);
            //Insert point history
            $pointHistoryId = $mPointHistory->add([
                'customer_id' => $customerId,
                'point' => $point['point'],
                'type' => 'subtract',
                'point_description' => 'order_app',
                'object_id' => $objectId
            ]);
            //Insert point history detail
            $rule = $mRule->getRule('order_app');
            $mPointHistoryDetail->add([
                'point_history_id' => $pointHistoryId,
                'point_reward_rule_id' => $rule['point_reward_rule_id']
            ]);
        }
    }

    /**
     * Check qua tang khi len don hang
     *
     * @param $input
     * @return array|mixed
     */
    public function checkPromotionGift($input)
    {
        $arrGift = [];
        $list = $input['list_param'];

        $customer_id = $input['customer_id'];

        // kiem tra san pham co nam trong CTKM nao ko
        $mHome = app()->get(HomeRepoInterface::class);

        if (count($list) > 0) {
            foreach ($list as $v) {
                $getPromotion = $mHome->getPromotionDetail(
                    $v['object_type'],
                    $v['object_code'],
                    $customer_id,
                    'app',
                    $v['quantity'],
                    $v['object_id']
                );

                if (isset($getPromotion) && count($getPromotion['promotion_log']) > 0) {
                    foreach ($getPromotion['promotion_log'] as $gift) {
                        if ($gift['promotion_type'] == 2 && $v['quantity'] >= $gift['quantity_buy']) {
                            $getImage = $this->getImageObject($gift['gift_object_type'], $gift['gift_object_id'], $gift['gift_object_code']);
                            //Lấy tỉ lệ quà tặng
                            $multiplication = $gift['quantity_buy'] > 0 ? intval($v['quantity'] / $gift['quantity_buy']) : 0;
                            //Số quà được tặng
                            $totalGift = intval($gift['quantity_gift'] * $multiplication);

                            $arrGift [] = [
                                "object_type" => $gift['gift_object_type'] . '_gift',
                                "object_id" => $gift['gift_object_id'],
                                "object_name" => $getImage['object_name'],
                                "object_code" => $gift['gift_object_code'],
                                "object_image" => $getImage['avatar'],
                                "quantity" => $totalGift,
                                "price" => 0,
                                "discount" => 0,
                                "amount" => 0
                            ];
                        }
                    }

                }
            }
            if (empty($arrGift) || count($arrGift) <= 0) {
                $arrGift = null;
            }
        }

        return $arrGift;
    }

    /**
     * Lấy hình sp, dv, thẻ dv
     *
     * @param $objectType
     * @param $objectId
     * @param $objectCode
     * @return string|null
     */
    protected function getImageObject($objectType, $objectId, $objectCode)
    {
        $objectName = '';
        $objectAvatar = '';

        if ($objectType == 'product') {
            $mProductImage = app()->get(ProductImageTable::class);
            $mProductChild = app()->get(ProductChildTable::class);
            //Lấy avatar product child
            $imageChild = $mProductImage->getImageChild($objectCode);

            if ($imageChild != null) {
                $objectAvatar = $imageChild['image'];
            } else {
                $objectAvatar = 'http://' . request()->getHttpHost() . '/static/images/product.png';
            }
            //Lấy tên sản phẩm
            $getChild = $mProductChild->getProductByCode($objectCode);

            $objectName = $getChild['product_name'];
        } else if ($objectType == 'service') {
            $mServiceBranchPrice = app()->get(ServiceBranchPriceTable::class);
            //Lấy thông tin dịch vụ
            $getService = $mServiceBranchPrice->getDetail($objectCode);

            if ($getService['service_avatar'] != null) {
                $objectAvatar = $getService['service_avatar'];
            } else {
                $objectAvatar = 'http://' . request()->getHttpHost() . '/static/images/service.png';
            }
            $objectName = $getService['service_name'];
        } else if ($objectType == 'service_card') {
            $mServiceCard = app()->get(ServiceCardTable::class);
            //Lấy thông tin thẻ dv
            $getServiceCard = $mServiceCard->getServiceCard($objectCode);

            if ($getServiceCard['image'] != null) {
                $objectAvatar = $getServiceCard['image'];
            } else {
                $objectAvatar = 'http://' . request()->getHttpHost() . '/static/images/service-card.png';
            }
            $objectName = $getServiceCard['name'];
        }

        return [
            'object_name' => $objectName,
            'avatar' => $objectAvatar
        ];
    }

    /**
     * Lấy thông tin CTKM khi mua hàng
     *
     * @param $objectType
     * @param $objectCode
     * @param $price
     * @param $quantity
     * @param $customerId
     * @param $orderSource
     * @param $objectId
     * @param $orderId
     * @param $orderCode
     * @return array|null
     */
    public function getPromotionLog($objectType, $objectCode, $price, $quantity, $customerId, $orderSource, $objectId, $orderId, $orderCode)
    {
        $mPromotionDetail = new PromotionDetailTable();
        $mDaily = new PromotionDailyTimeTable();
        $mWeekly = new PromotionWeeklyTimeTable();
        $mMonthly = new PromotionMonthlyTimeTable();
        $mFromTo = new PromotionDateTimeTable();
        $mCustomer = new CustomerTable();
        $mPromotionApply = new PromotionObjectApplyTable();

        $currentDate = Carbon::now()->format('Y-m-d H:i');
        $currentTime = Carbon::now()->format('H:i');

        //Lấy chi tiết CTKM
        $getDetail = $mPromotionDetail->getPromotionDetail($objectType, $objectCode, null, $currentDate);

        $promotionLog = [];
        $promotionQuota = [];
        $promotionPrice = [];
        $result = [];
        $resultPlusQuota = [];

        if (count($getDetail) > 0) {
            foreach ($getDetail as $v) {
                //Check thời gian diễn ra chương trình
                if ($currentDate < $v['start_date'] || $currentDate > $v['end_date']) {
                    //Kết thúc vòng for
                    continue;
                }
                //Check chi nhánh áp dụng
                if ($v['branch_apply'] != 'all' &&
                    !in_array(Auth()->user()->branch_id, explode(',', $v['branch_apply']))) {
                    //Kết thúc vòng for
                    continue;
                }
                //Check KM theo time đặc biệt
                if ($v['is_time_campaign'] == 1) {
                    switch ($v['time_type']) {
                        case 'D':
                            $daily = $mDaily->getDailyByPromotion($v['promotion_code']);

                            if ($daily != null) {
                                $startTime = Carbon::createFromFormat('H:i:s', $daily['start_time'])->format('H:i');
                                $endTime = Carbon::createFromFormat('H:i:s', $daily['end_time'])->format('H:i');
                                //Kiểm tra giờ bắt đầu, giờ kết thúc
                                if ($currentTime < $startTime || $currentTime > $endTime) {
                                    //Kết thúc vòng for
                                    continue 2;
                                }
                            }
                            break;
                        case 'W':
                            $weekly = $mWeekly->getWeeklyByPromotion($v['promotion_code']);
                            $startTime = Carbon::createFromFormat('H:i:s', $weekly['default_start_time'])->format('H:i');
                            $endTime = Carbon::createFromFormat('H:i:s', $weekly['default_end_time'])->format('H:i');

                            switch (Carbon::now()->format('l')) {
                                case 'Monday':
                                    if ($weekly['is_monday'] == 1) {
                                        if ($weekly['is_other_monday'] == 1) {
                                            $startTime = Carbon::createFromFormat('H:i:s', $weekly['is_other_monday_start_time'])->format('H:i');
                                            $endTime = Carbon::createFromFormat('H:i:s', $weekly['is_other_monday_end_time'])->format('H:i');
                                        }
                                    } else {
                                        //Kết thúc vòng for
                                        continue 3;
                                    }
                                    break;
                                case 'Tuesday':
                                    if ($weekly['is_tuesday'] == 1) {
                                        if ($weekly['is_other_tuesday'] == 1) {
                                            $startTime = Carbon::createFromFormat('H:i:s', $weekly['is_other_tuesday_start_time'])->format('H:i');
                                            $endTime = Carbon::createFromFormat('H:i:s', $weekly['is_other_tuesday_end_time'])->format('H:i');
                                        }
                                    } else {
                                        //Kết thúc vòng for
                                        continue 3;
                                    }
                                    break;
                                case 'Wednesday':
                                    if ($weekly['is_wednesday'] == 1) {
                                        if ($weekly['is_other_wednesday'] == 1) {
                                            $startTime = Carbon::createFromFormat('H:i:s', $weekly['is_other_wednesday_start_time'])->format('H:i');
                                            $endTime = Carbon::createFromFormat('H:i:s', $weekly['is_other_wednesday_end_time'])->format('H:i');
                                        }
                                    } else {
                                        //Kết thúc vòng for
                                        continue 3;
                                    }
                                    break;
                                case 'Thursday':
                                    if ($weekly['is_thursday'] == 1) {
                                        if ($weekly['is_other_monday'] == 1) {
                                            $startTime = Carbon::createFromFormat('H:i:s', $weekly['is_other_thursday_start_time'])->format('H:i');
                                            $endTime = Carbon::createFromFormat('H:i:s', $weekly['is_other_thursday_end_time'])->format('H:i');
                                        }
                                    } else {
                                        //Kết thúc vòng for
                                        continue 3;
                                    }
                                    break;
                                case 'Friday':
                                    if ($weekly['is_friday'] == 1) {
                                        if ($weekly['is_other_friday'] == 1) {
                                            $startTime = Carbon::createFromFormat('H:i:s', $weekly['is_other_friday_start_time'])->format('H:i');
                                            $endTime = Carbon::createFromFormat('H:i:s', $weekly['is_other_friday_end_time'])->format('H:i');
                                        }
                                    } else {
                                        //Kết thúc vòng for
                                        continue 3;
                                    }
                                    break;
                                case 'Saturday':
                                    if ($weekly['is_saturday'] == 1) {
                                        if ($weekly['is_other_saturday'] == 1) {
                                            $startTime = Carbon::createFromFormat('H:i:s', $weekly['is_other_saturday_start_time'])->format('H:i');
                                            $endTime = Carbon::createFromFormat('H:i:s', $weekly['is_other_saturday_end_time'])->format('H:i');
                                        }
                                    } else {
                                        //Kết thúc vòng for
                                        continue 3;
                                    }
                                    break;
                                case 'Sunday':
                                    if ($weekly['is_sunday'] == 1) {
                                        if ($weekly['is_other_sunday'] == 1) {
                                            $startTime = Carbon::createFromFormat('H:i:s', $weekly['is_other_sunday_start_time'])->format('H:i');
                                            $endTime = Carbon::createFromFormat('H:i:s', $weekly['is_other_sunday_end_time'])->format('H:i');
                                        }
                                    } else {
                                        //Kết thúc vòng for
                                        continue 3;
                                    }
                                    break;
                            }
                            //Kiểm tra giờ bắt đầu, giờ kết thúc
                            if ($currentTime < $startTime || $currentTime > $endTime) {
                                //Kết thúc vòng for
                                continue 2;
                            }
                            break;
                        case 'M':
                            $monthly = $mMonthly->getMonthlyByPromotion($v['promotion_code']);

                            if (count($monthly) > 0) {
                                $next = false;

                                foreach ($monthly as $v1) {
                                    $startDate = Carbon::createFromFormat('Y-m-d H:i:s', $v1['run_date'] . ' ' . $v1['start_time'])->format('Y-m-d H:i');
                                    $endDate = Carbon::createFromFormat('Y-m-d H:i:s', $v1['run_date'] . ' ' . $v1['end_time'])->format('Y-m-d H:i');

                                    if ($currentDate > $startDate && $currentDate < $endDate) {
                                        $next = true;
                                    }
                                }

                                if ($next == false) {
                                    //Kết thúc vòng for
                                    continue 2;
                                }
                            } else {
                                //Kết thúc vòng for
                                continue 2;
                            }
                            break;
                        case 'R':
                            $fromTo = $mFromTo->getDateTimeByPromotion($v['promotion_code']);

                            if ($fromTo != null) {
                                $startFrom = Carbon::createFromFormat('Y-m-d H:i:s', $fromTo['form_date'] . ' ' . $fromTo['start_time'])->format('Y-m-d H:i');
                                $endFrom = Carbon::createFromFormat('Y-m-d H:i:s', $fromTo['to_date'] . ' ' . $fromTo['end_time'])->format('Y-m-d H:i');

                                if ($currentDate < $startFrom || $currentDate > $endFrom) {
                                    //Kết thúc vòng for
                                    continue 2;
                                }
                            }
                            break;
                    }
                }

                //Check nguồn đơn hàng
                if ($v['order_source'] != 'all' && $v['order_source'] != $orderSource) {
                    //Kết thúc vòng for
                    continue;
                }
                //Check đối tượng áp dụng
                if ($v['promotion_apply_to'] != 1 && $v['promotion_apply_to'] != null) {
                    //Lấy thông tin khách hàng
                    $getCustomer = $mCustomer->getItem($customerId);

                    if ($getCustomer == null || $getCustomer['customer_id'] == 1) {
                        //Kết thúc vòng for
                        continue;
                    }

                    if ($getCustomer['member_level_id'] == null) {
                        $getCustomer['member_level_id'] = 1;
                    }

                    $objectId = '';
                    if ($v['promotion_apply_to'] == 2) {
                        $objectId = $getCustomer['member_level_id'];
                    } else if ($v['promotion_apply_to'] == 3) {
                        $objectId = $getCustomer['customer_group_id'];
                    } else if ($v['promotion_apply_to'] == 4) {
                        $objectId = $v['customer_id'];
                    }

                    $getApply = $mPromotionApply->getApplyByObjectId($v['promotion_code'], $objectId);

                    if ($getApply == null) {
                        //Kết thúc vòng for
                        continue;
                    }

                }

                if ($v['promotion_type'] == 1) {
                    //Khuyến mãi giảm giá
                    $promotionPrice [] = $v;
                } else if ($v['promotion_type'] == 2) {
                    if ($quantity >= $v['quantity_buy']) {
                        $multiplication = intval($quantity / $v['quantity_buy']);
                        //Số quà được tặng
                        $totalGift = intval($v['quantity_gift'] * $multiplication);
                        //Lấy quota_use nếu tính áp dụng promotion này
                        $quotaUse = floatval($v['quota_use']) + $totalGift;
                        //Check số lượng cần mua để dc quà + quota_use
                        if ($v['quota'] == 0 || $v['quota'] == '' || $quotaUse <= floatval($v['quota'])) {
                            //Lấy giá trị quà tặng
                            $priceGift = $this->getPriceObject($v['gift_object_type'], $v['gift_object_code']);

                            $v['quantity_gift'] = $totalGift;
                            $v['quota'] = !empty($v['quota']) ? $v['quota'] : 0;
                            $v['quota_use'] = floatval($v['quota_use']);
                            $v['total_price_gift'] = $priceGift * $totalGift;

                            $promotionQuota [] = $v;
                        }
                    }

                }

            }
        }

        if (count($promotionPrice) > 0) {
            //Lấy CTKM có giá ưu đãi nhất
            $getPriceMostPreferential = $this->choosePriceMostPreferential($promotionPrice);

            $promotionLog [] = $getPriceMostPreferential;
        }

        if (count($promotionQuota) > 0) {
            //Lấy CTKM có quà tặng ưu đãi nhất
            $getGiftMostPreferential = $this->getGiftMostPreferential($promotionQuota);
            $promotionLog [] = $getGiftMostPreferential;
        }

        foreach ($promotionLog as $v) {
            $result [] = [
                'promotion_id' => $v['promotion_id'],
                'promotion_code' => $v['promotion_code'],
                'start_date' => $v['start_date'],
                'end_date' => $v['end_date'],
                'order_id' => $orderId,
                'order_code' => $orderCode,
                'object_type' => $objectType,
                'object_id' => $objectId,
                'object_code' => $objectCode,
                'quantity' => $quantity,
                'base_price' => $v['base_price'],
                'promotion_price' => $v['promotion_price'],
                'gift_object_type' => $v['gift_object_type'],
                'gift_object_id' => $v['gift_object_id'],
                'gift_object_code' => $v['gift_object_code'],
                'quantity_gift' => $v['quantity_gift'],
                'created_by' => Auth()->id(),
                'updated_by' => Auth()->id(),
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ];

            if ($v['promotion_type'] == 2) {
                $resultPlusQuota = [
                    'promotion_code' => $v['promotion_code'],
                    'quantity_gift' => $v['quantity_gift']
                ];
            }
        }

        return [
            'promotion_log' => $result,
            'promotion_quota' => $resultPlusQuota
        ];
    }

    /**
     * Lấy giá trị khuyến mãi sp, dv, thẻ dv
     *
     * @param $objectType
     * @param $objectCode
     * @return int
     */
    private function getPriceObject($objectType, $objectCode)
    {
        $price = 0;

        switch ($objectType) {
            case 'product':
                $mProduct = app()->get(ProductChildTable::class);
                //Lấy thông tin sp khuyến mãi
                $getProduct = $mProduct->getProductPromotion($objectCode);
                $price = $getProduct['new_price'];

                break;
            case 'service':
                $mService = app()->get(ServiceTable::class);
                //Lấy thông tin dv khuyến mãi
                $getService = $mService->getServicePromotion($objectCode);
                $price = $getService['new_price'];

                break;
            case 'service_card':
                $mServiceCard = app()->get(ServiceCardTable::class);
                //Lấy thông tin thẻ dv khuyến mãi
                $getServiceCard = $mServiceCard->getServiceCardPromotion($objectCode);
                $price = $getServiceCard['new_price'];

                break;
        }

        return floatval($price);
    }

    /**
     * Chọn CTKM giảm giá ưu đãi nhất
     *
     * @param $arrPrice
     * @return array
     */
    private function choosePriceMostPreferential($arrPrice)
    {
        //Lấy giá trị quà tặng có giá trị cao nhất
        $minPrice = array_column($arrPrice, 'promotion_price');
        //Sắp xếp lại array có quà tặng giá trị cao nhất
        array_multisort($minPrice, SORT_ASC, $arrPrice);
        //Lấy CTKM có giá ưu đãi nhất
        return [
            'promotion_id' => $arrPrice[0]['promotion_id'],
            'promotion_code' => $arrPrice[0]['promotion_code'],
            'promotion_type' => $arrPrice[0]['promotion_type'],
            'start_date' => $arrPrice[0]['start_date'],
            'end_date' => $arrPrice[0]['end_date'],
            'base_price' => $arrPrice[0]['base_price'],
            'promotion_price' => $arrPrice[0]['promotion_price'],
            'gift_object_type' => $arrPrice[0]['gift_object_type'],
            'gift_object_id' => $arrPrice[0]['gift_object_id'],
            'gift_object_code' => $arrPrice[0]['gift_object_code'],
            'quantity_gift' => $arrPrice[0]['quantity_gift'],
        ];
    }

    /**
     * Lấy quà tặng ưu đãi nhất
     *
     * @param $arrGift
     * @return array
     */
    private function getGiftMostPreferential($arrGift)
    {
        $result = [];

        if (count($arrGift) == 1) {
            //Có 1 CTKM quà tặng thì lấy chính nó
            $result [] = [
                'promotion_type' => $arrGift[0]['promotion_type'],
                'promotion_id' => $arrGift[0]['promotion_id'],
                'promotion_code' => $arrGift[0]['promotion_code'],
                'start_date' => $arrGift[0]['start_date'],
                'end_date' => $arrGift[0]['end_date'],
                'base_price' => $arrGift[0]['base_price'],
                'promotion_price' => $arrGift[0]['promotion_price'],
                'gift_object_type' => $arrGift[0]['gift_object_type'],
                'gift_object_id' => $arrGift[0]['gift_object_id'],
                'gift_object_code' => $arrGift[0]['gift_object_code'],
                'quantity_gift' => $arrGift[0]['quantity_gift'],
            ];
        } else if (count($arrGift) > 1) {
            //Có nhiều CTKM quà tặng
            //Lấy quà tặng có giá trị cao nhất
            $giftPreferential = $this->chooseGiftPreferential($arrGift);

            $result = $giftPreferential;

            if (count($result) > 1) {
                //Lấy quà tặng có số lượng mua thấp nhất
                $giftMinBuy = $this->chooseGiftMinBuy($result);

                $result = $giftMinBuy;
            }

            if (count($result) > 1) {
                //Lấy quà tặng có quota - quota_use còn nhiều nhất (ưu tiên quota != 0 ko giới hạn)
                $giftQuota = $this->chooseGiftQuota($result);

                $result = $giftQuota;
            }
        }

        return $result[0];
    }

    /**
     * Chọn quà tặng có giá trị cao nhất
     *
     * @param $arrGift
     * @return array
     */
    private function chooseGiftPreferential($arrGift)
    {
        $result = [];
        //Lấy giá trị quà tặng có giá trị cao nhất
        $giftPrice = array_column($arrGift, 'total_price_gift');
        //Sắp xếp lại array có quà tặng giá trị cao nhất
        array_multisort($giftPrice, SORT_DESC, $arrGift);

        $result [] = [
            'promotion_id' => $arrGift[0]['promotion_id'],
            'promotion_code' => $arrGift[0]['promotion_code'],
            'promotion_type' => $arrGift[0]['promotion_type'],
            'start_date' => $arrGift[0]['start_date'],
            'end_date' => $arrGift[0]['end_date'],
            'base_price' => $arrGift[0]['base_price'],
            'promotion_price' => $arrGift[0]['promotion_price'],
            'gift_object_type' => $arrGift[0]['gift_object_type'],
            'gift_object_id' => $arrGift[0]['gift_object_id'],
            'gift_object_code' => $arrGift[0]['gift_object_code'],
            'quantity_gift' => $arrGift[0]['quantity_gift'],
            //mới update param thêm
            'quantity_buy' => $arrGift[0]['quantity_buy'],
            'quota' => $arrGift[0]['quota'],
            'quota_use' => $arrGift[0]['quota_use'],
            'total_price_gift' => $arrGift[0]['total_price_gift']
        ];

        unset($arrGift[0]);

        foreach ($arrGift as $v) {
            //Kiểm tra có promotion nào có giá trị = với promotion cao nhất
            if ($v['total_price_gift'] >= $result[0]['total_price_gift']) {
                $result [] = [
                    'promotion_id' => $v['promotion_id'],
                    'promotion_code' => $v['promotion_code'],
                    'promotion_type' => $v['promotion_type'],
                    'start_date' => $v['start_date'],
                    'end_date' => $v['end_date'],
                    'base_price' => $v['base_price'],
                    'promotion_price' => $v['promotion_price'],
                    'gift_object_type' => $v['gift_object_type'],
                    'gift_object_id' => $v['gift_object_id'],
                    'gift_object_code' => $v['gift_object_code'],
                    'quantity_gift' => $v['quantity_gift'],
                    //mới update param thêm
                    'quantity_buy' => $v['quantity_buy'],
                    'quota' => $v['quota'],
                    'quota_use' => $v['quota_use'],
                    'total_price_gift' => $v['total_price_gift']
                ];
            }
        }

        return $result;
    }

    /**
     * Chọn quà tặng có lượng mua thấp nhất
     *
     * @param $arrGift
     * @return array
     */
    private function chooseGiftMinBuy($arrGift)
    {
        //Có nhiều promotion bằng giá trị thì check số lượng mua (lợi ích khách hàng)
        $result = [];
        //Lấy quà tặng có số lượng mua thấp nhất
        $quantityBuy = array_column($arrGift, 'quantity_buy');
        //Sắp xếp lại array có số lượng cần mua thấp nhất
        array_multisort($quantityBuy, SORT_ASC, $arrGift);

        $result [] = [
            'promotion_id' => $arrGift[0]['promotion_id'],
            'promotion_code' => $arrGift[0]['promotion_code'],
            'promotion_type' => $arrGift[0]['promotion_type'],
            'start_date' => $arrGift[0]['start_date'],
            'end_date' => $arrGift[0]['end_date'],
            'base_price' => $arrGift[0]['base_price'],
            'promotion_price' => $arrGift[0]['promotion_price'],
            'gift_object_type' => $arrGift[0]['gift_object_type'],
            'gift_object_id' => $arrGift[0]['gift_object_id'],
            'gift_object_code' => $arrGift[0]['gift_object_code'],
            'quantity_gift' => $arrGift[0]['quantity_gift'],
            //mới update param thêm
            'quantity_buy' => $arrGift[0]['quantity_buy'],
            'quota' => $arrGift[0]['quota'],
            'quota_use' => $arrGift[0]['quota_use'],
            'total_price_gift' => $arrGift[0]['total_price_gift']
        ];

        unset($arrGift[0]);

        foreach ($arrGift as $v) {
            //Kiểm tra có promotion nào có giá trị = với promotion cao nhất
            if ($v['quantity_buy'] == $result[0]['quantity_buy']) {
                $result [] = [
                    'promotion_id' => $v['promotion_id'],
                    'promotion_code' => $v['promotion_code'],
                    'promotion_type' => $v['promotion_type'],
                    'start_date' => $v['start_date'],
                    'end_date' => $v['end_date'],
                    'base_price' => $v['base_price'],
                    'promotion_price' => $v['promotion_price'],
                    'gift_object_type' => $v['gift_object_type'],
                    'gift_object_id' => $v['gift_object_id'],
                    'gift_object_code' => $v['gift_object_code'],
                    'quantity_gift' => $v['quantity_gift'],
                    //mới update param thêm
                    'quantity_buy' => $v['quantity_buy'],
                    'quota' => $v['quota'],
                    'quota_use' => $v['quota_use'],
                    'total_price_gift' => $v['total_price_gift']
                ];
            }
        }

        return $result;
    }

    /**
     * Chọn quà tặng có quota còn lại cao nhất
     *
     * @param $arrGift
     * @return array
     */
    private function chooseGiftQuota($arrGift)
    {
        //Có nhiều promotion bằng giá trị + số lượng mua thì kiểm tra quota_use con lại (ưu tiên promotion có quota != 0)
        $result = [];

        $arrLimited = [];
        $arrUnLimited = [];

        foreach ($arrGift as $v) {
            if ($v['quota'] != 0) {
                $v['quota_balance'] = $v['quota'] - $v['quota_use'];
                $arrLimited [] = $v;
            } else {
                $arrUnLimited [] = $v;
            }
        }

        if (count($arrLimited) > 0) {
            //Ưu tiên lấy quà tặng có giới hạn quota

            //Lấy quà tặng có quota còn lại cao nhất
            $quantityQuota = array_column($arrLimited, 'quota_balance');
            //Sắp xếp lại array có số lượng cần mua thấp nhất
            array_multisort($quantityQuota, SORT_DESC, $arrLimited);

            $result [] = [
                'promotion_id' => $arrLimited[0]['promotion_id'],
                'promotion_code' => $arrLimited[0]['promotion_code'],
                'promotion_type' => $arrLimited[0]['promotion_type'],
                'start_date' => $arrLimited[0]['start_date'],
                'end_date' => $arrLimited[0]['end_date'],
                'base_price' => $arrLimited[0]['base_price'],
                'promotion_price' => $arrLimited[0]['promotion_price'],
                'gift_object_type' => $arrLimited[0]['gift_object_type'],
                'gift_object_id' => $arrLimited[0]['gift_object_id'],
                'gift_object_code' => $arrLimited[0]['gift_object_code'],
                'quantity_gift' => $arrLimited[0]['quantity_gift'],
                //mới update param thêm
                'quantity_buy' => $arrLimited[0]['quantity_buy'],
                'quota' => $arrLimited[0]['quota'],
                'quota_use' => $arrLimited[0]['quota_use'],
                'total_price_gift' => $arrLimited[0]['total_price_gift']
            ];

            unset($arrLimited[0]);

            foreach ($arrLimited as $v) {
                //Kiểm tra có promotion nào có giá trị = với promotion cao nhất
                if ($v['quota_balance'] == ($result[0]['quota'] - $result[0]['quota_use'])) {
                    $result [] = [
                        'promotion_id' => $v['promotion_id'],
                        'promotion_code' => $v['promotion_code'],
                        'promotion_type' => $v['promotion_type'],
                        'start_date' => $v['start_date'],
                        'end_date' => $v['end_date'],
                        'base_price' => $v['base_price'],
                        'promotion_price' => $v['promotion_price'],
                        'gift_object_type' => $v['gift_object_type'],
                        'gift_object_id' => $v['gift_object_id'],
                        'gift_object_code' => $v['gift_object_code'],
                        'quantity_gift' => $v['quantity_gift'],
                        //mới update param thêm
                        'quantity_buy' => $v['quantity_buy'],
                        'quota' => $v['quota'],
                        'quota_use' => $v['quota_use'],
                        'total_price_gift' => $v['total_price_gift']
                    ];
                }
            }
        }

        if (count($result) == 0 && count($arrUnLimited) > 0) {
            //Lấy quà tặng có quota_use thấp nhất
            $quantityQuotaUse = array_column($arrUnLimited, 'quota_use');
            //Sắp xếp lại array có số lượng cần mua thấp nhất
            array_multisort($quantityQuotaUse, SORT_ASC, $arrUnLimited);

            $result [] = [
                'promotion_id' => $arrUnLimited[0]['promotion_id'],
                'promotion_code' => $arrUnLimited[0]['promotion_code'],
                'promotion_type' => $arrUnLimited[0]['promotion_type'],
                'start_date' => $arrUnLimited[0]['start_date'],
                'end_date' => $arrUnLimited[0]['end_date'],
                'base_price' => $arrUnLimited[0]['base_price'],
                'promotion_price' => $arrUnLimited[0]['promotion_price'],
                'gift_object_type' => $arrUnLimited[0]['gift_object_type'],
                'gift_object_id' => $arrUnLimited[0]['gift_object_id'],
                'gift_object_code' => $arrUnLimited[0]['gift_object_code'],
                'quantity_gift' => $arrUnLimited[0]['quantity_gift'],
                //mới update param thêm
                'quantity_buy' => $arrUnLimited[0]['quantity_buy'],
                'quota' => $arrUnLimited[0]['quota'],
                'quota_use' => $arrUnLimited[0]['quota_use'],
                'total_price_gift' => $arrUnLimited[0]['total_price_gift']
            ];

            unset($arrUnLimited[0]);

            foreach ($arrUnLimited as $v) {
                //Kiểm tra có promotion nào có giá trị = với promotion cao nhất
                if ($v['quota_use'] <= $result[0]['quota_use']) {
                    $result [] = [
                        'promotion_id' => $v['promotion_id'],
                        'promotion_code' => $v['promotion_code'],
                        'promotion_type' => $v['promotion_type'],
                        'start_date' => $v['start_date'],
                        'end_date' => $v['end_date'],
                        'base_price' => $v['base_price'],
                        'promotion_price' => $v['promotion_price'],
                        'gift_object_type' => $v['gift_object_type'],
                        'gift_object_id' => $v['gift_object_id'],
                        'gift_object_code' => $v['gift_object_code'],
                        'quantity_gift' => $v['quantity_gift'],
                        //mới update param thêm
                        'quantity_buy' => $v['quantity_buy'],
                        'quota' => $v['quota'],
                        'quota_use' => $v['quota_use'],
                        'total_price_gift' => $v['total_price_gift']
                    ];
                }
            }
        }

//        if (count($result) > 1) {
//            $result = $result[0];
//        }

        return $result;
    }

    /**
     * Lấy hình thức thanh toán
     *
     * @param $lang
     * @return mixed
     * @throws OrderRepoException
     */
    public function getPaymentMethod($lang)
    {
        try {
            $mPaymentMethod = app()->get(PaymentMethodTable::class);

            return $mPaymentMethod->getPaymentMethod($lang);
        } catch (\Exception | QueryException $exception) {
            throw new OrderRepoException(OrderRepoException::GET_PAYMENT_METHOD_FAILED);
        }
    }

    /**
     * Đặt hàng lại
     *
     * @param $input
     * @return mixed|void
     * @throws OrderRepoException
     */
    public function reOrder($input)
    {
        try {
            $mOrderDetail = app()->get(OrderDetailTable::class);
            $mProductChild = app()->get(ProductChildTable::class);
            $mHome = app()->get(HomeRepoInterface::class);
            $mServiceBranchPrice = app()->get(ServiceBranchPriceTable::class);
            $mServiceCard = app()->get(ServiceCardListTable::class);

            //Lấy thông tin đơn hàng
            $getOrder = $this->order->getOrderByCode($input['order_code']);
            //Lấy thông tin chi tiết đơn hàng
            $getOrderDetail = $mOrderDetail->getDetailOrderList($getOrder['order_id']);

            $data['product'] = [];
            $data['service'] = [];
            $data['service_card'] = [];

            foreach ($getOrderDetail as $v) {
                if ($v['object_type'] == 'product') {
                    //Chỉnh lại giá bán hiện tại của sản phẩm
                    $getChild = $mProductChild->getProductByCode($v['object_code']);

                    if ($getChild != null) {
                        $getPromotion = $mHome->getPromotionDetail('product', $getChild['product_code'], Auth::id(), 'app', null, $getChild['product_id']);

                        $promotion = [];
                        if (isset($getPromotion) && $getPromotion['price'] != null || $getPromotion['price'] != null) {
                            if (isset($getPromotion['price']) && $getPromotion['price'] != null) {
                                // Tinh phan tram
                                if ($getPromotion['price'] < $getChild['new_price']) {
                                    $percent = $getPromotion['price'] / $getChild['new_price'] * 100;
                                    $promotion['price'] = (100 - round($percent, 2)) . '%';
                                    // Tính lại giá khi có khuyến mãi
                                    $getChild['new_price'] = ($getChild['new_price'] * $percent) / 100;
                                }
                            }
                            if ($getPromotion['gift'] != null) {
                                $promotion['gift'] = $getPromotion['gift'];
                            }

                        }
                        if (empty($promotion)) {
                            $promotion = null;
                        }
                        $getChild['promotion'] = $promotion;

                        $data['product'] [] = $getChild;
                    }
                } else if ($v['object_type'] == 'service') {
                    //Chỉnh lại giá bán hiện tại của dịch vụ
                    $getService = $mServiceBranchPrice->getDetail($v['object_code']);

                    if ($getService != null) {
                        $getPromotion = $mHome->getPromotionDetail('service', $getService['service_code'], Auth()->id(), 'app', null, $getService['service_id']);

                        $promotion = [];
                        if (isset($getPromotion) && $getPromotion['price'] != null || $getPromotion['price'] != null) {
                            if (isset($getPromotion['price']) && $getPromotion['price'] != null) {
                                // Tinh phan tram
                                if ($getPromotion['price'] < $getService['new_price']) {
                                    $percent = $getPromotion['price'] / $getService['new_price'] * 100;
                                    $promotion['price'] = (100 - round($percent, 2)) . '%';
                                    $getService['is_new'] = 0;
                                    // Tính lại giá khi có khuyến mãi
                                    $getService['new_price'] = ($getService['new_price'] * $percent) / 100;
                                }
                            }
                            if ($getPromotion['gift'] != null) {
                                $promotion['gift'] = $getPromotion['gift'];
                                $getService['is_new'] = 0;
                            }

                        } else {
                            // service new
                            $getService['is_new'] = 1;
                            $getService['promotion'] = null;
                        }

                        if (empty($promotion)) {
                            $promotion = null;
                        }
                        $getService['promotion'] = $promotion;

                        $data['service'] [] = $getService;
                    }
                } else if ($v['object_type'] == 'service_card') {
                    //Chỉnh lại giá bán hiện tại của thẻ dịch vụ
                    $getServiceCard = $mServiceCard->getServiceCard($v['object_code']);

                    if ($getServiceCard != null) {
                        $data['service_card'] [] = $getServiceCard;
                    }
                }
            }

            return $data;
        } catch (\Exception | QueryException $exception) {
            throw new OrderRepoException(OrderRepoException::RE_ORDER_FAILED, $exception->getMessage());
        }
    }

    /**
     * Kiểm tra tồn kho - thay đổi giá - đã xóa
     *
     * @param $input
     * @return mixed
     * @throws OrderRepoException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function checkInventory($input)
    {
        try {
            $data['inventory'] = [];
            $data['change_price'] = [];
            $data['is_delete'] = [];

            if (!isset($input['product_list']) || $input['product_list'] == 0) {
                throw new OrderRepoException(OrderRepoException::CHECK_INVENTORY_FAILED);
            }

            //Lấy chi nhánh mặc định nếu ko có chi nhánh
            $mConfig = app()->get(ConfigTable::class);
            $branchDefault = $mConfig->getBranchApplyOrder();

            $input['branch_code'] = $input['branch_code'] != null ? $input['branch_code'] : $branchDefault['branch_code'];

            foreach ($input['product_list'] as $v) {
                $getCheck = $this->checkProduct($input['branch_code'], $v['product_id'], $v['product_code'], $v['quantity'], $v['price']);

                if (count($getCheck['inventory']) > 0) {
                    $data['inventory'] [] = $getCheck['inventory'];
                }
                if (count($getCheck['change_price']) > 0) {
                    $data['change_price'] [] = $getCheck['change_price'];
                }
                if (count($getCheck['is_delete']) > 0) {
                    $data['is_delete'] [] = $getCheck['is_delete'];
                }
            }

            if (count($data['inventory']) == 0 && count($data['change_price']) == 0 && count($data['is_delete']) == 0) {
                throw new OrderRepoException(OrderRepoException::CHECK_INVENTORY_FAILED);
            }

            return $data;
        } catch (\Exception | QueryException $exception) {
            throw new OrderRepoException(OrderRepoException::RE_ORDER_FAILED, $exception->getMessage());
        }
    }


    /**
     * function kiểm tra tồn kho - thay đổi giá - đã xóa
     *
     * @param $branchCode
     * @param $productId
     * @param $productCode
     * @param $quantity
     * @param $price
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function checkProduct($branchCode, $productId, $productCode, $quantity, $price)
    {
        $mProductChild = app()->get(ProductChildTable::class);
        $mProduct = app()->get(ProductRepoInterface::class);
        $mHome = app()->get(HomeRepoInterface::class);

        $data['inventory'] = [];
        $data['change_price'] = [];
        $data['is_delete'] = [];

        //Lấy cấu hình cho bán âm ko, nếu cho bán âm thì ko cần check tồn kho
        $mConfig = app()->get(ConfigTable::class);

        $orderMinus = $mConfig->getConfig('sell_minus'); //value = 0 là ko cho bán âm, 1 ngược lại

        //Kiểm tra tồn kho
        if (isset($branchCode) && $branchCode != null && $orderMinus['value'] == 0) {
            $inventory = $this->getInventoryProduct($productCode, $branchCode);

            //Số lượng mua vướt quá tồn kho
            if (isset($inventory) && $inventory['inventory'] < $quantity) {
                $data['inventory'] = [
                    'product_id' => $productId,
                    'product_code' => $productCode,
                    'quantity' => $quantity,
                    'new_quantity' => $inventory['inventory']
                ];
            }

        }
        //Lấy thông tin sản phẩm
        $getChild = $mProductChild->getProductByCode($productCode);

        if ($getChild != null) {
            //Lấy thông tin khuyến mãi của sản phẩm
            $getPromotion = $mHome->getPromotionDetail('product', $getChild['product_code'], Auth::id(), 'app', null, $getChild['product_id']);

            if (isset($getPromotion) && $getPromotion['price'] != null || $getPromotion['price'] != null) {
                if (isset($getPromotion['price']) && $getPromotion['price'] != null && $getPromotion['price'] < $getChild['new_price']) {
                    $percent = $getPromotion['price'] / $getChild['new_price'] * 100;
                    // Tính lại giá khi có khuyến mãi
                    $getChild['new_price'] = ($getChild['new_price'] * $percent) / 100;
                }
            }
            //Sảm phẩm có thay đổi giá
            if (floatval($getChild['new_price']) != floatval($price)) {
                $data['change_price'] = [
                    'product_id' => $productId,
                    'product_code' => $productCode,
                    'price' => $price,
                    'new_price' => floatval($getChild['new_price'])
                ];
            }
        } else {
            //Sản phẩm đã xóa
            $data['is_delete'] = [
                'product_id' => $productId,
                'product_code' => $productCode,
            ];
        }

        return $data;
    }

    /**
     * Lấy thông tin tồn kho sủa sp
     *
     * @param $productCode
     * @param $branchCode
     * @return array
     */
    private function getInventoryProduct($productCode, $branchCode)
    {
        if ($branchCode != null) {
            $mProductInventory = app()->get(ProductInventoryTable::class);

            $quantity = 0;
            //Lấy thông tin tồn kho của sp
            $getInventory = $mProductInventory->getInventory($productCode, $branchCode);
            if ($getInventory != null) {
                $quantity = intval($getInventory['quantity']);
            }

            return [
                'product_code' => $productCode,
                'inventory' => $quantity
            ];
        }
    }

    /**
     * Thanh toán đơn hàng
     *
     * @param $input
     * @return mixed|void
     * @throws OrderRepoException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function orderPayment($input)
    {
        DB::beginTransaction();
        try {
            $statusOrder = 'paysuccess';

            //Lấy thông tin đơn hàng
            $getOrder = $this->order->getOrderByCode($input['order_code']);

            if ($input['total_amount_receipt'] > 0
                && $input['customer_id'] != 1
                && $input['total_amount_receipt'] < $input['amount_bill']) {
                $mSpaInfo = app()->get(SpaInfoTable::class);
                //Check cho thanh toán thiếu không
                $getInfo = $mSpaInfo->getInfo(1);

                if ($getInfo['is_part_paid'] == 1) {
                    $statusOrder = 'pay-half';
                    //Cho thanh toán thiếu nhưng nếu tạo từ app thì ko insert vào công nợ
                    if ($getOrder['order_source_id'] == 1) {
                        //Tạo công nợ nếu trả thiếu
                        $this->insertDebt(
                            $input['customer_id'],
                            $input['order_id'],
                            $input['amount_bill'] - $input['total_amount_receipt'],
                            $input['note']
                        );
                    }

                } else {
                    throw new OrderRepoException(OrderRepoException::PAYMENT_FAILED, __('Tiền thanh toán không hợp lệ'));
                }
            }
            $mReceipt = app()->get(ReceiptTable::class);
            //Tính tiền thòi lại
            $amountReturn = $input['total_amount_receipt'] - $input['amount_bill'];

            //Tạo phiếu thanh toán
            $receiptId = $mReceipt->add([
                'customer_id' => $input['customer_id'],
                'staff_id' => Auth()->id(),
                'branch_id' => Auth()->user()->branch_id,
                'object_id' => $input['order_id'],
                'object_type' => 'order',
                'order_id' => $input['order_id'],
                'total_money' => $input['total_amount_receipt'],
                'status' => 'paid',
                'is_discount' => 1,
                'amount' => $input['total_amount_receipt'],
                'amount_paid' => $input['total_amount_receipt'],
                'amount_return' => $amountReturn > 0 ? $amountReturn : 0,
                'note' => $input['note'],
                'created_by' => Auth()->id(),
                'updated_by' => Auth()->id(),
                'receipt_type_code' => 'RTC_ORDER',
                'object_accounting_type_code' => '', // order code
                'object_accounting_id' => $input['order_id'], // order id

            ]);
            //Update mã phiếu thu
            $mReceipt->edit([
                'receipt_code' => 'TT_' . date('dmY') . sprintf("%02d", $receiptId)
            ], $receiptId);
            //Lưu chi tiết thanh toán
            $mReceiptDetail = app()->get(ReceiptDetailTable::class);
            $receiptDetail = [];

            if (count($input['payment_method']) > 0) {
                $mReceiptOnline = app()->get(ReceiptOnlineTable::class);
                $mPaymentMethod = app()->get(PaymentMethodTable::class);

                foreach ($input['payment_method'] as $v) {
                    $receiptDetail [] = [
                        'receipt_id' => $receiptId,
                        'cashier_id' => Auth()->id(),
                        'payment_method_code' => $v['payment_method_code'],
                        'amount' => $v['money'],
                        'created_by' => Auth()->id(),
                        'updated_by' => Auth()->id()
                    ];

                    //Lấy thông tin phương thức thanh toán
                    $infoMethod = $mPaymentMethod->getInfoByCode($v['payment_method_code']);

                    if ($v['payment_method_code'] == 'VNPAY') {
                        //Nếu là VNPAY thì check có truyền transaction_code_vnpay
                        if (isset($input['transaction_code_vnpay']) && $input['transaction_code_vnpay'] != null) {
                            //Cập nhật trạng thái thanh toán online VNPAY
                            $mReceiptOnline->editByCode([
                                "status" => self::RECEIPT_ONLINE_SUCCESS,
                                "amount_paid" => $v['money']
                            ], $input['transaction_code_vnpay']);
                        }
                    } else if ($v['payment_method_code'] == "TRANSFER") {
                        //Thêm thanh toán online
                        $mReceiptOnline->add([
                            "object_type" => $getOrder['order_source_id'] == 1 ? 'order' : 'order_online',
                            "object_id" => $getOrder['order_id'],
                            "object_code" => $getOrder['order_code'],
                            "payment_method_code" => $v['payment_method_code'],
                            "amount_paid" => $v['money'],
                            "payment_time" => Carbon::now()->format('Y-m-d H:i:s'),
                            "type" => $infoMethod['payment_method_type'],
                            "platform" => "app_staff",
                            "performer_name" => $getOrder['full_name'],
                            "performer_phone" => $getOrder['phone'],
                            "status" => self::RECEIPT_ONLINE_SUCCESS
                        ]);
                    }
                }
            }
            $mReceiptDetail->insert($receiptDetail);

            //Insert sms log khi thanh toán thành công
            $this->saveSmsLog($input['customer_id'], 'paysuccess', $input['order_id']);
            $this->saveEmailLog($input['customer_id'], 'paysuccess', $input['order_id']);
            //Cập nhật trạng thái đơn hàng + lưu người thanh toán, ngày thanh toán
            $this->order->edit([
                'process_status' => $statusOrder,
                'cashier_by' => Auth()->id(),
                'cashier_date' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_by' => Auth()->id()
            ], $input['order_id']);

            $arrWarranty = [];

            //Tính hoa hồng nv phục vụ
            if (count($input['detail_order']) > 0) {
                foreach ($input['detail_order'] as $v) {
                    if (in_array($v['object_type'], ['product', 'service', 'service_card', 'member_card'])) {
                        //Tính hoa hồng
                        $this->insertOrderCommission(
                            $v['order_detail_id'],
                            $v['refer_id'],
                            $v['staff_id'],
                            $v['object_type'],
                            $v['object_id'],
                            $v['object_code'],
                            $v['amount']
                        );
                    }

                    if (in_array($v['object_type'], ['product', 'service', 'service_card'])) {
                        $arrWarranty [] = [
                            "object_type" => $v['object_type'],
                            "object_id" => $v['object_id'],
                            "object_code" => $v['object_code'],
                            "price" => $v['price'],
                            "quantity" => $v['quantity']
                        ];
                    }
                }
            }

            //Đơn hàng từ app thì cập nhật trạng thái đơn hàng cần giao, lưu log đơn hàng đã xác nhận
            if ($getOrder['order_source_id'] == 2) {
                //Cập nhật trạng thái đơn hàng cần giao (nếu là đơn hàng tại quầy thì không active)
                if ($getOrder['receive_at_counter'] == 0) {
                    $mDelivery = app()->get(DeliveryTable::class);
                    $mDelivery->edit([
                        'is_actived' => 1
                    ], $input['order_id']);
                }
                //Insert order log đơn hàng đã xác nhận
                $mOrderLog = app()->get(OrderLogTable::class);
                $checkConfirm = $mOrderLog->checkStatusLog($input['order_id'], 'confirmed');
                if ($checkConfirm == null) {
                    $mOrderLog->insert([
                        [
                            'order_id' => $input['order_id'],
                            'created_type' => 'backend',
                            'status' => 'confirmed',
                            'created_by' => Auth()->id(),
                            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                            'note_vi' => 'Đã xác nhận đơn hàng',
                            'note_en' => 'Order confirm'
                        ]
                    ]);
                }
                //Insert order log đơn hàng đang xử lý
                $checkPacking = $mOrderLog->checkStatusLog($input['order_id'], 'packing');
                if ($checkPacking == null) {
                    $mOrderLog->insert([
                        [
                            'order_id' => $input['order_id'],
                            'created_type' => 'backend',
                            'status' => 'packing',
                            'created_by' => Auth()->id(),
                            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                            'note_vi' => 'Đang xử lý',
                            'note_en' => 'Processing'
                        ]
                    ]);
                }
            }
            //Xoá tất cả phiếu giao hàng của đơn hàng
            $this->removeDeliveryHistory($input['order_id'], $getOrder['process_status']);
            //Thêm phiếu bảo hành điện tử
            if ($input['customer_id'] != 1) {
                $this->insertWarrantyCard($getOrder['customer_code'], $getOrder['order_code'], $arrWarranty);
            }

            DB::commit();

            //Gửi thông báo khách hàng
            FunctionSendNotify::dispatch([
                'type' => SEND_NOTIFY_CUSTOMER,
                'key' => 'order_status_S',
                'customer_id' => $input['customer_id'],
                'object_id' => $input['order_id'],
                'tenant_id' => session()->get('idTenant')
            ]);
            //Lưu log ZNS
            FunctionSendNotify::dispatch([
                'type' => SEND_ZNS_CUSTOMER,
                'key' => 'order_thanks',
                'customer_id' => $input['customer_id'],
                'object_id' => $input['order_id'],
                'tenant_id' => session()->get('idTenant')
            ]);
            //Cộng điểm thưởng khi thanh toán
            if ($input['total_amount_receipt'] >= $input['amount_bill']) {
                $this->plusPointReceiptFull(['receipt_id' => $receiptId]);
            } else {
                $this->plusPointReceipt(['receipt_id' => $receiptId]);
            }
        } catch (\Exception | QueryException $exception) {
            DB::rollBack();
            throw new OrderRepoException(OrderRepoException::PAYMENT_FAILED, $exception->getMessage());
        }
    }

    /**
     * Thêm công nợ khách hàng
     *
     * @param $customerId
     * @param $orderId
     * @param $amount
     * @param $note
     */
    private function insertDebt($customerId, $orderId, $amount, $note)
    {
        $mCustomerDebt = app()->get(CustomerDebtTable::class);
        //Tạo công nợ
        $debtId = $mCustomerDebt->add([
            'customer_id' => $customerId,
            'debt_code' => 'debt',
            'staff_id' => Auth()->id(),
            'branch_id' => Auth()->user()->branch_id,
            'note' => $note,
            'debt_type' => 'order',
            'order_id' => $orderId,
            'status' => 'unpaid',
            'amount' => $amount,
            'created_by' => Auth()->id(),
            'updated_by' => Auth()->id()
        ]);
        //Update order code
        $orderCode = 'CN_' . date('dmY') . sprintf("%02d", $debtId);
        $mCustomerDebt->edit([
            'debt_code' => $orderCode
        ], $debtId);
    }

    /**
     * Cancel tất cả phiếu giao hàng của đơn hàng
     *
     * @param $orderId
     * @param $status
     */
    public function removeDeliveryHistory($orderId, $status)
    {
        $mDeliveryHistory = new DeliveryHistoryTable();
        $mDeliveryHistoryLog = new DeliveryHistoryLogTable();

        if ($status) {
            //Kiểm tra đơn hàng đó có phiếu giao hàng chưa
            $getDeliveryHistory = $mDeliveryHistory->getHistoryByOrder($orderId);
            if (count($getDeliveryHistory) > 0) {
                foreach ($getDeliveryHistory as $item) {
                    //Xóa phiếu giao hàng
                    $mDeliveryHistory->edit([
                        'status' => 'cancel'
                    ], $item['delivery_history_id']);
                    //Lưu log xóa phiếu giao hàng
                    $mDeliveryHistoryLog->add([
                        "delivery_history_id" => $item['delivery_history_id'],
                        "status" => "cancel",
                        "created_by" => Auth()->id(),
                        "created_type" => "backend"
                    ]);
                }
            }
        }
    }

    /**
     * Lưu hoa hồng của đơn hàng
     *
     * @param $orderDetailId
     * @param $referId
     * @param $staffId
     * @param $objectType
     * @param $objectId
     * @param $objectCode
     * @param $amount
     */
    public function insertOrderCommission($orderDetailId, $referId, $staffId, $objectType, $objectId, $objectCode, $amount)
    {
        $mStaff = app()->get(StaffTable::class);

        $getStaff = $mStaff->getCommissionStaff($staffId);
        //Lấy tỉ lệ hoa hồng nhân viên
        $rate = floatval(isset($getStaff) ? $getStaff['commission_rate'] : 0);

        $referMoney = 0;
        $staffMoney = 0;

        $getInfo = null;

        switch ($objectType) {
            case "service":
                $mService = app()->get(ServiceTable::class);
                //Lấy tỉ lệ hoa hồng dịch vụ
                $getInfo = $mService->getInfo($objectId);

                break;
            case "product":
                $mProductChild = app()->get(ProductChildTable::class);
                //Lấy tỉ lệ hoa hồng sản phẩm
                $getInfo = $mProductChild->getInfo($objectId);

                break;
            case "service_card":
                $mServiceCard = app()->get(ServiceCardTable::class);
                //Lấy tỉ lệ hoa hồng thẻ dịch vụ
                $getInfo = $mServiceCard->getInfo($objectId);

                break;
            case "member_card":
                $mCustomerServiceCard = app()->get(CustomerServiceCardTable::class);
                //Lấy thông tin hoa hồng khi sử dụng thẻ liệu trình
                $getCard = $mCustomerServiceCard->getCommissionMemberCard($objectCode);

                $referMoney = $getCard['refer_commission'] != null ? floatval($getCard['refer_commission']) : 0;
                $staffMoney = $getCard['staff_commission'] != null ? floatval($getCard['staff_commission'] * $rate) : 0;

                break;
        }

        if ($getInfo != null) {
            //Tính hoa hồng người giới thiệu
            if ($getInfo['type_refer_commission'] == 'money') {
                //Tiền mặt
                $referMoney = isset($getInfo['refer_commission_value']) ? $getInfo['refer_commission_value'] : 0;
            } else {
                //Phần trăm
                $referMoney = ($amount / 100) * isset($getInfo['refer_commission_value']) ? $getInfo['refer_commission_value'] : 0;
            }
            //Tính hoa hồng nhân viên phục vụ
            if ($getInfo['type_staff_commission'] == 'money') {
                //Tiền mặt
                $staffMoney = isset($getInfo['staff_commission_value']) ? $getInfo['staff_commission_value'] * $rate : 0;
            } else {
                //Phần trăm
                $staffMoney = (($amount / 100) * isset($getInfo['refer_commission_value']) ? $getInfo['refer_commission_value'] : 0) * $rate;
            }
        }

        $mOrderCommission = app()->get(OrderCommissionTable::class);

        if ($referMoney > 0 || $staffMoney > 0) {
            //Thêm hoa hồng của đơn hàng
            $mOrderCommission->add([
                'order_detail_id' => $orderDetailId,
                'refer_id' => $referId,
                'staff_id' => $staffId,
                'refer_money' => $referMoney,
                'staff_money' => $staffMoney,
                'staff_commission_rate' => $rate,
                'created_by' => Auth()->id(),
                'updated_by' => Auth()->id()
            ]);
        }
    }


    /**
     * Thanh toán Eway
     *
     * @param $orderId
     * @param $imei
     * @return array
     * @throws \Modules\Payment\Repositories\PaymentException
     */
    protected function paymentEway($orderId, $imei)
    {
//        $mOrderDetail = app()->get(OrderDetailTable::class);
//        //Thông tin đơn hàng
//        $orderInfo = $this->order->orderInfo($orderId, Auth()->id());
//
//        $orderInfo['total'] = floatval(isset($orderInfo['total']) ? $orderInfo['total'] : 0);
//        $orderInfo['discount'] = floatval(isset($orderInfo['discount']) ? $orderInfo['discount'] : 0);
//        $orderInfo['amount'] = floatval(isset($orderInfo['amount']) ? $orderInfo['amount'] : 0);
//        $orderInfo['discount_member'] = floatval(isset($orderInfo['discount_member']) ? $orderInfo['discount_member'] : 0);
//
//        //Chi tiết đơn hàng
//        $getDetail = $mOrderDetail->getDetailOrderList($orderInfo['order_id']);
//
//        $dataDetail = [];
//
//        if (count($getDetail) > 0) {
//            foreach ($getDetail as $v) {
//                $dataDetail [] = [
//                    'Reference' => $v['object_id'],// ID sản phẩm
//                    'SKU' => $v['object_code'],
//                    'Description' => $v['object_name'],
//                    'Quantity' => $v['quantity'],
//                    'UnitCost' => $v['price']
//                    // Total is calculated automatically
//                ];
//            }
//        }
//
//        $transaction = [
//            'Customer' => [
//                'Reference' => '123',
//                'Title' => '',
//                'FirstName' => '',
//                'LastName' => Auth()->user()->full_name,
//                'CompanyName' => '',
//                'JobDescription' => '',
//                'Street1' => str_limit($orderInfo['full_address'], 45, '...'),
//                'Street2' => '',
//                'City' => $orderInfo['province_name'],
//                'State' => $orderInfo['district_name'],
//                'PostalCode' => $orderInfo['postcode'],
//                'Country' => 'au',
//                'Phone' => Auth()->user()->phone1,
//                'Mobile' => Auth()->user()->phone1,
//                'Email' => Auth()->user()->email,
//                "Url" => "http://www.ewaypayments.com",
//            ],
//            'Items' => $dataDetail,
//            'Payment' => [
//                'TotalAmount' => $orderInfo['amount'],
//                'InvoiceNumber' => $orderInfo['order_id'],
//                'InvoiceDescription' => $orderInfo['order_description'],
//                'CurrencyCode' => 'AUD',
//            ],
//            'Options' => [
//                [
//                    'Value' => 'Option1',
//                ],
//                [
//                    'Value' => 'Option2',
//                ],
//            ],
//            'RedirectUrl' => route('order.payment-success', ['order_id' => $orderId]),
//            'CancelUrl' => route('order.payment-cancel', ['order_id' => $orderId]),
//            'DeviceID' => $imei,
//            'CustomerIP' => \request()->ip(),
//            'PartnerID' => 'ID',
//            'Capture' => true,
//            'LogoUrl' => '',
//            'HeaderText' => '',
//            'Language' => 'EN',
//            'CustomerReadOnly' => true
//        ];
//
//        $oPayment = PaymentFactory::getInstance(PaymentFactory::EWAY);
//
//        return $oPayment->call($transaction);
    }

    /**
     * Thanh toán thất bại
     *
     * @throws OrderRepoException
     */
    public function paymentCancel()
    {
        try {
            //Hủy thanh toán do người dùng nhất nút cancel
            $this->paymentCancelEway($_GET['order_id'], __('Khách hàng hủy thanh toán'));
        } catch (\Exception | QueryException $exception) {
            throw new OrderRepoException(OrderRepoException::PAYMENT_CANCEL_FAILED, $exception->getMessage());
        }
    }

    /**
     * Function hủy thanh toán từ eway
     *
     * @param $orderId
     * @param $message
     * @throws \Modules\Payment\Repositories\PaymentException
     */
    protected function paymentCancelEway($orderId, $message)
    {
        $mConfig = app()->get(ConfigTable::class);
        $mConfigDetail = app()->get(ConfigDetailTable::class);

        $orderInfo = $this->order->orderItem($_GET['order_id']);
        //Hủy transaction
        $oPayment = PaymentFactory::getInstance(PaymentFactory::EWAY);
        $response = $oPayment->response(['AccessCode' => $_GET['AccessCode'], 'Type' => 'cancel']);

    }

    /**
     * Thanh toán thành công
     *
     * @return mixed|void
     * @throws OrderRepoException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function paymentSuccess()
    {
        DB::beginTransaction();
        try {
            $mReceipt = app()->get(ReceiptTable::class);
            $mReceiptDetail = app()->get(ReceiptDetailTable::class);
            $mOrderLog = app()->get(OrderLogTable::class);
            $mVoucher = app()->get(VoucherTable::class);
            $mDelivery = app()->get(DeliveryTable::class);
            $oPayment = PaymentFactory::getInstance(PaymentFactory::EWAY);

            $response = $oPayment->response(['AccessCode' => $_GET['AccessCode']]);

            //Thanh toán eway thành công
            if ($response['ErrorCode'] == 0) {
                //Lấy thông tin đơn hàng
                $orderInfo = $this->order->orderItem($_GET['order_id']);
                //Update trạng thái đơn hàng
                $this->order->edit([
                    'process_status' => 'paysuccess'
                ], $_GET['order_id']);
                //Insert phiếu thu
                $receiptId = $mReceipt->add([
                    'customer_id' => $orderInfo['customer_id'],
                    'object_id' => $_GET['order_id'],
                    'branch_id' => Auth()->user()->branch_id,
                    'staff_id' => Auth()->id(),
                    'object_type' => 'order',
                    'order_id' => $_GET['order_id'],
                    'total_money' => $orderInfo['total'],
                    'voucher_code' => $orderInfo['voucher_code'],
                    'status' => 'paid',
                    'is_discount' => 1,
                    'amount' => $orderInfo['amount'],
                    'amount_paid' => $orderInfo['amount'],
                    'amount_return' => 0,
                    'note' => __('Thanh toán eway')
                ]);
                //Update receipt code
                $receiptCode = 'TT_' . date('dmY') . sprintf("%02d", $receiptId);
                $mReceipt->edit([
                    'receipt_code' => $receiptCode
                ], $receiptId);
                //Insert receipt detail
                $mReceiptDetail->add([
                    'receipt_id' => $receiptId,
                    'receipt_type' => 'transfer',
                    'amount' => $orderInfo['amount'],
                    'note' => __('Thanh toán eway')
                ]);
                //Insert order log đơn hàng đã xác nhận, đang xử lý
                $mOrderLog->insert([
                    [
                        'order_id' => $_GET['order_id'],
                        'created_type' => 'app',
                        'status' => 'confirmed',
//                        'note' => __('Đã xác nhận đơn hàng'),
                        'created_by' => $orderInfo['customer_id'],
                        'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                        'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                        'note_vi' => 'Đã xác nhận đơn hàng',
                        'note_en' => 'Order confirmed',
                    ],
                    [
                        'order_id' => $_GET['order_id'],
                        'created_type' => 'app',
                        'status' => 'packing',
//                        'note' => __('Đang xử lý'),
                        'created_by' => $orderInfo['customer_id'],
                        'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                        'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                        'note_vi' => 'Đang xử lý',
                        'note_en' => 'Processing',
                    ]
                ]);

                //Insert sms log khi thanh toán thành công
                $this->saveSmsLog($orderInfo['customer_id'], 'paysuccess', $_GET['order_id']);
                $this->saveEmailLog($orderInfo['customer_id'], 'paysuccess', $_GET['order_id']);

                //Kiểm tra nếu có sử dụng voucher thì tăng số lần sử dụng
                $getItemVoucher = $mVoucher->getItemByCode($orderInfo['voucher_code']);
                if ($getItemVoucher != null) {
                    $dataVoucher = [
                        'total_use' => ($getItemVoucher['total_use'] + 1)
                    ];
                    $mVoucher->editVoucherOrder($dataVoucher, $orderInfo['voucher_code']);
                }
                //Check deliveries ton tai thi update delivery_active = 1
                $checkDelivery = $mDelivery->getDeliveryByOrderId($_GET['order_id']);
                if ($checkDelivery != null) {
                    $mDelivery->edit(['is_actived' => 1], $_GET['order_id']);
                }
                //Check nếu giao hàng tại quầy thì insert order log ordercomplete
                if ($orderInfo['receive_at_counter'] == 1) {
                    $mOrderLog->insert([
                        [
                            'order_id' => $_GET['order_id'],
                            'created_type' => 'app',
                            'status' => 'ordercomplete',
//                            'note' => __('Đã hoàn thành đơn hàng'),
                            'created_by' => $orderInfo['customer_id'],
                            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                            'note_vi' => 'Đã hoàn thành đơn hàng',
                            'note_en' => 'Order completed',
                        ]
                    ]);
                }

                //Send Notification khi thanh toán thành công
                FunctionSendNotify::dispatch([
                    'type' => SEND_NOTIFY_CUSTOMER,
                    'key' => 'order_status_S',
                    'customer_id' => $orderInfo['customer_id'],
                    'object_id' => $_GET['order_id'],
                    'tenant_id' => session()->get('idTenant')
                ]);
                //Cộng điểm thưởng khi thanh toán
                $this->plusPointReceiptFull(['receipt_id' => $receiptId]);


                DB::commit();
            } else {
                return [
                    'error' => 1,
                    'route' => 'order.payment-fail',
                    'order_id' => $_GET['order_id'],
                    'AccessCode' => $_GET['AccessCode'],
                    'message' => __('Thanh toán thất bại')
                ];
            }

            return [
                'error' => 0,
                'message' => __('Thanh toán thành công')
            ];
        } catch (\Exception | QueryException $exception) {
            DB::rollback();
            throw new OrderRepoException(OrderRepoException::ORDER_PAYMENT_FAILED, $exception->getMessage());
        }
    }

    /**
     * Cộng điểm khi thanh toán đơn hàng
     *
     * @param $param
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    protected function plusPointReceiptFull($param)
    {
        $brandCode = session()->get('brand_code');

        $endpoint = sprintf(BASE_URL_API, $brandCode) . '/loyalty/plus-point-receipt-full';

        $client = new \GuzzleHttp\Client();

        $response = $client->request('POST', $endpoint, ['query' => $param]);

        $statusCode = $response->getStatusCode();
        $content = $response->getBody();

        return json_decode($response->getBody(), true);
    }

    /**
     * Cộng điểm khi thanh toán đơn hàng thiếu
     *
     * @param $param
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    protected function plusPointReceipt($param)
    {
        $brandCode = session()->get('brand_code');

        $endpoint = sprintf(BASE_URL_API, $brandCode) . '/loyalty/plus-point-receipt';

        $client = new \GuzzleHttp\Client();

        $response = $client->request('POST', $endpoint, ['query' => $param]);

        $statusCode = $response->getStatusCode();
        $content = $response->getBody();

        return json_decode($response->getBody(), true);
    }

    /**
     * Kiểm tra phí vận chuyển
     *
     * @param $input
     * @return mixed|void
     * @throws OrderRepoException
     */
    public function checkTransportCharge($input)
    {
        try {
            $mCustomerContact = app()->get(CustomerContactTable::class);
            $mDeliveryCost = app()->get(DeliveryCostTable::class);
            $mDeliveryCostDetail = app()->get(DeliveryCostDetailTable::class);

            $transportCharge = 0;
            //Lấy thông tin địa chỉ giao hàng
            $getContact = $mCustomerContact->getContact($input['customer_contact_code'], $input['customer_id']);


            if ($getContact == null) {
                throw new OrderRepoException(OrderRepoException::CHECK_TRANSPORT_CHARGE_FAIL);
            }
            //Lấy phí giao hàng của địa chỉ giao hàng
            $getCostPostCode = $mDeliveryCostDetail->getCostDetail($getContact['province_id'], $getContact['district_id']);
            //Lấy phí giao hàng mặc đinh
            $getCostDefault = $mDeliveryCost->getCostDefault();

            if ($getCostPostCode != null) {
                $transportCharge = floatval($getCostPostCode['delivery_cost']);
            } else {
                $transportCharge = floatval($getCostDefault['delivery_cost']);
            }

            return [
                'transport_charge' => $transportCharge
            ];
        } catch (\Exception | QueryException $e) {
            throw new OrderRepoException(OrderRepoException::CHECK_TRANSPORT_CHARGE_FAIL, $e->getMessage());
        }
    }

    /**
     * Thanh toán thất bại do thẻ ko đủ tiền thanh toán
     *
     * @return mixed|void
     * @throws OrderRepoException
     */
    public function paymentFail()
    {
        try {
            //Hủy transaction, ghi nhận lý do hủy
//            dd($_GET['order_id'], $_GET['AccessCode']);
        } catch (\Exception | QueryException $e) {
            throw new OrderRepoException(OrderRepoException::PAYMENT_CANCEL_FAILED, $e->getMessage());
        }
    }

    /**
     * Hủy transaction thanh toán
     *
     * @param $input
     * @return mixed|void
     * @throws OrderRepoException
     */
    public function cancelTransaction($input)
    {
        try {
            //Hủy transaction, ghi nhận lý do hủy
        } catch (\Exception | QueryException $e) {
            throw new OrderRepoException(OrderRepoException::CANCEL_TRANSACTION_FAIL, $e->getMessage());
        }
    }

    /**
     * Lưu sms log
     *
     * @param $customerId
     * @param $smsType
     * @param $idOrder
     */
    public function saveSmsLog($customerId, $smsType, $idOrder)
    {
        $mSmsLog = new SmsLogTable();
        $mCustomer = new CustomerTable();
        $mSmsConfig = new SmsConfigTable();
        $mSmsProvider = new SmsProviderTable();
        $mSpaInfo = new SpaInfoTable();

        $dataCus = $mCustomer->getInfoById($customerId);
        $smsConfig = $mSmsConfig->getItemByType($smsType);
        $brandName = $mSmsProvider->getItem(1)->value;
        if ($smsConfig->is_active == 1) {
            $content = $smsConfig->content;
            //Build nội dung.
            $gender = __('Anh');
            if ($dataCus['gender'] == 'female') {
                $gender = __('Chị');
            } elseif ($dataCus['gender'] == 'other') {
                $gender = __('Anh/Chị');
            }
            $message = '';
            $explodeName = explode(' ', $dataCus['full_name']);
            $lastName = array_pop($explodeName);
            if ($smsType == 'paysuccess') {
                //Lấy thông tin spa
                $spaInfo = $mSpaInfo->getInfo(1);

                $mOrderDetail = new OrderDetailTable();
                //Lấy thông tin chi tiết đơn hàng
                $getDetail = $mOrderDetail->orderDetail($idOrder, $customerId);

                $productName = '';
                if (count($getDetail) > 0) {
                    foreach ($getDetail as $k => $v) {
                        if (in_array($v['object_type'], ['product', 'service', 'service_card'])) {
                            $comma = $k + 1 < count($getDetail) ? ';' : '';
                            $productName .= $v['object_name'] . $comma;
                        }
                    }
                }

                if (strlen($productName) > 50) {
                    $productName = substr($productName, 0, 47) . '...';
                }

                $message = str_replace(
                    [
                        '{CUSTOMER_GENDER}',
                        '{CUSTOMER_NAME}',
                        '{CUSTOMER_FULL_NAME}',
                        '{NAME_SPA}',
                        '{PRODUCT_NAME}'
                    ],
                    [
                        $gender,
                        $lastName,
                        $dataCus['full_name'],
                        $spaInfo['name'],
                        $productName
                    ], $content);

            } else if ($smsType == 'order_success') {
                //Lấy thông tin đơn hàng
                $order = $this->order->orderInfo($idOrder, $customerId);

                $mOrderDetail = new OrderDetailTable();
                //Lấy thông tin chi tiết đơn hàng
                $getDetail = $mOrderDetail->orderDetail($idOrder, $customerId);

                $productName = '';
                if (count($getDetail) > 0) {
                    foreach ($getDetail as $k => $v) {
                        if (in_array($v['object_type'], ['product', 'service', 'service_card'])) {
                            $comma = $k + 1 < count($getDetail) ? ';' : '';
                            $productName .= $v['object_name'] . $comma;
                        }
                    }
                }

                if (strlen($productName) > 50) {
                    $productName = substr($productName, 0, 47) . '...';
                }

                $message = str_replace(
                    [
                        '{CUSTOMER_GENDER}',
                        '{CUSTOMER_NAME}',
                        '{CUSTOMER_FULL_NAME}',
                        '{ORDER_CODE}',
                        '{PRODUCT_NAME}',
                        '{DATETIME}'
                    ],
                    [
                        $gender,
                        $lastName,
                        $dataCus['full_name'],
                        $order['order_code'],
                        $productName,
                        Carbon::createFromFormat('Y-m-d H:i:s', $order['created_at'])->format('d/m/Y H:i')
                    ], $content);
            }

            // insert
            $dataSmsLog = [
                'brandname' => $brandName,
                'phone' => $dataCus['phone'],
                'customer_name' => $dataCus['full_name'],
                'message' => $message,
                'sms_type' => $smsType,
                'created_at' => date('Y-m-d H:i:s'),
                'created_by' => $customerId,
                'sms_status' => 'new',
                'object_id' => $idOrder,
                'object_type' => 'order',
            ];
            $idSmsLog = $mSmsLog->add($dataSmsLog);
        }
    }

    /**
     * insert email log
     *
     * @param $customerId
     * @param $emailType
     * @param $idOrder
     */
    private function saveEmailLog($customerId, $emailType, $idOrder)
    {
        $mProvider = app()->get(EmailProviderTable::class);
        $mEmailConfig = app()->get(EmailConfigTable::class);
        $mLog = app()->get(EmailLogTable::class);
        $mSpaInfo = app()->get(SpaInfoTable::class);
        $mCustomer = app()->get(CustomerTable::class);

        $dataCus = $mCustomer->getInfoById($customerId);

        $checkProvider = $mProvider->getProvider(1);
        if ($checkProvider['is_actived'] == 1) {
            $checkConfig = $mEmailConfig->getEmailConfig($emailType);
            if ($checkConfig['is_actived'] == 1) {
                $gender = __('Anh');
                if ($dataCus['gender'] == 'female') {
                    $gender = __('Chị');
                } elseif ($dataCus['gender'] == 'other') {
                    $gender = __('Anh/Chị');
                }
                //replace giá trị của tham số
                $params = [
                    '{name}',
                    '{full_name}',
                    '{gender}',
                    '{birthday}',
                    '{email}',
                    '{order_code}',
                    '{name_spa}'
                ];
                $explodeName = explode(' ', $dataCus['full_name']);
                $spaInfo = $mSpaInfo->getInfo(1);
                $order = $this->order->orderInfo($idOrder, $customerId);
                $replaceParams = [
                    array_pop($explodeName),
                    $dataCus['full_name'],
                    $gender,
                    $dataCus['birthday'] != null ? date('d/m/Y', strtotime($dataCus['birthday'])) : '',
                    $dataCus['email'],
                    $order['order_code'],
                    $spaInfo['name']
                ];
                $contentLog = $checkConfig['content'];
                $subject = str_replace($params, $replaceParams, $contentLog);

                if ($dataCus['email'] != null) {
                    //Insert Email Log
                    $dataLog = [
                        'email' => $dataCus['email'],
                        'customer_name' => $dataCus['full_name'],
                        'email_status' => 'new',
                        'email_type' => $emailType,
                        'content_sent' => $subject,
                        'object_id' => $idOrder,
                        'object_type' => 'order',
                        'created_by' => 0,
                        'updated_by' => 0
                    ];
                    $mLog->add($dataLog);
                }
            }
        }
    }

    /**
     * Cộng quota_use khi mua hàng có promotion là quà tặng
     *
     * @param $arrPromotionSubtract
     */
    public function plusQuotaUsePromotion($arrPromotionSubtract)
    {
        $mPromotionMaster = app()->get(PromotionMasterTable::class);

        if (count($arrPromotionSubtract) > 0) {
            foreach ($arrPromotionSubtract as $promotionCode => $v) {
                $infoMaster = $mPromotionMaster->getInfo($promotionCode);
                //Cập nhật quota_use của promotion
                $mPromotionMaster->edit([
                    'quota_use' => $infoMaster['quota_use'] + $v['quantity_gift']
                ], $promotionCode);
            }
        }
    }

    /**
     * Trừ quota_use khi mua hàng có promotion là quà tặng
     *
     * @param $orderId
     * @return mixed|void
     */
    public function subtractQuotaUsePromotion($orderId)
    {
        $mPromotionLog = app()->get(PromotionLogTable::class);
        $mPromotionMaster = app()->get(PromotionMasterTable::class);

        $getQuotaPromotion = $mPromotionLog->getQuotaPromotion($orderId);

        if (count($getQuotaPromotion) > 0) {
            foreach ($getQuotaPromotion as $v) {
                $infoMaster = $mPromotionMaster->getInfo($v['promotion_code']);

                //Cập nhật quota_use của promotion
                $mPromotionMaster->edit([
                    'quota_use' => $infoMaster['quota_use'] - $v['quantity_gift']
                ], $v['promotion_code']);
            }
        }
    }

    const IMAGETYPE_GIF = 1;
    const IMAGETYPE_JPEG = 2;
    const IMAGETYPE_PNG = 3;

    /**
     * Upload ảnh trước/sau khi sử dụng
     *
     * @param $input
     * @return mixed|void
     * @throws OrderRepoException
     */
    public function uploadImage($input)
    {
        try {
            //Lấy size, định dạng image
            $imageFile = getimagesize($input['link']);

            if ($imageFile == false || !in_array($imageFile[2], [IMAGETYPE_GIF, IMAGETYPE_JPEG, IMAGETYPE_PNG])) {
                throw new OrderRepoException(OrderRepoException::FILE_NOT_TYPE);
            }

            //Lấy size của ảnh/file up lên
            $fileSize = number_format(filesize($input['link']) / 1048576, 2); //MB

            if ($fileSize > 20) {
                throw new OrderRepoException(OrderRepoException::MAX_FILE_SIZE);
            }

            //Upload image/file lên s3
            $link = UploadImage::uploadImageS3($input['link'], '_order.');

            $mOrderImage = app()->get(OrderImageTable::class);
            //Insert order image
            $mOrderImage->add([
                'order_code' => $input['order_code'],
                'type' => $input['type'],
                'link' => $link,
                'created_by' => Auth()->id(),
                'updated_by' => Auth()->id()
            ]);

            return [
                'link' => $link
            ];
        } catch (\Exception | QueryException $e) {
            throw new OrderRepoException(OrderRepoException::CANCEL_TRANSACTION_FAIL, $e->getMessage());
        }
    }

    /**
     * Lấy mã giảm giá
     *
     * @param $input
     * @return mixed|void
     * @throws OrderRepoException
     */
    public function getVoucher($input)
    {
        try {
            $mCustomer = app()->get(CustomerTable::class);
            $mVoucher = app()->get(VoucherTable::class);

            //Lấy thông tin khách hàng
            $infoCustomer = $mCustomer->getInfoById($input['customer_id']);
            //Lấy thông tin mã giảm giá
            $memberLevelId [] = $infoCustomer['member_level_id'];

            $getVoucher = $mVoucher->getVoucher($input['voucher_code'], $memberLevelId);

            if ($getVoucher == null) {
                throw new OrderRepoException(OrderRepoException::GET_VOUCHER_FAILED);
            }

            $checkVoucher = $this->checkVoucher($getVoucher, $infoCustomer, $input);

            if ($checkVoucher == false) {
                throw new OrderRepoException(OrderRepoException::GET_VOUCHER_FAILED);
            } else {
                return $checkVoucher;
            }
        } catch (\Exception | QueryException $e) {
            throw new OrderRepoException(OrderRepoException::GET_VOUCHER_FAILED, $e->getMessage());
        }
    }

    /**
     * Kiểm tra voucher
     *
     * @param $infoVoucher
     * @param $infoCustomer
     * @param $input
     * @return bool
     */
    private function checkVoucher($infoVoucher, $infoCustomer, $input)
    {
        //Check chi nhánh
//        if ($infoVoucher['branch_id'] != null) {
//            $arrBranch = explode(",", $infoVoucher['branch_id']);
//            if (in_array($input['branch_id'], $arrBranch) == false) {
//                return false;
//            }
//        }

        //Check nhóm khách hàng
        if ($infoVoucher['customer_group_apply'] != null && $infoVoucher['customer_group_apply'] != 'all') {
            $arrGroup = explode(",", $infoVoucher['customer_group_apply']);
            if (in_array($infoCustomer['customer_group_id'], $arrGroup) == false) {
                return false;
            }
        }

        //Check object_type, object_type_id
        if ($infoVoucher['object_type'] != 'all') {
            $arrObject = collect($input['arr_object']);
            if ($infoVoucher['object_type_id'] != null) {
                $arrObject = array_map('intval', explode(',', $infoVoucher['object_type_id']));
                $arrInputId = [];
                foreach ($input['arr_object'] as $item) {
                    if ($item['object_type'] == $infoVoucher['object_type']) {
                        $arrInputId[] = $item['object_id'];
                    }
                }
                if (!empty(array_intersect($arrObject, $arrInputId)) == false) {
                    return false;
                }
            } else {
                if ($arrObject->contains('object_type', $infoVoucher['object_type']) == false) {
                    return false;
                }
            }
        }

        //Check hạn sử dụng
        if ($infoVoucher['expire_date'] < date("Y-m-d")) {
            return false;
        }

        //Check số lần sử dụng
        if ($infoVoucher['total_use'] >= $infoVoucher['quota']) {
            return false;
        }

        //Check tổng tiền được sử dụng voucher
        if ($input['total_amount'] < $infoVoucher['required_price']) {
            return false;
        }

        $discount = 0;
        if ($infoVoucher['type'] == 'sale_cash') {
            $discount = $infoVoucher['cash'];
            // check discount so voi total_amount
            $input['total_amount'] = (float)$input['total_amount'];
            if ($discount > $input['total_amount']) {
                $discount = $input['total_amount'];
            }
        } else if ($infoVoucher['type'] == 'sale_percent') {
            $discount = ($input['total_amount'] / 100) * $infoVoucher['percent'];
            if ($discount > $infoVoucher['max_price']) {
                $discount = $infoVoucher['max_price'];
            }
        }


        return [
            'voucher_code' => $infoVoucher['code'],
            'discount' => $discount
        ];
    }

    /**
     * Xoá hình ảnh trước/sau khi sử dụng
     *
     * @param $input
     * @return mixed|void
     * @throws OrderRepoException
     */
    public function removeOrderImage($input)
    {
        try {
            $mOrderImage = app()->get(OrderImageTable::class);


            if (isset($input['order_image_id']) && count($input['order_image_id']) > 0) {
                //Xoá hình ảnh đơn hàng
                $mOrderImage->removeImageById($input['order_image_id']);
            }
        } catch (\Exception | QueryException $e) {
            throw new OrderRepoException(OrderRepoException::REMOVE_IMAGE_FAILED, $e->getMessage());
        }
    }

    /**
     * Chỉnh sửa đơn hàng
     *
     * @param $input
     * @return mixed|void
     * @throws OrderRepoException
     */
    public function update($input)
    {
        DB::beginTransaction();
        try {
            $mOrderDetail = app()->get(OrderDetailTable::class);
            $mPromotionLog = app()->get(PromotionLogTable::class);
            $mOrderLog = app()->get(OrderLogTable::class);

            $inventory['inventory'] = [];
            $inventory['change_price'] = [];
            $inventory['is_delete'] = [];

            //Check quà tặng đẩy lên khi thêm đơn hàng
            $checkStoreGift = $this->checkGiftStoreOrder($input['detail'], $input['customer_id']);

            if ($checkStoreGift['error'] == 1) {
                throw new OrderRepoException(OrderRepoException::CHECK_GIFT_PROMOTION_CHANGE);
                // return [
                //     'promotion_update' => $checkStoreGift['gift'],
                //     'message' => __('Quà tặng đã thay đổi')
                // ];
            }

//            if ($input['branch_code'] != null) {
//            if (isset($input['detail']) || count($input['detail']) > 0) {
//                foreach ($input['detail'] as $v) {
//                    //Check tồn kho - thay đổi giá - đã xóa sản phẩm
//                    if ($v['object_type'] == 'product') {
//                        $getCheck = $this->checkProduct($input['branch_code'], $v['object_id'], $v['object_code'], $v['quantity'], $v['price']);
//
//                        if (count($getCheck['inventory']) > 0) {
//                            $inventory['inventory'] [] = $getCheck['inventory'];
//                        }
//
//                        if (count($getCheck['change_price']) > 0) {
//                            $inventory['change_price'] [] = $getCheck['change_price'];
//                        }
//                        if (count($getCheck['is_delete']) > 0) {
//                            $inventory['is_delete'] [] = $getCheck['is_delete'];
//                        }
//                    }
//                }
//            }
//
//            if (count($inventory['inventory']) > 0 || count($inventory['change_price']) > 0 || count($inventory['is_delete']) > 0) {
//                return [
//                    'product_update' => $inventory
//                ];
//            }
//            }

            $dataOrder = [
                'customer_id' => $input['customer_id'],
                'total' => $input['total'],
                'discount_member' => isset($input['discount_member']) ? $input['discount_member'] : 0,
                'discount' => $input['discount'],
                'amount' => $input['amount'],
                'voucher_code' => $input['voucher_code'],
                'tranport_charge' => $input['transport_charge'],
                'customer_contact_code' => $input['customer_contact_code'],
                'payment_method_id' => $input['payment_method_id'],
                'order_description' => $input['order_description'],
                'process_status' => $input['process_status'],
                'updated_by' => Auth()->id(),
                "type_shipping" => isset($input['type_shipping']) ? $input['type_shipping'] : 0,
                "delivery_cost_id" => isset($input['delivery_cost_id']) ? $input['delivery_cost_id'] : null
            ];
            //Thanh toán tại quầy
            if (isset($input['branch_code']) && $input['branch_code'] != null && $input['customer_contact_code']) {
                $dataOrder['receive_at_counter'] = 1;
            }
            //Update đơn hàng
            $this->order->edit($dataOrder, $input['order_id']);

            //Xóa chi tiết đơn hàng cũ
            $mOrderDetail->removeByOrderId($input['order_id']);

            //Promotion log
            $arrObjectBuy = [];

            if (isset($input['detail']) && count($input['detail']) > 0) {
                foreach ($input['detail'] as $key => $value) {
                    if (in_array($value['object_type'], ['product', 'service', 'service_card'])) {
                        $arrObjectBuy [] = [
                            'object_type' => $value['object_type'],
                            'object_code' => $value['object_code'],
                            'object_id' => $value['object_id'],
                            'price' => $value['price'],
                            'quantity' => $value['quantity'],
                            'customer_id' => $input['customer_id'],
                            'order_source' => self::LIVE,
                            'order_id' => $input['order_id'],
                            'order_code' => $input['order_id']
                        ];
                    }
                }
            }
            //Trừ quota_user khi đơn hàng có promotion quà tặng
            $this->subtractQuotaUsePromotion($input['order_id']);
            //Remove promotion log
            $mPromotionLog->removeByOrder($input['order_id']);

            //Lấy thông tin CTKM dc áp dụng cho đơn hàng
            $getPromotionLog = $this->groupQuantityObjectBuy($arrObjectBuy);
            //Insert promotion log
            $arrPromotionLog = $getPromotionLog['promotion_log'];
            $mPromotionLog->insert($arrPromotionLog);
            //Cộng quota_use promotion quà tặng
            $arrQuota = $getPromotionLog['promotion_quota'];
            $this->plusQuotaUsePromotion($arrQuota);

            //Insert chi tiết đơn hàng
            if (!isset($input['detail']) || count($input['detail']) == 0) {
                throw new OrderRepoException(OrderRepoException::STORE_ORDER_FAILED);
            }
            foreach ($input['detail'] as $item) {
                $dataDetail = [
                    'order_id' => $input['order_id'],
                    'object_id' => $item['object_id'],
                    'object_name' => $item['object_name'],
                    'object_type' => $item['object_type'],
                    'object_code' => $item['object_code'],
                    'price' => $item['price'],
                    'quantity' => $item['quantity'],
                    'discount' => $item['discount'],
                    'amount' => $item['amount']
                ];
                //Thêm chi tiết đơn hàng
                $mOrderDetail->add($dataDetail);
            }

            if (isset($input['delivery_active']) && $input['delivery_active'] == 1) {
                //Cập nhật trạng thái đơn hàng cần giao
                $mDelivery = new DeliveryTable();
                $mDelivery->edit([
                    'is_actived' => 1,
                    'contact_name' => $input['contact_name'],
                    'contact_phone' => $input['contact_phone'],
                    'contact_address' => $input['full_address']
                ], $input['order_id']);

                //Insert order log đơn hàng đã xác nhận, đang xử lý
                $mOrderLog->insert([
                    [
                        'order_id' => $input['order_id'],
                        'created_type' => 'backend',
                        'status' => 'confirmed',
//                        'note' => __('Đã xác nhận đơn hàng'),
                        'created_by' => Auth()->id(),
                        'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                        'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                        'note_vi' => 'Đã xác nhận đơn hàng',
                        'note_en' => 'Order confirm',
                    ],
                    [
                        'order_id' => $input['order_id'],
                        'created_type' => 'backend',
                        'status' => 'packing',
//                        'note' => __('Đang xử lý'),
                        'created_by' => Auth()->id(),
                        'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                        'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                        'note_vi' => 'Đang xử lý',
                        'note_en' => 'Processing',
                    ]
                ]);
            }

            DB::commit();

            if (isset($input['delivery_active']) && $input['delivery_active'] == 1 && $input['customer_id'] != 1) {
                //Send notification xác nhận đơn hàng
                FunctionSendNotify::dispatch([
                    'type' => SEND_NOTIFY_CUSTOMER,
                    'key' => 'order_status_A',
                    'customer_id' => $input['customer_id'],
                    'object_id' => $input['order_id'],
                    'tenant_id' => session()->get('idTenant')
                ]);
            }

            return [
                "order_id" => $input['order_id']
            ];
        } catch (\Exception | QueryException $e) {
            DB::rollback();
            throw new OrderRepoException(OrderRepoException::UPDATE_FAILED, $e->getMessage());
        }
    }

    /**
     * Chình sửa đơn hàng (V2)
     *
     * @param $input
     * @return array
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws OrderRepoException
     */
    public function updateV2($input)
    {
        DB::beginTransaction();
        try {
            $mOrderDetail = app()->get(OrderDetailTable::class);
            $mPromotionLog = app()->get(PromotionLogTable::class);
            $mOrderLog = app()->get(OrderLogTable::class);

            $inventory['inventory'] = [];
            $inventory['change_price'] = [];
            $inventory['is_delete'] = [];

            //Check quà tặng đẩy lên khi thêm đơn hàng
            $checkStoreGift = $this->checkGiftStoreOrder($input['detail'], $input['customer_id']);

            if ($checkStoreGift['error'] == 1) {
                throw new OrderRepoException(OrderRepoException::CHECK_GIFT_PROMOTION_CHANGE);
            }

            $dataOrder = [
                'customer_id' => $input['customer_id'],
                'total' => $input['total'],
                'discount_member' => isset($input['discount_member']) ? $input['discount_member'] : 0,
                'discount' => $input['discount'],
                'amount' => $input['amount'],
                'voucher_code' => $input['voucher_code'],
                'tranport_charge' => $input['transport_charge'],
                'customer_contact_code' => $input['customer_contact_code'],
                'payment_method_id' => $input['payment_method_id'],
                'order_description' => $input['order_description'],
                'process_status' => $input['process_status'],
                'updated_by' => Auth()->id(),
                "type_shipping" => isset($input['type_shipping']) ? $input['type_shipping'] : 0,
                "delivery_cost_id" => isset($input['delivery_cost_id']) ? $input['delivery_cost_id'] : null
            ];
            //Thanh toán tại quầy
            if (isset($input['branch_code']) && $input['branch_code'] != null && $input['customer_contact_code']) {
                $dataOrder['receive_at_counter'] = 1;
            }
            //Update đơn hàng
            $this->order->edit($dataOrder, $input['order_id']);

            //Xóa chi tiết đơn hàng cũ
            $mOrderDetail->removeByOrderId($input['order_id']);

            //Promotion log
            $arrObjectBuy = [];

            if (isset($input['detail']) && count($input['detail']) > 0) {
                foreach ($input['detail'] as $key => $value) {
                    if (in_array($value['object_type'], ['product', 'service', 'service_card'])) {
                        $arrObjectBuy [] = [
                            'object_type' => $value['object_type'],
                            'object_code' => $value['object_code'],
                            'object_id' => $value['object_id'],
                            'price' => $value['price'],
                            'quantity' => $value['quantity'],
                            'customer_id' => $input['customer_id'],
                            'order_source' => self::LIVE,
                            'order_id' => $input['order_id'],
                            'order_code' => $input['order_id']
                        ];
                    }
                }
            }
            //Trừ quota_user khi đơn hàng có promotion quà tặng
            $this->subtractQuotaUsePromotion($input['order_id']);
            //Remove promotion log
            $mPromotionLog->removeByOrder($input['order_id']);

            //Lấy thông tin CTKM dc áp dụng cho đơn hàng
            $getPromotionLog = $this->groupQuantityObjectBuy($arrObjectBuy);
            //Insert promotion log
            $arrPromotionLog = $getPromotionLog['promotion_log'];
            $mPromotionLog->insert($arrPromotionLog);
            //Cộng quota_use promotion quà tặng
            $arrQuota = $getPromotionLog['promotion_quota'];
            $this->plusQuotaUsePromotion($arrQuota);

            //Insert chi tiết đơn hàng
            if (!isset($input['detail']) || count($input['detail']) == 0) {
                throw new OrderRepoException(OrderRepoException::STORE_ORDER_FAILED);
            }
            foreach ($input['detail'] as $item) {
                $dataDetail = [
                    'order_id' => $input['order_id'],
                    'object_id' => $item['object_id'],
                    'object_name' => $item['object_name'],
                    'object_type' => $item['object_type'],
                    'object_code' => $item['object_code'],
                    'price' => $item['price'],
                    'quantity' => $item['quantity'],
                    'discount' => $item['discount'],
                    'amount' => $item['amount'],
                    'created_by' => Auth::id(),
                    'updated_by' => Auth::id(),
                    'note' => $item['note']
                ];
                //Thêm chi tiết đơn hàng
                $mOrderDetail->add($dataDetail);
            }

            if (isset($input['delivery_active']) && $input['delivery_active'] == 1) {
                //Cập nhật trạng thái đơn hàng cần giao
                $mDelivery = new DeliveryTable();
                $mDelivery->edit([
                    'is_actived' => 1,
                    'contact_name' => $input['contact_name'],
                    'contact_phone' => $input['contact_phone'],
                    'contact_address' => $input['full_address']
                ], $input['order_id']);

                //Insert order log đơn hàng đã xác nhận, đang xử lý
                $mOrderLog->insert([
                    [
                        'order_id' => $input['order_id'],
                        'created_type' => 'backend',
                        'status' => 'confirmed',
//                        'note' => __('Đã xác nhận đơn hàng'),
                        'created_by' => Auth()->id(),
                        'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                        'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                        'note_vi' => 'Đã xác nhận đơn hàng',
                        'note_en' => 'Order confirm',
                    ],
                    [
                        'order_id' => $input['order_id'],
                        'created_type' => 'backend',
                        'status' => 'packing',
//                        'note' => __('Đang xử lý'),
                        'created_by' => Auth()->id(),
                        'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                        'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                        'note_vi' => 'Đang xử lý',
                        'note_en' => 'Processing',
                    ]
                ]);
            }

            DB::commit();

            if (isset($input['delivery_active']) && $input['delivery_active'] == 1 && $input['customer_id'] != 1) {
                //Send notification xác nhận đơn hàng
                FunctionSendNotify::dispatch([
                    'type' => SEND_NOTIFY_CUSTOMER,
                    'key' => 'order_status_A',
                    'customer_id' => $input['customer_id'],
                    'object_id' => $input['order_id'],
                    'tenant_id' => session()->get('idTenant')
                ]);
            }

            return [
                "order_id" => $input['order_id']
            ];
        } catch (\Exception | QueryException $e) {
            DB::rollback();
            throw new OrderRepoException(OrderRepoException::UPDATE_FAILED, $e->getMessage());
        }
    }

    /**
     * Tự tạo phiếu bảo hành khi thanh toán đơn hàng
     *
     * @param $customerCode
     * @param $orderCode
     * @param $dataProduct
     */
    private function insertWarrantyCard($customerCode, $orderCode, $dataProduct)
    {
        $mWarrantyDetail = new WarrantyPackageDetailTable();
        $mWarranty = new WarrantyPackageTable();
        $mWarrantyCard = new WarrantyCardTable();

        if (count($dataProduct) > 0) {
            foreach ($dataProduct as $v) {
                //Lấy thông tin chi tiết gói bảo hành
                $warrantyDetail = $mWarrantyDetail->getDetailByObjectCode($v['object_code'], $v['object_type']);

                if ($warrantyDetail != null) {
                    //Lấy thông tin gói bảo hành
                    $warranty = $mWarranty->getInfoByCode($warrantyDetail['warranty_packed_code']);
                    $dataInsert = [
                        'customer_code' => $customerCode,
                        'warranty_packed_code' => $warrantyDetail['warranty_packed_code'],
                        'quota' => $warranty['quota'],
                        'warranty_percent' => $warranty['percent'],
                        'warranty_value' => $warranty['required_price'],
                        'status' => 'new',
                        'object_type' => $v['object_type'],
                        'object_type_id' => $v['object_id'],
                        'object_code' => $v['object_code'],
                        'object_price' => $v['price'],
                        'created_by' => Auth::id(),
                        'order_code' => $orderCode,
                        'description' => $warranty['detail_description']
                    ];
                    if ($v['quantity'] > 1) {
                        for ($i = 0; $i < $v['quantity']; $i++) {
                            $warrantyCardId = $mWarrantyCard->add($dataInsert);
                            //Update mã phiếu bảo hành
                            $warrantyCardCode = 'WRC_' . date('dmY') . sprintf("%02d", $warrantyCardId);
                            $mWarrantyCard->edit(['warranty_card_code' => $warrantyCardCode], $warrantyCardId);
                        }
                    } else {
                        $warrantyCardId = $mWarrantyCard->add($dataInsert);
                        //Update mã phiếu bảo hành
                        $warrantyCardCode = 'WRC_' . date('dmY') . sprintf("%02d", $warrantyCardId);
                        $mWarrantyCard->edit(['warranty_card_code' => $warrantyCardCode], $warrantyCardId);
                    }
                }
            }
        }
    }

    /**
     * Tạo qr code thanh toán vn pay
     *
     * @param $input
     * @return mixed|void
     * @throws OrderRepoException
     */
    public function createQrCodeVnPay($input)
    {
        try {
            $objectType = null;
            $objectId = null;
            $objectCode = null;
            $fullName = null;
            $phone = null;
            $branchId = null;
            $customerId = null;

            switch ($input['object_type']) {
                case 'order':
                    //Lấy thông tin đơn hàng
                    $info = $this->order->orderInfo($input['object_id']);

                    if ($info != null) {
                        $branchId = $info['branch_id'];
                        $customerId = $info['customer_id'];
                        $objectType = $info['order_source_id'] == 1 ? 'order' : 'order_online';
                        $objectId = $info['order_id'];
                        $objectCode = $info['order_code'];
                        $fullName = $info['full_name'];
                        $phone = $info['phone'];
                    }
                    break;

                case 'maintenance':
                    $mMaintenance = app()->get(MaintenanceTable::class);
                    //Lấy thông tin phiếu bảo trì
                    $info = $mMaintenance->getInfo($input['object_id']);

                    if ($info != null) {
                        $branchId = Auth()->user()->branch_id;
                        $customerId = $info['customer_id'];
                        $objectType = $input['object_type'];
                        $objectId =  $info['maintenance_id'];
                        $objectCode = $info['maintenance_code'];
                        $fullName = $info['customer_name'];
                        $phone = $info['phone'];
                    }

                    break;
            }

            $mPaymentMethod = app()->get(PaymentMethodTable::class);
            //Lấy thông tin phương thức thanh toán
            $infoMethod = $mPaymentMethod->getInfoByCode($input['payment_method_code']);

            $arrVnPay = null;
            $arrMoMo = null;

            if ($input['payment_method_code'] == 'VNPAY') {
                //Call api tạo qr code
                $callVnPay = $this->_paymentVnPay($objectId, $input['money'], $customerId, $branchId, 'app_staff', "");

                if ($callVnPay['ErrorCode'] == 0) {
                    $arrVnPay = $callVnPay['Data'];
                    $arrVnPay['object_type'] = $input['object_type'];
                    $arrVnPay['object_id'] = intval($arrVnPay['order_id']);

                    unset($arrVnPay['order_id']);

                    $mReceiptOnline = app()->get(ReceiptOnlineTable::class);
                    //Lưu log thanh toán online
                    $mReceiptOnline->add([
                        "object_type" => $objectType,
                        "object_id" => $objectId,
                        "object_code" => $objectCode,
                        "payment_transaction_code" => $callVnPay['Data']['payment_transaction_code'],
                        "payment_method_code" => $infoMethod['payment_method_code'],
                        "amount_paid" => $input['money'],
                        "payment_time" => Carbon::now()->format('Y-m-d H:i:s'),
                        "type" => $infoMethod['payment_method_type'],
                        "platform" => "app_staff",
                        "performer_name" => $fullName,
                        "performer_phone" => $phone
                    ]);
                }
            }

            return [
                'vn_pay' => $arrVnPay,
                'momo' => $arrMoMo
            ];
        } catch (\Exception | QueryException $e) {
            throw new OrderRepoException(OrderRepoException::CREATE_QR_FAILED, $e->getMessage() . $e->getFile() . $e->getLine());
        }
    }

    /**
     * Call api thanh toán vn pay
     *
     * @param $orderId
     * @param $amount
     * @param $userId
     * @param $branchId
     * @param $platform
     * @param $paramsExtra
     * @return mixed
     */
    public function _paymentVnPay($orderId, $amount, $userId, $branchId, $platform, $paramsExtra)
    {
        $mPaymentOnline = app()->get(PaymentOnline::class);

        //Call api thanh toán vn pay
        return $mPaymentOnline->paymentVnPay([
            'method' => 'vnpay',
            'order_id' => $orderId,
            'amount' => $amount,
            'user_id' => $userId,
            'branch_id' => $branchId,
            'platform' => $platform,
            'params_extra' => $paramsExtra
        ]);
    }

    /**
     * Lấy trạng thái vn pay
     *
     * @param $input
     * @return mixed
     * @throws OrderRepoException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getStatusVnPay($input)
    {
        try {
            $mReceiptOnline = app()->get(ReceiptOnlineTable::class);

            //Lấy thông tin đơn hàng
            $info = $mReceiptOnline->getInfoByCode($input['payment_transaction_code']);

            if ($info == null) {
                throw new OrderRepoException(OrderRepoException::GET_STATUS_PAYMENT_FAIL);
            }

            return [
                'object_type' => $info['object_type'],
                'object_id' => $info['object_id'],
                'payment_transaction_code' => $input['payment_transaction_code'],
                'status' => $info['status'] == 'success' ? 1 : 0
            ];
        } catch (\Exception $e) {
            throw new OrderRepoException(OrderRepoException::GET_STATUS_PAYMENT_FAIL, $e->getMessage());
        }
    }

    /**
     * Lấy phương thức vận chuyển
     *
     * @param $input
     * @return mixed|void
     * @throws OrderRepoException
     */
    public function getTransportMethod($input)
    {
        try {
            $mCustomerContact = app()->get(CustomerContactTable::class);
            $mDeliveryCost = app()->get(DeliveryCostTable::class);
            $mDeliveryCostDetail = app()->get(DeliveryCostDetailTable::class);

            //Lấy thông tin địa chỉ giao hàng
            $getContact = $mCustomerContact->getContact($input['customer_contact_code'], $input['customer_id']);

            if ($getContact == null) {
                throw new OrderRepoException(OrderRepoException::GET_TRANSPORT_METHOD_FAILED);
            }
            //Lấy phí giao hàng của địa chỉ giao hàng
            $getCostPostCode = $mDeliveryCostDetail->getCostDetail($getContact['province_id'], $getContact['district_id']);

            //Lấy phí giao hàng mặc đinh
            $getCostDefault = $mDeliveryCost->getCostDefault();

            $data = [];

            if ($getCostPostCode != null) {
                $data [] = [
                    'type_shipping' => 0,
                    'text' => __('Tiết kiệm'),
                    'transport_charge' => $getCostPostCode['delivery_cost'] != null ? floatval($getCostPostCode['delivery_cost']) : 0,
                    'delivery_cost_id' => $getCostPostCode['delivery_cost_id'],
                    'default' => $getCostPostCode['is_delivery_fast'] == 1 ? 0 : 1
                ];

                if ($getCostPostCode['is_delivery_fast'] == 1) {
                    $data [] = [
                        'type_shipping' => 1,
                        'text' => __('Hoả tốc'),
                        'transport_charge' => $getCostPostCode['delivery_fast_cost'] != null ? floatval($getCostPostCode['delivery_fast_cost']) : 0,
                        'delivery_cost_id' => $getCostPostCode['delivery_cost_id'],
                        'default' => 1
                    ];
                }
            } else {
                $data [] = [
                    'type_shipping' => 0,
                    'text' => __('Tiết kiệm'),
                    'transport_charge' => $getCostDefault['delivery_cost'] != null ? floatval($getCostDefault['delivery_cost']) : 0,
                    'delivery_cost_id' => $getCostDefault['delivery_cost_id'],
                    'default' => 1
                ];
            }

            return $data;
        } catch (\Exception | QueryException $e) {
            throw new OrderRepoException(OrderRepoException::GET_TRANSPORT_METHOD_FAILED, $e->getMessage());
        }
    }

    /**
     * Lấy template in hóa đơn
     * @param array $all
     * @return mixed
     */
    public function getPrintBillTemplate(array $all)
    {
        $id = $all['order_id'];
        //Lấy thông tin đơn hàng
        $order = $this->order->getItemDetail($id);
        $totalDiscount = $order['discount'];

        //Lấy chi tiết đơn hàng
        $list_table = $this->orderDetail->getItem($order['order_id']);
        $arr = [];
        $totalQuantity = 0;
        $totalDiscountDetail = 0;
        foreach ($list_table as $key => $item) {
            $unitName = null;
            //Lấy đơn vị tính nếu là sản phẩm, sản phẩm quà tặng (object_type = product, product_gift)
            if ($item['object_type'] == 'product' || $item['object_type'] == 'product_gift') {
                $productInfo = $this->product->getItem($item['object_id']);
                if ($productInfo != null) {
                    $unitName = $productInfo['unitName'];
                }
            }
            $arr[] = [
                'order_detail_id' => $item['order_detail_id'],
                'object_id' => $item['object_id'],
                'object_name' => $item['object_name'],
                'object_type' => $item['object_type'],
                'price' => $item['price'],
                'quantity' => $item['quantity'],
                'discount' => $item['discount'],
                'amount' => $item['amount'],
                'voucher_code' => $item['voucher_code'],
                'object_code' => "XXXXXXXXXXXXXX" . substr($item['object_code'], -4),
                'unit_name' => $unitName
            ];
            $totalQuantity += (int)$item['quantity'];
            $totalDiscountDetail += $item['discount'];
            $totalDiscount += $item['discount'];
        }
        $receipt = $this->receipt->getItem($id);

        $list_receipt_detail = $this->receiptDetail->getItem($receipt['receipt_id']);

        $totalCustomerPaid = 0; // Tính tổng tiền KH trả
        foreach ($list_receipt_detail as $item) {
            $totalCustomerPaid += $item['amount'];
        }
        //Lấy cấu hình in bill
        $configPrintBill = $this->configPrintBill->getItem(1);
        isset($order['branch_id'])? $order['branch_id'] : '';
        //Lấy thông tin chi nhánh của đơn hàng
        $branchInfo = $this->branch->getItem($order['branch_id']);
        if ($branchInfo != null) {
            // cắt hot line thành mảng
            $arrPhoneBranch = explode(",", $branchInfo['hot_line']);
            $strPhone = '';
            $temp = 0;
            $countPhoneBranch = count($arrPhoneBranch);
            if ($countPhoneBranch > 0) {
                foreach ($arrPhoneBranch as $value) {
                    if ($temp < $countPhoneBranch - 1) {
                        $strPhone = $strPhone . str_replace(' ', '', $value) . ' - ';
                    } else {
                        $strPhone = $strPhone . str_replace(' ', '', $value);
                    }
                    $temp++;
                }
            }
            $branchInfo['hot_line'] = $strPhone;
        }else{
            $branchInfo = [
                "branch_name" => "",
                "address" => "",
                "district_type" => "",
                "district_name" => "",
                "province_name" => "",
                "hot_line" => "",
            ];
        }

        //Template mặc định
        $template = 'order::print.content-print';
        switch ($configPrintBill->template) {
            case 'k58':
                $template = 'order::print.template-k58';
                break;
            case 'A5':
                $template = 'order::print.template--a5';
                break;
            case 'A4':
                $template = 'order::print.template-a4';
                break;
        }
        //Lấy số lần in bill của đơn hàng này
        $checkPrintBill = $this->printBillLog->checkPrintBillOrder($order['order_code']);
        $printTime = count($checkPrintBill);
        $printReply = '';
        if ($printTime > 0) {
            $printReply = __('(In lại)');
        }
        $maxId = $this->printBillLog->getBiggestId();
        $convertNumberToWords = $this->help->convertNumberToWords($receipt['amount']);
        $spaInfo = $this->spaInfo->getInfoSpa();
        // Tách sdt theo dấu ,
        $arrPhoneSpa = explode(",", $spaInfo['phone']);
        $arrPhoneNew = [];
        if (count($arrPhoneSpa) > 0) {
            foreach ($arrPhoneSpa as $value) {
                $arrPhoneNew [] = str_replace(' ', '', $value);
            }
        }
        $spaInfo['phone'] = $arrPhoneNew;
        $html = view($template, [
            'order' => $order,
            'oder_detail' => $arr,
            'receipt' => $receipt,
            'receipt_detail' => $list_receipt_detail,
            'spaInfo' => $spaInfo,
            'totalCustomerPaid' => $totalCustomerPaid,
            'configPrintBill' => $configPrintBill,
            'id' => $id,
            'printTime' => $printReply,
            'STT' => $maxId != null ? $maxId['id'] : 1,
            'QrCode' => $order['order_code'],
            'convertNumberToWords' => $convertNumberToWords,
            'branchInfo' => $branchInfo,
            'order_detail' => $arr,
            'totalQuantity' => $totalQuantity,
            'totalDiscount' => $totalDiscount,
            'totalDiscountDetail' => $totalDiscountDetail,
            'text_total_amount_paid' => $this->convert_number_to_words($totalCustomerPaid)
        ])->render();

        return [
            'html' => $html
        ];
    }


    /**
     * Function đọc tiền tiếng việt
     *
     * @param $number
     * @return string
     */
    function convert_number_to_words($number)
    {
        $hyphen      = ' ';
        $conjunction = ' ';
        $separator   = ' ';
        $negative    = __('âm') . ' ';
        $decimal     = ' ' . __('phẩy') . ' ';
        $dictionary  = array(
            0                   => __('không'),
            1                   => __('một'),
            2                   => __('hai'),
            3                   => __('ba'),
            4                   => __('bốn'),
            5                   => __('năm'),
            6                   => __('sáu'),
            7                   => __('bảy'),
            8                   => __('tám'),
            9                   => __('chín'),
            10                  => __('mười'),
            11                  => __('mười một'),
            12                  => __('mười hai'),
            13                  => __('mười ba'),
            14                  => __('mười bốn'),
            15                  => __('mười năm'),
            16                  => __('mười sáu'),
            17                  => __('mười bảy'),
            18                  => __('mười tám'),
            19                  => __('mười chín'),
            20                  => __('hai mươi'),
            30                  => __('ba mươi'),
            40                  => __('bốn mươi'),
            50                  => __('năm mươi'),
            60                  => __('sáu mươi'),
            70                  => __('bảy mươi'),
            80                  => __('tám mươi'),
            90                  => __('chín mươi'),
            100                 => __('trăm'),
            1000                => __('nghìn'),
            1000000             => __('triệu'),
            1000000000          => __('tỷ'),
            1000000000000       => __('nghìn tỷ'),
            1000000000000000    => __('nghìn triệu triệu'),
            1000000000000000000 => __('tỷ tỷ')
        );
        if (!is_numeric($number)) {
            return false;
        }
        if (($number >= 0 && (int) $number < 0) || (int) $number < 0 - PHP_INT_MAX) {
            // overflow
            trigger_error(
                'convert_number_to_words only accepts numbers between -' . PHP_INT_MAX . ' and ' . PHP_INT_MAX,
                E_USER_WARNING
            );
            return false;
        }
        if ($number < 0) {
            return $negative . convert_number_to_words(abs($number));
        }
        $string = $fraction = null;
        if (strpos($number, '.') !== false) {
            list($number, $fraction) = explode('.', $number);
        }
        switch (true) {
            case $number < 21:
                $string = $dictionary[$number];
                break;
            case $number < 100:
                $tens   = ((int) ($number / 10)) * 10;
                $units  = $number % 10;
                $string = $dictionary[$tens];
                if ($units) {
                    $string .= $hyphen . $dictionary[$units];
                }
                break;
            case $number < 1000:
                $hundreds  = $number / 100;
                $remainder = $number % 100;
                $string = $dictionary[$hundreds] . ' ' . $dictionary[100];
                if ($remainder) {
                    $string .= $conjunction . $this->convert_number_to_words($remainder);
                }
                break;
            default:
                $baseUnit = pow(1000, floor(log($number, 1000)));
                $numBaseUnits = (int) ($number / $baseUnit);
                $remainder = $number % $baseUnit;
                $string = $this->convert_number_to_words($numBaseUnits) . ' ' . $dictionary[$baseUnit];
                if ($remainder) {
                    $string .= $remainder < 100 ? $conjunction : $separator;
                    $string .= $this->convert_number_to_words($remainder);
                }
                break;
        }
        if (null !== $fraction && is_numeric($fraction)) {
            $string .= $decimal;
            $words = array();
            foreach (str_split((string) $fraction) as $number) {
                $words[] = $dictionary[$number];
            }
            $string .= implode(' ', $words);
        }
        return $string;
    }

    /**
     * Lấy danh sách máy in
     * @param array $all
     * @return mixed
     */
    public function getPrintBillDevices(array $all)
    {
        $user = Auth::user();
        return $this->printBillDeviceTable->getPrinters(isset($all['branch_id']) ? $all['branch_id'] : $user->branch_id);
    }
}