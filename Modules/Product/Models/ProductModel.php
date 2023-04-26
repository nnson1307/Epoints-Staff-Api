<?php
/**
 * Created by PhpStorm
 * User: Mr Son
 * Date: 6/15/2020
 * Time: 11:08 AM
 */

namespace Modules\Product\Models;

use Illuminate\Database\Eloquent\Model;

class ProductModel extends Model
{
    protected $table = "product_model";
    protected $primaryKey = "product_model_id";
    protected $fillable = [
        "product_model_id",
        "product_model_name",
    ];
    /**
     * Get Product
     *
     * @param $productId
     * @return mixed
     */
    public function getByID($productId)
    {
        $data =  $this
            ->select(
                "{$this->table}.product_model_id",
                "{$this->table}.product_model_name"
            )
            ->where("product_model_id", $productId)
            ->first();
        return $data;
    }



}