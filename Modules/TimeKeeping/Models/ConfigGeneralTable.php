<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 24/10/2022
 * Time: 18:44
 */

namespace Modules\TimeKeeping\Models;


use Illuminate\Database\Eloquent\Model;

class ConfigGeneralTable extends Model
{
    protected $table = "sf_config_general";
    protected $primaryKey = "config_general_id";
    protected $fillable = [
        "config_general_id",
        "config_general_code",
        "config_general_value",
        "config_general_unit",
        "is_actived",
        "updated_by",
        "created_at",
        "updated_at"
    ];

    const IS_ACTIVE = 1;

    /**
     * Lấy tất cả cấu hình chung
     *
     * @return mixed
     */
    public function getConfig()
    {
        return $this
            ->select(
                "config_general_id",
                "config_general_code",
                "config_general_value",
                "config_general_unit",
                "is_actived"
            )
            ->get();
    }

    /**
     * Chỉnh sửa cấu hình chung
     *
     * @param array $data
     * @param $generalId
     * @return mixed
     */
    public function edit(array $data, $generalId)
    {
        return $this->where("config_general_id", $generalId)->update($data);
    }
}