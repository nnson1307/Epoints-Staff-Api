<?php
/**
 * Created by PhpStorm
 * User: Mr Son
 * Date: 5/12/2020
 * Time: 2:55 PM
 */

namespace Modules\Booking\Models;


use Illuminate\Database\Eloquent\Model;

class PointRewardRuleTable extends Model
{
    protected $table = "point_reward_rule";
    protected $primaryKey = "point_reward_rule_id";

    /**
     * Lấy thông tin cấu hình tích điểm
     *
     * @param $ruleCode
     * @return mixed
     */
    public function getRule($ruleCode)
    {
        return $this
            ->select(
                "point_reward_rule_id",
                "rule_name",
                "rule_code",
                "point_maths",
                "point_value"
            )
            ->where("rule_code", $ruleCode)
            ->first();
    }
}