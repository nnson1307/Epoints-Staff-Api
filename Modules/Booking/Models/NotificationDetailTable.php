<?php
/**
 * Created by PhpStorm
 * User: Mr Son
 * Date: 07-04-02020
 * Time: 3:01 PM
 */

namespace Modules\Booking\Models;


use Illuminate\Database\Eloquent\Model;

class NotificationDetailTable extends Model
{
    protected $table = "spa_notification_detail";
    protected $primaryKey = "notification_detail_id";
    protected $fillable = [
        "notification_detail_id",
        "notification_auto_group",
        "background",
        "content",
        "action_name",
        "action",
        "action_params",
        "created_at",
        "created_by",
        "updated_at",
        "updated_by"
    ];

    /**
     * Thêm notification detail
     *
     * @param array $data
     * @return mixed
     */
    public function add(array $data)
    {
        $add = $this->create($data);
        return $add->notification_detail_id;
    }
}