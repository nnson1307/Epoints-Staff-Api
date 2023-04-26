<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 28/09/2022
 * Time: 11:28
 */

namespace Modules\Chat\Models;


use Illuminate\Database\Eloquent\Model;

class PageTable extends Model
{
    protected $table = 'pages';
    protected $primaryKey = 'id';

    const PORTAL = "portal";

    /**
     * Lấy tất cả quyền của page
     *
     * @param $arrFeature
     * @return array
     */
    public function getAllRoute($arrFeature = [])
    {
        $select = $this
            ->select(
                'pages.route'
            )
            ->join('action_group as ag', 'ag.action_group_id', '=', 'pages.action_group_id')
            ->where('pages.is_actived', 1)
            ->where('ag.is_actived', 1)
            ->where("ag.platform", self::PORTAL)
            ->whereIn("{$this->table}.route", $arrFeature)
            ->get();
        $data = [];
        if ($select != null) {
            foreach ($select->toArray() as $item) {
                $data[] = $item['route'];
            }
        }
        return $data;
    }
}