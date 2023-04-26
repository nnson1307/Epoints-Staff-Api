<?php

namespace Modules\CustomerLead\Repositories\CustomerDeals;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Modules\CustomerLead\Models\ManageCommentTable;
use Modules\CustomerLead\Models\ManageDocumentFileTable;
use Modules\CustomerLead\Models\ManageWorkTable;
use Modules\CustomerLead\Models\ManageWorkTagTable;
use Modules\CustomerLead\Models\OrderDetailTable;
use Modules\CustomerLead\Models\TagTable;
use MyCore\Repository\PagingTrait;
use Modules\CustomerLead\Models\CustomerDealsTable;
use Modules\CustomerLead\Models\DealDetailTable;
use Modules\CustomerLead\Models\CustomerLeadTable;
use Modules\CustomerLead\Models\CustomerTable;
use Modules\CustomerLead\Models\OrderTable;
use Modules\CustomerLead\Models\CustomerLeadJourneyTable;
use Modules\CustomerLead\Models\ManageDealCommentTable;
use Modules\CustomerLead\Models\DealsCommentTable;


class CustomerDealRepo implements CustomerDealsRepoInterface
{
    use PagingTrait;

    protected $deal;

    public function __construct(
        CustomerDealsTable $deal
    ) {
        $this->deal = $deal;
    }

    // them co hoi ban hang
    public function createdDeal($params)
    {
        try {
            $mAddDeal = app()->get(CustomerDealsTable::class);
            $mDealDetail = app()->get(DealDetailTable::class);

            unset($params['brand_code']);
            $dataDeal = [
                'type_customer' => $params['type_customer'],
                'customer_code' => $params['customer_code'],
                'deal_name' => $params['deal_name'],
                'pipeline_code' => $params['pipeline_code'],
                'journey_code' => $params['journey_code'],
                'sale_id' => $params['sale_id'],
                'phone' => $params['phone'],
                'amount' => $params['amount'] ?? null,
                'closing_date' => $params['closing_date'] ?? null,
                'branch_code' => $params['branch_code'] ?? null,
                'tag' => isset($params['tag']) != '' ? implode(',', $params['tag']) : null,
                'order_source_id' => $params['order_source_id'] ?? null,
                'probability' => $params['probability'] ?? null,
                'deal_description' => $params['deal_description'] ?? null,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'created_by' => Auth()->id(),
                'date_last_care' => Carbon::now()
            ];
            //Thêm deal
            $id = $mAddDeal->createdDeal($dataDeal);
            //            Update deal_code
            $dealCode = "DEALS_" . date("dmY") . sprintf("%02d", $id);
            $mAddDeal->updateDealCode([
                "deal_code" => $dealCode
            ], $id);

            if (isset($params['product']) && count($params['product']) > 0) {
                foreach ($params['product'] as $key => $value) {
                    $mDealDetail->createdDetailDeal([
                        'object_type' => $value['object_type'],
                        'object_name' => $value['object_name'],
                        'object_code' => $value['object_code'],
                        'object_id' => $value['object_id'],
                        'quantity' => $value['quantity'],
                        'price' => $value['price'],
                        'amount' => $value['amount'],
                        'discount' => 0,
                        'deal_code' => $dealCode,
                        'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                        'created_by' => Auth()->id(),
                    ]);
                }
            }
            return [
                'deal_id' => $id,
            ];
        } catch (\Exception $exception) {
            throw new CustomerDealsRepoException(CustomerDealsRepoException::ADD_DEAL);
        }
    }

    //lay danh sach deal
    public function getDataDeal($input)
    {
        try {
            $mLead = app()->get(CustomerLeadTable::class);
            $mCustomer = app()->get(CustomerTable::class);
            $mWork = app()->get(ManageWorkTable::class);
            $mTag = app()->get(TagTable::class);

            //Lấy ds deal
            $data = $this->deal->getDataDeal($input);
            if (count($data->items()) > 0) {
                foreach ($data->items() as $v) {
                    ///lấy info tag
                    $dataTag = [];

                    if ($v['tag'] != null) {
                        $tag = explode(',', $v['tag']);

                        if (count($tag) > 0) {
                            foreach ($tag as $a) {
                                $dataTag [] = intval($a);
                            }
                        }
                    }
                    ///lây thông tin tag
                    unset($v['tag']);
                    $infoTag = $mTag->getInfoTag($dataTag);
                    if(isset($infoTag) && $infoTag != [] && $infoTag != null){
                        $v['tag'] = $infoTag;
                    }else{
                        $v['tag'] = [];
                    }

                    $customerName = null;
                    if ($v['type_customer'] == "customer") {
                        //Lay thong tin KH
                        $infoLead = $mCustomer->getInfoByCode($v['customer_code']);

                        if ($infoLead != null) {
                            $customerName = $infoLead['full_name'];
                        }

                    } else if ($v['type_customer'] == "lead") {
                        //Lay thong tin KHTN
                        $infoLead = $mLead->getInfoByCode($v['customer_code']);

                        if ($infoLead != null) {
                            $customerName = $infoLead['full_name'];
                        }
                    }

                    $v['customer_name'] = $customerName;
                    ///ngày chăm sóc gần nhất
                    $now = Carbon::parse(now())->format('Y-m-d');
                   ///lấy ngày chăm sóc gần nhất của deal
                    $v['diff_day'] = null;
                    if(isset($v['date_last_care']) && $v['date_last_care'] != null){
                       $dateNow = Carbon::parse($now);
                       $dateLastCareDeal = Carbon::parse($v['date_last_care']);
                       $diffDeal = $dateLastCareDeal->diffInDays($dateNow);
                       $v['diff_day'] = $diffDeal;
                    }
                    ///số công việc liên quan
                    $numberOfWork = $mWork->getWorkDeal($v['deal_id']);
                    $v['related_work'] = count($numberOfWork);

                    ///số lịch hẹn(công việc loại là họp)
                    $numberOfAppointment = $mWork ->getNumberOfAppointmentDeal($v['deal_id']);
                    $v['appointment '] = count($numberOfAppointment);
                }
            }
            return $this->toPagingData($data);
        } catch (\Exception $exception) {
            throw new CustomerDealsRepoException(CustomerDealsRepoException::GET_LIST_DEAL, $exception->getMessage());
        }
    }

    //lay chi tiet deal
    public function getDetail($input)
    {
        try {
            $mLead = app()->get(CustomerLeadTable::class);
            $mCustomer = app()->get(CustomerTable::class);
            $mJourney = app()->get(CustomerLeadJourneyTable::class);
            $mDealDetail = app()->get(DealDetailTable::class);
            $mWork = app()->get(ManageWorkTable::class);
            $mTag = app()->get(TagTable::class);
//            $file = app()->get(ManageDocumentFileTable::class);
//            $mWorkTag = app()->get(ManageWorkTagTable::class);
//            $workComment = app()->get(ManageCommentTable::class);

            //Lấy thông tin deal
            $dataInfo = $this->deal->getDetail($input);
            if ($dataInfo == null) {
                throw new CustomerDealsRepoException(CustomerDealsRepoException::GET_DETAIL);
            }

            $dataTag = [];

            if ($dataInfo['tag'] != null) {
                $tag = explode(',', $dataInfo['tag']);

                if (count($tag) > 0) {
                    foreach ($tag as $v) {
                        $dataTag [] = intval($v);
                    }
                }
            }
           ///lây thông tin tag
            $infoTag = $mTag->getInfoTag($dataTag);
            if(isset($infoTag) && $infoTag != [] && $infoTag != null){
                $dataInfo['tag'] = $infoTag;
            }else{
                $dataInfo['tag'] = [];
            }

            $journeyTracking = [];
            $customerName = null;
            $customerAvatar = null;
            $customerSourceName = null;
            $customerEmail = null;
            $customerGender = null;
            $province = null;
            $district = null;
            $ward = null;
            $address = null;
            $business_clue = null;
            $fanpage = null;
            $zalo = null;
            if ($dataInfo['type_customer'] == "customer") {
                //Lay thong tin KH
                $infoLead = $mCustomer->getInfoByCode($dataInfo['customer_code']);

                if ($infoLead != null) {
                    $customerName = $infoLead['full_name'];
                    $customerAvatar = $infoLead['customer_avatar'];
                    $customerSourceName = $infoLead['customer_source_name'];
                    $customerEmail = $infoLead['email'];
                    $customerGender = $infoLead['gender'];
                    $province = $infoLead['province_name'];
                    $district = $infoLead['district_name'];
                    $ward = $infoLead['ward_name'];
                    $address = $infoLead['address'];
                    if ($infoLead['business_clue']) {
                        $business_clue = $infoLead['business_clue'];
                    }
                    if ($infoLead['fanpage']) {
                        $fanpage = $infoLead['fanpage'];
                    }
                    $zalo = $infoLead['zalo'];
                }
            } else if ($dataInfo['type_customer'] == "lead") {
                //Lay thong tin KHTN
                $infoLead = $mLead->getInfoByCode($dataInfo['customer_code']);
                if ($infoLead != null) {
                    $customerName = $infoLead['full_name'];
                    $customerAvatar = $infoLead['avatar'];
                    $customerSourceName = $infoLead['customer_source_name'];
                    $customerEmail = $infoLead['email'];
                    $customerGender = $infoLead['gender'];
                    $province = $infoLead['province_name'];
                    $district = $infoLead['district_name'];
                    $ward = $infoLead['ward_name'];
                    $address = $infoLead['address'];
                    $business_clue = $infoLead['business_clue'];
                    $fanpage = $infoLead['fanpage'];
                    $zalo = $infoLead['zalo'];
                }
            }

            if ($customerName && $customerName != null) {
                $dataInfo['customer_name'] = $customerName;
            }
            if ($customerAvatar && $customerAvatar != null) {
                $dataInfo['customer_avatar'] = $customerAvatar;
            }
            if ($customerSourceName && $customerSourceName != null) {
                $dataInfo['customer_source_name'] = $customerSourceName;
            }
            if ($customerEmail && $customerEmail != null) {
                $dataInfo['customer_email'] = $customerEmail;
            }
            if ($customerGender && $customerGender != null) {
                $dataInfo['customer_gender'] = $customerGender;
            }
            $dataInfo['province'] = $province;
            if ($district && $district != null) {
                $dataInfo['district'] = $district;
            }
            if ($ward && $ward != null) {
                $dataInfo['ward'] = $ward;
            }
            if ($address && $address != null) {
                $dataInfo['address'] = $address;
            }
            if ($business_clue && $business_clue != null) {
                $dataInfo['business_clue'] = $business_clue;
            }
            if ($fanpage && $fanpage != null) {
                $dataInfo['fanpage'] = $fanpage;
            }
            if ($zalo && $zalo != null) {
                $dataInfo['zalo'] = $zalo;
            }
            $now = Carbon::parse(now())->format('Y-m-d');
            ///lấy ngày chăm sóc gần nhất của deal
            $dataInfo['diff_day'] = null;
            if(isset($dataInfo['date_last_care']) && $dataInfo['date_last_care'] != null){
                $dateNow = Carbon::parse($now);
                $dateLastCareDeal = Carbon::parse($dataInfo['date_last_care']);
                $diffDeal = $dateLastCareDeal->diffInDays($dateNow);
                $dataInfo['diff_day'] = $diffDeal + 1;
            }

            ///số công việc liên quan
            $numberOfWork = $mWork->getWorkDeal($dataInfo['deal_id']);
            $dataInfo['related_work'] = count($numberOfWork);
            ///số lịch hẹn(công việc loại là họp)
            $numberOfAppointment = $mWork ->getNumberOfAppointmentDeal($dataInfo['deal_id']);
            $dataInfo['appointment'] = count($numberOfAppointment);

            //Lấy ds hành trình
            $listJourney = $mJourney->getDataJourneyByCode($dataInfo['pipeline_code']);

            if (count($listJourney) > 0) {
                $keyJourneyCurrent = null;

                foreach ($listJourney as $key => $value) {

                    if ($dataInfo['journey_code'] == $value['journey_code']) {
                        $keyJourneyCurrent = $key;
                    }
                }
                foreach ($listJourney as $key => $value) {
                    $value['check'] = false;

                    if ($key <= $keyJourneyCurrent) {
                        $value['check'] = true;
                    }
                    $journeyTracking[] = $value;
                }
            }

            $dataInfo['journey_tracking'] = $journeyTracking;
            //Lay sp mua
            $getDetailInfo = $mDealDetail->getDetailByDeal($dataInfo['deal_code']);

            //Lấy thông tin sp,dv,thẻ dv của deal
            $dataInfo['product_buy'] = $getDetailInfo;

            $productNameBuy = '';

            if (count($getDetailInfo) > 0) {
                foreach ($getDetailInfo as $k => $v) {
                    $space = $k + 1 < count($getDetailInfo) ? ', ' : '';

                    $productNameBuy .= $v['object_name'] . $space;
                }
            }

            $dataInfo['product_name_buy'] = $productNameBuy;
            return $dataInfo;
        } catch (\Exception $exception) {
            throw new CustomerDealsRepoException(CustomerDealsRepoException::GET_DETAIL, $exception->getMessage());
        }
    }
    ///lịch sử đơn hàng của deal
    public function getOrderHistory($input)
    {
        try {
            $mOrder = app()->get(OrderTable::class);
            $mOrderDetail = app()->get(OrderDetailTable::class);

            $lisOrder = $mOrder->getListOrderByDealCode($input);
            if (isset($lisOrder)) {
                foreach ($lisOrder as $k => $v) {
                    if ($v['process_status'] == 'pay-half') {
                        $v['process_status_name'] = 'Đã thanh toán một nửa';
                    } else if ($v['process_status'] == 'paysuccess') {
                        $v['process_status_name'] = 'Đã thanh toán';
                    } else {
                        $v['process_status_name'] = 'Chưa thanh toán';
                    }
                    ///số sản phẩm theo order_id
                    $prod = $mOrderDetail->getListOrder($v['order_id']);
                    $v['count_prod'] = isset($prod) ? count($prod) : 0 ;
                }
            }

            return $lisOrder;
        } catch (\Exception $exception) {
            throw new CustomerDealsRepoException(CustomerDealsRepoException::ORDER_HISTORY);
        }
    }
    public function getCareDeal($input){
        try {

            $mWork = app()->get(ManageWorkTable::class);
            $file = app()->get(ManageDocumentFileTable::class);
            $mWorkTag = app()->get(ManageWorkTagTable::class);
            $workComment = app()->get(ManageCommentTable::class);
            /////chăm sóc khách hàng
//            $careDeals = [];
            $type = 'deal';
            $now = Carbon::now();
            $care = $mWork ->getCare($input['deal_id'],$type);
            foreach ($care as $k => $v){
                //lấy tag cv
                $tagWork = $mWorkTag->getTagWork($v['manage_work_id']);
                $v['list_tag'] = $tagWork ?? [];
                ///lấy số lượng file liên quan tới công việc
                $getFile = $file->getFile($v['manage_work_id']);
                $v['count_file'] = count($getFile);
                ///số commnent
                $getComment = $workComment->getComment($v['manage_work_id']);
                $v['count_comment'] = count($getComment);
                /// số ngày trễ hạn
                $dateEnd = Carbon::parse($v['date_end']);
                if(isset($v['date_finish']) && $v['date_finish'] != null){
                    $dateFinish = Carbon::parse($v['date_finish']);
                }else{
                    $dateFinish = Carbon::parse($now);
                }
                $diff = $dateEnd->diffInDays($dateFinish);
                $v['days_late'] = $diff;
            }
//            $careDeals['customer_care'] = $care;
//            ///lịch sử chăm sóc
//            $careHistory = $mWork ->getCareHistory($input['deal_id'],$type);
//
//            foreach ($careHistory as $k => $v){
//                //lấy tag cv
//                $tagWork = $mWorkTag->getTagWork($v['manage_work_id']);
//                $v['list_tag'] = $tagWork ?? [];
//                ///lấy số lượng file liên quan tới công việc
//                $getFile = $file->getFile($v['manage_work_id']);
//                $v['count_file'] = count($getFile);
//                ///số commnent
//                $getComment = $workComment->getComment($v['manage_work_id']);
//                $v['count_comment'] = count($getComment);
//                /// số ngày trễ hạn
//                $dateEnd = Carbon::parse($v['date_end']);
//                if(isset($v['date_finish']) && $v['date_finish'] != null){
//                    $dateFinish = Carbon::parse($v['date_finish']);
//                }else{
//                    $dateFinish = Carbon::parse($now);
//                }
//                $diff = $dateEnd->diffInDays($dateFinish);
//                $v['days_late'] = $diff;
//            }
//            $careDeals['care_history'] = $careHistory;
            return $care;
        } catch (\Exception $exception) {
            throw new CustomerDealsRepoException(CustomerDealsRepoException::CARE_DEALS);
        }
    }
    ///tạo message deal
    public function createMessageDeal($data)
    {
        try {
            $mMessage = app()->get(ManageDealCommentTable::class);

            $createdComment = [
                'deal_id' => $data['deal_id'],
                'parent_deal_comment_id' => isset($data['parent_customer_lead_comment_id']) ? $data['parent_customer_lead_comment_id'] : null,
                'staff_id' => Auth()->id(),
                'message' => isset($data['message']) ? $data['message'] : null,
                'path' => isset($data['path']) ? $data['path'] : null,
                'created_at' => Carbon::now(),
                'created_by' => Auth()->id(),
                'updated_at' => Carbon::now(),
                'updated_by' => Auth()->id()
            ];
            $createdNewComment = $mMessage->createdComment($createdComment);
            return  $createdNewComment;
        } catch (\Exception $exception) {
            throw new CustomerDealsRepoException(CustomerDealsRepoException::CREATED_COMMENT);
        }
    }
    ///danh sách message deal
    public function getListMessageDeal($input){
        try{
            $mMessage = app()->get(ManageDealCommentTable::class);
            $list = $mMessage ->getComment($input['deal_id']);
            return  $list;
        }catch (\Exception $exception){
            throw new CustomerDealsRepoException(CustomerDealsRepoException::LIST_COMMENT_DEAL);
        }
    }
    ///xóa message deal
    public function deleteMessageDeal($input){
        try{
            $mMessage = app()->get(ManageDealCommentTable::class);
            $delete = $mMessage ->deleteMessageDeal($input['deal_comment_id']);
            return  $delete;
        }catch (\Exception $exception){
            throw new CustomerDealsRepoException(CustomerDealsRepoException::LIST_COMMENT_DEAL);
        }
    }

    /**update deal
     * @inheritDoc
     */
    public function actionUpdate($input)
    {
        try {
            $mUpdateDealDetail = app()->get(DealDetailTable::class);

            $dataUpdate = [
                'type_customer' => $input['type_customer'],
                'customer_code' => $input['customer_code'],
                'deal_name' => $input['deal_name'],
                'pipeline_code' => $input['pipeline_code'],
                'journey_code' => $input['journey_code'],
                'sale_id' => $input['sale_id'],
                'phone' => $input['phone'],
                'amount' => $input['amount'] ?? null,
                'closing_date' => $input['closing_date'] ?? null,
                'branch_code' => $input['branch_code'] ?? null,
                'tag' => isset($input['tag']) != '' ? implode(',', $input['tag']) : null,
                'order_source_id' => $input['order_source_id'] ?? null,
                'probability' => $input['probability'] ?? null,
                'deal_description' => $input['deal_description'] ?? null,
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_by' => Auth()->id(),
            ];

            if (isset ($input['deal_description'])) {
                $dataUpdate['deal_description'] = $input['deal_description'];
            }
            if (isset ($input['order_source_id'])) {
                $dataUpdate['order_source_id'] = $input['order_source_id'];
            }
            if (isset ($input['probability'])) {
                $dataUpdate['probability'] = $input['probability'];
            }
            $dealCode = $input['deal_code'];
            //Xoá chi tiết deal
            $mUpdateDealDetail->deleteDetail($input);

            if (isset($input['product']) && count($input['product']) > 0) {
                foreach ($input['product'] as $key => $value) {
                    $dataUpdateDetail = [
                        'object_type' => $value['object_type'],
                        'object_name' => $value['object_name'],
                        'object_code' => $value['object_code'],
                        'object_id' => $value['object_id'],
                        'quantity' => $value['quantity'],
                        'price' => $value['price'],
                        'amount' => $value['amount'],
                        'discount' => 0,
                        'deal_code' => $dealCode,
//                        'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                        'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
//                        'created_by' => Auth()->id(),
                        'updated_by' => Auth()->id(),
                    ];
                    //Thêm chi tiết deal
                    $mUpdateDealDetail->createdDetailDeal($dataUpdateDetail);
                }
            }

            //Cập nhật deal
            $data = $this->deal->actionUpdate($dataUpdate, $dealCode);

            return $data;
        } catch (\Exception $exception) {
            throw new CustomerDealsRepoException(CustomerDealsRepoException::UPDATE_DEALS);
        }
    }

    //delete lead
    public function actionDelete($input)
    {
        try {
            $mDeleteDetail = app()->get(DealDetailTable::class);

            $data = $this->deal->actionDelete($input);
            $deleteDetail = $mDeleteDetail->deleteDetail($input);

        } catch (\Exception $exception) {
            throw new CustomerDealsRepoException(CustomerDealsRepoException::DELETE_DEALS);
        }
    }

    /**
     * Phân bổ hoặc thu hồi deal
     *
     * @param $input
     * @return mixed|void
     * @throws CustomerDealsRepoException
     */
    public function assignRevoke($input)
    {
        try {
            switch ($input['type']) {
                case 'assign':
                    //Phân bổ
                    $this->deal->actionUpdate([
                        'sale_id' => $input['sale_id'],
                        'date_revoke' => Carbon::now()->addDays(intval($input['time_revoke_lead']))->format('Y-m-d H:i:s'),
                    ], $input['deal_code']);

                    break;
                case 'revoke':
                    //Thu hồi
                    $this->deal->actionUpdate([
                        'sale_id' => null,
                        'date_revoke' => null,
                    ], $input['deal_code']);

                    break;
            }
        } catch (\Exception $e) {
            throw new CustomerDealsRepoException(CustomerDealsRepoException::ASSIGN_REVOKE_LEAD_FAILED, $e->getMessage());
        }
    }

    /**
     * Danh sách bình luận
     * @param $data
     * @return mixed|void
     */
    public function listComment($data)
    {
        try {

            $mManageComment = new DealsCommentTable();

            $listComment = $mManageComment->getListComment($data['deal_id']);
            if (count($listComment) != 0) {
                foreach ($listComment as $key => $item) {
                    $listComment[$key]['list_object'] = $mManageComment->getListComment($item['deal_id'], $item['deal_comment_id']);
                }
            }

            return $listComment;
        } catch (\Exception $exception) {
            throw new CustomerDealsRepoException(CustomerDealsRepoException::GET_DATA_FAILED, $exception->getMessage() . $exception->getLine());
        }
    }

    /**
     * Tạo comment
     * @param $data
     * @return mixed|void
     */
    public function createdComment($data)
    {
        try {
            $mCustomerComment = new DealsCommentTable();

            $createdComment = [
                'deal_id' => $data['deal_id'],
                'deal_parent_comment_id' => isset($data['deal_parent_comment_id']) ? $data['deal_parent_comment_id'] : null,
                'staff_id' => Auth::id(),
                'message' => isset($data['message']) ? $data['message'] : null,
                'path' => isset($data['path']) ? $data['path'] : null,
                'created_at' => Carbon::now(),
                'created_by' => Auth::id(),
                'updated_at' => Carbon::now(),
                'updated_by' => Auth::id()
            ];

            //Thêm bình luận
            $idComment = $mCustomerComment->createdComment($createdComment);

            // $detailComment = $mCustomerComment->getDetail($idComment);

            //Gửi notify bình luận ticket
            // $listCustomer = $this->getListStaff($data['ticket_id']);

            // foreach ($listCustomer as $item) {
            //     if ($item != Auth()->id()) {
            //         \App\Jobs\FunctionSendNotify::dispatch([
            //             'type' => SEND_NOTIFY_STAFF,
            //             'key' => 'ticket_comment_new',
            //             'customer_id' => Auth()->id(),
            //             'object_id' => $data['ticket_id'],
            //             'branch_id' => Auth()->user()->branch_id,
            //             'tenant_id' => session()->get('idTenant')
            //         ]);
            //     }
            // }
            return $this->listComment($data);
        } catch (\Exception $exception) {
            throw new CustomerDealsRepoException(CustomerDealsRepoException::GET_DATA_FAILED, $exception->getMessage() . $exception->getLine());
        }
    }
}