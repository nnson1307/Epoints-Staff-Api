<?php


/**
 * @Author : VuND
 */

namespace Modules\Payment\Repositories\Payment;


interface PaymentInterface
{
    public function getClient($data);

    public function call($data);

    public function callback($data);

    public function response($data);

    public function rePay($data);

}
