<?php

/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 01/06/2021
 * Time: 16:35
 */

namespace Modules\User\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class MapRoleGroupStaffTable extends Model
{
    protected $table = "map_role_group_staff";
    protected $primaryKey = "id";

    const APP_STAFF = "app_staff";
    const PORTAL = "portal";

    /**
     * Lấy ds quyền app loyalty
     *
     * @param $staffId
     * @param array $arrFeature
     * @return mixed
     */
    public function getRoleActionByStaff($staffId, $arrFeature = [])
    {
        return $this
            ->select(
                "actions.name as widget_id",
                "actions.title as widget_name",
                "actions.hot_function as is_hot_function"
            )
            ->leftJoin("staffs", "staffs.staff_id", "=", "{$this->table}.staff_id")
            ->leftJoin("role_group", "role_group.id", "=", "{$this->table}.role_group_id")
            ->leftJoin("role_actions", "role_actions.group_id", "=", "role_group.id")
            ->leftJoin("actions", "actions.id", "=", "role_actions.action_id")
            ->join("action_group as ag", "ag.action_group_id", "=", "actions.action_group_id")
            ->where("ag.is_actived", 1)
            ->where("{$this->table}.is_actived", 1)
            ->where("role_group.is_actived", 1)
            ->where("role_actions.is_actived", 1)
            ->where("actions.is_actived", 1)
            ->where("map_role_group_staff.staff_id", $staffId)
            ->where("ag.platform", self::APP_STAFF)
            ->whereIn("actions.name", $arrFeature)
            ->orderBy("actions.position", "asc")
            ->groupBy("actions.name")
            ->get();
    }

    /**
     * Lấy tất cả quyền của action theo portal
     *
     * @param $staffId
     * @param $arrFeature
     * @return array
     */
    public function getAllRoleActionByStaff($staffId, $arrFeature = [])
    {
        return $this
            ->select(
                "actions.name as widget_id",
                "actions.title as widget_name",
                "actions.hot_function as is_hot_function"
            )
            ->leftJoin("staffs", "staffs.staff_id", "=", "{$this->table}.staff_id")
            ->leftJoin("role_group", "role_group.id", "=", "{$this->table}.role_group_id")
            ->leftJoin("role_actions", "role_actions.group_id", "=", "role_group.id")
            ->leftJoin("actions", "actions.id", "=", "role_actions.action_id")
            ->join("action_group as ag", "ag.action_group_id", "=", "actions.action_group_id")
            ->where("ag.is_actived", 1)
            ->where("{$this->table}.is_actived", 1)
            ->where("role_group.is_actived", 1)
            ->where("role_actions.is_actived", 1)
            ->where("actions.is_actived", 1)
            ->where("map_role_group_staff.staff_id", $staffId)
            ->where("ag.platform", self::PORTAL)
            ->whereIn("actions.name", $arrFeature)
            ->groupBy("actions.name")
            ->get();
    }
}
