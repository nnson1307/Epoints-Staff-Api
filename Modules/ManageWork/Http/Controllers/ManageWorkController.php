<?php

namespace Modules\ManageWork\Http\Controllers;

use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\ManageWork\Http\Requests\Comment\CommentDetailRequest;
use Modules\ManageWork\Http\Requests\Document\DocumentFileUploadRequest;
use Modules\ManageWork\Http\Requests\Project\ProjectAddRequest;
use Modules\ManageWork\Http\Requests\Remind\RemindDetailRequest;
use Modules\ManageWork\Http\Requests\Remind\RemindRequest;
use Modules\ManageWork\Http\Requests\RepeatWork\RepeatWorkUpdateRequest;
use Modules\ManageWork\Http\Requests\Tag\TagAddRequest;
use Modules\ManageWork\Http\Requests\TypeWork\TypeWorkAddRequest;
use Modules\ManageWork\Http\Requests\Work\CreateLocationRequest;
use Modules\ManageWork\Http\Requests\Work\DestroyLocationRequest;
use Modules\ManageWork\Http\Requests\Work\JobOverViewRequest;
use Modules\ManageWork\Http\Requests\Work\ListLocationRequest;
use Modules\ManageWork\Http\Requests\Work\WorkAddRequest;
use Modules\ManageWork\Http\Requests\Work\WorkDetailRequest;
use Modules\ManageWork\Http\Requests\Work\WorkEditRequest;
use Modules\ManageWork\Repositories\ManageWorkRepositoryInterface;

class ManageWorkController extends Controller
{
    protected $manageWorkRepo;

    public function __construct(ManageWorkRepositoryInterface $manageWorkRepo)
    {
        $this->manageWorkRepo = $manageWorkRepo;
    }

    /**
     * lấy tổng công việc theo các trạng thái ở màn hình hơme
     * @return mixed
     */
    public function totalWork(Request $request)
    {
        try {
            $param = $request->all();
            $data = $this->manageWorkRepo->getTotalWork($param);

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (\Exception | QueryException $exception) {
            return $this->responseJson(CODE_ERROR, $exception->getMessage());
        }
    }

    /**
     * lấy tổng công việc theo các trạng thái ở màn hình hơme hỗ trợ
     * @return mixed
     */
    public function totalWorkSupport(Request $request)
    {
        try {
            $param = $request->all();
            $data = $this->manageWorkRepo->getTotalWorkSupport($param);

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (\Exception | QueryException $exception) {
            return $this->responseJson(CODE_ERROR, $exception->getMessage());
        }
    }

    /**
     * Tổng công việc và danh sách trễ hạn, nhân viên chưa có công việc trong ngày , nhân viên chưa bắt đầu công việc trong ngày
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function jobOverview(Request $request)
    {
        try {
            $param = $request->all();
            $data = $this->manageWorkRepo->jobOverview($param);

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (\Exception | QueryException $exception) {
            return $this->responseJson(CODE_ERROR, $exception->getMessage());
        }
    }

    /**
     * Danh sách chi nhánh
     */
    public function listBranch(Request $request)
    {
        try {
            $param = $request->all();
            $data = $this->manageWorkRepo->listBranch($param);

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (\Exception | QueryException $exception) {
            return $this->responseJson(CODE_ERROR, $exception->getMessage());
        }
    }

    /**
     * Danh sách phòng ban
     */
    public function listDepartment(Request $request)
    {
        try {
            $param = $request->all();
            $data = $this->manageWorkRepo->listDepartment($param);

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (\Exception | QueryException $exception) {
            return $this->responseJson(CODE_ERROR, $exception->getMessage());
        }
    }

    /**
     * Tạo nhắc nhở
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function createReminder(RemindRequest $request)
    {
        try {
            $param = $request->all();
            $data = $this->manageWorkRepo->createReminder($param);

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (\Exception | QueryException $exception) {
            return $this->responseJson(CODE_ERROR, $exception->getMessage());
        }
    }

    /**
     * Danh sách công việc
     * @return \Illuminate\Http\JsonResponse
     */
    public function listWork(Request $request)
    {
        try {
            $param = $request->all();
            $data = $this->manageWorkRepo->listWork($param);

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (\Exception | QueryException $exception) {
            return $this->responseJson(CODE_ERROR, $exception->getMessage());
        }
    }


    /**
     * Danh sách công việc
     * @return \Illuminate\Http\JsonResponse
     */
    public function listWorkParent(Request $request)
    {
        try {
            $param = $request->all();
            $data = $this->manageWorkRepo->listWorkParent($param);

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (\Exception | QueryException $exception) {
            return $this->responseJson(CODE_ERROR, $exception->getMessage());
        }
    }

    /**
     * Chi tiết công việc
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function workDetail(WorkDetailRequest $request)
    {
        try {
            $param = $request->all();
            $data = $this->manageWorkRepo->workDetail($param);

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (\Exception | QueryException $exception) {
            return $this->responseJson(CODE_ERROR, $exception->getMessage());
        }
    }

    /**
     * Duyệt công việc
     * @param WorkDetailRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function workApprove(WorkDetailRequest $request)
    {
        try {
            $param = $request->all();
            $data = $this->manageWorkRepo->workApprove($param);

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (\Exception | QueryException $exception) {
            return $this->responseJson(CODE_ERROR, $exception->getMessage());
        }
    }

    /**
     * Danh sách comment
     * @param WorkDetailRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function listComment(WorkDetailRequest $request)
    {
        try {
            $param = $request->all();
            $data = $this->manageWorkRepo->listComment($param);

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (\Exception | QueryException $exception) {
            return $this->responseJson(CODE_ERROR, $exception->getMessage());
        }
    }

    /**
     * Tạo comment
     * @param WorkDetailRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function createdComment(WorkDetailRequest $request)
    {
        try {
            $param = $request->all();
            $data = $this->manageWorkRepo->createdComment($param);

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (\Exception | QueryException $exception) {
            return $this->responseJson(CODE_ERROR, $exception->getMessage());
        }
    }

    /**
     * Danh sách nhắc nhở
     * @param WorkDetailRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function listRemind(WorkDetailRequest $request)
    {
        try {
            $param = $request->all();
            $data = $this->manageWorkRepo->listRemind($param);

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (\Exception | QueryException $exception) {
            return $this->responseJson(CODE_ERROR, $exception->getMessage());
        }
    }

    /**
     * Danh sách file
     * @param WorkDetailRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function listFile(WorkDetailRequest $request)
    {
        try {
            $param = $request->all();
            $data = $this->manageWorkRepo->listFile($param);

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (\Exception | QueryException $exception) {
            return $this->responseJson(CODE_ERROR, $exception->getMessage());
        }
    }

    /**
     * Danh sách lịch sử
     * @param WorkDetailRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function listHistory(WorkDetailRequest $request)
    {
        try {
            $param = $request->all();
            $data = $this->manageWorkRepo->listHistory($param);

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (\Exception | QueryException $exception) {
            return $this->responseJson(CODE_ERROR, $exception->getMessage());
        }
    }

    /**
     * Thêm công việc
     * @param WorkAddRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function addWork(WorkAddRequest $request)
    {
        try {
            $param = $request->all();
            $data = $this->manageWorkRepo->addWork($param);

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (\Exception | QueryException $exception) {
            return $this->responseJson(CODE_ERROR, $exception->getMessage());
        }
    }

    /**
     * Chỉnh sửa công việc
     * @param WorkAddRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function editWork(WorkEditRequest $request)
    {
        try {
            $param = $request->all();
            $data = $this->manageWorkRepo->editWork($param);

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (\Exception | QueryException $exception) {
            return $this->responseJson(CODE_ERROR, $exception->getMessage());
        }
    }

    /**
     * Thêm dự án
     * @param ProjectAddRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function addProject(ProjectAddRequest $request)
    {
        try {
            $param = $request->all();
            $data = $this->manageWorkRepo->addProject($param);

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (\Exception | QueryException $exception) {
            return $this->responseJson(CODE_ERROR, $exception->getMessage());
        }
    }

    /**
     * Thêm loại công việc
     * @param TypeWorkAddRequest $request
     */
    public function addTypeWork(TypeWorkAddRequest $request)
    {
        try {
            $param = $request->all();
            $data = $this->manageWorkRepo->addTypeWork($param);

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (\Exception | QueryException $exception) {
            return $this->responseJson(CODE_ERROR, $exception->getMessage());
        }
    }

    /**
     * Danh sách tags
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function listTags(Request $request)
    {
        try {
            $param = $request->all();
            $data = $this->manageWorkRepo->listTags($param);

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (\Exception | QueryException $exception) {
            return $this->responseJson(CODE_ERROR, $exception->getMessage());
        }
    }

    /**
     * Danh sách nhân viên
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function listStaff(Request $request)
    {
        try {
            $param = $request->all();
            $data = $this->manageWorkRepo->listStaff($param);

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (\Exception | QueryException $exception) {
            return $this->responseJson(CODE_ERROR, $exception->getMessage());
        }
    }

    /**
     * Upload file
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function uploadFile(Request $request)
    {
        try {
            $param = $request->all();
            $data = $this->manageWorkRepo->uploadFile($param);

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (\Exception | QueryException $exception) {
            return $this->responseJson(CODE_ERROR, $exception->getMessage());
        }
    }

    /**
     * Danh sách hồ sơ
     * @param WorkDetailRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function listDocument(Request $request)
    {
        try {
            $param = $request->all();
            $data = $this->manageWorkRepo->listDocument($param);

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (\Exception | QueryException $exception) {
            return $this->responseJson(CODE_ERROR, $exception->getMessage());
        }
    }

    /**
     * Cập nhật file hồ sơ
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function uploadFileDocument(WorkDetailRequest $request)
    {
        try {
            $param = $request->all();
            $data = $this->manageWorkRepo->uploadFileDocument($param);

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (\Exception | QueryException $exception) {
            return $this->responseJson(CODE_ERROR, $exception->getMessage());
        }
    }

    /**
     * Cập nhật tag cho công việc
     * @param WorkDetailRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateWorkTag(WorkDetailRequest $request)
    {
        try {
            $param = $request->all();
            $data = $this->manageWorkRepo->updateWorkTag($param);

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (\Exception | QueryException $exception) {
            return $this->responseJson(CODE_ERROR, $exception->getMessage());
        }
    }

    /**
     * Danh sách tác vụ con
     * @param WorkDetailRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function listChildWork(WorkDetailRequest $request)
    {
        try {
            $param = $request->all();
            $data = $this->manageWorkRepo->listChildWork($param);

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (\Exception | QueryException $exception) {
            return $this->responseJson(CODE_ERROR, $exception->getMessage());
        }
    }

    /**
     * Chỉnh sửa lặp lại công việc
     * @param WorkDetailRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function editRepeatWork(RepeatWorkUpdateRequest $request)
    {
        try {
            $param = $request->all();
            $data = $this->manageWorkRepo->editRepeatWork($param);

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (\Exception | QueryException $exception) {
            return $this->responseJson(CODE_ERROR, $exception->getMessage());
        }
    }

    /**
     * Danh sách dự án
     */
    public function listProject(Request $request)
    {
        try {
            $param = $request->all();
            $data = $this->manageWorkRepo->listProject($param);

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (\Exception | QueryException $exception) {
            return $this->responseJson(CODE_ERROR, $exception->getMessage());
        }
    }

    /**
     * Việc của tôi quá hạn
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function myWorkSearchOverdue(Request $request)
    {
        try {
            $param = $request->all();
            $data = $this->manageWorkRepo->myWorkSearchOverdue($param);

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (\Exception | QueryException $exception) {
            return $this->responseJson(CODE_ERROR, $exception->getMessage());
        }
    }

    /**
     * Việc của tôi
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function myWorkSearch(Request $request)
    {
        try {
            $param = $request->all();
            $data = $this->manageWorkRepo->myWorkSearch($param);

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (\Exception | QueryException $exception) {
            return $this->responseJson(CODE_ERROR, $exception->getMessage());
        }
    }

    /**
     * Việc của tôi tab của tôi
     * @return \Illuminate\Http\JsonResponse
     */
    public function myWork(Request $request)
    {
        try {
            $param = $request->all();
            $param['tab_my_work'] = 1;
            $data = $this->manageWorkRepo->myWork($param);

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (\Exception | QueryException $exception) {
            return $this->responseJson(CODE_ERROR, $exception->getMessage());
        }
    }

    /**
     * Việc của tôi tab tôi giao
     * @return \Illuminate\Http\JsonResponse
     */
    public function myAssignWork()
    {
        try {
            $data = $this->manageWorkRepo->myAssignWork();

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (\Exception | QueryException $exception) {
            return $this->responseJson(CODE_ERROR, $exception->getMessage());
        }
    }

    /**
     * Danh sách nhắc nhở của tôi
     * @return \Illuminate\Http\JsonResponse
     */
    public function myRemindWork(Request $request)
    {
        try {
            $param = $request->all();
            $data = $this->manageWorkRepo->myRemindWork($param);

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (\Exception | QueryException $exception) {
            return $this->responseJson(CODE_ERROR, $exception->getMessage());
        }
    }

    /**
     * Xoá nhắc nhở
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteRemind(RemindDetailRequest $request)
    {
        try {
            $param = $request->all();
            $data = $this->manageWorkRepo->deleteRemind($param);

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (\Exception | QueryException $exception) {
            return $this->responseJson(CODE_ERROR, $exception->getMessage());
        }
    }

    /**
     * danh sách trạng thái
     */
    public function listStatus()
    {
        try {
            $data = $this->manageWorkRepo->listStatus();

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (\Exception | QueryException $exception) {
            return $this->responseJson(CODE_ERROR, $exception->getMessage());
        }
    }

    /**
     * Xoá bình luận
     * @param Request $request
     */
    public function deleteComment(CommentDetailRequest $request)
    {
        try {
            $param = $request->all();
            $data = $this->manageWorkRepo->deleteComment($param);

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (\Exception | QueryException $exception) {
            return $this->responseJson(CODE_ERROR, $exception->getMessage());
        }
    }

    /**
     * Cập nhật nhân viên liên quan
     * @param WorkDetailRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateStaffSupport(WorkDetailRequest $request)
    {
        try {
            $param = $request->all();
            $data = $this->manageWorkRepo->updateStaffSupport($param);

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (\Exception | QueryException $exception) {
            return $this->responseJson(CODE_ERROR, $exception->getMessage());
        }
    }

    /**
     * Xoá công việc
     * @param WorkDetailRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteWork(WorkDetailRequest $request)
    {
        try {
            $param = $request->all();
            $data = $this->manageWorkRepo->deleteWork($param);

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (\Exception | QueryException $exception) {
            return $this->responseJson(CODE_ERROR, $exception->getMessage());
        }
    }

    /**
     * Danh sách loại công việc
     * @return \Illuminate\Http\JsonResponse
     */
    public function listTypeWork()
    {
        try {
            $data = $this->manageWorkRepo->listTypeWork();

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (\Exception | QueryException $exception) {
            return $this->responseJson(CODE_ERROR, $exception->getMessage());
        }
    }

    /**
     * Cập nhật nhanh công việc
     * @param WorkDetailRequest $request
     */
    public function quickUpdateWork(WorkDetailRequest $request)
    {
        try {
            $param = $request->all();
            $data = $this->manageWorkRepo->quickUpdateWork($param);

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (\Exception | QueryException $exception) {
            return $this->responseJson(CODE_ERROR, $exception->getMessage());
        }
    }

    /**
     * Danh sách khách hàng
     */
    public function listCustomer(Request $request)
    {
        try {
            $param = $request->all();
            $data = $this->manageWorkRepo->listCustomer($param);

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (\Exception | QueryException $exception) {
            return $this->responseJson(CODE_ERROR, $exception->getMessage());
        }
    }

    /**
     * Tạo tag mới
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function addTag(TagAddRequest $request)
    {
        try {
            $param = $request->all();
            $data = $this->manageWorkRepo->addTag($param);

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (\Exception | QueryException $exception) {
            return $this->responseJson(CODE_ERROR, $exception->getMessage());
        }
    }

    /**
     * Xoá hình ảnh
     * @param DocumentFileUploadRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteDocumentFile(DocumentFileUploadRequest $request)
    {
        try {
            $param = $request->all();
            $data = $this->manageWorkRepo->deleteDocumentFile($param);

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (\Exception | QueryException $exception) {
            return $this->responseJson(CODE_ERROR, $exception->getMessage());
        }
    }

    /**
     * Danh sách công việc cần duyệt
     * @param Request $request
     */
    public function getListWorkApprove(Request $request)
    {
        try {
            $param = $request->all();
            $data = $this->manageWorkRepo->getListWorkApprove($param);

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (\Exception | QueryException $exception) {
            return $this->responseJson(CODE_ERROR, $exception->getMessage());
        }
    }

    /**
     * gửi noti công việc
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function staffNotification(Request $request)
    {
        try {
            $param = $request->all();

            $data = $this->manageWorkRepo->staffNotification($param);

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (\Exception | QueryException $exception) {
            return $this->responseJson(CODE_ERROR, $exception->getMessage());
        }
    }

    /**
     * Loại khách hàng
     * @return \Illuminate\Http\JsonResponse
     */
    public function typeCustomer()
    {
        try {
            $data = $this->manageWorkRepo->typeCustomer();

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (\Exception | QueryException $exception) {
            return $this->responseJson(CODE_ERROR, $exception->getMessage());
        }
    }

    /**
     * Lấy danh sách phòng ban
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getDepartment()
    {
        try {
            $data = $this->manageWorkRepo->getDepartment();

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (\Exception | QueryException $exception) {
            return $this->responseJson(CODE_ERROR, $exception->getMessage());
        }
    }

    /**
     * Thêm vị trí công việc
     *
     * @param CreateLocationRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function createLocation(CreateLocationRequest $request)
    {
        try {
            $data = $this->manageWorkRepo->createLocation($request->all());

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (\Exception | QueryException $exception) {
            return $this->responseJson(CODE_ERROR, $exception->getMessage());
        }
    }

    /**
     * Lấy vị trí làm việc của công việc
     *
     * @param ListLocationRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function listLocation(ListLocationRequest $request)
    {
        try {
            $data = $this->manageWorkRepo->listLocation($request->all());

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (\Exception | QueryException $exception) {
            return $this->responseJson(CODE_ERROR, $exception->getMessage());
        }
    }

    /**
     * Xoá toạ độ
     *
     * @param DestroyLocationRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function removeLocation(DestroyLocationRequest $request)
    {
        try {
            $data = $this->manageWorkRepo->removeLocation($request->all());

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (\Exception | QueryException $exception) {
            return $this->responseJson(CODE_ERROR, $exception->getMessage());
        }
    }

    /**
     * Tổng quan công việc (V2)
     *
     * @param JobOverViewRequest $request
     * @return JsonResponse
     */
    public function jobOverviewV2(JobOverViewRequest $request)
    {
        try {
            $data = $this->manageWorkRepo->jobOverViewV2($request->all());

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (\Exception | QueryException $exception) {
            return $this->responseJson(CODE_ERROR, $exception->getMessage());
        }
    }

    /**
     * Danh sách trạng thái (màn hình bộ lọc)
     *
     * @return JsonResponse
     */
    public function listStatusV2()
    {
        try {
            $data = $this->manageWorkRepo->listStatusV2();

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (\Exception | QueryException $exception) {
            return $this->responseJson(CODE_ERROR, $exception->getMessage());
        }
    }
}
