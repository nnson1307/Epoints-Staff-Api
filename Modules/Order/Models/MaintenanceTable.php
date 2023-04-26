<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 27/09/2022
 * Time: 14:22
 */

namespace Modules\Order\Models;


use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class MaintenanceTable extends Model
{
    protected $table = "maintenance";
    protected $primaryKey = "maintenance_id";
    protected $fillable = [
        "maintenance_id",
        "maintenance_code",
        "customer_code",
        "warranty_code",
        "maintenance_cost",
        "warranty_value",
        "insurance_pay",
        "amount_pay",
        "total_amount_pay",
        "staff_id",
        "object_type",
        "object_type_id",
        "object_code",
        "object_serial",
        "object_status",
        "maintenance_content",
        "date_estimate_delivery",
        "status",
        "created_by",
        "updated_by",
        "created_at",
        "updated_at"
    ];

    protected $casts = [
        "maintenance_cost" => 'float',
        "warranty_value" => 'float',
        "insurance_pay" => 'float',
        "amount_pay" => 'float',
        "total_amount_pay" => 'float'
    ];

    const FINISH = "finish";

    /**
     * Lấy thông tin phiếu bảo trì
     *
     * @param $maintenanceId
     * @return mixed
     */
    public function getInfo($maintenanceId)
    {
        return $this
            ->select(
                "{$this->table}.maintenance_id",
                "{$this->table}.maintenance_code",
                "{$this->table}.warranty_code",
                "c.date_expired",
                "p.packed_code",
                "p.packed_name",
                "{$this->table}.customer_code",
                "cs.customer_id",
                "cs.full_name as customer_name",
                "cs.phone1 as phone",
                "cs.birthday",
                "cs.email",
                "cs.customer_avatar",
                "cg.group_name",
                "cs.address",
                DB::raw("CONCAT(province.type, ' ', province.name) as province_name"),
                DB::raw("CONCAT(district.type, ' ', district.name) as district_name"),
                DB::raw("CONCAT(w.type, ' ', w.name) as ward_name"),
                "{$this->table}.status",
                "{$this->table}.maintenance_cost",
                "{$this->table}.warranty_value",
                "{$this->table}.insurance_pay",
                "{$this->table}.amount_pay",
                "{$this->table}.total_amount_pay",
                "{$this->table}.created_at",
                "{$this->table}.date_estimate_delivery",
                "{$this->table}.object_type",
                "{$this->table}.object_type_id",
                "{$this->table}.object_code",
                "{$this->table}.object_serial",
                "{$this->table}.object_status",
                "{$this->table}.created_at",
                "{$this->table}.date_estimate_delivery",
                "{$this->table}.maintenance_content",
                "{$this->table}.staff_id",
                "s.full_name as staff_name"
            )
            ->join("customers as cs", "cs.customer_code", "=", "{$this->table}.customer_code")
            ->leftJoin("customer_groups as cg", "cg.customer_group_id", "=", "cs.customer_group_id")
            ->leftJoin("province", "province.provinceid", "=", "cs.province_id")
            ->leftJoin("district", "district.districtid", "=", "cs.district_id")
            ->leftJoin("ward as w", "w.ward_id", "=", "cs.ward_id")
            ->leftJoin("warranty_card as c", "c.warranty_card_code", "=", "{$this->table}.warranty_code")
            ->leftJoin("warranty_packed as p", "p.packed_code", "=", "c.warranty_packed_code")
            ->join("staffs as s", "s.staff_id", "=", "{$this->table}.staff_id")
            ->where("{$this->table}.maintenance_id", $maintenanceId)
            ->first();
    }
}