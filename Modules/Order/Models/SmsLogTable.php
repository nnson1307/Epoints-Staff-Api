<?php


namespace Modules\Order\Models;


use Illuminate\Database\Eloquent\Model;

class SmsLogTable extends Model
{
    protected $table = "sms_log";
    protected $primaryKey = "id";
    protected $fillable = [
        'id',
        'brandname',
        'campaign_id',
        'phone',
        'customer_name',
        'message',
        'sms_status',
        'sms_type',
        'error_code',
        'error_description',
        'sms_guid',
        'created_at',
        'updated_at',
        'time_sent',
        'time_sent_done',
        'sent_by',
        'created_by',
        'object_id',
        'object_type'
    ];

    /**
     * insert sms log
     *
     * @param array $data
     * @return mixed
     */
    public function add(array $data)
    {
        return $this->create($data)->id;
    }
}