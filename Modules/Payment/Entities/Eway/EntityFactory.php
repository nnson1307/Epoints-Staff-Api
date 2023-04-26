<?php

/**
 * @Author : VuND
 */

namespace Modules\Payment\Entities\Eway;


use Modules\Payment\Repositories\Payment\Eway;

class EntityFactory
{
    const Customer = 'Customer';
    const Items = 'Items';
    const ShippingAddress = 'ShippingAddress';
    const Payment = 'Payment';
    const Options = 'Options';

    public static function getInstance($type, $data)
    {
        switch ($type)
        {
            case self::Customer:
                return (new Customer($data))->toArray();

            case self::Items:
                $items =[];
                foreach ($data as $item){
                    $items[] = (new Item($item))->toArray();
                }
                return $items;

            case self::ShippingAddress:
                return (new Shipping($data))->toArray();

            case self::Payment:
                return (new Payment($data))->toArray();

            case self::Options:
                $items =[];
                foreach ($data as $item){
                    $items[] = (new Options($item))->toArray();
                }
                return $items;
        }
    }

    private function convertData(){

    }
}
