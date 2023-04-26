<?php
/**
 * Created by PhpStorm
 * User: Mr Son
 * Date: 14-04-02020
 * Time: 1:43 PM
 */

namespace Modules\Notification\Models;


use Illuminate\Database\Eloquent\Model;

class ConfigNotificationTable extends Model
{
    protected $table = "config_notification";
//    protected $primaryKey = "key";

    const IS_ACTIVE = 1;

    /**
     * Lấy thông tin config notification
     *
     * @param $key
     * @return mixed
     */
    public function getInfo($key)
    {
        return $this
            ->select(
                "{$this->table}.key",
                "{$this->table}.name",
                "{$this->table}.config_notification_group_id",
                "{$this->table}.send_type",
                "{$this->table}.schedule_unit",
                "{$this->table}.value",
                "notification_template_auto.title",
                "notification_template_auto.message",
                "notification_template_auto.avatar",
                "notification_template_auto.has_detail",
                "notification_template_auto.detail_background",
                "notification_template_auto.detail_content",
                "notification_template_auto.detail_action_name",
                "notification_template_auto.detail_action",
                "notification_template_auto.detail_action_params"
            )
            ->join("notification_template_auto", "notification_template_auto.key", "=", "{$this->table}.key")
            ->where("{$this->table}.key", $key)
            ->where("{$this->table}.is_active", self::IS_ACTIVE)
            ->first();
    }
}