<?php
/**
 * Created by PhpStorm
 * User: Mr Son
 * Date: 5/12/2020
 * Time: 2:53 PM
 */

namespace Modules\Order\Models;


use Illuminate\Database\Eloquent\Model;

class PointHistoryDetailTable extends Model
{
    protected $table = "point_history_detail";
    protected $primaryKey = "point_history_detail_id";
    protected $fillable = [
        "point_history_detail_id",
        "point_history_id",
        "point_reward_rule_id",
        "created_at",
        "created_by",
        "updated_at"
    ];

    /**
     * Thêm chi tiết tích điểm
     *
     * @param array $data
     * @return mixed
     */
    public function add(array $data)
    {
        return $this->create($data);
    }
}