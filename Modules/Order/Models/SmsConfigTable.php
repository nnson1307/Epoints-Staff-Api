<?php


namespace Modules\Order\Models;


use Illuminate\Database\Eloquent\Model;

class SmsConfigTable extends Model
{
    protected $table = 'sms_config';
    protected $primaryKey = 'id';
    protected $fillable = ['id', 'key', 'value', 'time_sent', 'name', 'content', 'is_active',
        'created_by', 'updated_by', 'created_at', 'updated_at', 'actived_by', 'datetime_actived'];

    /**
     * Lấy chi tiết theo type
     *
     * @param $type
     * @return mixed
     */
    public function getItemByType($type)
    {
        return $this->where('key', $type)->first();
    }
}