<?php

namespace Modules\ProjectManagement\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;


class ProjectIssueTable extends Model
{
    protected $table = "manage_project_issue";
    protected $primaryKey = "manage_project_issue_id";

    public function addIssue($data)
    {
        return $this->insertGetId($data);
    }

    public function listIssue($filter = [])
    {
        $oSelect = $this
            ->select(
                "{$this->table}.manage_project_issue_id as project_issue_id",
                "{$this->table}.parent_id",
                "{$this->table}.manage_project_id as project_id",
                "{$this->table}.content",
                "{$this->table}.status",
                "{$this->table}.created_at",
                "{$this->table}.created_by as staff_id",
                "staffs.full_name as staff_name",
                "staffs.staff_avatar"

            )
            ->orderBy("{$this->table}.created_at", 'desc')
            ->leftJoin("staffs", "{$this->table}.created_by", "staffs.staff_id");
        if (isset($filter['manage_project_id']) && $filter['manage_project_id'] != null) {
            $oSelect->where("{$this->table}.manage_project_id", $filter['manage_project_id']);
        }
        if (isset($filter['issue_status']) && $filter['issue_status'] != null) {
            $oSelect->where("{$this->table}.status", $filter['issue_status']);
        }
        if (isset($filter['staff_id']) && $filter['staff_id'] != null) {
            $oSelect->where("{$this->table}.created_by", $filter['staff_id']);
        }
        if (isset($filter["created_at"]) && $filter["created_at"] != null) {
            $arr_filter_update = explode(" - ", $filter["created_at"]);
            $startCreateTime = Carbon::createFromFormat("d/m/Y", $arr_filter_update[0])->format("Y-m-d");
            $endCreateTime = Carbon::createFromFormat("d/m/Y", $arr_filter_update[1])->format("Y-m-d");
            $oSelect->whereBetween("{$this->table}.created_at", [$startCreateTime . " 00:00:00", $endCreateTime . " 23:59:59"]);
        }
        $page = (int)($input["page"] ?? 1);
        return $oSelect->paginate(PAGING_ITEM_PER_PAGE, $columns = ["*"], $pageName = "page", $page);
    }
}