<?php

/**
 * Created by PhpStorm
 * User: Mr Son
 * Date: 7/24/2020
 * Time: 10:52 AM
 */

namespace Modules\ChatHub\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CustomerLeadTable extends Model
{

    protected $table = "cpo_customer_lead";
    protected $primaryKey = "customer_lead_id";
    protected $fillable = [
        "customer_lead_id",
        "customer_lead_code",
        'ch_customer_id',
        "full_name",
        "email",
        "phone",
        "gender",
        "birthday",
        "address",
        "avatar",
        "tag_id",
        "pipeline_code",
        "journey_code",
        "customer_type",
        "hotline",
        "fanpage",
        "zalo",
        "is_deleted",
        "created_by",
        "updated_by",
        "created_at",
        "updated_at",
        "tax_code",
        "representative",
        "customer_source",
        "business_clue",
        "is_convert",
        "convert_object_type",
        "convert_object_code",
        "assign_by",
        "sale_id",
        "date_revoke",
        "province_id",
        "district_id",
        "deal_code",
        "custom_1",
        "custom_2",
        "custom_3",
        "custom_4",
        "custom_5",
        "custom_6",
        "custom_7",
        "custom_8",
        "custom_9",
        "custom_10",
        "number_row",
        "id_google_sheet",
        "allocation_date"
    ];

    const NOT_DELETE = 0;
    const BUSINESS = "business";

    /**
     * Danh sách KH tiềm năng
     *
     * @param array $filter
     * @return mixed
     */
    public function getList(&$filter = [])
    {
        $ds = $this
            ->select(
                "{$this->table}.customer_lead_code as customer_code",
                "{$this->table}.customer_lead_id as customer_id",
                "{$this->table}.email",
                "{$this->table}.full_name",
                "{$this->table}.phone",
                DB::raw("'customer_lead' as type"),
                "{$this->table}.ch_customer_id"
           
            )
            ->where("{$this->table}.is_deleted", self::NOT_DELETE)
            ->orderBy("{$this->table}.customer_lead_id", "desc")
            ->groupBy("{$this->table}.customer_lead_code");

      
        // filter tên tên, mã
        if (isset($filter['search']) && $filter['search'] != "") {
            $search = $filter['search'];

            $ds->where(function ($query) use ($search) {
                $query->where("{$this->table}.full_name", 'like', '%' . $search . '%')
                    ->orWhere("{$this->table}.customer_lead_code", 'like', '%' . $search . '%')
                    ->orWhere("{$this->table}.phone", 'like', '%' . $search . '%');
            });
        }

         // get số trang
         $page = (int)($filter["page"] ?? 1);
         return $ds->skip(($page - 1) * PAGING_ITEM_PER_PAGE)->take(PAGING_ITEM_PER_PAGE)->get();
    }

    public function getInfo($input)
    {
        $mSelect = $this
            ->select(
                "{$this->table}.customer_lead_id",
                'cpo_customer_lead.full_name',
                'cpo_customer_lead.phone',
                'cpo_customer_lead.customer_lead_code',
                "{$this->table}.customer_source",
                'customer_sources.customer_source_name',
                'cpo_customer_lead.customer_type',
                'cpo_pipelines.pipeline_name',
                "{$this->table}.pipeline_code",
                "j.journey_name",
                "j.journey_code",
                "j.position as journey_position",
                "{$this->table}.email",
                "{$this->table}.gender",
                "{$this->table}.province_id",
                "{$this->table}.district_id",
                "province.type as province_type",
                "province.name as province_name",
                "district.type as district_type",
                "district.name as district_name",
                "{$this->table}.address",
                "{$this->table}.email",
                "{$this->table}.zalo",
                "{$this->table}.fanpage",
                "{$this->table}.sale_id",
                "s.full_name as sale_name",
                "{$this->table}.is_convert",
                "{$this->table}.business_clue",
                "l.full_name as business_clue_name",
                "cpo_pipelines.time_revoke_lead",
                "{$this->table}.date_revoke",
                "{$this->table}.allocation_date",
                "{$this->table}.deal_code"
            )
            ->leftJoin('customer_sources', 'cpo_customer_lead.customer_source', 'customer_sources.customer_source_id')
            ->join('cpo_pipelines', 'cpo_customer_lead.pipeline_code', 'cpo_pipelines.pipeline_code')
            ->join('cpo_journey as j', "j.journey_code", "=", "{$this->table}.journey_code")
            ->leftJoin('province', "province.provinceid", "=", "{$this->table}.province_id")
            ->leftJoin('district', "district.districtid", "=", "{$this->table}.district_id")
            ->leftJoin("staffs as s", "s.staff_id", "=", "{$this->table}.sale_id")
            ->leftJoin("cpo_customer_lead as l", "l.customer_lead_code", "=", "{$this->table}.business_clue");
        if (isset($input['customer_lead_id']) && $input['customer_lead_id'] != null) {
            $mSelect->where('cpo_customer_lead.customer_lead_id', $input['customer_lead_id']);
        }

        return $mSelect->first();
    }

     /**
     * Chỉnh sửa KH tiềm năng
     *
     * @param array $data
     * @param $customerLeadId
     * @return mixed
     */
    public function edit(array $data, $customerLeadId)
    {
        return $this->where("customer_lead_id", $customerLeadId)->update($data);
    }
}
