<?php
/**
 * Created by PhpStorm
 * User: Mr Son
 * Date: 8/7/2020
 * Time: 4:38 PM
 */

namespace Modules\Home\Repositories\Home;
use MyCore\Repository\RepositoryExceptionAbstract;

class HomeRepoException extends RepositoryExceptionAbstract
{
    const GET_LIST_BANNER_FAILED = 0;
    const GET_LIST_REMIND_FAILED = 1;
    const SEARCH_ALL_FAILED = 2;

    public function __construct(int $code = 0, string $message = "")
    {
        parent::__construct($message ? : $this->transMessage($code), $code);
    }

    protected function transMessage($code)
    {
        switch ($code)
        {
            case self::GET_LIST_BANNER_FAILED :
                return __('Lấy danh sách  thất bại.');

            case self::GET_LIST_REMIND_FAILED :
                return __('Lấy danh sách thất bại.');

            case self::SEARCH_ALL_FAILED :
                return __('Tìm kiếm thất bại.');

            default:
                return null;
        }
    }
}