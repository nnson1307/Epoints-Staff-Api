<?php

namespace Modules\CustomerLead\Models;

use Illuminate\Database\Eloquent\Model;

class StaffTitleTable extends Model
{
    protected $table = "staff_title";
    protected $primaryKey = "staff_title_id";
    public $timestamps = false;

    public function getListPosition()
    {
        $mSelect = $this
            ->select(
                "{$this->table}.staff_title_id",
                "{$this->table}.staff_title_name",
                "{$this->table}.slug",
                "{$this->table}.staff_title_code",
                "{$this->table}.staff_title_description"
            );
       return $mSelect->get();
    }
}