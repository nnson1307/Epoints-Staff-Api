<?php

namespace Modules\CustomerLead\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;


class CustomerLeadTable extends Model
{
    protected $table = "cpo_customer_lead";
    protected $primaryKey = "customer_lead_id";
    public $timestamps = false;
    protected $fillable = [
        "customer_lead_id",
        "customer_lead_code",
        "customer_id",
        "status",
        "email",
        "gender",
        "birthday",
        "address",
        "avatar",
        "tag_id",
        "pipeline_code",
        "journey_code",
        "hotline",
        "fanpage",
        "zalo",
        "is_deleted",
        "created_by",
        "updated_by",
        "updated_at",
        "tax_code",
        "representative",
        "customer_source",
        "is_convert",
        "branch_code",
        "assign_by",
        "sale_id",
        "date_revoke",
        "province_id",
        "district_id",
        "ward_id",
        "deal_code",
        "convert_object_type",
        "convert_object_code",
        "id_google_sheet",
        "number_row",
        "allocation_date",
        "note",
        "bussiness_id",
        "employees",
        "date_last_care",
    ];
    protected $casts = [
        "price" => 'float',
        "discount" => 'float',
        "quantity" => 'integer',
        "amount" => 'float'
    ];

    public function createdCustomer($dataLead)
    {
        return $this
            ->insertGetId($dataLead);
    }

    public function updateLeadCode(array $data, $id)
    {
        return $this
            ->where("{$this->table}.customer_lead_id", $id)
            ->update($data);
    }

    //lay so dien thoai da co
    public function getPhone()
    {
        $mSelect = $this
            ->select("{$this->table}.phone");
        if ($mSelect->get()) {
            return $mSelect->get()->toArray();
        }
        return [];
    }

    public function getDetail($input)
    {
        $mSelect = $this
            ->select(
                'cpo_journey.journey_name'
            )
            ->leftJoin(
                'cpo_pipelines',
                'cpo_customer_lead.pipeline_code',
                'cpo_pipelines.pipeline_code'
            )
            ->join(
                'cpo_journey',
                'cpo_customer_lead.journey_code',
                'cpo_journey.journey_code'
            )
            ->where('cpo_customer_lead.customer_lead_code', $input['customer_lead_code']);

        if ($mSelect->first()) {
            return $mSelect->first();
        }
        return [];
    }

    public function getInfo($input)
    {
        $mSelect = $this
            ->select(
                "{$this->table}.avatar",
                "{$this->table}.customer_lead_id",
                "{$this->table}.customer_lead_code",
                "{$this->table}.full_name",
                "{$this->table}.birthday",
                "{$this->table}.phone",
                "{$this->table}.hotline",
                "{$this->table}.tax_code",
                "{$this->table}.customer_source",
                'customer_sources.customer_source_name',
                "{$this->table}.customer_type",
                "{$this->table}.pipeline_code",
                'cpo_pipelines.pipeline_name',
                "{$this->table}.journey_code",
                "j.journey_name",
                "{$this->table}.email",
                "{$this->table}.gender",
                "{$this->table}.province_id",
                "province.type as province_type",
                "province.name as province_name",
                "{$this->table}.district_id",
                "district.type as district_type",
                "district.name as district_name",
                "{$this->table}.ward_id",
                "ward.type as ward_type",
                "ward.name as ward_name",
                "{$this->table}.address",
                "{$this->table}.email",
                "{$this->table}.zalo",
                "{$this->table}.zalo_id",
                "{$this->table}.fanpage",
                "{$this->table}.facebook_id",
                "{$this->table}.sale_id",
                "s.full_name as sale_name",
                "{$this->table}.is_convert",
                "{$this->table}.representative",
                "{$this->table}.business_clue",
                "l.full_name as business_clue_name",
                "{$this->table}.bussiness_id",
                "bussiness.name as business_name",
                "{$this->table}.employees",
                "cpo_pipelines.time_revoke_lead",
                "{$this->table}.date_revoke",
                "{$this->table}.allocation_date",
                "cpo_deals.amount",
                "{$this->table}.date_last_care",
                "{$this->table}.tag_id"
            )
            ->leftJoin('customer_sources', 'cpo_customer_lead.customer_source', 'customer_sources.customer_source_id')
            ->leftJoin("cpo_pipelines", "{$this->table}.pipeline_code", "cpo_pipelines.pipeline_code")
            ->join('cpo_journey as j', "j.journey_code", "=", "{$this->table}.journey_code")
            ->leftJoin('province', "province.provinceid", "=", "{$this->table}.province_id")
            ->leftJoin('district', "district.districtid", "=", "{$this->table}.district_id")
            ->leftJoin('ward', "ward.ward_id", "=", "{$this->table}.ward_id")
            ->leftJoin("staffs as s", "s.staff_id", "=", "{$this->table}.sale_id")
            ->leftJoin("cpo_customer_lead as l", "l.customer_lead_code", "=", "{$this->table}.business_clue")
            ->leftJoin("bussiness", "bussiness.id", "=", "{$this->table}.bussiness_id")
            ->leftJoin('cpo_deals', "{$this->table}.customer_lead_code", "cpo_deals.customer_code");
;
        if (isset($input['customer_lead_code']) && $input['customer_lead_code'] != null) {
            $mSelect->where('cpo_customer_lead.customer_lead_code', $input['customer_lead_code']);
        }
        return $mSelect->first();
    }

    /**
     * DS KHTN
     *
     * @param $input
     * @return mixed
     */
    public function getDataLead($input)
    {
        $mSelect = $this
            ->select(
                "{$this->table}.avatar",
                "{$this->table}.customer_source",
                'customer_sources.customer_source_name',
                "{$this->table}.customer_lead_id",
                "{$this->table}.customer_lead_code",
                "{$this->table}.full_name as lead_full_name",
                "{$this->table}.birthday",
                "{$this->table}.phone",
                "{$this->table}.customer_type",
                "{$this->table}.sale_id",
                'staffs.full_name as staff_full_name',
                "{$this->table}.zalo",
                "{$this->table}.zalo_id",
                "{$this->table}.fanpage",
                "{$this->table}.facebook_id",
                "{$this->table}.tag_id",
                "{$this->table}.is_convert",
                "{$this->table}.pipeline_code",
                "cpo_pipelines.pipeline_name",
                "{$this->table}.journey_code",
                'cpo_journey.journey_name',
                "{$this->table}.is_convert",
                "{$this->table}.date_last_care"
            )
            ->leftJoin("cpo_pipelines", "cpo_pipelines.pipeline_code", "=", "{$this->table}.pipeline_code")
            ->leftJoin("cpo_journey", function ($join) {
                $join->on("cpo_journey.journey_code", "=", "{$this->table}.journey_code")
                    ->on(DB::raw("{$this->table}.pipeline_code"), '=', "cpo_journey.pipeline_code");
            })
            ->leftJoin('staffs', 'cpo_customer_lead.sale_id', 'staffs.staff_id')
            ->leftJoin('cpo_tag', "{$this->table}.tag_id", "cpo_tag.tag_id")
            ->leftJoin('customer_sources', "{$this->table}.customer_source", "customer_sources.customer_source_id")
            ->orderBy("{$this->table}.customer_lead_id", "desc");

        // phân quyền theo user
        //1. User là người tạo lead nếu lead đó chưa phân bổ cho ai
        //2. User là chủ sở hữu pineline nào thì xem pineline ấy
        //3. User dc phân công ai thì dc xem người ấy
        //4. User là người dc phân công chăm sóc
        if (Auth()->user()->is_admin != 1) {
            $mSelect->where(function ($query) {
                $userLogin = Auth()->id();

                $query->whereRaw("{$this->table}.sale_id IS NULL and {$this->table}.created_by = $userLogin ")
                    ->orWhere("cpo_pipelines.owner_id", $userLogin)
                    ->orWhere("{$this->table}.assign_by",  $userLogin)
                    ->orWhere("{$this->table}.sale_id",  $userLogin);
            });
        }

        if (isset($input['search']) != "") {
            $search = $input['search'];
            $mSelect->where(function ($query) use ($search) {
                $query->where("cpo_customer_lead.full_name", 'like', '%' . $search . '%')
                    ->orWhere("cpo_customer_lead.customer_lead_code", '%' . $search . '%')
                    ->orWhere("cpo_customer_lead.phone", 'like', '%' . $search . '%');
            });
        }
        if (isset($input['tag_id']) && $input['tag_id'] != null && $input['tag_id'] != '' ) {
            $mSelect->where(function($query) use ($input){
                if(isset($input['tag_id']) && $input['tag_id'] != '' && $input['tag_id'] != null){
                    $arrTag = json_decode($input['tag_id']);
                    foreach ($arrTag as $tag){
                        $query->orWhereJsonContains("{$this->table}.tag_id", $tag);
                    }
                }
            });
        }

        if (isset($input['customer_type']) && $input['customer_type'] != null) {
            $mSelect->where("{$this->table}.customer_type", $input['customer_type']);
        }
        if (isset($input['status_assign'])) {
            switch ($input['status_assign']) {
                case "unassigned":
                    $mSelect->whereNull("{$this->table}.sale_id");
                    break;
                case "assigned":
                    $mSelect->whereNotNull("{$this->table}.sale_id");
                    break;
            }
        }
        if (isset($input['customer_source_id']) && $input['customer_source_id'] != null && count($input['customer_source_id']) != 0) {
            $mSelect->whereIn("customer_sources.customer_source_id",$input['customer_source_id']);
        }
        if (isset($input["created_at"]) && $input["created_at"] != null) {
            $arr_filter_create = explode(" - ", $input["created_at"]);
            $startCreateTime = Carbon::createFromFormat("d/m/Y", $arr_filter_create[0])->format("Y-m-d");
            $endCreateTime = Carbon::createFromFormat("d/m/Y", $arr_filter_create[1])->format("Y-m-d");
            $mSelect->whereBetween("{$this->table}.created_at", [$startCreateTime . " 00:00:00", $endCreateTime . " 23:59:59"]);
        }
        if (isset($input["allocation_date"]) && $input["allocation_date"] != null) {
            $arr_filter_allocate = explode(" - ", $input["allocation_date"]);
            $startAllocateTime = Carbon::createFromFormat("d/m/Y", $arr_filter_allocate[0])->format("Y-m-d");
            $endAllocateTime = Carbon::createFromFormat("d/m/Y", $arr_filter_allocate[1])->format("Y-m-d");
            $mSelect->whereBetween("{$this->table}.allocation_date", [$startAllocateTime . " 00:00:00", $endAllocateTime . " 23:59:59"]);
        }
        if (isset($input['is_convert'])) {
            switch ($input['is_convert']) {
                case "converted":
                    $mSelect->where("{$this->table}.is_convert", 1);
                    break;
                case "unconverted":
                    $mSelect->where("{$this->table}.is_convert", 0);
                    break;
            }
        }
        if (isset($input['staff_id']) && $input['staff_id'] != null && count($input['staff_id']) != 0) {
            $mSelect->whereIn("staffs.staff_id",$input['staff_id']);
        }
        if (isset($input['pipeline_id']) && $input['pipeline_id'] != null && count($input['pipeline_id']) != 0) {
            $mSelect->whereIn("cpo_pipelines.pipeline_id", $input['pipeline_id']);
        }
        if (isset($input['journey_id']) && $input['journey_id'] != null && count($input['journey_id']) != 0) {
            $mSelect->whereIn("cpo_journey.journey_id", $input['journey_id']);
        }

        if (isset($input["care_history"]) && $input["care_history"] != null) {
            $arr_filter_allocate = explode(" - ", $input["care_history"]);
            $startAllocateTime = Carbon::createFromFormat("d/m/Y", $arr_filter_allocate[0])->format("Y-m-d");
            $endAllocateTime = Carbon::createFromFormat("d/m/Y", $arr_filter_allocate[1])->format("Y-m-d");
            $mSelect->whereBetween("{$this->table}.date_last_care", [$startAllocateTime . " 00:00:00", $endAllocateTime . " 23:59:59"]);
        }
        // get số trang
        $page = (int)($input["page"] ?? 1);
        return $mSelect->paginate(PAGING_ITEM_PER_PAGE, $columns = ["*"], $pageName = "page", $page);
    }

    /**
     * Lay thong tin KHTN bang code
     *
     * @param $leadCode
     * @return mixed
     */
    public function getInfoByCode($leadCode)
    {
        return $this
            ->select(
                "{$this->table}.full_name",
                "{$this->table}.avatar",
                'customer_sources.customer_source_name',
                "{$this->table}.email",
                "{$this->table}.gender",
                'province.name as province_name',
                'district.name as district_name',
                'ward.name as ward_name',
                "{$this->table}.address",
                "{$this->table}.business_clue",
                "{$this->table}.fanpage",
                "{$this->table}.zalo"

            )
            ->leftJoin(
                'customer_sources',
                "{$this->table}.customer_source",
                'customer_sources.customer_source_id'
            )
            ->leftJoin(
                'province',
                "{$this->table}.province_id",
                'province.provinceid'
            )
            ->leftJoin(
                'district',
                "{$this->table}.district_id",
                'district.districtid'
            )
            ->leftJoin(
                'ward',
                "{$this->table}.ward_id",
                'ward.ward_id'
            )
            ->where("customer_lead_code", $leadCode)->first();
    }
    //cap nhat thong tin KHTN
    public function actionUpdate($dataUpdate, $customerLeadCode)
    {
        return $this
            ->where($this->table . '.customer_lead_code', $customerLeadCode)
            ->update($dataUpdate);
    }
    //xoa KHTN
    public function actionDelete($input)
    {
        return $this
            ->where($this->table . '.customer_lead_code', $input)
            ->delete();
    }

}