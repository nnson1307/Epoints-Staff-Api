<?php
/**
 * Created by PhpStorm
 * User: Mr Son
 * Date: 4/22/2020
 * Time: 4:32 PM
 */

namespace Modules\Order\Models;


use Illuminate\Database\Eloquent\Model;

class OrderLogTable extends Model
{
    protected $table = "order_log";
    protected $primaryKey = "id";
    protected $fillable = [
        "id",
        "order_id",
        "created_type",
        "type",
        "status",
        "note",
        "created_by",
        "created_at",
        "updated_at",
        "note_vi",
        "note_en"
    ];

    /**
     * Thêm order log
     *
     * @param array $data
     * @return mixed
     */
    public function add(array $data)
    {
        return $this->create($data);
    }

    /**
     * Lấy thông tin log đơn hàng
     *
     * @param $orderId
     * @param $lang
     * @return mixed
     */
    public function getLog($orderId, $lang)
    {
        return $this
            ->select(
                "id",
                "status",
                "created_at",
                "note_$lang as note"
            )
            ->orderBy("id", "desc")
            ->where("order_id", $orderId)
//            ->where("type", "status")
            ->get();
    }

    /**
     * Lấy order log
     *
     * @param $orderId
     * @param $status
     * @return mixed
     */
    public function checkStatusLog($orderId, $status)
    {
        return $this
            ->select(
                "order_id",
                "status"
//                "note"
            )
            ->where("order_id", $orderId)
            ->where("status", $status)
            ->first();
    }

}