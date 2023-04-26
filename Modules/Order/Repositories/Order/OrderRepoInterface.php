<?php


namespace Modules\Order\Repositories\Order;


interface OrderRepoInterface
{
    /**
     * Lấy Option Chi nhánh
     *
     * @param $input
     * @return mixed
     */
    public function getOptionBranch($input);

    /**
     * Lấy danh sách đơn hàng
     *
     * @param $input
     * @return mixed
     */
    public function getOrders($input);

    /**
     * Chi tiết đơn hàng
     *
     * @param $orderId
     * @param $lang
     * @return mixed
     */
    public function getOrderDetail($orderId, $lang);

    /**
     * Thêm đơn hàng
     *
     * @param $input
     * @return mixed
     */
    public function store($input);

    /**
     * Thêm đơn hàng (V2)
     *
     * @param $input
     * @return mixed
     */
    public function storeV2($input);

    /**
     * Lấy giảm giá thành viên
     *
     * @param $input
     * @return mixed
     */
    public function getDiscountMember($input);

    /**
     * Hủy đơn hàng
     *
     * @param $input
     * @return mixed
     */
    public function cancel($input);

    /**
     * Check qua tang khi len don hang
     *
     * @param $input
     * @return mixed
     */
    public function checkPromotionGift($input);

    /**
     * Lấy hình thức thanh toán
     *
     * @param $lang
     * @return mixed
     */
    public function getPaymentMethod($lang);

    /**
     * Đặt hàng lại
     *
     * @param $input
     * @return mixed
     */
    public function reOrder($input);

    /**
     * Kiểm tra tồn kho - thay đổi giá - đã xóa
     *
     * @param $input
     * @return mixed
     */
    public function checkInventory($input);

    /**
     * Thanh toán đơn hàng
     *
     * @param $input
     * @return mixed
     */
    public function orderPayment($input);

    /**
     * Hủy thanh toán
     *
     * @return mixed
     */
    public function paymentCancel();

    /**
     * Thanh toán thành công
     *
     * @return mixed
     */
    public function paymentSuccess();

    /**
     * Kiểm tra phí vận chuyển
     *
     * @param $input
     * @return mixed
     */
    public function checkTransportCharge($input);

    /**
     * Thanh toán thất bại do thẻ ko đủ tiền thanh toán
     *
     * @return mixed
     */
    public function paymentFail();

    /**
     * Hủy transaction thanh toán
     *
     * @param $input
     * @return mixed
     */
    public function cancelTransaction($input);

    /**
     * Upload ảnh trước/sau khi sử dụng
     *
     * @param $input
     * @return mixed
     */
    public function uploadImage($input);

    /**
     * Lấy mã giảm giá
     *
     * @param $input
     * @return mixed
     */
    public function getVoucher($input);

    /**
     * Xoá hình ảnh trước/sau khi sử dụng
     *
     * @param $input
     * @return mixed
     */
    public function removeOrderImage($input);

    /**
     * Chỉnh sửa đơn hàng
     *
     * @param $input
     * @return mixed
     */
    public function update($input);

    /**
     * Chỉnh sửa đơn hàng (V2)
     *
     * @param $input
     * @return mixed
     */
    public function updateV2($input);

    /**
     * Tạo qr code thanh toán vn pay
     *
     * @param $input
     * @return mixed
     */
    public function createQrCodeVnPay($input);

    /**
     * Thanh toán thành công
     *
     * @param $input
     * @return mixed
     */
    public function getStatusVnPay($input);

    /**
     * Lấy phương thức vận chuyển
     *
     * @param $input
     * @return mixed
     */
    public function getTransportMethod($input);

    /**
     * Lấy template in hóa đơn
     * @param array $all
     * @return mixed
     */
    public function getPrintBillTemplate(array $all);

    /**
     * Lấy danh sách máy in
     * @param array $all
     * @return mixed
     */
    public function getPrintBillDevices(array $all);
}