<?php

namespace Modules\User\Repositories\Authen;

use App\Auth\MyAuthNotFoundException;
use App\Jobs\SendNotification;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Modules\User\Libs\UploadImage;
use Modules\User\Models\ActionTable;
use Modules\Ticket\Models\TicketStaffQueueTable;
use Modules\User\Models\AdminFeatureTable;
use Modules\User\Models\AdminServiceBrandFeatureChildTable;
use Modules\User\Models\BranchTable;
use Modules\User\Models\CustomerAccountTable;
use Modules\User\Models\CustomerContactTable;
use Modules\User\Models\CustomerDeviceTable;
use Modules\User\Models\DeviceTokenTable;
use Modules\User\Models\DistrictTable;
use Modules\User\Models\MapRoleGroupStaffTable;
use Modules\User\Models\ProvinceTable;
use Modules\User\Models\SmsConfigTable;
use Modules\User\Models\SmsLogTable;
use Modules\User\Models\SmsSettingBrandNameTable;
use Modules\User\Models\StaffDeviceTable;
use Modules\User\Models\StaffTable;
use Modules\User\Models\UserCarrierTable;
use Modules\User\Models\UserTable;
use Modules\User\Repositories\OTP\OtpRepoException;
use Modules\User\Repositories\OTP\OtpRepoInterface;
use Modules\User\Repositories\User\UserRepoException;
use MyCore\Helper\RegisterDeviceToken;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\JWTAuth;
use Tymon\JWTAuth\Token;
use Illuminate\Support\Arr;

/**
 * Interface AuthenRepoInterface
 * @package Modules\User\Repositories\Authen
 * @author DaiDP
 * @since Aug, 2019
 */
class AuthenRepo implements AuthenRepoInterface
{
    use RegisterDeviceToken;

    protected $user;


    /**
     * AuthenRepo constructor.
     * @param StaffTable $mUser
     */
    public function __construct(StaffTable $mUser)
    {
        $this->user = $mUser;
    }

    /**
     * Login app
     *
     * @param $data
     * @return \Illuminate\Contracts\Auth\Authenticatable|mixed|null
     * @throws AuthenRepoException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function login($data)
    {

        if (isset($data['imei'])) {
            $mStaffDevice = app()->get(StaffDeviceTable::class);
            $mStaffDevice->removeByImei($data['imei']);
        }

        //Lấy thông tin user login
        $oUser = $this->checkUserLogin($data['user_name'], $data['password']);

        $platform = isset($data['platform']) ? $data['platform'] : '';
        $device_token = isset($data['device_token']) ? $data['device_token'] : '';
        $imei = isset($data['imei']) ? $data['imei'] : '';

        //Đăng ký aws
        $this->subcribeAmazon($platform, $device_token, $oUser['staff_id'], $imei);

        //Lấy quyền app
        $oUser['permission'] = $this->_getPermission();

        $mTicketStaffQueue = new TicketStaffQueueTable();

        $getQueue = $mTicketStaffQueue->getQueueStaff(Auth::id());
        $oUser['queue_name'] = $getQueue != null ? $getQueue['ticket_queue_name'] : '';
        $oUser['queue_role'] = $getQueue != null ? $getQueue['ticket_role_queue_name'] : '';
        return $oUser;
    }

    /**
     * Login app
     *
     * @param $data
     * @return \Illuminate\Contracts\Auth\Authenticatable|mixed|null
     * @throws AuthenRepoException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function loginV2($data)
    {

        if (isset($data['imei'])) {
            $mStaffDevice = app()->get(StaffDeviceTable::class);
            $mStaffDevice->removeByImei($data['imei']);
        }

        //Lấy thông tin user login
        $oUser = $this->checkUserLogin($data['user_name'], $data['password']);

        $platform = isset($data['platform']) ? $data['platform'] : '';
        $device_token = isset($data['device_token']) ? $data['device_token'] : '';
        $imei = isset($data['imei']) ? $data['imei'] : '';

        //Đăng ký aws
        $this->subcribeAmazon($platform, $device_token, $oUser['staff_id'], $imei);

        //Lấy quyền app
        // $oUser['permission'] = $this->_getPermission();

        $mTicketStaffQueue = new TicketStaffQueueTable();

        $getQueue = $mTicketStaffQueue->getQueueStaff(Auth::id());
        $oUser['queue_name'] = $getQueue != null ? $getQueue['ticket_queue_name'] : '';
        $oUser['queue_role'] = $getQueue != null ? $getQueue['ticket_role_queue_name'] : '';
        return $oUser;
    }

    /**
     * Lấy quyền app
     *
     * @return array
     */
    public function _getPermission()
    {
        $arrService = [];
        $mFeatureChild = new AdminServiceBrandFeatureChildTable();
        //Lấy bảng quyền dịch vụ được cấp cho brand
        $allService = $mFeatureChild->getAllService();

        if (count($allService) > 0) {
            foreach ($allService as $v) {
                $arrService[] = $v['feature_code'];
            }
        }

        //Lấy quyền
        $mMapRoleGroup = app()->get(MapRoleGroupStaffTable::class);
        //Lấy ds quyền của nhân viên
        $permission = $mMapRoleGroup->getRoleActionByStaff(Auth()->id(), $arrService);
        //Lấy session các quyền chức năng của nv (để xử lý data ở trong)
        $this->_getSessionPermission();

        return $permission;
    }

    /**
     * Lưu session các quyền nhân viên portal để xử lý data
     */
    private function _getSessionPermission()
    {
        $mFeatureChild = new AdminServiceBrandFeatureChildTable();
        $mapRoleGroupStaff = new MapRoleGroupStaffTable();
        $actions = new ActionTable();

        $arrService = [];
        $arrayRole = [];

        //Lấy bảng quyền dịch vụ được cấp cho brand
        $allService = $mFeatureChild->getTotalService();

        if (count($allService) > 0) {
            foreach ($allService as $v) {
                $arrService[] = $v['feature_code'];
            }
        }

        if (Auth()->user()->is_admin == 1) {
            //Lấy quyền action
            $getRoleAction = $actions->getAllRoute($arrService);
        } else {
            //Lấy quyền action
            $getRoleAction = $mapRoleGroupStaff->getAllRoleActionByStaff(Auth()->id(), $arrService);
        }

        if (count($getRoleAction) > 0) {
            foreach ($getRoleAction as $v) {
                $arrayRole[] = $v['widget_id'];
            }
        }

        //Push session quyền khi login thành công
        request()->session()->put('routeList', $arrayRole);
    }

    /**
     * refresh token access to app
     *
     * @param $data
     * @return mixed
     * @throws AuthenRepoException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function refreshToken($data)
    {
        $token = new Token($data['refresh_token']);
        $jwt = app(JWTAuth::class);
        try {
            $jwt->setToken($token);

            // refresh token
            $token = $jwt->refresh();
            $jwt->authenticate();

            // Lưu thời gian last login
            $this->user->edit([
                'date_last_login' => Carbon::now()->format('Y-m-d H:i:s'),
                'remember_token' => $token
            ], Auth()->id());

            $oUser = $jwt->user();

            if (empty($oUser)) {
                throw new TokenInvalidException();
            }
        } catch (TokenExpiredException $e) {
            throw new AuthenRepoException(__('Token hết hạn'));
        } catch (TokenInvalidException $e) {
            throw new AuthenRepoException(__('Token không đúng'));
        } catch (JWTException $e) {
            throw new AuthenRepoException(__('Token hết hạn'));
        }


        $mBranch = app()->get(BranchTable::class);
        //Lấy thông tin chi nhánh của NV
        $infoBranch = $mBranch->getBranch($oUser['branch_id']);
        if($infoBranch != null){
            $oUser['branch_name'] = $infoBranch['branch_name'];
            $oUser['branch_code'] = $infoBranch['branch_code'];
            $oUser['branch_address'] = $infoBranch['address'];
        }
        
        //Lấy token
        $oUser['access_token'] = $token;

        $platform = isset($data['platform']) ? $data['platform'] : '';
        $device_token = isset($data['device_token']) ? $data['device_token'] : '';
        $imei = isset($data['imei']) ? $data['imei'] : '';

        //Đăng ký aws
        $this->subcribeAmazon($platform, $device_token, $oUser['staff_id'], $imei);

        //Lấy quyền app
        $oUser['permission'] = $this->_getPermission();

        $mTicketStaffQueue = new TicketStaffQueueTable();

        $getQueue = $mTicketStaffQueue->getQueueStaff(Auth::id());
        $oUser['queue_name'] = $getQueue != null ? $getQueue['ticket_queue_name'] : '';
        $oUser['queue_role'] = $getQueue != null ? $getQueue['ticket_role_queue_name'] : '';

        //Lấy session các quyền chức năng của nv (để xử lý data ở trong)
        $this->_getSessionPermission();

        //Hard quyền mới nữa bổ sung sau
        //        $oUser['permission'][] = [
        //            "widget_id" => "TK000000",
        //            "widget_name" => "Ticket"
        //        ];
        //
        //        $oUser['permission'][] = [
        //            "widget_id" => "WK000000",
        //            "widget_name" => "Work"
        //        ];

        return $oUser;
    }

    /**
     * Check dieu kien login
     *
     * @param $oUser
     * @throws AuthenRepoException
     */
    protected function checkAutoLogin($oUser)
    {
        //Check customer của account đó is_actived, is_deleted, phone_verified
        $customerActive = $this->user->getUserActive($oUser['customer_id']);

        if ($customerActive == null) {
            throw new AuthenRepoException(__('Tài khoản chưa tồn tại. Nếu chưa có tài khoản vui lòng thực hiện đăng ký'));
        }

        //        if ($oUser->is_deleted) {
        //            throw new AuthenRepoException(__('Tài khoản chưa tồn tại. Nếu chưa có tài khoản vui lòng thực hiện đăng ký'));
        //        }
        //
        //        if (!$oUser->is_actived) {
        //            throw new AuthenRepoException(__('Tài khoản đã tồn tại nhưng không được quyền đăng nhập. Vui lòng liên hệ hotline để biết thêm thông tin'));
        //        }
    }

    /**
     * Lấy thông tin tk login
     *
     * @param $userName
     * @param $password
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     * @throws AuthenRepoException
     */
    protected function checkUserLogin($userName, $password)
    {
        // Kiểm tra tài khoản
        try {
            $credentials = [
                'user_name' => $userName,
                'password' => $password,
                'is_actived' => 1,
                'is_deleted' => 0
            ];

            //Login = customer_account
            $token = auth()->claims([
                'sid' => $userName,
                'brand_code' => session()->get('brand_code')
            ])->attempt($credentials);

            if (!$token) {
                throw new AuthenRepoException(__('Tài khoản hoặc Mật khẩu không đúng'));
            }
        } catch (MyAuthNotFoundException $ex) {
            throw new AuthenRepoException(__('Tài khoản hoặc Mật khẩu không đúng'));
        }

        //Lấy thông tin nhân viên
        $oUser = Auth()->user();
        $tokenMd5 = $oUser['token_md5'];
        if ($tokenMd5 == null) {
            $tokenMd5 = md5($token);
        }
        //Update date login
        // $tokenMd5 = md5($token);
        $this->user->edit([
            'date_last_login' => Carbon::now()->format('Y-m-d H:i:s'),
            'remember_token' => $token,
            'token_md5' => $tokenMd5
        ], Auth()->id());

        $mBranch = app()->get(BranchTable::class);
        //Lấy thông tin chi nhánh của NV
        $infoBranch = $mBranch->getBranch($oUser['branch_id']);

        $oUser['branch_name'] = $infoBranch['branch_name'] ?? null;
        $oUser['branch_code'] = $infoBranch['branch_code'] ?? null;
        $oUser['branch_address'] = $infoBranch['address'] ?? null;

        $oUser->access_token = $token;
        $oUser->token_md5 = $tokenMd5;
        return $oUser;
    }

    /**
     * Đăng ký token với amazone
     *
     * @param $platform
     * @param $deviceToken
     * @param $staffId
     * @param $imei
     * @return string
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    protected function subcribeAmazon($platform, $deviceToken, $staffId, $imei)
    {
        if (empty($deviceToken) && empty($platform) && empty($imei)) {
            return '';
        }

        $tenant_id = session()->get('idTenant');

        try {
            //Check user đã dk customer_device chưa
            $mStaffDevice = app()->get(StaffDeviceTable::class);

            $checkDevice = $mStaffDevice->checkImei($staffId, $imei);

            if ($checkDevice == null) {
                //Đăng ký device token
                $this->_registerDeviceToken($tenant_id, $staffId, $platform, $deviceToken, $imei);
            } else {
                //Check device token truyền lên so với device token (db)
                if ($deviceToken != $checkDevice['token']) {
                    //Xoá device của imei này
                    $mStaffDevice->removeByImei($imei);
                    //Call đăng ký device lại
                    $this->_registerDeviceToken($tenant_id, $staffId, $platform, $deviceToken, $imei);
                } else {
                    //Call active token lại
                    $this->_registerDeviceToken($tenant_id, $staffId, $platform, $deviceToken, $imei);
                }
            }
        } catch (\Exception $ex) {
            return '';
        }

        return '';
    }

    /**
     * Function call api đăng ký device token qua aws
     *
     * @param $tenantId
     * @param $staffId
     * @param $platform
     * @param $deviceToken
     * @param $imei
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    private function _registerDeviceToken($tenantId, $staffId, $platform, $deviceToken, $imei)
    {
        $oClient = new Client();

        $response = $oClient->request('POST', NAE_SERVICE_URL . '/notification/register', [
            'json' => [
                'tenant_id' => $tenantId,
                'staff_id' => $staffId,
                'platform' => $platform,
                'token' => $deviceToken,
                'imei' => $imei
            ]
        ]);

        $data = json_decode($response->getBody()->getContents(), true);

        if (isset($data['ErrorCode']) && $data['ErrorCode'] == 0) {
            return $data['Data']['endpoint_arn'];
        }
    }

    /**
     * Lấy thông tin địa chỉ giao hàng, tỉnh thành, quận huyện user
     *
     * @param $oUser
     * @return array
     */
    protected function getContactAddress($oUser)
    {
        //Lấy địa chỉ giao hàng của user
        $mCustomerContact = app()->get(CustomerContactTable::class);
        $address = $mCustomerContact->getContact($oUser['customer_id'])->toArray();

        return [
            'delivery_address' => $address
        ];
    }

    /**
     * Lấy thông tin phân quyền của app
     *
     * @return array|mixed
     * @throws AuthenRepoException
     */
    public function getPermission()
    {
        try {
            $mMapRoleGroup = app()->get(MapRoleGroupStaffTable::class);
            //Lấy ds quyền của nhân viên
            return $mMapRoleGroup->getRoleActionByStaff(Auth()->id());
        } catch (\Exception $e) {
            throw new AuthenRepoException(__('Lấy thông tin quyền thất bại'));
        }
    }

    /**
     * Logout app
     *
     * @param $input
     * @return mixed|void
     * @throws AuthenRepoException
     */
    public function logout($input)
    {
        try {
            //            $jwt = app(JWTAuth::class);
            //            $mDevice = new DeviceTokenTable();
            //            $mDevice->disableToken(auth()->id(), $jwt->getClaim('imei'));
            //            auth()->logout(true);

            $mStaffDevice = app()->get(StaffDeviceTable::class);
            $mStaff = app()->get(StaffTable::class);

            if (isset($input['imei'])) {
                $checkImei = $mStaffDevice->checkImei(Auth()->id(), $input['imei']);

                if ($checkImei != null) {
                    //Xoá imei của thiết bị này
                    $mStaffDevice->removeById($checkImei['staff_device_id']);
                }
            }

            //Xoá md5 của nhân viên
            $mStaff->edit([
                'token_md5' => null
            ], Auth()->id());
        } catch (\Exception $e) {
            throw new AuthenRepoException(__('Đăng xuất thất bại'));
        }
    }


    /**
     * Login Fb, GG, Zalo, AppleId
     *
     * @param $data
     * @return mixed
     * @throws AuthenRepoException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function loginService($data)
    {
        try {
            //Lấy thông tin user login = service
            $oUser = $this->getOrCreateUser($data);
            //Đăng ký aws
            $this->subcribeAmazon($data['platform'], $data['device_token'], $oUser['customer_id'], $data['imei']);
            //Lấy thông tin địa chỉ giao hàng, tỉnh thành, quận huyện
            $contact = $this->getContactAddress($oUser);
            $oUser['delivery_address'] = $contact['delivery_address'];

            return $oUser;
        } catch (AuthenRepoException $e) {
            throw new AuthenRepoException(__('Đăng nhập thất bại'));
        }
    }

    /**
     * Tạo hoặc lấy user nếu tồn tại
     *
     * @param $data
     * @return mixed
     * @throws AuthenRepoException
     */
    protected function getOrCreateUser($data)
    {
        $mCustomerAccount = app()->get(CustomerAccountTable::class);
        //Lấy thông tin account đã đăng ký chưa
        $getAccountService = $mCustomerAccount->getUserByService($data['object_type'], $data['object_id']);

        $isNew = 0;

        $data['full_name'] = !empty($data['full_name']) ? $data['full_name'] : __("Khách hàng vãng lai");

        if ($getAccountService == null) {
            //Tạo account
            $idAccount = $this->insertAccountService($data['object_type'], $data['object_id']);
            //Tạo customer
            $customer = $this->user->createUser([
                'full_name' => $data['full_name'],
                'customer_group_id' => 1,
                'is_actived' => 1,
                'member_level_id' => 1,
                'created_by' => 0,
                'updated_by' => 0,
            ]);
            //Update customer_code
            $this->user->editUser([
                'customer_code' => 'KH_' . date('dmY') . sprintf("%02d", $customer['customer_id'])
            ], $customer['customer_id']);
            //Update customer_id lại cho account
            $mCustomerAccount->edit([
                'customer_id' => $customer['customer_id']
            ], $idAccount);
            //Send Notify
            SendNotification::dispatch([
                'key' => 'customer_W',
                'customer_id' => $customer['customer_id'],
                'object_id' => ''
            ]);
            $isNew = 1;
        }
        //Login lấy token
        $getAccountService = $mCustomerAccount->getUserByService($data['object_type'], $data['object_id']);

        $token = auth()->login($getAccountService);

        if (!$token) {
            throw new AuthenRepoException(__('Đăng nhập thất bại'));
        }

        //Lấy thông tin customer
        $oUser = $this->user->getInfoUserLogin(Auth()->id());
        //Đăng nhập chính thống
        $oUser['is_quick_login'] = 0;
        $oUser->access_token = $token;
        $oUser['is_new'] = $isNew;

        return $oUser;
    }

    /**
     * Tạo account khi login service
     *
     * @param $objectType
     * @param $objectId
     * @return mixed
     */
    protected function insertAccountService($objectType, $objectId)
    {
        $mCustomerAccount = app()->get(CustomerAccountTable::class);

        switch ($objectType) {
            case "facebook":
                $idAccount = $mCustomerAccount->add([
                    'FbId' => $objectId
                ]);
                break;
            case "google":
                $idAccount = $mCustomerAccount->add([
                    'GoogleId' => $objectId
                ]);
                break;
            case "zalo":
                $idAccount = $mCustomerAccount->add([
                    'ZaloId' => $objectId
                ]);
                break;
            case "apple":
                $idAccount = $mCustomerAccount->add([
                    'AppleId' => $objectId
                ]);
                break;
            case "imei":
                $idAccount = $mCustomerAccount->add([
                    'imei' => $objectId
                ]);
                break;
        }

        return $idAccount;
    }


    /**
     * Gửi OTP Xác thực SĐT để đăng ký tài khoản
     *
     * @param bool $throwError
     * @param $oUser
     * @throws UserRepoException
     */
    protected function sendVerifyOtp($throwError = true, $oUser)
    {
        $rOtp = app(OtpRepoInterface::class);

        try {
            $rOtp->sendOtp($oUser['customer_id'], $oUser['phone'], 'register');
        } catch (OtpRepoException $ex) {
            if ($throwError) {
                throw new UserRepoException($ex->getMessage());
            }
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
                $oUser = $this->user->getUserByPhone($phone);
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
                    'Piospa'
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
     * Đăng nhập nhanh
     *
     * @param $input
     * @return mixed
     * @throws AuthenRepoException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function quickLogin($input)
    {
        try {
            //Lấy thông tin user đăng nhập nhanh
            $oUser = $this->checkUserQuickLogin($input['imei']);
            //Đăng ký aws
            $this->subcribeAmazon($input['platform'], $input['device_token'], $oUser['customer_id'], $input['imei']);
            //Lấy thông tin địa chỉ giao hàng, tỉnh thành, quận huyện
            $contact = $this->getContactAddress($oUser);
            $oUser['delivery_address'] = $contact['delivery_address'];

            return $oUser;
        } catch (AuthenRepoException $e) {
            throw new AuthenRepoException(__('Đăng nhập thất bại'));
        }
    }

    /**
     * Check tài khoản đăng nhập nhanh
     *
     * @param $imei
     * @return mixed
     * @throws AuthenRepoException
     */
    public function checkUserQuickLogin($imei)
    {
        $mCustomerAccount = app()->get(CustomerAccountTable::class);
        //Lấy thông tin account đã đăng ký chưa
        $getAccountImei = $mCustomerAccount->getUserQuickLogin($imei);

        $isNew = 0;

        $data['full_name'] = __("Khách hàng vãng lai");

        if ($getAccountImei == null) {
            //Tạo account
            $idAccount = $this->insertAccountService("imei", $imei);
            //Tạo customer
            $customer = $this->user->createUser([
                'full_name' => $data['full_name'],
                'customer_group_id' => 1,
                'is_actived' => 1,
                'member_level_id' => 1,
                'created_by' => 0,
                'updated_by' => 0,
            ]);
            //Update customer_code
            $this->user->editUser([
                'customer_code' => 'KH_' . date('dmY') . sprintf("%02d", $customer['customer_id'])
            ], $customer['customer_id']);
            //Update customer_id lại cho account
            $mCustomerAccount->edit([
                'customer_id' => $customer['customer_id']
            ], $idAccount);
            //Send Notify
            SendNotification::dispatch([
                'key' => 'customer_W',
                'customer_id' => $customer['customer_id'],
                'object_id' => ''
            ]);
            $isNew = 1;
        }
        //Login lấy token
        $getAccountService = $mCustomerAccount->getUserQuickLogin($imei);

        $token = auth()->login($getAccountService);

        if (!$token) {
            throw new AuthenRepoException(__('Đăng nhập thất bại'));
        }

        //Lấy thông tin customer
        $oUser = $this->user->getInfoUserLogin(Auth()->id());
        //Đăng nhập chính thống
        $oUser['is_quick_login'] = 1;
        $oUser->access_token = $token;
        $oUser['is_new'] = $isNew;

        return $oUser;
    }

    /**
     * Đăng ký device token từ portal
     *
     * @param $input
     * @return mixed|void
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function registerDeviceTokenPortal($input)
    {
        //Đăng ký aws
        $this->subcribeAmazon($input['platform'], $input['device_token'], $input['staff_id'], $input['imei']);
    }

    /**
     * Cập nhập avatar user
     *
     * @param array $all
     * @return mixed
     * @throws AuthenRepoException
     */
    public function uploadAvatar(array $all)
    {
        try {
            $imageFile = getimagesize($all['avatar']);

            if ($imageFile == false) {
                throw new AuthenRepoException('',AuthenRepoException::FILE_NOT_TYPE);
            }

            $fileSize = number_format(filesize($all['avatar']) / 1048576, 2); //MB

            if ($fileSize > 20) {
                throw new AuthenRepoException('', AuthenRepoException::MAX_FILE_SIZE);
            }

            $link = UploadImage::uploadImageS3($all['avatar'], '_avatar.');

            $this->user->edit([
                'staff_avatar' => $link,
                'updated_at' => Carbon::now()
            ], Auth()->id());
            return $this->user->getInfoUserLogin(Auth()->id());
        } catch (\Exception | QueryException $exception) {
            throw new AuthenRepoException('', AuthenRepoException::GET_UPLOAD_FILE_FAILED);
        }
    }

    /**
     * Upload file lên thư viện
     *
     * @param $input
     * @return array|mixed
     * @throws AuthenRepoException
     */
    public function uploadFile($input)
    {
        try {
            $imageFile = getimagesize($input['file_name']);

            if ($imageFile == false) {
                throw new AuthenRepoException('', AuthenRepoException::FILE_NOT_TYPE);
            }

            $fileSize = number_format(filesize($input['file_name']) / 1048576, 2); //MB

            if ($fileSize > 20) {
                throw new AuthenRepoException('', AuthenRepoException::MAX_FILE_SIZE);
            }

            $link = UploadImage::uploadImageS3($input['file_name'], '_user.');

            return [
                'link' => $link
            ];
        } catch (\Exception | QueryException $exception) {
            throw new AuthenRepoException('', AuthenRepoException::GET_UPLOAD_FILE_FAILED);
        }
    }

    /**
     * Login app
     *
     * @param $data
     * @return \Illuminate\Contracts\Auth\Authenticatable|mixed|null
     * @throws AuthenRepoException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getInfoPerMission()
    {
        //Lấy quyền app
        $oUser['permission'] = $this->_getPermission();

        // $mTicketStaffQueue = new TicketStaffQueueTable();

        // $getQueue = $mTicketStaffQueue->getQueueStaff(Auth::id());
        // $oUser['queue_name'] = $getQueue != null ? $getQueue['ticket_queue_name'] : '';
        // $oUser['queue_role'] = $getQueue != null ? $getQueue['ticket_role_queue_name'] : '';
        return $oUser;
    }

    /**
     * Xóa user theo policy của apple
     * @param array $all
     * @return mixed
     */
    public function delete(array $all)
    {
        $staff = Auth::user();
        $username = "{$staff['user_name']}_{$staff['staff_id']}";
        $phone = "{$staff['phone']}_{$staff['staff_id']}";
        // cập nhật thông tin bảng staffs
        $this->deleteStaff($staff['staff_id'], $phone, $username);

        // Cập nhật thông tin bảng staff_device
        $this->deleteStaffDevice($staff['staff_id']);
    }
    private function deleteStaff($idStaff, $phone, $username)
    {
        $this->user->edit(
            [
                "user_name" => $username,
                "phone1" => $phone,
                "is_deleted" => 1
            ],
            $idStaff
        );
    }

    private function deleteStaffDevice($idStaff)
    {
        $customerDeviceTable = app(StaffDeviceTable::class);
        $customerDeviceTable->deleteDevice($idStaff);
    }
}
