<?php
/**
 * Created by PhpStorm
 * User: Mr Son
 * Date: 9/14/2020
 * Time: 7:29 PM
 */

namespace Modules\Home\Models;


use Illuminate\Database\Eloquent\Model;

class ProductImageTable extends Model
{
    protected $table = "product_images";
    protected $primaryKey = "product_image_id";

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
            ->get();
    }

    /**
     * Lấy hình ảnh đại diện của sản phẩm con
     *
     * @param $productCode
     * @return mixed
     */
    public function getAvatar($productCode)
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