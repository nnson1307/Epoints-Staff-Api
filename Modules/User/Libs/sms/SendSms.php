<?php
/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 2/25/2019
 * Time: 6:24 PM
 */

namespace Modules\User\Libs\sms;


use Modules\User\Models\ConfigTable;
use Modules\User\Models\SmsSettingBrandNameTable;

error_reporting(E_ALL);
ini_set('display_errors', 1);

header("Access-Control-Allow-Origin: *");
#header("Access-Control-Allow-Origin: https://prod2.giadinhnestle.com.vn");
header("Access-Control-Allow-Methods: POST,OPTIONS");
header("Access-Control-Allow-Headers: X-CSRF-Token");
header("Content-type: application/json; charset=utf-8");

class SendSms
{
//    private $_URL_API_SMS = 'http://cloudsms.vietguys.biz:8088/api/index.php';
    private $_URL_API_SMS_VIETGUYS = 'http://cloudsms.vietguys.biz:8088/api/index.php';
    private $_URL_API_SMS_FPT = '';
    private $_URL_API_SMS_VIETTEL = '';
    private $_URL_API_SMS_VHT = '';
    private $_URL_API_SMS_ST = '';
    private $_URL_API_SMS_CLICK_SEND = 'https://rest.clicksend.com/v3/sms/send';

    private $_ERROR_CODE = [
        '-1' => 'Chưa truyền đầy đủ tham số',
        '-2' => 'Máy chủ đang bận',
        '-3' => 'Không tìm thấy tài khoản người dùng',
        '-4' => 'Tài khoản bị khóa',
        '-5' => 'Thông tin xác thực chưa chính xác',
        '-6' => 'Chưa kích hoạt tính năng gửi qua API',
        '-7' => 'IP bị giới hạn truy cập',
        '-8' => 'Tên thương hiệu chưa khai báo',
        '-9' => 'Tài khoản hết credits gửi tin',
        '-10' => 'Số điện thoại chưa chính xác',
        '-11' => 'Số điện thoại nằm trong danh sách từ chối nhận tin',
        '-12' => 'Hết credits gửi tin',
        '-13' => 'Tên thương hiệu chưa khai báo',
        '-14' => 'Số kí tự vượt quá 459 kí tự (lỗi tin nhắn dài)',
        '-16' => 'Gửi trùng số điện thoại, thương hiệu, nội dung trong 01 phút',
        '-17' => 'quá số lượng tin trong 1 ngày cho 1 tk.',
        '-18' => 'spam keyword.',
        '-19' => 'quá số lượng tin trong 1 ngày cho 1 số điện thoại.',
    ];
    protected $smsProvider;

    public function __construct()
    {
        $this->smsProvider = new SmsSettingBrandNameTable();
    }

    public function send($config)
    {
        $provider = $this->smsProvider->getSetting(1);

        $_URL_API_SMS=$this->_URL_API_SMS_VIETGUYS;
        $phone = $config['phone'];
        $message = $config['message'];
        $idTransaction = $config['idTransaction'];

        $_USER_NAME = $config['_USER_NAME'];
        $_PASSWORD = $config['_PASSWORD'];
        $_BRAND_NAME = $config['_BRAND_NAME'];

        try {

            if (trim($phone) == '') {
                return json_encode([
                    'error' => true,
                    'errorCode' => null,
                    'data' => null,
                    'message' => 'Phone invalid'
                ]);
            }
//
            if (trim($message) == '') {
                return json_encode([
                    'error' => true,
                    'errorCode' => null,
                    'data' => null,
                    'message' => 'Message invalid'
                ]);
            }

            if (trim($_URL_API_SMS) == '') {
                return json_encode([
                    'error' => true,
                    'errorCode' => null,
                    'data' => null,
                    'message' => 'API sms null'
                ]);
            }
            $phone = $this->_validPhone($phone);

            if (!$phone) {
                return json_encode([
                    'error' => true,
                    'errorCode' => null,
                    'data' => null,
                    'message' => 'Phone invalid'
                ]);
            }

            $sendSMS = [
                'u' => $_USER_NAME,
                'pwd' => $_PASSWORD,
                'from' => $_BRAND_NAME,
                'phone' => $phone,
                'sms' => NString::removeDiacriticalMarks($message),
                'bid' => $idTransaction
            ];

            $oURL = new Curl();

            $oURL->setPostParams($sendSMS);

            $result = $oURL->execute($_URL_API_SMS);
            $code = (int)$result;

            if ($code >= 0) {
                $arrResult = [
                    'error' => false,
                    'errorCode' => 0,
                    'data' => $result,
                    'message' => 'Send SMS success'
                ];
            } else {
                $arrResult = [
                    'error' => true,
                    'errorCode' => $code,
                    'data' => null,
                    'message' => isset($this->_ERROR_CODE[$code]) ? $this->_ERROR_CODE[$code] : 'Lỗi không xác định'
                ];
            }
        } catch (\Exception $ex) {
            $arrResult = [
                'error' => true,
                'errorCode' => 0,
                'data' => null,
                'message' => $ex->getMessage()
            ];
        }

        return json_encode($arrResult);
    }

    public function clickSend($config)
    {
        $provider = $this->smsProvider->getSetting(1);

        $_URL_API_SMS=$this->_URL_API_SMS_CLICK_SEND;
        $phone = $config['phone'];
        $message = $config['message'];
        $source = $config['source'];
        $branName = $config['brand_name'];

        $configTable = new ConfigTable();
        $configTmp = $configTable->getInfoByKey('area_code');

        try {

            if (trim($phone) == '') {
                return json_encode([
                    'error' => true,
                    'errorCode' => null,
                    'data' => null,
                    'message' => 'Phone invalid'
                ]);
            }
//
            if (trim($message) == '') {
                return json_encode([
                    'error' => true,
                    'errorCode' => null,
                    'data' => null,
                    'message' => 'Message invalid'
                ]);
            }

            if (trim($_URL_API_SMS) == '') {
                return json_encode([
                    'error' => true,
                    'errorCode' => null,
                    'data' => null,
                    'message' => 'API sms null'
                ]);
            }
//            $phone = $this->_validPhone($phone);
            $phone = $this->_validPhoneNoteReplace($phone);

            if (!$phone) {
                return json_encode([
                    'error' => true,
                    'errorCode' => null,
                    'data' => null,
                    'message' => 'Phone invalid'
                ]);
            }

            if ($branName == null || strlen($branName) == 0) {
                $sendSMS['messages'] = [
                    [
                        'source' => $source,
                        'body' => $message,
                        'to' => $phone,
                        'country' => $configTmp['value'] == null ? 'AU' : $configTmp['value']
                    ]
                ];
            } else {
                $sendSMS['messages'] = [
                    [
                        'source' => $source,
                        'body' => $message,
                        'to' => $phone,
                        'from' => $branName,
                        'country' => $configTmp['value'] == null ? 'AU' : $configTmp['value']
                    ]
                ];
            }

            $oURL = curl_init();
            curl_setopt($oURL, CURLOPT_URL, $_URL_API_SMS);
//            curl_setopt($oURL, CURLOPT_HEADER, TRUE);
            curl_setopt($oURL, CURLOPT_POST, TRUE);
            curl_setopt($oURL, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
            curl_setopt($oURL, CURLOPT_USERPWD, $provider['account'].":".$provider['password']);
            curl_setopt($oURL, CURLOPT_POSTFIELDS, json_encode($sendSMS));
            curl_setopt($oURL, CURLOPT_RETURNTRANSFER, true);
            curl_exec($oURL);
            $response = curl_getinfo($oURL, CURLINFO_HTTP_CODE);
            curl_close($oURL);

            if ($response == 200) {
                $arrResult = [
                    'error' => false,
                    'errorCode' => 0,
                    'data' => $response,
                    'message' => 'Send SMS success'
                ];
            } else {
                $message = '';
                switch ($response) {
                    case 400:
                        $message = 'BAD REQUEST';
                        break;
                    case 401:
                        $message = 'UNAUTHORIZED';
                        break;
                    case 403:
                        $message = 'FORBIDDEN';
                        break;
                    case 404:
                        $message = 'NOT FOUND';
                        break;
                    case 405:
                        $message = 'METHOD NOT ALLOWED';
                        break;
                    case 429:
                        $message = 'TOO MANY REQUESTS';
                        break;
                    case 500:
                        $message = 'INTERNAL SERVER ERROR';
                        break;
                    default:
                        $message = 'AN UNKNOWN ERROR';
                        break;
                }

                $arrResult = [
                    'error' => true,
                    'errorCode' => $response,
                    'data' => null,
                    'message' => $message
                ];
            }
        } catch (\Exception $ex) {
            $arrResult = [
                'error' => true,
                'errorCode' => 0,
                'data' => null,
                'message' => $ex->getMessage()
            ];
        }
        return json_encode($arrResult);
    }


    protected function _validPhone($phone)
    {

        $oPhoneFilter = new PhoneFilter();
        return $oPhoneFilter->filter($phone);
    }

    protected function _validPhoneNoteReplace($phone)
    {

        $oPhoneFilter = new PhoneFilter();
        return $oPhoneFilter->filterNoteReplace($phone);
    }
}