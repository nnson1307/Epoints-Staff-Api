<?php
/**
 * Created by PhpStorm   .
 * User: Mr Son
 * Date: 2020-02-19
 * Time: 11:49 PM
 * @author SonDepTrai
 */

namespace Modules\Booking\Models;


use Illuminate\Database\Eloquent\Model;

class SmsSettingBrandNameTable extends Model
{
    protected $table = 'sms_setting_brandname';
    protected $primaryKey = 'id';

    /**
     * Lấy thông tin setting brand name
     *
     * @param $id
     * @return mixed
     */
    public function getSetting($id)
    {
        return $this
            ->select(
                "provider",
                "value",
                "type",
                "account",
                "password",
                "is_actived"
            )
            ->where("id", $id)
            ->first();
    }
}