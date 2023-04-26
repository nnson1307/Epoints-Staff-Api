<?php

namespace Modules\ChatHub\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerLeadJourneyTable extends Model
{
    protected $table = "cpo_journey";
    protected $primaryKey = "journey_id";
    public $timestamps = false;

    const NOT_DELETE = 0;
    const NEW = 'new';
    const IS_ACTIVE = 1;
    
    public function getDataJourney($pipelineCode)
    {
        $mSelect = $this
            ->select(
                'journey_code',
                'journey_id',
                'journey_name',
                'pipeline_id',
                'pipeline_code',
                'background_color'
            )
            ->where('pipeline_code', $pipelineCode);

        return $mSelect->get()->toArray();
    }

     /**
     * Lấy option hành trình KH khi edit
     *
     * @param $pipelineCode
     * @param $position
     * @return mixed
     */
    public function getOptionEdit($pipelineCode, $position)
    {
        return $this
            ->select(
                "journey_id",
                "journey_name",
                "journey_code",
                "position"
            )
            ->where("pipeline_code", $pipelineCode)
            ->where("position", ">=", $position)
            ->where("is_deleted", self::NOT_DELETE)
            ->get();
    }

    /**
     * Lấy thông tin journey
     *
     * @param $journeyCode
     * @return mixed
     */
    public function getInfo($journeyCode)
    {
        return $this->where("journey_code", $journeyCode)->first();
    }

     /**
     * Lấy thông tin cập nhật journey
     *
     * @param $pipelineId
     * @param $journeyCode
     * @return mixed
     */
    public function getInfoUpdateJourney($pipelineId, $journeyCode)
    {
        return $this
            ->where("pipeline_id", $pipelineId)
            ->where("journey_code", $journeyCode)
            ->first();
    }

}