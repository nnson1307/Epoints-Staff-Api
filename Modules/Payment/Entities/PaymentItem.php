<?php
namespace Modules\Payment\Entities;

/**
 * Class PaymentItem
 * @author VuND
 * @since Aug, 2020
 */
class PaymentItem extends BaseAbstractEntity
{
    public $TransactionMasterId;

    public $Reference;

    public $SKU;

    public $Description;

    public $Quantity;

    public $UnitCost;

    /**
     * PayloadMessage constructor.
     * @param array $data
     */
    public function __construct(array $data)
    {
        parent::__construct($data);
    }
}
