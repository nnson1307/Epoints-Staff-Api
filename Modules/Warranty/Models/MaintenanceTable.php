<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 27/09/2022
 * Time: 14:22
 */

namespace Modules\Warranty\Models;


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
        "total_amount_pay" => 'float',
        "warranty_value_apply" => 'float'
    ];

    const FINISH = "finish";

    /**
     * DS phiếu bảo trì
     *
     * @param array $filter
     * @return mixed
     */
    public function getList($filter = [])
    {
        $ds = $this
            ->select(
                "{$this->table}.maintenance_id",
                "{$this->table}.maintenance_code",
                "cs.full_name as customer_name",
                "{$this->table}.status",
                "{$this->table}.total_amount_pay",
                "{$this->table}.created_at",
                "{$this->table}.date_estimate_delivery",
                "{$this->table}.object_type",
                "{$this->table}.object_type_id",
                "{$this->table}.object_code",
                "{$this->table}.object_serial",
                "{$this->table}.object_status"
            )
            ->join("customers as cs", "cs.customer_code", "=", "{$this->table}.customer_code")
            ->leftJoin("warranty_card as c", "c.warranty_card_code", "=", "{$this->table}.warranty_code")
            ->leftJoin("warranty_packed as p", "p.packed_code", "=", "c.warranty_packed_code")
            ->orderBy("{$this->table}.maintenance_id", "desc");

        //filter search
        if (isset($filter['search']) && $filter['search'] != null) {
            $search = $filter["search"];

            $ds->where(function ($query) use ($search) {
                $query->where("{$this->table}.warranty_code", "like", "%" . $search . "%")
                    ->orWhere("{$this->table}.maintenance_code", "like", "%" . $search . "%")
                    ->orWhere("cs.full_name", "like", "%" . $search . "%")
                    ->orWhere("cs.phone1", "like", "%" . $search . "%")
                    ->orWhere("{$this->table}.object_serial", "like", "%" . $search . "%");
            });
        }

        //filter theo trạng thái
        if (isset($filter['status']) && $filter['status'] != null) {
            $ds->where("{$this->table}.status", $filter['status']);
        }

        //filter theo gói
        if (isset($filter['packed_code']) && $filter['packed_code'] != null) {
            $ds->where("p.packed_code", $filter['packed_code']);
        }

        //filter theo phiếu bảo trì
        if (isset($filter['warranty_card_code']) && $filter['warranty_card_code'] != null) {
            $ds->where("{$this->table}.warranty_code", $filter['warranty_card_code']);
        }

        //filter theo khách hàng
        if (isset($filter['customer_code']) && $filter['customer_code'] != null) {
            $ds->where("{$this->table}.customer_code", $filter['customer_code']);
        }

        //filter theo ngày tạo
        if (isset($filter["created_at"]) && $filter["created_at"] != null) {
            $arr_filter = explode(" - ", $filter["created_at"]);
            $startTime = Carbon::createFromFormat("d/m/Y", $arr_filter[0])->format("Y-m-d");
            $endTime = Carbon::createFromFormat("d/m/Y", $arr_filter[1])->format("Y-m-d");
            $ds->whereBetween("{$this->table}.created_at", [$startTime . " 00:00:00", $endTime . " 23:59:59"]);
        }

        // get số trang
        $page = (int)($filter["page"] ?? 1);

        return $ds->paginate(PAGING_ITEM_PER_PAGE, $columns = ["*"], $pageName = "page", $page);
    }

    /**
     * Thêm phiếu bảo trì
     *
     * @param array $data
     * @return mixed
     */
    public function add(array $data)
    {
        return $this->create($data)->maintenance_id;
    }

    /**
     * Chỉnh sửa phiếu bảo trì
     *
     * @param array $data
     * @param $maintenanceId
     * @return mixed
     */
    public function edit(array $data, $maintenanceId)
    {
        return $this->where("maintenance_id", $maintenanceId)->update($data);
    }

    /**
     * Chỉnh sửa phiếu bảo trì bằng code
     *
     * @param array $data
     * @param $maintenanceCode
     * @return mixed
     */
    public function editByCode(array $data, $maintenanceCode)
    {
        return $this->where("maintenance_code", $maintenanceCode)->update($data);
    }

    /**
     * Lấy số phiếu bảo trì đã hoàn tất của 1 phiếu bảo hành trừ chính nó
     *
     * @param $warrantyCode
     * @param $maintenanceCode
     * @return mixed
     */
    public function getMaintenanceFinish($warrantyCode, $maintenanceCode)
    {
        return $this
            ->select(
                "{$this->table}.maintenance_id"
            )
            ->where("warranty_code", $warrantyCode)
            ->where("maintenance_code", "<>", $maintenanceCode)
            ->where("status", self::FINISH)
            ->get();
    }

    /**
     * Lấy thông tin phiếu bảo trì
     *
     * @param $maintenanceCode
     * @return mixed
     */
    public function getInfo($maintenanceCode)
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
                "{$this->table}.warranty_value as warranty_value_apply",
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
            ->where("{$this->table}.maintenance_code", $maintenanceCode)
            ->first();
    }
}