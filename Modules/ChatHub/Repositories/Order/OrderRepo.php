<?php

namespace Modules\ChatHub\Repositories\Order;

use Carbon\Carbon;
use Modules\ChatHub\Repositories\Order\OrderRepoInterface;
use Modules\ChatHub\Repositories\Order\OrderRepoException;
use Modules\ChatHub\Models\OrderTable;
use MyCore\Repository\PagingTrait;


class OrderRepo implements OrderRepoInterface
{
    use PagingTrait;
    protected $order;

    public function __construct(
        OrderTable $order
    )
    {
        $this->order = $order;
    }

    const LIVE = 1;
    const RECEIPT_ONLINE_SUCCESS = "success";
    
   /**
     * Lấy danh sách đơn hàng
     *
     * @param $input
     * @return mixed|void
     * @throws OrderRepoException
     */
    public function getOrders($input)
    {
        try {
            $customerId = Auth()->id();

            $data = $this->toPagingData($this->order->getOrders($input, $customerId));

            $dataItem = $data['Items'];

            if (count($dataItem) > 0) {
                foreach ($dataItem as $item) {
                    //Lấy status name của đơn hàng
                    $item['process_status_name'] = $this->setStatusName($item['process_status']);

                    $isRemove = 0;
                    $isCancel = 0;
                    $isEdit = 0;

                    if ($item['process_status'] == 'new') {
                        $isRemove = 1;
                    }

                    if (in_array($item['process_status'], ['new', 'confirmed'])) {
                        $isEdit = 1;
                    }

                    $dateNow = Carbon::now()->format('Y-m-d');
                    $dateCreated = Carbon::createFromFormat('Y-m-d H:i:s', $item['created_at'])->format('Y-m-d');

                    if (in_array($item['process_status'], ['paysuccess', 'pay-half']) && $dateNow == $dateCreated) {
                        $isCancel = 1;
                    }

                    $item['is_remove'] = $isRemove;
                    $item['is_cancel'] = $isCancel;
                    $item['is_edit'] = $isEdit;

                    
                }
            }

            return $data;
        } catch (\Exception $exception) {
            throw new OrderRepoException(OrderRepoException::GET_ORDER_LIST_FAILED, $exception->getMessage());
        }
    }

    /**
     * Lấy tên trạng thái đơn hàng
     *
     * @param $status
     * @return array|null|string
     */
    protected function setStatusName($status)
    {
        $statusName = null;

        switch ($status) {
            case "new":
                $statusName = __('Mới');
                break;
            case "confirmed":
                $statusName = __('Đã xác nhận');
                break;
            case "paysuccess":
                $statusName = __('Đã thanh toán');
                break;
            case "pay-half":
                $statusName = __('Thanh toán còn thiếu');
                break;
            case "ordercancle":
                $statusName = __('Đã huỷ');
                break;
        }

        return $statusName;
    }

}