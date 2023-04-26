<?php
namespace Modules\ProjectManagement\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;


class ProjectMemberTable extends Model
{
protected $table = "manage_project_staff";
protected $primaryKey = "manage_project_staff_id";

    public  function getListMem($input = []){
        $mSelect = $this
            ->select(
                "{$this->table}.manage_project_staff_id",
                "{$this->table}.manage_project_id",
                "{$this->table}.staff_id",
                "{$this->table}.manage_project_role_id as role_id",
                "manage_project_role.manage_project_role_name as role_name",
                "staffs.department_id",
                "staffs.branch_id",
                "staffs.full_name",
                "staffs.full_name",
                "staffs.gender",
                "staffs.phone1",
                "staffs.email",
                "staffs.staff_avatar",
                "staffs.staff_title_id",
                "staff_title.staff_title_name",
                "staff_title.slug",
                "staff_title.staff_title_code"
            )
            ->leftJoin("staffs","{$this->table}.staff_id","staffs.staff_id")
            ->leftJoin("staff_title","staffs.staff_title_id","staff_title.staff_title_id")
            ->leftJoin("manage_project_role","{$this->table}.manage_project_role_id","manage_project_role.manage_project_role_id")
        ;

        if(isset($input['manage_project_id']) && $input['manage_project_id'] != '' && $input['manage_project_id'] != null ){
            $mSelect = $mSelect->where( "{$this->table}.manage_project_id", $input['manage_project_id']);
        }
        if(isset($input['project_role_id']) && $input['project_role_id'] != '' && $input['project_role_id'] != null ){
            $mSelect = $mSelect->where( "{$this->table}.manage_project_role_id", $input['project_role_id']);
        }
        if(isset($input['staff_title_id']) && $input['staff_title_id'] != '' && $input['staff_title_id'] != null ){
            $mSelect = $mSelect->where( "staff_title.staff_title_id", $input['staff_title_id']);
        }
        if(isset($input['department_id']) && $input['department_id'] != '' && $input['department_id'] != null ){
            $mSelect = $mSelect->where(  "staffs.department_id", $input['department_id']);
        }
        if(isset($input['branch_id']) && $input['branch_id'] != '' && $input['branch_id'] != null ){
            $mSelect = $mSelect->where(  "staffs.branch_id", $input['branch_id']);
        }
//        $page = (int)($input["page"] ?? 1);
//        return $mSelect->paginate(PAGING_ITEM_PER_PAGE, $columns = ["*"], $pageName = "page", $page);
        return $mSelect->get();
    }
}