<?php
/**
 * Created by PhpStorm
 * User: Mr Son
 * Date: 08-04-02020
 * Time: 9:21 AM
 */

namespace Modules\Product\Repositories\ProductCategory;


use MyCore\Repository\RepositoryExceptionAbstract;

class ProductCategoryRepoException extends RepositoryExceptionAbstract
{
    const GET_PRODUCT_CATEGORY_LIST_FAILED = 0;
    const GET_OPTION_PRODUCT_CATEGORY_FAILED = 1;
    const GET_PRODUCT_CATEGORY_ETL_FAILED = 2;

    public function __construct(int $code = 0, string $message = "")
    {
        parent::__construct($message ? : $this->transMessage($code), $code);
    }

    protected function transMessage($code)
    {
        switch ($code)
        {
            case self::GET_PRODUCT_CATEGORY_LIST_FAILED :
                return __('Lấy danh sách loại sản phẩm thất bại.');

            case self::GET_OPTION_PRODUCT_CATEGORY_FAILED :
                return __('Lấy option loại sản phẩm thất bại.');

            case self::GET_PRODUCT_CATEGORY_ETL_FAILED :
                return __('Lấy thông tin loại sản phẩm thất bại.');

            default:
                return null;
        }
    }
}