<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 26/05/2021
 * Time: 14:37
 */

namespace Modules\Report\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class BranchTable extends Model
{
    protected $table = "branches";
    protected $primaryKey = "branch_id";

    const IS_ACTIVE = 1;
    const NOT_DELETED = 0;

    /**
     * Lấy danh sách chi nhánh
     *
     * @return mixed
     */
    public function getBranch()
    {
        return $this
            ->select(
                "{$this->table}.branch_id",
                "{$this->table}.branch_name",
                "{$this->table}.is_representative",
                DB::raw("CONCAT(province.type, ' ', province.name) as province_name"),
                DB::raw("CONCAT(district.type, ' ', district.name) as district_name"),
                DB::raw("CONCAT(w.type, ' ', w.name) as ward_name"),
                "{$this->table}.address",
                "{$this->table}.latitude",
                "{$this->table}.longitude"
            )
            ->leftJoin("province", "province.provinceid", "=", "{$this->table}.provinceid")
            ->leftJoin("district", "district.districtid", "=", "{$this->table}.districtid")
            ->leftJoin("ward as w", "w.ward_id", "=", "{$this->table}.ward_id")
            ->where("{$this->table}.is_actived", self::IS_ACTIVE)
            ->where("{$this->table}.is_deleted", self::NOT_DELETED)
            ->get();
    }
}