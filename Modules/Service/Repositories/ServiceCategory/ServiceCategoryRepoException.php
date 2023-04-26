<?php
/**
 * Created by PhpStorm
 * User: Mr Son
 * Date: 07-04-02020
 * Time: 11:21 PM
 */

namespace Modules\Service\Repositories\ServiceCategory;


use MyCore\Repository\RepositoryExceptionAbstract;

class ServiceCategoryRepoException extends RepositoryExceptionAbstract
{
    const GET_SERVICE_CATEGORY_LIST_FAILED = 0;
    const GET_OPTION_SERVICE_CATEGORY_FAILED = 1;

    public function __construct(int $code = 0, string $message = "")
    {
        parent::__construct($message ? : $this->transMessage($code), $code);
    }

    protected function transMessage($code)
    {
        switch ($code)
        {
            case self::GET_SERVICE_CATEGORY_LIST_FAILED :
                return __('Lấy danh sách loại dịch vụ thất bại.');

            case self::GET_OPTION_SERVICE_CATEGORY_FAILED :
                return __('Lấy option loại dịch vụ thất bại.');

            default:
                return null;
        }
    }
}