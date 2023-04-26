<?php
namespace Modules\Payment\Entities\Eway;

use Modules\Payment\Entities\BaseAbstractEntity;

/**
 * Class PaymentItem
 * @author VuND
 * @since Aug, 2020
 */
class Options extends BaseAbstractEntity
{
    public $Value;

    /**
     * PayloadMessage constructor.
     * @param array $data
     */
    public function __construct(array $data = [])
    {
        parent::__construct($data);
    }
}
