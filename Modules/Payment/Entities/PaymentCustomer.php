<?php
namespace Modules\Payment\Entities;

/**
 * Class PaymentCustomer
 * @author VuND
 * @since Aug, 2020
 */
class PaymentCustomer extends BaseAbstractEntity
{
    public $TransactionMasterId;

    public $Reference;

    public $Title;

    public $FirstName;

    public $LastName;

    public $CompanyName;

    public $JobDescription;

    public $Street1;

    public $Street2;

    public $City;

    public $State;

    public $PostalCode;

    public $Country;

    public $Phone;

    public $Mobile;

    public $Email;

    public $Url;

    /**
     * PayloadMessage constructor.
     * @param array $data
     */
    public function __construct(array $data)
    {
        parent::__construct($data);
    }
}
