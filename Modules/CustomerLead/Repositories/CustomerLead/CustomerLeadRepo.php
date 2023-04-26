<?php

namespace Modules\CustomerLead\Repositories\CustomerLead;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\CustomerLead\Models\CustomerLeadDistrictTable;
use Modules\CustomerLead\Models\CustomerLeadJourneyTable;
use Modules\CustomerLead\Models\CustomerLeadTable;
use Modules\CustomerLead\Models\CustomerLeadSourceTable;
use Modules\CustomerLead\Models\CustomerLeadPipelineTable;
use Modules\CustomerLead\Models\CustomerLeadProvinceTable;
use Modules\CustomerLead\Models\CustomerLeadAllocateTable;
use Modules\CustomerLead\Models\DealNameTable;
use Modules\CustomerLead\Models\BranchTable;
use Modules\CustomerLead\Models\CustomerTable;
use Modules\CustomerLead\Models\BusinessTable;
use Modules\CustomerLead\Models\ManageStatusTable;
use Modules\CustomerLead\Models\OrderSourceTable;
use Modules\CustomerLead\Models\CustomerLeadWardTable;
use Modules\CustomerLead\Models\TagTable;
use Modules\CustomerLead\Models\ManageWorkTable;
use Modules\CustomerLead\Models\CustomerContactTable;
use Modules\CustomerLead\Models\CustomerDealsTable;
use Modules\CustomerLead\Models\ManageDocumentFileTable;
use Modules\CustomerLead\Models\ManageLeadCommentTable;
use Modules\CustomerLead\Models\ManageTypeWorkTable;
use Modules\CustomerLead\Models\ManageWorkTagTable;
use Modules\CustomerLead\Models\CustomerLeadCommentTable;
use Modules\CustomerLead\Models\ManageCommentTable;
use Modules\CustomerLead\Models\StaffTitleTable;
use MyCore\Repository\PagingTrait;


class CustomerLeadRepo implements CustomerLeadRepoInterface
{
    use PagingTrait;

    //loai khach hang va nguon khach hang
    public function getOption()
    {
        $mOption = app()->get(CustomerLeadSourceTable::class);
        $data = $mOption->getOption();

        $CustomerType = [
            'personal' => 'Personal',
            'business' => 'Business'
        ];
        return [
            'Customer_type' => $CustomerType,
            'Source' => $data
        ];
    }

    //lay pipeline
    public function getPipe($input)
    {
        $mPipeline = app()->get(CustomerLeadPipelineTable::class);
        $data = $mPipeline->getPipe($input);
        return $data;
    }

    ///lay hanh trinh
    public function getDataJourney($input)
    {
        $mJourney = app()->get(CustomerLeadJourneyTable::class);
        $data = $mJourney->getDataJourney($input['pipeline_code']);
        return $data;
    }

    //lay tinh thanh
    public function getDataProvince()
    {
        try {
            $mProvince = app()->get(CustomerLeadProvinceTable::class);
            $data = $mProvince->getDataProvince();
            return $data;
        } catch (\Exception $exception) {
            throw new CustomerLeadRepoException(CustomerLeadRepoException::GET_PROVINCE);
        }
    }

    //lay quan huyen
    public function getDataDistrict($input)
    {

        try {
            $mDistrict = app()->get(CustomerLeadDistrictTable::class);
            $data = $mDistrict->getDataDistrict($input['provinceid']);
            return $data;
        } catch (\Exception $exception) {
            throw new CustomerLeadRepoException(CustomerLeadRepoException::GET_DISTRICT);
        }
    }

    //lay phuong xa
    public function getDataWard($input)
    {
        try {
            $mWard = app()->get(CustomerLeadWardTable::class);
            $data = $mWard->getDataWard($input['districtid']);
            return $data;
        } catch (\Exception $exception) {
            throw new CustomerLeadRepoException(CustomerLeadRepoException::GET_WARD);
        }
    }
    ///danh sách lĩnh vực kinh doanh
    public function getListBusinessAreas()
    {
        try {
            $mList = app()->get(BusinessTable::class);
            $data = $mList->getList();
            return $data;
        } catch (\Exception $exception) {
            throw new CustomerLeadRepoException(CustomerLeadRepoException::GET_BUSINESS_AREAS);
        }
    }
    ///thêm lĩnh vực kinh doanh
    public function addBusinessAreas($params){
        try{
            $mAdd = app()->get(BusinessTable::class);
            $data =[
                "name" => $params['name'],
                "is_actived" => 1,
                "is_deleted" => 0,
                "description" => $params['description'] ?? null,
                "created_at" => Carbon::now(),
                "created_by" => Auth()->id(),
            ];
            $add = $mAdd -> addBusinessAreas($data);
            return $add;
        }catch(\Exception $exception){
            throw new CustomerLeadRepoException(CustomerLeadRepoException::ADD_BUSINESS_AREAS);
        }
    }

    public function getDataAllocator()
    {
        try {
            $mAllocator = app()->get(CustomerLeadAllocateTable::class);
            $data = $mAllocator->getDataAllocator();
            return $data;
        } catch (\Exception $exception) {
            throw new CustomerLeadRepoException(CustomerLeadRepoException::GET_ALLOCATOR);
        }
    }
    ///them KHTN
    public function createdCustomerLead($params)
    {

        try {
            if($params['customer_type'] == 'business'){
                if(empty($params['contact_full_name']) || $params['contact_full_name'] == '' || $params['contact_full_name'] == null){
                    throw new CustomerLeadRepoException(CustomerLeadRepoException::DEFICIENCY_CONTACT_NAME);
                }elseif(empty($params['contact_phone']) || $params['contact_phone'] == '' || $params['contact_phone'] == null){
                    throw new CustomerLeadRepoException(CustomerLeadRepoException::DEFICIENCY_CONTACT_PHONE);
                }
            }

            $mAddLead = app()->get(CustomerLeadTable::class);

            $params['tag_id'] = implode(',',$params['tag_id']);
            $birthday = null;
            if (isset($params['birthday']) && $params['birthday'] != "") {
                $birthday = Carbon::createFromFormat('d/m/Y', $params['birthday'])->format('Y-m-d');
            }
            unset($params['brand_code']);

            $dataLead = [
                'avatar' => $params['avatar'] ?? null,
                'customer_type' => $params['customer_type'],
                'customer_source' => $params['customer_source'],
                'full_name' => $params['full_name'],
                'tax_code' => $params['tax_code'] ?? null,
                'phone' => $params['phone'],
                'email' => $params['email'] ?? null,
                'representative' => $params['representative'] ?? null,
                'pipeline_code' => $params['pipeline_code'],
                'journey_code' => $params['journey_code'],
                'sale_id' => $params['sale_id'] ?? null,
                'tag_id' => $params['tag_id'] ? '['.$params['tag_id'].']' : null,
                'gender' => $params['gender'] ?? null,
                'birthday' => $birthday,
                'bussiness_id' => $params['bussiness_id'] ?? null,
                'employees' => $params['employees'] ?? null,
                'address' => $params['address'] ?? null,
                'province_id' => $params['province_id'] ?? null,
                'district_id' => $params['district_id'] ?? null,
                'ward_id' => $params['ward_id'] ?? null,
                'fanpage' => $params['fanpage'] ?? null,
                'zalo' => $params['zalo'] ?? null,
                'business_clue' => $params['business_clue'] ?? null,
                'allocation_date' => $params['allocation_date'] ?? Carbon::now(),
                'created_by' => Auth()->id(),
                'created_at' => Carbon::now(),
                'date_last_care' => Carbon::now(),

            ];
            $id = $mAddLead->createdCustomer($dataLead);
            //            Update customer_lead_code
            $leadCode = "LEAD_" . date("dmY") . sprintf("%02d", $id);
            $mAddLead->updateLeadCode([
                "customer_lead_code" => $leadCode
            ], $id);

            if($params['customer_type'] == 'business'){
                //thêm thông tin người liên hệ
                $addContact = app()->get(CustomerContactTable::class);
                $dataContact = [
                    'customer_lead_code' => $leadCode,
                    'full_name' => $params['contact_full_name'],
                    'positon' => $params['position'] ?? null,
                    'phone' => $params['contact_phone'],
                    'email' => $params['contact_email'] ?? null,
                    'address' => $params['contact_address'] ?? null,
                    'created_at' => Carbon::now(),
                    'created_by' => Auth()->id(),
                ];
                $contact = $addContact ->addNewContact($dataContact);
            }
            return [
                'customer_lead_id' => $id
            ];
        } catch (\Exception $exception) {
            throw new CustomerLeadRepoException(CustomerLeadRepoException::ADD_LEAD, $exception->getMessage() . $exception->getLine());
        }
    }
    //thêm thông tin người liên hệ KHTN business
    public function addContact($params){
        try{
            //thêm thông tin người liên hệ
            $addContact = app()->get(CustomerContactTable::class);
                $dataContact = [
                    'customer_lead_code' => $params['customer_lead_code'],
                    'full_name' => $params['full_name'],
                    'positon' => $params['position'] ?? null,
                    'phone' => $params['phone'],
                    'email' => $params['email'] ?? null,
                    'address' => $params['address'] ?? null,
                    'created_at' => Carbon::now(),
                    'created_by' => Auth()->id(),
                ];
                $contact = $addContact ->addNewContact($dataContact);
            return $contact;
        }catch(\Exception $exception){
            dd($exception->getMessage());
            throw new CustomerLeadRepoException(CustomerLeadRepoException::ADD_CONTACT);
        }
    }
    ///thêm tag
    public function addTag($params){
        try{
            $mTag = app()->get(TagTable::class);
            $data =[
                "name" => $params['name'],
                "type" => 'tag',
                "created_at" => Carbon::now(),
            ];
            $add = $mTag -> addTag($data);
            return $add;
        }catch(\Exception $exception){
            throw new CustomerLeadRepoException(CustomerLeadRepoException::ADD_TAG);
        }
    }

    //lay ten deals
    public function getDealName()
    {
        try {
            $mDealName = app()->get(DealNameTable::class);
            $data = $mDealName->getDealName();
            return $data;
        } catch (\Exception $exception) {
            throw new CustomerLeadRepoException(CustomerLeadRepoException::GET_DEAL_NAME);
        }
    }

    //lay chi nhanh
    public function getBranch()
    {
        try {
            $mBranch = app()->get(BranchTable::class);
            $data = $mBranch->getBranch();

            return $data;
        } catch (\Exception $exception) {
            //            dd($exception->getMessage());
            throw new CustomerLeadRepoException(CustomerLeadRepoException::GET_BRANCH);
        }
    }

    //lay danh sach customer
    public function getCustomer()
    {
        try {
            $mCustomer = app()->get(CustomerTable::class);
            $data = $mCustomer->getCustomer();

            return $data;
        } catch (\Exception $exception) {
            throw new CustomerLeadRepoException(CustomerLeadRepoException::GET_CUSTOMER);
        }
    }

    //lay danh sach nguon don hang
    public function getListOrderSource()
    {
        try {
            $mOrderSource = app()->get(OrderSourceTable::class);
            $data = $mOrderSource->getListOrderSource();

            return $data;
        } catch (\Exception $exception) {
            throw new CustomerLeadRepoException(CustomerLeadRepoException::GET_CUSTOMER);
        }
    }
    //lay danh sach chức vụ
    public function getPosition($input = [])
    {
        try {
            $mPosition= app()->get(StaffTitleTable::class);
            $data = $mPosition->getListPosition();

            return $data;
        } catch (\Exception $exception) {
            throw new CustomerLeadRepoException(CustomerLeadRepoException::GET_LIST_POSITION);
        }
    }

    //lay danh sach KHTN
    public function getDataLead($input)
    {
        try {
            $mList = app()->get(CustomerLeadTable::class);
            $mWork = app()->get(ManageWorkTable::class);
            $mTag = app()->get(TagTable::class);

            if (isset($input['customer_type']) && ($input['customer_type'] == 'Cá nhân' || $input['customer_type'] == 'Personal')) {
                $input['customer_type'] = 'personal';
            } else if (isset($input['customer_type']) && ($input['customer_type'] == 'Doanh nghiệp' || $input['customer_type'] == 'Business')) {
                $input['customer_type'] = 'business';
            } else {
            }
            $data = $mList->getDataLead($input);

            $now = Carbon::parse(now())->format('Y-m-d');
            foreach ($data as $k => $v) {
                $v['tag_id'] = json_decode($v['tag_id']);
                //lấy tag_name
                $tagName = $mTag->getTagName($v['tag_id'] ?? []);
                $v['tag'] = $tagName;
                unset($v['tag_id']);

                $v['diff_day'] = null;
                if (isset($v['date_last_care']) && $v['date_last_care'] != null) {
                    $nowDiff = Carbon::parse($now);
                    $closest_interaction_diff = Carbon::parse($v['date_last_care']);
                    $diff = $closest_interaction_diff->diffInDays($nowDiff);
                    $v['diff_day'] = $diff + 1 ;
                }

                if (isset($v['customer_type']) && ($v['customer_type'] == 'Cá nhân' || $v['customer_type'] == 'Personal')) {
                    $v['customer_type'] = 'personal';
                } else if (isset($v['customer_type']) && ($v['customer_type'] == 'Doanh nghiệp' || $v['customer_type'] == 'Business')) {
                    $v['customer_type'] = 'business';
                } else {
                }

                ///số công việc liên quan
                $numberOfWork = $mWork->getWorkLead($v['customer_lead_id']);
                $v['related_work'] = count($numberOfWork);

                ///số lịch hẹn(công việc loại là họp)
                $numberOfAppointment = $mWork->getNumberOfAppointmentLead($v['customer_lead_id']);
                $v['appointment'] = count($numberOfAppointment);
            }
            return $this->toPagingData($data);
        } catch (\Exception $exception) {
            throw new CustomerLeadRepoException(CustomerLeadRepoException::GET_LIST_CUSTOMER_LEAD);
        }
    }

    //lay chi tiet KHTN
    public function getDetail($input)
    {
        try {
            $mLead = app()->get(CustomerLeadTable::class);
            $mJourney = app()->get(CustomerLeadJourneyTable::class);
            $mWork = app()->get(ManageWorkTable::class);
            $file = app()->get(ManageDocumentFileTable::class);
            $comment = app()->get(ManageLeadCommentTable::class);
            $workComment = app()->get(ManageCommentTable::class);

            $mContact = app()->get(CustomerContactTable::class);
            $mTag = app()->get(TagTable::class);
            $mWorkTag = app()->get(ManageWorkTagTable::class);

            //Lay thong tin lead
            $dataInfo = $mLead->getInfo($input);
            if(isset($dataInfo['customer_type']) && ($dataInfo['customer_type'] == 'Cá nhân' || $dataInfo['customer_type'] == 'Personal')){
                $dataInfo['customer_type'] = 'personal';
            }else if(isset($dataInfo['customer_type']) && ($dataInfo['customer_type'] == 'Doanh nghiệp'|| $dataInfo['customer_type'] == 'Business')){
                $dataInfo['customer_type'] = 'business';
            }else{}

            ///tên tag
            $dataInfo['tag_id'] = json_decode($dataInfo['tag_id']);
            if(isset($dataInfo['tag_id']) && $dataInfo['tag_id'] != null){
                $tagName = $mTag->getTagName($dataInfo['tag_id']);
            }else{
                $tagName = [];
            }
            $dataInfo['tag'] = $tagName;
            unset($dataInfo['tag_id']);

            //ngày tương tác gần nhất
            $now = Carbon::parse(now())->format('Y-m-d');
            $dataInfo['diff_day'] = null;
            if(isset($dataInfo['date_last_care']) && $dataInfo['date_last_care'] != null){
                $dateNow = Carbon::parse($now);
                $dateLastCare = Carbon::parse($dataInfo['date_last_care']);
                $diff = $dateLastCare->diffInDays($dateNow);
                $dataInfo['diff_day'] = $diff +1 ;
            }

            ///số công việc liên quan
            $numberOfWork = $mWork->getWorkLead($dataInfo['customer_lead_id']);
            $dataInfo['related_work'] = count($numberOfWork);

            ///số lịch hẹn(công việc loại là họp)
            $numberOfAppointment = $mWork ->getNumberOfAppointmentLead($dataInfo['customer_lead_id']);
            $dataInfo['appointment'] = count($numberOfAppointment);

            //Lay hanh trinh cua pipeline
            $journeyTracking = [];
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
            return $dataInfo;
        } catch (\Exception $exception) {
            throw new CustomerLeadRepoException(CustomerLeadRepoException::GET_DETAIL);
        }
    }
    ///chi tiết KHTN -thông tin Deal
    public function detailLeadInfoDeal($input){
        try{
            $deal = app()->get(CustomerDealsTable::class);
            $mJourney = app()->get(CustomerLeadJourneyTable::class);
            $mWork = app()->get(ManageWorkTable::class);

            $infoDeal = $deal->getInfoDeal($input['customer_lead_code']);
            $now = Carbon::now();
            foreach ($infoDeal as $k => $v){
                ///lấy ngày chăm sóc gần nhất của deal
                $v['diff_day'] = null;
                if(isset($v['date_last_care']) && $v['date_last_care'] != null){
                    $dateNow = Carbon::parse($now);
                    $dateLastCareDeal = Carbon::parse($v['date_last_care']);
                    $diffDeal = $dateLastCareDeal->diffInDays($dateNow);
                    $v['diff_day'] = $diffDeal + 1 ;
                }
                ///tên hành trình
                $journeyName = $mJourney->getJourneyName($v);
                $v['journey_name'] = $journeyName['journey_name'];

                ///số công việc liên quan
                $numberOfWork = $mWork->getWorkLead($v['customer_lead_id']);
                $v['related_work'] = count($numberOfWork);

                ///số lịch hẹn(công việc loại là họp)
                $numberOfAppointment = $mWork ->getNumberOfAppointmentLead($v['customer_lead_id']);
                $v['appointment'] = count($numberOfAppointment);
                $infoDeal[$k] = $v;
            }
            return $infoDeal;
        }catch (\Exception $exception){
            throw new CustomerLeadRepoException(CustomerLeadRepoException::GET_INFO_DEAL);
        }
    }
    ///danh sách liên hệ
    public function getContactList($input){
        try{
            $mContact = app()->get(CustomerContactTable::class);
            $contact = $mContact->getContact($input['customer_lead_code']);
            foreach ($contact as $k => $v){
                $v['customer_contact_type_name'] = null;
                if(isset($v['customer_contact_type']) && $v['customer_contact_type'] == 'contact'){
                    $v['customer_contact_type_name'] = 'liên hệ';
                }elseif (isset($v['customer_contact_type']) && $v['customer_contact_type'] == 'delivery'){
                    $v['customer_contact_type_name'] = 'vận chuyển';
                }
            }
            return  $contact;
        }catch (\Exception $exception){
            throw new CustomerLeadRepoException(CustomerLeadRepoException::GET_CONTACT_LIST);
        }
    }
    ///danh sách chăm sóc lead
    public function getCareLead($input){
        try{
            $mWorkTag = app()->get(ManageWorkTagTable::class);
            $file = app()->get(ManageDocumentFileTable::class);
            $mWork = app()->get(ManageWorkTable::class);
            $workComment = app()->get(ManageCommentTable::class);

            /////chăm sóc khách hàng
//            $dataCare = [];
            $type = 'lead';
            $now = Carbon::now();
            $care = $mWork ->getCare($input['customer_lead_id'],$type);
            $dateFinish = Carbon::parse($now);
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
//            $dataCare['customer_care'] = $care;
//            ///lịch sử chăm sóc
//            $careHistory = $mWork ->getCareHistory($input['customer_lead_id'],$type);
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
//            $dataCare['care_history'] = $careHistory;
            return $care;
        }catch (\Exception $exception){
            throw new CustomerLeadRepoException(CustomerLeadRepoException::GET_CARE_LEAD);
        }
    }
    ///danh sách message lead
    public function getListMessageLead($input){
        try{
            $mMessage = app()->get(ManageLeadCommentTable::class);
            $list = $mMessage ->getComment($input['customer_lead_id']);
            return  $list;
        }catch (\Exception $exception){
            throw new CustomerLeadRepoException(CustomerLeadRepoException::GET_LIST_COMMENT);
        }
    }
    ///tạo message lead
    public function createMessageLead($data){
        try{
            $mMessage = app()->get(ManageLeadCommentTable::class);

            $createdComment = [
                'customer_lead_id' => $data['customer_lead_id'],
                'customer_lead_parent_comment_id' => isset($data['customer_lead_parent_comment_id']) ? $data['customer_lead_parent_comment_id'] : null,
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
        }catch (\Exception $exception){
            throw new CustomerLeadRepoException(CustomerLeadRepoException::CREATED_COMMENT);
        }
    }
    ///xóa message lead
    public function deleteMessageLead($input){
        try{
            $mMessage = app()->get(ManageLeadCommentTable::class);
            $delete = $mMessage ->deleteMessageLead($input['customer_lead_comment_id']);
            return  $delete;
        }catch (\Exception $exception){
            throw new CustomerLeadRepoException(CustomerLeadRepoException::DELETE_COMMENT);
        }
    }
    ////danh sách trạng thái công việc
    public function getStatusWork($input){
        try{
            $status = app()->get(ManageStatusTable::class);
            $statusWork= $status->getStatusWork($input);
            return  $statusWork;
        }catch (\Exception $exception){
            throw new CustomerLeadRepoException(CustomerLeadRepoException::GET_STATUS_WORK);
        }
    }
    ///dnah sách doanh nghiệp
    public function getListBusiness($input){
        try{
            $buss = app()->get(CustomerTable::class);
            $listBusiness= $buss->getListBusiness($input);
            return  $listBusiness;
        }catch (\Exception $exception){
            throw new CustomerLeadRepoException(CustomerLeadRepoException::GET_LIST_BUSINESS);
        }
    }
    ///danh sách loại công việc
    public function getTypeWork($input){
        try{
            $typeWork = app()->get(ManageTypeWorkTable::class);
            $listTypeWork= $typeWork->getAll($input);
            return  $listTypeWork;
        }catch (\Exception $exception){
            throw new CustomerLeadRepoException(CustomerLeadRepoException::GET_LIST_TYPE_WORK);
        }
    }
    ///lưu công việc chăm sóc khách hàng
    public function saveWork($input){
        try{
            if(!isset($input['make_appointment'])){
                unset($input['date_start']);
                unset($input['date_end']);
                unset($input['manage_status_id']);
                unset($input['created_by']);
            }
            unset($input['make_appointment']);
            if(isset($input['date_start']) && $input['date_start'] != null){
                $input['date_start'] = Carbon::createFromFormat('d/m/Y',$input['date_start'])->format('Y-m-d H:i:s');
            }
            if(isset($input['date_end']) && $input['date_end'] != null){
                $input['date_end'] = Carbon::createFromFormat('d/m/Y',$input['date_end'])->format('Y-m-d H:i:s');
            }
            $input['created_at'] = Carbon::now();
//            $input['created_by'] = Auth()->id();
            $save = app()->get(ManageWorkTable::class);
            $saveWork= $save->saveWork($input);
            return  $saveWork;
        }catch (\Exception $exception){
            throw new CustomerLeadRepoException(CustomerLeadRepoException::SAVE_WORK);
        }
    }
    //chinh sua KHTN
    public function actionUpdate($input)
    {
        try {
            if($input['customer_type'] == 'business'){
                if(empty($input['contact_full_name']) || $input['contact_full_name'] == '' || $input['contact_full_name'] == null){
                    throw new CustomerLeadRepoException(CustomerLeadRepoException::DEFICIENCY_CONTACT_NAME);
                }elseif(empty($input['contact_phone']) || $input['contact_phone'] == '' || $input['contact_phone'] == null){
                    throw new CustomerLeadRepoException(CustomerLeadRepoException::DEFICIENCY_CONTACT_PHONE);
                }
            }

            $mUpdate = app()->get(CustomerLeadTable::class);
            $editContact = app()->get(CustomerContactTable::class);

            $birthday = null;
            if (isset($input['birthday']) && $input['birthday'] != "") {
                $birthday = Carbon::createFromFormat('d/m/Y', $input['birthday'])->format('Y-m-d');
            }
            $input['tag_id'] = implode(',',$input['tag_id']);
            $dataUpdate = [
                'avatar' => $input['avatar'] ?? null,
                'customer_type' => $input['customer_type'],
                'customer_source' => $input['customer_source'],
                'full_name' => $input['full_name'],
                'tax_code' => $input['tax_code'] ?? null,
                'phone' => $input['phone'],
                'email' => $input['email'] ?? null,
                'representative' => $input['representative'] ?? null,
                'pipeline_code' => $input['pipeline_code'],
                'journey_code' => $input['journey_code'] ?? null,
                'sale_id' => $input['sale_id'] ?? null,
                'tag_id' =>  $input['tag_id'] ? '['.$input['tag_id'].']' : null,
                'gender' => $input['gender'] ?? null,
                'birthday' => $birthday,
                'bussiness_id' => $input['bussiness_id'] ?? null,
                'employees' => $input['employees'] ?? null,
                'address' => $input['address'] ?? null,
                'province_id' => $input['province_id'] ?? null,
                'district_id' => $input['district_id'] ?? null,
                'ward_id' => $input['ward_id'] ?? null,
                'fanpage' => $input['fanpage'] ?? null,
                'zalo' => $input['zalo'] ?? null,
                'business_clue' => $input['business_clue'] ?? null,
                'allocation_date' => $input['allocation_date'] ?? null,
                'updated_by' => Auth()->id(),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'date_last_care' => Carbon::now(),
            ];
            $customerLeadCode = $input['customer_lead_code'];
            $data = $mUpdate->actionUpdate($dataUpdate, $customerLeadCode);
            ///update người liên hệ nếu là business
            if($input['customer_type'] == 'business'){
                $dataContact = [
                    'full_name' => $input['contact_full_name'] ?? null,
                    'positon' => $input['position'] ?? null,
                    'phone' => $input['contact_phone'] ?? null,
                    'email' => $input['contact_email'] ?? null,
                    'address' => $input['contact_address'] ?? null,
                    'updated_at' => Carbon::now(),
                ];
                $contact = $editContact->editContact($dataContact,$customerLeadCode);
            }
            return $data;
        } catch (\Exception $exception) {
            throw new CustomerLeadRepoException(CustomerLeadRepoException::UPDATE_CUSTOMER_LEAD);
        }
    }


    //delete lead
    public function actionDelete($input)
    {
        try {
            $mDelete = app()->get(CustomerLeadTable::class);
            $data = $mDelete->actionDelete($input);
            return $data;
        } catch (\Exception $exception) {
            throw new CustomerLeadRepoException(CustomerLeadRepoException::DELETE_CUSTOMER_LEAD);
        }
    }

    /**
     * Lấy ds tag
     *
     * @return mixed
     * @throws CustomerLeadRepoException
     */
    public function getTag()
    {
        try {
            $mTag = app()->get(TagTable::class);

            //Lấy ds tag
            return $mTag->getDataTag();
        } catch (\Exception $exception) {
            throw new CustomerLeadRepoException(CustomerLeadRepoException::GET_TAG_FAILED);
        }
    }

    /**
     * Phân bổ hoặc thu hồi lead
     *
     * @param $input
     * @return mixed|void
     * @throws CustomerLeadRepoException
     */
    public function assignRevoke($input)
    {
        try {
            $mLead = app()->get(CustomerLeadTable::class);

            switch ($input['type']) {
                case 'assign':
                    //Phân bổ
                    $mLead->actionUpdate([
                        'assign_by' => Auth()->id(),
                        'sale_id' => $input['sale_id'],
                        'date_revoke' => Carbon::now()->addDays(intval($input['time_revoke_lead']))->format('Y-m-d H:i:s'),
                        'allocation_date' => Carbon::now()->format('Y-m-d H:i:s')
                    ], $input['customer_lead_code']);

                    break;
                case 'revoke':
                    //Thu hồi
                    $mLead->actionUpdate([
                        'assign_by' => null,
                        'sale_id' => null,
                        'date_revoke' => null,
                        'allocation_date' => null
                    ], $input['customer_lead_code']);

                    break;
            }
        } catch (\Exception $exception) {
            throw new CustomerLeadRepoException(CustomerLeadRepoException::ASSIGN_REVOKE_LEAD_FAILED);
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

            $mManageComment = new CustomerLeadCommentTable();

            $listComment = $mManageComment->getListComment($data['customer_lead_id']);
            if (count($listComment) != 0) {
                foreach ($listComment as $key => $item) {
                    $listComment[$key]['list_object'] = $mManageComment->getListComment($item['customer_lead_id'], $item['customer_lead_comment_id']);
                   
                }
            }

            return $listComment;
        } catch (\Exception $exception) {
            throw new CustomerLeadRepoException(CustomerLeadRepoException::GET_DATA_FAILED, $exception->getMessage() . $exception->getLine());
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
            $mCustomerComment = new CustomerLeadCommentTable();

            $createdComment = [
                'customer_lead_id' => $data['customer_lead_id'],
                'customer_lead_parent_comment_id' => isset($data['customer_lead_parent_comment_id']) ? $data['customer_lead_parent_comment_id'] : null,
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
            throw new CustomerLeadRepoException(CustomerLeadRepoException::GET_DATA_FAILED, $exception->getMessage() . $exception->getLine());
        }
    }
}