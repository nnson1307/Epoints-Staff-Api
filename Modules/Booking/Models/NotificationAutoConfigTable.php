<?php
/**
 * Created by PhpStorm
 * User: Mr Son
 * Date: 07-04-02020
 * Time: 11:26 AM
 */

namespace Modules\Booking\Models;


use Illuminate\Database\Eloquent\Model;

class NotificationAutoConfigTable extends Model
{
    protected $table = "spa_notification_auto_config";
    protected $primaryKey = "id";
    protected $fillable = [
        "id",
        "notification_auto_group_id",
        "key",
        "value",
        "time_sent",
        "name",
        "content",
        "is_active",
        "created_by",
        "updated_by",
        "created_at",
        "updated_at",
        "actived_by",
        "datetime_actived"
    ];

    const IS_ACTIVE = 1;

    /**
     * Lấy thông tin notification config
     *
     * @param $key
     * @return mixed
     */
    public function getInfo($key)
    {
        return $this
            ->select(
                "spa_notification_auto_group.notification_auto_group_id",
                "spa_notification_auto_group.notification_auto_group_name",
                "{$this->table}.key",
                "{$this->table}.value",
                "{$this->table}.time_sent",
                "{$this->table}.name",
                "{$this->table}.content",
                "{$this->table}.is_active"
            )
            ->join("spa_notification_auto_group", "spa_notification_auto_group.notification_auto_group_id", "=", "{$this->table}.notification_auto_group_id")
            ->where("{$this->table}.key", $key)
            ->where("{$this->table}.is_active", self::IS_ACTIVE)
            ->first();
    }
}