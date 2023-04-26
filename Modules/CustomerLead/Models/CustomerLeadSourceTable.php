<?php

namespace Modules\CustomerLead\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerLeadSourceTable extends Model
{
    protected $table = "customer_sources";
    protected $primaryKey = "customer_source_id";
    public $timestamps = false;

    public function getOption()
    {
        $mSelect = $this
            ->select(
                'customer_source_id',
                'customer_source_name as Source_name',
                'customer_source_type'

            );
        if ($mSelect->get()) {
            return $mSelect->get()->toArray();
        }
        return [];
    }
}