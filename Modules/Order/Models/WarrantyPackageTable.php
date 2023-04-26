<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 02/06/2021
 * Time: 17:09
 */

namespace Modules\Order\Models;


use Illuminate\Database\Eloquent\Model;

class WarrantyPackageTable extends Model
{
    protected $table = "warranty_packed";
    protected $primaryKey = "warranty_packed_id";
    protected $fillable = [
        'warranty_packed_id',
        'packed_code',
        'packed_name',
        'time_type',
        'time',
        'percent',
        'quota',
        'required_price',
        'slug',
        'is_actived',
        'is_deleted',
        'created_by',
        'updated_by',
        'created_at',
        'updated_at',
        'description',
        'detail_description',
    ];

    const NOT_DELETED = 0;
    const IS_ACTIVE = 1;

    /**
     * Lấy thông tin gói bảo hành theo code
     *
     * @param $code
     * @return mixed
     */
    public function getInfoByCode($code)
    {
        $select = $this
            ->select(
                "{$this->table}.warranty_packed_id",
                "{$this->table}.packed_code",
                "{$this->table}.packed_name",
                "{$this->table}.time_type",
                "{$this->table}.time",
                "{$this->table}.percent",
                "{$this->table}.quota",
                "{$this->table}.required_price",
                "{$this->table}.slug",
                "{$this->table}.is_actived",
                "{$this->table}.is_deleted",
                "{$this->table}.description",
                "{$this->table}.detail_description"
            )
            ->where("{$this->table}.packed_code", $code)
            ->where("{$this->table}.is_deleted", self::NOT_DELETED)
            ->where("{$this->table}.is_actived", self::IS_ACTIVE);
        return $select->first();
    }
}