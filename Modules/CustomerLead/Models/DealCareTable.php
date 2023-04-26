<?php

namespace Modules\CustomerLead\Models;

use Illuminate\Database\Eloquent\Model;

class DealCareTable extends Model
{
    protected $table = "cpo_deal_care";
    protected $primaryKey = "deal_care_id";
    public $timestamps = false;

    public function getInteraction($code){
        $oSelect = $this
            ->select(
                "{$this->table}.updated_at"
            )
            ->orderBy("{$this->table}.updated_at",'desc')
            ->where("{$this->table}.customer_lead_code",$code);
        return $oSelect->first();
    }
    public function getCare($code){
        $oSelect = $this
            ->select(
                "{$this->table}.updated_at",
                "{$this->table}.created_by",
                "staffs.full_name as staff_full_name",
                "{$this->table}.care_type",
                "manage_type_work.manage_type_work_name"
            )
            ->join("staffs",  "{$this->table}.created_by","staffs.staff_id")
            ->join("manage_type_work",  "{$this->table}.care_type","manage_type_work.manage_type_work_key")
            ->orderBy("{$this->table}.updated_at",'desc')
            ->where("{$this->table}.customer_lead_code",$code);
        return $oSelect->get();
    }
    public function getClosestInteraction($id){
        $oSelect = $this
            ->select(
                "{$this->table}.updated_at"
            )
            ->orderBy("{$this->table}.updated_at",'desc')
            ->where("{$this->table}.deal_id",$id);
        return $oSelect->first();
    }
    public function getDealCare($id){
        $oSelect = $this
            ->select(
                "{$this->table}.deal_id",
                "{$this->table}.care_type",
                "manage_type_work.manage_type_work_name",
                "{$this->table}.content",
                "{$this->table}.created_by",
                "staffs.full_name as staff_full_name",
                "{$this->table}.created_at"
            )
            ->join("staffs","{$this->table}.created_by","staffs.staff_id")
            ->join("manage_type_work",  "{$this->table}.care_type","manage_type_work.manage_type_work_key")
            ->orderBy("{$this->table}.updated_at",'desc')
            ->where("{$this->table}.deal_id",$id);
        return $oSelect->get();
    }
}