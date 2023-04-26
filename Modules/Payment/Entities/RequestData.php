<?php
namespace Modules\Payment\Entities;

/**
 * Class ResponseData
 * @author VuND
 * @since Aug, 2020
 */
class RequestData extends BaseAbstractEntity
{
    public $AccessCode;

    public $PaymentUrl;

    public $InvoiceNumber;

    public $InvoiceReference;

    /**
     * PayloadMessage constructor.
     * @param array $data
     */
    public function __construct(array $data)
    {
        parent::__construct($data);
    }
}
