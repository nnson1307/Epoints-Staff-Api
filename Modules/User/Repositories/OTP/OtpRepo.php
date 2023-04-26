<?php
/**
 * Created by PhpStorm   .
 * User: Mr Son
 * Date: 2020-02-26
 * Time: 4:52 PM
 * @author SonDepTrai
 */

namespace Modules\User\Repositories\OTP;


use Carbon\Carbon;
use Modules\User\Http\Api\PlusPointApi;
use Modules\User\Libs\sms\SendSms;
use Modules\User\Models\CustomerAccountTable;
use function Modules\User\Libs\SmsFpt\getTechAuthorization;
use Modules\User\Libs\SmsFpt\TechAPI\src\TechAPI\Api\SendBrandnameOtp;
use Modules\User\Libs\SmsFpt\TechAPI\src\TechAPI\Auth\ClientCredentials;
use Modules\User\Libs\SmsFpt\TechAPI\src\TechAPI\Client;
use Modules\User\Models\OtpLogTable;
use Modules\User\Models\SmsConfigTable;
use Modules\User\Models\SmsSettingBrandNameTable;
use Modules\User\Libs\SmsFpt\TechAPI\boostrap;
use Modules\User\Libs\SmsFpt\TechAPI\src\TechAPI\Exception as TechException;
use Modules\User\Libs\SmsFpt\TechAPI\src\TechAPI\Auth\AccessToken;
use Modules\User\Models\UserTable;


class OtpRepo implements OtpRepoInterface
{
    protected $sendSms;

    public function __construct(SendSms $sendSms)
    {
        $this->sendSms = $sendSms;
    }

    /**
     * Gửi OTP
     *
     * @param $userId
     * @param $phone
     * @param $optType
     * @return mixed|void
     */
    public function sendOtp($userId, $phone, $optType)
    {
        $mBrandName = app()->get(SmsSettingBrandNameTable::class);

        $setting = $mBrandName->getSetting(1);

        $mSmsConfig = app()->get(SmsConfigTable::class);
        //Lấy nội dung OTP
        $contentOtp = $mSmsConfig->getSmsConfig('otp');
        // Lấy thông tin OTP code
        if (in_array(session()->get('brand_code'), ['giakhang'])) {
            $code = $this->generateRandomCode();
        } else {
            $code = 123456;
        }

        $message = str_replace(
            ['{CODE}',], [$code,], $contentOtp['content']);

        //Insert Otp Log
        $mOtpLog = app()->get(OtpLogTable::class);
        $dataLog = [
            'brandname' => $setting['value'],
            'telco' => $setting['provider'],
            'customer_id' => $userId,
            'phone' => $phone,
            'message' => $message,
            'otp' => $code,
            'otp_type' => $optType,
            'otp_expired' => Carbon::now()->addMinutes(30)->format('y-m-d H:i:s')
        ];
        $mOtpLog->updateStatusOtpOld($userId, $optType);
        $idLog = $mOtpLog->add($dataLog);
        //Config Sms
        $arrConfig = [
            'phone' => $phone,
            'message' => $message,
            '_USER_NAME' => $setting['account'],
            '_PASSWORD' => $setting['password'],
            '_BRAND_NAME' => $setting['value'],
            'idTransaction' => 1
        ];
        //Send Sms theo nhà mạng
        if ($setting != null && $setting['is_actived'] == 1) {
            $result = [];
            if ($setting['provider'] == 'vietguys') {
                $send = $this->sendSms->send($arrConfig);
                $result = json_decode($send, true);
            } else if ($setting['provider'] == 'fpt') {
//                $send = $this->sendSmsFpt($arrConfig);
//                $result = json_decode($send, true);
            } else if ($setting['provider'] == 'clicksend') {
                $config = [
                    'source' => 'php',
                    'message' => $message,
                    'phone' => $phone,
                    'brand_name' => $setting['value'],
                ];
                $send = $this->sendSms->clickSend($config);
                $result = json_decode($send, true);
            }

            if (isset($result['error']) && $result['error'] == false) {
                $mOtpLog->edit([
                    'is_sent' => 1,
                    'time_send' => Carbon::now()->format('y-m-d H:i:s')
                ], $idLog);
            }
        }
    }

    /**
     * Tạo code random
     *
     * @return string
     */
    protected function generateRandomCode()
    {
        if (isset($_GET['load-test'])) {
            return '123456';
        }

        $chars = "0123456789";
        srand((double)microtime() * 1000000);
        $i = 0;
        $code = '';

        while ($i < 6) {
            $num = rand() % 10;
            $tmp = substr($chars, $num, 1);
            $code = $code . $tmp;
            $i++;
        }

        return $code;
    }

    /**
     * Gửi sms bằng nhà mạng fpt
     *
     * @param $data
     * @return false|string
     */
    protected function sendSmsFpt($data)
    {
        $data['Phone'] = $data['phone'];
        $data['Message'] = $data['message'];
        $data['ServiceNum'] = 8700;
        $data['BrandName'] = $data['_BRAND_NAME'];
        unset($data['_USER_NAME'], $data['_PASSWORD'], $data['phone'], $data['message'], $data['_BRAND_NAME']);

        // Khởi tạo đối tượng API với các tham số phía trên.
        $apiSendBrandname = new SendBrandnameOtp($data);

        try {
            // Lấy đối tượng Authorization để thực thi API
//            $oGrantType = getTechAuthorization();
            $client = new Client(
                '574c082d16868c921D68516E8b4F9988A5c6F1a9',
                '9fcd2cEDf821ff85fcf89be3fcc4629cc26b20b414Dc59f814d1527e08e1bf60DB3662e2',
                array('send_brandname', 'send_brandname_otp')
            );
            $oGrantType = new ClientCredentials($client);

            // Thực thi API
            $arrResponse = $oGrantType->execute($apiSendBrandname);


            // kiểm tra kết quả trả về có lỗi hay không
            if (!empty($arrResponse['error'])) {
                // Xóa cache access token khi có lỗi xảy ra từ phía server
                AccessToken::getInstance()->clear();

                // quăng lỗi ra, và ghi log
                throw new TechException($arrResponse['error_description'], $arrResponse['error']);
            }


            return json_encode([
                'error' => false,
                'errorCode' => 0,
                'message' => 'Gửi thành công',
                'data' => null,
            ]);

        } catch (\Exception $ex) {

            return json_encode([
                'error' => true,
                'errorCode' => $ex->getCode(),
                'message' => $ex->getMessage(),
                'data' => null,
            ]);
        }
    }

    /**
     * Xác thực OTP
     *
     * @param $phone
     * @param $codeType
     * @param $code
     * @return mixed
     */
    public function verifyOtp($phone, $codeType, $code)
    {
        $mUser = app()->get(CustomerAccountTable::class);
        $mOtpLog = app()->get(OtpLogTable::class);

        $oUser = $mUser->getUserByPhone($phone);

        // Lấy otp code
        $oCode = $mOtpLog->getOtp($oUser['customer_id'], $codeType, $code);

        if (!$oCode) {
            return self::OTP_ERR_INVALID;
        }

        // Check thời gian OTP còn hạng không
        $curTime = Carbon::now();
        if ($oCode->otp_expired < $curTime) {
            // Xóa OTP Code
            $mOtpLog->edit(['is_actived' => 1], $oCode['id']);
            return self::OTP_ERR_EXPIRED;
        } else {
            // Xóa OTP Code
            $mOtpLog->edit(['is_actived' => 1], $oCode['id']);

            if ($oCode['otp_type'] == 'register') {
                //Cộng điểm khi active_app
                $this->plusPoint([
                    'customer_id' => $oUser['customer_id'],
                    'rule_code' => 'actived_app',
                    'object_id' => ''
                ]);
            }

            //Lấy thông tin user account
            $mCustomerAccount = app()->get(CustomerAccountTable::class);
            $oUser = $mCustomerAccount->getUserByPhone($phone);

            return $oUser;
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
        $brandCode = session()->get('brand_code');

        $endpoint = sprintf(BASE_URL_API, $brandCode) . '/loyalty/plus-point-event';

        $client = new \GuzzleHttp\Client();

        $response = $client->request('post', $endpoint, ['query' => $param]);

        $statusCode = $response->getStatusCode();
        $content = $response->getBody();

        return json_decode($response->getBody(), true);
    }
}