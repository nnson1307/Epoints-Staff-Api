<?php
/**
 * Created by PhpStorm   .
 * User: Mr Son
 * Date: 2020-02-19
 * Time: 4:21 PM
 * @author SonDepTrai
 */

namespace Modules\Booking\Models;


use Illuminate\Database\Eloquent\Model;

class EmailConfigTable extends Model
{
    protected $table = 'email_config';
    protected $primaryKey = 'id';


    /**
     * Lấy thông tin email config
     *
     * @param $key
     * @return mixed
     */
    public function getEmailConfig($key)
    {
        return $this
            ->select(
                "key",
                "value",
                "title",
                "content",
                "is_actived",
                "time_sent"
            )
            ->where("key", $key)
            ->first();
    }
}