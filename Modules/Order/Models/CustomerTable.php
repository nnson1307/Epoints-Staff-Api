<?php
/**
 * Created by PhpStorm
 * User: Mr Son
 * Date: 5/12/2020
 * Time: 2:49 PM
 */

namespace Modules\Order\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class CustomerTable extends Model
{
    protected $table = "customers";
    protected $primaryKey = "customer_id";

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
                "{$this->table}.member_level_id",
                "{$this->table}.customer_group_id"
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
     * @param $id
     */
    public function getItem($id)
    {
        $get = $this
            ->leftJoin('customer_groups as group', 'group.customer_group_id', '=', 'customers.customer_group_id')
            ->leftJoin('customer_sources as source', 'source.customer_source_id', '=', 'customers.customer_source_id')
            ->leftJoin('province', 'province.provinceid', '=', 'customers.province_id')
            ->leftJoin('district', 'district.districtid', '=', 'customers.district_id')
            ->leftJoin('member_levels', 'member_levels.member_level_id', '=', 'customers.member_level_id')
            ->select('customers.customer_group_id as customer_group_id',
                'group.group_name as group_name',
                'customers.full_name as full_name',
                'customers.customer_code as customer_code',
                'customers.gender as gender',
                'customers.phone1 as phone1',
                'province.name as province_name',
                'province.type as province_type',
                'district.name as district_name',
                'district.type as district_type',
                'customers.address as address',
                'customers.email as email',
                'customers.customer_source_id as customer_source_id',
                'customers.birthday as birthday',
                'source.customer_source_name',
                'customers.customer_refer_id',
                'customers.facebook as facebook',
                'customers.zalo as zalo',
                'customers.note as note',
                'customers.customer_id as customer_id',
                'customers.is_actived as is_actived',
                'customers.phone2 as phone2',
                'customers.customer_avatar as customer_avatar',
                'customers.created_at as created_at',
                'customers.account_money as account_money',
                'customers.province_id as province_id',
                'customers.district_id as district_id',
                'customers.point as point',
                'customers.member_level_id as member_level_id',
                'member_levels.name as member_level_name',
                'customers.point as point',
                'member_levels.discount as member_level_discount',
                "{$this->table}.postcode"
            )
            ->where('customers.customer_id', $id);
        return $get->first();
    }
}