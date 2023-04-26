<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 25/05/2021
 * Time: 15:22
 */

namespace Modules\Order\Models;


use Illuminate\Database\Eloquent\Model;

class StaffTable extends Model
{
    protected $table = "staffs";
    protected $primaryKey = "staff_id";

    /**
     * Lấy thông tin hoa hồng của nhân viên
     *
     * @param $idStaff
     * @return mixed
     */
    public function getCommissionStaff($idStaff)
    {
        return $this
            ->select(
                "staff_id",
                "commission_rate"
            )
            ->where("staff_id", $idStaff)
            ->where('is_deleted', 0)
            ->first();
    }
}