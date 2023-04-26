<?php

namespace Modules\CustomerLead\Http\Controllers;

use Illuminate\Http\Request;
use Modules\CustomerLead\Http\Requests\CustomerLead\AssignRevokeRequest;
use Modules\CustomerLead\Repositories\CustomerLead\CustomerLeadRepoInterface;
use Modules\CustomerLead\Repositories\CustomerLead\CustomerLeadRepoException;
use Modules\CustomerLead\Http\Requests\CustomerLead\AddLeadRequest;
use Modules\CustomerLead\Http\Requests\CustomerLead\AddContactRequest;
use Modules\CustomerLead\Http\Requests\CustomerLead\AddTagRequest;
use Modules\CustomerLead\Http\Requests\CustomerLead\DetailLeadRequest;
use Modules\CustomerLead\Http\Requests\CustomerLead\UpdateLeadRequest;
use Modules\CustomerLead\Http\Requests\CustomerLead\DeleteLeadRequest;
use Modules\CustomerLead\Http\Requests\CustomerLead\SaveWorkRequest;
use Modules\CustomerLead\Http\Requests\CustomerLead\AddBusinessAreasRequest;
use Modules\CustomerLead\Http\Requests\CustomerLead\CommentIdRequest;
use Modules\CustomerLead\Http\Requests\CustomerLead\LeadIdRequest;
use Modules\CustomerLead\Http\Requests\CustomerLead\CreateCommentRequest;


class CustomerLeadController extends Controller
{
    protected $customerLead;

    public function __construct(
        CustomerLeadRepoInterface $customerLead
    ) {
        $this->customerLead = $customerLead;
    }

    //lay thong tin loai khach hang va nguon khach hang
    public function getCustomerOption()
    {
        try {
            $data = $this->customerLead->getOption();

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (CustomerLeadRepoException $ex) {
            return $this->responseJson(CODE_ERROR, $ex->getMessage());
        }
    }

    //lay  pipeline
    public function getPipeline(Request $request)
    {
        try {
            $data = $this->customerLead->getPipe($request->all());

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (CustomerLeadRepoException $ex) {
            return $this->responseJson(CODE_ERROR, $ex->getMessage());
        }
    }

    //lay journey
    public function getJourney(Request $request)
    {
        try {
            $data = $this->customerLead->getDataJourney($request->all());

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (CustomerLeadRepoException $ex) {
            return $this->responseJson(CODE_ERROR, $ex->getMessage());
        }
    }

    //lay tinh thanh
    public function getProvince()
    {
        try {
            $mProvince = app()->get(CustomerLeadRepoInterface::class);
            $data = $mProvince->getDataProvince();

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (CustomerLeadRepoException $ex) {
            return $this->responseJson(CODE_ERROR, $ex->getMessage());
        }
    }

    public function getDistrict(Request $request)
    {
        try {
            $data = $this->customerLead->getDataDistrict($request->all());

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (CustomerLeadRepoException $ex) {
            return $this->responseJson(CODE_ERROR, $ex->getMessage());
        }
    }

    public function getAllocator()
    {
        try {
            $data = $this->customerLead->getDataAllocator();

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (CustomerLeadRepoException $ex) {
            return $this->responseJson(CODE_ERROR, $ex->getMessage());
        }
    }
    public function listBusinessAreas()
    {
        try {
            $data = $this->customerLead->getListBusinessAreas();

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (CustomerLeadRepoException $ex) {
            return $this->responseJson(CODE_ERROR, $ex->getMessage());
        }
    }
    public function addBusinessAreas(AddBusinessAreasRequest $request)
    {
        try {
            $data = $this->customerLead->addBusinessAreas($request->all());

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (CustomerLeadRepoException $ex) {
            return $this->responseJson(CODE_ERROR, $ex->getMessage());
        }
    }

    public function addLead(AddLeadRequest $request)
    {

        try {
            // thuc hien tao lead moi
            $data = $this->customerLead->createdCustomerLead($request->all());

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (CustomerLeadRepoException $ex) {
            return $this->responseJson(CODE_ERROR, $ex->getMessage());
        }
    }
    public function addContact(AddContactRequest $request)
    {

        try {
            // thuc hien tao lead moi
            $data = $this->customerLead->addContact($request->all());

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (CustomerLeadRepoException $ex) {
            return $this->responseJson(CODE_ERROR, $ex->getMessage());
        }
    }
    ///thêm thẻ(tag)
    public function addTag(AddTagRequest $request)
    {

        try {
            // thuc hien tao tag moi
            $data = $this->customerLead->addTag($request->all());

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (CustomerLeadRepoException $ex) {
            return $this->responseJson(CODE_ERROR, $ex->getMessage());
        }
    }

    // lay ten deals
    public function getDealName()
    {
        try {
            $data = $this->customerLead->getDealName();

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (CustomerLeadRepoException $ex) {
            return $this->responseJson(CODE_ERROR, $ex->getMessage());
        }
    }

    // lay chi nhanh
    public function getBranch()
    {
        try {
            $data = $this->customerLead->getBranch();

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (CustomerLeadRepoException $ex) {
            return $this->responseJson(CODE_ERROR, $ex->getMessage());
        }
    }

    // lay chi danh sach customer
    public function getCustomer()
    {
        try {
            $data = $this->customerLead->getCustomer();

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (CustomerLeadRepoException $ex) {
            return $this->responseJson(CODE_ERROR, $ex->getMessage());
        }
    }

    //lay danh sach nguon don hang
    public function getOrderSource()
    {
        try {
            $data = $this->customerLead->getListOrderSource();

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (CustomerLeadRepoException $ex) {
            return $this->responseJson(CODE_ERROR, $ex->getMessage());
        }
    }

    public function getWard(Request $request)
    {
        try {
            $data = $this->customerLead->getDataWard($request->all());

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (CustomerLeadRepoException $ex) {
            return $this->responseJson(CODE_ERROR, $ex->getMessage());
        }
    }
    public function getPosition(Request $request)
    {
        try {
            $data = $this->customerLead->getPosition($request->all());

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (CustomerLeadRepoException $ex) {
            return $this->responseJson(CODE_ERROR, $ex->getMessage());
        }
    }

    public function getListCustomerLead(Request $request)
    {
        try {
            $data = $this->customerLead->getDataLead($request->all());

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (CustomerLeadRepoException $ex) {
            return $this->responseJson(CODE_ERROR, $ex->getMessage());
        }
    }

    public function getDetailLead(DetailLeadRequest $request)
    {
        try {
            $data = $this->customerLead->getDetail($request->all());

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (CustomerLeadRepoException $ex) {
            return $this->responseJson(CODE_ERROR, $ex->getMessage());
        }
    }
    public function detailLeadInfoDeal(DetailLeadRequest $request)
    {
        try {
            $data = $this->customerLead->detailLeadInfoDeal($request->all());

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (CustomerLeadRepoException $ex) {
            return $this->responseJson(CODE_ERROR, $ex->getMessage());
        }
    }
    public function getContactList(DetailLeadRequest $request)
    {
        try {
            $data = $this->customerLead->getContactList($request->all());

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (CustomerLeadRepoException $ex) {
            return $this->responseJson(CODE_ERROR, $ex->getMessage());
        }
    }
    public function getListMessageLead(LeadIdRequest $request)
    {
        try {
            $data = $this->customerLead->getListMessageLead($request->all());

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (CustomerLeadRepoException $ex) {
            return $this->responseJson(CODE_ERROR, $ex->getMessage());
        }
    }
    public function createMessageLead(LeadIdRequest $request)
    {
        try {
            $data = $this->customerLead->createMessageLead($request->all());

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (CustomerLeadRepoException $ex) {
            return $this->responseJson(CODE_ERROR, $ex->getMessage());
        }
    }
    public function deleteMessageLead(CommentIdRequest $request)
    {
        try {
            $data = $this->customerLead->deleteMessageLead($request->all());

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (CustomerLeadRepoException $ex) {
            return $this->responseJson(CODE_ERROR, $ex->getMessage());
        }
    }
    public function getCareLead(LeadIdRequest $request)
    {
        try {
            $data = $this->customerLead->getCareLead($request->all());

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (CustomerLeadRepoException $ex) {
            return $this->responseJson(CODE_ERROR, $ex->getMessage());
        }
    }
    public function getStatusWork(Request $request)
    {
        try {
            $data = $this->customerLead->getStatusWork($request->all());

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (CustomerLeadRepoException $ex) {
            return $this->responseJson(CODE_ERROR, $ex->getMessage());
        }
    }
    public function getListBusiness(Request $request)
    {
        try {
            $data = $this->customerLead->getListBusiness($request->all());

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (CustomerLeadRepoException $ex) {
            return $this->responseJson(CODE_ERROR, $ex->getMessage());
        }
    }
    public function getTypeWork(Request $request)
    {
        try {
            $data = $this->customerLead->getTypeWork($request->all());

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (CustomerLeadRepoException $ex) {
            return $this->responseJson(CODE_ERROR, $ex->getMessage());
        }
    }
    public function saveWork(SaveWorkRequest $request)
    {
        try {
            $data = $this->customerLead->saveWork($request->all());
            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (CustomerLeadRepoException $ex) {
            return $this->responseJson(CODE_ERROR, $ex->getMessage());
        }
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
    //update lead
    public function updateLead(UpdateLeadRequest $request)
    {
        try {
            $data = $this->customerLead->actionUpdate($request->all());

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (CustomerLeadRepoException $ex) {
            return $this->responseJson(CODE_ERROR, $ex->getMessage());
        }
    }
    //delete lead
    public function deleteLead(DeleteLeadRequest $request)
    {
        try {
            $data = $this->customerLead->actionDelete($request->all());

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (CustomerLeadRepoException $ex) {
            return $this->responseJson(CODE_ERROR, $ex->getMessage());
        }
    }

    /**
     * Lấy ds nhãn
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getTag()
    {
        try {
            $data = $this->customerLead->getTag();

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (CustomerLeadRepoException $ex) {
            return $this->responseJson(CODE_ERROR, $ex->getMessage());
        }
    }

    /**
     * Phân bổ hoặc thu hồi lead
     *
     * @param AssignRevokeRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function assignRevokeLead(AssignRevokeRequest $request)
    {
        try {
            $data = $this->customerLead->assignRevoke($request->all());

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (CustomerLeadRepoException $ex) {
            return $this->responseJson(CODE_ERROR, $ex->getMessage());
        }
    }

     /**
     * Danh sách comment
     * @return \Illuminate\Http\JsonResponse
     */
    public function listComment(Request $request)
    {
        try {
            $param = $request->all();
            $data = $this->customerLead->listComment($param);

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (\Exception $exception) {
            return $this->responseJson(CODE_ERROR, $exception->getMessage());
        }
    }

    /**
     * Tạo comment
     * @param CreateCommentRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function createdComment(CreateCommentRequest $request)
    {
        try {
            $param = $request->all();
            $data = $this->customerLead->createdComment($param);

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (\Exception $exception) {
            return $this->responseJson(CODE_ERROR, $exception->getMessage());
        }
    }
}