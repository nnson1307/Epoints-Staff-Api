<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 27/04/2021
 * Time: 15:48
 */

namespace Modules\Booking\Libs\Sms;


abstract class SmsAbstract
{
    /**
     * Lưu log sms
     *
     * @param $input
     * @return mixed
     */
    abstract public function insertLogSms($input);
}