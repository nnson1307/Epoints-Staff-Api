<?php

namespace Modules\CustomerLead\Models;

use Illuminate\Database\Eloquent\Model;

class OrderSourceTable extends Model
{
    protected $table = "order_sources";
    protected $primaryKey = "order_source_id";
    public $timestamps = false;

    public function getListOrderSource()
    {
        $mSelect = $this
            ->select(
                'order_source_id',
                'order_source_name'
            );
        if ($mSelect->get()) {
            return $mSelect->get()->toArray();
        }
        return [];
    }
}