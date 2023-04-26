<?php
namespace Modules\Payment\Entities;

/**
 * Class PaymentLog
 * @author VuND
 * @since Aug, 2020
 */
class PaymentLog extends BaseAbstractEntity
{
    public $TransactionMasterId;

    public $Type;

    public $Worker;

    public $Status;

    public $Code;

    public $Message;

    public $DataInput;

    public $DataOutput;

    public $CreatedAt;

    /**
     * PayloadMessage constructor.
     * @param array $data
     */
    public function __construct(array $data)
    {
        parent::__construct($data);
    }
}
