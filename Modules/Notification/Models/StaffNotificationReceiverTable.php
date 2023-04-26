<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 03/08/2022
 * Time: 10:32
 */

namespace Modules\Notification\Models;


use Illuminate\Database\Eloquent\Model;

class StaffNotificationReceiverTable extends Model
{
    protected $table = "staff_notification_receiver";
    protected $primaryKey = "staff_notification_receiver_id";

    /**
     * Lấy thông tin người nhận
     *
     * @param $notificationKey
     * @return mixed
     */
    public function getReceiverByKey($notificationKey)
    {
        return $this->where("staff_notification_key", $notificationKey)->get();
    }
}