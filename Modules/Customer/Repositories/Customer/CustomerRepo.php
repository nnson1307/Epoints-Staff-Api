<?php
/**
 * Created by PhpStorm   .
 * User: Mr Son
 * Date: 2020-01-04
 * Time: 10:56 AM
 * @author SonDepTrai
 */

namespace Modules\Customer\Repositories\Customer;


use App\Jobs\SaveLogZns;
use App\Jobs\SendNotification;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Modules\Customer\Models\ConfigTable;
use Modules\Customer\Models\CustomerBranchTable;
use Modules\Customer\Models\CustomerContactTable;
use Modules\Customer\Models\CustomerGroupTable;
use Modules\Customer\Models\CustomerTable;
use Modules\Customer\Models\OrderTable;
use Modules\Customer\Models\SmsConfigTable;
use Modules\Customer\Models\SmsLogTable;
use Modules\Customer\Models\SmsSettingBrandNameTable;
use Modules\Customer\Models\SpaInfoTable;
use Modules\Customer\Models\CustomerCommentTable;
use MyCore\Repository\PagingTrait;


class CustomerRepo implements CustomerRepoInterface
{
    protected $customer;

    public function __construct(
        CustomerTable $customer
    )
    {
        $this->customer = $customer;
    }

    use PagingTrait;

    /**
     * Lấy ds khách hàng
     *
     * @param $input
     * @return mixed|void
     * @throws CustomerRepoException
     */
    public function getCustomer($input)
    {
        try {
            //Lấy danh sách khách hàng
            $data = $this->customer->getCustomer($input);

            if (count($data->items()) > 0) {
                $mCustomerContact = app()->get(CustomerContactTable::class);

                foreach ($data->items() as $v) {
                    $v['full_address'] = $v['address'];

                    if ($v['ward_name'] != null) {
                        $v['full_address'] .=  ', ' . $v['ward_type'] . ' ' . $v['ward_name'];
                    }

                    if ($v['district_name'] != null) {
                        $v['full_address'] .=  ', ' . $v['district_type'] . ' ' . $v['district_name'];
                    }

                    if ($v['province_name'] != null) {
                        $v['full_address'] .=  ', ' . $v['province_type'] . ' ' . $v['province_name'];
                    }

                    //Lấy địa chỉ giao hàng của khách hàng
                    $deliveryAddress = $mCustomerContact->getContact($v['customer_id'])->toArray();

                    $arrDeliveryAddress = [];

                    if (count($deliveryAddress) > 0) {
                        foreach ($deliveryAddress as $v1) {
                            $v1['full_address'] = $v1['address'] . ', ' . $v1['ward_type'] . ' ' . $v1['ward_name'] . ', ' . $v1['district_type'] . ' ' . $v1['district_name'] . ', ' . $v1['province_type'] . ' ' . $v1['province_name'];
                            $arrDeliveryAddress[] = $v1;
                        }
                    }
                    $v['delivery_address'] = $arrDeliveryAddress;

                    $v['is_have_delivery_address'] = count($arrDeliveryAddress) > 0 ? 1 : 0;
                }
            }

            return $this->toPagingData($data);
        } catch (\Exception $exception) {
            throw new CustomerRepoException(CustomerRepoException::GET_CUSTOMER_FAILED);
        }
    }

    /**
     * Chi tiết khách hàng
     *
     * @param $input
     * @return mixed|void
     * @throws CustomerRepoException
     */
    public function getDetail($input)
    {
        try {
            //Lấy thông tin khách hàng
            $info = $this->customer->getInfoById($input['customer_id']);

            $info['full_address'] = $info['address'];

            if ($info['ward_name'] != null) {
                $info['full_address'] .=  ', ' . $info['ward_name'];
            }

            if ($info['district_name'] != null) {
                $info['full_address'] .=  ', ' . $info['district_name'];
            }

            if ($info['province_name'] != null) {
                $info['full_address'] .=  ', ' . $info['province_name'];
            }

            //Format birthday
            $info['birthday'] = $info['birthday'] != null ? Carbon::parse($info['birthday'])->format('d/m/Y') : null;

            $mCustomerContact = app()->get(CustomerContactTable::class);

            //Lấy địa chỉ giao hàng của khách hàng
            $deliveryAddress = $mCustomerContact->getContact($info['customer_id'])->toArray();

            $arrDeliveryAddress = [];

            if (count($deliveryAddress) > 0) {
                foreach ($deliveryAddress as $v1) {
                    $v1['full_address'] = $v1['address'] . ', ' . $v1['ward_type'] . ' ' . $v1['ward_name'] .', ' . $v1['district_type'] . ' ' . $v1['district_name'] . ', ' . $v1['province_type'] . ' ' . $v1['province_name'];
                    $arrDeliveryAddress[] = $v1;
                }
            }
            $info['delivery_address'] = $arrDeliveryAddress;

            return $info;
        } catch (\Exception $exception) {
            throw new CustomerRepoException(CustomerRepoException::GET_CUSTOMER_DETAIL_FAILED);
        }
    }

    /**
     * Lấy option nhóm khách hàng
     *
     * @return mixed|void
     * @throws CustomerRepoException
     */
    public function getCustomerGroup()
    {
        try {
            $mCustomerGroup = app()->get(CustomerGroupTable::class);
            //Lấy thông tin nhóm khách hàng
            return $mCustomerGroup->getCustomerGroup();
        } catch (\Exception $exception) {
            throw new CustomerRepoException(CustomerRepoException::GET_CUSTOMER_GROUP_FAILED);
        }
    }

    /**
     * Thêm khách hàng
     *
     * @param $input
     * @return mixed|void
     * @throws CustomerRepoException
     */
    public function store($input)
    {
        try {
            $mCustomerBranch = app()->get(CustomerBranchTable::class);
            $mConfig = app()->get(ConfigTable::class);

            $input['member_level_id'] = 1;
            $input['created_by'] = Auth()->id();
            $input['updated_by'] = Auth()->id();
            $input['point_rank'] = 0;
            $input['account_money'] = 0;
            $input['phone1'] = $input['phone'];
            $input['branch_id'] = Auth()->user()->branch_id;

            unset($input['brand_code'], $input['phone']);

            //Kiểm tra sđt đã tồn tại chưa
            $checkPhone = $this->customer->checkPhoneExist($input['phone1'], '');

            if (!empty($checkPhone['phone1'])) {
                //Kiểm tra KH đó có ở chi nhánh này không
                $getCustomerBranch = $mCustomerBranch->getBranchByCustomer($checkPhone['customer_id'], Auth()->user()->branch_id);

                if ($getCustomerBranch != null || Auth()->user()->is_admin == 1 || !in_array('permission-customer-branch',session('routeList'))) {
                    throw new CustomerRepoException(CustomerRepoException::STORE_CUSTOMER_FAILED, __('Số điện thoại đã tồn tại'));
                }

                //Khách hàng chưa có chi nhánh (Kiểm tra cấu hình có tự động insert chi nhánh không)
                $getInsertBranch = $mConfig->getInfoByKey('is_insert_customer_branch')['value'];

                if ($getInsertBranch == 1) {
                    //Tự động insert chi nhánh và lấy customer_id ra
                    $mCustomerBranch->add([
                        'customer_id' =>  $checkPhone['customer_id'],
                        'branch_id' => Auth()->user()->branch_id
                    ]);
                    //Return thông tin user
                    return $this->customer->getInfoWhenStore($checkPhone['customer_id']);
                } else {
                    throw new CustomerRepoException(CustomerRepoException::STORE_CUSTOMER_FAILED, __('Khách hàng đã tồn tại ở chi nhánh khác, bạn vui lòng liên hệ quản trị viên để cấp quyền hiển thị khách hàng này'));
                }
            }

            //Tạo khách hàng
            $customerId = $this->customer->add($input);
            //Update customer_code
            $this->customer->edit([
                'customer_code' => 'KH_' . date('dmY') . sprintf("%02d", $customerId)
            ], $customerId);

            //Insert sms log
            $this->addSmsLog($input['phone1']);
            //Send Notify
            SendNotification::dispatch([
                'key' => 'customer_W',
                'customer_id' => $customerId,
                'object_id' => ''
            ]);
            //Lưu log ZNS
//            SaveLogZns::dispatch('new_customer', $customerId, $customerId);
            //Return thông tin user
            return $this->customer->getInfoWhenStore($customerId);
        } catch (\Exception $exception) {
            throw new CustomerRepoException(CustomerRepoException::STORE_CUSTOMER_FAILED, $exception->getMessage());
        }
    }

    /**
     * Thêm sms log
     *
     * @param $idAppointment
     * @param $appointmentCode
     * @param $date
     * @param $time
     */
    private function addSmsLog($phone)
    {
        $mSettingBrandName = app()->get(SmsSettingBrandNameTable::class);
        $mConfig = app()->get(SmsConfigTable::class);
        $mLog = app()->get(SmsLogTable::class);

        $checkSetting = $mSettingBrandName->getSetting(1);
        if ($checkSetting['is_actived'] == 1) {
            $checkConfig = $mConfig->getSmsConfig('new_customer');
            if ($checkConfig['is_active'] == 1) {
                $mSpaInfo = app()->get(SpaInfoTable::class);
                //Lấy thông tin spa
                $spaInfo = $mSpaInfo->getInfo(1);
                //Lấy thông tin khách hàng
                $oUser = $this->customer->getUserByPhone($phone);

                if ($oUser['gender'] == 'male') {
                    $gender = __('Anh');
                } else if ($oUser['gender'] == 'female') {
                    $gender = __('Chị');
                } else {
                    $gender = __('Anh/Chị');
                }
                //replace giá trị của tham số
                $params = [
                    '{CUSTOMER_NAME}',
                    '{CUSTOMER_FULL_NAME}',
                    '{CUSTOMER_GENDER}',
                    '{NAME_SPA}'
                ];
                $explodeName = explode(' ', $oUser['full_name']);
                $replaceParams = [
                    array_pop($explodeName),
                    $oUser['full_name'],
                    $gender,
                    $spaInfo['name']
                ];
                $contentLog = $checkConfig['content'];
                $message = str_replace($params, $replaceParams, $contentLog);
                //Insert Sms Log
                $dataLog = [
                    'brandname' => $checkSetting['value'],
                    'phone' => $oUser['phone1'],
                    'customer_name' => $oUser['full_name'],
                    'message' => $message,
                    'sms_type' => 'new_customer',
                    'time_sent' => null,
                    'created_by' => 0,
                    'sms_status' => 'new',
                    'object_id' => $oUser['customer_id'],
                    'object_type' => 'customer',
                ];
                $mLog->add($dataLog);
            }
        }
    }

    /**
     * Lấy lịch sử mua hàng
     *
     * @param $input
     * @return mixed|void
     * @throws CustomerRepoException
     */
    public function historyOrder($input)
    {
        try {
            $mOrder = app()->get(OrderTable::class);

            //Lấy lịch sử mua hàng
            $data = $this->toPagingData($mOrder->getOrders($input, $input['customer_id']));

            $dataItem = $data['Items'];

            if (count($dataItem) > 0) {
                foreach ($dataItem as $item) {
                    //Lấy status name của đơn hàng
                    $item['process_status_name'] = $this->setStatusName($item['process_status']);
                    //Lấy cờ huỷ or xoá
                    $isRemove = 0;
                    $isCancel = 0;

                    if ($item['process_status'] == 'new') {
                        $isRemove = 1;
                    }

                    $dateNow = Carbon::now()->format('Y-m-d');
                    $dateCreated = Carbon::createFromFormat('Y-m-d H:i:s', $item['created_at'])->format('Y-m-d');

                    if (in_array($item['process_status'], ['paysuccess', 'pay-half']) && $dateNow == $dateCreated) {
                        $isCancel = 1;
                    }

                    $item['is_remove'] = $isRemove;
                    $item['is_cancel'] = $isCancel;
                }
            }

            return $data;
        } catch (\Exception $exception) {
            throw new CustomerRepoException(CustomerRepoException::GET_HISTORY_ORDER_FAILED);
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
                $statusName = __('Huỷ');
                break;
        }

        return $statusName;
    }

    /**
     * Cập nhật khách hàng
     *
     * @param $input
     * @return mixed|void
     * @throws CustomerRepoException
     */
    public function update($input)
    {
        try {
            $mCustomerBranch = app()->get(CustomerBranchTable::class);
            $mConfig = app()->get(ConfigTable::class);

            $input['phone1'] = $input['phone'];

            unset($input['brand_code'], $input['phone']);

            //Kiểm tra sđt đã tồn tại chưa
            $checkPhone = $this->customer->checkPhoneExist($input['phone1'], $input['customer_id']);

            if (!empty($checkPhone['phone1'])) {
                //Kiểm tra KH đó có ở chi nhánh này không
                $getCustomerBranch = $mCustomerBranch->getBranchByCustomer($checkPhone['customer_id'], Auth()->user()->branch_id);

                if ($getCustomerBranch != null || Auth()->user()->is_admin == 1 || !in_array('permission-customer-branch',session('routeList'))) {
                    throw new CustomerRepoException(CustomerRepoException::STORE_CUSTOMER_FAILED, __('Số điện thoại đã tồn tại'));
                }

                //Khách hàng chưa có chi nhánh (Kiểm tra cấu hình có tự động insert chi nhánh không)
                $getInsertBranch = $mConfig->getInfoByKey('is_insert_customer_branch')['value'];

                if ($getInsertBranch == 1) {
                    //Tự động insert chi nhánh và lấy customer_id ra
                    $mCustomerBranch->add([
                        'customer_id' =>  $checkPhone['customer_id'],
                        'branch_id' => Auth()->user()->branch_id
                    ]);
                    throw new CustomerRepoException(CustomerRepoException::STORE_CUSTOMER_FAILED, __('Số điện thoại đã tồn tại'));
                } else {
                    throw new CustomerRepoException(CustomerRepoException::STORE_CUSTOMER_FAILED, __('Khách hàng đã tồn tại ở chi nhánh khác, bạn vui lòng liên hệ quản trị viên để cấp quyền hiển thị khách hàng này'));
                }
            }

            //Cập nhật thông tin khách hàng
            $this->customer->edit($input, $input['customer_id']);

            //Return thông tin user
            return $this->customer->getInfoWhenStore($input['customer_id']);
        } catch (\Exception $exception) {
            throw new CustomerRepoException(CustomerRepoException::GET_CUSTOMER_UPDATE_FAILED, $exception->getMessage());
        }
    }

     /**
     * Danh sách bình luận
     * @param $data
     * @return mixed|void
     */
    public function listComment($data)
    {
        try {

            $mManageComment = new CustomerCommentTable();

            $listComment = $mManageComment->getListComment($data['customer_id']);
            if (count($listComment) != 0) {
                foreach ($listComment as $key => $item) {
                    $listComment[$key]['list_object'] = $mManageComment->getListComment($item['customer_id'], $item['customer_comment_id']);
                   
                }
            }

            return $listComment;
        } catch (\Exception $exception) {
            throw new CustomerRepoException(CustomerRepoException::GET_DATA_FAILED, $exception->getMessage() . $exception->getLine());
        }
    }

    /**
     * Tạo comment
     * @param $data
     * @return mixed|void
     */
    public function createdComment($data)
    {
        try {
            $mCustomerComment = new CustomerCommentTable();

            $createdComment = [
                'customer_id' => $data['customer_id'],
                'customer_parent_comment_id' => isset($data['customer_parent_comment_id']) ? $data['customer_parent_comment_id'] : null,
                'staff_id' => Auth::id(),
                'message' => isset($data['message']) ? $data['message'] : null,
                'path' => isset($data['path']) ? $data['path'] : null,
                'created_at' => Carbon::now(),
                'created_by' => Auth::id(),
                'updated_at' => Carbon::now(),
                'updated_by' => Auth::id()
            ];

            //Thêm bình luận
            $idComment = $mCustomerComment->createdComment($createdComment);

            // $detailComment = $mCustomerComment->getDetail($idComment);

            //Gửi notify bình luận ticket
            // $listCustomer = $this->getListStaff($data['ticket_id']);

            // foreach ($listCustomer as $item) {
            //     if ($item != Auth()->id()) {
            //         \App\Jobs\FunctionSendNotify::dispatch([
            //             'type' => SEND_NOTIFY_STAFF,
            //             'key' => 'ticket_comment_new',
            //             'customer_id' => Auth()->id(),
            //             'object_id' => $data['ticket_id'],
            //             'branch_id' => Auth()->user()->branch_id,
            //             'tenant_id' => session()->get('idTenant')
            //         ]);
            //     }
            // }
            return $this->listComment($data);
        } catch (\Exception $exception) {
            throw new CustomerRepoException(CustomerRepoException::GET_DATA_FAILED, $exception->getMessage() . $exception->getLine());
        }
    }
}