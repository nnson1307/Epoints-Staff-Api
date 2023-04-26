<?php


namespace Modules\ProjectManagement\Repositories\Project;


use Modules\ManageWork\Models\CustomersTable;
use Modules\ProjectManagement\Models\ManageTypeWorkTable;
use Modules\ProjectManagement\Models\ManageProjectHistoryTable;
use  Modules\ProjectManagement\Models\ManageProjectStatusTable;
use Modules\ProjectManagement\Models\ProjectMemberTable;
use  Modules\ProjectManagement\Models\StaffTable;
use  Modules\ProjectManagement\Models\DepartmentTable;
use  Modules\ProjectManagement\Models\CustomerTable;
use  Modules\ProjectManagement\Models\TagTable;
use  Modules\ProjectManagement\Models\ProjectTable;
use  Modules\ProjectManagement\Models\WorkTable;
use  Modules\ProjectManagement\Models\ProjectTagTable;
use  Modules\ProjectManagement\Models\ProjectDocumentTable;
use  Modules\ProjectManagement\Models\BranchTable;
use  Modules\ProjectManagement\Models\ProjectStaffTable;
use  Modules\ProjectManagement\Models\ManageCommentTable;
use  Modules\ProjectManagement\Models\ProjectPhaseTable;
use  Modules\ProjectManagement\Models\ProjectIssueTable;
use  Modules\ProjectManagement\Models\ProjectRoleTable;
use  Modules\ProjectManagement\Models\ProjectExpenditureTable;
use  Modules\ProjectManagement\Models\ProjectCommentTable;
use  Modules\ProjectManagement\Models\ReceiptTable;
use  Modules\ProjectManagement\Models\PaymentTable;
use  Modules\ProjectManagement\Models\ManageRemindTable;
use  Modules\ProjectManagement\Models\ManageHistoryTable;
use  Modules\ProjectManagement\Models\ManageConfigNotificationTable;
use  Modules\ProjectManagement\Models\ManageDocumentFileTable;
use  Modules\ProjectManagement\Models\ManageWorkSupportTable;
use  Modules\ProjectManagement\Models\StaffTitleTable;
use  Modules\ProjectManagement\Models\ContractTable;

use MyCore\Repository\PagingTrait;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Collection;
use Modules\Notification\Repositories\Notification\NotificationRepo;
use Aws\S3\S3Client;
use Illuminate\Support\Facades\Log;
use GuzzleHttp\Client;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;

class ProjectRepo implements ProjectInterface
{
    use PagingTrait;

    /**
     * Random mã màu
     * @return string
     */
    public function randColor()
    {
        return '#' . str_pad(dechex(mt_rand(0, 0xFFFFFF)), 6, '0', STR_PAD_LEFT);
    }

    function convert_vi_to_en($str)
    {
        $str = preg_replace("/(à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ)/", "a", $str);
        $str = preg_replace("/(è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ)/", "e", $str);
        $str = preg_replace("/(ì|í|ị|ỉ|ĩ)/", "i", $str);
        $str = preg_replace("/(ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ)/", "o", $str);
        $str = preg_replace("/(ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ)/", "u", $str);
        $str = preg_replace("/(ỳ|ý|ỵ|ỷ|ỹ)/", "y", $str);
        $str = preg_replace("/(đ)/", "d", $str);
        $str = preg_replace("/(À|Á|Ạ|Ả|Ã|Â|Ầ|Ấ|Ậ|Ẩ|Ẫ|Ă|Ằ|Ắ|Ặ|Ẳ|Ẵ)/", "A", $str);
        $str = preg_replace("/(È|É|Ẹ|Ẻ|Ẽ|Ê|Ề|Ế|Ệ|Ể|Ễ)/", "E", $str);
        $str = preg_replace("/(Ì|Í|Ị|Ỉ|Ĩ)/", "I", $str);
        $str = preg_replace("/(Ò|Ó|Ọ|Ỏ|Õ|Ô|Ồ|Ố|Ộ|Ổ|Ỗ|Ơ|Ờ|Ớ|Ợ|Ở|Ỡ)/", "O", $str);
        $str = preg_replace("/(Ù|Ú|Ụ|Ủ|Ũ|Ư|Ừ|Ứ|Ự|Ử|Ữ)/", "U", $str);
        $str = preg_replace("/(Ỳ|Ý|Ỵ|Ỷ|Ỹ)/", "Y", $str);
        $str = preg_replace("/(Đ)/", "D", $str);
        //$str = str_replace(" ", "-", str_replace("&*#39;","",$str));
        return $str;
    }

    public function getStatus()
    {
        try {
            $mStatus = app()->get(ManageProjectStatusTable::class);
            $data = $mStatus->getStatus();
            return $data;
        } catch (\Exception $exception) {
            throw new ProjectRepoException(ProjectRepoException::GET_STATUS);
        }
    }

    public function getManage()
    {
        try {
            $mManage = app()->get(StaffTable::class);
            $data = $mManage->getManage();
            return $data;
        } catch (\Exception $exception) {
            throw new ProjectRepoException(ProjectRepoException::GET_MANAGE);
        }
    }

    public function getRole()
    {
        try {
            $role = app()->get(ProjectRoleTable::class);
            $data = $role->getRole();
            return $data;
        } catch (\Exception $exception) {
            throw new ProjectRepoException(ProjectRepoException::GET_ROLE);
        }
    }

    public function getStaffTitle()
    {
        try {
            $staffTitle = app()->get(StaffTitleTable::class);
            $data = $staffTitle->getStaffTitle();
            return $data;
        } catch (\Exception $exception) {
            throw new ProjectRepoException(ProjectRepoException::GET_ROLE);
        }
    }

    public function getDepartment()
    {
        try {
            $mDepartment = app()->get(DepartmentTable::class);
            $data = $mDepartment->getDepartment();
            return $data;
        } catch (\Exception $exception) {
            throw new ProjectRepoException(ProjectRepoException::GET_DEPARTMENT);
        }
    }

    public function getCustomer($input)
    {
        try {

            $mCustomer = app()->get(CustomerTable::class);
            $data = $mCustomer->getCustomer($input);
            return $this->toPagingData($data);
        } catch (\Exception $exception) {
            throw new ProjectRepoException(ProjectRepoException::GET_CUSTOMER);
        }
    }

    public function getTag()
    {
        try {
            $mTag = app()->get(TagTable::class);
            $data = $mTag->getTag();
            return $data;
        } catch (\Exception $exception) {
            throw new ProjectRepoException(ProjectRepoException::GET_TAG);
        }
    }

    public function getBranch()
    {
        try {
            $branch = app()->get(BranchTable::class);
            $data = $branch->getBranch();
            return $data;
        } catch (\Exception $exception) {
            throw new ProjectRepoException(ProjectRepoException::GET_BRANCH);
        }
    }

    public function getStaff($filter)
    {
        try {
            $staff = app()->get(StaffTable::class);
            $data = $staff->getManage($filter);
            return $data;

        } catch (\Exception $exception) {
            throw new ProjectRepoException(ProjectRepoException::GET_STAFF);
        }
    }

    public function getTypeWork()
    {
        try {
            $typeWork = app()->get(ManageTypeWorkTable::class);
            $data = $typeWork->getAll();
            return $data;

        } catch (\Exception $exception) {
            throw new ProjectRepoException(ProjectRepoException::GET_STAFF);
        }
    }
    public function getListContract( $filter = [])
    {
        try {
            $mContract = app()->get(ContractTable::class);
            $data = $mContract->getListContract($filter);
            return $data;

        } catch (\Exception $exception) {
            throw new ProjectRepoException(ProjectRepoException::GET_LIST_CONTRACT);
        }
    }

    public function createdProject($input)
    {
        try {

            $mAddProject = app()->get(ProjectTable::class);
            $mAddTag = app()->get(ProjectTagTable::class);
            $mAddDocument = app()->get(ProjectDocumentTable::class);
            $mProjectPhase= app()->get(ProjectPhaseTable::class);
            $mProjectStaff= app()->get(ProjectStaffTable::class);

            $manage_project_status_id = null;
            $date_start = null;
            $date_end = null;

            if (isset($input['project_status_id']) && $input['project_status_id'] != "") {
                $manage_project_status_id = $input['project_status_id'];
            };
            if (isset($input['date_start']) && $input['date_start'] != "") {
                $date_start = Carbon::createFromFormat('d/m/Y', $input['date_start'])->format('Y-m-d');
            };
            if (isset($input['date_end']) && $input['date_end'] != "") {
                $date_end = Carbon::createFromFormat('d/m/Y', $input['date_end'])->format('Y-m-d');
            };
            //prefix_code
            $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $projectName = $this->convert_vi_to_en($input['project_name']);
            $a = explode(' ', $projectName);
            if (count($a) > 1) {
                $prefixCode = $a[0][0] . $a[1][0] . substr(str_shuffle($permitted_chars), 0, 2);
            } else {
                $prefixCode = $a[0][0] . $a[0][1] . substr(str_shuffle($permitted_chars), 0, 2);
            }
            $dataProject = [
                'manage_project_name' => $input['project_name'],//
                'manage_project_describe' => $input['project_describe'],
                'manage_project_status_id' => $manage_project_status_id,
                'date_start' => $date_start,
                'date_end' => $date_end,
                'created_at' => Carbon::now(),
                'created_by' => Auth()->id(),
                'updated_at' => Carbon::now(),
                'updated_by' => Auth()->id(),
                'manager_id' => $input['manager_id'] ? $input['manager_id'] : null,//
                'department_id' => $input['department_id'] ? $input['department_id'] : null,//
                'customer_type' => $input['customer_type'] ? $input['customer_type'] : null,
                'customer_id' => $input['customer_id'] ? $input['customer_id'] : null,
                'color_code' => $input['color_code'] ? $input['color_code'] : null,
                'permission' => $input['permission'],//
                'prefix_code' => $prefixCode,//
                'budget' => $input['budget'] ? $input['budget'] : null,
                'contract_code' => $input['contract_code'] ? $input['contract_code'] : null,
                'is_active' => 1,
                'is_deleted' => 0,
            ];
            ///tạo dự án mới
            $id = $mAddProject->addProject($dataProject);
            //them quan tri
            $addManagerToProjectStaff = $mProjectStaff->addMem([
                'manage_project_id' => $id,
                'staff_id' => $input['manager_id'] ? $input['manager_id'] : null,
                'manage_project_role_id' => 1,
                'created_at' => Carbon::now(),
                'created_by' => Auth()->id()
            ]);

            if (isset($input['tag']) && $input['tag'] != '') {
                foreach ($input['tag'] as $key => $value) {
                    $mAddTag->createdTag([
                        'tag_id' => $value['tag_id'],
                        'manage_project_id' => $id,
                        'created_at' => Carbon::now(),
                        'created_by' => Auth()->id()
                    ]);
                }
            }
            if (isset($input['manage_project_documents']) && $input['manage_project_documents'] != '') {
                foreach ($input['manage_project_documents'] as $key => $value) {
                    $mAddDocument->AddDocument([
                        'file_name' => $value['file_name'],
                        'manage_project_id' => $id,
                        'type' => $value['type'],
                        'created_at' => Carbon::now(),
                        'created_by' => Auth()->id(),
                    ]);
                }
            }

            $data = [
                'manage_project_id' => $id,
                'new' => $input['project_name'],
                'key' => 'created'
            ];

            $this->checkDataCreateHistory($id, $data);
            //tao gai doan mac dinh cho du an
            $idProject = [
                'manage_project_id' => $id
            ];
            $infoProjectJustAdd = $mAddProject->projectInfo($idProject);

            $dataDefaultPhase = [
                'manage_project_id' => $id,
                'name' => 'Phase Default',
                'date_start' => $infoProjectJustAdd['from_date'],
                'date_end' => $infoProjectJustAdd['to_date'],
                'is_default' => 1,
                'pic' => Auth()->id(),
                'is_deleted' => 0,
                'status' => 'new',
                'created_at' => Carbon::now(),
                'created_by' => Auth()->id(),
            ];
            $addDefaultPhase = $mProjectPhase->addDefaultPhase($dataDefaultPhase);

            return [
                'manage_project_id' => $id
            ];
        } catch (\Exception $exception) {
            throw new ProjectRepoException(ProjectRepoException::ADD_PROJECT, $exception->getMessage() . $exception->getLine());
        }
    }

    public function listProject($input)
    {
        try {
            $mListProject = app()->get(ProjectTable::class);
            $mWork = app()->get(WorkTable::class);
            $staff = app()->get(StaffTable::class);
            $mCustomer = app()->get(CustomerTable::class);
            $mDocument = app()->get(ProjectDocumentTable::class);
            $memberProject = app()->get(ProjectStaffTable::class);
            $mTags = app()->get(ProjectTagTable::class);

            $data = $mListProject->listProject($input);

            $dataArr = collect($data)->toArray();

            $collectionId = collect($dataArr['data']);


            /// danh sách thông tin manager
            $arrIdManager = $collectionId->pluck('manager_id');
            $a = [];
            foreach ($arrIdManager as $key => $value) {
                $a = array_merge($a, explode(',', $value));
            }
            $filter['arrIdManager'] = $a;
            $listManager = $staff->getInfoManager($filter);
            $listManager = collect($listManager)->keyBy('manager_id');
            ////  danh sách thông tin customer
            $arrIdCustomer = $collectionId->pluck('customer_id');
            $filter['arrIdCustomer'] = $arrIdCustomer;
            $listCustomer = $mCustomer->getCustomerAll($filter);
            $listCustomer = collect($listCustomer)->keyBy('customer_id');
            /// số lượng tài liệu
            $arrIdProject = $collectionId->pluck('project_id');
            $filter['arrIdProject'] = $arrIdProject;
            $numberDocument = $mDocument->getNumberDocument($filter);
            $numberDocument = collect($numberDocument)->keyBy('manage_project_id');
            /// % tiến độ công việc
            $totalWork = $mWork->getTotalWork($filter);
            $totalWorkComplete = $mWork->getTotalWorkComplete($filter);
            $totalWork = collect($totalWork)->keyBy('manage_project_id');
            $totalWorkComplete = collect($totalWorkComplete)->keyBy('manage_project_id');
            /// số thành viên dự án
            $totalMember = $memberProject->getMemberProject($filter);
            $totalMember = collect($totalMember)->keyBy('manage_project_id');
            /// tag
            $tag = $mTags->getTagProject($filter);
            $tag = collect($tag)->groupBy('project_id');
            //danh sách công việc của dự án
            $listWork = $mWork->getAllWork($filter);
            $listWork = collect($listWork)->groupBy('project_id');

            foreach ($data as $key => $value) {
                //mức độ quan trọng
                if ($value['is_important'] == 1) {
                    $value['important_name'] = "Quan trọng";
                } else {
                    $value['important_name'] = "Bình thường";
                }
                ///nguồn lực(tính ra giờ)
                if (isset($listWork[$value['project_id']])) {
                    $value['resource_implement'] = 0;
                    foreach ($listWork[$value['project_id']] as $item) {
                        if ($item['time_type'] == "h") {
                            $value['resource_implement'] = $value['resource_implement'] + $item['time'];
                        } else {
                            $value['resource_implement'] = $value['resource_implement'] + $item['time'] * 24;
                        }
                    }
                } else {
                    $value['resource_implement'] = 0;
                }
                if (isset($listWork[$value['project_id']]) && isset($value['to_date'])) {
                    ///tình trạng dự án
                    $arrDateEndWork = collect($listWork[$value['project_id']])->pluck('date_finish')->toArray();
                    if (in_array(null, $arrDateEndWork)) {
                        $maxDateEndWork = Carbon::now()->format('Y-m-d');
                    } else {
                        $maxDateEndWork = Carbon::createFromFormat('Y-m-d H:i:s', max($arrDateEndWork))->format('Y-m-d');
                    }
                    if ($maxDateEndWork > $value['to_date']) {
                        $dateWork = strtotime($maxDateEndWork);
                        $dateProject = strtotime($value['to_date']);
                        $dateLate = abs($dateWork - $dateProject) / (60 * 60 * 24);
                        $value['condition'] = [
                            'condition_color' => "#FFB6C1",
                            'condition_name' => "Quá hạn " . $dateLate . " ngày"
                        ];
                    } else {
                        $value['condition'] = [
                            'condition_color' => "#87CEFF",
                            'condition_name' => "Bình thường"
                        ];
                    }
                } else {
                    $value['condition'] = [
                        'condition_color' => "#87CEFF",
                        'condition_name' => "Bình thường"
                    ];
                }

                ///tag
                if (isset($tag) && count($tag) > 0 && isset($tag[$value['project_id']])) {
                    $value['tag'] = $tag[$value['project_id']];
                } else {
                    $value['tag'] = [];
                }
                ///nguoi quan tri
                $value['manager_id'] = explode(',', $value['manager_id']);
                $manager = [];
                foreach ($value['manager_id'] as $k => $v) {
                    if (isset($listManager[$v])) {
                        $manager[] = $listManager[$v];
                    } else {
                        $manager = [];
                    }
                }
                $value['manager'] = $manager;
                unset($value['manager_id']);

                ///khach hang
                if (count($listCustomer) > 0 && !empty($listCustomer) && isset($listCustomer[$value['customer_id']])) {
                    $value['customer'] = [$listCustomer[$value['customer_id']]];
                    unset($value['customer_id']);
                } else {
                    $value['customer'] = [];
                }
                unset($value['customer_id']);

                $value['work'] = 0;
                if (isset($totalWork[$value['project_id']])) {
                    $value['work'] = $totalWork[$value['project_id']]['total'];
                }
                ///work_progress tính theo số lượng công việc hoàn thành
//                if (count($totalWork) > 0 && count($totalWorkComplete) > 0
//                    && !empty($value['project_id']) && isset($totalWork[$value['project_id']]) && isset($totalWorkComplete[$value['project_id']])) {
//                    if ($totalWork[$value['project_id']]['total'] != 0) {
//                        $value['work_progress'] = floor($totalWorkComplete[$value['project_id']]['total'] * 100 / $totalWork[$value['project_id']]['total']);
//                        $value['work'] = $totalWork[$value['project_id']]['total'];
//                    } else {
//                        $value['work_progress'] = 0;
//                    }
//                } else {
//                    $value['work_progress'] = 0;
//                }

                if (count($numberDocument) > 0 && !empty($value['project_id']) && isset($numberDocument[$value['project_id']])) {
                    $value['document'] = $numberDocument[$value['project_id']]['total'];
                } else {
                    $value['document'] = 0;
                }

                if (count($totalMember) > 0 && !empty($value['project_id']) && isset($totalMember[$value['project_id']])) {
                    $value['member'] = $totalMember[$value['project_id']]['total'];
                } else {
                    $value['member'] = 0;
                }

                //thong tin rui ro
                $value['project_risk_id'] = 2;
                $value['project_risk_name'] = 'Bình thường';
                //tinh do rui ro
                if (isset($listWork[$value['project_id']])) {
                    $ext = $listWork[$value['project_id']];
                    foreach ($ext as $k => $val) {
                        $a = $val['date_start'];
                        $b = $val['date_end'];
                        $c = $val['date_finish'];
                        if ($c == null) {
                            $first_date = strtotime($a);
                            $second_date = strtotime($b);

                        } else {
                            $first_date = strtotime($a);
                            $second_date = strtotime($c);
                        }
                        $datediff = abs($first_date - $second_date);
                        $val['time_work'] = $datediff / (60 * 60 * 24);
                        $ext[$k] = $val;
                    }
                    $totalWorkk = count($ext);
                    $totalWorkCompletee = collect($ext)->where('status_id', 6)->count();
                    $totalTimeWork = collect($ext)->sum('time_work');
                    $ratioWork = $totalWorkk != 0 && $totalWorkCompletee != 0 ? $totalWorkCompletee / $totalWorkk * 100 : 0;
                    $ratioTimeWork = $value['resource_total'] != 0 && $value['resource_total'] != null && $totalTimeWork != 0 ? $totalTimeWork / $value['resource_total'] * 100 : 0;
                    $ratio = $ratioTimeWork - $ratioWork;
                    if ($ratio < 0) {
                        $value['project_risk_id'] = 1;
                        $value['project_risk_name'] = 'Thấp';
                    } elseif ($ratio > 20) {
                        $value['project_risk_id'] = 3;
                        $value['project_risk_name'] = 'Cao';
                    } else {
                        $value['project_risk_id'] = 2;
                        $value['project_risk_name'] = 'Bình thường';
                    }
                }
            }
            return $this->toPagingData($data);
        } catch (\Exception $exception) {
            throw new ProjectRepoException(ProjectRepoException::GET_LIST_PROJECT);
        }
    }

    public function projectInfo($input)
    {
        try {

            $mProjectInfo = app()->get(ProjectTable::class);
            $mWork = app()->get(WorkTable::class);
            $mCustomer = app()->get(CustomerTable::class);
            $staff = app()->get(StaffTable::class);
            $mTags = app()->get(ProjectTagTable::class);
            $mDocument = app()->get(ProjectDocumentTable::class);
            $memberProject = app()->get(ProjectStaffTable::class);

            $data = $mProjectInfo->projectInfo($input);


            $filter['manage_project_id'] = $data['project_id'];
            $listWork = $mWork->getAllWork($filter);
            ///nguồn lực(tính ra giờ)
            if (isset($listWork)) {
                $data['resource_implement'] = 0;
                foreach ($listWork as $item) {
                    if ($item['time_type'] == "h") {
                        $data['resource_implement'] = $data['resource_implement'] + $item['time'];
                    } else {
                        $data['resource_implement'] = $data['resource_implement'] + $item['time'] * 24;
                    }
                }
            } else {
                $value['resource_implement'] = 0;
            }
            //mức độ quan trọng
            if ($data['is_important'] == 1) {
                $data['important_name'] = "Quan trọng";
            } else {
                $data['important_name'] = "Bình thường";
            }
            ///tình trạng dự án
            $arrDateEndWork = collect($listWork)->pluck('date_finish')->toArray();
            if (count($arrDateEndWork) > 0) {
                if (in_array(null, $arrDateEndWork)) {
                    $maxDateEndWork = Carbon::now()->format('Y-m-d');
                } else {
                    $maxDateEndWork = Carbon::createFromFormat('Y-m-d H:i:s', max($arrDateEndWork))->format('Y-m-d');
                }
                if ($maxDateEndWork > $data['to_date']) {

                    $data['condition'] = [
                        'condition_color' => "#FFB6C1",
                        'condition_name' => "Quá hạn "
                    ];
                } else {
                    $data['condition'] = [
                        'condition_color' => "#87CEFF",
                        'condition_name' => "Bình thường"
                    ];
                }
            } else {
                $data['condition'] = [
                    'condition_color' => "#87CEFF",
                    'condition_name' => "Bình thường"
                ];
            }

            ///ngày trễ hạn
            $now = Carbon::parse(Carbon::parse(now())->format('Y-m-d'));
            if ($data['to_date'] != null && $data['date_finish'] != null) {
                $dateEnd = Carbon::parse($data['to_date']);
                $dateFinish = Carbon::parse($data['date_finish']);
                if ($dateEnd < $dateFinish) {
                    $data['date_late'] = $dateEnd->diffInDays($dateFinish);
                } else {
                    $data['date_late'] = $dateEnd->diffInDays($now);
                }
            } elseif ($data['to_date'] != null && $data['date_finish'] == null && $data['to_date'] < $now) {
                $dateEnd = Carbon::parse($data['to_date']);
                $data['date_late'] = $dateEnd->diffInDays($now);
            } else {
                $data['date_late'] = 0;
            }

            $totalWork = $mWork->getTotalWork($input);
            $totalWorkComplete = $mWork->getTotalWorkComplete($input);

            $numberTotalWork = 0;
            $numberTotalComplete = 0;

            if ($totalWork != null && $totalWork != []) {
                $numberTotalWork = $totalWork[0]['total'];
            }

            if ($totalWorkComplete != null && $totalWorkComplete != []) {
                $numberTotalComplete = $totalWorkComplete[0]['total'];
            }

//            $data['work_progress'] = $numberTotalComplete == 0 ? $numberTotalComplete : $numberTotalComplete * 100 / $numberTotalWork;

            ///thông tin khách hàng
            if (isset($data['customer_id']) && $data['customer_id'] != null) {
                $infoCustomer = $mCustomer->getCustomerAll($data);
                if (!empty($infoCustomer)) {
                    $data['customer'] = [$infoCustomer[0]];
                } else {
                    $data['customer'] = [];
                }
            } else {
                $data['customer'] = [];
            }

            unset($data['customer_id']);

            ///thông tin người quản trị
            if (isset($data['manager_id']) && $data['manager_id'] != null) {
                $filter['arrIdManager'] = explode(',', $data['manager_id']);
                $infoManager = $staff->getInfoManager($filter);
                if (!empty($infoManager)) {
                    $data['manager'] = $infoManager;
                } else {
                    $data['manager'] = [];
                }
            } else {
                $data['manager'] = [];
            }
            unset($data['manager_id']);
            ///thong tin nguoi tao
            $infoCreator = $staff->getInfoManager($data);
            if (count($infoCreator) > 0 && !empty($infoManager)) {
                $data['creator'] = [$infoCreator[0]];
            } else {
                $data['creator'] = [];
            }

            ///danh sach tag
            $data['tag'] = $mTags->getTagProject($input);
            ///so luong tai lieu
            $numberDocument = $mDocument->getNumberDocument($input);
            $data['document'] = $numberDocument ? $numberDocument[0]['total'] : 0;

            ///thanh vien du an
            $totalMember = $memberProject->getMemberProject($input);
            $data['member'] = $totalMember ? $totalMember[0]['total'] : 0;

            ///so luong cong viec
            $data['work'] = $totalWork ? $totalWork[0]['total'] : 0;

            //thong tin rui ro
            $data['project_risk_id'] = 2;
            $data['project_risk_name'] = 'Bình thường';
            //tinh do rui ro
            if (isset($listWork)) {
                foreach ($listWork as $k => $val) {
                    $a = $val['date_start'];
                    $b = $val['date_end'];
                    $c = $val['date_finish'];
                    if ($c == null) {
                        $first_date = strtotime($a);
                        $second_date = strtotime($b);

                    } else {
                        $first_date = strtotime($a);
                        $second_date = strtotime($c);
                    }
                    $datediff = abs($first_date - $second_date);
                    $val['time_work'] = $datediff / (60 * 60 * 24);
                    $listWork[$k] = $val;
                }
                $totalWorkk = count($listWork);
                $totalWorkCompletee = collect($listWork)->where('status_id', 6)->count();
                $totalTimeWork = collect($listWork)->sum('time_work');
                $ratioWork = $totalWorkk != 0 && $totalWorkCompletee != 0 ? $totalWorkCompletee / $totalWorkk * 100 : 0;
                $ratioTimeWork = $data['resource'] != 0 && $data['resource'] != null && $totalTimeWork != 0 ? $totalTimeWork / $data['resource'] * 100 : 0;
                $ratio = $ratioTimeWork - $ratioWork;
                if ($ratio < 0) {
                    $data['project_risk_id'] = 1;
                    $data['project_risk_name'] = 'Thấp';
                } elseif ($ratio > 20) {
                    $data['project_risk_id'] = 3;
                    $data['project_risk_name'] = 'Cao';
                } else {
                    $data['project_risk_id'] = 2;
                    $data['project_risk_name'] = 'Bình thường';
                }
            }

            return $data;
        } catch (\Exception $exception) {
            throw new ProjectRepoException(ProjectRepoException::GET_PROJECT_INFO);
        }
    }

    ////update trạng thái dự án
    public function updateStatusProject($input)
    {
        try {
            $mProjectInfo = app()->get(ProjectTable::class);

            $projectId = $input['project_id'];
            $dataUpdateStatus = [
                "manage_project_status_id" => $input['project_status_id'],
                "progress" => $input['progress'] ? $input['progress'] : null
            ];

            $data = $mProjectInfo->updateStatus($projectId, $dataUpdateStatus);
            if (isset($data)) {
                return [
                    'error' => 0,
                    'message' => 'Cập nhật trạng thái dự án thành công.'
                ];
            } else {
                return [
                    'error' => 0,
                    'message' => 'Cập nhật trạng thái dự án thất bại.'
                ];
            }

        } catch (\Exception $exception) {
            throw new ProjectRepoException(ProjectRepoException::UPDATE_STATUS_PROJECT);
        }
    }

    //số lượng tổng thành viên-công việc-đang thực hiện-chưa thực hiện-hoàn thành-đã đóng-quán hạn
    public function getDataStatictical($input)
    {
        try {
            $memberProject = app()->get(ProjectStaffTable::class);
            $mWork = app()->get(WorkTable::class);

            $data = [];
            switch ($input['statistical_type']) {
                case ('member') :
                    $allMember = $memberProject->getAllMember($input);
                    $dataMember['member_total'] = $allMember ? count($allMember) : 0;
                    $department = $this->getDepartment();
                    if (count($allMember) > 0) {
                        $allMember = collect($allMember)->groupBy('department_id');
                        foreach ($department as $key => $value) {
                            if (isset($allMember[$value['department_id']])) {
                                $data[$value['department_name']] = count($allMember[$value['department_id']]);
                            } else {
                                $data[$value['department_name']] = 0;
                            }
                        }
                        foreach ($data as $k => $v) {
                            if ($v != 0) {
                                $a = [
                                    'member_position' => $k,
                                    'member_amount' => $v,
                                    'color' => $this->randColor()
                                ];
                                $data1[] = $a;
                            }
                        }

                        $dataMember['member_detail'] = $data1;
                    } else {
                        $dataMember = [];
                    }


                    return $dataMember;
                    break;
                case ('work') :
                    $listWork = $mWork->getAllWork($input);
//                    $typeWork = $this->getTypeWork()->toArray();
                    $department = $this->getDepartment();
                    if (count($listWork) > 0) {
                        $listWork1 = collect($listWork)->groupBy('department_name');
                        if (isset($input['status_id']) && $input['status_id'] != 0) {
                            $data['total'] = $listWork ? count($listWork) : 0;
                            foreach ($department as $key => $value) {
                                if (isset($listWork1[$value['department_name']])) {
                                    $data[$value['department_name']] = count($listWork1[$value['department_name']]);
                                } else {
                                    $data[$value['department_name']] = 0;
                                }
                            }
                        } elseif (!isset($input['status_id']) || $input['status_id'] == 0) {
                            $data['total'] = $listWork ? count($listWork) : 0;
                            foreach ($department as $key => $value) {
                                if (isset($listWork1[$value['department_name']])) {
                                    $data[$value['department_name']] = count($listWork1[$value['department_name']]);
                                } else {
                                    $data[$value['department_name']] = 0;
                                }
                            }
                        }
                        foreach ($data as $k => $v) {
                            if ($v != 0) {
                                $a = [
                                    'department_name' => $k,
                                    'work_amount' => $v,
                                    'color' => $this->randColor()
                                ];
                                $data1[] = $a;
                            }
                        }
                    } else {
                        $data1 = [];
                    }
                    return $data1;
                    break;
                default:
                    return null;
            }
        } catch (\Exception $exception) {

            throw new ProjectRepoException(ProjectRepoException::GET_STATICTICAL);
        }
    }

    public function editProject($input)
    {
        try {
            $mEditProject = app()->get(ProjectTable::class);
            $mEditTags = app()->get(ProjectTagTable::class);

            $date_start = null;
            $date_end = null;
            $customer_type = null;
            $customer_id = null;
            if (isset($input['date_start']) && $input['date_start'] != "") {
                $date_start = Carbon::createFromFormat('d/m/Y', $input['date_start'])->format('Y-m-d');
            };
            if (isset($input['date_end']) && $input['date_end'] != "") {
                $date_end = Carbon::createFromFormat('d/m/Y', $input['date_end'])->format('Y-m-d');
            };
            if (isset($input['customer_type']) && $input['customer_type'] != "") {
                $customer_type = $input['customer_type'];
            };

            $dataUpdate = [
                'manage_project_name' => $input['project_name'],//
                'manage_project_describe' => $input['project_describe'] ? $input['project_describe'] : null,
//                'manager_id' => $input['manager_id'],//
//                'department_id' => $input['department_id'],//
//                'date_start' => $date_start,
                'date_end' => $date_end,
                'progress' => $input['progress'] ? $input['progress'] : 0,
                'budget' => $input['budget'] ? $input['budget'] : 0,
//                'customer_type' => $customer_type,
//                'customer_id' => $customer_id,
                'manage_project_status_id' => $input['project_status_id'] ? $input['project_status_id'] : null,//
                'permission' => $input['permission'] ? $input['permission'] : null,
                'color_code' => $input['color_code'] ? $input['color_code'] : null,
            ];

            if (isset($input['manage_project_tags']) && $input['manage_project_tags'] != "") {
                $deleteOldTags = $mEditTags->deleteOldTag($input['project_id']);
                foreach ($input['manage_project_tags'] as $key => $value) {
                    $mEditTags->createdTag([
                        'tag_id' => $value['manage_tag_id'],
                        'manage_project_id' => $input['project_id'],
                        'updated_at' => Carbon::now(),
                        'updated_by' => Auth()->id(),
                    ]);
                }
            }
            ///action update info project
            $manage_project_id = $input['project_id'];

            $oldProject = $mEditProject->getDetail($input['project_id']);
            $this->checkDataCreateHistory($manage_project_id, [], $dataUpdate, $oldProject);

            $data = $mEditProject->editProject($dataUpdate, $manage_project_id);
        } catch (\Exception $exception) {
            throw new ProjectRepoException(ProjectRepoException::EDIT_PROJECT);
        }
    }

    public function deleteProject($input)
    {
        try {
            $delete = app()->get(ProjectTable::class);
            $data = $delete->actionDelete($input);
            return $data;
        } catch (\Exception $exception) {
            throw new ProjectRepoException(ProjectRepoException::DELETE_PROJECT);
        }
    }

    public function isDelete($input)
    {
        try {
            $isDelete = app()->get(ProjectTable::class);
            //trang thai is_delete
            $dataUpdate = [
                'is_active' => 0,
                'is_deleted' => 1
            ];
            $data = $isDelete->actionIsDelete($dataUpdate, $input['manage_project_id']);

            return $data;
        } catch (\Exception $exception) {
            throw new ProjectRepoException(ProjectRepoException::IS_DELETE);
        }
    }

    public function getListDocument($input)
    {
        try {
            $mDocument = app()->get(ProjectDocumentTable::class);
            $data = $mDocument->getListDocument($input);
            return $this->toPagingData($data);
        } catch (\Exception $exception) {
            throw new ProjectRepoException(ProjectRepoException::GET_LIST_DOCUMENTS);
        }
    }

    public function addDocument($input)
    {
        try {
            $ext = strtoupper(pathinfo($input['path'], PATHINFO_EXTENSION));
            $formatImages = ['JPG', 'JPEG', 'PNG', 'WEBP', 'GIF', 'TIFF', 'PSD', 'ESP', 'AI', 'HEIC', 'RAW', 'SVG'];
            $mDocument = app()->get(ProjectDocumentTable::class);
            $dataInsert = [
                'manage_project_id' => $input['manage_project_id'],
                'file_name' => $input['file_name'],
                'type' => in_array($ext, $formatImages) ? 'image' : 'file',
                'path' => $input['path'],
                'created_at' => Carbon::now(),
                'created_by' => Auth()->id(),
                'updated_at' => Carbon::now(),
                'updated_by' => Auth()->id(),
            ];
            $data = $mDocument->AddDocument($dataInsert);
            if (isset($data) && $data != []) {
                $dataHistory = [
                    'manage_project_id' => $input['manage_project_id'],
                    'new' => $input['file_name'],
                    'key' => 'add_document'
                ];
                $this->createHistoryProject($dataHistory);
            }
            return $data;
        } catch (\Exception $exception) {
            throw new ProjectRepoException(ProjectRepoException::ADD_DOCUMENTS);
        }
    }

    public function deleteDocument($input)
    {
        try {
            $mDocument = app()->get(ProjectDocumentTable::class);
            ///lấy thông tin tài liệu
            $infoDocument = $mDocument->getListDocument($input);
            $data = $mDocument->deleteDocument($input['document_id']);
            if (isset($data) && $data != [] && isset($infoDocument) && $infoDocument != []) {
                $dataHistory = [
                    'manage_project_id' => $infoDocument[0]['manage_project_id'],
                    'new' => $infoDocument[0]['document_name'],
                    'key' => 'delete_document'
                ];
                $this->createHistoryProject($dataHistory);
            }
            return $data;
        } catch (\Exception $exception) {
            throw new ProjectRepoException(ProjectRepoException::DELETE_DOCUMENT);
        }
    }

    public function getActivities($input)
    {
        try {
            $mActivities = app()->get(ProjectTable::class);
            $data = $mActivities->getActivities($input);


            return $this->toPagingData($data);
        } catch (\Exception $exception) {
            throw new ProjectRepoException(ProjectRepoException::GET_LIST_DOCUMENTS);
        }
    }

    public function getListMem($input)
    {
        try {
            $memberProject = app()->get(ProjectMemberTable::class);
            $data = $memberProject->getListMem($input);
//            return $this->toPagingData($data);
            return $data;
        } catch (\Exception $exception) {
            throw new ProjectRepoException(ProjectRepoException::GET_LIST_MEMBER_PROJECT);
        }
    }

    public function addMem($input)
    {
        try {
            $addMemberProject = app()->get(ProjectStaffTable::class);
            unset($input['brand_code']);
            $data = [];
            foreach ($input['staff_id'] as $key => $val) {
                $data[] = [
                    'staff_id' => $val,
                    'manage_project_id' => $input['manage_project_id'],
                    'manage_project_role_id' => $input['manage_project_role_id']
                ];
            }
            foreach ($data as $key => $val) {
                $dataStaff = [
                    "manage_project_id" => $val['manage_project_id'],
                    "staff_id" => $val['staff_id'],
                    "manage_project_role_id" => $val['manage_project_role_id'],
                    "created_at" => Carbon::now()->format('Y-m-d H:i:s'),
                    "created_by" => Auth()->id()
                ];
                //lấy danh sách thành viên hiện có của dự án
                $listMember = $addMemberProject->getAllMember($input);
                $arrIdMember = collect($listMember)->pluck('staff_id')->toArray();
                $listMemberKeyByStaffId = collect($listMember)->keyBy('staff_id');
                if (in_array($val['staff_id'], $arrIdMember)) {
                    return [
                        "error" => true,
                        "message" => "Nhân viên " . $listMemberKeyByStaffId[$val['staff_id']]['staff_name'] . " đã thuộc dự án",
                    ];
                } else {
                    $result = $addMemberProject->addMem($dataStaff);
                }
            }
        } catch (\Exception $exception) {
            throw new ProjectRepoException(ProjectRepoException::ADD_PROJECT_MEMBER);
        }
    }

    public function editMem($input)
    {
        try {
            $editMemberProject = app()->get(ProjectStaffTable::class);
            unset($input['brand_code']);
            $dataEditStaff = [
                "manage_project_role_id" => $input['manage_project_role_id'],
                "updated_at" => Carbon::now()->format('Y-m-d H:i:s'),
                "updated_by" => Auth()->id()
            ];
            $message = "Dự án phải có tối thiểu 1 người quản trị, thêm quản trị và rồi thay đổi?";
            $infoMem = $editMemberProject->infoMember($input);
            if ($input['manage_project_role_id'] == 1) {
                $data = $editMemberProject->editMem($dataEditStaff, $input['manage_project_staff_id']);
            } else {
                $total = $editMemberProject->getTotalManager($input['manage_project_id']);
                if (!empty($total) && $total['total'] > 1) {
                    $data = $editMemberProject->editMem($dataEditStaff, $input['manage_project_staff_id']);
                } else {
                    if ($infoMem['manage_project_role_id'] != 1) {
                        $data = $editMemberProject->editMem($dataEditStaff, $input['manage_project_staff_id']);
                    } else {
                        return $message;
                    }
                }
            }
            return [
                "error" => false,
                "message" => "Cập nhật thành công",
            ];
        } catch (\Exception $exception) {
            throw new ProjectRepoException(ProjectRepoException::EDIT_PROJECT_MEMBER);
        }
    }

    public function deleteMem($input)
    {
        try {
            $delete = app()->get(ProjectStaffTable::class);
            $memberProject = app()->get(ProjectStaffTable::class);
            $totalManager = app()->get(ProjectStaffTable::class);

            $total = $totalManager->getTotalManager($input['manage_project_id']);
            $infoMem = $memberProject->infoMember($input);
            unset($input['brand_code']);
            $message = "Đây là quản trị viên duy nhất của dự án, thêm quản trị mới rồi xóa?";
            $id = $input['manage_project_staff_id'];
            if ($total['total'] > 1) {
                $data = $delete->actionDelete($id);
            } else {
                if ($infoMem['manage_project_role_id'] != 1) {
                    $data = $delete->actionDelete($id);
                } else {
                    return [
                        'error' => true,
                        'message' => $message,
                    ];
                }
            }
            return $data;
        } catch (\Exception $exception) {
            throw new ProjectRepoException(ProjectRepoException::DELETE_MEMBER);
        }
    }

    public function checkDataCreateHistory($manage_project_id, $dataInsert, $dataNew = [], $dataOld = [])
    {
        if (count($dataInsert) != 0) {
            $this->createHistoryProject($dataInsert);
            return true;
        }

        if (count($dataNew) != 0 && isset($dataOld)) {
//            Tên dự án
            if (isset($dataNew['manage_project_name']) && $dataNew['manage_project_name'] != $dataOld['manage_project_name']) {
                $data = [
                    'manage_project_id' => $manage_project_id,
                    'new' => $dataNew['manage_project_name'],
                    'old' => $dataOld['manage_project_name'],
                    'key' => 'manage_project_name'
                ];
                $this->createHistoryProject($data);
            }
//            Khách hàng
            if (isset($dataNew['customer_id']) && $dataNew['customer_id'] != $dataOld['customer_id']) {
                $mCustomer = app()->get(CustomersTable::class);
                $olDCustomer = $mCustomer->getDetail($dataOld['customer_id']);
                $newCustomer = $mCustomer->getDetail($dataNew['customer_id']);
                $data = [
                    'manage_project_id' => $manage_project_id,
                    'new' => isset($newCustomer) ? $newCustomer['full_name'] : '',
                    'old' => isset($olDCustomer) ? $olDCustomer['full_name'] : '',
                    'key' => 'customer'
                ];
                $this->createHistoryProject($data);
            }

//            Status
            if (isset($dataNew['manage_project_status_id']) && $dataNew['manage_project_status_id'] != $dataOld['manage_project_status_id']) {
                $mManageProjectStatus = app()->get(ManageProjectStatusTable::class);
                $olDStatus = $mManageProjectStatus->getDetail($dataOld['manage_project_status_id']);
                $newStatus = $mManageProjectStatus->getDetail($dataNew['manage_project_status_id']);
                $data = [
                    'manage_project_id' => $manage_project_id,
                    'new' => isset($newStatus) ? $newStatus['manage_project_status_name'] : '',
                    'old' => isset($olDStatus) ? $olDStatus['manage_project_status_name'] : '',
                    'key' => 'status'
                ];
                $this->createHistoryProject($data);
            }

//            Quyền truy cập
            if (isset($dataNew['permission']) && $dataNew['permission'] != $dataOld['permission']) {
                $data = [
                    'manage_project_id' => $manage_project_id,
                    'new' => $dataOld['permission'] == 'private' ? __('Nội bộ') : __('Công khai'),
                    'old' => $dataNew['permission'] == 'private' ? __('Nội bộ') : __('Công khai'),
                    'key' => 'permission'
                ];
                $this->createHistoryProject($data);
            }
        }

    }

    /**
     * Tạo lịch sử dự án
     * @param $data
     */
    public function createHistoryProject($data)
    {
//        $data['manage_project_id'] : Id Dự án
//        $data['old'] : Giá trị cũ
//        $data['new'] : Giá trị mới
//        $data['key'] : khóa để check . status : trạng thái, manage_id : người quản lý
        $message = '';
        switch ($data['key']) {
//        Cập nhật nội dung nhiều giá trị
            case 'update' :
                $message = __('đã cập nhật dự án');
                break;
            case 'created' :
                $message = __('đã tạo dự án :new', ['new' => $data['new']]);
                break;
            case 'manage_project_name' :
                $message = __('đã cập nhật tiêu đề từ :old sang :new', ['old' => $data['old'], 'new' => $data['new']]);
                break;
            case 'tag' :
                $message = __('đã cập nhật tag dự án');
                break;
            case 'customer' :
                $message = __('đã cập nhật khách hàng từ :old sang :new', ['old' => $data['old'], 'new' => $data['new']]);
                if ($data['old'] == '') {
                    $message = __('đã cập nhật khách hàng thành :new', ['old' => $data['old'], 'new' => $data['new']]);
                }
                break;
            case 'status' :
                $message = __('đã cập nhật trạng thái từ :old sang :new', ['old' => $data['old'], 'new' => $data['new']]);
                break;
            case 'permission' :
                $message = __('đã cập nhật quyền truy cập từ :old sang :new', ['old' => $data['old'], 'new' => $data['new']]);
                break;
            case 'add_document' :
                $message = __('đã thêm tài liệu :new', ['new' => $data['new']]);
                break;
            case 'delete_document' :
                $message = __('đã xóa tài liệu :new', ['new' => $data['new']]);
                break;
            default:
                break;
        }

        $mManageProjectHistory = app()->get(ManageProjectHistoryTable::class);
        $mManageProjectHistory->addHistory([
            'manage_project_id' => $data['manage_project_id'],
            'staff_id' => Auth::id(),
            'message' => $message,
            'created_at' => Carbon::now(),
            'created_by' => Auth::id(),
            'updated_at' => Carbon::now(),
            'updated_by' => Auth::id(),
        ]);

        return true;
    }

    public function getWorkList($input)
    {
        try {
            $mWork = app()->get(WorkTable::class);
            $staff = app()->get(StaffTable::class);
            $mComment = app()->get(ManageCommentTable::class);
            $mPhaseProject = app()->get(ProjectPhaseTable ::class);

            $data = $mWork->getAllWork($input);
            $listPhase = $mPhaseProject->getPhase($input);


            ///lay danh sach nhan vien
            $arrIdStaffs = collect($data)->pluck('processor_id')->toArray();
            $arrIdStaffs = array_values(array_unique($arrIdStaffs));
            $filter['arrIdStaff'] = $arrIdStaffs;
            $listStaff = $staff->getInfoStaff($filter);
            $listStaff = collect($listStaff)->keyBy('staff_id');
            ////lấy số comment công việc
            $arrIdWork = collect($data)->pluck('work_id')->toArray();
            $filter['arrIdWork'] = $arrIdWork;
            $numOfComment = $mComment->getNumOfComment($filter)->toArray();
            $numOfComment = collect($numOfComment)->keyBy('manage_work_id');

            $now = Carbon::parse(Carbon::parse(now())->format('Y-m-d H:i:s'));
            foreach ($data as $key => $value) {
                $data[$key]['staff'] = [];
                $data[$key]['comment'] = 0;

                if (isset($listStaff[$value['processor_id']])) {
                    $data[$key]['staff'] = $listStaff[$value['processor_id']];
                }
                ///số comment công việc
                if (isset($numOfComment[$value['work_id']])) {
                    $data[$key]['comment'] = $numOfComment[$value['work_id']]['total'];
                } else {
                    $data[$key]['comment'] = 0;

                }

                //công việc có trễ hạn?

                $value['is_late'] = 0;
               if($value['date_finish'] == null && $now > $value['date_end']){
                   $value['is_late'] = 1;
               }elseif($value['date_finish'] != null && $value['date_finish'] > $value['date_end']){
                   $value['is_late'] = 1;
               }

                $data[$key] = $value;
            }
            $data = collect($data)->groupBy('phase_id')->toArray();
            foreach ($listPhase as $key => $value) {
                if (isset($data[$value['manage_project_phase_id']])) {
                    $value['work_list'] = $data[$value['manage_project_phase_id']];
                } else {
                    $value['work_list'] = [];
                }
               $value['work_late'] = $value['work_list'] != [] ?
                   collect($value['work_list'])->where('is_late' , 1)->count() : 0;
                $listPhase[$key] = $value;
            }
            return $listPhase;
        } catch (\Exception $exception) {
            throw new ProjectRepoException(ProjectRepoException:: WORK_LIST);
        }
    }

    public function addIssue($input)
    {
        try {
            $issue = app()->get(ProjectIssueTable::class);
            $dataInsert = [
                'parent_id' => $input['parent_id'] ? $input['parent_id'] : null,
                'manage_project_id' => $input['manage_project_id'],
                'content' => $input['content'],
                'status' => $input['status'] ? $input['status'] : null,
                'created_at' => Carbon::now(),
                'created_by' => Auth()->id()
            ];
            $data = $issue->addIssue($dataInsert);
        } catch (\Exception $exception) {
            throw new ProjectRepoException(ProjectRepoException:: ADD_ISSUE);
        }
    }

    public function listIssue($input)
    {
        try {
            $issue = app()->get(ProjectIssueTable::class);
            $data = $issue->listIssue($input);
            return $this->toPagingData($data);
        } catch (\Exception $exception) {
            throw new ProjectRepoException(ProjectRepoException:: LIST_ISSUE);
        }
    }

    public function getInfoReport($input)
    {
        try {
            $data = [];
            $mWork = app()->get(WorkTable::class);
            $mPhaseProject = app()->get(ProjectPhaseTable :: class);
            $mProjectInfo = app()->get(ProjectTable::class);
            $mExpenditure = app()->get(ProjectExpenditureTable :: class);

            ///tổng quan giai đoạn
            $data['phase_report'] = [];
            $listPhase = $mPhaseProject->getPhase($input);
            if (isset($listPhase) && count($listPhase) > 0) {
                foreach ($listPhase as $key => $value) {
                    ///cong viec da hoan thanh theo giai doan
                    $filterComplete = [
                        'manage_project_phase_id' => $value['manage_project_phase_id'],
                        'status_id' => 6,
                        'manage_project_id' => $value['manage_project_id']
                    ];
                    $completeWork = $mWork->getAllWork($filterComplete);
                    $filterIncomplete = [
                        'manage_project_phase_id' => $value['manage_project_phase_id'],
                        'status_id' => 5,
                        'manage_project_id' => $value['manage_project_id']
                    ];
                    ///cong viec chua hoan thanh theo giai doan
                    $incompleteWork = $mWork->getAllWork($filterIncomplete);
                    $infoPhase = [
                        'phase_name' => $value['phase_name'],
                        'phase_data' => [
                            'processing' => $incompleteWork ? count($incompleteWork) : 0,
                            'complete' => $completeWork ? count($completeWork) : 0
                        ]
                    ];
                    $data['phase_report'][] = $infoPhase;
                }
            }

            ///công việc không thuộc giai đoạn
            $workComplete = 0;
            $workIncomplete = 0;
            $listWork = $mWork->getAllWork($input);
            foreach ($listWork as $key => $work) {
                if ($work['phase_id'] == null && $work['status_id'] == 6) {
                    $workComplete += 1;
                } elseif ($work['phase_id'] == null && $work['status_id'] == 5) {
                    $workIncomplete += 1;
                }
            }
            if ($workComplete > 0 || $workIncomplete > 0) {
                $workNotPhase = [
                    'phase_name' => 'Không có',
                    'phase_data' => [
                        'processing' => $workIncomplete,
                        'complete' => $workComplete
                    ]
                ];
                $data['phase_report'][] = $workNotPhase;
            };

            ///báo cáo ngân sách
            $infoProject = $mProjectInfo->projectInfo($input);
            ///thu - dự án
            $expenditureReceipt = $mExpenditure->getExpenditureReceipt($input);

            if (isset($expenditureReceipt) && count($expenditureReceipt) > 0) {
                $arrTotalMoneyReceipt = collect($expenditureReceipt)->pluck('total_money')->toArray();
                $revenue = array_sum($arrTotalMoneyReceipt);
            } else {
                $revenue = 0;
            }
            ///chi-dự án
            $expenditurePayment = $mExpenditure->getExpenditurePayment($input);
            if (isset($expenditurePayment) && count($expenditurePayment) > 0) {
                $arrTotalMoneyPayment = collect($expenditurePayment)->pluck('total_amount')->toArray();
                $spending = array_sum($arrTotalMoneyPayment);
            } else {
                $spending = 0;
            }
            $budgetReport = [
                'budget' => $infoProject['budget'] ? $infoProject['budget'] : 0,
                'revenue' => $revenue,
                'spending' => $spending,
            ];
            $data['budget_report'] = [$budgetReport];
            return $data;
        } catch (\Exception $exception) {
            throw new ProjectRepoException(ProjectRepoException:: GET_INFO_REPORT);
        }
    }

    public function getDataPhase($input)
    {
        try {
            $mPhaseProject = app()->get(ProjectPhaseTable ::class);
            $mWork = app()->get(WorkTable::class);

            $dataPhase = $mPhaseProject->getPhase($input);
            ///danh sách công việc theo giai đoạn
            $dataWork = $mWork->getAllWork($input);
            $dataWorkIdByPhase = collect($dataWork)->groupBy('phase_id')->toArray();
            $a = $dataWorkIdByPhase;
            ///tìm max date end theo từng giai đoạn
            foreach ($dataWorkIdByPhase as $key => $value) {
                $arrDateEnd = collect($value)->pluck('date_end')->toArray();
                if (in_array(null, $arrDateEnd)) {
                    $maxDateEnd = Carbon::now()->format('Y-m-d');
                } else {
                    $maxDateEnd = Carbon::createFromFormat('Y-m-d H:i:s', max($arrDateEnd))->format('Y-m-d');
                }
                $value = $maxDateEnd;
                $dataWorkIdByPhase[$key] = $value;
            }
            foreach ($dataPhase as $k => $v) {
                if (isset($dataWorkIdByPhase[$v['manage_project_phase_id']])) {
                    $v['max_date_end_work'] = $dataWorkIdByPhase[$v['manage_project_phase_id']];
                }else{
                    $v['max_date_end_work'] = Carbon::now()->format('Y-m-d');
                }
                if ($v['max_date_end_work'] > $v['phase_end'] && $v['phase_status'] != 'success') {
                    $dateWork = strtotime($v['max_date_end_work']);
                    $datePhase = strtotime($v['phase_end']);
                    $dateLate = abs($dateWork - $datePhase) / (60 * 60 * 24);
                    $v['condition'] = [
                        'condition_color' => "#FFB6C1",
                        'condition_name' => "Quá hạn " . $dateLate . " ngày"
                    ];
                } else {
                    $v['condition'] = [
                        'condition_color' => "#87CEFF",
                        'condition_name' => "Bình thường"
                    ];
                }
                if (isset($a[$v['manage_project_phase_id']])) {
                    $v['work'] = count($a[$v['manage_project_phase_id']]);
                } else {
                    $v['work'] = 0;
                }

                $dataPhase[$k] = $v;
            }
            return $dataPhase;
        } catch (\Exception $exception) {
            throw new ProjectRepoException(ProjectRepoException:: GET_DATA_PHASE);
        }
    }

    public function addComment($input)
    {
        try {
            $mComment = app()->get(ProjectCommentTable::class);
            $mManageProjectHistory = app()->get(ManageProjectHistoryTable::class);
            $dataInsert = [
                'manage_project_id' => $input['manage_project_id'],
                'message' => $input['message'],
                'path' => $input['path'] ? $input['path'] : null,
                'created_at' => Carbon::now(),
                'created_by' => Auth()->id(),
                'staff_id' => Auth()->id()
            ];
            $addComment = $mComment->addComment($dataInsert);
            ///lưu lịch sử thêm bình luận
            $dataHistory = [
                'manage_project_id' => $input['manage_project_id'],
                'manage_project_comment_id' => $addComment,
                'staff_id' => Auth()->id(),
                'message' => 'đã thêm bình luận: ' . $input['message'],
                'action' => 'comment',
                'created_at' => Carbon::now(),
                'created_by' => Auth()->id(),
            ];
            $addHistory = $mManageProjectHistory->addHistory($dataHistory);
            return $addComment;
        } catch (\Exception $exception) {
            throw new ProjectRepoException(ProjectRepoException:: ADD_COMMENT_PROJECT);
        }
    }

    public function getHistoryComment($input)
    {
        try {
            $mManageProjectHistory = app()->get(ManageProjectHistoryTable::class);
            ///lay danh sach theo filter activities
            $data = $mManageProjectHistory->getListComment($input);
            foreach ($data as $key => $value) {
                $value['comment'] = null;
                if ($value['action_type'] == 'comment') {
                    $a = explode("đã thêm bình luận: ", $value['message']);
                    $value['comment'] = $a[1];
                    $data[$key] = $value;
                }
            }
            return $data;
        } catch (\Exception $exception) {
            throw new ProjectRepoException(ProjectRepoException:: HISTORY_COMMENT_PROJECT);
        }
    }

    public function getListExpenditure($input)
    {
        try {
            $mExpenditure = app()->get(ProjectExpenditureTable :: class);
            $mReceipt = app()->get(ReceiptTable :: class);
            $mPayment = app()->get(PaymentTable :: class);
            $listExpenditure = $mExpenditure->getListExpenditure($input);

            $filter['created_at'] = $input['created_at'];
            if ($listExpenditure && count($listExpenditure) > 0) {
                $listExpenditureByType = collect($listExpenditure)->groupBy('type');
                if (isset($listExpenditureByType['receipt'])) {
                    $arrIdReceipt = collect($listExpenditureByType['receipt'])->pluck('obj_id')->toArray();
                    //lấy danh sách phiếu thu
                    $filter['arrIdReceipt'] = $arrIdReceipt;
                    $listReceipt = $mReceipt->getListReceipt($filter);
                    $listReceipt = collect($listReceipt)->keyBy('receipt_id');
                } else {
                    $listReceipt = [];
                }
                if (isset($listExpenditureByType['payment'])) {
                    $arrIdPayment = collect($listExpenditureByType['payment'])->pluck('obj_id')->toArray();
                    //lấy danh sách phiếu chi
                    $filter['arrIdPayment'] = $arrIdPayment;
                    $listPayment = $mPayment->getListPayment($filter);
                    $listPayment = collect($listPayment)->keyBy('payment_id');
                } else {
                    $listPayment = [];
                }
                foreach ($listExpenditure as $key => $value) {
                    if ($value['type'] == 'receipt' && isset($listReceipt[$value['obj_id']])) {
                        $listExpenditure[$key]['expenditure_info'] = $listReceipt[$value['obj_id']];
                    } elseif ($value['type'] == 'payment' && isset($listPayment[$value['obj_id']])) {
                        $listExpenditure[$key]['expenditure_info'] = $listPayment[$value['obj_id']];
                    } else {
                        unset($listExpenditure[$key]);
                    }
                }
            } else {
                return [];
            }
            return array_values($listExpenditure);
        } catch (\Exception $exception) {
            throw new ProjectRepoException(ProjectRepoException:: GET_LIST_EXPENDITURE);
        }
    }

    /**
     * Tạo nhắc nhở theo công việc hoặc theo nhân viên
     * @param $data
     * @return mixed|void
     * @throws ProjectRepoException
     */
    public function createReminder($data)
    {
        try {
            $mRemind = new ManageRemindTable();
            $mManageHistory = new ManageHistoryTable();
            $mWork = app()->get(WorkTable::class);
            $mStaff = new StaffTable();
            $mManageProjectHistory = app()->get(ManageProjectHistoryTable::class);

            if (!isset($data['list_staff']) || count($data['list_staff']) == 0) {
                throw new ProjectRepoException(ProjectRepoException::GET_MANAGE_PROJECT_CREATED_REMINDER_FAILED);
            }

//            if ($data['date_remind'] < Carbon::now()) {
//                throw new ManageWorkRepoException(ManageWorkRepoException::GET_MANAGE_WORK_CREATED_REMINDER_FAILED);
//            }

            $data['list_staff'] = collect($data['list_staff'])->keyBy('staff_id')->toArray();

            if (isset($data['manage_work_id'])) {
                $detailWork = $mWork->detailWork($data['manage_work_id']);
                $mWork->editWork([
                    'updated_at' => Carbon::now(),
                    'updated_by' => Auth()->id()
                ], $data['manage_work_id']);
            }

            $dataRemind = [];
            foreach (array_values($data['list_staff']) as $item) {
                if (isset($data['date_remind'])) {
                    $created_by = $mStaff->getStaffId(Auth::id());
                    $staff_id = $mStaff->getStaffId($item['staff_id']);
                    if (isset($data['manage_work_id'])) {
                        $title = $created_by['staff_name'] . ' ' . __('tạo nhắc nhở về công việc :manage_work_title cho', ['manage_work_title' => $detailWork['manage_work_title']]) . ' ' . $staff_id['staff_name'];
                    } else {
                        $title = $created_by['staff_name'] . ' ' . __('tạo nhắc nhở cho ') . $staff_id['staff_name'];
                    }

                    //                    if ($item['staff_id'] != Auth::id()){
                    $dataRemind[] = [
                        'title' => $title,
                        'staff_id' => $item['staff_id'],
                        'date_remind' => $data['date_remind'],
                        'time' => isset($data['time']) && $data['time'] != null ? $data['time'] : null,
                        'time_type' => isset($data['time_type']) && $data['time_type'] != null ? $data['time_type'] : null,
                        'description' => isset($data['description']) ? $data['description'] : null,
                        'manage_work_id' => isset($data['manage_work_id']) ? $data['manage_work_id'] : '',
                        'created_at' => Carbon::now(),
                        'created_by' => Auth::id(),
                        'updated_at' => Carbon::now(),
                        'updated_by' => Auth::id(),
                        'manage_project_id' => isset($data['manage_project_id']) ? $data['manage_project_id'] : null,
                    ];
                    //                    }
                }
            }

            if (count($dataRemind) != 0) {

                $arrId = array_unique(collect($data['list_staff'])->pluck('staff_id')->toArray());
                $listStaff = $mStaff->getListStaffByArrId($arrId);
                $arrHistory = [];
                foreach ($listStaff as $item) {
                    $arrHistory[] = [
                        'manage_work_id' => isset($data['manage_work_id']) ? $data['manage_work_id'] : '',
                        'staff_id' => Auth::id(),
                        'message' => __(' đã tạo nhắc nhở cho ') . $item['staff_name'],
                        'created_at' => Carbon::now(),
                        'created_by' => Auth::id(),
                        'updated_at' => Carbon::now(),
                        'updated_by' => Auth::id()
                    ];
                }

                if (count($arrHistory) != 0 && !isset($data['manage_project_id'])) {
                    $mManageHistory->createdHistory($arrHistory);
                } elseif (count($arrHistory) != 0 && isset($data['manage_project_id'])) {
                    $arrHistory['manage_project_id'] = $data['manage_project_id'];
                    $mManageProjectHistory->createdHistory($arrHistory[0]);
                }
                $id = $mRemind->createdRemindGetId($dataRemind[0]);
                unset($dataRemind[0]);
                if (count($dataRemind) != 0) {
                    $mRemind->createdRemind($dataRemind);
                }

                $dataNoti = [
                    'key' => 'work_remind',
                    'object_id' => $id,
                ];
                $this->staffNotification($dataNoti);
            }
            return true;
        } catch (\Exception $exception) {
            throw new ProjectRepoException(ProjectRepoException::GET_MANAGE_PROJECT_CREATED_REMINDER_FAILED);
        }
    }

    /**
     * Gửi noti
     * @param $data
     */
    public function staffNotification($data)
    {
        $mManageConfigNotification = new ManageConfigNotificationTable();
        $mManageWork = new WorkTable();
        $mManageDocumentFile = new ManageDocumentFileTable();
        $mManageRemind = new ManageRemindTable();

        $oClient = new Client();
        $configNoti = $mManageConfigNotification->getConfigByKey($data['key']);
        $dataGroupNoti = [];
        if ($configNoti != null) {
            switch ($configNoti['manage_config_notification_key']) {
                case 'work_finish':

                    $info = $mManageWork->detailWorkNoti($data['object_id']);

                    $message = str_replace(['[manage_work_title]', '[updated_name]', '[date_end]'], [$info['manage_work_title'], $info['updated_name'], Carbon::parse($info['date_end'])->format('d/m/Y H:i')], $configNoti['manage_config_notification_message']);
                    $content = str_replace(['[manage_work_title]', '[updated_name]', '[date_end]'], [$info['manage_work_title'], $info['updated_name'], Carbon::parse($info['date_end'])->format('d/m/Y H:i')], $configNoti['manage_config_notification_message']);

                    $params = str_replace(
                        [
                            '[:manage_work_id]'
                        ],
                        [
                            $info['manage_work_id']
                        ],
                        $configNoti['detail_action_params']
                    );

                    //Data insert
                    $dataNotificationDetail = [
                        'background' => '',
                        'content' => $content,
                        'action_name' => $configNoti['detail_action_name'],
                        'action' => $configNoti['detail_action'],
                        'action_params' => $params
                    ];
                    $dataNotification = [
                        'user_id' => $configNoti['customer_id'],
                        'notification_avatar' => $configNoti['avatar'],
                        'notification_title' => $configNoti['manage_config_notification_title'],
                        'notification_message' => $message
                    ];

                    if ($info['is_approve_id'] == 1) {
                        $this->addEmailLog($dataNotificationDetail, $dataNotification, $configNoti, $info);
                        $this->insertNotificationLog($dataNotificationDetail, $dataNotification, $configNoti, $info);
                    }

                    break;

                case 'work_assign':

                    $info = $mManageWork->detailWorkNoti($data['object_id']);

                    $message = str_replace(['[created_name]', '[processor_name]', '[manage_work_title]', '[date_end]'], [$info['updated_name'], $info['processor_name'], $info['manage_work_title'], Carbon::parse($info['date_end'])->format('d/m/Y H:i')], $configNoti['manage_config_notification_message']);
                    $content = str_replace(['[created_name]', '[processor_name]', '[manage_work_title]', '[date_end]'], [$info['updated_name'], $info['processor_name'], $info['manage_work_title'], Carbon::parse($info['date_end'])->format('d/m/Y H:i')], $configNoti['manage_config_notification_message']);

                    $params = str_replace(
                        [
                            '[:manage_work_id]'
                        ],
                        [
                            $info['manage_work_id']
                        ],
                        $configNoti['detail_action_params']
                    );

                    //Data insert
                    $dataNotificationDetail = [
                        'background' => '',
                        'content' => $content,
                        'action_name' => $configNoti['detail_action_name'],
                        'action' => $configNoti['detail_action'],
                        'action_params' => $params
                    ];
                    $dataNotification = [
                        'user_id' => $configNoti['customer_id'],
                        'notification_avatar' => $configNoti['avatar'],
                        'notification_title' => $configNoti['manage_config_notification_title'],
                        'notification_message' => $message
                    ];

                    $this->addEmailLog($dataNotificationDetail, $dataNotification, $configNoti, $info);
                    $this->insertNotificationLog($dataNotificationDetail, $dataNotification, $configNoti, $info);
                    break;
                case 'work_update_status':

                    $info = $mManageWork->detailWorkNoti($data['object_id']);

                    $message = str_replace(['[manage_work_title]', '[updated_name]', '[manage_status_name]'], [$info['manage_work_title'], $info['updated_name'], $info['manage_status_name']], $configNoti['manage_config_notification_message']);
                    $content = str_replace(['[manage_work_title]', '[updated_name]', '[manage_status_name]'], [$info['manage_work_title'], $info['updated_name'], $info['manage_status_name']], $configNoti['manage_config_notification_message']);

                    $params = str_replace(
                        [
                            '[:manage_work_id]'
                        ],
                        [
                            $info['manage_work_id']
                        ],
                        $configNoti['detail_action_params']
                    );

                    //Data insert
                    $dataNotificationDetail = [
                        'background' => '',
                        'content' => $content,
                        'action_name' => $configNoti['detail_action_name'],
                        'action' => $configNoti['detail_action'],
                        'action_params' => $params
                    ];
                    $dataNotification = [
                        'user_id' => $configNoti['customer_id'],
                        'notification_avatar' => $configNoti['avatar'],
                        'notification_title' => $configNoti['manage_config_notification_title'],
                        'notification_message' => $message
                    ];

                    $this->addEmailLog($dataNotificationDetail, $dataNotification, $configNoti, $info);
                    $this->insertNotificationLog($dataNotificationDetail, $dataNotification, $configNoti, $info);
                    break;
                case 'comment_new':

                    $info = $mManageWork->detailWorkNoti($data['object_id']);

                    $mManageComment = app()->get(ManageCommentTable::class);

                    $lastComment = $mManageComment->getCommentLast($data['object_id']);

                    if ($lastComment != null) {
                        $info['update_comment'] = $lastComment['updated_by'];
                        $info['update_comment_parent'] = $lastComment['updated_by_parent'];
                    }

                    $message = str_replace(['[manage_work_title]'], [$info['manage_work_title']], $configNoti['manage_config_notification_message']);
                    $content = str_replace(['[manage_work_title]'], [$info['manage_work_title']], $configNoti['manage_config_notification_message']);

                    $params = str_replace(
                        [
                            '[:manage_work_id]'
                        ],
                        [
                            $info['manage_work_id']
                        ],
                        $configNoti['detail_action_params']
                    );

                    //Data insert
                    $dataNotificationDetail = [
                        'background' => '',
                        'content' => $content,
                        'action_name' => $configNoti['detail_action_name'],
                        'action' => $configNoti['detail_action'],
                        'action_params' => $params
                    ];
                    $dataNotification = [
                        'user_id' => $configNoti['customer_id'],
                        'notification_avatar' => $configNoti['avatar'],
                        'notification_title' => $configNoti['manage_config_notification_title'],
                        'notification_message' => $message
                    ];

                    $this->addEmailLog($dataNotificationDetail, $dataNotification, $configNoti, $info);
                    $this->insertNotificationLog($dataNotificationDetail, $dataNotification, $configNoti, $info);
                    break;
                case 'file_new':

                    $info = $mManageDocumentFile->getDetailFileNoti($data['object_id']);
                    $infoWork = null;
                    if ($info != null) {
                        $infoWork = $mManageWork->detailWorkNoti($info['manage_work_id']);
                    }

                    $message = str_replace(['[manage_work_title]', '[updated_name]', '[file_name]'], [$info['manage_work_title'], $info['staff_name'], $info['file_name']], $configNoti['manage_config_notification_message']);
                    $content = str_replace(['[manage_work_title]', '[updated_name]', '[file_name]'], [$info['manage_work_title'], $info['staff_name'], $info['file_name']], $configNoti['manage_config_notification_message']);

                    $params = str_replace(
                        [
                            '[:manage_work_id]'
                        ],
                        [
                            $info['manage_work_id']
                        ],
                        $configNoti['detail_action_params']
                    );

                    //Data insert
                    $dataNotificationDetail = [
                        'background' => '',
                        'content' => $content,
                        'action_name' => $configNoti['detail_action_name'],
                        'action' => $configNoti['detail_action'],
                        'action_params' => $params
                    ];
                    $dataNotification = [
                        'user_id' => $configNoti['customer_id'],
                        'notification_avatar' => $configNoti['avatar'],
                        'notification_title' => $configNoti['manage_config_notification_title'],
                        'notification_message' => $message
                    ];

                    $this->addEmailLog($dataNotificationDetail, $dataNotification, $configNoti, $infoWork);
                    $this->insertNotificationLog($dataNotificationDetail, $dataNotification, $configNoti, $infoWork);
                    break;
                case 'work_update_description':

                    $info = $mManageWork->detailWorkNoti($data['object_id']);

                    $message = str_replace(['[manage_work_title]', '[updated_name]', '[description]'], [$info['manage_work_title'], $info['updated_name'], strip_tags($info['description'])], $configNoti['manage_config_notification_message']);
                    $content = str_replace(['[manage_work_title]', '[updated_name]', '[description]'], [$info['manage_work_title'], $info['updated_name'], strip_tags($info['description'])], $configNoti['manage_config_notification_message']);

                    $params = str_replace(
                        [
                            '[:manage_work_id]'
                        ],
                        [
                            $info['manage_work_id']
                        ],
                        $configNoti['detail_action_params']
                    );

                    //Data insert
                    $dataNotificationDetail = [
                        'background' => '',
                        'content' => $content,
                        'action_name' => $configNoti['detail_action_name'],
                        'action' => $configNoti['detail_action'],
                        'action_params' => $params
                    ];
                    $dataNotification = [
                        'user_id' => $configNoti['customer_id'],
                        'notification_avatar' => $configNoti['avatar'],
                        'notification_title' => $configNoti['manage_config_notification_title'],
                        'notification_message' => $message
                    ];

                    $this->addEmailLog($dataNotificationDetail, $dataNotification, $configNoti, $info);
                    $this->insertNotificationLog($dataNotificationDetail, $dataNotification, $configNoti, $info);
                    break;
                case 'work_remind':

                    $info = $mManageRemind->getDetailRemindNoti($data['object_id']);
                    $infoWork = null;
                    if ($info != null) {
                        $infoWork = $mManageWork->detailWorkNoti($info['manage_work_id']);
                    }

                    $message = str_replace(['[created_name]', '[description]'], [$info['staff_name'], $info['description']], $configNoti['manage_config_notification_message']);
                    $content = str_replace(['[created_name]', '[description]'], [$info['staff_name'], $info['description']], $configNoti['manage_config_notification_message']);

                    $params = str_replace(
                        [
                            '[:manage_work_id]'
                        ],
                        [
                            $info['manage_work_id']
                        ],
                        $configNoti['detail_action_params']
                    );

                    //Data insert
                    $dataNotificationDetail = [
                        'background' => '',
                        'content' => $content,
                        'action_name' => $configNoti['detail_action_name'],
                        'action' => $configNoti['detail_action'],
                        'action_params' => $params
                    ];
                    $dataNotification = [
                        'user_id' => $configNoti['customer_id'],
                        'notification_avatar' => $configNoti['avatar'],
                        'notification_title' => $configNoti['manage_config_notification_title'],
                        'notification_message' => $message
                    ];
                    $this->addEmailLog($dataNotificationDetail, $dataNotification, $configNoti, $infoWork);
                    $this->insertNotificationLog($dataNotificationDetail, $dataNotification, $configNoti, $infoWork);
                    break;
                default:
                    break;
            }
        }
    }

    private function addEmailLog($dataNotificationDetail, $dataNotification, $configNoti, $info)
    {
        try {
            if ($configNoti['is_mail'] == 1) {
                $dataEmail = [];
                $staffEmailLog = new StaffEmailLogTable();
                $mManageWorkSupport = new ManageWorkSupportTable();
                $listStaff = [];
                if ($configNoti['is_created'] == 1) {
                    $listStaff[$info['created_by']] = $info['created_by'];
                }

                if ($configNoti['is_processor'] == 1) {
                    $listStaff[$info['processor_id']] = $info['processor_id'];
                }

                if ($configNoti['is_approve'] == 1) {
                    $listStaff[$info['approve_id']] = $info['approve_id'];
                }

                if ($configNoti['is_support'] == 1) {
                    $listSupport = $mManageWorkSupport->getListStaffByWork($info['manage_work_id']);
                    if (count($listSupport) != 0) {
                        $listSupport = collect($listSupport)->pluck('staff_id')->toArray();
                        $listStaff = array_merge($listStaff, $listSupport);
                    }
                }

                $key = array_search($info['updated_by'], $listStaff);
                if ($key !== false) {
                    unset($listStaff[$key]);
                }

                $listStaff = array_unique($listStaff);

                $staff = new StaffTable();

                $listEmail = $staff->getListStaffByArrId($listStaff);

                if (count($listEmail) != 0) {
                    $listEmail = collect($listEmail)->pluck('email');
                }

                if (count($listEmail)) {
                    $var = [
                        'content' => $dataNotification['notification_message'],
                        'title' => $dataNotification['notification_title']
                    ];
                    foreach ($listEmail as $v) {
                        $dataEmail[] = [
                            'email_type' => $configNoti['manage_config_notification_key'],
                            'email_subject' => $dataNotification['notification_title'],
                            'email_from' => env('MAIL_USERNAME'),
                            'email_to' => $v,
                            'email_params' => json_encode($var),
                            'is_run' => 0,
                            'created_at' => Carbon::now()
                        ];
                    }
                }

                if (count($dataEmail) != 0) {

                    $staffEmailLog->addEmail($dataEmail);
                }
            }
        } catch (\Exception $exception) {
            return '';
        }
    }

    private function insertNotificationLog($dataNotificationDetail, $dataNotification, $configNoti, $info)
    {
        try {
            if ($configNoti['is_noti'] == 1) {
                $mManageWorkSupport = new ManageWorkSupportTable();
                $listStaff = [];
                if ($configNoti['is_created'] == 1) {
                    $listStaff[$info['created_by']] = $info['created_by'];
                }

                if ($configNoti['is_processor'] == 1) {
                    $listStaff[$info['processor_id']] = $info['processor_id'];
                }

                if ($configNoti['is_approve'] == 1) {
                    $listStaff[$info['approve_id']] = $info['approve_id'];
                }

                if ($configNoti['is_support'] == 1) {
                    $listSupport = $mManageWorkSupport->getListStaffByWork($info['manage_work_id']);
                    if (count($listSupport) != 0) {
                        $listSupport = collect($listSupport)->pluck('staff_id')->toArray();

                        $listStaff = array_merge($listStaff, $listSupport);
                    }
                }

                if ($configNoti['manage_config_notification_key'] == 'comment_new') {
                    if ($info['update_comment_parent'] != null) {
                        $listStaff = [];
                        if (isset($info['update_comment']) && $info['update_comment'] != $info['update_comment_parent']) {
                            $listStaff[$info['update_comment_parent']] = $info['update_comment_parent'];
                        }
                    } else {
                        $key = array_search($info['updated_by'], $listStaff);
                        if ($key !== false) {
                            unset($listStaff[$key]);
                        }
                    }
                } else {
                    $key = array_search($info['updated_by'], $listStaff);
                    if ($key !== false) {
                        unset($listStaff[$key]);
                    }
                }


                $mNotificationDetail = app()->get(StaffNotificationDetailTable::class);
                $mStaff = app()->get(\Modules\Notification\Models\StaffTable::class);
                $oClient = new Client();
                //Insert notification detail
                $idNotificationDetail = $mNotificationDetail->add($dataNotificationDetail);
                $listStaff = array_unique($listStaff);
                if (count($listStaff)) {

                    foreach ($listStaff as $v) {

                        $response = $oClient->request('POST', NAE_SERVICE_URL . '/notification/push', [
                            'json' => [
                                'tenant_id' => session()->get('idTenant'),
                                'staff_id' => $v,
                                'title' => $dataNotification['notification_title'],
                                'message' => $dataNotification['notification_message'],
                                'detail_id' => $idNotificationDetail,
                                'avatar' => $dataNotification['notification_avatar']
                            ]
                        ]);
                    }
                }
            }
        } catch (\Exception $exception) {
            return '';
        }
    }

    /**
     * Danh sách nhắc nhở
     * @param $data
     * @return array|mixed
     * @throws ProjectRepoException
     */
    public function listRemind($data)
    {
        try {
            $mManageRemind = new ManageRemindTable();
            $list = $mManageRemind->getListRemindProject($data);
            foreach ($list as $k => $v) {
                $now = strtotime(Carbon::now()->format('Y-m-d H:i:s'));
                $dateRemind = strtotime($v['date_remind']);
                if ($dateRemind > $now) {
                    $timeRemainng = abs($now - $dateRemind) / (60 * 60 * 24);
                    $list[$k]['time_remainng'] = 'Còn lại ' . floor($timeRemainng) . ' ngày';
                } else {
                    $list[$k]['time_remainng'] = 'Quá hạn';
                }
            }
            return $this->toPagingData($list);
        } catch (\Exception | QueryException $exception) {
            throw new ProjectRepoException(ProjectRepoException::GET_MANAGE_LIST_REMIND_FAILED);
        }
    }

}