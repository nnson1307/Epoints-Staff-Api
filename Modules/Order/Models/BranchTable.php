<?php


namespace Modules\Order\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
class BranchTable extends Model
{
    protected $table = 'branches';
    protected $primaryKey = 'branch_id';

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'updated_at'
    ];

    const IS_ACTIVE = 1;
    const NOT_DELETED = 0;
    const IS_REPRESENTATIVE = 1;

    /**
     * Lấy Option Chi nhánh
     *
     * @param $filters
     * @return mixed
     */
    public function getOptionBranch($filters)
    {
        $ds = $this
            ->select(
                "branch_id",
                "branch_name",
                "address",
                "latitude",
                "longitude",
                "branch_code"
            )
            ->where("is_actived", self::IS_ACTIVE)
            ->where("is_deleted", self::NOT_DELETED);
        if (Auth::user()->is_admin != 1) {
            $ds->where("{$this->table}.branch_id", Auth::user()->branch_id);
        }
            
        if (isset($filters['provinceid']) && $filters['provinceid'] != null) {
            $ds->where("provinceid", $filters['provinceid']);

        }
        if (isset($filters['districtid']) && $filters['districtid'] != null) {
            $ds->where("districtid", $filters['districtid']);
        }
        return $ds->get();
    }

    /**
     * Lấy thông tin chi nhánh chính
     *
     * @return mixed
     */
    public function getBranchRepresentative()
    {
        return $this
            ->select(
                "branch_id",
                "branch_name"
            )
            ->where("is_representative", self::IS_REPRESENTATIVE)
            ->first();
    }

    /**
     * Lấy thông tin chi nhánh by code
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
                "branch_code"
            )
            ->where("branch_code", $branchCode)
            ->where("is_actived", self::IS_ACTIVE)
            ->where("is_deleted", self::NOT_DELETED)
            ->first();
    }

    /**
     * Lấy thông tin chi nhánh by id
     *
     * @param $branchId
     * @return mixed
     */
    public function getBranchById($branchId)
    {
        return $this
            ->select(
                "branch_id",
                "branch_name",
                "branch_code"
            )
            ->where("branch_id", $branchId)
            ->where("is_actived", self::IS_ACTIVE)
            ->where("is_deleted", self::NOT_DELETED)
            ->first();
    }

    public function getItem($id)
    {
        return $this
            ->leftJoin('province', 'province.provinceid', '=', 'branches.provinceid')
            ->leftJoin('district', 'district.districtid', '=', 'branches.districtid')
            ->select('branches.branch_id',
                'branches.branch_name',
                'branches.address',
                'branches.description',
                'branches.phone',
                'branches.is_actived',
                'branches.is_deleted',
                'branches.created_by',
                'branches.updated_by',
                'branches.created_at',
                'branches.updated_at',
                'branches.email',
                'branches.hot_line',
                'branches.provinceid', 'branches.districtid',
                'branches.is_representative', 'branches.representative_code',
                'province.type as province_type',
                'province.name as province_name',
                'district.type as district_type',
                'district.name as district_name',
                'branches.hot_line',
                'branches.hot_line',
                'latitude',
                'longitude',
                "{$this->table}.branch_code"
            )
            ->where('branches.branch_id', $id)->first();
    }


}