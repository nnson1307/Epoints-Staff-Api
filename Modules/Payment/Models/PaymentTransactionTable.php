<?php


/**
 * @Author : VuND
 */

namespace Modules\Payment\Models;


class PaymentTransactionTable extends AbstractBaseModel
{
    protected $table = 'payment_transaction';
    protected $primaryKey = 'TransactionMasterId';

    public $timestamps = false;


    protected $fillable = [
        'PartnerID',
        'TransactionType',
        'TransactionID',
        'AccessCode',
        'InvoiceReference',
        'InvoiceNumber',
        'TotalAmount',
        'DeviceID',
        'CustomerIP',
        'Language',
        'RequestTime',
        'ResponseTime',
        'ResponseCode',
        'ResponseMessage',
        'Status',
        'Message',
        'Code',
        'Retry',
        'CreatedAt',
        'CreatedBy',
        'UpdatedAt',
        'UpdatedBy',
    ];

    public function getTransactionByAccessCode($accessCode, $type){
        return $this->where('AccessCode', $accessCode)
                    ->where('TransactionType', $type)
                    ->first();
    }

    public function getTransactionFail(){
        return $this->where('Status', '<>','S')
            ->where('Retry', '<', 3)
            ->get();
    }
}
