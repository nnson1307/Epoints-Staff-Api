<?php
/**
 * Created by PhpStorm
 * User: Mr Son
 * Date: 6/12/2020
 * Time: 2:50 PM
 */

namespace Modules\Branch\Repositories\Branch;


use MyCore\Repository\RepositoryExceptionAbstract;

class BranchRepoException extends RepositoryExceptionAbstract
{
    const GET_BRANCH_ETL_FAILED = 0;


    public function __construct(int $code = 0, string $message = "")
    {
        parent::__construct($message ? : $this->transMessage($code), $code);
    }

    protected function transMessage($code)
    {
        switch ($code)
        {
            case self::GET_BRANCH_ETL_FAILED :
                return __('Lấy thông tin chi nhánh thất bại.');

            default:
                return null;
        }
    }
}