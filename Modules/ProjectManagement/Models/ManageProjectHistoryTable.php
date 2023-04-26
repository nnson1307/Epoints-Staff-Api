<?php

namespace Modules\ProjectManagement\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;


class ManageProjectHistoryTable extends Model
{
    protected $table = "manage_project_history";
    protected $primaryKey = "manage_project_history_id";

    public function addHistory($data)
    {
        return $this
            ->insert($data);
    }

    public function getAllHistory($filter = [])
    {
        $oSelect = $this
            ->select(
                "{$this->table}.manage_project_history_id",
                "{$this->table}.manage_project_id",
                "{$this->table}.manage_project_comment_id",
                "{$this->table}.staff_id",
                "staffs.full_name as staff_name",
                "staffs.staff_avatar",
                "{$this->table}.message",
                "{$this->table}.created_at",
                "{$this->table}.created_by",
                "{$this->table}.updated_at",
                "{$this->table}.updated_by",
                "{$this->table}.action as action_type"

            )
            ->leftJoin("staffs", "{$this->table}.staff_id", "staffs.staff_id");
        if (isset($filter['manage_project_id']) && $filter['manage_project_id'] != null) {
            $oSelect->where("{$this->table}.manage_project_id", $filter['manage_project_id']);
        }
        return $oSelect->get()->toArray();
    }

    public function getListComment($filter = [])
    {
        $oSelect = $this
            ->select(
                "{$this->table}.manage_project_history_id",
                "{$this->table}.manage_project_id",
                "{$this->table}.staff_id",
                "staffs.full_name as staff_name",
                "staffs.staff_avatar",
                "{$this->table}.message",
                "{$this->table}.created_at",
                "{$this->table}.created_by",
                "{$this->table}.updated_at",
                "{$this->table}.updated_by",
                "{$this->table}.action as action_type",
                "manage_project_comment.manage_project_comment_id",
                "manage_project_comment.path"
            )
            ->orderBy("{$this->table}.created_at", "desc")
            ->leftJoin("manage_project_comment", "{$this->table}.manage_project_comment_id", "manage_project_comment.manage_project_comment_id")
            ->leftJoin("staffs", "{$this->table}.staff_id", "staffs.staff_id");

        if (isset($filter['manage_project_id']) && $filter['manage_project_id'] != null) {
            $oSelect->where("{$this->table}.manage_project_id", $filter['manage_project_id']);
        }
        if (!isset($filter['activities']) || $filter['activities'] == 0) {
            $oSelect->where("{$this->table}.action", 'comment');
        }
        return $oSelect->get();
    }

    /**
     * Tạo lịch sử
     * @param $data
     */
    public function createdHistory($data)
    {
        return $this->insert($data);
    }
}