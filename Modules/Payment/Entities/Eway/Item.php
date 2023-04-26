<?php
namespace Modules\Payment\Entities\Eway;

use Modules\Payment\Entities\BaseAbstractEntity;

/**
 * Class PaymentItem
 * @author VuND
 * @since Aug, 2020
 */
class Item extends BaseAbstractEntity
{
    public $SKU;

    public $Description;

    public $Quantity;

    public $UnitCost;

    private $_ConversionRate = 100;

    /**
     * PayloadMessage constructor.
     * @param array $data
     */
    public function __construct(array $data)
    {
        parent::__construct($data);
        $this->UnitCost = $this->UnitCost * $this->_ConversionRate;
    }
}
