<?php
/**
 * Created by PhpStorm   .
 * User: Mr Son
 * Date: 2020-02-17
 * Time: 2:57 PM
 * @author SonDepTrai
 */

namespace Modules\Booking\Models;


use Illuminate\Database\Eloquent\Model;

class StaffTable extends Model
{
    protected $table = "staffs";
    protected $primaryKey = "staff_id";

    const IS_ACTIVED = 1;
    const NOT_DELETED = 0;

    /**
     * Lấy danh sách kỹ thuật viên
     *
     * @param $filters
     * @return mixed
     */
    public function getStaffs($filters)
    {
        $ds = $this
            ->select(
                "staffs.staff_id",
                "departments.department_name",
                "branches.branch_name",
                "staff_title.staff_title_name",
                "staffs.full_name",
                "staffs.birthday",
                "staffs.gender",
                "staffs.phone1",
                "staffs.phone2",
                "staffs.email",
                "staffs.facebook",
                "staffs.date_last_login",
                "staffs.staff_avatar",
                "staffs.address"
            )
            ->leftJoin("departments", "departments.department_id", "=", "staffs.department_id")
            ->leftJoin("branches", "branches.branch_id", "=", "staffs.branch_id")
            ->leftJoin("staff_title", "staff_title.staff_title_id", "=", "staffs.staff_title_id")
            ->where("staffs.branch_id", $filters["branch_id"])
            ->where("{$this->table}.is_actived", self::IS_ACTIVED)
            ->where("staffs.is_deleted", self::NOT_DELETED)
            ->orderBy("{$this->table}.staff_id", "desc");

        if (isset($filters['full_name']) && $filters['full_name'] != null) {
            $ds->where("{$this->table}.full_name", 'like', '%' . $filters['full_name'] . '%');
        }

        return $ds->get();
    }
}