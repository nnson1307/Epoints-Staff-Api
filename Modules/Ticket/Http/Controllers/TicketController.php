<?php

namespace Modules\Ticket\Http\Controllers;

use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Ticket\Http\Requests\AcceptanceRecord\AcceptanceRecordEditRequest;
use Modules\Ticket\Http\Requests\AcceptanceRecord\AcceptanceRecordRequest;
use Modules\Ticket\Http\Requests\RequestForm\InfoRequestFormDetailRequest;
use Modules\Ticket\Http\Requests\RequestForm\InfoRequestFormRequest;
use Modules\Ticket\Http\Requests\RequestForm\RequestFormEditRequest;
use Modules\Ticket\Http\Requests\RequestForm\RequestFormRequest;
use Modules\Ticket\Http\Requests\Ticket\TaskOfTicketRequest;
use Modules\Ticket\Http\Requests\Ticket\TicketEditRequest;
use Modules\Ticket\Http\Requests\Ticket\TicketAddRequest;
use Modules\Ticket\Http\Requests\Location\AddTicketLocationRequest;
use Modules\Ticket\Http\Requests\Ticket\CreateCommentRequest;
use Modules\Ticket\Repositories\TicketRepositoryInterface;

class TicketController extends Controller
{
    protected $ticketRepo;

    public function __construct(TicketRepositoryInterface $ticketRepo)
    {
        $this->ticketRepo = $ticketRepo;
    }

//    Lấy tổng ticket trang chủ
    public function totalTicket()
    {
        try {
            $data = $this->ticketRepo->getTotalTicket();

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (\Exception|QueryException $exception) {
            return $this->responseJson(CODE_ERROR, $exception->getMessage());
        }
    }

//    Danh sách ticket , chưa phân công và đã phân công
    public function myTicket(Request $request)
    {
        try {
            $param = $request->all();
            $data = $this->ticketRepo->getMyTicket($param);

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (\Exception|QueryException $exception) {
            return $this->responseJson(CODE_ERROR, $exception->getMessage());
        }
    }

//    Danh sách ticket user có quyền xem
    public function listTicket(Request $request)
    {
        try {
            $param = $request->all();
            $data = $this->ticketRepo->getListTicket($param);

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (\Exception|QueryException $exception) {
            return $this->responseJson(CODE_ERROR, $exception->getMessage());
        }
    }

//    Thông tin ticket chưa hoàn thành
    public function ticketNotCompleted()
    {
        try {
            $data = $this->ticketRepo->getTicketNotCompleted();

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (\Exception|QueryException $exception) {
            return $this->responseJson(CODE_ERROR, $exception->getMessage());
        }
    }

//    Chi tiết ticket
    public function ticketDetail(Request $request)
    {
        try {
            $param = $request->all();
            $data = $this->ticketRepo->getDetail($param);

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (\Exception|QueryException $exception) {
            return $this->responseJson(CODE_ERROR, $exception->getMessage());
        }
    }

//    Cập nhật ticket
    public function ticketEdit(TicketEditRequest $request)
    {
        try {
            $param = $request->all();
            $data = $this->ticketRepo->editTicket($param);

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (\Exception|QueryException $exception) {
            return $this->responseJson(CODE_ERROR, $exception->getMessage());
        }
    }

//    Tạo phiếu yêu cầu
    public function addRequestForm(RequestFormRequest $request)
    {
        try {
            $param = $request->all();
            $data = $this->ticketRepo->addRequestForm($param);

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (\Exception|QueryException $exception) {
            return $this->responseJson(CODE_ERROR, $exception->getMessage());
        }
    }

//    Tạo phiếu yêu cầu
    public function editRequestForm(RequestFormEditRequest $request)
    {
        try {
            $param = $request->all();
            $data = $this->ticketRepo->editRequestForm($param);

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (\Exception|QueryException $exception) {
            return $this->responseJson(CODE_ERROR, $exception->getMessage());
        }
    }

//    Xoá phiếu yêu cầu
    public function deleteRequestForm(InfoRequestFormDetailRequest $request)
    {
        try {
            $param = $request->all();
            $data = $this->ticketRepo->deleteRequestForm($param);

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (\Exception|QueryException $exception) {
            return $this->responseJson(CODE_ERROR, $exception->getMessage());
        }
    }

//    Thông tin vật tư
    public function infoMaterials(InfoRequestFormRequest $request)
    {
        try {
            $param = $request->all();
            $data = $this->ticketRepo->infoMaterials($param);

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (\Exception|QueryException $exception) {
            return $this->responseJson(CODE_ERROR, $exception->getMessage());
        }
    }

//    Chi tiết vật tư
    public function infoMaterialsDetail(InfoRequestFormDetailRequest $request)
    {
        try {
            $param = $request->all();
            $data = $this->ticketRepo->infoMaterialsDetail($param);

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (\Exception|QueryException $exception) {
            return $this->responseJson(CODE_ERROR, $exception->getMessage());
        }
    }

//    Chi tiết vật tư
    public function ratingDetail(InfoRequestFormRequest $request)
    {
        try {
            $param = $request->all();
            $data = $this->ticketRepo->ratingDetail($param);

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (\Exception|QueryException $exception) {
            return $this->responseJson(CODE_ERROR, $exception->getMessage());
        }
    }

//    Danh sách lịch sử
    public function history(InfoRequestFormRequest $request)
    {
        try {
            $param = $request->all();
            $data = $this->ticketRepo->getHistory($param);

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (\Exception|QueryException $exception) {
            return $this->responseJson(CODE_ERROR, $exception->getMessage());
        }
    }

//    Danh sách ảnh
    public function image(InfoRequestFormRequest $request)
    {
        try {
            $param = $request->all();
            $data = $this->ticketRepo->getImage($param);

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (\Exception|QueryException $exception) {
            return $this->responseJson(CODE_ERROR, $exception->getMessage());
        }
    }

//    Thêm hình ảnh
    public function addImage(InfoRequestFormRequest $request)
    {
        try {
            $param = $request->all();
            $data = $this->ticketRepo->addImage($param);

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (\Exception|QueryException $exception) {
            return $this->responseJson(CODE_ERROR, $exception->getMessage());
        }
    }

//    Thông tin biên bản nghiệm thu
    public function acceptanceRecord(InfoRequestFormRequest $request)
    {
        try {
            $param = $request->all();
            $data = $this->ticketRepo->acceptanceRecord($param);

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (\Exception|QueryException $exception) {
            return $this->responseJson(CODE_ERROR, $exception->getMessage());
        }
    }

//    Tạo biên bản nghiệm thu
    public function acceptanceRecordCreate(AcceptanceRecordRequest $request)
    {
        try {
            $param = $request->all();
            $data = $this->ticketRepo->acceptanceRecordCreate($param);

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (\Exception|QueryException $exception) {
            return $this->responseJson(CODE_ERROR, $exception->getMessage());
        }
    }

//    Chỉnh sửa biên bản nghiệm thu
    public function acceptanceRecordEdit(AcceptanceRecordEditRequest $request)
    {
        try {
            $param = $request->all();
            $data = $this->ticketRepo->acceptanceRecordEdit($param);

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (\Exception|QueryException $exception) {
            return $this->responseJson(CODE_ERROR, $exception->getMessage());
        }
    }

//    Tìm kiếm vật tư
    public function searchMaterials(Request $request)
    {
        try {
            $param = $request->all();
            $data = $this->ticketRepo->searchMaterials($param);

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (\Exception|QueryException $exception) {
            return $this->responseJson(CODE_ERROR, $exception->getMessage());
        }
    }

//    Danh sách nhóm yêu cầu
    public function issueGroup(Request $request)
    {
        try {
            $param = $request->all();
            $data = $this->ticketRepo->issueGroup($param);

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (\Exception|QueryException $exception) {
            return $this->responseJson(CODE_ERROR, $exception->getMessage());
        }
    }

    //    Danh sách yêu cầu
    public function issue(Request $request)
    {
        try {
            $param = $request->all();
            $data = $this->ticketRepo->issue($param);

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (\Exception|QueryException $exception) {
            return $this->responseJson(CODE_ERROR, $exception->getMessage());
        }
    }

//    Danh sách trạng thái
    public function listStatus()
    {
        try {
            $data = $this->ticketRepo->listStatus();

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (\Exception|QueryException $exception) {
            return $this->responseJson(CODE_ERROR, $exception->getMessage());
        }
    }

//    Danh sách queue có quyền xem
    public function listQueue()
    {
        try {
            $data = $this->ticketRepo->listQueue();

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (\Exception|QueryException $exception) {
            return $this->responseJson(CODE_ERROR, $exception->getMessage());
        }
    }

//    Upload file
    public function uploadFile(Request $request)
    {
        try {

            $data = $this->ticketRepo->uploadFile($request->all());

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (\Exception|QueryException $exception) {
            return $this->responseJson(CODE_ERROR, $exception->getMessage());
        }
    }

//    Lấy danh sách nhân viên theo queue
    public function getListStaffByQueue(Request $request)
    {
        try {

            $data = $this->ticketRepo->getListStaffByQueue($request->all());

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (\Exception|QueryException $exception) {
            return $this->responseJson(CODE_ERROR, $exception->getMessage());
        }
    }

    /**
     * Lấy ds công việc của ticket
     *
     * @param TaskOfTicketRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function listTaskOfTicket(TaskOfTicketRequest $request)
    {
        try {
            $data = $this->ticketRepo->getListTaskOfTicket($request->all());

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (\Exception|QueryException $exception) {
            return $this->responseJson(CODE_ERROR, $exception->getMessage());
        }
    }

    /**
     * Thêm vị trí
     *
     * @param TaskOfTicketRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function addLocationTicket(AddTicketLocationRequest $request)
    {
        try {
            $data = $this->ticketRepo->addTikcetLocation($request->all());

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (\Exception|QueryException $exception) {
            return $this->responseJson(CODE_ERROR, $exception->getMessage());
        }
    }

    /**
     * Thêm vị trí
     *
     * @param TaskOfTicketRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getLocationTicket(Request $request)
    {
        try {
            $data = $this->ticketRepo->listLocation($request->all());

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (\Exception|QueryException $exception) {
            return $this->responseJson(CODE_ERROR, $exception->getMessage());
        }
    }

    /**
     * Thêm vị trí
     *
     * @param TaskOfTicketRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteLocationTicket(Request $request)
    {
        try {
            $data = $this->ticketRepo->removeLocation($request->all());

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (\Exception|QueryException $exception) {
            return $this->responseJson(CODE_ERROR, $exception->getMessage());
        }
    }

    //    Thêm mới ticket
    public function ticketAdd(TicketAddRequest $request)
    {
        try {

            $param = $request->all();
            $data = $this->ticketRepo->addTicket($param);

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (\Exception|QueryException $exception) {
            return $this->responseJson(CODE_ERROR, $exception->getMessage());
        }
    }

    /**
     * Lấy danh sách mức độ ưu tiên
     *
     * @param TaskOfTicketRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getListPriority()
    {
        return $this->responseJson(CODE_SUCCESS, null, ARRAY_PRIORITY);

    }


    /**
     * Lấy danh sách mức độ ưu tiên
     *
     * @param TaskOfTicketRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function loadStaffByQueue(Request $request)
    {
        try {

            $param = $request->all();
            $data = $this->ticketRepo->loadStaffByQueue($param);

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (\Exception|QueryException $exception) {
            return $this->responseJson(CODE_ERROR, $exception->getMessage());
        }

    }

    /**
     * Lấy danh sách queue
     *
     * @param TaskOfTicketRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getListQueue()
    {
        try {
            $data = $this->ticketRepo->loadQueue();

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (\Exception|QueryException $exception) {
            return $this->responseJson(CODE_ERROR, $exception->getMessage());
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
            $data = $this->ticketRepo->listComment($param);

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (\Exception|QueryException $exception) {
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
            $data = $this->ticketRepo->createdComment($param);

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (\Exception|QueryException $exception) {
            return $this->responseJson(CODE_ERROR, $exception->getMessage());
        }
    }

}
