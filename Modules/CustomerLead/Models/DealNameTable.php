<?php

namespace Modules\CustomerLead\Models;

use Illuminate\Database\Eloquent\Model;

class DealNameTable extends Model
{
    protected $table = "cpo_deals";
    protected $primaryKey = "deal_id";
    public $timestamps = false;

    public function getDealName()
    {
        $mSelect = $this
            ->select(
                'deal_id',
                'deal_code',
                'deal_name',
                'customer_code'
            );
        if ($mSelect->get()) {
            return $mSelect->get()->toArray();
        }
        return [];
    }
}