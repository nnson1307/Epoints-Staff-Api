<?php


namespace Modules\Order\Repositories\Order;


use MyCore\Repository\RepositoryExceptionAbstract;

class OrderRepoException extends RepositoryExceptionAbstract
{
    const GET_ORDER_LIST_FAILED = 0;
    const GET_ORDER_DETAIL_FAILED = 1;
    const GET_PRODUCT_LIST_FAILED = 2;
    const GET_SERVICE_CARD_LIST_FAILED = 3;
    const STORE_ORDER_FAILED = 4;
    const DETAIL_FAILED = 5;
    const GET_VOUCHER_FAILED = 6;
    const GET_ALL_PRODUCT_FAILED = 7;
    const GET_PRODUCT_DETAIL_FAILED = 8;
    const GET_DISCOUNT_MEMBER_FAILED = 9;
    const CANCEL_ORDER_FAILED = 10;
    const GET_PAYMENT_METHOD_FAILED = 11;
    const RE_ORDER_FAILED = 12;
    const CHECK_INVENTORY_FAILED = 13;
    const ORDER_PAYMENT_FAILED = 14;
    const PAYMENT_CANCEL_FAILED = 15;
    const CHECK_TRANSPORT_CHARGE_FAIL = 16;
    const CANCEL_TRANSACTION_FAIL = 17;
    const UPLOAD_IMAGE_FAILED = 18;
    const FILE_NOT_TYPE = 19;
    const MAX_FILE_SIZE = 20;
    const REMOVE_IMAGE_FAILED = 21;
    const UPDATE_FAILED = 22;
    const PAYMENT_FAILED = 23;
    const CREATE_QR_FAILED = 24;
    const GET_STATUS_PAYMENT_FAIL = 25;
    const GET_TRANSPORT_METHOD_FAILED = 26;
    const CHECK_GIFT_PROMOTION_CHANGE = 27;

    public function __construct(int $code = 0, string $message = "")
    {
        parent::__construct($message ? : $this->transMessage($code), $code);
    }

    protected function transMessage($code)
    {
        switch ($code)
        {
            case self::GET_ORDER_LIST_FAILED :
                return __('Lấy danh sách đơn hàng thất bại.');

            case self::GET_ORDER_DETAIL_FAILED :
                return __('Lấy chi tiết đơn hàng thất bại.');

            case self::GET_PRODUCT_LIST_FAILED :
                return __('Lấy danh sách sản phẩm thất bại.');

            case self::GET_SERVICE_CARD_LIST_FAILED :
                return __('Lấy danh sách thẻ dịch vụ thất bại.');

            case self::STORE_ORDER_FAILED :
                return __('Thêm đơn hàng thất bại.');

            case self::DETAIL_FAILED :
                return __('Vui lòng chọn dịch vụ/thẻ dịch vụ/sản phẩm.');

            case self::GET_VOUCHER_FAILED :
                return __('Lấy mã giảm giá thất bại.');

            case self::GET_ALL_PRODUCT_FAILED :
                return __('Lấy danh sách tất cả sản phẩm thất bại.');

            case self::GET_PRODUCT_DETAIL_FAILED :
                return __('Lấy chi tiết sản phẩm thất bại.');

            case self::GET_DISCOUNT_MEMBER_FAILED :
                return __('Lấy giảm giá thành viên thất bại.');

            case self::CANCEL_ORDER_FAILED :
                return __('Hủy đơn hàng thất bại.');

            case self::GET_PAYMENT_METHOD_FAILED :
                return __('Lấy hình thức thanh toán thất bại.');

            case self::RE_ORDER_FAILED :
                return __('Đặt hàng lại thất bại.');

            case self::CHECK_INVENTORY_FAILED :
                return __('Kiểm tra tồn kho thất bại.');

            case self::ORDER_PAYMENT_FAILED :
                return __('Thanh toán đơn hàng thất bại.');

            case self::PAYMENT_CANCEL_FAILED :
                return __('Hủy thanh toán đơn hàng thất bại.');

            case self::CHECK_TRANSPORT_CHARGE_FAIL :
                return __('Kiểm tra phí vận chuyển thất bại.');

            case self::CANCEL_TRANSACTION_FAIL :
                return __('Hủy transaction thanh toán thất bại.');

            case self::UPLOAD_IMAGE_FAILED :
                return __('Upload hình ảnh thất bại.');

            case self::FILE_NOT_TYPE :
                return __('Ảnh/file không đúng định dạng.');

            case self::MAX_FILE_SIZE :
                return __('FIle có kích thước quá lớn, vui lòng upload file có kích thước tối đa 20MB.');

            case self::REMOVE_IMAGE_FAILED :
                return __('Xoá hình ảnh thất bại.');

            case self::UPDATE_FAILED :
                return __('Chỉnh sửa đơn hàng thất bại.');

            case self::PAYMENT_FAILED :
                return __('Thanh toán đơn hàng thất bại.');

            case self::CREATE_QR_FAILED :
                return __('Tạo qr code thất bại.');

            case self::GET_STATUS_PAYMENT_FAIL :
                return __('Lấy trạng thái thanh toán thất bại.');

            case self::GET_TRANSPORT_METHOD_FAILED :
                return __('Lấy phương thức vận chuyển thất bại.');
            case self::CHECK_GIFT_PROMOTION_CHANGE :
                return  __('Quà tặng đã thay đổi');
            default:
                return null;
        }
    }
}