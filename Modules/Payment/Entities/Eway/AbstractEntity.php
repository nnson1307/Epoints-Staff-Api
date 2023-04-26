<?php


/**
 * @Author : VuND
 */

namespace Modules\Payment\Entities\Eway;


use Modules\Payment\Entities\Eway\Customer;

use Modules\Payment\Entities\BaseAbstractEntity;

abstract class AbstractEntity extends BaseAbstractEntity
{
    /**
     * JobMessageModel constructor.
     * @param array $data
     */
    public function __construct(array $data)
    {
        foreach ($data as $key => $val) {
            if (property_exists($this, $key)) {
                if(is_array($val)){
                    $factory = new EntityFactory();
                    $this->$key = $factory->getInstance($key, $val);
                } else {
                    $this->$key = $val;
                }
            }
        }
    }

    /**
     * Convert message to array
     * @return array
     */
    public function toArray()
    {
        return get_object_vars($this);
    }
}
