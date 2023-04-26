<?php


/**
 * @Author : VuND
 */

namespace Modules\Payment\Models;


class PaymentCustomerTable extends AbstractBaseModel
{
    protected $table = 'payment_transaction_customer';
    protected $primaryKey = 'TransactionCusId';
}
