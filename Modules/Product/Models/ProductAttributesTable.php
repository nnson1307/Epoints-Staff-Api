<?php
/**
 * Created by PhpStorm
 * User: Mr Son
 * Date: 6/15/2020
 * Time: 11:08 AM
 */

namespace Modules\Product\Models;

use Illuminate\Database\Eloquent\Model;

class ProductAttributesTable extends Model
{
    const NOT_DELETE = 0;
    const IS_ACTIVE = 1;
    protected $table = "product_attributes";
    protected $primaryKey = "product_attribute_id";
    protected $fillable = [
        "product_attribute_id",
        "product_attribute_label",
        "product_attribute_code",
        "is_deleted",
        "is_actived"
    ];
    /**
     * Get Product
     *
     * @param
     * @return mixed
     */
    public function getAll()
    {
        $data =  $this
            ->select(
                "{$this->table}.product_attribute_id",
                "{$this->table}.product_attribute_label",
                "{$this->table}.product_attribute_code"
            )
            ->where("{$this->table}.is_actived", self::IS_ACTIVE)
            ->where("{$this->table}.is_deleted",self::NOT_DELETE)
            ->get();
        return $data;
    }



}