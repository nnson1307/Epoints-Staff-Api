<?php
namespace Modules\Notification\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class NotificationDetailTable
 * @package Modules\Store\Models
 * @author BangNB
 * @since Sep, 2019
 */
class NotificationDetailTable extends Model
{
    protected $table = "notification_detail";
    protected $primaryKey = "notification_detail_id";
    protected $fillable = [
        "notification_detail_id",
        "background",
        "content",
        "action_name",
        "action",
        "action_params",
        "created_by",
        "updated_by"
    ];
    
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        "created_at","created_by","updated_by","updated_at"
    ];


    /**
     * Lấy chi tiết thông báo
     *
     * @param $idNotificationDetail
     * @return mixed
     */
    public function getNotificationDetailById($idNotificationDetail)
    {
        return $this->where($this->primaryKey, $idNotificationDetail)
                    ->first();
    }

    /**
     * Xóa chi tiết thông báo
     *
     * @param $idNotificationDetail
     * @return mixed
     */
    public function deleteNotificationDetailById($idNotificationDetail)
    {
        return $this->where($this->primaryKey, $idNotificationDetail)
                    ->delete();
    }

    /**
     * Xóa chi tiết thông báo theo danh sách notification
     *
     * @param $arrNotificationId
     * @return mixed
     */
    public function deleteNotificationByNotificationList($arrNotificationId)
    {
        return $this->whereIn($this->primaryKey, $arrNotificationId)
                    ->delete();
    }

    /**
     * Thêm chi tiết thông báo
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
