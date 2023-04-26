<?php
namespace Modules\Payment\Entities;

/**
 * Class PaymentEway
 * @author VuND
 * @since Aug, 2020
 */
class PaymentEway extends BaseAbstractEntity
{
    public $Customer;

    public $ShippingAddress;

    public $Items;

    public $Options;

    public $Payment;

    public $RedirectUrl;

    public $CancelUrl;

    public $DeviceID;

    public $CustomerIP;

    public $PartnerID;

    public $LogoUrl;

    public $TransactionType = \Eway\Rapid\Enum\TransactionType::PURCHASE;

    public $Capture;

    public $HeaderText;

    public $Language = 'EN';

    public $CustomerReadOnly = true;

    /**
     * PayloadMessage constructor.
     * @param array $data
     */
    public function __construct(array $data)
    {
        parent::__construct($data);
    }
}
