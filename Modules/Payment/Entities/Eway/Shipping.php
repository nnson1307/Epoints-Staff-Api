<?php
namespace Modules\Payment\Entities\Eway;

use Modules\Payment\Entities\BaseAbstractEntity;

/**
 * Class PaymentShipping
 * @author VuND
 * @since Aug, 2020
 */
class Shipping extends BaseAbstractEntity
{
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

    public $ShippingMethod = \Eway\Rapid\Enum\ShippingMethod::NEXT_DAY;

    /**
     * PayloadMessage constructor.
     * @param array $data
     */
    public function __construct(array $data = [])
    {
        parent::__construct($data);
    }
}
