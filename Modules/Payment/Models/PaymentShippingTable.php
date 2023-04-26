<?php


/**
 * @Author : VuND
 */

namespace Modules\Payment\Models;


class PaymentShippingTable extends AbstractBaseModel
{
    protected $table = 'payment_transaction_shipping';
    protected $primaryKey = 'TransactionShippingId';
}
