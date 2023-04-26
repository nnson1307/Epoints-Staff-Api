<?php

namespace Modules\CustomerLead\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerLeadDistrictTable extends Model
{
    protected $table = "district";
    protected $primaryKey = "districtid";
    public $timestamps = false;

    public function getDataDistrict($provinceId)
    {
        $mSelect = $this
            ->select(
                'districtid',
                'type',
                'name'
            )
            ->where('provinceid', $provinceId);
        if ($mSelect->get()) {
            return $mSelect->get()->toArray();
        }
        return [];
    }
}