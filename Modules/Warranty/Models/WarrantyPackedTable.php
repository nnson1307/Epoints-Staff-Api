<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 27/09/2022
 * Time: 09:51
 */

namespace Modules\Warranty\Models;


use Illuminate\Database\Eloquent\Model;

class WarrantyPackedTable extends Model
{
    protected $table = "warranty_packed";
    protected $primaryKey = "warranty_packed_id";

    const IS_ACTIVE = 1;
    const NOT_DELETED = 0;

    /**
     * Lấy option gói bảo hành
     *
     * @return mixed
     */
    public function getOptionPacked()
    {
        return $this
            ->select(
                "warranty_packed_id",
                "packed_code",
                "packed_name"
            )
            ->where("is_actived", self::IS_ACTIVE)
            ->where("is_deleted", self::NOT_DELETED)
            ->get();
    }

    /**
     * Lấy thông tin gói bảo hành
     *
     * @param $packedCode
     * @return mixed
     */
    public function getInfoPacked($packedCode)
    {
        return $this
            ->select(
                "warranty_packed_id",
                "packed_code",
                "packed_name",
                "time"
            )
            ->where("packed_code", $packedCode)
            ->first();
    }
}