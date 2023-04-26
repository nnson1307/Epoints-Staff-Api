<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 24/05/2021
 * Time: 13:54
 */

namespace Modules\Customer\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class CustomerContactTable extends Model
{
    protected $table = "customer_contacts";
    protected $primaryKey = "customer_contact_id";
    protected $fillable = [
        "customer_contact_id",
        "customer_id",
        "customer_contact_code",
        "district_id",
        "province_id",
        "ward_id",
        "postcode",
        "address_default",
        "contact_name",
        "contact_phone",
        "contact_email",
        "full_address",
        "created_at",
        "updated_at",
        "is_deleted"
    ];

    const NOT_DELETE = 0;

    /**
     * Lấy địa chỉ nhận hàng của KH
     *
     * @param $customerId
     * @return mixed
     */
    public function getContact($customerId)
    {
        return $this
            ->select(
                "{$this->table}.customer_contact_id",
                "{$this->table}.customer_contact_code",
                "{$this->table}.contact_name",
                "{$this->table}.contact_phone",
                DB::raw("CONCAT({$this->table}.full_address, ', ',w.type, ' ',w.name, ', ',district.type, ' ',district.name, ', ',province.type, ' ',province.name) as full_address"),
                "{$this->table}.address_default",
                "{$this->table}.postcode",
                "{$this->table}.province_id",
                "{$this->table}.district_id",
                "{$this->table}.ward_id",
                "{$this->table}.full_address as address",
                "district.type as district_type",
                "district.name as district_name",
                "province.type as province_type",
                "province.name as province_name",
                "w.type as ward_type",
                "w.name as ward_name"
            )
            ->leftJoin("province", "province.provinceid", "=", "{$this->table}.province_id")
            ->leftJoin("district", "district.districtid", "=", "{$this->table}.district_id")
            ->leftJoin("ward as w", "w.ward_id", "=", "{$this->table}.ward_id")
            ->where("{$this->table}.customer_id", $customerId)
            ->where("{$this->table}.is_deleted", self::NOT_DELETE)
            ->get();
    }

    /**
     * Thêm địa chỉ giao hàng
     *
     * @param array $data
     * @return mixed
     */
    public function add(array $data)
    {
        return $this->create($data)->customer_contact_id;
    }

    /**
     * Chỉnh sửa địa chỉ giao hàng
     *
     * @param $contactId
     * @param $data
     * @return mixed
     */
    public function edit($data, $contactId)
    {
        return $this->where("customer_contact_id", $contactId)->update($data);
    }

    /**
     * Update địa chỉ mặc định của các địa chỉ khác = 0
     *
     * @param $customerId
     * @return mixed
     */
    public function updateNotDefault($customerId)
    {
        return $this
            ->where("customer_id", $customerId)
            ->where("is_deleted", self::NOT_DELETE)
            ->update(["address_default" => 0]);
    }
}