<?php

namespace Modules\Notification\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/**
 * Class NotificationTable
 * @package Modules\Store\Models
 * @author BangNB
 * @since Sep, 2019
 */
class NotificationTable extends Model
{
    protected $table = "notification";
    protected $primaryKey = "notification_id";
    protected $fillable = [
        "notification_id",
        "notification_detail_id",
        "user_id",
        "notification_avatar",
        "notification_title",
        "notification_message",
        "is_read"
    ];

    const IS_NEW = 0;
    const IS_OLD = 1;
    const NOT_READ = 0;
    const IS_READ = 1;

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        "updated_at", "created_at"
    ];


    /**
     * Lấy danh sách thông báo
     *
     * @param $filter
     * @param $idUser
     * @return mixed
     */
    public function getNotifications($filter, $idUser)
    {
        $oSelect = $this->select(
            "{$this->table}.notification_id",
            "{$this->table}.notification_title as title",
            "{$this->table}.notification_message as description",
            "{$this->table}.notification_detail_id",
            "{$this->table}.is_read",
            "notification_detail.action_name",
            "notification_detail.action",
            "{$this->table}.created_at as created_date"
        )
            ->join("notification_detail", "notification_detail.notification_detail_id", "=", "notification.notification_detail_id")
            ->where("user_id", $idUser)
            ->orderBy("{$this->table}.created_at", "desc");
        // get số trang
        $page = (int)($filter["page"] ?? 1);

        // filter theo is_read
        if (isset($filter["is_read"])) {
            $oSelect->where("is_read", $filter["is_read"]);
        }

        return $oSelect->paginate(PAGING_ITEM_PER_PAGE, $columns = ["*"], $pageName = "page", $page);
    }

    /**
     * Lấy thông báo theo user_id và notifcation_id
     * @param $idNotification
     * @param $idUser
     * @return array
     */
    public function getNotificationById($idNotification, $idUser)
    {
        return $this->where($this->primaryKey, $idNotification)
            ->where("user_id", $idUser)
            ->first();
    }

    /**
     * Xóa thông báo theo id và user_id
     *
     * @param $idNotification
     * @param $idUser
     * @return mixed
     */
    public function deleteNotificationById($idNotification, $idUser)
    {
        return $this->where($this->primaryKey, $idNotification)
            ->where("user_id", $idUser)
            ->delete();
    }

    /**
     * Xóa thông báo theo user
     *
     * @param $idUser
     * @param $idBrand
     * @return integer
     */
    public function deleteNotificationByUser($idUser, $idBrand)
    {
        $select = $this->where("user_id", $idUser);

        if ($idBrand > 0) {
            $select->where("brand_id", $idBrand);
        }
        return $select->delete();
    }

    /**
     * Lấy danh sách id notification theo user
     *
     * @param $idUser
     * @param $isBrand
     * @return array
     */
    public function getNotificationDetailIdByUser($idUser, $isBrand)
    {
        return $this->select("notification_detail_id")
            ->where("user_id", $idUser)
            ->where("is_brand", $isBrand)
            ->get();
    }

    /**
     * Cập nhật trạng thái đã đọc thông báo
     * @param $idNotification
     * @param $idUser
     * @return integer
     */
    public function updateNotificationRead($idNotification, $idUser)
    {
        return $this->where($this->primaryKey, $idNotification)
            ->where("user_id", $idUser)
            ->update(["is_read" => 1]);
    }

    /**
     * Chi tiết thông báo
     *
     * @param $idNotification
     * @param $idUser
     * @return mixed
     */
    public function getNotificationDetail($idNotification, $idUser)
    {
        return $this->select(
            "{$this->table}.notification_detail_id",
            "background",
            "content",
            "action_name",
            "action",
            "action_params",
            "notification_title"
        )
            ->join("notification_detail", "{$this->table}.notification_detail_id", "=", "notification_detail.notification_detail_id")
            ->where("user_id", $idUser)
            ->where("notification_id", $idNotification)
            ->first();
    }

    /**
     * Thêm thông báo
     *
     * @param array $data
     * @return mixed
     */
    public function add(array $data)
    {
        $add = $this->create($data);
        return $add->notification_id;
    }

    /**
     * Đếm số lượng thông báo mới
     *
     * @param $userId
     * @return mixed
     */
    public function countNotification($userId)
    {
        return $this
            ->select(
                "notification_id",
                "user_id",
                "is_read"
            )
            ->where("user_id", $userId)
            ->where("is_new", self::IS_NEW)
            ->get()
            ->count();
    }

    /**
     * Cập nhật tất cả thông báo mới thành cũ khi click vào chuông thông báo
     *
     * @param $userId
     * @return mixed
     */
    public function clearNotificationNew($userId)
    {
        return $this
            ->where("is_new", self::IS_NEW)
            ->where("user_id", $userId)
            ->update(["is_new" => self::IS_OLD]);
    }

    /**
     * Đọc tất cả thông báo
     *
     * @param $userId
     * @return mixed
     */
    public function readAllNotification($userId)
    {
        return $this
            ->where("is_read", self::NOT_READ)
            ->where("user_id", $userId)
            ->update(["is_read" => self::IS_READ]);
    }

}
