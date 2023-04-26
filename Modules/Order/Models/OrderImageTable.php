<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 08/05/2021
 * Time: 11:54
 */

namespace Modules\Order\Models;


use Illuminate\Database\Eloquent\Model;

class OrderImageTable extends Model
{
    protected $table = "order_images";
    protected $primaryKey = "order_image_id";
    protected $fillable = [
        "order_image_id",
        "order_code",
        "type",
        "link",
        "created_by",
        "updated_by",
        "created_at",
        "updated_at"
    ];

    /**
     * Lấy hình ảnh sau khi sử dụng
     *
     * @param $orderCode
     * @param $type
     * @return mixed
     */
    public function getOrderImage($orderCode, $type = null)
    {
        $ds = $this
            ->select(
                "order_image_id",
                "type",
                "link"
            )
            ->where("order_code", $orderCode);

        if($type != null) {
            $ds->where("type", $type);
        }

        return $ds->get();
    }

    /**
     * Xoá hình ảnh sau khi sử dụng
     *
     * @param $orderCode
     * @param $type
     * @return mixed
     */
    public function removeOrderImage($orderCode, $type)
    {
        return $this
            ->where("order_code", $orderCode)
            ->where("type", $type)
            ->delete();
    }

    /**
     * Thêm hình ảnh đơn hàng
     *
     * @param array $data
     * @return mixed
     */
    public function add(array $data)
    {
        return $this->create($data)->order_image_id;
    }

    /**
     * Xoá hình ảnh trước/sau bằng array id
     *
     * @param $arrImageId
     * @return mixed
     */
    public function removeImageById($arrImageId)
    {
        return $this->whereIn("order_image_id", $arrImageId)->delete();
    }
}