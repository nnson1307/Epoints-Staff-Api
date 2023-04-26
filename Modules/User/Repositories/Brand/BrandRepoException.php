<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 06/05/2021
 * Time: 14:39
 */

namespace Modules\User\Repositories\Brand;


use MyCore\Repository\RepositoryExceptionAbstract;

class BrandRepoException extends RepositoryExceptionAbstract
{
    const GET_BRAND_FAILED = 0;

    public function __construct(int $code = 0, string $message = "")
    {
        parent::__construct($message ? : $this->transMessage($code), $code);
    }

    protected function transMessage($code)
    {
        switch ($code)
        {
            case self::GET_BRAND_FAILED :
                return __('Lấy danh sách thương hiệu thất bại.');

            default:
                return null;
        }
    }
}