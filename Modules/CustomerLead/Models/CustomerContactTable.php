<?php

namespace Modules\CustomerLead\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerContactTable extends Model
{
    protected $table = "cpo_customer_contact";
    protected $primaryKey = "customer_contact_id";
    public $timestamps = false;

    public function addNewContact($data)
    {
       return $this -> insertGetId($data);
    }
    public function editContact($dataContact,$customerLeadCode){
        return  $this
            ->where("{$this->table}.customer_lead_code",$customerLeadCode)
            ->update($dataContact);
    }
    public function getContact($customer_lead_code){
        $oSelect = $this
            ->select(
                "{$this->table}.customer_contact_id",
                "{$this->table}.customer_lead_code",
                "{$this->table}.full_name",
                "{$this->table}.positon",
                "{$this->table}.phone",
                "{$this->table}.email",
                "{$this->table}.address",
                "{$this->table}.customer_contact_tilte_id",
                "{$this->table}.customer_contact_type",
                "customer_contact_tilte.customer_contact_tilte_name_vi",
                "customer_contact_tilte.customer_contact_tilte_name_en",
                "{$this->table}.customer_contact_type"
            )
            ->leftJoin("customer_contact_tilte","{$this->table}.customer_contact_tilte_id","customer_contact_tilte.customer_contact_tilte_id")
            ->where("{$this->table}.customer_lead_code",$customer_lead_code);
        return $oSelect->get();

    }
}