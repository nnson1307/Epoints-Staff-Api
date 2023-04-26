<?php
namespace Modules\ProjectManagement\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;


class ProjectStaffTable extends Model
{
protected $table = "manage_project_staff";
protected $primaryKey = "manage_project_staff_id";


    public function addMem($input){

        return $this
            ->insertGetId($input);
    }
    public  function editMem($dataEditStaff,$id){
        return $this
            ->where ("{$this->table}.manage_project_staff_id", $id)
        ->update($dataEditStaff);
    }
    public function infoMember($input){
        $mSelect = $this
            ->select (
                "{$this->table}.manage_project_staff_id",
                "{$this->table}.manage_project_id",
                "{$this->table}.staff_id",
                "{$this->table}.manage_project_role_id"
            )
        ->where("{$this->table}.manage_project_staff_id", $input['manage_project_staff_id']);
            return $mSelect->first()->toArray();
    }
    public  function getTotalManager($id){
        $mSelect = $this
            ->select('manage_project_id', DB::raw('count(*) as total'))
            ->groupBy('manage_project_id')
            ->where("{$this->table}.manage_project_id", $id)
            ->where("{$this->table}.manage_project_role_id", 1);
        return $mSelect->first();
    }
    public  function  actionDelete($id){
        return $this
            ->where($this->table . '.manage_project_staff_id', $id)
            ->delete();
    }

    public function getMemberProject($filter = []){
        $mSelect = $this
            ->select('manage_project_id', DB::raw('count(*) as total'))
            ->groupBy('manage_project_id');
        if(isset($filter['arrIdProject']) && $filter['arrIdProject'] != '' && $filter['arrIdProject']!= null ){
            $mSelect = $mSelect->whereIn("{$this->table}.manage_project_id",$filter['arrIdProject']);
        }
        if(isset($filter['manage_project_id']) && $filter['manage_project_id'] != '' && $filter['manage_project_id']!= null ){
            $mSelect = $mSelect->where("{$this->table}.manage_project_id",$filter['manage_project_id']);
        }
        return $mSelect->get()->toArray();
    }

    public function getAllMember($input)
    {
        $oSelect = $this
            ->select(
                "{$this->table}.manage_project_id",
                "{$this->table}.manage_project_staff_id",
                "{$this->table}.staff_id",
                "staffs.full_name as staff_name",
                "{$this->table}.manage_project_role_id",
                "departments.department_id",
                "departments.department_name"
            )
            ->leftJoin("staffs", "{$this->table}.staff_id", "staffs.staff_id")
            ->leftJoin("departments", "staffs.department_id", "departments.department_id")
            ->where("{$this->table}.manage_project_id", $input['manage_project_id']);
        return $oSelect->get()->toArray();

    }
}