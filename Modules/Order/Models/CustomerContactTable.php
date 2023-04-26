<?php
/**
 * Created by PhpStorm
 * User: Mr Son
 * Date: 10/14/2020
 * Time: 2:16 PM
 */

namespace Modules\Order\Models;


use Illuminate\Database\Eloquent\Model;

class CustomerContactTable extends Model
{
    protected $table = "customer_contacts";
    protected $primaryKey = "customer_contact_id";

    const NOT_DELETE = 0;
    const IS_DEFAULT = 1;

    /**
     * Lấy thông tin địa chỉ giao hàng
     *
     * @param $contactCode
     * @param $customerId
     * @return mixed
     */
    public function getContact($contactCode, $customerId)
    {
        return $this
            ->select(
                "customer_contact_id",
                "district_id",
                "province_id",
                "postcode"
            )
            ->where("customer_contact_code", $contactCode)
            ->where("customer_id", $customerId)
            ->where("is_deleted", self::NOT_DELETE)
            ->first();
    }

    /**
     * Lấy địa chỉ nhận hàng của KH
     *
     * @param $customerId
     * @return mixed
     */
    public function getContactCustomer($customerId)
    {
        return $this
            ->select(
                "{$this->table}.customer_contact_id",
                "{$this->table}.customer_contact_code",
                "{$this->table}.contact_name",
                "{$this->table}.contact_phone",
//                DB::raw("CONCAT({$this->table}.full_address, ', ',district.type, ' ',district.name, ', ',province.type, ' ',province.name) as full_address"),
                "{$this->table}.address_default",
                "{$this->table}.postcode",
                "{$this->table}.province_id",
                "{$this->table}.district_id",
                "{$this->table}.full_address as address",
                "district.type as district_type",
                "district.name as district_name",
                "province.type as province_type",
                "province.name as province_name"
            )
            ->leftJoin("province", "province.provinceid", "=", "{$this->table}.province_id")
            ->leftJoin("district", "district.districtid", "=", "{$this->table}.district_id")
            ->where("{$this->table}.customer_id", $customerId)
            ->where("{$this->table}.is_deleted", self::NOT_DELETE)
            ->get();
    }

    /**
     * Lấy địa chỉ giao hàng mặc định
     *
     * @param $customerId
     * @return mixed
     */
    public function getContactDefault($customerId)
    {
        return $this
            ->select(
                "{$this->table}.customer_contact_id",
                "{$this->table}.customer_contact_code",
                "{$this->table}.contact_name",
                "{$this->table}.contact_phone",
//                DB::raw("CONCAT({$this->table}.full_address, ', ',district.type, ' ',district.name, ', ',province.type, ' ',province.name) as full_address"),
                "{$this->table}.address_default",
                "{$this->table}.postcode",
                "{$this->table}.province_id",
                "{$this->table}.district_id",
                "{$this->table}.full_address as address",
                "district.type as district_type",
                "district.name as district_name",
                "province.type as province_type",
                "province.name as province_name"
            )
            ->leftJoin("province", "province.provinceid", "=", "{$this->table}.province_id")
            ->leftJoin("district", "district.districtid", "=", "{$this->table}.district_id")
            ->where("{$this->table}.customer_id", $customerId)
            ->where("{$this->table}.is_deleted", self::NOT_DELETE)
            ->where("{$this->table}.address_default", self::IS_DEFAULT)
            ->first();
    }
}