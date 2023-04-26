<?php
/**
 * Created by PhpStorm
 * User: Mr Son
 * Date: 6/12/2020
 * Time: 2:50 PM
 */

namespace Modules\Brand\Repositories\Brand;


use MyCore\Repository\RepositoryExceptionAbstract;

class BrandRepoException extends RepositoryExceptionAbstract
{
    const BRAND_FAILED = -1;
    const BRAND_PENDING_STATUS = 0;
    const BRAND_APPROVED_STATUS = 1;
    const BRAND_NOT_EXITS = 2;
    const DECRYPT_PAYLOAD_FAILED = 3;

    public function __construct(int $code = 0, string $message = "")
    {
        parent::__construct($message ? : $this->transMessage($code), $code);
    }

    protected function transMessage($code)
    {
        switch ($code)
        {
            case self::BRAND_PENDING_STATUS:
                return __("Thương hiệu đã được bạn đăng kí, nhân viên tư vấn sẽ liên hệ bạn trong thời gian sớm nhất");

            case self::BRAND_APPROVED_STATUS:
                return __("Thương hiệu đã được bạn đăng kí, liên hệ với nhân viên tư vấn nếu bạn chưa nhận được mã đăng nhập");

            case self::BRAND_NOT_EXITS:
                return __("Không tìm thấy thương hiêu, vui lòng đăng kí thương hiệu để sử dụng ứng dụng");

            case self::DECRYPT_PAYLOAD_FAILED:
                return __("Dữ liệu không hợp lệ");
            default:
                return null;
        }
    }
}