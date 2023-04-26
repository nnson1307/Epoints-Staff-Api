<?php

namespace Modules\CustomerLead\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerLeadAllocateTable extends Model
{
    protected $table = "staffs";
    protected $primaryKey = "staff_id";
    public $timestamps = false;

    public function getDataAllocator()
    {
        $mSelect = $this
            ->select(
                'staff_id',
                'full_name'
            );
        if ($mSelect->get()) {
            return $mSelect->get()->toArray();
        }
        return [];
    }
}