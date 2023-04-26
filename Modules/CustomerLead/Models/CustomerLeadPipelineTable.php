<?php

namespace Modules\CustomerLead\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerLeadPipelineTable extends Model
{
    protected $table = "cpo_pipelines";
    protected $primaryKey = "pipeline_id";
    public $timestamps = false;

    public function getPipe($filter = [])
    {
        $mSelect = $this
            ->select(
                'pipeline_id',
                'pipeline_code',
                'pipeline_name',
                'pipeline_category_code',
                'owner_id'
            );

        //Filter theo loại pipeline của lead or deal
        if (isset($filter['pipeline_category_code']) && $filter['pipeline_category_code'] != null) {
            $mSelect->where("pipeline_category_code", $filter['pipeline_category_code']);
        }
        return $mSelect->get();
    }
}