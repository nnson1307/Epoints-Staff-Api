<?php
namespace Modules\ProjectManagement\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;


class ProjectTable extends Model
{
protected $table = "manage_project";
protected $primaryKey = "manage_project_id";

    public function addProject($dataProject)
    {
        return $this
            ->insertGetId($dataProject);
    }
    public function addTag($dataProject)
    {
        return $this
            ->insertGetId($dataProject);
    }

    public function listProject($input)
    {

        $mSelect = $this
            ->select (
                "{$this->table}.manage_project_id as project_id",
                "{$this->table}.manage_project_name as project_name",
                "{$this->table}.manage_project_status_id as project_status_id",
                "manage_project_status.manage_project_status_name as project_status_name",
                "manage_project_status.manage_project_status_color as project_status_color",
                "{$this->table}.manager_id",
                "{$this->table}.customer_id",
                "{$this->table}.date_start as from_date",
                "{$this->table}.date_end as to_date",
                "{$this->table}.date_finish",
                "{$this->table}.is_active",
                "{$this->table}.is_important",
                "{$this->table}.budget",
                "{$this->table}.resource as resource_total",
                "{$this->table}.progress",
                "{$this->table}.created_at",
                "{$this->table}.updated_at"

            )
        ->leftJoin("staffs","manage_project.manager_id","staffs.staff_id")
        ->leftJoin("manage_project_status","manage_project.manage_project_status_id","manage_project_status.manage_project_status_id")
        ->orderBy("{$this->table}.manage_project_id",'asc');


        if (isset($input['search']) != "") {
            $search = $input['search'];
            $mSelect->where(function ($query) use ($search) {
                $query->where("manage_project.manage_project_name", 'like', '%' . $search . '%')
                    ->orWhere("manage_project.manage_project_status_id", '%' . $search . '%')
                    ->orWhere("manage_project.manager_id", 'like', '%' . $search . '%');
            });
        }
        if (isset($input["created_at"]) && $input["created_at"] != null) {
            $arr_filter_create = explode(" - ", $input["created_at"]);
                $startCreateTime = Carbon::createFromFormat("d/m/Y", $arr_filter_create[0])->format("Y-m-d");
                $endCreateTime = Carbon::createFromFormat("d/m/Y", $arr_filter_create[1])->format("Y-m-d");
                $mSelect->whereBetween("{$this->table}.created_at", [$startCreateTime . " 00:00:00", $endCreateTime . " 23:59:59"]);
        }
        if (isset($input["updated_at"]) && $input["updated_at"] != null) {
            $arr_filter_update = explode(" - ", $input["updated_at"]);
                $startUpdateTime = Carbon::createFromFormat("d/m/Y", $arr_filter_update[0])->format("Y-m-d");
                $endUpdateTime = Carbon::createFromFormat("d/m/Y", $arr_filter_update[1])->format("Y-m-d");
                $mSelect->whereBetween("{$this->table}.updated_at", [$startUpdateTime . " 00:00:00", $endUpdateTime . " 23:59:59"]);
        }
        $page = (int)($input["page"] ?? 1);
        return $mSelect->paginate(PAGING_ITEM_PER_PAGE, $columns = ["*"], $pageName = "page", $page);
    }
    public function getProjectTag($id){
        $mSelect = $this
            -> select(
//                "{$this->table}.manage_project_id",
                "manage_tags.manage_tag_name"
            )
            ->join("manage_project_tag","manage_project.manage_project_id","manage_project_tag.manage_project_id")
            ->join("manage_tags","manage_project_tag.tag_id","manage_tags.manage_tag_id")
            ->where ("{$this->table}.manage_project_id",$id );
        return $mSelect->get();
    }
    public function updateStatus($projectId,$dataUpdate)
    {
        return $this
            ->where($this->table . '.manage_project_id', $projectId)
            ->update($dataUpdate);
    }

    public  function projectInfo($input){
        $mSelect = $this
            -> select (
                "{$this->table}.manage_project_id as project_id",
                "{$this->table}.manage_project_name as project_name",
                "{$this->table}.manage_project_describe as project_describe",
                "{$this->table}.manage_project_status_id as project_status_id",
                "manage_project_status.manage_project_status_name as project_status_name",
                "manage_project_status.manage_project_status_color as project_status_color",
                "{$this->table}.manager_id",
                "{$this->table}.customer_id",
                "{$this->table}.created_by",
                "{$this->table}.created_at",
                "{$this->table}.date_start as from_date",
                "{$this->table}.date_end as to_date",
                "{$this->table}.date_finish",
                "{$this->table}.prefix_code",
                "{$this->table}.is_important",
                "{$this->table}.budget",
                "{$this->table}.resource",
                "{$this->table}.progress",
                "{$this->table}.permission"
            )
            ->leftJoin("manage_project_status","{$this->table}.manage_project_status_id","manage_project_status.manage_project_status_id")
//            ->leftJoin("manage_work","manage_project.manage_project_id","manage_work.manage_project_id")
//            ->leftJoin("manage_work_tag","manage_work.manage_work_id","manage_work_tag.manage_work_id")
//            ->leftJoin("manage_tags","manage_work_tag.manage_tag_id","manage_tags.manage_tag_id")

        ->where("{$this->table}.manage_project_id" , $input['manage_project_id']);
        return $mSelect->first();

    }
    public function editProject($dataUpdate , $manage_project_id){
        return $this
            ->where($this->table . '.manage_project_id', $manage_project_id)
            ->update($dataUpdate);
    }
    public function actionDelete($input)
    {
        return $this
            ->where($this->table . '.manage_project_id', $input)
            ->delete();
    }
    public function actionIsDelete($dataUpdate , $manage_project_id){
        return $this
            ->where($this->table . '.manage_project_id', $manage_project_id)
            ->update($dataUpdate);
    }
    public function getActivities($input){
        $mSelect = $this
            ->select(
                "{$this->table}.created_at",
                "staffs.full_name as manager",
                "manage_project_history.manage_project_history_id",
                "manage_project_history.manage_work_id",
                "manage_work.manage_work_title",
                "manage_history.message"
            )
            ->leftJoin("manage_project_history", "{$this->table}.manage_project_id","manage_project_history.manage_project_id")
            ->leftJoin("manage_work", "manage_project_history.manage_work_id","manage_work.manage_work_id")
            ->leftJoin("staffs", "{$this->table}.manager_id","staffs.staff_id")
            ->leftJoin("manage_history", "manage_project_history.manage_work_id","manage_history.manage_work_id")
            ->where("manage_project_history.manage_project_id", $input['manage_project_id'])
            ->orderBy( "{$this->table}.created_at", "desc");


        if (isset($input['manage_work_id']) && $input['manage_work_id'] != null) {
            $mSelect = $mSelect->where("manage_project_history.manage_work_id",$input['manage_work_id']);
        }
        if (isset($input['manage_work_title']) && $input['manage_work_title'] != null) {
            $mSelect->where("manage_work.manage_work_title", $input['manage_work_title']);
        }
        if (isset($input['manager_id']) && $input['manager_id'] != null) {
            $mSelect->where("{$this->table}.manager_id", $input['manager_id']);
        }

        if (isset($input["created_at"]) && $input["created_at"] != null) {
            $arr_filter_create = explode(" - ", $input["created_at"]);
//            $a = strtotime(Carbon::createFromFormat('d/m/Y',$arr_filter_create[0])->format('Y-m-d'));
//            $b = strtotime(Carbon::createFromFormat('d/m/Y',$arr_filter_create[1])->format('Y-m-d'));
//            $c = abs($b - $a);
//            $d = floor($c/(60*60*24));
//                if( $d < 7) {
                    $startCreateTime = Carbon::createFromFormat("d/m/Y", $arr_filter_create[0])->format("Y-m-d");
                    $endCreateTime = Carbon::createFromFormat("d/m/Y", $arr_filter_create[1])->format("Y-m-d");
                    $mSelect->whereBetween("manage_project_history.created_at", [$startCreateTime . " 00:00:00", $endCreateTime . " 23:59:59"]);
//                }
        }
        $page = (int)($input["page"] ?? 1);
        return $mSelect->paginate(PAGING_ITEM_PER_PAGE, $columns = ["*"], $pageName = "page", $page);

    }

    public function getDetail($manage_project_id){
        return $this
            ->where($this->table.'.manage_project_id',$manage_project_id)
            ->first();
    }
}