<?php

namespace Modules\ProjectManagement\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;


class WorkTable extends Model
{
    protected $table = "manage_work";
    protected $primaryKey = "manage_work_id";


    public function getTotalWork($filter = [])
    {

        $mSelect = $this
            ->select('manage_project_id', DB::raw('count(*) as total'))
            ->groupBy('manage_project_id');
        if (isset($filter['arrIdProject']) && $filter['arrIdProject'] != '' && $filter['arrIdProject'] != null) {
            $mSelect = $mSelect->whereIn("{$this->table}.manage_project_id", $filter['arrIdProject']);
        };
        if (isset($filter['manage_project_id']) && $filter['manage_project_id'] != '' && $filter['manage_project_id'] != null) {
            $mSelect = $mSelect->where("{$this->table}.manage_project_id", $filter['manage_project_id']);
        };
        return $mSelect->get()->toArray();
    }

    public function getTotalWorkComplete($filter = [])
    {
        $mSelect = $this
            ->select('manage_project_id', DB::raw('count(*) as total'))
            ->groupBy('manage_project_id')
            ->where("{$this->table}.manage_status_id", 6);
        if (isset($filter['arrIdProject']) && $filter['arrIdProject'] != '' && $filter['arrIdProject'] != null) {
            $mSelect = $mSelect->whereIn("{$this->table}.manage_project_id", $filter['arrIdProject']);
        };
        if (isset($filter['manage_project_id']) && $filter['manage_project_id'] != '' && $filter['manage_project_id'] != null) {
            $mSelect = $mSelect->where("{$this->table}.manage_project_id", $filter['manage_project_id']);
        };
        return $mSelect->get()->toArray();
    }

    public function getAllWork($filter = [])
    {
        $mSelect = $this
            ->select(
                "{$this->table}.manage_work_id as work_id",
                "{$this->table}.manage_work_customer_type as customer_type",
                "{$this->table}.manage_project_id as project_id",
                "{$this->table}.manage_work_code as work_code",
                "{$this->table}.manage_work_title as work_title",
                "{$this->table}.manage_type_work_id as type_work_id",
                "manage_type_work.manage_type_work_key as type_work_key",
                "manage_type_work.manage_type_work_name as type_work_name",
                "manage_status.manage_status_id as status_id",
                "manage_status.manage_status_name as status_name",
                "{$this->table}.manage_project_phase_id as phase_id",
                "manage_project_phase.name as phase_name",
                "manage_project_phase.date_start as phase_start",
                "manage_project_phase.date_end as phase_end",
                "{$this->table}.processor_id",
                "{$this->table}.date_start",
                "{$this->table}.date_end",
                "{$this->table}.date_finish",
                "{$this->table}.progress",
                "{$this->table}.time",
                "{$this->table}.time_type",
                "departments.department_id",
                "departments.department_name"
            )
            ->leftJoin("manage_type_work", "{$this->table}.manage_type_work_id", "manage_type_work.manage_type_work_id")
            ->leftJoin("manage_status", "{$this->table}.manage_status_id", "manage_status.manage_status_id")
            ->leftJoin("manage_project_phase", "{$this->table}.manage_project_phase_id", "manage_project_phase.manage_project_phase_id")
            ->leftJoin("staffs", "{$this->table}.processor_id", "staffs.staff_id")
            ->leftJoin("departments", "staffs.department_id", "departments.department_id");
        if (isset($filter['manage_project_id']) && $filter['manage_project_id'] != '' && $filter['manage_project_id'] != null) {
            $mSelect = $mSelect->where("{$this->table}.manage_project_id", $filter['manage_project_id']);
        }
        if (isset($filter['arrIdProject']) && $filter['arrIdProject'] != '' && $filter['arrIdProject'] != null) {
            $mSelect = $mSelect->whereIn("{$this->table}.manage_project_id", $filter['arrIdProject']);
        }
        if (isset($filter['status_id']) && $filter['status_id'] != '' && $filter['status_id'] != null && $filter['status_id'] != 0) {
            $mSelect = $mSelect->where("{$this->table}.manage_status_id", $filter['status_id']);
        }
        if (isset($filter['manage_project_phase_id']) && $filter['manage_project_phase_id'] != '' && $filter['manage_project_phase_id'] != null) {
            $mSelect = $mSelect->where("{$this->table}.manage_project_phase_id", $filter['manage_project_phase_id']);
        }

        return $mSelect->get();
    }

    public function detailWork($manage_work_id)
    {
        $customer = __('Khách hàng');
        $customerLead = __('Khách hàng tiềm năng');
        $listDeal = __('Danh sách deal');

        $high = __('Cao');
        $normal = __('Bình thường');
        $low = __('Thấp');

        $user = Auth()->id();
        $oSelect = $this
            ->select(
                $this->table . '.manage_work_id',
                $this->table . '.manage_work_title',
                $this->table . '.manage_work_customer_type',
                Db::raw("IF(manage_work.manage_work_customer_type = 'customer', '$customer',(IF(manage_work.manage_work_customer_type = 'lead', '$customerLead',(IF(manage_work.manage_work_customer_type = 'deal','$listDeal',''))))) as manage_work_customer_type_title"),
                $this->table . '.parent_id',
                $this->table . '.progress',
                'parent.manage_work_title as parent_name',
                $this->table . '.processor_id',
                'processor.full_name as processor_name',
                'processor.staff_avatar as processor_avatar',
                $this->table . '.assignor_id',
                'assignor.full_name as assignor_name',
                'assignor.staff_avatar as assignor_avatar',
                $this->table . '.date_start',
                $this->table . '.date_end',
                $this->table . '.description',
                $this->table . '.date_finish',
                $this->table . '.manage_project_id',
                'manage_project.manage_project_name',
                $this->table . '.manage_type_work_id',
                'manage_type_work.manage_type_work_name',
                $this->table . '.priority',
                DB::raw("IF(manage_work.priority = 1 , '$high' , IF(manage_work.priority = 2 , '$normal', '$low') ) as priority_name"),
                $this->table . '.type_card_work',
                DB::raw("IF(manage_work.type_card_work = 'bonus' , '$normal', IF(manage_work.type_card_work = 'kpi' , 'Kpi','') ) as type_card_work_name"),
                $this->table . '.repeat_type',
                $this->table . '.repeat_end',
                $this->table . '.repeat_end_time',
                $this->table . '.repeat_end_type',
                $this->table . '.repeat_end_full_time',
                $this->table . '.repeat_time',
                $this->table . '.approve_id',
                'approve.full_name as approve_name',
                $this->table . '.manage_status_id',
                $this->table . '.time',
                $this->table . '.time_type',
                $this->table . '.customer_id',
                $this->table . '.is_approve_id',
                $this->table . '.branch_id',
                //                'customers.full_name as customer_name',
                DB::raw("IF(manage_work.manage_work_customer_type = 'customer' , customers.full_name , (IF(manage_work.manage_work_customer_type = 'lead' , lead.full_name , (IF(manage_work.manage_work_customer_type = 'deal', deal.deal_name , ''))))) as customer_name"),
                'manage_status.manage_status_name',
                'manage_status.manage_status_color',
                //                'manage_status_config.is_edit',
                DB::raw("IF((manage_work.processor_id = {$user} OR manage_work.assignor_id = {$user}) AND manage_status_config.is_edit = 1, 1,0) as is_edit"),
                DB::raw("IF((manage_work.processor_id = {$user} OR manage_work.assignor_id = {$user}) AND manage_status_config.is_deleted = 1, 1,0) as is_deleted"),
                //                'manage_status_config.is_deleted',
                DB::raw("IF(manage_work.approve_id = {$user} AND manage_work.manage_status_id = 3, 1,0) as is_approve"),
                DB::raw("(SELECT COUNT(manage_work_parent.manage_work_id) FROM manage_work as manage_work_parent where manage_work.manage_work_id = manage_work_parent.parent_id ) as total_child_job"),
                "{$this->table}.create_object_type",
                "{$this->table}.create_object_id"
            )
            ->leftJoin('manage_work as parent', 'parent.manage_work_id', $this->table . '.parent_id')
            ->leftJoin('staffs as processor', 'processor.staff_id', $this->table . '.processor_id')
            ->leftJoin('staffs as assignor', 'assignor.staff_id', $this->table . '.assignor_id')
            ->leftJoin('staffs as approve', 'approve.staff_id', $this->table . '.approve_id')
            ->leftJoin('manage_project', 'manage_project.manage_project_id', $this->table . '.manage_project_id')
            ->leftJoin('manage_type_work', 'manage_type_work.manage_type_work_id', $this->table . '.manage_type_work_id')
            ->leftJoin('customers', 'customers.customer_id', $this->table . '.customer_id')
            ->leftJoin('cpo_deals as deal', 'deal.deal_id', $this->table . '.customer_id')
            ->leftJoin('cpo_customer_lead as lead', 'lead.customer_lead_id', $this->table . '.customer_id')
            ->leftJoin('manage_status_config', 'manage_status_config.manage_status_id', '=', "{$this->table}.manage_status_id")
            ->join('manage_status', 'manage_status.manage_status_id', $this->table . '.manage_status_id')
            ->where($this->table . '.manage_work_id', $manage_work_id);

        $oSelect = $this->getPermission($oSelect);

        return $oSelect->first();
    }

    public function getPermission($oSelect)
    {
        $user = Auth::user();

        $userId = $user->staff_id;

        $dataRole = DB::table('map_role_group_staff')
            ->select('manage_role.role_group_id', 'is_all', 'is_branch', 'is_department', 'is_own')
            ->join('manage_role', 'manage_role.role_group_id', 'map_role_group_staff.role_group_id')
            ->where('staff_id', $userId)
            ->get()->toArray();

        $isAll = $isBranch = $isDepartment = $isOwn = 0;
        foreach ($dataRole as $role) {
            $role = (array)$role;
            if ($role['is_all']) {
                $isAll = 1;
            }

            if ($role['is_branch']) {
                $isBranch = 1;
            }

            if ($role['is_department']) {
                $isDepartment = 1;
            }

            if ($role['is_own']) {
                $isOwn = 1;
            }
        }
        $listManageSupport = DB::table('manage_work_support')
            ->where('staff_id', $userId)
            ->get()->pluck('manage_work_id')->toArray();

        if ($isAll) {
        } else if ($isBranch) {
            $myBrand = $user->branch_id;

            //            $oSelect = $oSelect->join('staffs as per_staff', function ($join) use($myBrand){
            //                $join->on('per_staff.staff_id', '=', $this->table.'.processor_id')->on('per_staff.branch_id', '=', DB::raw($myBrand));
            //            });

            $oSelect = $oSelect->where(function ($sql) use ($userId, $listManageSupport, $myBrand) {
                $sql->join('staffs as per_staff', function ($join) use ($myBrand) {
                    $join->on('per_staff.staff_id', '=', $this->table . '.processor_id')->on('per_staff.branch_id', '=', DB::raw($myBrand));
                })->orWhere($this->table . '.processor_id', $userId)->orWhere($this->table . '.assignor_id', $userId)
                    ->orWhere($this->table . '.approve_id', $userId)->orWhereIn($this->table . '.manage_work_id', $listManageSupport);
            });
        } else if ($isDepartment) {
            $myDep = $user->department_id;

            //            $oSelect = $oSelect->join('staffs as per_staff', function ($join) use($myDep){
            //                $join->on('per_staff.staff_id', '=', $this->table.'.processor_id')->on('per_staff.department_id', '=', DB::raw($myDep));
            //            });

            $oSelect = $oSelect->where(function ($sql) use ($userId, $listManageSupport, $myDep) {
                $sql->join('staffs as per_staff', function ($join) use ($myDep) {
                    $join->on('per_staff.staff_id', '=', $this->table . '.processor_id')->on('per_staff.department_id', '=', DB::raw($myDep));
                })->orWhere($this->table . '.processor_id', $userId)->orWhere($this->table . '.assignor_id', $userId)
                    ->orWhere($this->table . '.approve_id', $userId)->orWhereIn($this->table . '.manage_work_id', $listManageSupport);
            });
        } else {
            $listManageSupport = DB::table('manage_work_support')
                ->where('staff_id', $userId)
                ->get()->pluck('manage_work_id')->toArray();

            $oSelect = $oSelect->where(function ($query) use ($userId, $listManageSupport) {
                $query->where($this->table . '.processor_id', $userId)->orWhere($this->table . '.assignor_id', $userId)
                    ->orWhere($this->table . '.approve_id', $userId)->orWhereIn($this->table . '.manage_work_id', $listManageSupport);
            });
        }

        return $oSelect;
    }

    /**
     * Chỉnh sửa công việc
     * @param $data
     * @param $id
     */
    public function editWork($data, $id)
    {
        if (isset($data['manage_status_id']) && $data['manage_status_id'] == 6 && !isset($data['date_finish'])) {
            $data['date_finish'] = Carbon::now();
        }

        return $this->where('manage_work_id', $id)->update($data);
    }

    /**
     * Lấy chi tiết công việc
     * @param $manage_work_id
     */
    public function detailWorkNoti($manage_work_id)
    {
        if (Auth::user() != null) {
            $user = Auth::id();
        } else {
            $user = 0;
        }
        return $this
            ->select(
                $this->table . '.manage_work_id',
                $this->table . '.manage_work_title',
                $this->table . '.parent_id',
                $this->table . '.progress',
                'parent.manage_work_title as parent_name',
                $this->table . '.processor_id',
                'processor.full_name as processor_name',
                'processor.staff_avatar as processor_avatar',
                $this->table . '.assignor_id',
                $this->table . '.assignor_id as created_by',
                'assignor.full_name as assignor_name',
                'assignor.staff_avatar as assignor_avatar',
                $this->table . '.date_start',
                $this->table . '.date_end',
                $this->table . '.description',
                $this->table . '.date_finish',
                $this->table . '.manage_project_id',
                'manage_project.manage_project_name',
                $this->table . '.manage_type_work_id',
                'manage_type_work.manage_type_work_name',
                $this->table . '.priority',
                DB::raw("IF(manage_work.priority = 1 , 'Cao' , IF(manage_work.priority = 2 , 'Bình thường','Thấp') ) as priority_name"),
                $this->table . '.type_card_work',
                DB::raw("IF(manage_work.type_card_work = 'bonus' , 'Thường' , IF(manage_work.type_card_work = 'kpi' , 'Kpi','') ) as type_card_work_name"),
                $this->table . '.repeat_type',
                $this->table . '.repeat_end',
                $this->table . '.repeat_end_time',
                $this->table . '.repeat_end_type',
                $this->table . '.repeat_end_full_time',
                $this->table . '.repeat_time',
                $this->table . '.approve_id',
                'approve.full_name as approve_name',
                'updated.full_name as updated_name',
                'updated.email as updated_email',
                $this->table . '.manage_status_id',
                $this->table . '.time',
                $this->table . '.time_type',
                $this->table . '.customer_id',
                $this->table . '.is_approve_id',
                $this->table . '.created_by',
                $this->table . '.updated_by',
                $this->table . '.branch_id',
                'customers.full_name as customer_name',
                'manage_status.manage_status_name',
                'manage_status.manage_status_color',
                DB::raw("IF((manage_work.processor_id = {$user} OR manage_work.assignor_id = {$user}) AND manage_status_config.is_edit = 1, 1,0) as is_edit"),
                DB::raw("IF((manage_work.processor_id = {$user} OR manage_work.assignor_id = {$user}) AND manage_status_config.is_deleted = 1, 1,0) as is_deleted"),
                DB::raw("(SELECT COUNT(manage_work_parent.manage_work_id) FROM manage_work as manage_work_parent where manage_work.manage_work_id = manage_work_parent.parent_id ) as total_child_job")
            )
            ->leftJoin('manage_work as parent', 'parent.manage_work_id', $this->table . '.parent_id')
            ->leftJoin('staffs as processor', 'processor.staff_id', $this->table . '.processor_id')
            ->leftJoin('staffs as assignor', 'assignor.staff_id', $this->table . '.assignor_id')
            ->leftJoin('staffs as approve', 'approve.staff_id', $this->table . '.approve_id')
            ->leftJoin('staffs as updated', 'updated.staff_id', $this->table . '.updated_by')
            ->leftJoin('manage_project', 'manage_project.manage_project_id', $this->table . '.manage_project_id')
            ->leftJoin('manage_type_work', 'manage_type_work.manage_type_work_id', $this->table . '.manage_type_work_id')
            ->leftJoin('customers', 'customers.customer_id', $this->table . '.customer_id')
            ->leftJoin('manage_status', 'manage_status.manage_status_id', $this->table . '.manage_status_id')
            ->leftJoin('manage_status_config', 'manage_status_config.manage_status_id', $this->table . '.manage_status_id')
            ->where($this->table . '.manage_work_id', $manage_work_id)
            ->first();
    }

}