<?php
/**
 * Created by PhpStorm   .
 * User: Mr Son
 * Date: 2020-01-06
 * Time: 2:14 PM
 * @author SonDepTrai
 */

namespace Modules\Booking\Repositories\Address;


use MyCore\Repository\RepositoryExceptionAbstract;

class AddressRepoException extends RepositoryExceptionAbstract
{
    const GET_OPTION_PROVINCE_FAILED = 0;
    const GET_OPTION_DISTRICT_FAILED = 1;
    const GET_OPTION_WARD_FAILED = 1;

    public function __construct(int $code = 0, string $message = "")
    {
        parent::__construct($message ? : $this->transMessage($code), $code);
    }

    protected function transMessage($code)
    {
        switch ($code)
        {
            case self::GET_OPTION_PROVINCE_FAILED :
                return __('Lấy danh sách tỉnh thành thất bại.');

            case self::GET_OPTION_DISTRICT_FAILED :
                return __('Lấy danh sách quận huyện thất bại.');

            case self::GET_OPTION_WARD_FAILED :
                return __('Lấy danh sách phường xã thất bại.');

            default:
                return null;
        }
    }
}