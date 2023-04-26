<?php

namespace Modules\CustomerLead\Models;

use Illuminate\Database\Eloquent\Model;

class BusinessTable extends Model
{
    protected $table = "bussiness";
    protected $primaryKey = "id";
    public $timestamps = false;

    public function getList()
    {
        $mSelect = $this
            ->select(
                "{$this->table}.name as business_name",
                "{$this->table}.description",
                "{$this->table}.created_by",
                "{$this->table}.created_at"
            );
        return $mSelect->get() ;
    }
    public function addBusinessAreas($data){
        return $this-> insertGetId($data);
    }
}