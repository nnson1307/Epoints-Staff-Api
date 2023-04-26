<?php
/**
 * Created by PhpStorm
 * User: Mr Son
 * Date: 5/12/2020
 * Time: 1:47 PM
 */

namespace Modules\Booking\Models;


use Illuminate\Database\Eloquent\Model;

class ConfigDetailTable extends Model
{
    protected $table = "config_detail";
    protected $primaryKey = "id";

    /**
     * Lấy thông tin chi tiết cấu hình
     *
     * @param $configId
     * @return mixed
     */
    public function getDetail($configId)
    {
        return $this
            ->select(
                "id",
                "config_id",
                "key",
                "name",
                "value"
            )
            ->where("config_id", $configId)
            ->first();
    }
}