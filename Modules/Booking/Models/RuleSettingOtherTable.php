<?php
/**
 * Created by PhpStorm   .
 * User: Mr Son
 * Date: 2020-02-17
 * Time: 2:25 PM
 * @author SonDepTrai
 */

namespace Modules\Booking\Models;


use Illuminate\Database\Eloquent\Model;

class RuleSettingOtherTable extends Model
{
    protected $table = 'rule_setting_other';
    protected $primaryKey = 'id';

    /**
     * Lấy rule setting other
     *
     * @return mixed
     */
    public function getSettingOther()
    {
        return $this
            ->select(
                "id",
                "name",
                "is_actived",
                "day"
            )
            ->get();
    }
}