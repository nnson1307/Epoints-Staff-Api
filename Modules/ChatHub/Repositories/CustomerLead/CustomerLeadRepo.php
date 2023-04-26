<?php

namespace Modules\ChatHub\Repositories\CustomerLead;

use Carbon\Carbon;
use Modules\ChatHub\Models\CustomerLeadJourneyTable;
use Modules\ChatHub\Models\CustomerLeadTable;
use Modules\ChatHub\Models\CustomerTable;
use Modules\ChatHub\Repositories\CustomerLead\CustomerLeadRepoInterface;
use MyCore\Repository\PagingTrait;


class CustomerLeadRepo implements CustomerLeadRepoInterface
{
    use PagingTrait;

    
    //lay chi tiet KHTN
    public function getDetail($input)
    {
        try {
            $mLead = app()->get(CustomerLeadTable::class);
            $mJourney = app()->get(CustomerLeadJourneyTable::class);

            //Lay thong tin lead
            $dataInfo = $mLead->getInfo($input);

            $journeyTracking = [];

            //Lay hanh trinh cua pipeline
            $listJourney = $mJourney->getDataJourney($dataInfo['pipeline_code']);
            $listJourneyEdit = $mJourney->getOptionEdit($dataInfo['pipeline_code'], $dataInfo["journey_position"]);


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
            $dataInfo['journey_edit'] = $listJourneyEdit;
            return $dataInfo;
        } catch (\Exception $exception) {
            throw new CustomerLeadRepoException(CustomerLeadRepoException::GET_DETAIL, $exception->getMessage());
        }
    }

    /**
     * Cập nhật hành trình khách hàng
     *
     * @param $input
     * @return array|mixed
     */
    public function updateJourney($input)
    {
        try {
            $mJourney = new CustomerLeadJourneyTable();
            $mCustomer = new CustomerTable();
            $mLead = app()->get(CustomerLeadTable::class);
            //Get customer lead
            // $getInfo = $mLead->getInfo($input['customer_lead_id']);
            // //Get journey old
            // $getOld = $mJourney->getInfoUpdateJourney($input['pipeline_id'], $input['journey_old']);
            // //Get journey new
            // $getNew = $mJourney->getInfoUpdateJourney($input['pipeline_id'], $input['journey_new']);

            // //Check journey old dc update qua journey new ko
            // if (!in_array($getNew['journey_id'], explode(',', $getOld['journey_updated']))) {
            //     throw new CustomerLeadRepoException(CustomerLeadRepoException::VALID_FAIL, __('Chỉnh sửa thất bại') . ', Journey new ko có trong journey_updated của journey cũ');
            //     // return [
            //     //     'error' => true,
            //     //     'message' => __('Chỉnh sửa thất bại'),
            //     //     '_message' => 'Journey new ko có trong journey_updated của journey cũ'
            //     // ];
            // }
            //Update journey customer lead
            $mLead->edit([
                'journey_code' => $input['journey_new']
            ], $input['customer_lead_id']);

            //Check customer có tồn tại chưa
            // $checkCustomer = $mCustomer->getCustomerByPhone($getInfo["phone"]);

            // if ($getNew["default_system"] == "win" && $checkCustomer == null) {
            //     //Insert customer
            //     $mCustomer->add([
            //         "full_name" => $getInfo["full_name"],
            //         "email" => $getInfo["email"],
            //         "phone1" => $getInfo["phone"],
            //         "gender" => $getInfo["gender"],
            //         "address" => $getInfo["address"],
            //         "branch_id" => Auth()->user()->branch_id,
            //         "member_level_id" => 1,
            //         "created_by" => Auth()->id(),
            //         "updated_by" => Auth()->id()
            //     ]);
            // }

            //Kiểm tra tạo deal tự động
            // $checkHaveDeal = $this->checkJourneyHaveDeal($input["journey_new"], $input["customer_lead_id"]);

            return [
                'error' => false,
                'message' => __('Chỉnh sửa thành công'),
                // "create_deal" => $checkHaveDeal,
                "lead_id" => $input["customer_lead_id"]
            ];
        } catch (\Exception $e) {
            throw new CustomerLeadRepoException(CustomerLeadRepoException::VALID_FAIL, __('Chỉnh sửa thất bại'));
        }
    }

      /**
     * Kiểm tra hành trình có tạo deal không
     *
     * @param $journeyCode
     * @param $leadId
     * @return int
     */
    private function checkJourneyHaveDeal($journeyCode, $leadId)
    {
        $mJourney = new CustomerLeadJourneyTable();
        $mCustomerLead = new CustomerLeadTable();

        //Lấy thông tin hành trình
        $getJourney = $mJourney->getInfo($journeyCode);
        //Lấy thông tin KH tiềm năng
        $getLead = $mCustomerLead->getInfo($leadId);

        $createDeal = 0;

        if ($getJourney['is_deal_created'] == 1 && $getLead['deal_code'] == null) {
            $createDeal = 1;
        }

        return $createDeal;
    }
}