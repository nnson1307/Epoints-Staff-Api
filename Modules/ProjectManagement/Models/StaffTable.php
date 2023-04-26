<?php
namespace Modules\ProjectManagement\Models;

use Illuminate\Database\Eloquent\Model;


class StaffTable extends Model
{
protected $table = "staffs";
protected $primaryKey = "staff_id";

    public  function getManage($filter =[]){
        $mSelect = $this
            ->select(
                "{$this->table}.staff_id",
                "{$this->table}.full_name",
                "{$this->table}.phone1",
                "{$this->table}.email",
                "{$this->table}.staff_type",
                "{$this->table}.department_id",
                "departments.department_name",
                "{$this->table}.branch_id",
                "branches.branch_name",
                "{$this->table}.birthday",
                "{$this->table}.gender",
                "{$this->table}.staff_avatar",
                "{$this->table}.address"
            )
        ->leftJoin('departments', "{$this->table}.department_id", 'departments.department_id')
        ->leftJoin('branches', "{$this->table}.branch_id", 'branches.branch_id');
        if (isset($filter['search']) != "") {
            $search = $filter['search'];
            $mSelect->where(function ($query) use ($search) {
                $query->where("staffs.full_name", 'like', '%' . $search . '%')
                    ->orWhere("staffs.phone1", '%' . $search . '%');
            });
        }
        if(isset($filter['branch_id']) && $filter['branch_id'] != null){
            $mSelect =$mSelect-> where( "{$this->table}.branch_id", $filter['branch_id']);
        }
        if(isset($filter['department_id']) && $filter['department_id'] != null){
            $mSelect =$mSelect-> where( "{$this->table}.department_id", $filter['department_id']);
        }
        return $mSelect->get()->toArray();
    }
    public function getInfoManager($filter = []){

        $mSelect = $this
            ->select(
                "{$this->table}.staff_id as manager_id",
                "{$this->table}.full_name as manager_name",
                "{$this->table}.staff_avatar as manager_avatar",
                "{$this->table}.staff_avatar as manager_avatar",
                "{$this->table}.phone1",
                "{$this->table}.email",
                "{$this->table}.staff_type"
            );
        if(isset($filter['arrIdManager']) && $filter['arrIdManager'] != '' && $filter['arrIdManager'] != null ){
            $mSelect = $mSelect->whereIn( "{$this->table}.staff_id", $filter['arrIdManager']);
        }
        if(isset($filter['created_by']) && $filter['created_by'] != '' && $filter['created_by'] != null ){
            $mSelect = $mSelect->where( "{$this->table}.staff_id", $filter['created_by']);
        }
        if(isset($filter['arrIdStaff']) && $filter['arrIdStaff'] != '' && $filter['arrIdStaff'] != null ){
            $mSelect = $mSelect->whereIn( "{$this->table}.staff_id", $filter['arrIdStaff']);
        }
        return $mSelect->get()->toArray();
    }
    public function getInfoStaff($filter = []){

        $mSelect = $this
            ->select(
                "{$this->table}.staff_id",
                "{$this->table}.full_name",
                "{$this->table}.staff_avatar",
                "{$this->table}.staff_avatar",
                "{$this->table}.phone1",
                "{$this->table}.email",
                "{$this->table}.staff_type"
            );
        if(isset($filter['arrIdStaff']) && $filter['arrIdStaff'] != '' && $filter['arrIdStaff'] != null ){
            $mSelect = $mSelect->whereIn( "{$this->table}.staff_id", $filter['arrIdStaff']);
        }
        return $mSelect->get()->toArray();
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


}