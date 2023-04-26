<?php

namespace Modules\ProjectManagement\Models;

use Illuminate\Database\Eloquent\Model;


class StaffTitleTable extends Model
{
    protected $table = "staff_title";
    protected $primaryKey = "staff_title_id";

    public function getStaffTitle(& $filter = [])
    {
        $mSelect = $this
            ->select(
                "{$this->table}.staff_title_id",
                "{$this->table}.staff_title_name",
                "{$this->table}.slug",
                "{$this->table}.slug",
                "{$this->table}.staff_title_description",
                "{$this->table}.created_at",
                "{$this->table}.created_by"
            );
        return $mSelect->get()->toArray();
    }



}