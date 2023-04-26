<?php
/**
 * Created by PhpStorm
 * User: Mr Son
 * Date: 4/23/2020
 * Time: 2:16 PM
 */

namespace Modules\Product\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

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

    /**
     * Đếm số lượng đã đánh giá trên từng value
     *
     * @param $object
     * @param $objectValue
     * @return mixed
     */
    public function countRating($object, $objectValue)
    {
        return $this
            ->where("object", $object)
            ->where("object_value", $objectValue)
            ->where("is_show", self::IS_SHOW)
            ->get()
            ->count();
    }

    /**
     * Điểm đánh giá trung bình trên từng value
     *
     * @param $object
     * @param $objectValue
     * @return mixed
     */
    public function avgRating($object, $objectValue)
    {
        return $this
            ->select(
                "object_value",
                DB::raw('AVG(rating_value) as rating_avg')
            )
            ->where("object", $object)
            ->where("object_value", $objectValue)
            ->where("is_show", self::IS_SHOW)
            ->groupBy('object_value')
            ->first();
    }

    /**
     * Lay danh sach cac bai danh gia
     *
     * @param $object
     * @param $objectValue
     * @return mixed
     */
    public function getListRating($object, $objectValue)
    {
        return $this->select(
            "{$this->table}.*",
            "customers.full_name"
        )
            ->leftJoin("customers","{$this->table}.rating_by", "=", "customers.customer_id")
            ->where("object", $object)
            ->where("object_value", $objectValue)
            ->where("{$this->table}.is_show", self::IS_SHOW)
            ->orderBy("{$this->table}.id", "desc")
            ->get();

    }

    public function getListTotalRating($object, $objectValue)
    {
        return $this->select(
            "{$this->table}.rating_value",
            DB::raw('COUNT(rating_log.rating_value) as amount')
        )
            ->join("rating_log as rl2","rl2.rating_value", "=", "{$this->table}.rating_value")
            ->where("{$this->table}.id", DB::raw('rl2.id'))
            ->where("{$this->table}.object", $object)
            ->where("{$this->table}.object_value", $objectValue)
            ->where("{$this->table}.is_show", self::IS_SHOW)
            ->groupBy("{$this->table}.rating_value")
            ->get();
    }

}