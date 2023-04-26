<?php
/**
 * Created by PhpStorm   .
 * User: Mr Son
 * Date: 2020-01-03
 * Time: 5:48 PM
 * @author SonDepTrai
 */

namespace Modules\ChatHub\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CustomerTable extends Model
{
    protected $table = 'customers';
    protected $primaryKey = 'customer_id';
    protected $fillable = [
        "customer_id",
        "branch_id",
        "customer_group_id",
        "full_name",
        "birthday",
        "gender",
        "phone1",
        "phone2",
        "email",
        "facebook",
        "province_id",
        "district_id",
        "ward_id",
        "address",
        "customer_source_id",
        "customer_refer_id",
        "customer_avatar",
        "zalo",
        "note",
        "point_rank",
        "account_money",
        "customer_code",
        "point",
        "member_level_id",
        "password",
        "phone_verified",
        "date_last_visit",
        "FBId",
        "ZaloId",
        "postcode",
        "site_id",
        "point_balance",
        "is_updated",
        "is_actived",
        "is_deleted",
        "created_by",
        "update_by",
        "created_at",
        "updated_at"
    ];

    const IS_ACTIVE = 1;
    const NOT_DELETED = 0;

    
    /**
     * Lấy danh sách khách hàng
     *
     * @param array $filter
     * @return mixed
     */
    public function getCustomer($filter = [])
    {
        $arrRouteList = [];

        if (session('routeList')) {
            $arrRouteList = session('routeList');
        }

        $ds = $this
            ->select(
                "{$this->table}.customer_code",
                "{$this->table}.customer_id",
                "{$this->table}.email",
                "{$this->table}.full_name",
                "{$this->table}.phone1 as phone",
                DB::raw("'customer' as type"),
                "{$this->table}.ch_customer_id",
            )

            ->where("{$this->table}.is_actived", self::IS_ACTIVE)
            ->where("{$this->table}.is_deleted", self::NOT_DELETED)
            ->groupBy("{$this->table}.customer_id")
            ->orderBy("{$this->table}.customer_id", "desc");

        if (isset($filter['search']) != "") {
            $search = $filter['search'];
            $ds->where(function ($query) use ($search) {
                $query->where("customers.full_name", 'like', '%' . $search . '%')
                    ->orWhere("customers.customer_code", '%' . $search . '%')
                    ->orWhere("customers.phone1", 'like', '%' . $search . '%');
            });
        }

        //Phân quyền xem khách hàng theo chi nhánh
        // if (Auth::user()->is_admin != 1 && in_array('permission-customer-branch', $arrRouteList)) {
        //     $ds->where("cb.branch_id", Auth::user()->branch_id);
        // }

        // get số trang
        $page = (int)($filter["page"] ?? 1);
        return $ds->skip(($page - 1) * PAGING_ITEM_PER_PAGE)->take(PAGING_ITEM_PER_PAGE)->get();
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
            ->select(
                "{$this->table}.customer_id",
                "{$this->table}.full_name",
                "{$this->table}.customer_code",
                "{$this->table}.gender",
                "{$this->table}.phone1 as phone",
                DB::raw("CONCAT(province.type, ' ', province.name) as province_name"),
                DB::raw("CONCAT(district.type, ' ', district.name) as district_name"),
                DB::raw("CONCAT(w.type, ' ', w.name) as ward_name"),
                "{$this->table}.province_id",
                "{$this->table}.district_id",
                "{$this->table}.ward_id",
                "{$this->table}.address as address",
                "{$this->table}.email",
                "{$this->table}.birthday",
                "{$this->table}.customer_group_id",
                "group.group_name",
                "source.customer_source_name",
                "refer.full_name as refer_name",
                "{$this->table}.customer_avatar",
                "{$this->table}.point",
                "member_levels.name as level",
                "{$this->table}.zalo",
                "{$this->table}.facebook",
            )
            ->leftJoin("customer_groups as group", "group.customer_group_id", "=", "{$this->table}.customer_group_id")
            ->leftJoin("customer_sources as source", "source.customer_source_id", "=", "{$this->table}.customer_source_id")
            ->leftJoin("province", "province.provinceid", "=", "{$this->table}.province_id")
            ->leftJoin("district", "district.districtid", "=", "{$this->table}.district_id")
            ->leftJoin("ward as w", "w.ward_id", "=", "{$this->table}.ward_id")
            ->leftJoin("{$this->table} as refer", "refer.customer_id", "=", "{$this->table}.customer_refer_id")
            ->leftJoin("member_levels", "member_levels.member_level_id", "=", "{$this->table}.member_level_id")

            ->where("{$this->table}.customer_id", $customerId)
            ->where("{$this->table}.is_deleted", 0)
            ->first();
    }

        /**
     * Lấy thông tin khách hàng
     *
     * @param $phone
     * @return mixed
     */
    public function getCustomerByPhone($phone)
    {
        return $this->where("phone1", $phone)->first();
    }

    
}