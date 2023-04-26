<?php
/**
 * Created by PhpStorm
 * User: Mr Son
 * Date: 07-04-02020
 * Time: 11:21 PM
 */

namespace Modules\Support\Repositories\Support;


use MyCore\Repository\RepositoryExceptionAbstract;

class SupportRepoException extends RepositoryExceptionAbstract
{
    const GET_LIST_FAILED = 0;

    public function __construct(int $code = 0, string $message = "")
    {
        parent::__construct($message ? : $this->transMessage($code), $code);
    }

    protected function transMessage($code)
    {
        switch ($code)
        {
            case self::GET_LIST_FAILED :
                return __('Lấy danh sách thất bại.');
            default:
                return null;
        }
    }
}