<?php
//
define('CODE_ERROR', 1);
define('CODE_SUCCESS', 0);
define('CODE_UNAUTHORIZED', 401);
define('PAGING_ITEM_PER_PAGE', 10);
define('CORE_PAGING_ITEM', 8);
define('AUTO_COMPLETE_ITEM', 10);

define('SERVICES_UPLOADS_PATH', 'uploads/services/services/');
define('DATE_SYSTEM_FORMAT', 'Y-m-d H:i:s');
define('DATE_ONLY_SYSTEM_FORMAT', 'Y-m-d');

define('DB_ERROR_DUPLICATE', 23000);

define('SMS_OTP_EXPIRE', 300); // Thời gian expire OTP SMS tính theo giây
define('SMS_OTP_LIMIT', 3); // Số lần gửi SMS 1 ngày

define('VIETNAM_COUNTRY_ID', 1);

define('NAE_SERVICE_URL', env('NAE_SERVICE_URL'));

define('BASE_URL_API', env('BASE_URL_API'));

define('DOMAIN_API_EPOINTS', env('DOMAIN_API_EPOINTS'));

define('PIOSPA_QUEUE_URL', env('PIOSPA_QUEUE_URL'));

define('DOMAIN_CHAT_EPOINTS', env('DOMAIN_CHAT_EPOINTS'));

define('STAFF_QUEUE_URL', env('STAFF_QUEUE_URL'));

define('SEND_NOTIFY_CUSTOMER', 'notify_customer');
define('SEND_NOTIFY_STAFF', 'notify_staff');
define('SEND_EMAIL_CUSTOMER', 'email_customer');
define('SEND_SMS_CUSTOMER', 'sms_customer');
define('SEND_ZNS_CUSTOMER', 'zns_customer');

define('REGISTER_BRAND_DEFAULT', env('REGISTER_BRAND_DEFAULT', 'sale'));
define('REGISTER_BRAND_CUSTOMER_SOURCE', env('REGISTER_BRAND_CUSTOMER_SOURCE', 27));
define('REGISTER_BRAND_PIPELINE_CODE', env('REGISTER_BRAND_PIPELINE_CODE', 'PIPELINE_1201202201'));
define('REGISTER_BRAND_JOURNEY_CODE', env('REGISTER_BRAND_JOURNEY_CODE', 'PJD_CUSTOMER_NEW'));
define('REGISTER_BRAND_URL_DEFAULT', env('REGISTER_BRAND_URL_DEFAULT'));
define('ARRAY_PRIORITY', array(
    [
        'priority_id' => 'N',
        'priority_name' => 'Thấp',
        'is_defalut'    => 0
    ],
    [
        'priority_id' => 'L',
        'priority_name' => 'Bình thường',
        'is_defalut'    => 1
    ],
    [
        'priority_id' => 'H',
        'priority_name' => 'Cao',
        'is_defalut'    => 0
    ],
));

return [

];

/**
 * Cấp độ sự cố
 *
 * @param $level
 * @return array
 */
if (!function_exists('levelIssue')) {
    function levelIssue($level = 'list')
    {
        if ($level == 'list') {
            return [
                1 => 'Cấp 1',
                2 => 'Cấp 2',
                3 => 'Cấp 3',
                4 => 'Cấp 4',
                5 => 'Cấp 5',
            ];
        }
        return 'Cấp ' . $level;
    }
}

/**
 * Cấp độ sự cố
 *
 * @param $level
 * @return array
 */
if (!function_exists('getPriority')) {
    function getPriority($priority = 'list')
    {
        $arr = [
            'N' => 'Thấp',
            'L' => 'Bình thường',
            'H' => 'Cao',
        ];
        if ($priority == 'list') {
            return $arr;
        }
        return isset($arr[$priority]) ? $arr[$priority] : '';
    }
}