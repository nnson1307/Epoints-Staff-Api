<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 27/04/2021
 * Time: 15:45
 */

namespace Modules\Booking\Libs\Sms;


class SmsFactory
{
    const NEW_APPOINTMENT = 'new_appointment';
    const CANCEL_APPOINTMENT = 'cancel_appointment';

    /**
     * Gửi sms theo key
     *
     * @param $smsType
     * @return mixed
     */
    public static function sendSms($smsType)
    {
        switch ($smsType) {
            case self::NEW_APPOINTMENT:
                return app()->get(NewAppointment::class);

            case self::CANCEL_APPOINTMENT:
                return app()->get(CancelAppointment::class);
        }
    }
}