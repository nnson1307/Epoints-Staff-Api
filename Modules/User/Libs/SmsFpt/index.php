<?php
/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 3/4/2019
 * Time: 10:22 AM
 */

namespace Modules\User\Libs\SmsFpt;

require_once realpath(__DIR__) . '/TechAPI/bootstrap.php';

use Modules\User\Libs\SmsFpt\TechAPI\src\TechAPI\Api\SendBrandnameOtp;
use Modules\User\Libs\SmsFpt\TechAPI\src\TechAPI\Auth\AccessToken;
use Modules\User\Libs\SmsFpt\TechAPI\src\TechAPI\Exception;

class index
{
    public function __construct()
    {

    }

    public function send($arrData)
    {
        try {
            // get post data
//            $arrData = array(
//                'Phone' => '01266808286',
//                'BrandName' => 'FTI',
//                'Message' => 'Test gui tin nhan Brandname'
//            );

            // call api
            $oGrantType = getTechAuthorization();
            $apiSendBrandname = new SendBrandnameOtp($arrData);
            $arrData = $oGrantType->execute($apiSendBrandname);

            if (!empty($arrData['error'])) {
                // clear access token when error
                AccessToken::getInstance()->clear();

                throw new Exception($arrData['error_description'], $arrData['error']);
            }

            // Gửi thành công
//            echo '<pre>';
            $arrData['errorrss'] = false;
            return json_encode($arrData);
        } catch (\Exception $ex) {
            // gửi thất bại
//            echo "Mã lỗi: " . $ex->getCode();
//            echo "<br>Mô tả: " . $ex->getMessage();
            $arrayError = [
                'errorrss' => true,
                'errorCode' => $ex->getCode(),
                'errorDescription' => $ex->getMessage(),
            ];

            return json_encode($arrayError);
        }
    }
}