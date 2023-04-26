<?php

namespace Modules\CustomerLead\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerLeadJourneyTable extends Model
{
    protected $table = "cpo_journey";
    protected $primaryKey = "journey_id";
    public $timestamps = false;

    public function getDataJourney($pipelineCode)
    {
        $mSelect = $this
            ->select(
               "{$this->table}.journey_code",
               "{$this->table}.journey_id",
               "{$this->table}.journey_name",
               "{$this->table}.pipeline_id",
               "{$this->table}.pipeline_code",
                'background_color as background_color_journey'
            )
            ->whereIn("{$this->table}.pipeline_code", $pipelineCode);
        return $mSelect->get()->toArray();
    }
    public function getDataJourneyByCode($pipelineCode)
    {
        $mSelect = $this
            ->select(
                "{$this->table}.journey_code",
                "{$this->table}.journey_id",
                "{$this->table}.journey_name",
                "{$this->table}.pipeline_id",
                "{$this->table}.pipeline_code",
                'background_color as background_color_journey'
            )
            ->where("{$this->table}.pipeline_code", $pipelineCode);
        return $mSelect->get()->toArray();
    }
    public function getJourneyName($data)
    {
        $mSelect = $this
            ->select(
                "{$this->table}.journey_id",
                "{$this->table}.journey_name"
            )
            ->where("{$this->table}.pipeline_code", $data['pipeline_code'])
            ->where("{$this->table}.journey_code", $data['journey_code']);
        return $mSelect->first();
    }
}