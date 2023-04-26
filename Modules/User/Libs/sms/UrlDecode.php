<?php
/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 2/25/2019
 * Time: 7:14 PM
 */

namespace Modules\User\Libs\sms;


class UrlDecode
{
    public static function paramsArray(array $params)
    {
        return self::getPostVal($params);
    }


    protected static function getPostVal($arrData)
    {
        $postvars = array();

        foreach($arrData as $key=>$val)
        {
            $postvars[] = $key.'=' . urlencode($val);
        }

        return implode('&', $postvars);
    }
}