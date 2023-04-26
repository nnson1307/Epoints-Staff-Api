<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 11/05/2021
 * Time: 09:27
 */

namespace Modules\User\Models;


use Illuminate\Database\Eloquent\Model;

class BranchTable extends Model
{
    protected $table = "branches";
    protected $primaryKey = "branch_id";

    const IS_ACTIVE = 1;
    const NOT_DELETED = 0;

    /**
     * Lấy thông tin chi nhánh
     *
     * @param $branchId
     * @return mixed
     */
    public function getBranch($branchId)
    {
        return $this
            ->select(
                "branch_id",
                "branch_name",
                "branch_code",
                "address"
            )
            ->where("branch_id", $branchId)
            ->where("is_actived", self::IS_ACTIVE)
            ->where("is_deleted", self::NOT_DELETED)
            ->first();
    }
}