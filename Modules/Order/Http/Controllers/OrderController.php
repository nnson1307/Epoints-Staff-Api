<?php


namespace Modules\Order\Http\Controllers;


use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Order\Http\Requests\Order\BranchRequest;
use Modules\Order\Http\Requests\Order\CancelRequest;
use Modules\Order\Http\Requests\Order\CancelTransactionRequest;
use Modules\Order\Http\Requests\Order\CheckInventoryRequest;
use Modules\Order\Http\Requests\Order\CheckTransportChargeRequest;
use Modules\Order\Http\Requests\Order\CreateQrCodeRequest;
use Modules\Order\Http\Requests\Order\DiscountMemberRequest;
use Modules\Order\Http\Requests\Order\GetPromotionGiftRequest;
use Modules\Order\Http\Requests\Order\GetStatusPaymentRequest;
use Modules\Order\Http\Requests\Order\GetTransportMethodRequest;
use Modules\Order\Http\Requests\Order\GetVoucherRequest;
use Modules\Order\Http\Requests\Order\OrderDetailRequest;
use Modules\Order\Http\Requests\Order\OrderListRequest;

use Modules\Order\Http\Requests\Order\OrderPaymentRequest;
use Modules\Order\Http\Requests\Order\PaymentCancelRequest;
use Modules\Order\Http\Requests\Order\ReOrderRequest;
use Modules\Order\Http\Requests\Order\StoreRequest;

use Modules\Order\Http\Requests\Order\UpdateRequest;
use Modules\Order\Http\Requests\Order\UploadImageRequest;
use Modules\Order\Repositories\Order\OrderRepoException;
use Modules\Order\Repositories\Order\OrderRepoInterface;

class OrderController extends Controller
{
    protected $order;

    public function __construct(
        OrderRepoInterface $order
    ) {
        $this->order = $order;
    }

    /**
     * Lấy Option chi nhánh
     *
     * @param BranchRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getOptionBranch(BranchRequest $request)
    {
        $data = $this->order->getOptionBranch($request->all());

        return $this->responseJson(CODE_SUCCESS, null, $data);
    }

    /**
     * Lấy danh sách đơn hàng
     *
     * @param OrderListRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getOrders(OrderListRequest $request)
    {
        try {
            $data = $this->order->getOrders($request->all());

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (OrderRepoException $ex) {
            return $this->responseJson(CODE_ERROR, $ex->getMessage());
        }
    }

    /**
     * Chi tiết đơn hàng
     *
     * @param OrderDetailRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getDetailOrder(OrderDetailRequest $request)
    {
        try {
            $lang = \request()->header('lang');
            $data = $this->order->getOrderDetail($request->order_id, $lang != null ? $lang : 'vi');

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (OrderRepoException $ex) {
            return $this->responseJson(CODE_ERROR, $ex->getMessage());
        }
    }

    /**
     * Thêm đơn hàng
     *
     * @param StoreRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreRequest $request)
    {
        try {
            $data = $this->order->store($request->all());

            return $this->responseJson(CODE_SUCCESS, __('Đặt hàng thành công'), $data);
        } catch (OrderRepoException $ex) {
            return $this->responseJson(CODE_ERROR, $ex->getMessage());
        }
    }

    /**
     * Thêm đơn hàng (V2)
     *
     * @param StoreRequest $request
     * @return JsonResponse
     */
    public function storeV2(StoreRequest $request)
    {
        try {
            $data = $this->order->storeV2($request->all());

            return $this->responseJson(CODE_SUCCESS, __('Đặt hàng thành công'), $data);
        } catch (OrderRepoException $ex) {
            return $this->responseJson(CODE_ERROR, $ex->getMessage());
        }
    }

    /**
     * Lấy giảm giá thành viên
     *
     * @param DiscountMemberRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getDiscountMember(DiscountMemberRequest $request)
    {
        try {
            $data = $this->order->getDiscountMember($request->all());

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (OrderRepoException $ex) {
            return $this->responseJson(CODE_ERROR, $ex->getMessage());
        }
    }

    /**
     * Hủy đơn hàng
     *
     * @param CancelRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function cancelAction(CancelRequest $request)
    {
        try {
            $data = $this->order->cancel($request->all());

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (OrderRepoException $ex) {
            return $this->responseJson(CODE_ERROR, $ex->getMessage());
        }
    }

    public function checkPromotionGift(GetPromotionGiftRequest $request)
    {
        try {
            $data = $this->order->checkPromotionGift($request->all());

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (OrderRepoException $ex) {
            return $this->responseJson(CODE_ERROR, $ex->getMessage());
        }
    }

    /**
     * Lấy hình thức thanh toán
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getPaymentMethod()
    {
        try {
            $lang = \request()->header('lang');

            $data = $this->order->getPaymentMethod($lang != null ? $lang : 'vi');

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (OrderRepoException $ex) {
            return $this->responseJson(CODE_ERROR, $ex->getMessage());
        }
    }

    /**
     * Đặt lại đơn hàng
     *
     * @param ReOrderRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function reOrder(ReOrderRequest $request)
    {
        try {
            $data = $this->order->reOrder($request->all());

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (OrderRepoException $ex) {
            return $this->responseJson(CODE_ERROR, $ex->getMessage());
        }
    }

    /**
     * Kiểm tra tồn kho - thay đổi giá - đã xóa
     *
     * @param CheckInventoryRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkInventory(CheckInventoryRequest $request)
    {
        try {
            $data = $this->order->checkInventory($request->all());

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (OrderRepoException $ex) {
            return $this->responseJson(CODE_ERROR, $ex->getMessage());
        }
    }

    /**
     * Thanh toán đơn hàng
     *
     * @param OrderPaymentRequest $request
     * @return \Illuminate\Http\JsonResponse`
     */
    public function orderPayment(OrderPaymentRequest $request)
    {
        try {
            $data = $this->order->orderPayment($request->all());

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (OrderRepoException $ex) {
            return $this->responseJson(CODE_ERROR, $ex->getMessage());
        }
    }

    /**
     * Hủy thanh toán
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function paymentCancel()
    {
        try {
            $data = $this->order->paymentCancel();

//            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (OrderRepoException $ex) {
            return $this->responseJson(CODE_ERROR, $ex->getMessage());
        }
    }

    /**
     * Thanh toán thành công
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function paymentSuccess()
    {
        try {
            $data = $this->order->paymentSuccess();

            if ($data['error'] == 1) {
                return redirect()->route($data['route'], ['order_id' => $data['order_id'], 'AccessCode' => $data['AccessCode']]);
            } else {
//                return $this->responseJson(CODE_SUCCESS, null, $data);
            }
        } catch (OrderRepoException $ex) {
            return $this->responseJson(CODE_ERROR, $ex->getMessage());
        }
    }

    /**
     * Kiểm tra phí vận chuyển
     *
     * @param CheckTransportChargeRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkTransportCharge(CheckTransportChargeRequest $request)
    {
        try {
            $data = $this->order->checkTransportCharge($request->all());

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (OrderRepoException $ex) {
            return $this->responseJson(CODE_ERROR, $ex->getMessage());
        }
    }

    /**
     * Thanh toán thất bại do thẻ ko đủ tiền thanh toán
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function paymentFail()
    {
        try {
            $data = $this->order->paymentFail();

//            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (OrderRepoException $ex) {
            return $this->responseJson(CODE_ERROR, $ex->getMessage());
        }
    }

    /**
     * Hủy transaction thanh toán
     *
     * @param CancelTransactionRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function cancelTransactionPayment(CancelTransactionRequest $request)
    {
        try {
            $data = $this->order->cancelTransaction($request->all());

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (OrderRepoException $ex) {
            return $this->responseJson(CODE_ERROR, $ex->getMessage());
        }
    }

    /**
     * Upload ảnh trước/sau khi sử dụng
     *
     * @param UploadImageRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function uploadImage(UploadImageRequest $request)
    {
        try {
            $data = $this->order->uploadImage($request->all());

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (OrderRepoException $ex) {
            return $this->responseJson(CODE_ERROR, $ex->getMessage());
        }
    }

    /**
     * Lấy mã giảm giá
     *
     * @param GetVoucherRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getVoucher(GetVoucherRequest $request)
    {
        try {
            $data = $this->order->getVoucher($request->all());

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (OrderRepoException $ex) {
            return $this->responseJson(CODE_ERROR, $ex->getMessage());
        }
    }

    /**
     * Xoá hình ảnh trước/sau khi sử dụng
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function removeImage(Request $request)
    {
        try {
            $data = $this->order->removeOrderImage($request->all());

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (OrderRepoException $ex) {
            return $this->responseJson(CODE_ERROR, $ex->getMessage());
        }
    }

    /**
     * Chỉnh sửa đơn hàng
     *
     * @param UpdateRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateRequest $request)
    {
        try {
            $data = $this->order->update($request->all());

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (OrderRepoException $ex) {
            return $this->responseJson(CODE_ERROR, $ex->getMessage());
        }
    }

    /**
     * Chỉnh sửa đơn hàng (V2)
     *
     * @param UpdateRequest $request
     * @return JsonResponse
     */
    public function updateV2(UpdateRequest $request)
    {
        try {
            $data = $this->order->updateV2($request->all());

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (OrderRepoException $ex) {
            return $this->responseJson(CODE_ERROR, $ex->getMessage());
        }
    }

    /**
     * Tạo qr code thanh toán vn pay
     *
     * @param CreateQrCodeRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function createQrCodeVnPay(CreateQrCodeRequest $request)
    {
        try {
            $data = $this->order->createQrCodeVnPay($request->all());

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (OrderRepoException $ex) {
            return $this->responseJson(CODE_ERROR, $ex->getMessage());
        }
    }

    /**
     * Thanh toán online thành công
     *
     * @param GetStatusPaymentRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getStatusVnPay(GetStatusPaymentRequest $request)
    {
        try {
            $data = $this->order->getStatusVnPay($request->all());

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (OrderRepoException $ex) {
            return $this->responseJson(CODE_ERROR, $ex->getMessage());
        }
    }

    /**
     * Lấy phương thức vận chuyển
     *
     * @param GetTransportMethodRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getTransportMethod(GetTransportMethodRequest $request)
    {
        try {
            $data = $this->order->getTransportMethod($request->all());

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (OrderRepoException $ex) {
            return $this->responseJson(CODE_ERROR, $ex->getMessage());
        }
    }

    /**
     * Lấy template in hóa đơn
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getPrintBillAction(Request $request)
    {
        try {
            $data = $this->order->getPrintBillTemplate($request->all());

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (OrderRepoException $ex) {
            return $this->responseJson(CODE_ERROR, $ex->getMessage());
        }
    }

    /**
     * Lấy danh sách máy in
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getPrintBillDeviceAction(Request $request)
    {
        try {
            $data = $this->order->getPrintBillDevices($request->all());

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (OrderRepoException $ex) {
            return $this->responseJson(CODE_ERROR, $ex->getMessage());
        }
    }
}