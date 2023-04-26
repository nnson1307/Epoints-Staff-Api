<?php
/**
 * Created by PhpStorm
 * User: Mr Son
 * Date: 4/23/2020
 * Time: 1:46 PM
 */

namespace Modules\Product\Models;


use Illuminate\Database\Eloquent\Model;

class ConfigTable extends Model
{
    protected $table = "config";
    protected $primaryKey = "config_id";

    /**
     * Lấy thông tin cấu hình
     *
     * @param $key
     * @return mixed
     */
    public function getConfig($key)
    {
        return $this
            ->select(
                "config_id",
                "key",
                "value"
            )
            ->where("key", $key)
            ->first();
    }
}