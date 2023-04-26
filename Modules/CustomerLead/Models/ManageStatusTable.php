<?php

namespace Modules\CustomerLead\Models;

use Illuminate\Database\Eloquent\Model;

class ManageStatusTable extends Model
{
    protected $table = "manage_status";
    protected $primaryKey = "manage_status_id";
    public $timestamps = false;

    public function getStatusWork($filter = []){
        $oSelect = $this
            ->select(
                "{$this->table}.manage_status_id",
                "{$this->table}.manage_status_value",
                "{$this->table}.manage_status_name",
                "{$this->table}.manage_status_color"
            )
            ->where("{$this->table}.is_active", 1);
        return $oSelect->get();
    }
}