<?php

namespace Modules\ChatHub\Http\Controllers;

use Illuminate\Http\Request;
use Modules\ChatHub\Repositories\CustomerLead\CustomerLeadRepoInterface;
use Modules\ChatHub\Repositories\CustomerLead\CustomerLeadRepoException;
use Modules\ChatHub\Http\Requests\CustomerLead\UpdateJourneyRequest;

class CustomerLeadController extends Controller
{
    protected $customerLead;

    public function __construct(
        CustomerLeadRepoInterface $customerLead
    ) {
        $this->customerLead = $customerLead;
    }

    public function getDetail(Request $request)
    {
        try {
            $data = $this->customerLead->getDetail($request->all());

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (CustomerLeadRepoException $ex) {
            return $this->responseJson(CODE_ERROR, $ex->getMessage());
        }
    }

    
    /**
     * Lấy thông tin khách hàng
     *
     * @param CustomerInfoRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateJourney(UpdateJourneyRequest $request)
    {
        try {
            $data = $this->customerLead->updateJourney($request->all());

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (CustomerLeadRepoException $ex) {
            return $this->responseJson(CODE_ERROR, $ex->getMessage());
        }
    }
}