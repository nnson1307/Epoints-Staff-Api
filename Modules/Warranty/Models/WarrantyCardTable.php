<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 27/09/2022
 * Time: 09:58
 */

namespace Modules\Warranty\Models;


use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class WarrantyCardTable extends Model
{
    protected $table = "warranty_card";
    protected $primaryKey = "warranty_card_id";
    protected $fillable = [
        "warranty_card_id",
        "warranty_card_code",
        "customer_code",
        "warranty_packed_code",
        "date_actived",
        "date_expired",
        "quota",
        "warranty_percent",
        "warranty_value",
        "description",
        "object_type",
        "object_type_id",
        "object_code",
        "object_price",
        "object_serial",
        "object_note",
        "created_by",
        "updated_by",
        "created_at",
        "updated_at",
        "status",
        "order_code"
    ];

    protected $casts = [
        "warranty_value" => 'float',
        "warranty_percent" => 'float'
    ];

    const ACTIVE = 'actived';
    const FINISH = 'finish';

    /**
     * DS thẻ bảo hành
     *
     * @param array $filter
     * @return mixed
     */
    public function getList($filter = [])
    {
        $ds = $this
            ->select(
                "{$this->table}.warranty_card_id",
                "{$this->table}.warranty_card_code",
                "{$this->table}.quota",
                "cs.customer_code",
                "cs.full_name as customer_name",
                "{$this->table}.date_expired",
                "{$this->table}.created_at",
                "{$this->table}.status",
                "{$this->table}.object_type",
                "{$this->table}.object_type_id",
                "{$this->table}.object_code",
                "{$this->table}.object_serial"
            )
            ->join("customers as cs", "cs.customer_code", "=", "{$this->table}.customer_code")
            ->orderBy("{$this->table}.warranty_card_id", "desc");

        //filter search
        if (isset($filter['search']) && $filter['search'] != null) {
            $search = $filter["search"];

            $ds->where(function ($query) use ($search) {
                $query->where("{$this->table}.warranty_card_code", "like", "%" . $search . "%")
                    ->orWhere("cs.full_name", "like", "%" . $search . "%")
                    ->orWhere("cs.phone1", "like", "%" . $search . "%")
                    ->orWhere("{$this->table}.object_serial", "like", "%" . $search . "%");
            });
        }

        //filter status
        if (isset($filter['status']) && $filter['status'] != null) {
            $ds->where("{$this->table}.status", $filter['status']);
        }

        //filter theo gói bảo hành
        if (isset($filter['warranty_packed_code']) && $filter['warranty_packed_code'] != null) {
            $ds->where("{$this->table}.warranty_packed_code", $filter['warranty_packed_code']);
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
     * Lấy thông tin phiếu bảo hành
     *
     * @param $warrantyCardCode
     * @return mixed
     */
    public function getInfo($warrantyCardCode)
    {
        return $this
            ->select(
                "{$this->table}.warranty_card_id",
                "{$this->table}.warranty_card_code",
                "{$this->table}.warranty_packed_code",
                "p.packed_name",
                "{$this->table}.quota",
                "cs.customer_id",
                "cs.customer_code",
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
                "{$this->table}.date_actived",
                "{$this->table}.date_expired",
                "{$this->table}.status",
                "{$this->table}.warranty_percent",
                "{$this->table}.warranty_value",
                "{$this->table}.description",
                "{$this->table}.object_type",
                "{$this->table}.object_type_id",
                "{$this->table}.object_code",
                "{$this->table}.object_price",
                "{$this->table}.object_serial",
                "{$this->table}.object_note"
            )
            ->join("warranty_packed as p", "p.packed_code" , "=", "{$this->table}.warranty_packed_code")
            ->join("customers as cs", "cs.customer_code", "=", "{$this->table}.customer_code")
            ->leftJoin("customer_groups as cg", "cg.customer_group_id", "=", "cs.customer_group_id")
            ->leftJoin("province", "province.provinceid", "=", "cs.province_id")
            ->leftJoin("district", "district.districtid", "=", "cs.district_id")
            ->leftJoin("ward as w", "w.ward_id", "=", "cs.ward_id")
            ->where("{$this->table}.warranty_card_code", $warrantyCardCode)
            ->first();
    }

    /**
     * Chỉnh sửa phiếu bảo hành
     *
     * @param array $data
     * @param $warrantyCardCode
     * @return mixed
     */
    public function editByCode(array $data, $warrantyCardCode)
    {
        return $this->where("warranty_card_code", $warrantyCardCode)->update($data);
    }

    /**
     * Lấy phiếu bảo hành của khách hàng
     *
     * @param array $filter
     * @return mixed
     */
    public function getWarrantyCardCustomer($filter = [])
    {
        $dateNow = Carbon::now()->format('Y-m-d');

        $ds = $this
            ->select(
                "{$this->table}.warranty_card_id",
                "{$this->table}.warranty_card_code",
                "{$this->table}.customer_code",
                "cs.full_name as customer_name",
                "{$this->table}.date_actived",
                "{$this->table}.date_expired",
                "{$this->table}.quota",
                DB::raw("count(maintenance.warranty_code) as count_using"),
                "{$this->table}.object_type",
                "{$this->table}.object_type_id",
                "{$this->table}.object_code",
                "{$this->table}.object_price",
                "{$this->table}.object_serial",
                "{$this->table}.warranty_value",
                "{$this->table}.warranty_percent"
            )
            ->join("customers as cs", "cs.customer_code", "=", "{$this->table}.customer_code")
            ->leftJoin('maintenance', function ($join) {
                $join->on("maintenance.warranty_code", "=", "{$this->table}.warranty_card_code")
                    ->on("maintenance.status", "=", DB::raw("'finish'"));
            })
            ->where("{$this->table}.status", self::ACTIVE)
            ->where(function ($query) use ($dateNow) {
                $query->whereNull("{$this->table}.date_expired")
                    ->orWhereDate("{$this->table}.date_expired", ">=", $dateNow);
            })
            ->where(function ($query) {
                $query->havingRaw("{$this->table}.quota = 0 || count_using < {$this->table}.quota");
            })
            ->where("{$this->table}.customer_code", $filter['customer_code'])
            ->groupby("{$this->table}.warranty_card_code")
            ->orderBy("{$this->table}.warranty_card_id", "desc");

        //filter search
        if (isset($filter['search']) && $filter['search'] != null) {
            $ds->where("{$this->table}.warranty_card_code", "like", "%" . $filter["search"] . "%")
                ->orWhere("cs.full_name", "like", "%" . $filter["search"] . "%")
                ->orWhere("cs.phone1", "like", "%" . $filter["search"] . "%")
                ->orWhere("{$this->table}.object_serial", "like", "%" . $filter["search"] . "%");
        }

        // get số trang
        $page = (int)($filter["page"] ?? 1);

        return $ds->paginate(PAGING_ITEM_PER_PAGE, $columns = ["*"], $pageName = "page", $page);
    }
}