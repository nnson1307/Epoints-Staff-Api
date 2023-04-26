<?php

namespace Modules\CustomerLead\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerLeadProvinceTable extends Model
{
    protected $table = "province";
    protected $primaryKey = "provinceid";
    public $timestamps = false;

    public function getDataProvince()
    {
        $mSelect = $this
            ->select(
                'provinceid',
                'type',
                'name'
            );
        if ($mSelect->get()) {
            return $mSelect->get()->toArray();
        }
        return [];
    }
}