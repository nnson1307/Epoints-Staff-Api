<?php

namespace Modules\CustomerLead\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;


class CustomerDealsTable extends Model
{
    protected $table = "cpo_deals";
    protected $primaryKey = "deal_id";
    public $timestamps = false;
    protected $casts = [
        "price" => 'float',
        "discount" => 'float',
        "quantity" => 'integer',
        "probability" => 'integer',
        "amount" => 'float'
    ];


    /**
     * Lấy ds deal
     *
     * @param $input
     * @return mixed
     */
    public function getDataDeal($input)
    {
        $mSelect = $this
            ->select(
                "{$this->table}.deal_id",
                "{$this->table}.deal_code",
                "{$this->table}.deal_name",
                "{$this->table}.phone",
                "{$this->table}.created_at",
                "{$this->table}.updated_at",
                'staffs.full_name as staff_full_name',
                "{$this->table}.type_customer",
                "{$this->table}.customer_code",
                "{$this->table}.pipeline_code",
                "{$this->table}.journey_code",
                'cpo_journey.journey_name',
                "cpo_journey.background_color as background_color_journey",
                "{$this->table}.branch_code",
                'branches.branch_name',
                'order_sources.order_source_name',
                'cpo_pipelines.pipeline_name',
                "{$this->table}.amount",
                "{$this->table}.probability",
                "{$this->table}.date_last_care",
                "{$this->table}.closing_date",
                "{$this->table}.closing_due_date",
                "{$this->table}.reason_lose_code",
                "{$this->table}.deal_type_code",
                "{$this->table}.tag"

            )
            ->leftJoin('staffs', 'staffs.staff_id', "=", "{$this->table}.sale_id")
            ->leftJoin("cpo_pipelines", "cpo_pipelines.pipeline_code", "=", "{$this->table}.pipeline_code")
            ->leftJoin('cpo_journey', function ($join) {
                $join->on("cpo_journey.journey_code", '=', "{$this->table}.journey_code")
                    ->on("cpo_pipelines.pipeline_code", '=', "cpo_journey.pipeline_code");
            })
            ->leftJoin('order_sources', 'cpo_deals.order_source_id','order_sources.order_source_id')
            ->leftJoin('branches', 'cpo_deals.branch_code','branches.branch_code')
            ->leftJoin('manage_work', "{$this->table}.deal_id", "manage_work.obj_id")
            ->orderBy("{$this->table}.deal_id", "desc");

        // phân quyền theo user
        //1. User là người tạo lead
        //2. User là chủ sở hữu pineline nào thì xem pineline ấy
        //3. User dc phân công ai thì dc xem người ấy
        //4. User là người dc phân công chăm sóc
        if (Auth()->user()->is_admin != 1) {
            $mSelect->where(function ($query) {
                $query->where("{$this->table}.created_by", Auth()->id())
                    ->orWhere("cpo_pipelines.owner_id", Auth()->id())
                    ->orWhere("{$this->table}.owner",  Auth()->id())
                    ->orWhere("{$this->table}.sale_id",  Auth()->id());
            });
        }

        if (isset($input['search']) != "") {
            $search = $input['search'];
            $mSelect->where(function ($query) use ($search) {
                $query
                    ->where("cpo_deals.deal_name", 'like', '%' . $search . '%')
                    ->orWhere("cpo_deals.deal_code", 'like', '%' . $search . '%')
                    ->orWhere("cpo_deals.phone", 'like', '%' . $search . '%');
            });
        }
        if (isset($input['order_source_name']) && $input['order_source_name'] != null) {
            $mSelect->where("order_sources.order_source_name", $input['order_source_name']);
        }
        if (isset($input['branch_id']) && $input['branch_id'] != null && count($input['branch_id']) != 0) {
            $mSelect->whereIn("branches.branch_id", $input['branch_id']);
        }
        if (isset($input["created_at"]) && $input["created_at"] != null) {
            $arr_filter_create = explode(" - ", $input["created_at"]);
            $startCreateTime = Carbon::createFromFormat("d/m/Y", $arr_filter_create[0])->format("Y-m-d");
            $endCreateTime = Carbon::createFromFormat("d/m/Y", $arr_filter_create[1])->format("Y-m-d");
            $mSelect->whereBetween("{$this->table}.created_at", [$startCreateTime . " 00:00:00", $endCreateTime . " 23:59:59"]);
        }
        if (isset($input["closing_date"]) && $input["closing_date"] != null) {
            $arr_filter_closing = explode(" - ", $input["closing_date"]);
            $startClosingTime = Carbon::createFromFormat("d/m/Y", $arr_filter_closing[0])->format("Y-m-d");
            $endClosingTime = Carbon::createFromFormat("d/m/Y", $arr_filter_closing[1])->format("Y-m-d");
            $mSelect->whereBetween("{$this->table}.closing_date", [$startClosingTime, $endClosingTime]);
        }
        if (isset($input['staff_id']) && $input['staff_id'] != null && count($input['staff_id']) != 0) {
            $mSelect->where("staffs.staff_id", $input['staff_id']);
        }
        if (isset($input["closing_due_date"]) && $input["closing_due_date"] != null) {
            $arr_filter_closing_due = explode(" - ", $input["closing_due_date"]);
            $startClosingDueTime = Carbon::createFromFormat("d/m/Y", $arr_filter_closing_due[0])->format("Y-m-d");
            $endClosingDueTime = Carbon::createFromFormat("d/m/Y", $arr_filter_closing_due[1])->format("Y-m-d");
            $mSelect->whereBetween("{$this->table}.closing_date", [$startClosingDueTime, $endClosingDueTime]);
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
            $mSelect->whereBetween("manage_work.updated_at", [$startAllocateTime . " 00:00:00", $endAllocateTime . " 23:59:59"]);
        }
        if (isset($input['manage_status_id']) && $input['manage_status_id'] != null && count($input['manage_status_id']) != 0) {
            $mSelect->whereIn("manage_work.manage_status_id", $input['manage_status_id']);
        }
        // get số trang
        $page = (int)($input["page"] ?? 1);
        return $mSelect->paginate(PAGING_ITEM_PER_PAGE, $columns = ["*"], $pageName = "page", $page);
    }

    public function getDetail($input)
    {
        $mSelect = $this
            ->select(
                "{$this->table}.deal_id",
                "{$this->table}.deal_code",
                "{$this->table}.deal_name",
                "{$this->table}.phone",
                "{$this->table}.amount",
                "{$this->table}.type_customer",
                "{$this->table}.customer_code",
                "{$this->table}.deal_code",
                'cpo_pipelines.pipeline_code',
                'cpo_journey.journey_name',
                'cpo_journey.journey_code',
                "cpo_journey.background_color as background_color_journey",
                "{$this->table}.closing_date",
                "{$this->table}.closing_due_date",
                "{$this->table}.reason_lose_code",
                "{$this->table}.branch_code",
                'branches.branch_name',
                "{$this->table}.order_source_id",
                'order_sources.order_source_name',
                "{$this->table}.tag",
                "{$this->table}.probability",
                "{$this->table}.deal_description",
                "{$this->table}.sale_id",
                'staffs.full_name as staff_name',
                "cpo_pipelines.time_revoke_lead",
                "{$this->table}.created_at",
                "{$this->table}.updated_at",
                "{$this->table}.date_last_care"
            )
            ->join('cpo_journey', 'cpo_deals.journey_code','cpo_journey.journey_code')
            ->join('cpo_pipelines', 'cpo_deals.pipeline_code','cpo_pipelines.pipeline_code')
            ->leftJoin('staffs', 'cpo_deals.sale_id','staffs.staff_id')
            ->leftJoin('branches', 'cpo_deals.branch_code','branches.branch_code')
            ->leftJoin('order_sources', 'cpo_deals.order_source_id','order_sources.order_source_id')
            ->where("{$this->table}.deal_code", $input['deal_code']);

        return $mSelect->first();
    }
    //lấy customer_type,customer_code để add customer_name cho api chi tiết deal
    public function getTypeCode($input)
    {
        $mSelect = $this
            ->select(
                "{$this->table}.type_customer",
                "{$this->table}.customer_code"
            )
            ->leftJoin('cpo_journey', 'cpo_deals.journey_code','cpo_journey.journey_code')
            ->where("{$this->table}.deal_code", $input['deal_code']);

        return $mSelect->get();
    }

    //thông tin khách hàng
    public function getInfoCustomer($input)
    {
        $mSelect = $this
            ->select(
                "{$this->table}.phone",
                "{$this->table}.type_customer"
            )
            ->where("{$this->table}.deal_code", $input['deal_code']);

        return $mSelect->first();
    }

    //cap nhat thong tin deal
    public function actionUpdate($dataUpdate, $dealCode){
        return $this
            ->where($this->table.'.deal_code',$dealCode)
            ->update($dataUpdate);
    }
    //xoa DEAL
    public function actionDelete($input){
        return $this
            ->where($this->table.'.deal_code',$input)
            ->delete();
    }

    public function createdDeal($dataDeal){
        return $this
            ->insertGetId($dataDeal);
    }

    public function updateDealCode(array $data, $id){
        return $this
            ->where("{$this->table}.deal_id", $id)
            ->update($data);
    }
   public  function getInfoDeal($code){
        $oSelect = $this
            ->select(
                "{$this->table}.deal_id",
                "{$this->table}.deal_code",
                "{$this->table}.customer_code",
                "{$this->table}.type_customer",
                "cpo_customer_lead.customer_lead_id",
                "{$this->table}.pipeline_code",
                "{$this->table}.journey_code",
                "{$this->table}.deal_name",
                "{$this->table}.probability",
                "{$this->table}.created_at",
                "{$this->table}.sale_id",
                "staffs.full_name as staff_name",
                "{$this->table}.amount",
                "{$this->table}.date_last_care"
            )
            ->leftJoin('staffs', 'cpo_deals.sale_id','staffs.staff_id')
            ->leftJoin('cpo_customer_lead', 'cpo_deals.customer_code','cpo_customer_lead.customer_lead_code')
        ->where( "{$this->table}.customer_code", $code);
        return $oSelect->get()->toArray();

   }
}
