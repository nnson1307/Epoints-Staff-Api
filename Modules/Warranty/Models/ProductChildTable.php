<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 27/09/2022
 * Time: 10:17
 */

namespace Modules\Warranty\Models;


use Illuminate\Database\Eloquent\Model;

class ProductChildTable extends Model
{
    protected $table = 'product_childs';
    protected $primaryKey = 'product_child_id';

    /**
     * Lấy thông tin sản phẩm
     *
     * @param $productCode
     * @return mixed
     */
    public function getProduct($productCode)
    {
        return $this
            ->select(
                "product_child_id",
                "product_child_name",
                "price"
            )
            ->where("product_code", $productCode)
            ->first();
    }
}