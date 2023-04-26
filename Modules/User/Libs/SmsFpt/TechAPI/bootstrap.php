<?php

namespace Modules\User\Libs\SmsFpt;
require_once realpath(__DIR__) . '/Autoload.php';

TechAPIAutoloader::register();

use Modules\User\Libs\SmsFpt\TechAPI\src\TechAPI\Constant;
use Modules\User\Libs\SmsFpt\TechAPI\src\TechAPI\Client;
use Modules\User\Libs\SmsFpt\TechAPI\src\TechAPI\Auth\ClientCredentials;
//use Modules\User\Models\SmsProviderTable;

// config api
Constant::configs(array(
    'mode' => Constant::MODE_LIVE,
//    'mode'            => Constant::MODE_SANDBOX,
    'connect_timeout' => 15,
    'enable_cache' => true,
    'enable_log' => true,
    'log_path' => realpath(__DIR__) . '/logs'
));


// config client and authorization grant type
function getTechAuthorization()
{
//    $smsProvider=new SmsProviderTable();
//    $clientId=$smsProvider->getItem(1)->account;
//    $clientSecret=$smsProvider->getItem(1)->password;
//    $client = new Client(
//    //'YOUR_CLIENT_ID',
//    //'YOUR_CLIENT_SECRET',
//        $clientId,
//        $clientSecret,
//        array('send_brandname', 'send_brandname_otp')
//    );
    $client = new Client(
        '574c082d16868c921D68516E8b4F9988A5c6F1a9',
        '9fcd2cEDf821ff85fcf89be3fcc4629cc26b20b414Dc59f814d1527e08e1bf60DB3662e2',
        array('send_brandname', 'send_brandname_otp')
    );
    return new ClientCredentials($client);
}
