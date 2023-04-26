<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 24/08/2022
 * Time: 14:42
 */

namespace Modules\Booking\Models;


use Illuminate\Database\Eloquent\Model;

class RoomTable extends Model
{
    protected $table = "rooms";
    protected $primaryKey = "room_id";

    const IS_ACTIVE = 1;
    const NOT_DELETED = 0;

    /**
     * Lấy option phòng phục vụ
     *
     * @return mixed
     */
    public function optionRoom()
    {
        return $this
            ->select(
                "room_id",
                "name",
                "seat"
            )
            ->where("is_actived", self::IS_ACTIVE)
            ->where("is_deleted", self::NOT_DELETED)
            ->get();
    }
}