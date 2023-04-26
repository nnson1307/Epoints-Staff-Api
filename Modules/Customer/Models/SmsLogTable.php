<?php
/**
 * Created by PhpStorm   .
 * User: Mr Son
 * Date: 2020-02-19
 * Time: 11:57 PM
 * @author SonDepTrai
 */

namespace Modules\Customer\Models;


use Illuminate\Database\Eloquent\Model;

class SmsLogTable extends Model
{
    protected $table = 'sms_log';
    protected $primaryKey = 'id';
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
     * Thêm sms log
     *
     * @param array $data
     * @return mixed
     */
    public function add(array $data)
    {
        $add = $this->create($data);
        return $add->id;
    }

    /**
     * Lấy thông tin sms log
     *
     * @param $objectId
     * @param $objectType
     * @return mixed
     */
    public function getLog($objectId, $objectType)
    {
        return $this
            ->select(
                "id",
                "message",
                "time_sent_done",
                "sent_by"
            )
            ->where("object_id", $objectId)
            ->where("object_type", $objectType)
            ->whereNull("time_sent_done")
            ->first();
    }

    /**
     * Cập nhật sms log
     *
     * @param array $data
     * @param $id
     * @return mixed
     */
    public function edit(array $data, $id)
    {
        return $this->where("id", $id)->update($data);
    }
}