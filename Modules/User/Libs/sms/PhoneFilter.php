<?php
/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 2/25/2019
 * Time: 6:29 PM
 */

namespace Modules\User\Libs\sms;


class PhoneFilter
{
    public function filter($value)
    {
        /*$value = trim($value);

        return preg_replace('/^0/', 84, $value);*/
        $value = trim($value);
        $value = preg_replace('/\D/', '', $value);

        return preg_replace('/^0|^(?!84)/', 84, $value);
    }

    public function filterNoteReplace($value)
    {
        /*$value = trim($value);

        return preg_replace('/^0/', 84, $value);*/
        $value = trim($value);
        $value = preg_replace('/\D/', '', $value);

        return $value;
    }
}