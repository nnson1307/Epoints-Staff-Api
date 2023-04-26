<?php


namespace Modules\ManageWork\Models;


use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use MyCore\Models\Traits\ListTableTrait;

class CustomerDealTable extends Model
{
    use ListTableTrait;
    protected $table = "cpo_deals";
    protected $primaryKey = "deal_id";

    const NOT_DELETE = 0;
    const RECEIPT_STATUS = ['paid', 'part-paid'];
    const ORDER_STATUS = ['paysuccess', 'pay-half'];

    /**
     * Danh sách tất cả customer deal
     *
     * @param array $filter
     * @return mixed
     */
    public function getAll($filter = [])
    {
        $ds = $this
            ->select(
                "{$this->table}.deal_id as customer_id",
                "{$this->table}.deal_name as customer_name",
                "customers.customer_avatar"
            )
            ->leftJoin("customers", "customers.customer_code", "=", "{$this->table}.customer_code")
            ->leftJoin("staffs", "staffs.staff_id", "=", "cpo_deals.owner")
            ->leftJoin("staffs as ss", "ss.staff_id", "=", "cpo_deals.sale_id")
            ->leftJoin("cpo_pipelines", "cpo_pipelines.pipeline_code", "=", "{$this->table}.pipeline_code")
            ->leftJoin('cpo_journey', function ($join) {
                $join->on("cpo_journey.journey_code", '=', "{$this->table}.journey_code")
                    ->on("cpo_pipelines.pipeline_code", '=', "cpo_journey.pipeline_code");
            })

            ->where("{$this->table}.is_deleted", self::NOT_DELETE)
            ->orderBy("{$this->table}.deal_id", "desc");

        // phân quyền theo user
        //1. User là người tạo lead
        //2. User là chủ sở hữu pineline nào thì xem pineline ấy
        //3. User dc phân công ai thì dc xem người ấy
        //4. User là người dc phân công chăm sóc
        if (Auth()->user()->is_admin != 1) {
            $ds->where(function ($query) {
                $query->where("{$this->table}.created_by", Auth()->id())
                    ->orWhere("cpo_pipelines.owner_id", Auth()->id())
                    ->orWhere("{$this->table}.owner",  Auth()->id())
                    ->orWhere("{$this->table}.sale_id",  Auth()->id());
            });
            unset($filter['user_id']);
        }
        // filter tên tên, mã
        if (isset($filter['search']) && $filter['search'] != "") {
            $search = $filter['search'];

            $ds->where(function ($query) use ($search) {
                $query->where('deal_code', 'like', '%' . $search . '%')
                    ->orWhere('deal_name', 'like', '%' . $search . '%')
                    ->orWhere('customers.full_name', 'like', '%' . $search . '%')
                    ->orWhere('staffs.full_name', 'like', '%' . $search . '%');
            });
        }


        return $ds->get();
    }
}