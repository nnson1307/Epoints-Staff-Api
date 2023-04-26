<?php
/**
 * Created by PhpStorm
 * User: Mr Son
 * Date: 9/15/2020
 * Time: 1:53 PM
 */

namespace Modules\Order\Models;


use Illuminate\Database\Eloquent\Model;

class ProductImageTable extends Model
{
    protected $table = "product_images";
    protected $primaryKey = "product_image_id";

    const IS_AVATAR = 1;

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
                "{$this->table}.product_image_id",
                "{$this->table}.product_id",
                "{$this->table}.name as image",
                "product_childs.product_child_name"
            )
            ->join("product_childs", "product_childs.product_code", "=", "{$this->table}.product_child_code")
            ->where("{$this->table}.product_child_code", $productCode)
            ->where("{$this->table}.is_avatar", self::IS_AVATAR)
            ->first();
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