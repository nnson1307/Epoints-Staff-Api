<?php
/**
 * Created by PhpStorm   .
 * User: Mr Son
 * Date: 2020-02-19
 * Time: 11:57 PM
 * @author SonDepTrai
 */

namespace Modules\User\Models;


use Illuminate\Database\Eloquent\Model;

class SmsConfigTable extends Model
{
    protected $table = "sms_config";
    protected $primaryKey = 'id';

    /**
     * Lấy thông tin sms config
     *
     * @param $key
     * @return mixed
     */
    public function getSmsConfig($key)
    {
        return $this
            ->select(
                "key",
                "value",
                "time_sent",
                "name",
                "content",
                "is_active"
            )
            ->where("key", $key)
            ->first();
    }
}