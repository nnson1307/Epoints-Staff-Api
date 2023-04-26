<?php

namespace Modules\CustomerLead\Models;

use Illuminate\Database\Eloquent\Model;

class BranchTable extends Model
{
    protected $table = "branches";
    protected $primaryKey = "staff_id";
    public $timestamps = false;

    public function getBranch()
    {
        $mSelect = $this
            ->select(
                'branch_id',
                'branch_name',
                'address',
                'branch_code',
                'avatar'
            );
        if ($mSelect->get()) {
            return $mSelect->get()->toArray();
        }
        return [];
    }
}