<?php

namespace Modules\CustomerLead\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerLeadWardTable extends Model
{
    protected $table = "ward";
    protected $primaryKey = "ward_id";
    public $timestamps = false;

    public function getDataWard($districtId)
    {
        $mSelect = $this
            ->select(
                'ward_id',
                'type',
                'name'
            )
            ->where('district_id', $districtId);
        if ($mSelect->get()) {
            return $mSelect->get()->toArray();
        }
        return [];
    }
}