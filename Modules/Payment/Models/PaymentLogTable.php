<?php


/**
 * @Author : VuND
 */

namespace Modules\Payment\Models;


class PaymentLogTable extends AbstractBaseModel
{
    protected $table = 'payment_transaction_log';
    protected $primaryKey = 'TransactionLogId';
}
