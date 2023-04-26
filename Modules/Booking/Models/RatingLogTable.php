<?php
/**
 * Created by PhpStorm
 * User: Mr Son
 * Date: 4/23/2020
 * Time: 2:16 PM
 */

namespace Modules\Booking\Models;


use Illuminate\Database\Eloquent\Model;

class RatingLogTable extends Model
{
    protected $table = "rating_log";
    protected $primaryKey = "id";

    const IS_SHOW = 1;

    /**
     * Lấy thông tin đánh giá của user
     *
     * @param $object
     * @param $objectValue
     * @param $customerId
     * @return mixed
     */
    public function getLogByUser($object, $objectValue, $customerId)
    {
        return $this
            ->select(
                "id",
                "object",
                "object_value",
                "rating_by",
                "rating_value",
                "comment"
            )
            ->where("object", $object)
            ->where("object_value", $objectValue)
            ->where("rating_by", $customerId)
            ->where("is_show", self::IS_SHOW)
            ->first();
    }
}