<?php
namespace Modules\Payment\Entities;

/**
 * Class PaymentTransaction
 * @author VuND
 * @since Aug, 2020
 */
class PaymentTransaction extends BaseAbstractEntity
{
    public $PartnerID;

    public $TransactionType;

    public $AccessCode;

    public $InvoiceReference;

    public $InvoiceNumber;

    public $TotalAmount;

    public $DeviceID;

    public $CustomerIP;

    public $Language;

    public $RequestTime;

    public $Status;

    public $Message;

    public $Code;

    public $CreatedAt;

    public $CreatedBy;

    /**
     * PayloadMessage constructor.
     * @param array $data
     */
    public function __construct(array $data)
    {
        parent::__construct($data);
    }
}
