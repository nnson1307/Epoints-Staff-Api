<?php
namespace Modules\Payment\Entities;

/**
 * Class PaymentShipping
 * @author VuND
 * @since Aug, 2020
 */
class PaymentShipping extends BaseAbstractEntity
{
    public $TransactionMasterId;

    public $Reference;

    public $FirstName;

    public $LastName;

    public $Street1;

    public $Street2;

    public $City;

    public $State;

    public $PostalCode;

    public $Country;

    public $Phone;

    public $ShippingMethod;

    /**
     * PayloadMessage constructor.
     * @param array $data
     */
    public function __construct(array $data = [])
    {
        parent::__construct($data);
    }
}
