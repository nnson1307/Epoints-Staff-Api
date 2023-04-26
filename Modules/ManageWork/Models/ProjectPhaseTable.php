<?php

namespace Modules\ManageWork\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;


class ProjectPhaseTable extends Model
{
    protected $table = "manage_project_phase";
    protected $primaryKey = "manage_project_phase_id";

    public function addDefaultPhase($dataDefaultPhase){
        return $this->insertGetId($dataDefaultPhase);
    }

    public function getPhase($filter = [])
    {
        $mSelect = $this
            ->select(
                "{$this->table}.manage_project_phase_id",
                "{$this->table}.manage_project_id",
                "{$this->table}.name as phase_name",
                "{$this->table}.date_start as phase_start",
                "{$this->table}.date_end as phase_end",
                "{$this->table}.pic",
                "{$this->table}.status as phase_status",
                "{$this->table}.created_at",
                "{$this->table}.created_by",
                "staffs.full_name as staff_name",
                "staffs.staff_avatar"
            )
            ->orderBy("{$this->table}.date_start", 'asc')
            ->leftJoin("staffs", "{$this->table}.created_by", "staffs.staff_id");
        if (isset($filter['manage_project_id']) && $filter['manage_project_id'] != null) {
            $mSelect->where("{$this->table}.manage_project_id", $filter['manage_project_id']);
        }
        if (isset($filter['phase_id']) && $filter['phase_id'] != null) {
            $mSelect->where("{$this->table}.manage_project_phase_id", $filter['phase_id']);
        }
        if (isset($filter['phase_status']) && $filter['phase_status'] != null) {
            $mSelect->where("{$this->table}.status", $filter['phase_status']);
        }
        if (isset($filter["date_start"]) && $filter["date_start"] != null) {
            $arr_filter_dateStart = explode(" - ", $filter["date_start"]);
            $startDateTime = Carbon::createFromFormat("d/m/Y", $arr_filter_dateStart[0])->format("Y-m-d");
            $endDateTime = Carbon::createFromFormat("d/m/Y", $arr_filter_dateStart[1])->format("Y-m-d");
            $mSelect->whereBetween("{$this->table}.date_start", [$startDateTime . " 00:00:00", $endDateTime . " 23:59:59"]);
        }
        if (isset($filter["date_end"]) && $filter["date_end"] != null) {
            $arr_filter_dateEnd = explode(" - ", $filter["date_end"]);
            $startEndTime = Carbon::createFromFormat("d/m/Y", $arr_filter_dateEnd[0])->format("Y-m-d");
            $endEndTime = Carbon::createFromFormat("d/m/Y", $arr_filter_dateEnd[1])->format("Y-m-d");
            $mSelect->whereBetween("{$this->table}.date_end", [$startEndTime . " 00:00:00", $endEndTime . " 23:59:59"]);
        }
        return $mSelect->get()->toArray();
    }
    public  function getDefaultPhase($filter = [])
    {
        $oSelect = $this
            ->select(
                "{$this->table}.manage_project_phase_id",
                "{$this->table}.manage_project_id",
                "{$this->table}.is_default",
                "{$this->table}.name as phase_name",
                "{$this->table}.date_start as phase_start",
                "{$this->table}.date_end as phase_end",
                "{$this->table}.pic",
                "{$this->table}.status as phase_status",
                "{$this->table}.created_at",
                "{$this->table}.created_by",
                "staffs.full_name as staff_name",
                "staffs.staff_avatar"
            )
            ->orderBy("{$this->table}.date_start", 'asc')
            ->where("{$this->table}.is_default" , 1)
            ->leftJoin("staffs", "{$this->table}.created_by", "staffs.staff_id");
        if (isset($filter['manage_project_id']) && $filter['manage_project_id'] != null) {
            $oSelect->where("{$this->table}.manage_project_id", $filter['manage_project_id']);
        }
        return $oSelect->first();
    }
}