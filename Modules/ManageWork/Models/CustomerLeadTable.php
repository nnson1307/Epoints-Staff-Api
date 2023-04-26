<?php
/**
 * Created by PhpStorm
 * User: Mr Son
 * Date: 7/24/2020
 * Time: 10:52 AM
 */

namespace Modules\ManageWork\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use MyCore\Models\Traits\ListTableTrait;

class CustomerLeadTable extends Model
{
    use ListTableTrait;
    protected $table = "cpo_customer_lead";
    protected $primaryKey = "customer_lead_id";

    const NOT_DELETE = 0;
    const BUSINESS = "business";

    /**
     * Danh sách lead export excel
     *
     * @param $filter
     * @return mixed
     */
    public function getAllCustomerLead($filter)
    {
        $ds = $this
            ->select(
                "{$this->table}.customer_lead_id as customer_id",
                "{$this->table}.full_name as customer_name",
                "{$this->table}.avatar as customer_avatar"
            )
            ->join("cpo_pipelines", "cpo_pipelines.pipeline_code", "=", "{$this->table}.pipeline_code")
            ->join("cpo_journey", function ($join) {
                $join->on("cpo_journey.journey_code", "=", "{$this->table}.journey_code")
                    ->on(DB::raw("{$this->table}.pipeline_code"), '=', "cpo_journey.pipeline_code");
            })
            ->leftJoin("customer_sources", "customer_sources.customer_source_id", "=", "{$this->table}.customer_source")
            ->leftJoin("staffs as s", "s.staff_id", "=", "{$this->table}.assign_by")
            ->leftJoin("staffs as ss", "ss.staff_id", "=", "{$this->table}.sale_id")
            ->leftJoin("province", "province.provinceid", "=", "{$this->table}.province_id")
            ->leftJoin("district", "district.districtid", "=", "{$this->table}.district_id")
            ->where("{$this->table}.is_deleted", self::NOT_DELETE)
            ->where("{$this->table}.is_convert", 0)
            ->orderBy("{$this->table}.customer_lead_id", "desc");

        // phân quyền theo user
        //1. User là người tạo lead
        //2. User là chủ sở hữu pineline nào thì xem pineline ấy
        //3. User dc phân công ai thì dc xem người ấy
        //4. User là người dc phân công chăm sóc
        if (Auth()->user()->is_admin != 1) {
            $ds->where(function ($query) {
                $query->where("{$this->table}.created_by", Auth()->id())
                    ->orWhere("cpo_pipelines.owner_id", Auth()->id())
                    ->orWhere("{$this->table}.assign_by",  Auth()->id())
                    ->orWhere("{$this->table}.sale_id",  Auth()->id());
            });
        }

        // filter theo người tạo
        if (isset($filter['created_by']) && !empty($filter['created_by'])) {
            $ds->where("{$this->table}.created_by", $filter['created_by']);
        }

        return $ds->get();
    }
    public function updateCare($dateCare,$id){
        return $this
            ->where("{$this->table}.customer_lead_id", $id)
            ->update($dateCare);
    }
}
