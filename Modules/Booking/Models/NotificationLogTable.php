<?php
/**
 * Created by PhpStorm
 * User: Mr Son
 * Date: 07-04-02020
 * Time: 11:06 AM
 */

namespace Modules\Booking\Models;


use Illuminate\Database\Eloquent\Model;

class NotificationLogTable extends Model
{
    protected $table = "spa_notification_log";
    protected $primaryKey = "notification_id";
    protected $fillable = [
        "notification_id",
        "notification_detail_id",
        "user_id",
        "notification_avatar",
        "notification_title",
        "notification_message",
        "is_read",
        "created_at",
        "updated_at"
    ];

    /**
     * Thêm notification log
     *
     * @param array $data
     * @return mixed
     */
    public function add(array $data)
    {
        $add = $this->create($data);
        return $add->notification_id;
    }
}