<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 06/05/2021
 * Time: 16:04
 */

namespace Modules\Chat\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Contracts\JWTSubject;



class StaffTable extends Model
{
    protected $table = "staffs";
    protected $primaryKey = "staff_id";
    protected $fillable = [
        "staff_id",
        "department_id",
        "branch_id",
        "staff_title_id",
        "user_name",
        "password",
        "salt",
        "full_name",
        "birthday",
        "gender",
        "created_at",
        "updated_at",
        "date_last_login",
        "staff_avatar"
    ];

    const IS_ACTIVE = 1;
    const NOT_DELETE = 0;


    /**
     * Lấy thông tin user khi đăng nhập
     *
     * @param $customerId
     * @return mixed
     */
    public function getInfoUserLogin($staffId)
    {
        return $this
            ->select(
                "{$this->table}.*"
            )
            ->where("staff_id", $staffId)->first();
    }

    /**
     * Lấy ds nhân viên
     *
     * @return mixed
     */
    public function getStaff()
    {
        return $this
            ->select(
                "staff_id",
                "full_name",
                "is_admin"
            )
            ->where("is_actived", self::IS_ACTIVE)
            ->where("is_deleted", self::NOT_DELETE)
            ->get();
    }
}