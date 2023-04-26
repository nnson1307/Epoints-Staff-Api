<?php
namespace Modules\Payment\Entities\Eway;

use Modules\Payment\Entities\BaseAbstractEntity;

/**
 * Class PaymentItem
 * @author VuND
 * @since Aug, 2020
 */
class Payment extends BaseAbstractEntity
{
    public $TotalAmount;

    public $InvoiceNumber;

    public $InvoiceDescription;

    public $InvoiceReference;

    public $CurrencyCode;

    private $_ConversionRate = 100;

    /**
     * PayloadMessage constructor.
     * @param array $data
     */
    public function __construct(array $data)
    {
        parent::__construct($data);

        $this->TotalAmount = $this->TotalAmount * $this->_ConversionRate;
    }
}
