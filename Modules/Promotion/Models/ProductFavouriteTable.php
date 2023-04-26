<?php


namespace Modules\Promotion\Models;


use Illuminate\Database\Eloquent\Model;

class ProductFavouriteTable extends Model
{
    protected $table = "product_favourite";
    protected $primaryKey = "id";
    protected $fillable = [
        "id",
        "product_id",
        "user_id",
        "created_at",
        "updated_at"
    ];

    const IS_ACTIVE = 1;
    const NOT_DELETE = 0;

    /**
     * Lấy thông tin tất cã sản phẩm đã like của user
     *
     * @param $userId
     * @return mixed
     */
    public function getLikeAll($userId)
    {
        return $this
            ->select(
                "id",
                "product_id",
                "user_id"
            )
            ->where("user_id", $userId)
            ->get();
    }

    /**
     * Kiểm tra sản phẩm đã like chưa
     *
     * @param $productId
     * @param $userId
     * @return mixed
     */
    public function checkFavourite($productId, $userId)
    {
        return $this
            ->where("product_id", $productId)
            ->where("user_id", $userId)
            ->first();
    }
}