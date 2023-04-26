<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 24/10/2021
 * Time: 14:22
 */

namespace Modules\User\Models;


use Illuminate\Database\Eloquent\Model;

class ActionTable extends Model
{
    protected $table = 'actions';
    protected $primaryKey = 'id';

    const PORTAL = "portal";

    /**
     * Lấy tất cả quyền của action
     *
     * @param $arrFeature
     * @return array
     */
    public function getAllRoute($arrFeature = [])
    {
        return $this
            ->select(
                "actions.name as widget_id",
                "actions.title as widget_name"
            )
            ->join('action_group as ag', 'ag.action_group_id', '=', 'actions.action_group_id')
            ->where('ag.is_actived', 1)
            ->where('actions.is_actived',1)
            ->where("ag.platform", self::PORTAL)
            ->whereIn("{$this->table}.name", $arrFeature)
            ->get();
    }
}