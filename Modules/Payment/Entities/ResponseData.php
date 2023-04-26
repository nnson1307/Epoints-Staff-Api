<?php
namespace Modules\Payment\Entities;

/**
 * Class ResponseData
 * @author VuND
 * @since Aug, 2020
 */
class ResponseData extends BaseAbstractEntity
{
    public $TransactionStatus;

    public $TransactionID;

    public $AccessCode;

    public $InvoiceNumber;

    public $InvoiceReference;

    public $ResponseCode;

    public $ResponseMessage;

    /**
     * PayloadMessage constructor.
     * @param array $data
     */
    public function __construct(array $data)
    {
        parent::__construct($data);
    }
}
