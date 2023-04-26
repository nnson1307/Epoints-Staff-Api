<?php

/**
 * @Author : VuND
 */

namespace Modules\Payment\Repositories;


use Modules\Payment\Repositories\Payment\Eway;

class PaymentFactory
{
    const EWAY = 'eway';
    const MOMO = 'momo';
    const VNPAY = 'vnpay';

    public static function getInstance($type)
    {
        switch ($type)
        {
            case self::EWAY:
                return new Eway();
        }

        throw new PaymentException(PaymentException::SYS_UNKNOWN_METHOD);
    }
}
