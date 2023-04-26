<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 02/06/2021
 * Time: 17:10
 */

namespace Modules\Order\Models;


use Illuminate\Database\Eloquent\Model;

class WarrantyPackageDetailTable extends Model
{
    protected $table = "warranty_packed_detail";
    protected $primaryKey = "warranty_packed_detail_id";
    protected $fillable = [
        'warranty_packed_detail_id',
        'warranty_packed_code',
        'object_type',
        'object_id',
        'object_code',
        'updated_at',
        'created_at',
    ];
    const IS_ACTIVE = 1;
    const NOT_DELETE = 0;

    /**
     * Lấy chi tiết gói bảo hành theo object (object unique)
     *
     * @param $objectCode
     * @param $objectType
     * @return mixed
     */
    public function getDetailByObjectCode($objectCode, $objectType)
    {
        $select = $this->select(
            "{$this->table}.warranty_packed_detail_id",
            "{$this->table}.warranty_packed_code",
            "{$this->table}.object_type",
            "{$this->table}.object_id",
            "{$this->table}.object_code"
        )
            ->join("warranty_packed", "warranty_packed.packed_code", "=", "{$this->table}.warranty_packed_code")
            ->where("{$this->table}.object_type", $objectType)
            ->where("{$this->table}.object_code", $objectCode)
            ->where("warranty_packed.is_actived", self::IS_ACTIVE)
            ->where("warranty_packed.is_deleted", self::NOT_DELETE);
        return $select->first();
    }
}