<?php


namespace Modules\Promotion\Models;


use Illuminate\Database\Eloquent\Model;

class ProductImageTable extends Model
{
    protected $table = "product_images";
    protected $primaryKey = "product_image_id";

    /**
     * Lấy hình ảnh kèm theo của product
     *
     * @param $productId
     * @return mixed
     */
    public function getProductImage($productId)
    {
        return $this
            ->select(
                "product_image_id",
                "name as image"
            )
            ->where("product_id", $productId)
            ->get();
    }

    /**
     * Get image child
     *
     * @param $productCode
     * @return mixed
     */
    public function getImageChild($productCode)
    {
        return $this
            ->select(
                "product_image_id",
                "product_id",
                "name as image"
            )
            ->where("product_child_code", $productCode)
            ->where("is_avatar", 1)
            ->first();
    }
}