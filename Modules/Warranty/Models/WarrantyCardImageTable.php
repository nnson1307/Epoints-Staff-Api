<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 27/09/2022
 * Time: 11:39
 */

namespace Modules\Warranty\Models;


use Illuminate\Database\Eloquent\Model;

class WarrantyCardImageTable extends Model
{
    protected $table = "warranty_images";
    protected $primaryKey = "warranty_image_id";

    const NOT_DELETED = 0;

    /**
     * Lấy hình ảnh thẻ bảo hành
     *
     * @param $warrantyCardCode
     * @return mixed
     */
    public function getImage($warrantyCardCode)
    {
        return $this
            ->select(
                "warranty_image_id",
                "warranty_card_code",
                "link"
            )
            ->where("warranty_card_code", $warrantyCardCode)
            ->where("is_deleted", self::NOT_DELETED)
            ->get();
    }

    /**
     * Xoá hình ảnh thẻ bảo hành
     *
     * @param $warrantyCardCode
     * @return mixed
     */
    public function removeImage($warrantyCardCode)
    {
        return $this->where("warranty_card_code", $warrantyCardCode)->delete();
    }
}