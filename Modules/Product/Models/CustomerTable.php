<?php
namespace Modules\Product\Models;
use Illuminate\Database\Eloquent\Model;

class CustomerTable extends Model
{
    protected $table = 'customers';
    protected $primaryKey = 'customer_id';
    protected $fillable = [
        'customer_id', 'branch_id', 'customer_group_id', 'full_name', 'birthday', 'gender', 'phone1', 'phone2', 'email',
        'facebook', 'address', 'customer_source_id', 'customer_refer_id', 'customer_avatar', 'note', 'date_last_visit',
        'is_actived', 'is_deleted', 'created_by', 'updated_by', 'created_at', 'updated_at', 'zalo', 'account_money', 'customer_code',
        'province_id', 'district_id', 'postcode'
    ];

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
//        if (Auth::user()->is_admin != 1) {
//            $get->where('customers.branch_id', Auth::user()->branch_id);
//        }
        return $get->first();
    }
}