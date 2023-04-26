<?php


/**
 * @Author : VuND
 */

namespace Modules\Payment\Models;


class PaymentItemTable extends AbstractBaseModel
{
    protected $table = 'payment_transaction_item';
    protected $primaryKey = 'TransactionItemId';
}
