<?php

namespace Modules\ProjectManagement\Models;

use Illuminate\Database\Eloquent\Model;


class ContractTable extends Model
{
    protected $table = "contracts";
    protected $primaryKey = "contract_id";

    public function getListContract($filter = [])
    {
        $mSelect = $this
            ->select(
                "{$this->table}.contract_id",
                "{$this->table}.contract_category_id",
                "{$this->table}.contract_name",
                "{$this->table}.contract_code",
                "{$this->table}.contract_no",
                "{$this->table}.contract_form",
                "{$this->table}.sign_date",
                "{$this->table}.performer_by",
                "{$this->table}.effective_date",
                "{$this->table}.expired_date",
                "{$this->table}.is_renew",
                "{$this->table}.number_day_renew",
                "{$this->table}.is_created_ticket",
                "{$this->table}.status_code_created_ticket",
                "{$this->table}.warranty_start_date",
                "{$this->table}.warranty_end_date",
                "{$this->table}.content",
                "{$this->table}.note",
                "{$this->table}.status_code",
                "{$this->table}.is_value_goods",
                "{$this->table}.ticket_code",
                "{$this->table}.reason_remove",
                "{$this->table}.created_by",
                "{$this->table}.created_at"
            )
            ->orderBy("{$this->table}.created_at", 'desc')
            ->where("{$this->table}.is_deleted", 0);
        return $mSelect->get()->toArray();
    }


}