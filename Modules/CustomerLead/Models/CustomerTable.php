<?php

namespace Modules\CustomerLead\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerTable extends Model
{
    protected $table = "customers";
    protected $primaryKey = "customer_id";
    public $timestamps = false;

    public function getCustomer()
    {
        $mSelect = $this
            ->select(
                "{$this->table}.customer_id",
                "{$this->table}.customer_avatar",
                "{$this->table}.full_name",
                "{$this->table}.customer_code",
                "{$this->table}.customer_type",
                "{$this->table}.phone1",
                "{$this->table}.phone2",
                "{$this->table}.email",
                "{$this->table}.province_id",
                "{$this->table}.district_id",
                "{$this->table}.ward_id",
                'province.name as province_name',
                'district.name as district_name',
                'ward.name as ward_name',
                "{$this->table}.zalo",
                "{$this->table}.profile_code",
                "{$this->table}.bussiness_id",
                "{$this->table}.tax_code",
                "{$this->table}.representative",
                "{$this->table}.date_last_care"
            )
        ->leftJoin('province',
        "{$this->table}.province_id",'province.provinceid')
        ->leftJoin('district',
            "{$this->table}.district_id",'district.districtid')
        ->leftJoin('ward',
            "{$this->table}.ward_id",'ward.ward_id');
        return $mSelect->get();
    }

    public function getInfoByCode ($customerCode)
    {
        return $this
            ->select (
                "{$this->table}.full_name",
                "{$this->table}.customer_avatar",
                'customer_sources.customer_source_name',
                "{$this->table}.email",
                "{$this->table}.gender",
                'province.name as province_name',
                'district.name as district_name',
                'ward.name as ward_name',
                "{$this->table}.address"

            )
            ->leftJoin('customer_sources',
                "{$this->table}.customer_source_id",'customer_sources.customer_source_id')
            ->leftJoin('province',
                "{$this->table}.province_id",'province.provinceid')
            ->leftJoin('district',
                "{$this->table}.district_id",'district.districtid')
            ->leftJoin('ward',
                "{$this->table}.ward_id",'ward.ward_id')
            ->where("customer_code", $customerCode)->first();
    }
    public function getListBusiness($filter =[]){
        return $this
            ->select (
                "{$this->table}.full_name",
                "{$this->table}.customer_avatar",
                "{$this->table}.customer_code",
                'customer_sources.customer_source_name',
                "{$this->table}.email",
                "{$this->table}.gender",
                'province.name as province_name',
                'district.name as district_name',
                "{$this->table}.address",
                "{$this->table}.customer_type"

            )
            ->leftJoin('customer_sources',
                "{$this->table}.customer_source_id",'customer_sources.customer_source_id')
            ->leftJoin('province',
                "{$this->table}.province_id",'province.provinceid')
            ->leftJoin('district',
                "{$this->table}.district_id",'district.districtid')
            ->where("{$this->table}.customer_type", 'business')->get();
    }
}