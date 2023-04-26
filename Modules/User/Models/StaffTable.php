<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 06/05/2021
 * Time: 16:04
 */

namespace Modules\User\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Contracts\JWTSubject;



class StaffTable extends Authenticatable implements JWTSubject
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
        "staff_avatar",
        'token_md5'
    ];

    const NOT_DELETE = 0;

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        // TODO: Implement getJWTIdentifier() method.
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        // TODO: Implement getJWTCustomClaims() method.
        return [
        ];
    }

    /**
     * Chỉnh sửa tài khoản user
     *
     * @param array $data
     * @param $staffId
     * @return mixed
     */
    public function edit(array $data, $staffId)
    {
        if (isset($data['password'])) {
            $data['password']  = Hash::make($data['password']);
        }

        return $this->where("staff_id", $staffId)->update($data);
    }

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
}