<?php
/**
 * Created by PhpStorm   .
 * User: Mr Son
 * Date: 2020-02-19
 * Time: 4:25 PM
 * @author SonDepTrai
 */

namespace Modules\Booking\Models;


use Illuminate\Database\Eloquent\Model;

class EmailLogTable extends Model
{
    protected $table = 'email_log';
    protected $primaryKey = 'id';
    protected $fillable = [
        'id',
        'campaign_id',
        'email',
        'customer_name',
        'email_status',
        'email_type',
        'content_sent',
        'created_at',
        'updated_at',
        'time_sent',
        'time_sent_done',
        'provider',
        'sent_by',
        'created_by',
        'updated_by',
        'object_id',
        'object_type'
    ];

    /**
     * Thêm email log
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
     * Lấy thông tin email log
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
                "content_sent",
                "time_sent_done",
                "sent_by"
            )
            ->where("object_id", $objectId)
            ->where("object_type", $objectType)
            ->whereNull("time_sent_done")
            ->first();
    }

    /**
     * Chỉnh sửa email log
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