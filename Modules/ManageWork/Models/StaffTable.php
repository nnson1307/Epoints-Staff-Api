<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 26/05/2021
 * Time: 14:37
 */

namespace Modules\ManageWork\Models;


use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class StaffTable extends Model
{
    protected $table = "staffs";
    protected $primaryKey = "staff_id";

    /**
     * lấy danh sách nhân viên
     */
    public function getListStaff($data){
        $oSelect = $this
            ->select(
                "{$this->table}.staff_id",
                "{$this->table}.full_name as staff_name",
                "{$this->table}.staff_avatar",
                "branches.branch_id",
                "branches.branch_name",
                "{$this->table}.department_id",
                "departments.department_name"
            )
            ->join("branches","{$this->table}.branch_id","branches.branch_id")
            ->join("departments","{$this->table}.department_id","departments.department_id")
            ->where("{$this->table}.is_actived",1)
            ->where("{$this->table}.is_deleted",0);

        if (isset($data['staff_name'])){
            $oSelect = $oSelect->where('full_name','like','%'.$data['staff_name'].'%');
        }

        if (isset($data['branch_id'])){
            $oSelect = $oSelect->where('branch_id',$data['branch_id']);
        }

        return $oSelect->orderBy("{$this->table}.created_at",'DESC')->get();
    }

    /**
     * lấy danh sách nhân viên jobOverview
     */
    public function staffNoJob($data){
        $oSelect = $this
            ->select(
                $this->table.'.staff_id',
                $this->table.'.full_name as staff_name',
                $this->table.'.staff_avatar',
                'manage_work.manage_work_id',
                'departments.department_name as role_name'
            )
            ->join('manage_work','manage_work.processor_id',$this->table.'.staff_id')
            ->join('departments','departments.department_id',$this->table.'.department_id')
            ->where($this->table.'.is_actived',1)
            ->where($this->table.'.is_deleted',0);

        if (isset($data['list_staff_no_started_work'])){
            $oSelect = $oSelect->where('manage_work.manage_status_id',1);
        }

        if (isset($data['staff_name'])){
            $oSelect = $oSelect->where($this->table.'.full_name','like','%'.$data['staff_name'].'%');
        }

        if (isset($data['from_date']) && isset($data['to_date']) && !isset($data['job_overview'])){
            $start = $data['from_date'];
            $end = $data['to_date'];
            $oSelect = $oSelect->where(function ($sql) use ($start,$end){
                $sql->whereBetween('manage_work.date_start',[$start,$end])
                    ->orWhereBetween('manage_work.date_end',[$start,$end])
                    ->orWhere(function ($sql1) use ($start){
                        $sql1
                            ->where('manage_work.date_start','<=',$start)
                            ->where('manage_work.date_end','>=',$start);
                    })
                    ->orWhere(function ($sql1) use ($end){
                        $sql1
                            ->where('manage_work.date_start','<=',$end)
                            ->where('manage_work.date_end','>=',$end);
                    });
            });
        } else {
//            $oSelect = $oSelect
//                ->where('manage_work.date_start','<=', Carbon::now())
//                ->where('manage_work.date_end','>=', Carbon::now());

            $oSelect = $oSelect
                ->where(function ($sql){
                    $sql->whereNull('manage_work.date_start')
                        ->where('manage_work.date_end','>=', Carbon::now());
                })
                ->orWhere(function ($sql){
                    $sql->where('manage_work.date_start','<=', Carbon::now())
                        ->where('manage_work.date_end','>=', Carbon::now());
                });
        }


        //        Tìm kiếm chi nhánh
        if (isset($data['branch_id'])){
            $oSelect = $oSelect->where($this->table.'.branch_id',$data['branch_id']);
        }

//        Tìm kiếm phòng ban
        if (isset($data['department_id'])){
            $oSelect = $oSelect->where($this->table.'.department_id',$data['department_id']);
        }

        //        Tìm kiếm dự án
        if (isset($data['manage_project_id'])){
            $oSelect = $oSelect->where('manage_work.manage_project_id',$data['manage_project_id']);
        }
        $oSelect = $this->permission($oSelect);
        return $oSelect->groupBy($this->table.'.staff_id')->get();
    }

    public function permission($oSelect){
        $user = Auth::user();
        $userId = $user->staff_id;

        $dataRole = DB::table('map_role_group_staff')
            ->select('manage_role.role_group_id', 'is_all', 'is_branch', 'is_department', 'is_own')
            ->join('manage_role', 'manage_role.role_group_id', 'map_role_group_staff.role_group_id')
            ->where('staff_id', $userId)
            ->get()->toArray();

        $isAll = $isBranch = $isDepartment = $isOwn = 0;

        foreach ($dataRole as $role){
            $role = (array)$role;
            if($role['is_all']){
                $isAll = 1;
            }

            if($role['is_branch']){
                $isBranch = 1;
            }

            if($role['is_department']){
                $isDepartment = 1;
            }

            if($role['is_own']){
                $isOwn = 1;
            }
        }

        if($isAll){

        } else if ($isBranch){
            $myBrand = $user->branch_id;

            $oSelect->where($this->table.'.branch_id', $myBrand);
        } else if ($isDepartment){
            $myDep= $user->department_id;
            $oSelect->where($this->table.'.department_id', $myDep);
        } else {
            // where de khong ra
            $oSelect->where($this->table.'.department_id', 'vund');
        }

        return $oSelect;
    }

    /**
     * Danh sách nhân viên chưa có việc làm
     * @param $arrIdStaff
     */
    public function getListStaffNoJob($arrIdStaff){
        $oSelect = $this
            ->select(
                $this->table.'.staff_id',
                $this->table.'.full_name as staff_name',
                $this->table.'.staff_avatar',
                'departments.department_name as role_name'
            )
            ->join('departments','departments.department_id',$this->table.'.department_id')
            ->where($this->table.'.is_actived',1)
            ->where($this->table.'.is_deleted',0)
            ->whereNotIn($this->table.'.staff_id',$arrIdStaff);

        return $oSelect->groupBy($this->table.'.staff_id')->get();
    }

    public function getListStaffByArrId($arrIdStaff){
        $oSelect = $this
            ->select(
                $this->table.'.staff_id',
                $this->table.'.full_name as staff_name',
                $this->table.'.staff_avatar',
                $this->table.'.email'
            )
            ->where($this->table.'.is_actived',1)
            ->where($this->table.'.is_deleted',0)
            ->whereIn($this->table.'.staff_id',$arrIdStaff);

        return $oSelect->groupBy($this->table.'.staff_id')->get();
    }

    /**
     * Lấy nhân viên theo id
     */
    public function getStaffId($staffId){
        $oSelect = $this
            ->select(
                $this->table.'.staff_id',
                $this->table.'.full_name as staff_name',
                $this->table.'.staff_avatar'
            )
            ->where($this->table.'.staff_id',$staffId);

        return $oSelect->first();
    }

    /**
     * lấy danh sách nhân viên theo dự án
     */
    public function getListStaffNew($data){
        $oSelect = $this
            ->select(
                $this->table.'.staff_id',
                $this->table.'.full_name as staff_name',
                $this->table.'.staff_avatar as staff_avatar',
                $this->table.'.branch_id',
                "{$this->table}.department_id"
            )
            ->where($this->table.'.is_actived',1)
            ->where($this->table.'.is_deleted',0);

        if (isset($data['staff_name'])){
            $oSelect = $oSelect->where($this->table.'.full_name','like','%'.$data['staff_name'].'%');
        }

        if (isset($data['branch_id'])){
            $oSelect = $oSelect->where($this->table.'.branch_id',$data['branch_id']);
        }

        if (isset($data['manage_project_id']) || isset($data['project_id'])){
            $manageProjectId = isset($data['manage_project_id']) ? $data['manage_project_id'] : (isset($data['project_id']) ? $data['project_id'] : null);
            $oSelect = $oSelect
                ->join('manage_project_staff','manage_project_staff.staff_id',$this->table.'.staff_id')
                ->where('manage_project_staff.manage_project_id',$manageProjectId);
        }

        return $oSelect
            ->groupBy($this->table.'.staff_id')
            ->orderBy($this->table.'.created_at','DESC')->get();
    }
}