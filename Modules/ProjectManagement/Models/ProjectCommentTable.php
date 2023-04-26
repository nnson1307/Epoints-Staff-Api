<?php
namespace Modules\ProjectManagement\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;


class ProjectCommentTable extends Model
{
protected $table = "manage_project_comment";
protected $primaryKey = "manage_project_comment_id";


    public function addComment($input){
        return $this->insertGetId($input);
    }
    public  function  getListComment($input){
        $oSelect = $this
            ->select(
                "{$this->table}.manage_project_comment_id",
                "{$this->table}.manage_project_id",
                "{$this->table}.parent_id",
                "{$this->table}.staff_id",
                "staffs.full_name as staff_name",
                "staffs.staff_avatar",
                "{$this->table}.message",
                "{$this->table}.path",
                "{$this->table}.created_at",
                "{$this->table}.created_by",
                "{$this->table}.updated_at",
                "{$this->table}.updated_by"
            )
            ->leftJoin("staffs","{$this->table}.staff_id","staffs.staff_id");
        if(isset($input['manage_project_id']) && $input['manage_project_id'] != null ){
            $oSelect->where("{$this->table}.manage_project_id",$input['manage_project_id']);
        }
        $page = (int)($input["page"] ?? 1);
        return $oSelect->paginate(PAGING_ITEM_PER_PAGE, $columns = ["*"], $pageName = "page", $page);
    }
    public function getPathComment($input = []){
        $oSelect = $this
            ->select(
                "{$this->table}.manage_project_comment_id",
                "{$this->table}.manage_project_id",
                "{$this->table}.parent_id",
                "{$this->table}.staff_id",
                "staffs.full_name as staff_name",
                "staffs.staff_avatar",
                "{$this->table}.message",
                "{$this->table}.path",
                "{$this->table}.created_at",
                "{$this->table}.created_by",
                "{$this->table}.updated_at",
                "{$this->table}.updated_by"
            )
            ->leftJoin("staffs","{$this->table}.staff_id","staffs.staff_id");
        if(isset($input['created_at']) && $input['created_at'] != null ){
            $oSelect->where("{$this->table}.created_at",$input['created_at']);
        }
        return $oSelect->get();
    }
}