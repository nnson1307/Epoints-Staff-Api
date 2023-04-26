<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 01/06/2021
 * Time: 16:35
 */

namespace Modules\Chat\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class MapRoleGroupStaffTable extends Model
{
    protected $table = "map_role_group_staff";
    protected $primaryKey = "id";

    const APP_STAFF = "app_staff";
    const PORTAL = "portal";

    /**
     * Lấy quyền role page
     *
     * @param $staff
     * @param array $arrFeature
     * @return array
     */
    public function getRolePageByStaff($staff, $arrFeature = [])
    {
        $select = $this
            ->select(
                'pages.route as route'
            )
            ->leftJoin('staffs', 'staffs.staff_id', '=', "{$this->table}.staff_id")
            ->leftJoin('role_group', 'role_group.id', '=', "{$this->table}.role_group_id")
            ->leftJoin('role_pages', 'role_pages.group_id', '=', 'role_group.id')
            ->leftJoin('pages', 'pages.id', '=', 'role_pages.page_id')
            ->join('action_group as ag', 'ag.action_group_id', '=', 'pages.action_group_id')
            ->where('ag.is_actived', 1)
            ->where("{$this->table}.is_actived", 1)
            ->where('role_group.is_actived', 1)
            ->where('role_pages.is_actived', 1)
            ->where('pages.is_actived', 1)
            ->where("ag.platform", self::PORTAL)
            ->where("{$this->table}.staff_id", $staff)
            ->whereIn("pages.route", $arrFeature)
            ->get();
        $data = [];
        if ($select != null) {
            foreach ($select as $item) {
                $data[] = $item['route'];
            }
        }

        return $data;
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
//            ->where("ag.platform", self::PORTAL)
            ->whereIn("actions.name", $arrFeature)
            ->groupBy("actions.name")
            ->get();
    }

}