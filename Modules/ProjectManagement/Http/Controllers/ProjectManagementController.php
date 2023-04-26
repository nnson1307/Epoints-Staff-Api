<?php

namespace Modules\ProjectManagement\Http\Controllers;

use Illuminate\Http\Request;
use Modules\ProjectManagement\Http\Requests\AddDocumentRequest;
use Modules\ProjectManagement\Http\Requests\DocumentIDRequest;
use Modules\ProjectManagement\Repositories\Project\ProjectInterface;
use Modules\ProjectManagement\Http\Requests\ProjectRequest;
use Modules\ProjectManagement\Http\Requests\EditProjectRequest;
use Modules\ProjectManagement\Http\Requests\ProjectIdRequestNew;
use Modules\ProjectManagement\Http\Requests\AddMemberRequest;
use Modules\ProjectManagement\Http\Requests\EditMemberRequest;
use Modules\ProjectManagement\Http\Requests\AddIssueRequest;
use Modules\ProjectManagement\Http\Requests\AddCommentRequest;
use Modules\ProjectManagement\Http\Requests\RemindRequest;
use Modules\ProjectManagement\Repositories\Project\ProjectRepoException;

class ProjectManagementController extends Controller
{
    protected $manageProject;

    public function __construct(
        ProjectInterface $manageProject
    )
    {
        $this->manageProject = $manageProject;
    }

    //trang thai
    public function getStatus()
    {
        try {
            $data = $this->manageProject->getStatus();
            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (ProjectRepoException $ex) {
            return $this->responseJson(CODE_ERROR, $ex->getMessage());
        }
    }

    //nguoi quan tri
    public function getManage()
    {
        try {
            $data = $this->manageProject->getManage();
            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (ProjectRepoException $ex) {
            return $this->responseJson(CODE_ERROR, $ex->getMessage());
        }

    }

    //phong ban
    public function getDepartment()
    {
        try {
            $data = $this->manageProject->getDepartment();
            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (ProjectRepoException $ex) {
            return $this->responseJson(CODE_ERROR, $ex->getMessage());
        }
    }

    //ds nhan vien
    public function getStaffs(Request $request)
    {
        try {
            $data = $this->manageProject->getStaff($request->all());
            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (ProjectRepoException $ex) {
            return $this->responseJson(CODE_ERROR, $ex->getMessage());
        }
    }

    //danh sach chuc vu
    public function getStaffTitle(Request $request)
    {
        try {
            $data = $this->manageProject->getStaffTitle($request->all());
            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (ProjectRepoException $ex) {
            return $this->responseJson(CODE_ERROR, $ex->getMessage());
        }
    }

    //loai khach hang
    public function getCustomerType()
    {
        try {
            $data = [
                0 => [
                    "type_id" => 1,
                    "type_name" => "Cá nhân",
                ],
                1 => [
                    "type_id" => 2,
                    "type_name" => "Doanh nghiệp",
                ]
            ];
            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (ProjectRepoException $ex) {
            return $this->responseJson(CODE_ERROR, $ex->getMessage());
        }
    }

    //ds khach hang
    public function getCustomer(Request $request)
    {
        try {
            $data = $this->manageProject->getCustomer($request->all());
            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (ProjectRepoException $ex) {
            return $this->responseJson(CODE_ERROR, $ex->getMessage());
        }
    }

    //tag
    public function getTag()
    {
        try {
            $data = $this->manageProject->getTag();
            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (ProjectRepoException $ex) {
            return $this->responseJson(CODE_ERROR, $ex->getMessage());
        }
    }

    //ds chi nhanh
    public function getBranch()
    {
        try {
            $data = $this->manageProject->getBranch();
            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (ProjectRepoException $ex) {
            return $this->responseJson(CODE_ERROR, $ex->getMessage());
        }
    }

    //quyen truy cap
    public function getPermission()
    {

        try {
            $data = [
                0 => [
                    "permission_code" => "private",
                    "permission_name" => "Nội bộ",
                ],
                1 => [
                    "permission_code" => "public",
                    "permission_name" => "Công khai",

                ]
            ];
            $dataPermission = [
                "data " => $data,
            ];

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (ProjectRepoException $ex) {
            return $this->responseJson(CODE_ERROR, $ex->getMessage());
        }
    }

    //vai tro
    public function getRole()
    {
        try {
            $data = $this->manageProject->getRole();
            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (ProjectRepoException $ex) {
            return $this->responseJson(CODE_ERROR, $ex->getMessage());
        }
    }

    ///danh sách loại công việc
    public function getTypeWork()
    {

        try {
            $data = $this->manageProject->getTypeWork();
            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (ProjectRepoException $ex) {
            return $this->responseJson(CODE_ERROR, $ex->getMessage());
        }
    }
    ///danh sách loại công việc
    public function listContract()
    {

        try {
            $data = $this->manageProject->getListContract();
            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (ProjectRepoException $ex) {
            return $this->responseJson(CODE_ERROR, $ex->getMessage());
        }
    }

    //them du an
    public function addProject(ProjectRequest $request)
    {
        try {
            // thuc hien tao project moi
            $data = $this->manageProject->createdProject($request->all());
            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (ProjectRepoException $ex) {
            return $this->responseJson(CODE_ERROR, $ex->getMessage());
        }

    }

    //danh sach du an
    public function listProject(Request $request)
    {
        try {
            $data = $this->manageProject->listProject($request->all());
            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (ProjectRepoException $ex) {
            return $this->responseJson(CODE_ERROR, $ex->getMessage());
        }
    }

    //chinh sua du an
    public function editProject(EditProjectRequest $request)
    {
        try {
            $data = $this->manageProject->editProject($request->all());
            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (ProjectRepoException $ex) {
            return $this->responseJson(CODE_ERROR, $ex->getMessage());
        }

    }

    //xoa du an
    public function deleteProject(ProjectIdRequestNew $request)
    {
        try {
            $data = $this->manageProject->deleteProject($request->all());
            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (ProjectRepoException $ex) {
            return $this->responseJson(CODE_ERROR, $ex->getMessage());
        }
    }

    //trang thai xoa du an
    public function isDelete(ProjectIdRequestNew $request)
    {
        try {
            $data = $this->manageProject->isDelete($request->all());
            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (ProjectRepoException $ex) {
            return $this->responseJson(CODE_ERROR, $ex->getMessage());
        }
    }

    //thong tin du an
    public function projectInfo(ProjectIdRequestNew $request)
    {
        try {
            $data = $this->manageProject->projectInfo($request->all());
            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (ProjectRepoException $ex) {
            return $this->responseJson(CODE_ERROR, $ex->getMessage());
        }
    }

    ///tab phân tích lượng tổng thành viên-công việc-đang thực hiện-chưa thực hiện-hoàn thành-đã đóng-quán hạn
    public function statisticalTab(Request $request)
    {
        try {
            $dataStatus = $this->manageProject->getStatus();
            $data = [
                [
                    "manage_project_id" => 16,
                    "statistical_type" => "member",
                    "status_id" => 0,
                    "tab_name" => "Tổng thành viên",
                ],
                [
                    "manage_project_id" => 16,
                    "statistical_type" => "work",
                    "status_id" => 0,
                    "tab_name" => "Tổng công việc",
                ]
            ];
            foreach ($dataStatus as $item) {
                $data[] = [
                    "manage_project_id" => 16,
                    "statistical_type" => "work",
                    "status_id" => $item['manage_project_status_id'],
                    "tab_name" => $item['manage_project_status_name'],
                ];
            }
            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (ProjectRepoException $ex) {
            return $this->responseJson(CODE_ERROR, $ex->getMessage());
        }
    }

    //số lượng tổng thành viên-công việc-đang thực hiện-chưa thực hiện-hoàn thành-đã đóng-quán hạn
    public function statistical(Request $request)
    {
        try {
            $input = $request->all();
            $data = $this->manageProject->getDataStatictical($input);
            if (count($data) > 0) {
                if ($input['statistical_type'] == 'member') {
                    $a = $data['member_detail'];
                    $b = [
                        'member_position' => 'total',
                        'member_amount' => $data['member_total'],
                        'color' => '#33CCCC'

                    ];
                    $a[] = $b;
                    $data = $a;
                    foreach ($data as $k => $v) {
                        $v['name'] = $v['member_position'];
                        $v['amount'] = $v['member_amount'];
                        unset($v['member_position']);
                        unset($v['member_amount']);
                        $data[$k] = $v;
                    }
                } else {
                    foreach ($data as $k => $v) {
                        $v['name'] = $v['department_name'];
                        $v['amount'] = $v['work_amount'];
                        unset($v['department_name']);
                        unset($v['work_amount']);
                        $data[$k] = $v;
                    }
                }
            }
            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (ProjectRepoException $ex) {
            return $this->responseJson(CODE_ERROR, $ex->getMessage());
        }
    }

    //trạng thái dự án
    public function projectStatus(Request $request)
    {
        try {
            $input = $request->all();
            $data = $this->manageProject->updateStatusProject($input);
            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (ProjectRepoException $ex) {
            return $this->responseJson(CODE_ERROR, $ex->getMessage());
        }
    }

    //lich su hoat dong
    public function activitiesHistory(ProjectIdRequestNew $request)
    {
        try {
            $data = $this->manageProject->getActivities($request->all());
            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (ProjectRepoException $ex) {
            return $this->responseJson(CODE_ERROR, $ex->getMessage());
        }
    }

    //danh sach tai lieu
    public function listDocument(ProjectIdRequestNew $request)
    {
        try {
            $data = $this->manageProject->getListDocument($request->all());
            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (ProjectRepoException $ex) {
            return $this->responseJson(CODE_ERROR, $ex->getMessage());
        }
    }

    //thêm tai lieu
    public function addDocument(AddDocumentRequest $request)
    {
        try {
            $data = $this->manageProject->addDocument($request->all());
            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (ProjectRepoException $ex) {
            return $this->responseJson(CODE_ERROR, $ex->getMessage());
        }
    }

    //xóa tai lieu
    public function deleteDocument(DocumentIDRequest $request)
    {
        try {
            $data = $this->manageProject->deleteDocument($request->all());
            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (ProjectRepoException $ex) {
            return $this->responseJson(CODE_ERROR, $ex->getMessage());
        }
    }

    //thanh vien du an
    public function memberProject(ProjectIdRequestNew $request)
    {
        try {
            $data = $this->manageProject->getListMem($request->all());
            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (ProjectRepoException $ex) {
            return $this->responseJson(CODE_ERROR, $ex->getMessage());
        }

    }

    //them thanh vien du an
    public function addMember(AddMemberRequest $request)
    {
        try {
            $data = $this->manageProject->addMem($request->all());
            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (ProjectRepoException $ex) {
            return $this->responseJson(CODE_ERROR, $ex->getMessage());
        }
    }

    //chinh sua thanh vien
    public function editMember(EditMemberRequest $request)
    {
        try {
            $data = $this->manageProject->editMem($request->all());
            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (ProjectRepoException $ex) {
            return $this->responseJson(CODE_ERROR, $ex->getMessage());
        }
    }

    //xoa thanh vien
    public function deleteMember(EditMemberRequest $request)
    {
        try {
            $data = $this->manageProject->deleteMem($request->all());
            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (ProjectRepoException $ex) {
            return $this->responseJson(CODE_ERROR, $ex->getMessage());
        }
    }

    //thông tin báo cáo
    public function reportInformation(ProjectIdRequestNew $request)
    {
        try {
            $input = $request->all();
            $data = $this->manageProject->getInfoReport($request->all());
            $inputStatisWork = [
                'manage_project_id' => $input['manage_project_id'],
                'statistical_type' => 'work'
            ];
            $dataStatisWork = $this->manageProject->getDataStatictical($inputStatisWork);
            $inputStatisMember = [
                'manage_project_id' => $input['manage_project_id'],
                'statistical_type' => 'member'
            ];
            $dataStatisMember = $this->manageProject->getDataStatictical($inputStatisMember);
            $data['overview_report'] = $dataStatisWork ? $dataStatisWork : [];
            $data['member_report'] = $dataStatisMember ? $dataStatisMember : [];
            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (ProjectRepoException $ex) {
            return $this->responseJson(CODE_ERROR, $ex->getMessage());
        }
    }

    //danh sách công việc
    public function workList(ProjectIdRequestNew $request)
    {
        try {
            $input = $request->all();
            $data = $this->manageProject->getWorkList($input);
            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (ProjectRepoException $ex) {
            return $this->responseJson(CODE_ERROR, $ex->getMessage());
        }
    }

    //danh sách chi tiết giai đoạn
    public function phaseDetail(ProjectIdRequestNew $request)
    {
        try {
            $data = $this->manageProject->getDataPhase($request->all());
            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (ProjectRepoException $ex) {
            return $this->responseJson(CODE_ERROR, $ex->getMessage());
        }
    }

    //danh sách phiếu thu-chi
    public function listExpenditure(ProjectIdRequestNew $request)
    {
        try {
            $data = $this->manageProject->getListExpenditure($request->all());
            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (ProjectRepoException $ex) {
            return $this->responseJson(CODE_ERROR, $ex->getMessage());
        }
    }

    ///thêm vấn đề dự án
    public function addIssue(AddIssueRequest $request)
    {
        try {
            $input = $request->all();
            $data = $this->manageProject->addIssue($input);
            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (ProjectRepoException $ex) {
            return $this->responseJson(CODE_ERROR, $ex->getMessage());
        }
    }

    ///danh sách vấn đề dự án
    public function listIssue(ProjectIdRequestNew $request)
    {
        try {
            $input = $request->all();
            $data = $this->manageProject->listIssue($input);
            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (ProjectRepoException $ex) {
            return $this->responseJson(CODE_ERROR, $ex->getMessage());
        }
    }

    ///thêm bình luận
    public function addComment(AddCommentRequest $request)
    {
        try {
            $input = $request->all();
            $data = $this->manageProject->addComment($input);
            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (ProjectRepoException $ex) {
            return $this->responseJson(CODE_ERROR, $ex->getMessage());
        }
    }

    ///danh sách lịch sử bình luận
    public function historyComment(ProjectIdRequestNew $request)
    {
        try {
            $input = $request->all();
            $data = $this->manageProject->getHistoryComment($input);
            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (ProjectRepoException $ex) {
            return $this->responseJson(CODE_ERROR, $ex->getMessage());
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
            $data = $this->manageProject->createReminder($param);
            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (ProjectRepoException $ex) {
            return $this->responseJson(CODE_ERROR, $ex->getMessage());
        }
    }

    /**
     * Danh sách nhắc nhở
     * @param ProjectIdRequestNew $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function listRemind(ProjectIdRequestNew $request)
    {
        try {
            $param = $request->all();
            $data = $this->manageProject->listRemind($param);

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (\ProjectRepoException $ex) {
            return $this->responseJson(CODE_ERROR, $ex->getMessage());
        }
    }

}
