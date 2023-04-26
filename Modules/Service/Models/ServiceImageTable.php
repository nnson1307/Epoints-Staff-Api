<?php
namespace Modules\Service\Models;
use Illuminate\Database\Eloquent\Model;

class ServiceImageTable extends Model
{
    protected $table = 'service_images';
    protected $primaryKey = 'service_image_id';


    public function getServiceImage($serviceId)
    {
        return $this->select(
            "{$this->table}.service_image_id",
            "{$this->table}.name",
            "{$this->table}.type"
        )
            ->where("{$this->table}.service_id", $serviceId)->get();
    }
}