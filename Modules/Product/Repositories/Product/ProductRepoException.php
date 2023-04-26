<?php
/**
 * Created by PhpStorm   .
 * User: Mr Son
 * Date: 2020-01-09
 * Time: 9:39 AM
 * @author SonDepTrai
 */

namespace Modules\Product\Repositories\Product;


use MyCore\Repository\RepositoryExceptionAbstract;

class ProductRepoException extends RepositoryExceptionAbstract
{
    const GET_PRODUCT_LIST_FAILED = 0;
    const GET_ALL_PRODUCT_FAILED = 1;
    const GET_PRODUCT_DETAIL_FAILED = 2;
    const GET_PRODUCT_TYPE_LIST_FAILED = 3;
    const GET_PRODUCT_HOME_FAILED = 4;
    const GET_HOT_KEYWORD_FAILED = 5;
    const LIKE_UNLIKE_PRODUCT_FAILED = 6;
    const GET_LIST_PRODUCT_LIKE_FAILED = 7;
    const GET_PRODUCT_ETL_FAILED = 8;
    const GET_GENERAL_INFO_FAILED = 9;
    const UN_ACTIVE_PRODUCT_FAILED = 10;
    const SCAN_PRODUCT_FAILED = 11;

    public function __construct(int $code = 0, string $message = "")
    {
        parent::__construct($message ? : $this->transMessage($code), $code);
    }

    protected function transMessage($code)
    {
        switch ($code)
        {
            case self::GET_PRODUCT_LIST_FAILED :
                return __('Lấy danh sách lịch sử sản phẩm thất bại.');

            case self::GET_ALL_PRODUCT_FAILED :
                return __('Lấy danh sách tất cả sản phẩm thất bại.');

            case self::GET_PRODUCT_DETAIL_FAILED :
                return __('Lấy chi tiết sản phẩm thất bại.');

            case self::GET_PRODUCT_TYPE_LIST_FAILED :
                return __('Lấy danh sách sản phẩm theo type thất bại.');

            case self::GET_PRODUCT_HOME_FAILED :
                return __('Lấy danh sách sản phẩm home page thất bại.');

            case self::GET_HOT_KEYWORD_FAILED :
                return __('Lấy từ khóa tìm kiếm thất bại.');

            case self::GET_LIST_PRODUCT_LIKE_FAILED :
                return __('Lấy danh sách sản phẩm yêu thích thất bại.');

            case self::GET_PRODUCT_ETL_FAILED :
                return __('Lấy sản phẩm ETL thất bại.');

            case self::GET_GENERAL_INFO_FAILED :
                return __('Lấy thông tin chung màn hình sản phẩm thất bại.');

            case self::UN_ACTIVE_PRODUCT_FAILED :
                return __('Un active sản phẩm thất bại.');

            case self::SCAN_PRODUCT_FAILED :
                return __('Scan sản phẩm thất bại');

            default:
                return null;
        }
    }
}