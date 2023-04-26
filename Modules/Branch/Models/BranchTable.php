<?php
/**
 * Created by PhpStorm
 * User: Mr Son
 * Date: 6/12/2020
 * Time: 2:20 PM
 */

namespace Modules\Branch\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BranchTable extends Model
{
    protected $table = "branches";
    protected $primaryKey = "branch_id";
    protected $fillable = [
        "branch_id",
        "branch_name",
        "branch_code",
        "site_id",
        "slug",
        "address",
        "description",
        "phone",
        "email",
        "hot_line",
        "provinceid",
        "districtid",
        "is_representative",
        "representative_code",
        "is_actived",
        "is_deleted",
        "created_by",
        "updated_by",
        "created_at",
        "updated_at",
        "latitude",
        "longitude"
    ];

    const NOT_DELETE = 0;
    const IS_ACTIVE = 1;
    /**
     * Lấy thông tin chi nhánh by branch_code
     *
     * @param $branchCode
     * @return mixed
     */
    public function getBranchByCode($branchCode)
    {
        return $this
            ->select(
                "branch_id",
                "branch_name",
                "branch_code",
                "site_id"
            )
            ->where("is_deleted", self::NOT_DELETE)
            ->where("branch_code", $branchCode)
            ->first();
    }

    /**
     * Thêm chi nhánh
     *
     * @param array $data
     * @return mixed
     */
    public function add(array $data)
    {
        return $this->create($data)->branch_id;
    }

    /**
     * Lấy danh sách chi nhánh
     *
     * @return mixed
     */
    public function getBranch()
    {
        $ds = $this
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
            ->where("{$this->table}.is_deleted", self::NOT_DELETE);
             //Phân quyền data
            if (Auth::user()->is_admin != 1) {
                $ds->where("{$this->table}.branch_id", Auth::user()->branch_id);
            }
        return $ds->get();
    }
}