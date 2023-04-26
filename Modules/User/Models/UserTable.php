<?php
namespace Modules\User\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

/**
 * Class UserTable
 * @package Modules\User\Models
 * @author DaiDP
 * @since Aug, 2019
 */
class UserTable extends Model
{
    protected $table = 'customers';
    protected $primaryKey = 'customer_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'customer_id',
        'branch_id',
        'customer_group_id',
        'full_name',
        'birthday',
        'gender',
        'phone1',
        'phone2',
        'email',
        'facebook',
        'province_id',
        'district_id',
        'address',
        'customer_source_id',
        'customer_refer_id',
        'customer_avatar',
        'note',
        'date_last_visit',
        'is_actived',
        'is_deleted',
        'created_by',
        'updated_by',
        'created_at',
        'zalo',
        'updated_at',
        'account_money',
        'customer_code',
        'point',
        'member_level_id',
        'password',
        'phone_verified',
        'FbId',
        'ZaloId',
        'is_updated',
        'postcode'
    ];


    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'created_at', 'updated_at', 'remember_token', 'created_by', 'updated_by', 'is_actived', 'is_deleted'
    ];


//    /**
//     * Get the identifier that will be stored in the subject claim of the JWT.
//     *
//     * @return mixed
//     */
//    public function getJWTIdentifier()
//    {
//        // TODO: Implement getJWTIdentifier() method.
//        return $this->getKey();
//    }
//
//    /**
//     * Return a key value array, containing any custom claims to be added to the JWT.
//     *
//     * @return array
//     */
//    public function getJWTCustomClaims()
//    {
//        // TODO: Implement getJWTCustomClaims() method.
//        return [
//        ];
//    }

    const IS_ACTIVE = 1;
    const NOT_DELETED = 0;
    const IS_VERIFY_PHONE = 1;

    /**
     * Lấy thông tin user by phone
     *
     * @param $phone
     * @return mixed
     */
    public function getUserByPhone($phone)
    {
        return $this->where('phone1', $phone)
            ->where('is_deleted', 0)
            ->first();
    }

    /**
     * Lấy thông tin user by fb id
     *
     * @param $FbId
     * @return mixed
     */
    public function getUserByFbId($FbId)
    {
        return $this
            ->where('FbId', $FbId)
            ->first();
    }

    /**
     * Create user
     *
     * @param $data
     * @return mixed
     */
    public function createUser($data)
    {
        //$data['user_code'] = uniqid();
        if (isset($data['password'])) {
            $data['password']  = Hash::make($data['password']);
        }

        return self::create($data);
    }

    /**
     * Create user khi login fb hoặc zalo

     * @param $data
     * @return mixed
     */
    public function createUserFbZalo($data)
    {
        //$data['user_code'] = uniqid();

        return self::create($data);
    }

    /**
     * Lấy thông tin khách hàng
     *
     * @param $customerId
     * @return mixed
     */
    public function getInfoById($customerId)
    {
        return $this
            ->leftJoin("customer_groups as group", "group.customer_group_id", "=", "{$this->table}.customer_group_id")
            ->leftJoin("customer_sources as source", "source.customer_source_id", "=", "{$this->table}.customer_source_id")
            ->leftJoin("province", "province.provinceid", "=", "{$this->table}.province_id")
            ->leftJoin("district", "district.districtid", "=", "{$this->table}.district_id")
            ->leftJoin("{$this->table} as refer", "refer.customer_id", "=", "{$this->table}.customer_refer_id")
            ->leftJoin("member_levels", "member_levels.member_level_id", "=", "{$this->table}.member_level_id")
            ->select(
                "{$this->table}.full_name",
                "{$this->table}.customer_code",
                "{$this->table}.gender",
                "{$this->table}.phone1 as phone",
                DB::raw("CONCAT(province.type, ' ', province.name) as province_name"),
                DB::raw("CONCAT(district.type, ' ', district.name) as district_name"),
                "{$this->table}.province_id",
                "{$this->table}.district_id",
                "{$this->table}.address as address",
                "{$this->table}.email",
                "{$this->table}.birthday",
                "group.group_name",
                "source.customer_source_name",
                "refer.full_name as refer_name",
                "{$this->table}.customer_avatar",
                "{$this->table}.point",
                "member_levels.name as level",
                "{$this->table}.zalo",
                "{$this->table}.facebook",
                "{$this->table}.customer_id",
                "{$this->table}.member_level_id"
            )
            ->where("{$this->table}.customer_id", $customerId)
            ->where("{$this->table}.is_deleted", 0)
            ->first();
    }

    /**
     * Cập nhật khách hàng
     *
     * @param array $data
     * @param $userId
     * @return mixed
     */
    public function editUser(array $data, $userId)
    {
        return $this->where("customer_id", $userId)->update($data);
    }

    /**
     * Lấy thông tin user đã kích hoạt
     *
     * @param $customerId
     * @return mixed
     */
    public function getUserActive($customerId)
    {
        return $this
            ->where("is_actived", self::IS_ACTIVE)
            ->where("is_deleted", self::NOT_DELETED)
//            ->where("phone_verified", self::IS_VERIFY_PHONE)
            ->where("customer_id", $customerId)
            ->first();
    }

    /**
     * Lấy thông tin user khi đăng nhập
     *
     * @param $customerId
     * @return mixed
     */
    public function getInfoUserLogin($customerId)
    {
        return $this
            ->select(
                "{$this->table}.*",
                "province.name as province_name",
                "district.name as district_name"
            )
            ->leftJoin("province", "province.provinceid", "=", "{$this->table}.province_id")
            ->leftJoin("district", "district.districtid", "=", "{$this->table}.district_id")
            ->where("customer_id", $customerId)->first();
    }
}
