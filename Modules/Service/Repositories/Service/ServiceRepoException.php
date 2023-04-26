<?php
/**
 * Created by PhpStorm   .
 * User: Mr Son
 * Date: 2020-01-09
 * Time: 11:23 AM
 * @author SonDepTrai
 */

namespace Modules\Service\Repositories\Service;


use MyCore\Repository\RepositoryExceptionAbstract;

class ServiceRepoException extends RepositoryExceptionAbstract
{
    const GET_SERVICE_LIST_FAILED = 0;
    const GET_SERVICE_HISTORY_LIST_FAILED = 1;
    const GET_SERVICE_REPRESENTATIVE_FAILED = 2;
    const GET_SERVICE_DETAIL_FAILED = 3;
    const GET_GENERAL_INFO_FAILED = 4;
    const LIKE_UNLIKE_FAILED = 5;
    const GET_SERVICE_FAVOURITE_FAILED = 6;

    public function __construct(int $code = 0, string $message = "")
    {
        parent::__construct($message ?: $this->transMessage($code), $code);
    }

    protected function transMessage($code)
    {
        switch ($code) {
            case self::GET_SERVICE_LIST_FAILED :
                return __('Lấy danh sách dịch vụ thất bại.');

            case self::GET_SERVICE_HISTORY_LIST_FAILED :
                return __('Lấy danh sách lịch sử dịch vụ thất bại.');

            case self::GET_SERVICE_REPRESENTATIVE_FAILED :
                return __('Lấy danh sách dịch vụ theo chi nhánh chính thất bại.');

            case self::GET_SERVICE_DETAIL_FAILED :
                return __('Lấy chi tiết dịch vụ thất bại.');

            case self::GET_GENERAL_INFO_FAILED :
                return __('Lấy thông tin chung dịch vụ thất bại.');

            case self::LIKE_UNLIKE_FAILED :
                return __('Thích / không thích dịch vụ thất bại.');

            case self::GET_SERVICE_FAVOURITE_FAILED :
                return __('Lấy danh sách dịch vụ yêu thích thất bại.');

            default:
                return null;
        }
    }
}